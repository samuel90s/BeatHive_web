{{-- =====================[ HEADER + NAVBAR – BeatHive ]===================== --}}
@php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

$is = fn (...$patterns) => request()->routeIs($patterns);

$ROLE_ADMIN  = 1;
$ROLE_AUTHOR = 2;
$ROLE_USER   = 3;

$user = Auth::user();
$role = (int) ($user->role ?? $ROLE_USER);

$isAdmin  = $user && $role === $ROLE_ADMIN;
$isAuthor = $user && $role === $ROLE_AUTHOR;

$canManageMusic = $isAdmin || $isAuthor;
$canManageSfx   = $isAdmin || $isAuthor;

$activeDashboard = $is('home');

$activeMusic = $is(
    'tracks.*',
    'genres.*',
    'tags.*',
    'albums.*'
);

$activeSfx = $is(
    'sound_effects.*',
    'sound_categories.*',
    'sound_tags.*',
    'sound_licenses.*',
    'sound_subcategories.*'
);

$activePricing = $is('pricing', 'pricing.index');
$activeSaved   = $is('saved.*', 'favorites.*');

$activeLi   = 'active';
$activeLink = 'is-active';


/* ================= STYLE VARIABLES ================= */

$navStyle = "display:flex;align-items:center;gap:10px;
font-size:clamp(14px,1.1vw,18px);
padding:12px 18px;
border-radius:10px;
text-decoration:none;";

$iconStyle = "font-size:clamp(18px,1.5vw,22px);";

@endphp


<nav class="main-navbar">
  <div class="container">
    <ul style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">


      {{-- DASHBOARD --}}
      <li class="menu-item {{ $activeDashboard ? $activeLi : '' }}">
        <a href="{{ Route::has('home') ? route('home') : url('/') }}"
           class="menu-link {{ $activeDashboard ? $activeLink : '' }}"
           style="{{ $navStyle }}">
          <span>
            <i class="bi bi-grid-fill" style="{{ $iconStyle }}"></i>
            Dashboard
          </span>
        </a>
      </li>


      {{-- ================= MUSIC ================= --}}
      @if($canManageMusic)

        <li class="menu-item has-sub {{ $activeMusic ? $activeLi : '' }}">
          <a href="#"
             class="menu-link {{ $activeMusic ? $activeLink : '' }}"
             style="{{ $navStyle }}">
            <span>
              <i class="bi bi-music-note-list" style="{{ $iconStyle }}"></i>
              Music
            </span>
          </a>

          <div class="submenu">
            <ul class="submenu-group">

              <li class="submenu-item">
                <a href="{{ Route::has('tracks.index') ? route('tracks.index') : '#' }}"
                   class="submenu-link {{ $is('tracks.index','tracks.show','tracks.edit') ? $activeLink : '' }}"
                   style="font-size:clamp(13px,1vw,16px);padding:10px 14px;">
                  Explore all music
                </a>
              </li>

              <li class="submenu-item">
                <a href="{{ Route::has('genres.index') ? route('genres.index') : '#' }}"
                   class="submenu-link {{ $is('genres.*','tags.*') ? $activeLink : '' }}"
                   style="font-size:clamp(13px,1vw,16px);padding:10px 14px;">
                  Genres
                </a>
              </li>

              <li class="submenu-item">
                <a href="{{ Route::has('albums.index') ? route('albums.index') : '#' }}"
                   class="submenu-link {{ $is('albums.*') ? $activeLink : '' }}"
                   style="font-size:clamp(13px,1vw,16px);padding:10px 14px;">
                  Moods
                </a>
              </li>

              <li class="submenu-item">
                <a href="{{ Route::has('albums.index') ? route('albums.index') : '#' }}"
                   class="submenu-link {{ $is('albums.*') ? $activeLink : '' }}"
                   style="font-size:clamp(13px,1vw,16px);padding:10px 14px;">
                  Themes
                </a>
              </li>

            </ul>
          </div>
        </li>

      @else

        <li class="menu-item {{ $activeMusic ? $activeLi : '' }}">
          <a href="{{ Route::has('tracks.index') ? route('tracks.index') : '#' }}"
             class="menu-link {{ $activeMusic ? $activeLink : '' }}"
             style="{{ $navStyle }}">
            <span>
              <i class="bi bi-music-note-list" style="{{ $iconStyle }}"></i>
              Music
            </span>
          </a>
        </li>

      @endif


      {{-- ================= SOUND EFFECTS ================= --}}
      <li class="menu-item has-sub {{ $activeSfx ? $activeLi : '' }}">
        <a href="#" class="menu-link {{ $activeSfx ? $activeLink : '' }}">
          <span><i class="bi bi-soundwave"></i> Sound Effects</span>
        </a>

        <div class="submenu">
          <div class="submenu-group-wrapper">
            <ul class="submenu-group">

              {{-- Library --}}
              <li class="submenu-item">
                <a href="{{ route('sound_effects.index') }}"
                   class="submenu-link {{ $is('sound_effects.index','sound_effects.show') ? $activeLink : '' }}">
                  <i class="bi bi-collection me-1"></i> Library
                </a>
              </li>

              {{-- Add --}}
              <li class="submenu-item">
                <a href="{{ route('sound_effects.create') }}"
                   class="submenu-link {{ $is('sound_effects.create') ? $activeLink : '' }}">
                  <i class="bi bi-plus-circle me-1"></i> Add Sound Effect
                </a>
              </li>

              {{-- ADMIN ONLY --}}
              @if($isAdmin)
              <li class="submenu-item">
                <a href="{{ route('sound_categories.index') }}"
                   class="submenu-link {{ $is('sound_categories.*') ? $activeLink : '' }}">
                  <i class="bi bi-folder-fill me-1"></i> Categories
                </a>
              </li>

              <li class="submenu-item">
                <a href="{{ route('sound_tags.index') }}"
                   class="submenu-link {{ $is('sound_tags.*') ? $activeLink : '' }}">
                  <i class="bi bi-tags-fill me-1"></i> Tags
                </a>
              </li>

              <li class="submenu-item">
                <a href="{{ route('sound_licenses.index') }}"
                   class="submenu-link {{ $is('sound_licenses.*') ? $activeLink : '' }}">
                  <i class="bi bi-award-fill me-1"></i> Licenses
                </a>
              </li>

              <li class="submenu-item">
                <a href="{{ route('sound_subcategories.index') }}"
                   class="submenu-link {{ $is('sound_subcategories.*') ? $activeLink : '' }}">
                  <i class="bi bi-diagram-3-fill me-1"></i> Subcategories
                </a>
              </li>
              @endif

            </ul>
          </div>
        </div>
      </li>


      {{-- ===== SFX CATEGORIES — menu item terpisah ===== --}}

      <li class="menu-item {{ $is('sound_effects.foley') ? $activeLi : '' }}">
        <a href="{{ route('sound_effects.foley') }}"
           class="menu-link {{ $is('sound_effects.foley') ? $activeLink : '' }}"
           style="{{ $navStyle }}">
          <span><i class="bi bi-volume-up" style="{{ $iconStyle }}"></i> Foley</span>
        </a>
      </li>

      <li class="menu-item {{ $is('sound_effects.soundscape') ? $activeLi : '' }}">
        <a href="{{ route('sound_effects.soundscape') }}"
           class="menu-link {{ $is('sound_effects.soundscape') ? $activeLink : '' }}"
           style="{{ $navStyle }}">
          <span><i class="bi bi-globe" style="{{ $iconStyle }}"></i> Soundscape</span>
        </a>
      </li>

      <li class="menu-item {{ $is('sound_effects.ambience') ? $activeLi : '' }}">
        <a href="{{ route('sound_effects.ambience') }}"
           class="menu-link {{ $is('sound_effects.ambience') ? $activeLink : '' }}"
           style="{{ $navStyle }}">
          <span><i class="bi bi-wind" style="{{ $iconStyle }}"></i> Ambience</span>
        </a>
      </li>

      <li class="menu-item {{ $is('sound_effects.soundscoring') ? $activeLi : '' }}">
        <a href="{{ route('sound_effects.soundscoring') }}"
           class="menu-link {{ $is('sound_effects.soundscoring') ? $activeLink : '' }}"
           style="{{ $navStyle }}">
          <span><i class="bi bi-film" style="{{ $iconStyle }}"></i> Sound Scoring</span>
        </a>
      </li>


      {{-- AUTHORS --}}
      @can('admin-only')
        <li class="menu-item {{ request()->routeIs('author.*') ? $activeLi : '' }}">
          <a href="{{ route('author.index') }}"
             class="menu-link {{ request()->routeIs('author.*') ? $activeLink : '' }}"
             style="{{ $navStyle }}">
            <span>
              <i class="bi bi-people-fill" style="{{ $iconStyle }}"></i>
              Authors
            </span>
          </a>
        </li>
      @endcan


      {{-- PRICING --}}
      @if(Route::has('pricing.index'))
        <li class="menu-item {{ $activePricing ? $activeLi : '' }}">
          <a href="{{ route('pricing.index') }}"
             class="menu-link {{ $activePricing ? $activeLink : '' }}"
             style="{{ $navStyle }}">
            <span>
              <i class="bi bi-currency-dollar" style="{{ $iconStyle }}"></i>
              Pricing
            </span>
          </a>
        </li>
      @endif


      {{-- SAVED --}}
      <li class="menu-item {{ $activeSaved ? $activeLi : '' }}">
        <a href="{{ Route::has('saved.index')
            ? route('saved.index')
            : (Route::has('favorites.index') ? route('favorites.index') : '#') }}"
           class="menu-link {{ $activeSaved ? $activeLink : '' }}"
           style="{{ $navStyle }}">
          <span>
            <i class="bi bi-bookmark-heart-fill" style="{{ $iconStyle }}"></i>
            Saved
          </span>
        </a>
      </li>


    </ul>
  </div>
</nav>