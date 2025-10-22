<?php

namespace App\Http\Controllers;

use App\Models\SoundCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SoundCategoryController extends Controller
{
    public function index()
    {
        $categories = SoundCategory::latest()->paginate(10);
        return view('sound_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('sound_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|unique:sound_categories,name',
            'bg_color' => 'nullable|max:10',
        ]);

        SoundCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'bg_color' => $request->bg_color ?? '#'.substr(md5($request->name), 0, 6),
            'icon_path' => $request->icon_path,
        ]);

        return redirect()->route('sound_categories.index')->with('success', 'Category added successfully!');
    }

    public function edit(SoundCategory $sound_category)
    {
        return view('sound_categories.edit', compact('sound_category'));
    }

    public function update(Request $request, SoundCategory $sound_category)
    {
        $request->validate([
            'name' => 'required|max:100|unique:sound_categories,name,' . $sound_category->id,
            'bg_color' => 'nullable|max:10',
        ]);

        $sound_category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'bg_color' => $request->bg_color ?? $sound_category->bg_color,
        ]);

        return redirect()->route('sound_categories.index')->with('success', 'Category updated!');
    }

    public function destroy(SoundCategory $sound_category)
    {
        $sound_category->delete();
        return redirect()->route('sound_categories.index')->with('success', 'Category deleted.');
    }
}
