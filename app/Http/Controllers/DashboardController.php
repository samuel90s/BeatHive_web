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
        // =========================
        // KPI cards
        // =========================
        $totalTracks   = Track::count();
        $published     = Track::where('is_published', true)->count();
        $draft         = $totalTracks - $published;

        // =========================
        // Tracks added per month (12 bulan terakhir)
        // =========================
        $start = Carbon::now()->startOfMonth()->subMonths(11);

        $monthly = Track::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as c')
            ->where('created_at', '>=', $start)
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('c', 'ym')
            ->all();

        $labels = [];
        $seriesAdd = [];
        for ($i = 0; $i < 12; $i++) {
            $d = (clone $start)->addMonths($i);
            $ym = $d->format('Y-m');
            $labels[]    = $d->translatedFormat('M Y');
            $seriesAdd[] = $monthly[$ym] ?? 0;
        }

        // =========================
        // Top Genres (limit 5)
        // =========================
        $topGenres = Track::query()
            ->select('genres.name', DB::raw('COUNT(tracks.id) as total'))
            ->leftJoin('genres', 'genres.id', '=', 'tracks.genre_id')
            ->groupBy('genres.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $genreLabels = $topGenres->pluck('name')->map(fn($v) => $v ?? 'Unknown');
        $genreSeries = $topGenres->pluck('total');

        // =========================
        // Recent tracks (5 terbaru)
        // =========================
        $recentTracks = Track::with('genre')
            ->latest()
            ->limit(5)
            ->get();

        // =========================
        // Bonus insight: Top Artists (limit 5)
        // =========================
        $topArtists = Track::select('artist', DB::raw('COUNT(*) as total'))
            ->groupBy('artist')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // =========================
        // Upcoming releases (release_date >= hari ini, limit 5)
        // =========================
        $upcomingReleases = Track::with('genre')
            ->whereDate('release_date', '>=', Carbon::today())
            ->orderBy('release_date')
            ->limit(5)
            ->get();

        return view('index', [
            'totalTracks'      => $totalTracks,
            'published'        => $published,
            'draft'            => $draft,
            'labels'           => $labels,
            'seriesAdd'        => $seriesAdd,
            'genreLabels'      => $genreLabels,
            'genreSeries'      => $genreSeries,
            'recentTracks'     => $recentTracks,
            'topArtists'       => $topArtists,
            'upcomingReleases' => $upcomingReleases,
        ]);
    }
}
