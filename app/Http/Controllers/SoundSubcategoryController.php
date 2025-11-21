<?php

namespace App\Http\Controllers;

use App\Models\SoundCategory;
use App\Models\SoundSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SoundSubcategoryController extends Controller
{
    /**
     * Tampilkan semua subcategory
     */
    public function index()
    {
        $subs = SoundSubcategory::with('category')   // untuk akses $sub->category
            ->withCount('soundEffects')              // butuh relasi soundEffects() di model
            ->orderBy('name')
            ->paginate(15);

        return view('sound_subcategories.index', compact('subs'));
    }

    /**
     * Form tambah subcategory
     */
    public function create()
    {
        $categories = SoundCategory::orderBy('name')->get();

        return view('sound_subcategories.create', compact('categories'));
    }

    /**
     * Simpan subcategory baru
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:sound_categories,id'],
            'name'        => ['required', 'string', 'max:100', 'unique:sound_subcategories,name'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        SoundSubcategory::create($data);

        return redirect()
            ->route('sound_subcategories.index')
            ->with('success', 'Subcategory added successfully!');
    }

    /**
     * Form edit
     */
    public function edit(SoundSubcategory $sound_subcategory)
    {
        $categories = SoundCategory::orderBy('name')->get();

        return view('sound_subcategories.edit', compact('sound_subcategory', 'categories'));
    }

    /**
     * Update subcategory
     */
    public function update(Request $request, SoundSubcategory $sound_subcategory)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:sound_categories,id'],
            'name'        => ['required', 'string', 'max:100', 'unique:sound_subcategories,name,' . $sound_subcategory->id],
        ]);

        $data['slug'] = Str::slug($data['name']);

        $sound_subcategory->update($data);

        return redirect()
            ->route('sound_subcategories.index')
            ->with('success', 'Subcategory updated successfully!');
    }

    /**
     * Hapus subcategory
     */
    public function destroy(SoundSubcategory $sound_subcategory)
    {
        $sound_subcategory->delete();

        return redirect()
            ->route('sound_subcategories.index')
            ->with('success', 'Subcategory deleted successfully!');
    }

    /**
     * JSON untuk ambil subcategory berdasarkan kategori (buat dropdown dinamis)
     */
    public function byCategory(SoundCategory $category)
    {
        return response()->json(
            $category->subcategories()
                ->select('id', 'name')
                ->orderBy('name')
                ->get()
        );
    }
}
