<?php

namespace App\Http\Controllers;

use App\Models\SoundEffect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use App\Models\SoundCategory;
use App\Models\SoundSubcategory;



class SoundEffectController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); // pastikan auth
        $this->authorizeResource(\App\Models\SoundEffect::class, 'sound_effect');
    }
    public function index()
    {
        // Halaman GRID kategori (landing)
        $categories = SoundCategory::query()
            ->withCount('soundEffects')   // boleh dicabut kalau belum perlu
            ->orderBy('name')->get();

        $subGroups = SoundSubcategory::query()
            ->with('category:id,name')
            ->orderBy('name')->get()
            ->groupBy('category_id');

        return view('sound_effects.index', [
            'categories' => $categories,
            'subGroups'  => $subGroups,
            'q'          => '',
        ]);
    }

    public function list()
    {
        // Halaman LIST SFX (yang lama, ada player)
        $sounds = SoundEffect::with('author','category')->latest()->paginate(10);
        return view('sound_effects.list', compact('sounds'));
    }

    public function create()
    {
        return view('sound_effects.create');
    }


public function store(Request $request)
{
    $request->validate([
        'title'       => 'required|string|max:255',
        'file'        => 'required|mimes:wav,mp3,ogg,flac,aiff|max:51200',
        'category_id' => 'nullable|exists:sound_categories,id',
    ]);

    // Pastikan symlink sekali jalan
    if (!is_link(public_path('storage'))) {
        try { Artisan::call('storage:link'); } catch (\Throwable $e) {}
    }

    $file      = $request->file('file');
    $ext       = strtolower($file->getClientOriginalExtension());
    $slug      = SoundEffect::generateSlug($request->title);

    // Struktur folder (tanpa 'public/' prefix; kita pakai disk('public'))
    $datePath  = date('Y/m');
    $dirOrig   = "sound_effects/original/{$datePath}";
    $dirPrev   = "sound_effects/preview/{$datePath}";
    $dirWave   = "sound_effects/waveform/{$datePath}";

    // Buat folder otomatis di disk public
    Storage::disk('public')->makeDirectory($dirOrig);
    Storage::disk('public')->makeDirectory($dirPrev);
    Storage::disk('public')->makeDirectory($dirWave);

    // Simpan file asli
    $fileName   = "{$slug}.{$ext}";
    $storedPath = Storage::disk('public')->putFileAs($dirOrig, $file, $fileName);
    // $storedPath contoh: "sound_effects/original/2025/10/slug.mp3"

    $absolute   = Storage::disk('public')->path($storedPath); // path absolut OS-safe
    if (!is_file($absolute)) {
        clearstatcache();
        usleep(400000); // 0.4s; flush I/O Windows
    }
    if (!is_file($absolute)) {
        return back()->withErrors(['file' => 'File gagal disimpan ke storage. Periksa permission folder storage.']);
    }

    // ffprobe (opsional)
    $hasFfprobe = (bool) shell_exec('ffprobe -version 2>NUL || echo 0');
    $hasFfmpeg  = (bool) shell_exec('ffmpeg -version 2>NUL || echo 0');

    $duration = 0.0; $bitrate = 0; $sampleRate = 0; $channels = 0; $bitDepth = 0;
    if ($hasFfprobe) {
        $cmd   = 'ffprobe -v quiet -print_format json -show_format -show_streams ' . escapeshellarg($absolute);
        $json  = shell_exec($cmd);
        $meta  = json_decode($json ?? '[]', true);
        $fmt   = $meta['format'] ?? [];
        $st0   = $meta['streams'][0] ?? [];
        $duration   = (float)($fmt['duration'] ?? 0);
        $bitrate    = (int) round(($fmt['bit_rate'] ?? 0) / 1000);
        $sampleRate = (int)($st0['sample_rate'] ?? 0);
        $channels   = (int)($st0['channels'] ?? 0);
        $bitDepth   = (int)($st0['bits_per_sample'] ?? 0);
    }
    $sizeBytes = filesize($absolute);

    // Generate preview & waveform
    $previewName = "{$slug}_preview.mp3";
    $waveName    = "{$slug}_waveform.png";
    $previewRel  = "{$dirPrev}/{$previewName}";
    $waveRel     = "{$dirWave}/{$waveName}";
    $previewAbs  = Storage::disk('public')->path($previewRel);
    $waveAbs     = Storage::disk('public')->path($waveRel);

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

    // Simpan ke DB → URL publik harus diawali "storage/"
    SoundEffect::create([
        'title'            => $request->title,
        'slug'             => $slug,
        'file_path'        => 'storage/' . $storedPath,     // "storage/sound_effects/original/2025/10/slug.mp3"
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
        'creator_user_id'  => Auth::id(),
        'analysis_status'  => 'done',
    ]);

    return redirect()
        ->route('sound_effects.index')
        ->with('success', 'Sound Effect berhasil diupload & diproses!');
}

    public function edit(SoundEffect $sound_effect)
    {
        return view('sound_effects.edit', compact('sound_effect'));
    }

    public function update(Request $request, SoundEffect $sound_effect)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:sound_categories,id',
            'license_type_id' => 'nullable|exists:sound_licenses,id',
            'is_active' => 'boolean',
        ]);

        $sound_effect->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'license_type_id' => $request->license_type_id,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('sound_effects.index')->with('success', 'Sound Effect updated successfully!');
    }

    public function destroy(SoundEffect $sound_effect)
    {
        $sound_effect->delete();
        return redirect()->route('sound_effects.index')->with('success', 'Sound Effect deleted successfully!');
    }
    
    public function browse(Request $request)
    {
        $q = trim($request->get('q', '')); // pencarian
        $categories = \App\Models\SoundCategory::query()
            ->withCount('soundEffects')        // kalau sudah ada relasi
            ->orderBy('name')
            ->get();

        // subkategori dikelompokkan per kategori
        $subGroups = \App\Models\SoundSubcategory::query()
            ->with('category:id,name')
            ->orderBy('name')
            ->get()
            ->groupBy('category_id');

        return view('sound_effects.browse', [
            'categories' => $categories,
            'subGroups'  => $subGroups,
            'q'          => $q,
        ]);
    }

}

