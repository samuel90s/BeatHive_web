<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\Genre;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // KPI
        $totalTracks   = Track::count();
        $published     = Track::where('is_published', true)->count();
        $draft         = $totalTracks - $published;

        // Tracks added per month: 12 bulan terakhir (label: Jan..Des current locale)
        $start = Carbon::now()->startOfMonth()->subMonths(11); // 12 months window
        $monthly = Track::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as c')
            ->where('created_at', '>=', $start)
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('c', 'ym')
            ->all();

        // bangun array 12 bulan berurutan
        $labels = [];
        $seriesAdd = [];
        for ($i = 0; $i < 12; $i++) {
            $d = (clone $start)->addMonths($i);
            $ym = $d->format('Y-m');
            $labels[]   = $d->translatedFormat('M Y'); // ex: Sep 2025 (sesuai locale)
            $seriesAdd[] = $monthly[$ym] ?? 0;
        }

        // Top Genres (top 5)
        $topGenres = Track::query()
            ->select('genres.name', DB::raw('COUNT(tracks.id) as total'))
            ->leftJoin('genres', 'genres.id', '=', 'tracks.genre_id')
            ->groupBy('genres.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $genreLabels = $topGenres->pluck('name')->map(fn($v) => $v ?? 'Unknown');
        $genreSeries = $topGenres->pluck('total');

        // Recent Tracks (5 terakhir)
        $recentTracks = Track::with('genre')->latest()->limit(5)->get();

        // Placeholder hooks kalau nanti ada Orders/Downloads:
        $revenueSeries   = []; // isi jika punya tabel orders
        $downloadsSeries = []; // isi jika punya tabel downloads

        return view('index', [
            'totalTracks'     => $totalTracks,
            'published'       => $published,
            'draft'           => $draft,
            'labels'          => $labels,
            'seriesAdd'       => $seriesAdd,
            'genreLabels'     => $genreLabels,
            'genreSeries'     => $genreSeries,
            'recentTracks'    => $recentTracks,
            'revenueSeries'   => $revenueSeries,
            'downloadsSeries' => $downloadsSeries,
        ]);
    }
}
