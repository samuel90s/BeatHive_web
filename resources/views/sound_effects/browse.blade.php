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
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="{{ asset('assets/compiled/css/sfx/browse.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/compiled/js/sfx/browse.js') }}"></script>
@endpush