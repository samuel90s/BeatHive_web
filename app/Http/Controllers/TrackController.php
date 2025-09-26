<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TrackController extends Controller
{
    /**
     * List tracks + filter ringan:
     * - ?q=keyword (title/artist/tags)
     * - ?genre=ID
     * - ?published=1|0
     * - ?per_page=50 (10-200)
     */
    public function index(Request $request)
    {
        $q         = (string) $request->query('q', '');
        $genreId   = $request->integer('genre');
        $published = $request->has('published') ? (int) $request->query('published') : null;
        $perPage   = max(10, min(200, (int) $request->query('per_page', 50)));

        $tracks = Track::with('genre')
            ->when($q !== '', function ($s) use ($q) {
                $s->where(function ($w) use ($q) {
                    $w->where('title', 'like', "%{$q}%")
                      ->orWhere('artist', 'like', "%{$q}%")
                      ->orWhere('tags', 'like', "%{$q}%");
                });
            })
            ->when($genreId, fn ($s) => $s->where('genre_id', $genreId))
            ->when(!is_null($published), fn ($s) => $s->where('is_published', $published))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $genres = Genre::orderBy('name')->get();

        return view('tracks.index', compact('tracks', 'genres', 'q', 'genreId', 'published', 'perPage'));
    }

    /** Form create */
    public function create()
    {
        $genres = Genre::orderBy('name')->get();
        return view('tracks.create', compact('genres'));
    }

    /** Simpan data baru */
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['slug']         = $this->makeUniqueSlug($data['title']);
        $data['is_published'] = $request->boolean('is_published');

        DB::beginTransaction();
        try {
            // Upload file (public disk)
            if ($request->hasFile('cover_path')) {
                $data['cover_path'] = $request->file('cover_path')->store('covers', 'public');
            }
            if ($request->hasFile('preview_path')) {
                $data['preview_path'] = $request->file('preview_path')->store('previews', 'public');
            }
            if ($request->hasFile('bundle_path')) {
                $data['bundle_path'] = $request->file('bundle_path')->store('bundles', 'public');
            }

            Track::create($data);

            DB::commit();
            return redirect()->route('tracks.index')->with('success', 'Track berhasil ditambahkan');
        } catch (\Throwable $e) {
            DB::rollBack();
            // cleanup file jika ada
            foreach (['cover_path', 'preview_path', 'bundle_path'] as $col) {
                if (!empty($data[$col])) {
                    Storage::disk('public')->delete($data[$col]);
                }
            }
            throw $e;
        }
    }

    /** Detail track */
    public function show(Track $track)
    {
        $track->load('genre');
        return view('tracks.show', compact('track'));
    }

    /** Form edit */
    public function edit(Track $track)
    {
        $track->load('genre');
        $genres = Genre::orderBy('name')->get();
        return view('tracks.edit', compact('track', 'genres'));
    }

    /** Update data */
    public function update(Request $request, Track $track)
    {
        $data = $this->validateData($request, isUpdate: true);
        $data['is_published'] = $request->boolean('is_published');

        DB::beginTransaction();
        try {
            // (opsional) ganti slug saat title berubah:
            // if (!empty($data['title']) && $data['title'] !== $track->title) {
            //     $data['slug'] = $this->makeUniqueSlug($data['title']);
            // }

            // Replace file lama jika upload baru
            if ($request->hasFile('cover_path')) {
                if ($track->cover_path) Storage::disk('public')->delete($track->cover_path);
                $data['cover_path'] = $request->file('cover_path')->store('covers', 'public');
            }
            if ($request->hasFile('preview_path')) {
                if ($track->preview_path) Storage::disk('public')->delete($track->preview_path);
                $data['preview_path'] = $request->file('preview_path')->store('previews', 'public');
            }
            if ($request->hasFile('bundle_path')) {
                if ($track->bundle_path) Storage::disk('public')->delete($track->bundle_path);
                $data['bundle_path'] = $request->file('bundle_path')->store('bundles', 'public');
            }

            $track->update($data);

            DB::commit();
            return redirect()->route('tracks.index')->with('success', 'Track berhasil diperbarui');
        } catch (\Throwable $e) {
            DB::rollBack();
            // kalau file baru sudah tersimpan tapi gagal update, hapus file baru
            foreach (['cover_path', 'preview_path', 'bundle_path'] as $col) {
                if (isset($data[$col]) && $data[$col] !== $track->getOriginal($col)) {
                    Storage::disk('public')->delete($data[$col]);
                }
            }
            throw $e;
        }
    }

    /** Hapus data */
    public function destroy(Track $track)
    {
        DB::beginTransaction();
        try {
            foreach (['cover_path', 'preview_path', 'bundle_path'] as $col) {
                if ($track->$col) {
                    Storage::disk('public')->delete($track->$col);
                }
            }
            $track->delete();

            DB::commit();
            return redirect()->route('tracks.index')->with('success', 'Track berhasil dihapus');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /** JSON quick search: GET /tracks/search/json?q=... */
    public function search(Request $request)
    {
        $q = (string) $request->query('q', '');
        $rows = Track::query()
            ->when($q !== '', function ($s) use ($q) {
                $s->where(function ($w) use ($q) {
                    $w->where('title', 'like', "%{$q}%")
                      ->orWhere('artist', 'like', "%{$q}%")
                      ->orWhere('tags', 'like', "%{$q}%");
                });
            })
            ->orderBy('title')
            ->limit(20)
            ->get(['id', 'title', 'artist', 'slug']);

        return response()->json([
            'data' => $rows->map(fn ($r) => [
                'id'     => $r->id,
                'title'  => $r->title,
                'artist' => $r->artist,
                'slug'   => $r->slug,
                'text'   => "{$r->title} — {$r->artist}",
            ]),
        ]);
    }

    /** Halaman bulk import */
    public function bulkImport()
    {
        return view('tracks.bulk-import');
    }

    /**
     * Proses bulk import CSV
     * Header minimal: title, artist, genre_id, price_idr
     * Urutan kolom bebas (dibaca via header)
     */
    public function bulkImportStore(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:20480'],
        ]);

        $path = $request->file('file')->store('temp', 'local');
        $full = storage_path("app/{$path}");

        $handle = fopen($full, 'r');
        if (!$handle) {
            return back()->with('error', 'Gagal membuka file.');
        }

        $success = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            // Baca header
            $header = fgetcsv($handle);
            if (!$header) {
                fclose($handle);
                throw new \RuntimeException('Header CSV tidak ditemukan.');
            }
            $header = array_map(fn ($h) => Str::slug(trim($h), '_'), $header);

            // Posisi kolom (case-insensitive)
            $col = [
                'title'     => array_search('title', $header, true),
                'artist'    => array_search('artist', $header, true),
                'genre_id'  => array_search('genre_id', $header, true),
                'price_idr' => array_search('price_idr', $header, true),
            ];

            if ($col['title'] === false || $col['artist'] === false || $col['genre_id'] === false) {
                fclose($handle);
                throw new \RuntimeException('Kolom wajib: title, artist, genre_id. (price_idr opsional)');
            }

            while (($row = fgetcsv($handle)) !== false) {
                // trim semua kolom
                $row = array_map(fn ($v) => is_string($v) ? trim($v) : $v, $row);

                $title    = $row[$col['title']] ?? null;
                $artist   = $row[$col['artist']] ?? null;
                $genreId  = $row[$col['genre_id']] ?? null;
                $priceIdr = $col['price_idr'] !== false ? ($row[$col['price_idr']] ?? null) : null;

                if (!$title || !$artist || !$genreId) {
                    $skipped++;
                    continue;
                }

                Track::create([
                    'title'        => $title,
                    'artist'       => $artist,
                    'genre_id'     => (int) $genreId,
                    'price_idr'    => (int) ($priceIdr ?? 0),
                    'slug'         => $this->makeUniqueSlug($title),
                    'is_published' => false,
                ]);

                $success++;
            }

            fclose($handle);
            DB::commit();
        } catch (\Throwable $e) {
            if (is_resource($handle)) fclose($handle);
            DB::rollBack();
            @unlink($full);
            throw $e;
        }

        @unlink($full);

        $msg = "Bulk import selesai: {$success} baris sukses";
        if ($skipped > 0) $msg .= ", {$skipped} baris diskip";
        $msg .= '.';

        return redirect()->route('tracks.index')->with('success', $msg);
    }

    /** Toggle publish cepat (support AJAX) */
    public function togglePublish(Track $track)
    {
        $track->is_published = !$track->is_published;
        $track->save();

        if (request()->wantsJson()) {
            return response()->json([
                'id'           => $track->id,
                'is_published' => (bool) $track->is_published,
                'message'      => $track->is_published ? 'Track dipublish.' : 'Track di-unpublish.',
            ]);
        }

        return back()->with('success', $track->is_published ? 'Track dipublish.' : 'Track di-unpublish.');
    }

    /** Rules validasi untuk store & update */
    private function validateData(Request $request, bool $isUpdate = false): array
    {
        return $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'artist'           => ['required', 'string', 'max:255'],
            'genre_id'         => ['required', Rule::exists('genres', 'id')],
            'bpm'              => ['nullable', 'integer', 'min:40', 'max:220'],
            'musical_key'      => ['nullable', 'string', 'max:8'],
            'mood'             => ['nullable', 'string', 'max:100'],
            'tags'             => ['nullable', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'price_idr'        => ['nullable', 'integer', 'min:0'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'release_date'     => ['nullable', 'date'],
            'is_published'     => ['nullable', 'boolean'],

            // files
            'cover_path'   => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],   // 2MB
            'preview_path' => ['nullable', 'mimetypes:audio/mpeg', 'max:10240'],         // 10MB mp3
            'bundle_path'  => ['nullable', 'mimes:wav,zip', 'max:204800'],               // 200MB
        ]);
    }

    /** Generator slug unik */
    private function makeUniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'track';
        $slug = $base;

        $tries = 0;
        while (Track::where('slug', $slug)->exists()) {
            $suffix = substr(Str::random(6), 0, 6);
            $slug = "{$base}-{$suffix}";
            if (++$tries > 10) {
                $slug = "{$base}-".Str::random(8);
                break;
            }
        }

        return $slug;
    }
}
