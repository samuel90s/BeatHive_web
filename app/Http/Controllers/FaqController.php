<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    public function __construct()
    {
        // Semua aksi butuh admin, KECUALI halaman publik
        $this->middleware(['auth', 'can:admin-only'])->except(['public']);
    }

    /**
     * List FAQ (admin only)
     */
    public function index(Request $r)
    {
        $q   = $r->input('q');
        $cat = $r->input('category');

        $faqs = Faq::with('category')
            ->when($cat, fn($qq) => $qq->where('faq_category_id', $cat))
            ->search($q)
            ->orderBy('faq_category_id')
            ->orderBy('order_column')
            ->paginate(12)
            ->withQueryString();

        $categories = FaqCategory::orderBy('name')->get();

        return view('faqs.index', compact('faqs', 'categories', 'q', 'cat'));
    }

    /**
     * Form create (admin only)
     */
    public function create()
    {
        $categories = FaqCategory::orderBy('name')->get();
        return view('faqs.create', compact('categories'));
    }

    /**
     * Store (admin only)
     */
    public function store(Request $r)
    {
        $data = $r->validate([
            'faq_category_id' => ['nullable', 'exists:faq_categories,id'],
            'question'        => ['required', 'string', 'max:255'],
            'answer'          => ['required', 'string'],
            'is_published'    => ['nullable', 'boolean'],
            'order_column'    => ['nullable', 'integer', 'min:0'],
        ]);

        $data['slug']         = Str::slug($r->question . '-' . Str::random(6));
        $data['is_published'] = $r->boolean('is_published');

        Faq::create($data);

        return redirect()->route('faqs.index')->with('success', 'FAQ created.');
    }

    /**
     * Form edit (admin only)
     */
    public function edit(Faq $faq)
    {
        $categories = FaqCategory::orderBy('name')->get();
        return view('faqs.edit', compact('faq', 'categories'));
    }

    /**
     * Update (admin only)
     */
    public function update(Request $r, Faq $faq)
    {
        $data = $r->validate([
            'faq_category_id' => ['nullable', 'exists:faq_categories,id'],
            'question'        => ['required', 'string', 'max:255'],
            'answer'          => ['required', 'string'],
            'is_published'    => ['nullable', 'boolean'],
            'order_column'    => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_published'] = $r->boolean('is_published');

        if ($faq->question !== $r->question) {
            $data['slug'] = Str::slug($r->question . '-' . Str::random(6));
        }

        $faq->update($data);

        return redirect()->route('faqs.index')->with('success', 'FAQ updated.');
    }

    /**
     * Delete (admin only)
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ deleted.');
    }

    /**
     * Toggle publish (admin only)
     */
    public function toggle(Faq $faq)
    {
        $faq->update(['is_published' => !$faq->is_published]);
        return back()->with('success', 'Publish status updated.');
    }

    /**
     * Reorder (admin only)
     */
    public function reorder(Request $r, Faq $faq)
    {
        $data = $r->validate([
            'order_column' => ['required', 'integer', 'min:0'],
        ]);

        $faq->update($data);

        return back()->with('success', 'Order updated.');
    }

    /**
     * Halaman publik FAQ (tidak butuh admin)
     */
    public function public(Request $r)
    {
        $q = trim((string) $r->input('q'));

        $categories = FaqCategory::with(['faqs' => function ($qq) use ($q) {
            $qq->published();
            if ($q) {
                $qq->search($q);
            }
            $qq->orderBy('order_column');
        }])->orderBy('name')->get();

        return view('faqs.public', compact('categories', 'q'));
    }
}
