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
     * Landing page – grid kategori
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
     * Legacy list page
     */
    public function list()
    {
        $sounds = SoundEffect::with(['author','category','subcategory','tags'])
            ->latest()
            ->paginate(10);

        return view('sound_effects.list', compact('sounds'));
    }

    /**
     * Create form
     */
    public function create()
    {
        $categories    = SoundCategory::orderBy('name')->get();
        $subcategories = SoundSubcategory::orderBy('name')->get();
        $tags          = SoundTag::orderBy('name')->get();

        return view('sound_effects.create', compact('categories','subcategories','tags'));
    }

    /**
     * Store sound effect
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

        if ($request->filled('subcategory_id') && $request->filled('category_id')) {
            $sub = SoundSubcategory::where('id', $request->subcategory_id)
                ->where('category_id', $request->category_id)
                ->first();

            if (!$sub) {
                return back()->withErrors([
                    'subcategory_id' => 'Subcategory does not belong to selected category.'
                ])->withInput();
            }
        }

        if (!is_link(public_path('storage'))) {
            try { Artisan::call('storage:link'); } catch (\Throwable $e) {}
        }

        $file = $request->file('file');
        $ext  = strtolower($file->getClientOriginalExtension());

        $slug = SoundEffect::generateSlug($request->title);

        $path = $file->storeAs(
            'sound_effects/original',
            $slug.'.'.$ext,
            'public'
        );

        $soundEffect = SoundEffect::create([
            'title'           => $request->title,
            'slug'            => $slug,
            'file_path'       => 'storage/'.$path,
            'file_ext'        => $ext,
            'mime_type'       => $file->getMimeType(),
            'category_id'     => $request->category_id,
            'subcategory_id'  => $request->subcategory_id,
            'creator_user_id' => Auth::id(),
            'analysis_status' => 'done',
        ]);

        if ($request->filled('tags')) {
            $soundEffect->tags()->sync($request->tags);
        }

        return redirect()
            ->route('sound_effects.index')
            ->with('success','Sound Effect uploaded successfully!');
    }

    /**
     * Edit form
     */
    public function edit(SoundEffect $sound_effect)
    {
        $categories    = SoundCategory::orderBy('name')->get();
        $subcategories = SoundSubcategory::orderBy('name')->get();
        $tags          = SoundTag::orderBy('name')->get();

        return view(
            'sound_effects.edit',
            compact('sound_effect','categories','subcategories','tags')
        );
    }

    /**
     * Update sound effect
     */
    public function update(Request $request, SoundEffect $sound_effect)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'category_id'     => 'nullable|exists:sound_categories,id',
            'subcategory_id'  => 'nullable|exists:sound_subcategories,id',
            'is_active'       => 'boolean',
            'tags'            => 'nullable|array',
            'tags.*'          => 'exists:sound_tags,id',
        ]);

        $sound_effect->update([
            'title'          => $request->title,
            'category_id'    => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'is_active'      => $request->boolean('is_active'),
        ]);

        if ($request->filled('tags')) {
            $sound_effect->tags()->sync($request->tags);
        } else {
            $sound_effect->tags()->sync([]);
        }

        return redirect()
            ->route('sound_effects.index')
            ->with('success','Sound Effect updated successfully!');
    }

    /**
     * Delete sound
     */
    public function destroy(SoundEffect $sound_effect)
    {
        $sound_effect->delete();

        return redirect()
            ->route('sound_effects.index')
            ->with('success','Sound Effect deleted successfully!');
    }

    /**
     * Show detail sound
     */
    public function show(SoundEffect $sound_effect)
    {
        $sound_effect->load(['category','subcategory','tags','author']);

        $sound_effect->increment('play_count');

        $similar = SoundEffect::where('category_id',$sound_effect->category_id)
            ->where('id','!=',$sound_effect->id)
            ->limit(6)
            ->get();

        return view('sound_effects.show',[
            'sound'   => $sound_effect,
            'similar' => $similar
        ]);
    }

    /**
     * Browse / search sound effects
     */
    public function browse(Request $request)
{
    $q          = trim($request->get('q',''));
    $categoryId = $request->get('category');
    $subId      = $request->get('subcategory');
    $sort       = $request->get('sort','popular');

    $categories = SoundCategory::withCount('soundEffects')
        ->orderBy('name')
        ->get();

    $subGroups = SoundSubcategory::orderBy('name')
        ->get()
        ->groupBy('category_id');

    $currentCategory = null;
    $currentSubcategory = null;

    if ($categoryId) {
        $currentCategory = SoundCategory::find($categoryId);
    }

    if ($subId) {
        $currentSubcategory = SoundSubcategory::find($subId);

        if (!$currentCategory && $currentSubcategory) {
            $currentCategory = $currentSubcategory->category;
        }
    }

    $query = SoundEffect::query()
        ->with(['category','subcategory','tags'])
        ->where('is_active',true);

    if ($currentCategory) {
        $query->where('category_id',$currentCategory->id);
    }

    if ($currentSubcategory) {
        $query->where('subcategory_id',$currentSubcategory->id);
    }

    if ($q !== '') {
        $query->where('title','like',"%{$q}%");
    }

    switch ($sort) {
        case 'newest':
            $query->orderByDesc('created_at');
            break;

        case 'shortest':
            $query->orderBy('duration_seconds');
            break;

        default:
            $query->orderByDesc('play_count');
    }

    $sounds = $query->paginate(25)->withQueryString();

    return view('sound_effects.browse',[
        'categories'         => $categories,
        'subGroups'          => $subGroups,
        'sounds'             => $sounds,
        'q'                  => $q,
        'currentCategory'    => $currentCategory,
        'currentSubcategory' => $currentSubcategory
    ]);
}

    /**
     * Increment play count (AJAX)
     */
    public function incrementPlay(SoundEffect $sound_effect)
    {
        $sound_effect->increment('play_count');

        return response()->json([
            'play_count' => $sound_effect->play_count
        ]);
    }


    // =====================================================
    // CATEGORY SHORTCUT ROUTES
    // =====================================================

    private function categoryShortcut(Request $request,string $name)
    {
        $category = SoundCategory::where('name',$name)->firstOrFail();

        $request->merge([
            'category' => $category->id
        ]);

        return $this->browse($request);
    }

    // ===== CATEGORY SHORTCUT ROUTES =====

public function foley()
{
    $category = SoundCategory::where('name', 'Foley')->firstOrFail();

    return redirect()->route('sound_effects.browse', [
        'category' => $category->id
    ]);
}

public function soundscape()
{
    $category = SoundCategory::where('name', 'Soundscape')->firstOrFail();

    return redirect()->route('sound_effects.browse', [
        'category' => $category->id
    ]);
}

public function ambience()
{
    $category = SoundCategory::where('name', 'Ambience')->firstOrFail();

    return redirect()->route('sound_effects.browse', [
        'category' => $category->id
    ]);
}

public function soundscoring()
{
    $category = SoundCategory::where('name', 'Sound Scoring')->firstOrFail();

    return redirect()->route('sound_effects.browse', [
        'category' => $category->id
    ]);
}

}