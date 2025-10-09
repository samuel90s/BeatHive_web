<?php

namespace App\Http\Controllers;

use App\Models\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FaqCategoryController extends Controller
{
    public function __construct()
    {
        // Semua aksi kategori khusus admin
        $this->middleware(['auth', 'can:admin-only']);
    }

    /**
     * List categories (admin only)
     */
    // public function index()
    // {
    //     $cats = FaqCategory::orderBy('name')->paginate(20);
    //     return view('faq-categories.index', compact('cats'));
    // }
    
    public function index(Request $r)
    {
        $qRaw  = (string) $r->input('q', '');
        $q     = trim($qRaw);

        $allowedSorts = ['name', 'faqs_count', 'created_at'];
        $sort  = $r->input('sort', 'name');
        if (! in_array($sort, $allowedSorts, true)) $sort = 'name';

        $order = strtolower((string) $r->input('order', 'asc'));
        if (! in_array($order, ['asc','desc'], true)) $order = 'asc';

        $cats = FaqCategory::withCount('faqs')
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('name','like',"%{$q}%")
                    ->orWhere('slug','like',"%{$q}%");
                });
            })
            ->orderBy($sort, $order)
            ->paginate(20)
            ->withQueryString();

        return view('faq-categories.index', compact('cats','q','sort','order'));
    }
    /**
     * Form create (admin only)
     */
    public function create()
    {
        return view('faq-categories.create');
    }

    /**
     * Store (admin only)
     */
    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => ['required', 'max:120'],
        ]);

        FaqCategory::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);

        return redirect()->route('faq-categories.index')->with('success', 'Category added.');
    }

    /**
     * Form edit (admin only)
     *
     * Catatan: parameter harus {faq_category} pada resource route
     */
    public function edit(FaqCategory $faq_category)
    {
        return view('faq-categories.edit', ['cat' => $faq_category]);
    }

    /**
     * Update (admin only)
     */
    public function update(Request $r, FaqCategory $faq_category)
    {
        $data = $r->validate([
            'name' => ['required', 'max:120'],
        ]);

        $faq_category->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);

        return back()->with('success', 'Category updated.');
    }

    /**
     * Delete (admin only)
     */
    public function destroy(FaqCategory $faq_category)
    {
        $faq_category->delete();
        return back()->with('success', 'Category deleted.');
    }
}
