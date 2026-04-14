@extends('layouts.master')

@section('title', ($currentCategory->name ?? 'Sound Effects') . ' – BeatHive')
@section('heading', 'Sound Effects')

@section('content')
@php
    $q = request('q', '');

    $title = $currentCategory->name
          ?? $currentSubcategory->name
          ?? 'Sound Effects';

    $hasSounds     = isset($sounds) && $sounds->count() > 0;
    $currentSort   = request('sort', 'popular');
    $currentDur    = request('duration', 'all');
    $currentCatId  = $currentCategory->id ?? null;
@endphp

<div class="es-wrap">

  {{-- ============ LEFT SIDEBAR ============ --}}
  <aside class="es-sidebar" id="es-sidebar">

    <div class="es-sidebar-inner">

      {{-- Search --}}
      <form action="{{ route('sound_effects.browse') }}" method="GET" class="es-search-form">
        @if($currentCatId)
          <input type="hidden" name="category" value="{{ $currentCatId }}">
        @endif
        <div class="es-search-box">
          <svg class="es-search-icon" width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
          </svg>
          <input type="text" name="q" value="{{ $q }}" placeholder="Search sound effects…" class="es-search-input" autocomplete="off">
          @if($q)
            <a href="{{ route('sound_effects.browse', array_filter(['category' => $currentCatId])) }}" class="es-search-clear">
              <svg width="12" height="12" viewBox="0 0 12 12" fill="currentColor"><path d="M6 5.293l4.146-4.147a.5.5 0 0 1 .708.708L6.707 6l4.147 4.146a.5.5 0 0 1-.708.708L6 6.707l-4.146 4.147a.5.5 0 0 1-.708-.708L5.293 6 1.146 1.854a.5.5 0 1 1 .708-.708L6 5.293z"/></svg>
            </a>
          @endif
        </div>
      </form>

      {{-- Categories --}}
      <div class="es-sidebar-section">
        <div class="es-sidebar-label">Categories</div>
        <ul class="es-sidebar-list">
          <li>
            <a href="{{ route('sound_effects.index') }}"
               class="es-sidebar-link {{ !$currentCatId && !request('subcategory') ? 'active' : '' }}">
              <span class="es-sidebar-dot"></span>
              All categories
            </a>
          </li>
          @foreach($categories as $cat)
            <li>
              <a href="{{ route('sound_effects.browse', ['category' => $cat->id]) }}"
                 class="es-sidebar-link {{ ($currentCatId == $cat->id) ? 'active' : '' }}">
                <span class="es-sidebar-dot"></span>
                {{ $cat->name }}
                <span class="es-sidebar-count">{{ $cat->sound_effects_count ?? '' }}</span>
              </a>

              {{-- Subcategories under active category --}}
              @if($currentCatId == $cat->id && isset($subGroups[$cat->id]))
                <ul class="es-sidebar-sublist">
                  @foreach($subGroups[$cat->id] as $sub)
                    <li>
                      <a href="{{ route('sound_effects.browse', ['category' => $cat->id, 'subcategory' => $sub->id]) }}"
                         class="es-sidebar-sublink {{ (request('subcategory') == $sub->id) ? 'active' : '' }}">
                        {{ $sub->name }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              @endif
            </li>
          @endforeach
        </ul>
      </div>

      {{-- Duration Filter --}}
      <div class="es-sidebar-section">
        <div class="es-sidebar-label">Duration</div>
        <ul class="es-sidebar-list">
          @foreach(['all' => 'Any duration', 'short' => 'Short (0–30s)', 'medium' => 'Medium (30s–2min)', 'long' => 'Long (2min+)'] as $val => $label)
            <li>
              <a href="{{ request()->fullUrlWithQuery(['duration' => $val]) }}"
                 class="es-sidebar-link {{ $currentDur === $val ? 'active' : '' }}">
                <span class="es-sidebar-dot"></span>
                {{ $label }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>

    </div>
  </aside>

  {{-- ============ MAIN CONTENT ============ --}}
  <main class="es-main">

    {{-- Breadcrumb --}}
    <nav class="es-breadcrumb">
      <a href="{{ route('sound_effects.index') }}">Sound Effects</a>
      @if($currentCategory)
        <span>/</span>
        <a href="{{ route('sound_effects.browse', ['category' => $currentCategory->id]) }}">{{ $currentCategory->name }}</a>
      @endif
      @if($currentSubcategory)
        <span>/</span>
        <span>{{ $currentSubcategory->name }}</span>
      @endif
    </nav>

    {{-- Page Header --}}
    <div class="es-page-header">
      <div class="es-page-header-left">
        <h1 class="es-page-title">{{ $title }}</h1>
        @if($hasSounds)
          <div class="es-page-count">{{ number_format($sounds->total()) }} sounds</div>
        @endif
      </div>
      <div class="es-page-header-right">
        {{-- Sort --}}
        <div class="es-sort-wrap" id="sort-wrap">
          <button class="es-sort-btn" id="sort-btn" type="button">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor" style="opacity:.7"><path d="M3.5 2.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 11.293V2.5zm3.5 1a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z"/></svg>
            Sort: {{ ucfirst($currentSort) }}
            <svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>
          </button>
          <div class="es-sort-dropdown" id="sort-dropdown">
            @foreach(['popular' => 'Popular', 'newest' => 'Newest', 'shortest' => 'Shortest', 'longest' => 'Longest'] as $val => $label)
              <a href="{{ request()->fullUrlWithQuery(['sort' => $val]) }}"
                 class="es-sort-option {{ $currentSort === $val ? 'active' : '' }}">
                @if($currentSort === $val)
                  <svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor"><path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022z"/></svg>
                @endif
                {{ $label }}
              </a>
            @endforeach
          </div>
        </div>

        {{-- Mobile sidebar toggle --}}
        <button class="es-filter-toggle" id="sidebar-toggle" type="button">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><path d="M1.5 3a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1h-13zM3 6.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm2 3a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/></svg>
          Filters
        </button>
      </div>
    </div>

    {{-- Active filters --}}
    @if($q || $currentDur !== 'all')
      <div class="es-active-filters">
        @if($q)
          <span class="es-active-chip">
            "{{ $q }}"
            <a href="{{ request()->fullUrlWithQuery(['q' => '']) }}" class="es-chip-remove">×</a>
          </span>
        @endif
        @if($currentDur !== 'all')
          <span class="es-active-chip">
            {{ ['short'=>'0–30s','medium'=>'30s–2min','long'=>'2min+'][$currentDur] ?? $currentDur }}
            <a href="{{ request()->fullUrlWithQuery(['duration' => 'all']) }}" class="es-chip-remove">×</a>
          </span>
        @endif
        <a href="{{ route('sound_effects.browse', array_filter(['category' => $currentCatId])) }}" class="es-clear-all">Clear all</a>
      </div>
    @endif

    {{-- ============ SOUND LIST ============ --}}
    <div class="es-sound-list" id="es-sound-list">

      {{-- Header row --}}
      <div class="es-list-header">
        <div class="es-col-play"></div>
        <div class="es-col-title">Title</div>
        <div class="es-col-category">Category</div>
        <div class="es-col-duration">Duration</div>
        <div class="es-col-actions"></div>
      </div>

      @if($hasSounds)
        @foreach($sounds as $sound)
          @php
            $trackTitle  = $sound->title ?? 'Untitled';
            $catName     = optional($sound->category)->name ?? '—';
            $subName     = optional($sound->subcategory)->name ?? null;

            $durSec = (int) ($sound->duration_seconds ?? 0);
            $durFmt = $durSec > 0
                ? sprintf('%d:%02d', floor($durSec / 60), $durSec % 60)
                : '—:—';

            $previewUrl = $sound->preview_path ? asset($sound->preview_path) : null;
            $waveUrl    = $sound->waveform_image ? asset($sound->waveform_image) : null;
          @endphp

          <div class="es-sound-row" data-sound-id="{{ $sound->id }}" data-audio="{{ $previewUrl }}">

            {{-- Play button --}}
            <div class="es-col-play">
              <button class="es-play-btn" type="button"
                      data-audio-src="{{ $previewUrl }}"
                      data-title="{{ $trackTitle }}"
                      aria-label="Play {{ $trackTitle }}">
                <svg class="icon-play" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                <svg class="icon-pause" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="display:none"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                <svg class="icon-loading" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;animation:spin 1s linear infinite"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
              </button>
            </div>

            {{-- Waveform + Title --}}
            <div class="es-col-title">
              <div class="es-title-wrap">
                <div class="es-waveform" data-wave="{{ $waveUrl }}">
                  <div class="es-waveform-bg">
                    @if($waveUrl)
                      <div class="es-waveform-img" style="background-image:url('{{ $waveUrl }}')"></div>
                    @else
                      <div class="es-waveform-bars">
                        @for($i=0; $i<40; $i++)
                          <span style="height:{{ rand(15,85) }}%"></span>
                        @endfor
                      </div>
                    @endif
                  </div>
                  <div class="es-waveform-progress"></div>
                </div>
                <a href="{{ route('sound_effects.show', $sound->id) }}" class="es-sound-title">{{ $trackTitle }}</a>
                @if($subName)
                  <span class="es-sound-sub">{{ $subName }}</span>
                @endif
              </div>
            </div>

            {{-- Category --}}
            <div class="es-col-category">
              <a href="{{ route('sound_effects.browse', ['category' => optional($sound->category)->id]) }}"
                 class="es-category-badge">
                {{ $catName }}
              </a>
            </div>

            {{-- Duration --}}
            <div class="es-col-duration">{{ $durFmt }}</div>

            {{-- Actions --}}
            <div class="es-col-actions">
              {{-- Favourite --}}
              <button class="es-action-btn es-btn-fav" type="button" title="Save" data-id="{{ $sound->id }}">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><path d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/></svg>
              </button>

              {{-- Add to collection --}}
              <button class="es-action-btn" type="button" title="Add to collection">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
              </button>

              {{-- Download --}}
              <a href="{{ route('sound_effects.show', $sound->id) }}"
                 class="es-action-btn es-btn-download" title="Download">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/><path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/></svg>
              </a>

              {{-- More --}}
              <button class="es-action-btn es-btn-more" type="button" title="More options">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/></svg>
              </button>
            </div>

          </div>
        @endforeach

      @else
        <div class="es-empty">
          <svg width="56" height="56" viewBox="0 0 24 24" fill="currentColor" opacity=".2"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>
          <h3>No sound effects found</h3>
          <p>Try adjusting your search or filters</p>
          <a href="{{ route('sound_effects.index') }}" class="es-btn-primary">Browse all sounds</a>
        </div>
      @endif
    </div>

    {{-- Pagination --}}
    @if($hasSounds && method_exists($sounds, 'links'))
      <div class="es-pagination">
        {{ $sounds->withQueryString()->links() }}
      </div>
    @endif

  </main>

</div>

{{-- ============ STICKY MINI PLAYER ============ --}}
<div class="es-player" id="es-player" style="display:none">
  <div class="es-player-left">
    <button class="es-player-play" id="player-play-btn" type="button">
      <svg class="icon-play" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
      <svg class="icon-pause" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="display:none"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
    </button>
    <div class="es-player-info">
      <div class="es-player-title" id="player-title">—</div>
      <div class="es-player-category" id="player-category">Sound Effect</div>
    </div>
  </div>
  <div class="es-player-center">
    <div class="es-player-bar" id="player-bar">
      <div class="es-player-track" id="player-track">
        <div class="es-player-fill" id="player-fill"></div>
        <div class="es-player-thumb" id="player-thumb"></div>
      </div>
      <span class="es-player-time" id="player-time">0:00 / 0:00</span>
    </div>
  </div>
  <div class="es-player-right">
    <input type="range" class="es-volume-slider" id="volume-slider" min="0" max="1" step="0.05" value="1">
    <button class="es-player-close" id="player-close" type="button">
      <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg>
    </button>
  </div>
</div>

@endsection

@push('styles')
<style>
/* ===== ROOT VARIABLES ===== */
:root {
  --es-bg:        #0f0f0f;
  --es-bg2:       #181818;
  --es-bg3:       #222222;
  --es-bg4:       #2a2a2a;
  --es-border:    #2e2e2e;
  --es-text:      #ffffff;
  --es-text2:     #aaaaaa;
  --es-text3:     #666666;
  --es-accent:    #ff6b35;
  --es-accent-h:  #e55a2b;
  --es-green:     #3ecf8e;
  --es-row-h:     68px;
  --es-sidebar-w: 240px;
  --es-player-h:  72px;
}

@keyframes spin { to { transform: rotate(360deg); } }

/* ===== LAYOUT ===== */
.es-wrap {
  display: flex;
  min-height: calc(100vh - var(--es-player-h));
  background: var(--es-bg);
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  color: var(--es-text);
}

/* ===== SIDEBAR ===== */
.es-sidebar {
  width: var(--es-sidebar-w);
  flex-shrink: 0;
  border-right: 1px solid var(--es-border);
  position: sticky;
  top: 0;
  height: 100vh;
  overflow-y: auto;
  padding: 24px 0;
  scrollbar-width: thin;
  scrollbar-color: var(--es-bg4) transparent;
}

.es-sidebar-inner { padding: 0 16px; }

/* Search */
.es-search-form { margin-bottom: 28px; }
.es-search-box {
  position: relative;
  display: flex;
  align-items: center;
  background: var(--es-bg3);
  border: 1px solid var(--es-border);
  border-radius: 8px;
  padding: 0 12px;
  gap: 8px;
  transition: border-color .2s;
}
.es-search-box:focus-within { border-color: var(--es-accent); }
.es-search-icon { color: var(--es-text3); flex-shrink: 0; }
.es-search-input {
  flex: 1;
  background: none;
  border: none;
  outline: none;
  color: var(--es-text);
  font-size: 14px;
  padding: 10px 0;
  font-family: inherit;
}
.es-search-input::placeholder { color: var(--es-text3); }
.es-search-clear {
  color: var(--es-text3);
  display: flex;
  align-items: center;
  text-decoration: none;
  transition: color .2s;
}
.es-search-clear:hover { color: var(--es-text); }

/* Sidebar sections */
.es-sidebar-section { margin-bottom: 32px; }
.es-sidebar-label {
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: .8px;
  color: var(--es-text3);
  margin-bottom: 10px;
}
.es-sidebar-list {
  list-style: none;
  padding: 0;
  margin: 0;
}
.es-sidebar-link {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 7px 8px;
  border-radius: 6px;
  color: var(--es-text2);
  text-decoration: none;
  font-size: 14px;
  transition: all .15s;
}
.es-sidebar-link:hover,
.es-sidebar-link.active {
  background: var(--es-bg3);
  color: var(--es-text);
}
.es-sidebar-link.active { font-weight: 600; }
.es-sidebar-dot {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: var(--es-bg4);
  flex-shrink: 0;
  transition: background .15s;
}
.es-sidebar-link.active .es-sidebar-dot { background: var(--es-accent); }
.es-sidebar-count {
  margin-left: auto;
  font-size: 12px;
  color: var(--es-text3);
}

/* Subcategories */
.es-sidebar-sublist {
  list-style: none;
  padding: 4px 0 4px 22px;
  margin: 0;
}
.es-sidebar-sublink {
  display: block;
  padding: 5px 8px;
  border-radius: 5px;
  color: var(--es-text3);
  text-decoration: none;
  font-size: 13px;
  transition: all .15s;
}
.es-sidebar-sublink:hover,
.es-sidebar-sublink.active {
  color: var(--es-text);
  background: var(--es-bg3);
}

/* ===== MAIN CONTENT ===== */
.es-main {
  flex: 1;
  min-width: 0;
  padding: 24px 32px;
  padding-bottom: calc(var(--es-player-h) + 24px);
}

/* Breadcrumb */
.es-breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  color: var(--es-text3);
  margin-bottom: 24px;
}
.es-breadcrumb a {
  color: var(--es-text2);
  text-decoration: none;
  transition: color .15s;
}
.es-breadcrumb a:hover { color: var(--es-text); }
.es-breadcrumb span { opacity: .5; }

/* Page header */
.es-page-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  margin-bottom: 8px;
  flex-wrap: wrap;
  gap: 12px;
}
.es-page-title {
  font-size: 32px;
  font-weight: 700;
  letter-spacing: -.5px;
  margin: 0 0 4px 0;
  line-height: 1.1;
}
.es-page-count {
  font-size: 14px;
  color: var(--es-text2);
}
.es-page-header-right {
  display: flex;
  align-items: center;
  gap: 10px;
}

/* Sort */
.es-sort-wrap { position: relative; }
.es-sort-btn {
  background: var(--es-bg3);
  border: 1px solid var(--es-border);
  color: var(--es-text2);
  font-size: 13px;
  font-family: inherit;
  padding: 8px 14px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  gap: 6px;
  cursor: pointer;
  transition: all .15s;
}
.es-sort-btn:hover {
  background: var(--es-bg4);
  color: var(--es-text);
}
.es-sort-dropdown {
  display: none;
  position: absolute;
  top: calc(100% + 6px);
  right: 0;
  background: var(--es-bg2);
  border: 1px solid var(--es-border);
  border-radius: 10px;
  padding: 8px;
  min-width: 160px;
  z-index: 200;
  box-shadow: 0 8px 24px rgba(0,0,0,.5);
}
.es-sort-dropdown.show { display: block; }
.es-sort-option {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 9px 12px;
  border-radius: 6px;
  color: var(--es-text2);
  text-decoration: none;
  font-size: 14px;
  transition: all .15s;
}
.es-sort-option:hover,
.es-sort-option.active {
  background: var(--es-bg3);
  color: var(--es-text);
}

/* Filter toggle (mobile) */
.es-filter-toggle {
  background: var(--es-bg3);
  border: 1px solid var(--es-border);
  color: var(--es-text2);
  font-size: 13px;
  font-family: inherit;
  padding: 8px 14px;
  border-radius: 8px;
  display: none;
  align-items: center;
  gap: 6px;
  cursor: pointer;
}

/* Active filter chips */
.es-active-filters {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
  margin-bottom: 16px;
}
.es-active-chip {
  background: var(--es-bg3);
  border: 1px solid var(--es-border);
  color: var(--es-text2);
  font-size: 13px;
  padding: 4px 10px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  gap: 6px;
}
.es-chip-remove {
  color: var(--es-text3);
  text-decoration: none;
  font-size: 16px;
  line-height: 1;
  transition: color .15s;
}
.es-chip-remove:hover { color: var(--es-text); }
.es-clear-all {
  font-size: 13px;
  color: var(--es-accent);
  text-decoration: none;
}
.es-clear-all:hover { text-decoration: underline; }

/* ===== SOUND LIST ===== */
.es-sound-list { margin-top: 8px; }

/* Header row */
.es-list-header {
  display: grid;
  grid-template-columns: 56px 1fr 160px 80px 160px;
  align-items: center;
  padding: 0 8px;
  border-bottom: 1px solid var(--es-border);
  padding-bottom: 10px;
  margin-bottom: 4px;
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: .6px;
  color: var(--es-text3);
}

/* Sound row */
.es-sound-row {
  display: grid;
  grid-template-columns: 56px 1fr 160px 80px 160px;
  align-items: center;
  height: var(--es-row-h);
  padding: 0 8px;
  border-bottom: 1px solid transparent;
  border-radius: 8px;
  transition: background .15s;
  cursor: default;
}
.es-sound-row:hover {
  background: var(--es-bg2);
  border-color: var(--es-border);
}
.es-sound-row.playing {
  background: rgba(255, 107, 53, .06);
}

/* Play button */
.es-play-btn {
  width: 38px; height: 38px;
  border-radius: 50%;
  background: var(--es-bg4);
  border: 1px solid var(--es-border);
  color: var(--es-text2);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all .15s;
  flex-shrink: 0;
}
.es-play-btn:hover,
.es-sound-row.playing .es-play-btn {
  background: var(--es-accent);
  border-color: var(--es-accent);
  color: #fff;
}

/* Title + waveform column */
.es-col-title { min-width: 0; padding-right: 16px; }
.es-title-wrap { display: flex; flex-direction: column; gap: 4px; }
.es-sound-title {
  font-size: 14px;
  font-weight: 500;
  color: var(--es-text);
  text-decoration: none;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  transition: color .15s;
}
.es-sound-title:hover { color: var(--es-accent); }
.es-sound-sub {
  font-size: 12px;
  color: var(--es-text3);
}

/* Waveform */
.es-waveform {
  position: relative;
  height: 28px;
  overflow: hidden;
  border-radius: 4px;
  cursor: pointer;
  margin-bottom: 2px;
}
.es-waveform-bg {
  width: 100%;
  height: 100%;
  position: relative;
}
.es-waveform-img {
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  opacity: .45;
  filter: hue-rotate(0deg) saturate(1.5);
}
.es-waveform-bars {
  display: flex;
  align-items: flex-end;
  gap: 1px;
  width: 100%;
  height: 100%;
  padding: 0 2px;
}
.es-waveform-bars span {
  flex: 1;
  background: var(--es-bg4);
  border-radius: 1px;
  min-height: 10%;
}
.es-waveform-progress {
  position: absolute;
  top: 0; left: 0;
  height: 100%;
  width: 0%;
  background: linear-gradient(to right, var(--es-accent), rgba(255,107,53,.3));
  transition: width .1s linear;
  mix-blend-mode: screen;
}

/* Category badge */
.es-col-category {}
.es-category-badge {
  font-size: 12px;
  color: var(--es-text2);
  background: var(--es-bg3);
  border: 1px solid var(--es-border);
  padding: 3px 10px;
  border-radius: 20px;
  text-decoration: none;
  white-space: nowrap;
  transition: all .15s;
}
.es-category-badge:hover {
  background: var(--es-bg4);
  color: var(--es-text);
}

/* Duration */
.es-col-duration {
  font-size: 13px;
  color: var(--es-text2);
  font-variant-numeric: tabular-nums;
}

/* Actions */
.es-col-actions {
  display: flex;
  align-items: center;
  gap: 4px;
  justify-content: flex-end;
  opacity: 0;
  transition: opacity .15s;
}
.es-sound-row:hover .es-col-actions { opacity: 1; }

.es-action-btn {
  background: none;
  border: 1px solid transparent;
  color: var(--es-text3);
  width: 34px; height: 34px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all .15s;
  text-decoration: none;
  flex-shrink: 0;
}
.es-action-btn:hover {
  background: var(--es-bg4);
  border-color: var(--es-border);
  color: var(--es-text);
}
.es-btn-fav.active { color: var(--es-accent); }
.es-btn-download {
  background: var(--es-accent) !important;
  border-color: var(--es-accent) !important;
  color: #fff !important;
}
.es-btn-download:hover { background: var(--es-accent-h) !important; }

/* Empty state */
.es-empty {
  text-align: center;
  padding: 80px 24px;
  color: var(--es-text2);
}
.es-empty h3 { font-size: 20px; margin: 16px 0 8px; color: var(--es-text); }
.es-empty p { font-size: 14px; color: var(--es-text2); margin: 0 0 24px; }
.es-btn-primary {
  background: var(--es-accent);
  color: #fff;
  text-decoration: none;
  padding: 10px 22px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  transition: background .15s;
  display: inline-block;
}
.es-btn-primary:hover { background: var(--es-accent-h); color: #fff; }

/* Pagination */
.es-pagination {
  margin-top: 32px;
  display: flex;
  justify-content: center;
}
.es-pagination .pagination {
  display: flex; gap: 6px; list-style: none; margin: 0; padding: 0;
}
.es-pagination .page-item .page-link {
  background: var(--es-bg3);
  border: 1px solid var(--es-border);
  color: var(--es-text2);
  padding: 8px 14px;
  border-radius: 6px;
  text-decoration: none;
  font-size: 14px;
  transition: all .15s;
}
.es-pagination .page-item.active .page-link {
  background: var(--es-accent);
  border-color: var(--es-accent);
  color: #fff;
}
.es-pagination .page-item .page-link:hover {
  background: var(--es-bg4);
  color: var(--es-text);
}

/* ===== STICKY PLAYER ===== */
.es-player {
  position: fixed;
  bottom: 0; left: 0; right: 0;
  height: var(--es-player-h);
  background: var(--es-bg2);
  border-top: 1px solid var(--es-border);
  display: flex;
  align-items: center;
  gap: 24px;
  padding: 0 24px;
  z-index: 1000;
  backdrop-filter: blur(12px);
}
.es-player-left {
  display: flex; align-items: center; gap: 14px;
  min-width: 220px; flex-shrink: 0;
}
.es-player-play {
  width: 44px; height: 44px;
  border-radius: 50%;
  background: var(--es-accent);
  border: none; color: #fff;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  transition: background .15s;
  flex-shrink: 0;
}
.es-player-play:hover { background: var(--es-accent-h); }
.es-player-info { min-width: 0; }
.es-player-title {
  font-size: 14px; font-weight: 600;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.es-player-category { font-size: 12px; color: var(--es-text3); }
.es-player-center { flex: 1; min-width: 0; }
.es-player-bar { display: flex; align-items: center; gap: 12px; }
.es-player-track {
  flex: 1; height: 4px;
  background: var(--es-bg4);
  border-radius: 2px;
  position: relative;
  cursor: pointer;
}
.es-player-fill {
  position: absolute; top: 0; left: 0;
  height: 100%; width: 0%;
  background: var(--es-accent);
  border-radius: 2px;
  transition: width .1s linear;
  pointer-events: none;
}
.es-player-thumb {
  position: absolute; top: 50%; left: 0%;
  width: 14px; height: 14px;
  background: #fff;
  border-radius: 50%;
  transform: translate(-50%, -50%);
  opacity: 0;
  transition: opacity .15s, left .1s linear;
  pointer-events: none;
}
.es-player-track:hover .es-player-thumb { opacity: 1; }
.es-player-time {
  font-size: 12px; color: var(--es-text2);
  font-variant-numeric: tabular-nums;
  white-space: nowrap; flex-shrink: 0;
}
.es-player-right {
  display: flex; align-items: center; gap: 12px; flex-shrink: 0;
}
.es-volume-slider {
  width: 90px; accent-color: var(--es-accent); cursor: pointer;
}
.es-player-close {
  background: none; border: none;
  color: var(--es-text3); cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: color .15s;
}
.es-player-close:hover { color: var(--es-text); }

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
  .es-sidebar { display: none; }
  .es-sidebar.open { display: block; position: fixed; top: 0; left: 0; height: 100vh; z-index: 500; background: var(--es-bg2); border-right: 1px solid var(--es-border); }
  .es-filter-toggle { display: flex; }
  .es-main { padding: 16px; padding-bottom: calc(var(--es-player-h) + 16px); }
  .es-list-header { grid-template-columns: 48px 1fr 80px 120px; }
  .es-list-header .es-col-category { display: none; }
  .es-sound-row { grid-template-columns: 48px 1fr 80px 120px; }
  .es-col-category { display: none; }
}
@media (max-width: 640px) {
  .es-list-header { grid-template-columns: 48px 1fr 80px; }
  .es-list-header .es-col-duration { display: none; }
  .es-sound-row { grid-template-columns: 48px 1fr 80px; }
  .es-col-duration { display: none; }
  .es-col-actions { display: flex; opacity: 1; }
  .es-page-title { font-size: 24px; }
}

/* Font */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
</style>
@endpush

@push('scripts')
<script>
(function () {
  'use strict';

  /* ===== Audio engine ===== */
  const audio  = new Audio();
  audio.preload = 'none';

  let activeRow  = null;
  let activeBtn  = null;

  const player      = document.getElementById('es-player');
  const playerTitle = document.getElementById('player-title');
  const playerFill  = document.getElementById('player-fill');
  const playerThumb = document.getElementById('player-thumb');
  const playerTime  = document.getElementById('player-time');
  const playerTrack = document.getElementById('player-track');
  const playerPlay  = document.getElementById('player-play-btn');
  const volumeSlider= document.getElementById('volume-slider');

  function fmt(s) {
    s = Math.floor(s || 0);
    return Math.floor(s / 60) + ':' + String(s % 60).padStart(2, '0');
  }

  function setRowPlaying(row, btn, playing) {
    if (!row) return;
    row.classList.toggle('playing', playing);
    const iconPlay  = btn.querySelector('.icon-play');
    const iconPause = btn.querySelector('.icon-pause');
    if (iconPlay)  iconPlay.style.display  = playing ? 'none' : '';
    if (iconPause) iconPause.style.display = playing ? ''     : 'none';

    const pPlay  = playerPlay.querySelector('.icon-play');
    const pPause = playerPlay.querySelector('.icon-pause');
    if (pPlay)  pPlay.style.display  = playing ? 'none' : '';
    if (pPause) pPause.style.display = playing ? ''     : 'none';
  }

  function stopAll() {
    if (activeRow) setRowPlaying(activeRow, activeBtn, false);
    audio.pause();
    activeRow = null;
    activeBtn = null;
  }

  function playRow(row) {
    const src   = row.dataset.audio;
    const title = row.querySelector('.es-sound-title')?.textContent?.trim() || 'Sound';
    const cat   = row.querySelector('.es-category-badge')?.textContent?.trim() || 'Sound Effect';
    const btn   = row.querySelector('.es-play-btn');

    if (activeRow === row) {
      if (audio.paused) { audio.play(); setRowPlaying(row, btn, true); }
      else              { audio.pause(); setRowPlaying(row, btn, false); }
      return;
    }

    stopAll();
    if (!src) return;

    activeRow = row;
    activeBtn = btn;

    setRowPlaying(row, btn, true);
    playerTitle.textContent = title;
    document.getElementById('player-category').textContent = cat;
    player.style.display = 'flex';

    audio.src = src;
    audio.play().catch(() => setRowPlaying(row, btn, false));
  }

  /* Time update → fill bar */
  audio.addEventListener('timeupdate', () => {
    if (!audio.duration) return;
    const pct = (audio.currentTime / audio.duration) * 100;
    playerFill.style.width  = pct + '%';
    playerThumb.style.left  = pct + '%';
    if (activeRow) {
      const wf = activeRow.querySelector('.es-waveform-progress');
      if (wf) wf.style.width = pct + '%';
    }
    playerTime.textContent = fmt(audio.currentTime) + ' / ' + fmt(audio.duration);
  });

  audio.addEventListener('ended', () => {
    if (activeRow && activeBtn) setRowPlaying(activeRow, activeBtn, false);
    if (activeRow) {
      const wf = activeRow.querySelector('.es-waveform-progress');
      if (wf) wf.style.width = '0%';
    }
    activeRow = null; activeBtn = null;
  });

  /* Seek on track bar */
  playerTrack.addEventListener('click', e => {
    if (!audio.duration) return;
    const rect = playerTrack.getBoundingClientRect();
    audio.currentTime = ((e.clientX - rect.left) / rect.width) * audio.duration;
  });

  /* Volume */
  volumeSlider.addEventListener('input', () => { audio.volume = volumeSlider.value; });

  /* Play/pause from player controls */
  playerPlay.addEventListener('click', () => {
    if (!activeRow) return;
    if (audio.paused) { audio.play(); setRowPlaying(activeRow, activeBtn, true); }
    else              { audio.pause(); setRowPlaying(activeRow, activeBtn, false); }
  });

  /* Close player */
  document.getElementById('player-close').addEventListener('click', () => {
    stopAll();
    player.style.display = 'none';
  });

  /* Row play buttons */
  document.querySelectorAll('.es-play-btn').forEach(btn => {
    btn.addEventListener('click', e => {
      e.stopPropagation();
      playRow(btn.closest('.es-sound-row'));
    });
  });

  /* Click on waveform → seek or play */
  document.querySelectorAll('.es-waveform').forEach(wf => {
    wf.addEventListener('click', e => {
      const row = wf.closest('.es-sound-row');
      if (activeRow === row && audio.duration) {
        const rect = wf.getBoundingClientRect();
        audio.currentTime = ((e.clientX - rect.left) / rect.width) * audio.duration;
      } else {
        playRow(row);
      }
    });
  });

  /* ===== Sort dropdown ===== */
  const sortBtn  = document.getElementById('sort-btn');
  const sortDrop = document.getElementById('sort-dropdown');
  if (sortBtn && sortDrop) {
    sortBtn.addEventListener('click', e => { e.stopPropagation(); sortDrop.classList.toggle('show'); });
    document.addEventListener('click', () => sortDrop.classList.remove('show'));
    sortDrop.addEventListener('click', e => e.stopPropagation());
  }

  /* ===== Mobile sidebar toggle ===== */
  const sidebarToggle = document.getElementById('sidebar-toggle');
  const sidebar       = document.getElementById('es-sidebar');
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('open'));
    document.addEventListener('click', e => {
      if (!sidebar.contains(e.target) && e.target !== sidebarToggle) {
        sidebar.classList.remove('open');
      }
    });
  }

  /* ===== Favourite toggle (UI only) ===== */
  document.querySelectorAll('.es-btn-fav').forEach(btn => {
    btn.addEventListener('click', e => {
      e.stopPropagation();
      btn.classList.toggle('active');
      btn.title = btn.classList.contains('active') ? 'Saved' : 'Save';
    });
  });

})();
</script>
@endpush