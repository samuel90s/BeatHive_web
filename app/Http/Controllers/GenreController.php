<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::latest()->paginate(10);
        return view('genres.index', compact('genres'));
    }

    public function create()
    {
        return view('genres.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:genres,name',
            'description' => 'nullable|string|max:255',
        ]);

        $data['slug'] = Str::slug($data['name']);

        Genre::create($data);

        return redirect()->route('genres.index')->with('success', 'Genre created successfully.');
    }

    public function show(Genre $genre)
    {
        return view('genres.show', compact('genre'));
    }

    public function edit(Genre $genre)
    {
        return view('genres.edit', compact('genre'));
    }

    public function update(Request $request, Genre $genre)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:genres,name,' . $genre->id,
            'description' => 'nullable|string|max:255',
        ]);

        $data['slug'] = Str::slug($data['name']);

        $genre->update($data);

        return redirect()->route('genres.index')->with('success', 'Genre updated successfully.');
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();
        return redirect()->route('genres.index')->with('success', 'Genre deleted successfully.');
    }
}
