<?php

namespace App\Http\Controllers;

use App\Models\SoundTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SoundTagController extends Controller
{
    public function index()
    {
        $tags = SoundTag::latest()->paginate(10);
        return view('sound_tags.index', compact('tags'));
    }

    public function create()
    {
        return view('sound_tags.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50|unique:sound_tags,name',
        ]);

        SoundTag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('sound_tags.index')->with('success', 'Tag added successfully!');
    }

    public function edit(SoundTag $sound_tag)
    {
        return view('sound_tags.edit', compact('sound_tag'));
    }

    public function update(Request $request, SoundTag $sound_tag)
    {
        $request->validate([
            'name' => 'required|max:50|unique:sound_tags,name,' . $sound_tag->id,
        ]);

        $sound_tag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('sound_tags.index')->with('success', 'Tag updated!');
    }

    public function destroy(SoundTag $sound_tag)
    {
        $sound_tag->delete();
        return redirect()->route('sound_tags.index')->with('success', 'Tag deleted.');
    }
}
