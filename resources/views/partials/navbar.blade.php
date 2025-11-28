{{-- =====================[ HEADER + NAVBAR – BeatHive (Role-based Smart Menu) ]===================== --}}
@php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Helper: cocokkan rute aktif
$is = fn (...$patterns) => request()->routeIs($patterns);

// ===== Role integer (sama seperti di AuthController) =====
$ROLE_ADMIN  = 1;
$ROLE_AUTHOR = 2;
$ROLE_USER   = 3;

$user = Auth::user();
$role = (int) ($user->role ?? $ROLE_USER);

$isAdmin  = $user && $role === $ROLE_ADMIN;
$isAuthor = $user && $role === $ROLE_AUTHOR;

// Author & Admin sama-sama bisa manage konten
$canManageMusic = $isAdmin || $isAuthor;
$canManageSfx   = $isAdmin || $isAuthor;

// ===== Flags aktif =====
$activeDashboard = $is('home');

// Semua route yang termasuk "Music area"
$activeMusic = $is(
    'tracks.*',
    'genres.*',
    'tags.*',
    'albums.*'
);

// Semua route yang termasuk "SFX area"
$activeSfx = $is(
    'sound_effects.*',
    'sound_categories.*',
    'sound_tags.*',
    'sound_licenses.*',
    'sound_subcategories.*'
);

$activePricing = $is('pricing', 'pricing.index');
$activeSaved   = $is('saved.*', 'favorites.*');

// Kelas utilitas
$activeLi   = 'active';
$activeLink = 'is-active';
@endphp

<nav class="main-navbar">
  <div class="container">
    <ul>
      {{-- =====================[ MAIN MENU – SEMUA ROLE ]===================== --}}

      {{-- Dashboard --}}
      <li class="menu-item {{ $activeDashboard ? $activeLi : '' }}">
        <a href="{{ Route::has('home') ? route('home') : url('/') }}"
           class="menu-link {{ $activeDashboard ? $activeLink : '' }}">
          <span><i class="bi bi-grid-fill"></i> Dashboard</span>
        </a>
      </li>

      {{-- =====================[ MUSIC ]===================== --}}
      @if($canManageMusic)
        {{-- ADMIN / AUTHOR: Music sebagai DROPDOWN (manage) --}}
        <li class="menu-item has-sub {{ $activeMusic ? $activeLi : '' }}">
          <a href="#" class="menu-link {{ $activeMusic ? $activeLink : '' }}">
            <span><i class="bi bi-music-note-list"></i> Music</span>
          </a>

          <div class="submenu">
            <div class="submenu-group-wrapper">
              <ul class="submenu-group">
                {{-- Explore all music --}}
                <li class="submenu-item">
                  <a href="{{ Route::has('tracks.index') ? route('tracks.index') : '#' }}"
                     class="submenu-link {{ $is('tracks.index','tracks.show','tracks.edit') ? $activeLink : '' }}">
                    Explore all music
                  </a>
                </li>

                {{-- Genres --}}
                <li class="submenu-item">
                  <a href="{{ Route::has('genres.index') ? route('genres.index') : '#' }}"
                     class="submenu-link {{ $is('genres.*','tags.*') ? $activeLink : '' }}">
                    Genres
                  </a>
                </li>

                {{-- Moods --}}
                <li class="submenu-item">
                  <a href="{{ Route::has('albums.index') ? route('albums.index') : '#' }}"
                     class="submenu-link {{ $is('albums.*') ? $activeLink : '' }}">
                    Moods
                  </a>
                </li>

                {{-- Themes (sementara pakai albums.* juga) --}}
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
      @else
        {{-- USER BIASA: Music single link (no dropdown) --}}
        <li class="menu-item {{ $activeMusic ? $activeLi : '' }}">
          <a href="{{ Route::has('tracks.index') ? route('tracks.index') : '#' }}"
             class="menu-link {{ $activeMusic ? $activeLink : '' }}">
            <span><i class="bi bi-music-note-list"></i> Music</span>
          </a>
        </li>
      @endif

      {{-- =====================[ SOUND EFFECTS ]===================== --}}
      @if($canManageSfx)
        {{-- ADMIN / AUTHOR: Sound Effects sebagai DROPDOWN (manage) --}}
        <li class="menu-item has-sub {{ $activeSfx ? $activeLi : '' }}">
          <a href="#" class="menu-link {{ $activeSfx ? $activeLink : '' }}">
            <span><i class="bi bi-soundwave"></i> Sound Effects</span>
          </a>

          <div class="submenu">
            <div class="submenu-group-wrapper">
              <ul class="submenu-group">
                {{-- Library (index) --}}
                <li class="submenu-item">
                  <a href="{{ Route::has('sound_effects.index') ? route('sound_effects.index') : '#' }}"
                     class="submenu-link {{ $is('sound_effects.index','sound_effects.show') ? $activeLink : '' }}">
                    <i class="bi bi-collection me-1"></i> Library
                  </a>
                </li>

                {{-- Add (author + admin) --}}
                <li class="submenu-item">
                  <a href="{{ Route::has('sound_effects.create') ? route('sound_effects.create') : '#' }}"
                     class="submenu-link {{ $is('sound_effects.create') ? $activeLink : '' }}">
                    <i class="bi bi-plus-circle me-1"></i> Add Sound Effect
                  </a>
                </li>

                {{-- ===== Master data: khusus ADMIN (opsional) ===== --}}
                @if($isAdmin)
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
                @endif
              </ul>
            </div>
          </div>
        </li>
      @else
        {{-- USER BIASA: Sound Effects single link (no dropdown) --}}
        <li class="menu-item {{ $activeSfx ? $activeLi : '' }}">
          <a href="{{ Route::has('sound_effects.index') ? route('sound_effects.index') : '#' }}"
             class="menu-link {{ $activeSfx ? $activeLink : '' }}">
            <span><i class="bi bi-soundwave"></i> Sound Effects</span>
          </a>
        </li>
      @endif
      {{-- Authors (Admin only) --}}



        @can('admin-only')


          <li class="menu-item {{ request()->routeIs('author.*') ? $activeLi : '' }}">


            <a href="{{ route('author.index') }}"


              class="menu-link {{ request()->routeIs('author.*') ? $activeLink : '' }}">


              <span><i class="bi bi-people-fill"></i> Authors</span>


            </a>


          </li>


        @endcan

      {{-- Upload --}}
      {{-- =====================[ LAINNYA ]===================== --}}

      {{-- Pricing (kalau dipakai) --}}
      @if(Route::has('pricing.index'))
      <li class="menu-item {{ $activePricing ? $activeLi : '' }}">
        <a href="{{ route('pricing.index') }}"
           class="menu-link {{ $activePricing ? $activeLink : '' }}">
          <span><i class="bi bi-currency-dollar"></i> Pricing</span>
        </a>
      </li>
      @endif

      {{-- Saved (opsional) --}}
      <li class="menu-item {{ $activeSaved ? $activeLi : '' }}">
        <a href="{{ Route::has('saved.index')
                    ? route('saved.index')
                    : (Route::has('favorites.index') ? route('favorites.index') : '#') }}"
           class="menu-link {{ $activeSaved ? $activeLink : '' }}">
          <span><i class="bi bi-bookmark-heart-fill"></i> Saved</span>
        </a>
      </li>

    </ul>
  </div>
</nav>
