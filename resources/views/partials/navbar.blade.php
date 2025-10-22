{{-- =====================[ HEADER + NAVBAR – BeatHive ]===================== --}}
@php
use Illuminate\Support\Facades\Route;

// Helper: cocokkan rute aktif
$is = fn(...$patterns) => request()->routeIs($patterns);

// Flags top-level
$activeDashboard = $is('home');
$activeCatalog = $is('tracks.*','genres.*','tags.*','albums.*');
$activeGenres = $is('genres.*');
$activeUpload = $is('tracks.create','tracks.bulk.*');
$activeSales = $is('orders.*','licenses.*','payouts.*');
$activeAnalytics = $is('analytics','analytics.*');
$activeSupport = $is('tickets.*','reviews.*','faq.public','faqs.*','faq-categories.*');
$activeExtras = $is('pricing','pages.pricing','licensing','pages.licensing','how-to-credit','credits.guide','contact','pages.contact')
|| $is('faq.public');

// Role check (ubah sesuai skema kamu)
$isAdmin = auth()->check() && (auth()->user()->role ?? null) == 1;

// Kelas utilitas (sesuaikan dengan CSS tema kamu)
$activeLi = 'active';
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

      {{-- Catalog --}}
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
      {{-- Sound Effects (mega + 3-level, kolom 10 item) --}}
      @php
      $routeAllSfx = Route::has('sfx.index') ? route('sfx.index') : (Route::has('tracks.index') ? route('tracks.index', ['type'=>'sfx']) : '#');
      $routeSfxGenres = Route::has('sfx.genres') ? route('sfx.genres') : (Route::has('genres.index') ? route('genres.index', ['type'=>'sfx']) : '#');
      $routeSfxMoods = Route::has('sfx.moods') ? route('sfx.moods') : (Route::has('tracks.index') ? route('tracks.index', ['type'=>'sfx','scope'=>'moods']) : '#');
      $routeSfxThemes = Route::has('sfx.themes') ? route('sfx.themes') : (Route::has('tracks.index') ? route('tracks.index', ['type'=>'sfx','scope'=>'themes']) : '#');

      $sfxCats = [
      'Animal'=>'animal','Explosions'=>'explosions','Horror'=>'horror','Impacts'=>'impacts','Multimedia and UI'=>'multimedia-and-ui','Public Places'=>'public-places','Technology'=>'technology',
      'Bell'=>'bell','Fantasy'=>'fantasy','Hospital'=>'hospital','Industrial'=>'industrial','Musical'=>'musical','Science Fiction'=>'science-fiction','Vehicles'=>'vehicles',
      'Cartoon'=>'cartoon','Foley'=>'foley','Household'=>'household','Lab'=>'lab','Nature'=>'nature','Sound Design'=>'sound-design','Warfare'=>'warfare',
      'Emergency'=>'emergency','Food and Drink'=>'food-and-drink','Human'=>'human','Leisure'=>'leisure','Office'=>'office','Sport'=>'sport',
      ];

      // === chunk per 10 item per kolom ===
      $sfxChunks = array_chunk($sfxCats, 10, true);
      @endphp

      <li class="menu-item has-sub">
        <a href="#" class="menu-link">
          <span><i class="bi bi-soundwave"></i> Sound Effects</span>
        </a>

        <div class="submenu">
          <div class="submenu-group-wrapper">
            <ul class="submenu-group">
              {{-- All Sound Effects (punya subsubmenu kolom) --}}
              <li class="submenu-item has-sub">
                <a href="{{ $routeAllSfx }}" class="submenu-link">All Sound Effects</a>

                {{-- 3rd level: by genre, 10 rows per column --}}
                <ul class="subsubmenu subsubmenu-columns-10"
                  style="display:grid;
             grid-auto-flow:column;
             grid-template-rows:repeat(10,auto);
             row-gap:8px; column-gap:28px;
             min-width:640px; /* lebar panel agar muat 2-3 kolom */
             padding-right:8px;">

                  @foreach($sfxCats as $label => $slug)
                  <li class="subsubmenu-item" style="break-inside:avoid;">
                    <a href="{{ Route::has('sfx.category')
                    ? route('sfx.category', $slug)
                    : (Route::has('tracks.index') ? route('tracks.index', ['type'=>'sfx','cat'=>$slug]) : '#') }}"
                      class="subsubmenu-link">{{ $label }}</a>
                  </li>
                  @endforeach
                </ul>
              </li>


              {{-- Sama seperti Music --}}
              <li class="submenu-item">
                <a href="{{ $routeSfxGenres }}" class="submenu-link">Genres</a>
              </li>
              <li class="submenu-item">
                <a href="{{ $routeSfxMoods }}" class="submenu-link">Moods</a>
              </li>
              <li class="submenu-item">
                <a href="{{ $routeSfxThemes }}" class="submenu-link">Themes</a>
              </li>
            </ul>
          </div>
        </div>
      </li>
      {{-- Pricing --}}
      <li class="menu-item {{ request()->routeIs('pricing') ? $activeLi : '' }}">
        <a href="{{ route('pricing.index') }}" class="menu-link {{ request()->routeIs('pricing') ? $activeLink : '' }}">
          <span><i class="bi bi-currency-dollar"></i> Pricing</span>
        </a>
      </li>
      {{-- Authors (Admin only) --}}
@can('admin-only')
  <li class="menu-item {{ request()->routeIs('author.*') ? $activeLi : '' }}">
    <a href="{{ route('author.index') }}"
       class="menu-link {{ request()->routeIs('author.*') ? $activeLink : '' }}">
      <span><i class="bi bi-people-fill"></i> Authors</span>
    </a>
  </li>
@endcan
      {{-- Genres (shortcut) --}}
      <li class="menu-item {{ $activeGenres ? $activeLi : '' }}">
        <a href="{{ Route::has('genres.index') ? route('genres.index') : '#' }}"
          class="menu-link {{ $activeGenres ? $activeLink : '' }}">
          <span><i class="bi bi-tags-fill"></i> Genres</span>
        </a>
      </li>
      {{-- ===== Sound Effects (module) ===== --}}
@php
  $activeSfx = request()->routeIs('sound_effects.*')
    || request()->routeIs('sound_categories.*')
    || request()->routeIs('sound_tags.*')
    || request()->routeIs('sound_licenses.*');
@endphp

<li class="menu-item has-sub {{ $activeSfx ? $activeLi : '' }}">
  <a href="#" class="menu-link {{ $activeSfx ? $activeLink : '' }}">
    <span><i class="bi bi-soundwave"></i> Sound Effects</span>
  </a>

  <div class="submenu">
    <div class="submenu-group-wrapper">
      <ul class="submenu-group">
        {{-- Library (Index) --}}
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

        {{-- Admin-only taxonomy --}}
        @can('admin-only')
          <li class="submenu-item">
            <a href="{{ Route::has('sound_categories.index') ? route('sound_categories.index') : '#' }}"
               class="submenu-link {{ $is('sound_categories.*') ? $activeLink : '' }}">
              <i class="bi bi-folder me-1"></i> Categories
            </a>
          </li>
          <li class="submenu-item">
            <a href="{{ Route::has('sound_tags.index') ? route('sound_tags.index') : '#' }}"
               class="submenu-link {{ $is('sound_tags.*') ? $activeLink : '' }}">
              <i class="bi bi-tags me-1"></i> Tags
            </a>
          </li>
          <li class="submenu-item">
            <a href="{{ Route::has('sound_licenses.index') ? route('sound_licenses.index') : '#' }}"
               class="submenu-link {{ $is('sound_licenses.*') ? $activeLink : '' }}">
              <i class="bi bi-award me-1"></i> Licenses
            </a>
          </li>
        @endcan
      </ul>
    </div>
  </div>
</li>
<!-- 
tage -->

{{-- ===== SOUND FX MANAGEMENT ===== --}}
@php
  $activeSfxData = request()->routeIs('sound_categories.*')
    || request()->routeIs('sound_tags.*')
    || request()->routeIs('sound_licenses.*');
@endphp

<li class="menu-item has-sub {{ $activeSfxData ? $activeLi : '' }}">
  <a href="#" class="menu-link {{ $activeSfxData ? $activeLink : '' }}">
    <span><i class="bi bi-sliders"></i> SFX Data</span>
  </a>

  <div class="submenu">
    <div class="submenu-group-wrapper">
      <ul class="submenu-group">

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
        <!-- sub category? -->
         <li class="submenu-item">
          <a href="{{ route('sound_subcategories.index') }}"
            class="submenu-link {{ $is('sound_subcategories.*') ? $activeLink : '' }}">
            <i class="bi bi-diagram-3-fill me-1"></i> Subcategories
          </a>
        </li>

        
      </ul>
    </div>
  </div>
</li>


      {{-- Sales --}}
      <li class="menu-item has-sub {{ $activeSales ? $activeLi : '' }}">
        <a href="#" class="menu-link {{ $activeSales ? $activeLink : '' }}">
          <span><i class="bi bi-bag-check-fill"></i> Sales</span>
        </a>
        <div class="submenu">
          <div class="submenu-group-wrapper">
            <ul class="submenu-group">
              <li class="submenu-item">
                <a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}"
                  class="submenu-link {{ $is('orders.*') ? $activeLink : '' }}">
                  Orders
                </a>
              </li>
              <li class="submenu-item">
                <a href="{{ Route::has('licenses.index') ? route('licenses.index') : '#' }}"
                  class="submenu-link {{ $is('licenses.*') ? $activeLink : '' }}">
                  Licenses
                </a>
              </li>
              <li class="submenu-item">
                <a href="{{ Route::has('payouts.index') ? route('payouts.index') : '#' }}"
                  class="submenu-link {{ $is('payouts.*') ? $activeLink : '' }}">
                  Payouts
                </a>
              </li>
            </ul>
          </div>
        </div>
      </li>

      <!-- {{-- Analytics --}}
      <li class="menu-item {{ $activeAnalytics ? $activeLi : '' }}">
        <a href="{{ Route::has('analytics.index') ? route('analytics.index') : (Route::has('analytics') ? route('analytics') : '#') }}"
          class="menu-link {{ $activeAnalytics ? $activeLink : '' }}">
          <span><i class="bi bi-graph-up"></i> Analytics</span>
        </a>
      </li> -->

      {{-- Support --}}
      <li class="menu-item has-sub {{ $activeSupport ? $activeLi : '' }}">
        <a href="#" class="menu-link {{ $activeSupport ? $activeLink : '' }}">
          <span><i class="bi bi-life-preserver"></i> Support</span>
        </a>
        <div class="submenu">
          <div class="submenu-group-wrapper">
            <ul class="submenu-group">
              <li class="submenu-item">
                <a href="{{ Route::has('tickets.index') ? route('tickets.index') : '#' }}"
                  class="submenu-link {{ $is('tickets.*') ? $activeLink : '' }}">
                  Tickets
                </a>
              </li>
              <li class="submenu-item">
                <a href="{{ Route::has('reviews.index') ? route('reviews.index') : '#' }}"
                  class="submenu-link {{ $is('reviews.*') ? $activeLink : '' }}">
                  Reviews
                </a>
              </li>
              {{-- Help Center (FAQ) publik --}}
              <li class="submenu-item">
                <a href="{{ Route::has('faq.public') ? route('faq.public') : '#' }}"
                  class="submenu-link {{ $is('faq.public') ? $activeLink : '' }}">
                  <i class="bi bi-question-circle me-1"></i> Help Center (FAQ)
                </a>
              </li>

              @auth
              @if($isAdmin)
              <li>
                <hr class="dropdown-divider" />
              </li>
              <li class="submenu-item">
                <a href="{{ Route::has('faqs.index') ? route('faqs.index') : '#' }}"
                  class="submenu-link {{ $is('faqs.*') ? $activeLink : '' }}">
                  <i class="bi bi-ui-checks-grid me-1"></i> Manage FAQs
                </a>
              </li>
              <li class="submenu-item">
                <a href="{{ Route::has('faq-categories.index') ? route('faq-categories.index') : '#' }}"
                  class="submenu-link {{ $is('faq-categories.*') ? $activeLink : '' }}">
                  <i class="bi bi-folder2-open me-1"></i> FAQ Categories
                </a>
              </li>
              @endif
              @endauth
            </ul>
          </div>
        </div>
      </li>

      {{-- (Login / Register di top-right) --}}
    </ul>
  </div>
</nav>