<?php

namespace App\Http\Controllers;

use App\Models\SoundEffect;
use App\Models\SoundCategory;
use App\Models\SoundSubcategory;
use App\Models\SoundTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class SoundEffectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(SoundEffect::class, 'sound_effect');
    }

    /**
     * Halaman GRID kategori (landing).
     */
    public function index()
    {
        $categories = SoundCategory::query()
            ->withCount('soundEffects')
            ->orderBy('name')
            ->get();

        $subGroups = SoundSubcategory::query()
            ->with('category:id,name')
            ->orderBy('name')
            ->get()
            ->groupBy('category_id');

        return view('sound_effects.index', [
            'categories' => $categories,
            'subGroups'  => $subGroups,
            'q'          => '',
        ]);
    }

    /**
     * Halaman LIST SFX (yang lama, ada player).
     */
    public function list()
    {
        $sounds = SoundEffect::with(['author', 'category', 'subcategory', 'tags'])
            ->latest()
            ->paginate(10);

        return view('sound_effects.list', compact('sounds'));
    }

    /**
     * Form tambah Sound Effect.
     */
    public function create()
    {
        $categories    = SoundCategory::orderBy('name')->get();
        $subcategories = SoundSubcategory::orderBy('name')->get();
        $tags          = SoundTag::orderBy('name')->get();

        return view('sound_effects.create', compact('categories', 'subcategories', 'tags'));
    }

    /**
     * Simpan Sound Effect baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'file'           => 'required|mimes:wav,mp3,ogg,flac,aiff|max:51200',
            'category_id'    => 'nullable|exists:sound_categories,id',
            'subcategory_id' => 'nullable|exists:sound_subcategories,id',
            'tags'           => 'nullable|array',
            'tags.*'         => 'exists:sound_tags,id',
        ]);

        // Validasi: subcategory harus milik category yang dipilih (kalau dua-duanya diisi)
        if ($request->filled('subcategory_id') && $request->filled('category_id')) {
            $sub = SoundSubcategory::where('id', $request->subcategory_id)
                ->where('category_id', $request->category_id)
                ->first();

            if (!$sub) {
                return back()
                    ->withErrors(['subcategory_id' => 'Subcategory does not belong to selected category.'])
                    ->withInput();
            }
        }

        // Pastikan symlink sekali jalan
        if (!is_link(public_path('storage'))) {
            try {
                Artisan::call('storage:link');
            } catch (\Throwable $e) {
                // silently ignore
            }
        }

        $file = $request->file('file');
        $ext  = strtolower($file->getClientOriginalExtension());
        $slug = SoundEffect::generateSlug($request->title);

        // Struktur folder (tanpa 'public/' prefix; pakai disk('public'))
        $datePath = date('Y/m');
        $dirOrig  = "sound_effects/original/{$datePath}";
        $dirPrev  = "sound_effects/preview/{$datePath}";
        $dirWave  = "sound_effects/waveform/{$datePath}";

        // Buat folder otomatis di disk public
        Storage::disk('public')->makeDirectory($dirOrig);
        Storage::disk('public')->makeDirectory($dirPrev);
        Storage::disk('public')->makeDirectory($dirWave);

        // Simpan file asli
        $fileName   = "{$slug}.{$ext}";
        $storedPath = Storage::disk('public')->putFileAs($dirOrig, $file, $fileName);
        // contoh: "sound_effects/original/2025/10/slug.mp3"

        $absolute = Storage::disk('public')->path($storedPath);
        if (!is_file($absolute)) {
            clearstatcache();
            usleep(400000); // 0.4s; flush I/O Windows (khusus Windows)
        }
        if (!is_file($absolute)) {
            return back()->withErrors([
                'file' => 'File gagal disimpan ke storage. Periksa permission folder storage.',
            ]);
        }

        // ffprobe / ffmpeg
        $hasFfprobe = (bool) shell_exec('ffprobe -version 2>NUL || echo 0');
        $hasFfmpeg  = (bool) shell_exec('ffmpeg -version 2>NUL || echo 0');

        $duration   = 0.0;
        $bitrate    = 0;
        $sampleRate = 0;
        $channels   = 0;
        $bitDepth   = 0;

        if ($hasFfprobe) {
            $cmd  = 'ffprobe -v quiet -print_format json -show_format -show_streams ' . escapeshellarg($absolute);
            $json = shell_exec($cmd);
            $meta = json_decode($json ?? '[]', true);

            $fmt = $meta['format']     ?? [];
            $st0 = $meta['streams'][0] ?? [];

            $duration   = (float) ($fmt['duration'] ?? 0);
            $bitrate    = (int) round(($fmt['bit_rate'] ?? 0) / 1000);
            $sampleRate = (int) ($st0['sample_rate'] ?? 0);
            $channels   = (int) ($st0['channels'] ?? 0);
            $bitDepth   = (int) ($st0['bits_per_sample'] ?? 0);
        }

        $sizeBytes = filesize($absolute);

        // Generate preview & waveform
        $previewName = "{$slug}_preview.mp3";
        $waveName    = "{$slug}_waveform.png";

        $previewRel = "{$dirPrev}/{$previewName}";
        $waveRel    = "{$dirWave}/{$waveName}";

        $previewAbs = Storage::disk('public')->path($previewRel);
        $waveAbs    = Storage::disk('public')->path($waveRel);

        if ($hasFfmpeg) {
            $endFade = max(0, $duration - 0.2);

            $cmdPrev = 'ffmpeg -y -i ' . escapeshellarg($absolute)
                . ' -af "dynaudnorm=f=75,afade=t=in:ss=0:d=0.1,afade=t=out:st=' . $endFade . ':d=0.1"'
                . ' -c:a libmp3lame -b:a 128k ' . escapeshellarg($previewAbs) . ' 2>&1';
            shell_exec($cmdPrev);

            $cmdWave = 'ffmpeg -y -i ' . escapeshellarg($absolute)
                . ' -filter_complex "aformat=channel_layouts=mono,showwavespic=s=1200x250" -frames:v 1 '
                . escapeshellarg($waveAbs) . ' 2>&1';
            shell_exec($cmdWave);
        }

        $fingerprint = sha1_file($absolute);

        // Simpan ke DB
        $soundEffect = SoundEffect::create([
            'title'            => $request->title,
            'slug'             => $slug,
            'file_path'        => 'storage/' . $storedPath,
            'file_ext'         => $ext,
            'mime_type'        => $file->getMimeType(),
            'size_bytes'       => $sizeBytes,
            'duration_seconds' => $duration,
            'sample_rate'      => $sampleRate,
            'channels'         => $channels,
            'bitrate_kbps'     => $bitrate,
            'bit_depth'        => $bitDepth,
            'preview_path'     => 'storage/' . $previewRel,
            'waveform_image'   => 'storage/' . $waveRel,
            'fingerprint'      => $fingerprint,
            'category_id'      => $request->category_id,
            'subcategory_id'   => $request->subcategory_id,
            'creator_user_id'  => Auth::id(),
            'analysis_status'  => 'done',
        ]);

        // Simpan relasi tags (kalau ada)
        if ($request->filled('tags')) {
            $soundEffect->tags()->sync($request->tags);
        }

        return redirect()
            ->route('sound_effects.index')
            ->with('success', 'Sound Effect berhasil diupload & diproses!');
    }

    /**
     * Form edit Sound Effect.
     */
    public function edit(SoundEffect $sound_effect)
    {
        $categories    = SoundCategory::orderBy('name')->get();
        $subcategories = SoundSubcategory::orderBy('name')->get();
        $tags          = SoundTag::orderBy('name')->get();

        return view('sound_effects.edit', compact('sound_effect', 'categories', 'subcategories', 'tags'));
    }

    /**
     * Update Sound Effect existing.
     */
    public function update(Request $request, SoundEffect $sound_effect)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'category_id'     => 'nullable|exists:sound_categories,id',
            'subcategory_id'  => 'nullable|exists:sound_subcategories,id',
            'license_type_id' => 'nullable|exists:sound_licenses,id',
            'is_active'       => 'boolean',
            'tags'            => 'nullable|array',
            'tags.*'          => 'exists:sound_tags,id',
        ]);

        // Validasi: subcategory harus milik category yang dipilih (kalau dua-duanya diisi)
        if ($request->filled('subcategory_id') && $request->filled('category_id')) {
            $sub = SoundSubcategory::where('id', $request->subcategory_id)
                ->where('category_id', $request->category_id)
                ->first();

            if (!$sub) {
                return back()
                    ->withErrors(['subcategory_id' => 'Subcategory does not belong to selected category.'])
                    ->withInput();
            }
        }

        $sound_effect->update([
            'title'           => $request->title,
            'category_id'     => $request->category_id,
            'subcategory_id'  => $request->subcategory_id,
            'license_type_id' => $request->license_type_id,
            'is_active'       => $request->boolean('is_active'),
        ]);

        // Update tags
        if ($request->filled('tags')) {
            $sound_effect->tags()->sync($request->tags);
        } else {
            $sound_effect->tags()->sync([]); // clear tags kalau dikosongkan
        }

        return redirect()
            ->route('sound_effects.index')
            ->with('success', 'Sound Effect updated successfully!');
    }

    public function destroy(SoundEffect $sound_effect)
    {
        $sound_effect->delete();

        return redirect()
            ->route('sound_effects.index')
            ->with('success', 'Sound Effect deleted successfully!');
    }

    public function show(SoundEffect $sound_effect)
    {
        $sound_effect->load(['category', 'subcategory', 'tags', 'author']);

        // similar tracks (kategori sama, beda id)
        $similar = SoundEffect::query()
            ->where('id', '!=', $sound_effect->id)
            ->where('category_id', $sound_effect->category_id)
            ->limit(6)
            ->get();

        return view('sound_effects.show', [
            'sound'   => $sound_effect,
            'similar' => $similar,
        ]);
    }

    /**
     * Browse / filter sound effects (search, category, subcategory).
     */
    public function browse(Request $request)
{
    $q          = trim($request->get('q', ''));
    $categoryId = $request->get('category');
    $subId      = $request->get('subcategory');
    $sort       = $request->get('sort', 'popular');

    // Data umum untuk index/footer (kalau mau dipakai di layout lain)
    $categories = SoundCategory::query()
        ->withCount('soundEffects')
        ->orderBy('name')
        ->get();

    $subGroups = SoundSubcategory::query()
        ->with('category:id,name')
        ->orderBy('name')
        ->get()
        ->groupBy('category_id');

    $currentCategory    = null;
    $currentSubcategory = null;

    if ($categoryId) {
        $currentCategory = SoundCategory::findOrFail($categoryId);
    }

    if ($subId) {
        $currentSubcategory = SoundSubcategory::with('category')->findOrFail($subId);

        // Kalau datang dari subcategory saja, set currentCategory juga
        if (!$currentCategory) {
            $currentCategory = $currentSubcategory->category;
        }
    }

    // ==== QUERY DASAR: selalu jalan, kayak Epidemic (list terus) ====
    $query = SoundEffect::query()
        ->with(['category', 'subcategory', 'tags']);

    // Filter kategori
    if ($currentCategory) {
        $query->where('category_id', $currentCategory->id);
    }

    // Filter subkategori
    if ($currentSubcategory) {
        $query->where('subcategory_id', $currentSubcategory->id);
    }

    // Pencarian teks
    if ($q !== '') {
        $query->where(function ($qq) use ($q) {
            $qq->where('title', 'like', "%{$q}%")
               ->orWhere('description', 'like', "%{$q}%"); // abaikan kalau kolom ini belum ada
        });
    }

    // Sorting
    switch ($sort) {
        case 'newest':
            $query->orderByDesc('created_at');
            break;

        case 'shortest':
            $query->orderBy('duration_seconds');
            break;

        case 'longest':
            $query->orderByDesc('duration_seconds');
            break;

        case 'popular':
        default:
            // sementara pakai created_at, nanti bisa diganti play_count, download_count, dll.
            $query->orderByDesc('created_at');
            break;
    }

    $sounds = $query->paginate(20)->withQueryString();

    return view('sound_effects.browse', [
        'categories'         => $categories,
        'subGroups'          => $subGroups,
        'q'                  => $q,
        'sounds'             => $sounds,
        'currentCategory'    => $currentCategory,
        'currentSubcategory' => $currentSubcategory,
    ]);
}

}
