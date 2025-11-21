<?php

namespace App\Http\Controllers;

use App\Models\SoundCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SoundCategoryController extends Controller
{
    public function index()
    {
        // kalau di view kamu pakai ->sound_effects_count, pakai withCount
        $categories = SoundCategory::withCount('soundEffects')
            ->latest()
            ->paginate(10);

        return view('sound_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('sound_categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100', 'unique:sound_categories,name'],
            'bg_color' => ['nullable', 'string', 'max:10'],
            'icon'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
        ]);

        // warna default kalau tidak diisi
        $bgColor = $data['bg_color'] ?? '#' . substr(md5($data['name']), 0, 6);

        // upload icon (opsional)
        $iconPath = null;
        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('sound_categories', 'public');
            // disimpan sebagai "storage/..." supaya di view bisa langsung asset($icon_path)
            $iconPath = 'storage/' . $path;
        }

        SoundCategory::create([
            'name'      => $data['name'],
            'slug'      => Str::slug($data['name']),
            'bg_color'  => $bgColor,
            'icon_path' => $iconPath,
        ]);

        return redirect()
            ->route('sound_categories.index')
            ->with('success', 'Category added successfully!');
    }

    public function edit(SoundCategory $sound_category)
    {
        return view('sound_categories.edit', compact('sound_category'));
    }

    public function update(Request $request, SoundCategory $sound_category)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100', 'unique:sound_categories,name,' . $sound_category->id],
            'bg_color'    => ['nullable', 'string', 'max:10'],
            'icon'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'remove_icon' => ['nullable', 'boolean'],
        ]);

        $sound_category->name = $data['name'];
        $sound_category->slug = Str::slug($data['name']);
        $sound_category->bg_color = $data['bg_color'] ?? $sound_category->bg_color;

        // hapus icon kalau dicentang
        if ($request->boolean('remove_icon')) {
            $sound_category->icon_path = null;
        }

        // ganti icon kalau upload baru
        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('sound_categories', 'public');
            $sound_category->icon_path = 'storage/' . $path;
        }

        $sound_category->save();

        return redirect()
            ->route('sound_categories.index')
            ->with('success', 'Category updated!');
    }

    public function destroy(SoundCategory $sound_category)
    {
        $sound_category->delete();

        return redirect()
            ->route('sound_categories.index')
            ->with('success', 'Category deleted.');
    }
}
