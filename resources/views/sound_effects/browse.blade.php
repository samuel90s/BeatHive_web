@extends('layouts.master')

@section('title', ($currentCategory->name ?? 'Sound effects') . ' – BeatHive')
@section('heading', 'Sound effects')

@section('content')
@php
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|null $sounds */

    $q = request('q');

    $bannerCat = $currentCategory ?? null;
    $title     = $currentCategory->name
                ?? $currentSubcategory->name
                ?? 'Sound effects';

    $bgColor   = $bannerCat->bg_color ?? '#FFA93A';
    $icon      = $bannerCat && $bannerCat->icon_path
                    ? (str_starts_with($bannerCat->icon_path, 'http')
                          ? $bannerCat->icon_path
                          : asset($bannerCat->icon_path))
                    : null;

    $hasSounds = isset($sounds) && $sounds->count() > 0;
@endphp

<div class="epidemic-sound-container">

  {{-- ============ BREADCRUMB ============ --}}
  <nav class="epidemic-breadcrumb">
    <a href="{{ route('sound_effects.index') }}">Sound effects</a>
    @if($currentCategory)
      <span class="breadcrumb-separator">/</span>
      <a href="{{ route('sound_effects.browse', ['category' => $currentCategory->id]) }}">
        {{ $currentCategory->name }}
      </a>
    @endif
    @if($currentSubcategory)
      <span class="breadcrumb-separator">/</span>
      <span>{{ $currentSubcategory->name }}</span>
    @endif
  </nav>

  {{-- ============ PAGE HEADER ============ --}}
  <header class="epidemic-page-header">
    <div class="header-content">
      <div>
        <h1 class="epidemic-page-title">{{ $title }}</h1>
        @if($hasSounds)
          <div class="epidemic-page-count">{{ $sounds->total() ?? $sounds->count() }} sounds</div>
        @endif
      </div>
      @if($icon)
        <div class="epidemic-category-icon">
          <img src="{{ $icon }}" alt="{{ $title }}">
        </div>
      @endif
    </div>
    
    @if($bannerCat && !empty($bannerCat->description))
      <p class="epidemic-page-description">{{ $bannerCat->description }}</p>
    @elseif($currentSubcategory && !empty($currentSubcategory->description))
      <p class="epidemic-page-description">{{ $currentSubcategory->description }}</p>
    @endif
  </header>

  {{-- ============ FILTER BAR ============ --}}
  <div class="epidemic-filter-bar">
    <div class="filter-controls">
      <div class="epidemic-filter-group">
        <button class="epidemic-filter-btn" id="filter-btn">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
            <path d="M1.5 3a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1h-13zM3 6.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm2 3a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm2 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z"/>
          </svg>
          <span>Filters</span>
        </button>
        
        @if($q)
        <div class="epidemic-active-filter">
          <span class="filter-label">Search: "{{ $q }}"</span>
          <button class="filter-remove" onclick="window.location.href='{{ route('sound_effects.browse', ['category' => $currentCategory->id ?? '']) }}'">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="currentColor">
              <path d="M6 5.293l4.146-4.147a.5.5 0 0 1 .708.708L6.707 6l4.147 4.146a.5.5 0 0 1-.708.708L6 6.707l-4.146 4.147a.5.5 0 0 1-.708-.708L5.293 6 1.146 1.854a.5.5 0 1 1 .708-.708L6 5.293z"/>
            </svg>
          </button>
        </div>
        @endif
      </div>

      <div class="epidemic-sort-group">
        @php $currentSort = request('sort', 'popular'); @endphp
        <button class="epidemic-sort-btn" id="sort-btn">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
            <path d="M3.5 2.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 11.293V2.5zm3.5 1a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z"/>
          </svg>
          <span>{{ ucfirst($currentSort) }}</span>
        </button>
      </div>
    </div>
  </div>

  {{-- ============ FILTER DROPDOWN ============ --}}
  <div class="epidemic-filter-dropdown" id="filter-dropdown">
    <div class="dropdown-content">
      <div class="dropdown-section">
        <h3 class="dropdown-title">Category</h3>
        <div class="dropdown-options">
          <a href="{{ route('sound_effects.index') }}" class="dropdown-option">All categories</a>
          @if(isset($categories))
            @foreach($categories as $cat)
              <a href="{{ route('sound_effects.browse', ['category' => $cat->id]) }}" class="dropdown-option">{{ $cat->name }}</a>
            @endforeach
          @endif
        </div>
      </div>
      
      <div class="dropdown-section">
        <h3 class="dropdown-title">Duration</h3>
        <div class="dropdown-options">
          <a href="{{ request()->fullUrlWithQuery(['duration' => 'all']) }}" class="dropdown-option">Any duration</a>
          <a href="{{ request()->fullUrlWithQuery(['duration' => 'short']) }}" class="dropdown-option">Short (0-30s)</a>
          <a href="{{ request()->fullUrlWithQuery(['duration' => 'medium']) }}" class="dropdown-option">Medium (30s-2min)</a>
          <a href="{{ request()->fullUrlWithQuery(['duration' => 'long']) }}" class="dropdown-option">Long (2min+)</a>
        </div>
      </div>
    </div>
  </div>

  {{-- ============ SORT DROPDOWN ============ --}}
  <div class="epidemic-sort-dropdown" id="sort-dropdown">
    <div class="dropdown-options">
      <a href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}" class="dropdown-option {{ $currentSort === 'popular' ? 'active' : '' }}">Popular</a>
      <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" class="dropdown-option {{ $currentSort === 'newest' ? 'active' : '' }}">Newest</a>
      <a href="{{ request()->fullUrlWithQuery(['sort' => 'shortest']) }}" class="dropdown-option {{ $currentSort === 'shortest' ? 'active' : '' }}">Shortest</a>
      <a href="{{ request()->fullUrlWithQuery(['sort' => 'longest']) }}" class="dropdown-option {{ $currentSort === 'longest' ? 'active' : '' }}">Longest</a>
    </div>
  </div>

  {{-- ============ SOUNDS GRID ============ --}}
  <main class="epidemic-sounds-grid">
    @if($hasSounds)
      @foreach($sounds as $sound)
        @php
            $trackTitle = $sound->title ?? $sound->name ?? 'Untitled sound';
            $catName    = optional($sound->category)->name ?? ($bannerCat->name ?? 'Sound');
            
            $duration = '—:—';
            $durationSeconds = null;
            if (!is_null($sound->duration_seconds ?? null)) {
                $durationSeconds = (int) $sound->duration_seconds;
                $m = floor($durationSeconds / 60);
                $s = $durationSeconds % 60;
                $duration = sprintf('%d:%02d', $m, $s);
            } elseif (!empty($sound->duration ?? null)) {
                $duration = $sound->duration;
            }

            $previewPath = $sound->preview_path ?: $sound->file_path;
            $previewUrl  = $previewPath ? asset($previewPath) : null;
            $waveUrl     = $sound->waveform_image ? asset($sound->waveform_image) : null;
        @endphp

        <div class="epidemic-sound-card" data-sound-id="{{ $sound->id }}">
          <div class="sound-waveform-container">
            <div class="sound-waveform">
              <div class="waveform-background">
                @if($waveUrl)
                  <div class="waveform-image" style="background-image: url('{{ $waveUrl }}');"></div>
                @else
                  <div class="waveform-placeholder">
                    @for($i = 0; $i < 20; $i++)
                      <span style="height: {{ rand(20, 80) }}%"></span>
                    @endfor
                  </div>
                @endif
              </div>
              
              <button class="sound-play-button"
                      data-audio-src="{{ $previewUrl }}"
                      data-title="{{ $trackTitle }}"
                      data-wave-url="{{ $waveUrl }}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M8 5v14l11-7z"/>
                </svg>
              </button>
              
              <div class="sound-duration">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="currentColor" style="margin-right: 4px;">
                  <path d="M6 1a5 5 0 1 0 0 10A5 5 0 0 0 6 1zm.5 8.5V6H5v-.5h2v4h-.5z"/>
                </svg>
                {{ $duration }}
              </div>
            </div>
          </div>

          <div class="sound-info">
            <a href="{{ route('sound_effects.show', $sound->id) }}" class="sound-title">
              {{ $trackTitle }}
            </a>
            <div class="sound-meta">
              <div class="sound-category">{{ $catName }}</div>
              <div class="sound-actions">
                <button class="sound-action-btn" title="Add to favorites">
                  <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                  </svg>
                </button>
                <button class="sound-action-btn" title="Add to playlist">
                  <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                  </svg>
                </button>
                <button class="sound-action-btn" title="Download">
                  <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                  </svg>
                </button>
                <button class="sound-action-btn" title="More options">
                  <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    @else
      {{-- EMPTY STATE --}}
      <div class="epidemic-empty-state">
        <div class="empty-icon">
          <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
          </svg>
        </div>
        <h3>No sound effects found</h3>
        <p>Try adjusting your filters or search term</p>
        <a href="{{ route('sound_effects.index') }}" class="epidemic-button">Browse all sounds</a>
      </div>
    @endif
  </main>

  {{-- ============ PAGINATION ============ --}}
  @if($hasSounds && method_exists($sounds, 'links'))
    <div class="epidemic-pagination">
      {{ $sounds->withQueryString()->links('vendor.pagination.simple') }}
    </div>
  @endif

</div>
@endsection

@push('styles')
<style>
/* Epidemic Sound Main Styles */
:root {
  --es-bg-primary: #0F0F0F;
  --es-bg-secondary: #1A1A1A;
  --es-bg-tertiary: #2A2A2A;
  --es-text-primary: #FFFFFF;
  --es-text-secondary: #A0A0A0;
  --es-text-tertiary: #666666;
  --es-border-color: #333333;
  --es-accent-color: #FF6B00;
  --es-accent-hover: #E55A00;
  --es-overlay-bg: rgba(0, 0, 0, 0.7);
}

body {
  background: var(--es-bg-primary);
  color: var(--es-text-primary);
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  margin: 0;
  padding: 0;
}

.epidemic-sound-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 24px;
}

/* Breadcrumb */
.epidemic-breadcrumb {
  font-size: 14px;
  color: var(--es-text-secondary);
  margin-bottom: 32px;
}

.epidemic-breadcrumb a {
  color: var(--es-text-secondary);
  text-decoration: none;
  transition: color 0.2s;
}

.epidemic-breadcrumb a:hover {
  color: var(--es-text-primary);
}

.breadcrumb-separator {
  margin: 0 8px;
  opacity: 0.6;
}

/* Page Header */
.epidemic-page-header {
  margin-bottom: 32px;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 16px;
}

.epidemic-page-title {
  font-family: 'Sebenta', 'Inter', sans-serif;
  font-weight: 500;
  font-size: 36px;
  margin: 0 0 8px 0;
  color: var(--es-text-primary);
}

.epidemic-page-count {
  font-size: 16px;
  color: var(--es-text-secondary);
}

.epidemic-category-icon {
  width: 80px;
  height: 80px;
  background: var(--es-bg-tertiary);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 16px;
}

.epidemic-category-icon img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.epidemic-page-description {
  font-size: 16px;
  line-height: 1.6;
  color: var(--es-text-secondary);
  max-width: 800px;
  margin: 0;
}

/* Filter Bar */
.epidemic-filter-bar {
  background: var(--es-bg-secondary);
  border-radius: 8px;
  padding: 16px 24px;
  margin-bottom: 24px;
  border: 1px solid var(--es-border-color);
}

.filter-controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.epidemic-filter-group {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

.epidemic-filter-btn,
.epidemic-sort-btn {
  background: var(--es-bg-tertiary);
  border: 1px solid var(--es-border-color);
  color: var(--es-text-secondary);
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  transition: all 0.2s;
  font-family: 'Inter', sans-serif;
}

.epidemic-filter-btn:hover,
.epidemic-sort-btn:hover {
  background: var(--es-bg-tertiary);
  color: var(--es-text-primary);
  border-color: var(--es-text-tertiary);
}

.epidemic-active-filter {
  background: var(--es-bg-tertiary);
  border: 1px solid var(--es-border-color);
  padding: 6px 12px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.filter-label {
  font-size: 14px;
  color: var(--es-text-secondary);
}

.filter-remove {
  background: none;
  border: none;
  color: var(--es-text-secondary);
  padding: 2px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.2s;
}

.filter-remove:hover {
  color: var(--es-text-primary);
}

.epidemic-sort-group {
  display: flex;
  align-items: center;
}

/* Dropdowns */
.epidemic-filter-dropdown,
.epidemic-sort-dropdown {
  display: none;
  position: absolute;
  background: var(--es-bg-secondary);
  border: 1px solid var(--es-border-color);
  border-radius: 8px;
  padding: 20px;
  margin-top: 8px;
  z-index: 1000;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
  min-width: 280px;
}

.epidemic-filter-dropdown.show,
.epidemic-sort-dropdown.show {
  display: block;
}

.dropdown-section {
  margin-bottom: 24px;
}

.dropdown-section:last-child {
  margin-bottom: 0;
}

.dropdown-title {
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: var(--es-text-secondary);
  margin: 0 0 12px 0;
}

.dropdown-options {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.dropdown-option {
  color: var(--es-text-primary);
  text-decoration: none;
  font-size: 14px;
  padding: 8px 12px;
  border-radius: 4px;
  transition: background-color 0.2s;
}

.dropdown-option:hover {
  background: var(--es-bg-tertiary);
}

.dropdown-option.active {
  background: var(--es-bg-tertiary);
  color: var(--es-accent-color);
}

/* Sounds Grid */
.epidemic-sounds-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 24px;
  margin-bottom: 48px;
}

/* Sound Card */
.epidemic-sound-card {
  background: var(--es-bg-secondary);
  border-radius: 8px;
  overflow: hidden;
  border: 1px solid var(--es-border-color);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.epidemic-sound-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.3);
  border-color: var(--es-accent-color);
}

.sound-waveform-container {
  position: relative;
  height: 140px;
  background: #000;
  overflow: hidden;
}

.sound-waveform {
  width: 100%;
  height: 100%;
  position: relative;
}

.waveform-background {
  width: 100%;
  height: 100%;
  position: relative;
  overflow: hidden;
}

.waveform-image {
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  filter: brightness(1.2) contrast(1.1) saturate(1.5);
  opacity: 0.8;
}

.waveform-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: flex-end;
  gap: 2px;
  padding: 20px;
  box-sizing: border-box;
}

.waveform-placeholder span {
  flex: 1;
  background: linear-gradient(to top, var(--es-accent-color), #FF9B50);
  border-radius: 1px;
  min-height: 20%;
}

.sound-play-button {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 56px;
  height: 56px;
  background: var(--es-accent-color);
  border: none;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  cursor: pointer;
  opacity: 0;
  transition: all 0.3s ease;
  z-index: 10;
}

.epidemic-sound-card:hover .sound-play-button {
  opacity: 1;
}

.sound-play-button:hover {
  background: var(--es-accent-hover);
  transform: translate(-50%, -50%) scale(1.1);
}

.sound-duration {
  position: absolute;
  bottom: 12px;
  right: 12px;
  background: rgba(0, 0, 0, 0.8);
  color: var(--es-text-secondary);
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
  display: flex;
  align-items: center;
  backdrop-filter: blur(4px);
}

/* Sound Info */
.sound-info {
  padding: 20px;
}

.sound-title {
  color: var(--es-text-primary);
  font-size: 16px;
  font-weight: 600;
  line-height: 1.4;
  text-decoration: none;
  display: block;
  margin-bottom: 12px;
  transition: color 0.2s;
}

.sound-title:hover {
  color: var(--es-accent-color);
}

.sound-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.sound-category {
  font-size: 14px;
  color: var(--es-text-secondary);
  background: var(--es-bg-tertiary);
  padding: 4px 10px;
  border-radius: 4px;
}

.sound-actions {
  display: flex;
  gap: 8px;
}

.sound-action-btn {
  background: none;
  border: 1px solid var(--es-border-color);
  color: var(--es-text-secondary);
  width: 32px;
  height: 32px;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
}

.sound-action-btn:hover {
  background: var(--es-bg-tertiary);
  color: var(--es-text-primary);
  border-color: var(--es-accent-color);
}

/* Empty State */
.epidemic-empty-state {
  grid-column: 1 / -1;
  text-align: center;
  padding: 64px 24px;
}

.empty-icon {
  color: var(--es-text-tertiary);
  margin-bottom: 24px;
}

.epidemic-empty-state h3 {
  font-size: 20px;
  font-weight: 600;
  margin: 0 0 12px 0;
  color: var(--es-text-primary);
}

.epidemic-empty-state p {
  color: var(--es-text-secondary);
  margin: 0 0 24px 0;
}

.epidemic-button {
  background: var(--es-accent-color);
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.epidemic-button:hover {
  background: var(--es-accent-hover);
  color: white;
  text-decoration: none;
}

/* Pagination */
.epidemic-pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 8px;
}

.epidemic-pagination .pagination {
  display: flex;
  gap: 8px;
  list-style: none;
  margin: 0;
  padding: 0;
}

.epidemic-pagination .page-item .page-link {
  background: var(--es-bg-secondary);
  border: 1px solid var(--es-border-color);
  color: var(--es-text-secondary);
  padding: 8px 16px;
  border-radius: 6px;
  text-decoration: none;
  transition: all 0.2s;
}

.epidemic-pagination .page-item.active .page-link {
  background: var(--es-accent-color);
  border-color: var(--es-accent-color);
  color: white;
}

.epidemic-pagination .page-item .page-link:hover {
  background: var(--es-bg-tertiary);
  color: var(--es-text-primary);
}

/* Font Faces */
@font-face {
  font-family: 'Inter';
  font-style: normal;
  font-weight: 400 700;
  font-display: swap;
  src: url(https://fonts.gstatic.com/s/inter/v13/UcC73FwrK3iLTeHuS_fvQtMwCp50KnMa1ZL7W0Q5nw.woff2) format('woff2');
}

@font-face {
  font-family: 'Sebenta';
  font-weight: 500;
  font-display: swap;
  font-style: normal;
  src: url('https://www.epidemicsound.com/staticfiles/legacy/20/fonts/Sebenta-Medium.woff2') format('woff2');
}

@font-face {
  font-family: 'Sebenta';
  font-weight: 500;
  font-display: swap;
  font-style: italic;
  src: url('https://www.epidemicsound.com/staticfiles/legacy/20/fonts/Sebenta-MediumItalic.woff2') format('woff2');
}

/* Responsive */
@media (max-width: 768px) {
  .epidemic-sound-container {
    padding: 16px;
  }
  
  .epidemic-page-title {
    font-size: 28px;
  }
  
  .header-content {
    flex-direction: column;
    gap: 16px;
  }
  
  .epidemic-category-icon {
    width: 60px;
    height: 60px;
  }
  
  .epidemic-sounds-grid {
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 16px;
  }
  
  .filter-controls {
    flex-direction: column;
    gap: 12px;
    align-items: stretch;
  }
  
  .epidemic-filter-group {
    justify-content: space-between;
  }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const filterBtn = document.getElementById('filter-btn');
  const sortBtn = document.getElementById('sort-btn');
  const filterDropdown = document.getElementById('filter-dropdown');
  const sortDropdown = document.getElementById('sort-dropdown');
  const player = new Audio();
  let currentPlayingCard = null;

  // Toggle filter dropdown
  filterBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    filterDropdown.classList.toggle('show');
    sortDropdown.classList.remove('show');
  });

  // Toggle sort dropdown
  sortBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    sortDropdown.classList.toggle('show');
    filterDropdown.classList.remove('show');
  });

  // Close dropdowns when clicking outside
  document.addEventListener('click', function() {
    filterDropdown.classList.remove('show');
    sortDropdown.classList.remove('show');
  });

  // Stop propagation for dropdown clicks
  filterDropdown.addEventListener('click', function(e) {
    e.stopPropagation();
  });

  sortDropdown.addEventListener('click', function(e) {
    e.stopPropagation();
  });

  // Sound play functionality
  document.querySelectorAll('.sound-play-button').forEach(button => {
    button.addEventListener('click', function(e) {
      e.stopPropagation();
      
      const audioSrc = this.dataset.audioSrc;
      const title = this.dataset.title;
      const card = this.closest('.epidemic-sound-card');
      
      if (!audioSrc) {
        console.log('No audio source available');
        return;
      }

      // If this is the currently playing sound, pause it
      if (currentPlayingCard === card && !player.paused) {
        player.pause();
        this.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>';
        return;
      }

      // Reset all play buttons
      document.querySelectorAll('.sound-play-button').forEach(btn => {
        btn.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>';
      });

      // Set this as active
      currentPlayingCard = card;
      
      // Change icon to pause
      this.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>';

      // Play the sound
      player.src = audioSrc;
      player.play().catch(error => {
        console.log('Playback failed:', error);
        this.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>';
      });
    });
  });

  // Player events
  player.addEventListener('play', function() {
    console.log('Playing audio');
  });

  player.addEventListener('pause', function() {
    if (currentPlayingCard) {
      const playBtn = currentPlayingCard.querySelector('.sound-play-button');
      if (playBtn) {
        playBtn.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>';
      }
    }
  });

  player.addEventListener('ended', function() {
    if (currentPlayingCard) {
      const playBtn = currentPlayingCard.querySelector('.sound-play-button');
      if (playBtn) {
        playBtn.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>';
      }
      currentPlayingCard = null;
    }
  });

  // Action buttons functionality
  document.querySelectorAll('.sound-action-btn').forEach(button => {
    button.addEventListener('click', function(e) {
      e.stopPropagation();
      
      const card = this.closest('.epidemic-sound-card');
      const soundId = card ? card.dataset.soundId : 'unknown';
      const title = card ? card.querySelector('.sound-title').textContent : 'Unknown';
      const action = this.title;
      
      console.log(`${action} sound: ${title} (ID: ${soundId})`);
      
      // Toggle favorite
      if (action === 'Add to favorites') {
        const svg = this.querySelector('svg');
        if (svg.getAttribute('fill') === 'currentColor') {
          svg.style.fill = '#FF6B00';
          this.title = 'Remove from favorites';
        } else {
          svg.style.fill = 'currentColor';
          this.title = 'Add to favorites';
        }
      }
      
      // Download simulation
      if (action === 'Download') {
        const originalHTML = this.innerHTML;
        this.innerHTML = '<svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/></svg>';
        
        setTimeout(() => {
          this.innerHTML = originalHTML;
        }, 2000);
      }
    });
  });

  // Card click navigation (excluding buttons)
  document.querySelectorAll('.epidemic-sound-card').forEach(card => {
    card.addEventListener('click', function(e) {
      // Don't navigate if clicking on buttons
      if (e.target.closest('button') || 
          e.target.closest('.sound-play-button') || 
          e.target.closest('.sound-action-btn') ||
          e.target.tagName === 'A') {
        return;
      }
      
      const soundId = this.dataset.soundId;
      if (soundId) {
        window.location.href = `/sound-effects/${soundId}`;
      }
    });
  });
});
</script>
@endpush