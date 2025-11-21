{{-- =====================[ HEADER + NAVBAR – BeatHive (Simplified) ]===================== --}}
@php
use Illuminate\Support\Facades\Route;

// Helper: cocokkan rute aktif
$is = fn (...$patterns) => request()->routeIs($patterns);

// Flags aktif seperlunya saja
$activeDashboard = $is('home');
$activeCatalog   = $is('tracks.*', 'genres.*', 'tags.*', 'albums.*');
$activeGenres    = $is('genres.*');
$activeSfx       = $is(
    'sound_effects.*',
    'sound_categories.*',
    'sound_tags.*',
    'sound_licenses.*',
    'sound_subcategories.*'
);

// Kelas utilitas (sesuaikan dengan CSS tema kamu)
$activeLi   = 'active';
$activeLink = 'is-active';
@endphp

<nav class="main-navbar">
  <div class="container">
    <ul>
      {{-- Dashboard --}}
      <li class="menu-item {{ $activeDashboard ? $activeLi : '' }}">
        <a href="{{ Route::has('home') ? route('home') : url('/') }}"
           class="menu-link {{ $activeDashboard ? $activeLink : '' }}">
          <span><i class="bi bi-grid-fill"></i> Dashboard</span>
        </a>
      </li>

      {{-- Music (Catalog) --}}
      <li class="menu-item has-sub {{ $activeCatalog ? $activeLi : '' }}">
        <a href="#" class="menu-link {{ $activeCatalog ? $activeLink : '' }}">
          <span><i class="bi bi-music-note-list"></i> Music</span>
        </a>

        <div class="submenu">
          <div class="submenu-group-wrapper">
            <ul class="submenu-group">
              <li class="submenu-item">
                <a href="{{ Route::has('tracks.index') ? route('tracks.index') : '#' }}"
                   class="submenu-link {{ $is('tracks.index','tracks.show','tracks.edit') ? $activeLink : '' }}">
                  Explore all music
                </a>
              </li>

              <li class="submenu-item">
                <a href="{{ Route::has('genres.index') ? route('genres.index') : '#' }}"
                   class="submenu-link {{ $is('genres.*','tags.*') ? $activeLink : '' }}">
                  Genres
                </a>
              </li>

              <li class="submenu-item">
                <a href="{{ Route::has('albums.index') ? route('albums.index') : '#' }}"
                   class="submenu-link {{ $is('albums.*') ? $activeLink : '' }}">
                  Moods
                </a>
              </li>

              <li class="submenu-item">
                <a href="{{ Route::has('albums.index') ? route('albums.index') : '#' }}"
                   class="submenu-link {{ $is('albums.*') ? $activeLink : '' }}">
                  Themes
                </a>
              </li>
            </ul>
          </div>
        </div>
      </li>

      {{-- Pricing (kalau dipakai) --}}
      @if(Route::has('pricing.index'))
      <li class="menu-item {{ $is('pricing','pricing.index') ? $activeLi : '' }}">
        <a href="{{ route('pricing.index') }}"
           class="menu-link {{ $is('pricing','pricing.index') ? $activeLink : '' }}">
          <span><i class="bi bi-currency-dollar"></i> Pricing</span>
        </a>
      </li>
      @endif

      {{-- Genres (shortcut) --}}
      <li class="menu-item {{ $activeGenres ? $activeLi : '' }}">
        <a href="{{ Route::has('genres.index') ? route('genres.index') : '#' }}"
           class="menu-link {{ $activeGenres ? $activeLink : '' }}">
          <span><i class="bi bi-tags-fill"></i> Genres</span>
        </a>
      </li>

      {{-- Sound Effects (module utama, termasuk data SFX) --}}
      <li class="menu-item has-sub {{ $activeSfx ? $activeLi : '' }}">
        <a href="#" class="menu-link {{ $activeSfx ? $activeLink : '' }}">
          <span><i class="bi bi-soundwave"></i> Sound Effects</span>
        </a>

        <div class="submenu">
          <div class="submenu-group-wrapper">
            <ul class="submenu-group">
              {{-- Library --}}
              <li class="submenu-item">
                <a href="{{ Route::has('sound_effects.index') ? route('sound_effects.index') : '#' }}"
                   class="submenu-link {{ $is('sound_effects.index') ? $activeLink : '' }}">
                  <i class="bi bi-collection me-1"></i> Library
                </a>
              </li>

              {{-- Add --}}
              <li class="submenu-item">
                <a href="{{ Route::has('sound_effects.create') ? route('sound_effects.create') : '#' }}"
                   class="submenu-link {{ $is('sound_effects.create') ? $activeLink : '' }}">
                  <i class="bi bi-plus-circle me-1"></i> Add Sound Effect
                </a>
              </li>

              {{-- Categories --}}
              <li class="submenu-item">
                <a href="{{ Route::has('sound_categories.index') ? route('sound_categories.index') : '#' }}"
                   class="submenu-link {{ $is('sound_categories.*') ? $activeLink : '' }}">
                  <i class="bi bi-folder-fill me-1"></i> Categories
                </a>
              </li>

              {{-- Tags --}}
              <li class="submenu-item">
                <a href="{{ Route::has('sound_tags.index') ? route('sound_tags.index') : '#' }}"
                   class="submenu-link {{ $is('sound_tags.*') ? $activeLink : '' }}">
                  <i class="bi bi-tags-fill me-1"></i> Tags
                </a>
              </li>

              {{-- Licenses --}}
              <li class="submenu-item">
                <a href="{{ Route::has('sound_licenses.index') ? route('sound_licenses.index') : '#' }}"
                   class="submenu-link {{ $is('sound_licenses.*') ? $activeLink : '' }}">
                  <i class="bi bi-award-fill me-1"></i> Licenses
                </a>
              </li>

              {{-- Subcategories --}}
              <li class="submenu-item">
                <a href="{{ Route::has('sound_subcategories.index') ? route('sound_subcategories.index') : '#' }}"
                   class="submenu-link {{ $is('sound_subcategories.*') ? $activeLink : '' }}">
                  <i class="bi bi-diagram-3-fill me-1"></i> Subcategories
                </a>
              </li>
            </ul>
          </div>
        </div>
      </li>
    </ul>
  </div>
</nav>
