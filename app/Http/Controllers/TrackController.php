<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrackController extends Controller
{
    // daftar semua tracks
    public function index()
    {
        $tracks = Track::with('genre')->latest()->paginate(10);
        return view('tracks.index', compact('tracks'));
    }

    // form create
    public function create()
    {
        $genres = Genre::orderBy('name')->get();
        return view('tracks.create', compact('genres'));
    }

    // simpan data baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'genre_id' => 'required|exists:genres,id',
            'bpm' => 'nullable|integer|min:40|max:220',
            'musical_key' => 'nullable|string|max:8',
            'mood' => 'nullable|string|max:100',
            'tags' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price_idr' => 'nullable|integer|min:0',
            'duration_seconds' => 'nullable|integer|min:0',
            'release_date' => 'nullable|date',
            'is_published' => 'nullable|boolean',
            'cover_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'preview_path' => 'nullable|mimetypes:audio/mpeg|max:10240',
            'bundle_path' => 'nullable|mimes:wav,zip|max:204800',
        ]);

        $data['slug'] = Str::slug($data['title']).'-'.Str::random(6);
        $data['is_published'] = $request->boolean('is_published');

        // handle file upload
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

        return redirect()->route('tracks.index')->with('success', 'Track berhasil ditambahkan');
    }

    // detail track
    public function show(Track $track)
    {
        return view('tracks.show', compact('track'));
    }

    // form edit
    public function edit(Track $track)
    {
        $genres = Genre::orderBy('name')->get();
        return view('tracks.edit', compact('track','genres'));
    }

    // update data
    public function update(Request $request, Track $track)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'genre_id' => 'required|exists:genres,id',
            'bpm' => 'nullable|integer|min:40|max:220',
            'musical_key' => 'nullable|string|max:8',
            'mood' => 'nullable|string|max:100',
            'tags' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price_idr' => 'nullable|integer|min:0',
            'duration_seconds' => 'nullable|integer|min:0',
            'release_date' => 'nullable|date',
            'is_published' => 'nullable|boolean',
            'cover_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'preview_path' => 'nullable|mimetypes:audio/mpeg|max:10240',
            'bundle_path' => 'nullable|mimes:wav,zip|max:204800',
        ]);

        $data['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('cover_path')) {
            $data['cover_path'] = $request->file('cover_path')->store('covers', 'public');
        }
        if ($request->hasFile('preview_path')) {
            $data['preview_path'] = $request->file('preview_path')->store('previews', 'public');
        }
        if ($request->hasFile('bundle_path')) {
            $data['bundle_path'] = $request->file('bundle_path')->store('bundles', 'public');
        }

        $track->update($data);

        return redirect()->route('tracks.index')->with('success', 'Track berhasil diperbarui');
    }

    // hapus data
    public function destroy(Track $track)
    {
        $track->delete();
        return redirect()->route('tracks.index')->with('success', 'Track berhasil dihapus');
    }
}
