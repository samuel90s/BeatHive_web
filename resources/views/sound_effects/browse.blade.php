@extends('layouts.master')

@section('title', ($currentCategory->name ?? 'Sound effects') . ' – BeatHive')
@section('heading', 'Sound effects')

@section('content')
@php
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|null $sounds */
    $q = request('q');

    $bannerCat = $currentCategory ?? null;
    $title     = $bannerCat->name ?? 'Sound effects';
    $bg        = $bannerCat->bg_color ?? '#FFA93A';
    $icon      = $bannerCat && $bannerCat->icon_path
                    ? (str_starts_with($bannerCat->icon_path, 'http')
                          ? $bannerCat->icon_path
                          : asset($bannerCat->icon_path))
                    : null;

    $hasSounds  = isset($sounds) && $sounds->count() > 0;
@endphp

<div class="page-content">

  {{-- Breadcrumb --}}
  <nav class="small text-muted mb-2">
    <a href="{{ route('sound_effects.index') }}" class="text-decoration-none text-muted">
      Sound effects
    </a>
    @if($bannerCat)
      <span class="mx-1">/</span>
      <span class="text-light">{{ $bannerCat->name }}</span>
    @endif
  </nav>

  {{-- Hero banner kategori --}}
  <section class="mb-4">
    <div class="card border-0 sfx-cat-hero" style="background: {{ $bg }};">
      <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
          <div class="small text-uppercase fw-semibold mb-1">
            Sound effects
          </div>
          <h2 class="mb-1">{{ $title }}</h2>
          @if($bannerCat && !empty($bannerCat->description))
            <p class="mb-0 small text-muted">
              {{ $bannerCat->description }}
            </p>
          @endif
        </div>

        <div class="sfx-cat-hero-icon d-flex align-items-center justify-content-center">
          @if($icon)
            <img src="{{ $icon }}" alt="{{ $title }}" class="img-fluid" style="max-height:80%;max-width:80%;">
          @else
            <div class="sfx-wave-placeholder-lg">
              <span></span><span></span><span></span><span></span>
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>

  {{-- Filter bar + info --}}
  <section class="mb-2">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">

      <div class="d-flex align-items-center gap-2">
        {{-- tombol filter dummy --}}
        <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
          <i class="bi bi-sliders"></i>
          <span>Filter</span>
        </button>

        {{-- info filter aktif --}}
        @if($q)
          <span class="badge bg-light border text-muted small">
            <i class="bi bi-search me-1"></i> “{{ $q }}”
          </span>
        @endif
        @if($currentSubcategory)
          <span class="badge bg-light border text-muted small">
            <i class="bi bi-diagram-3 me-1"></i> {{ $currentSubcategory->name }}
          </span>
        @endif
      </div>

      <div class="d-flex align-items-center gap-2">
        {{-- variations (dummy) --}}
        <div class="dropdown">
          <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
            Variations
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><button class="dropdown-item" type="button">All variations</button></li>
            <li><button class="dropdown-item" type="button">Main only</button></li>
          </ul>
        </div>

        {{-- sort --}}
        <div class="dropdown">
          <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
            Sort
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}">Popular</a></li>
            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">Newest</a></li>
            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'shortest']) }}">Shortest</a></li>
            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'longest']) }}">Longest</a></li>
          </ul>
        </div>
      </div>

    </div>
  </section>

  {{-- List tracks --}}
  <section class="card border-0 shadow-sm">
    <div class="card-body p-0">

      {{-- header row (desktop) --}}
      <div class="sfx-list-header px-3 px-md-4 py-2 small text-muted d-none d-md-flex">
        <div class="col-title flex-grow-1">Title</div>
        <div class="col-waveform">Duration</div>
        <div class="col-tag">Tag</div>
        <div class="col-actions text-end"></div>
      </div>

      @if($hasSounds)
        @foreach($sounds as $sound)
          @php
              $trackTitle = $sound->name ?? $sound->title ?? 'Untitled sound';
              $catName    = optional($sound->category)->name ?? ($bannerCat->name ?? 'Sound');
              $tagName    = optional($sound->subcategory)->name ?? null;

              // durasi (pakai duration_seconds, fallback ke kolom duration kalau ada)
              $duration   = '—:—';
              if (!is_null($sound->duration_seconds ?? null)) {
                  $sec = (int) $sound->duration_seconds;
                  $m   = floor($sec / 60);
                  $s   = $sec % 60;
                  $duration = sprintf('%d:%02d', $m, $s);
              } elseif (!empty($sound->duration)) {
                  $duration = $sound->duration;
              }

              // audio & waveform
              $previewPath = $sound->preview_path ?: $sound->file_path;
              $previewUrl  = $previewPath ? asset($previewPath) : null;
              $waveUrl     = $sound->waveform_image ? asset($sound->waveform_image) : null;
              $mime        = $sound->mime_type ?? 'audio/mpeg';
          @endphp

          <div class="sfx-row px-3 px-md-4 py-2">
            {{-- Left: tombol play + title --}}
            <div class="d-flex align-items-center gap-3 flex-grow-1">

              {{-- Play button --}}
              <button type="button"
                      class="btn btn-icon btn-sm btn-ghost sfx-play-btn"
                      data-audio-src="{{ $previewUrl }}"
                      data-audio-type="{{ $mime }}">
                <i class="bi bi-play-fill"></i>
              </button>

              {{-- Thumb kecil --}}
              <div class="sfx-thumb d-none d-sm-flex">
                <div class="sfx-thumb-inner">
                  <span></span><span></span><span></span>
                </div>
              </div>

              {{-- Title + category --}}
              <div class="flex-grow-1">
                {{-- Judul → halaman detail --}}
                <a href="{{ route('sound_effects.show', $sound->id) }}"
                   class="fw-semibold text-decoration-none text-light d-block text-truncate">
                  {{ $trackTitle }}
                </a>
                <div class="small text-muted text-truncate">
                  {{ $catName }}
                </div>
              </div>
            </div>

            {{-- Waveform + duration (desktop) --}}
            <div class="d-none d-md-flex align-items-center col-waveform">
              <div class="sfx-waveform me-2">
                @if($waveUrl)
                  <img src="{{ $waveUrl }}" alt="waveform" class="img-fluid">
                @endif
              </div>
              <div class="small text-muted">
                {{ $duration }}
              </div>
            </div>

            {{-- Tag --}}
            <div class="d-none d-md-flex align-items-center col-tag">
              @if($tagName)
                <span class="small text-muted">{{ $tagName }}</span>
              @endif
            </div>

            {{-- Actions --}}
            <div class="d-flex align-items-center justify-content-end gap-2 col-actions">
              <button type="button" class="btn btn-icon btn-sm btn-ghost">
                <i class="bi bi-heart"></i>
              </button>
              <button type="button" class="btn btn-icon btn-sm btn-ghost">
                <i class="bi bi-download"></i>
              </button>
              <button type="button" class="btn btn-icon btn-sm btn-ghost">
                <i class="bi bi-three-dots"></i>
              </button>
            </div>

            {{-- Mobile row: waveform + duration --}}
            <div class="d-flex d-md-none align-items-center mt-2 ms-5 w-100">
              <div class="sfx-waveform me-2">
                @if($waveUrl)
                  <img src="{{ $waveUrl }}" alt="waveform" class="img-fluid">
                @endif
              </div>
              <div class="small text-muted ms-auto">
                {{ $duration }}
              </div>
            </div>
          </div>
        @endforeach
      @else
        <div class="px-3 px-md-4 py-4 text-center text-muted">
          No sound effects found for this filter.
        </div>
      @endif

      {{-- Pagination --}}
      @if($hasSounds && method_exists($sounds, 'links'))
        <div class="px-3 px-md-4 py-3 border-top">
          {{ $sounds->withQueryString()->links() }}
        </div>
      @endif

    </div>
  </section>
</div>
@endsection

@push('styles')
<style>
  /* Banner kategori */
  .sfx-cat-hero {
    border-radius: 1rem;
    color: #111;
  }
  .sfx-cat-hero h2 {
    font-size: 2rem;
    font-weight: 600;
  }
  .sfx-cat-hero-icon {
    width: 220px;
    height: 100px;
  }
  @media (max-width: 575.98px) {
    .sfx-cat-hero-icon {
      width: 160px;
      height: 80px;
    }
  }
  .sfx-wave-placeholder-lg {
    display: flex;
    gap: .4rem;
    width: 100%;
    height: 100%;
    align-items: flex-end;
  }
  .sfx-wave-placeholder-lg span {
    flex: 1 1 auto;
    border-radius: .3rem;
    background: rgba(0,0,0,.18);
  }
  .sfx-wave-placeholder-lg span:nth-child(2) { height: 90%; }
  .sfx-wave-placeholder-lg span:nth-child(1),
  .sfx-wave-placeholder-lg span:nth-child(4) { height: 65%; }
  .sfx-wave-placeholder-lg span:nth-child(3) { height: 45%; }

  /* List header & rows */
  .sfx-list-header {
    border-bottom: 1px solid rgba(255,255,255,.06);
  }
  .sfx-row {
    display: grid;
    grid-template-columns: minmax(0, 1.8fr) 1.1fr 0.6fr auto;
    column-gap: 1rem;
    align-items: center;
    border-bottom: 1px solid rgba(255,255,255,.04);
    min-height: 64px;
  }
  .sfx-row:hover {
    background: rgba(255,255,255,.02);
  }

  .col-waveform { min-width: 200px; }
  .col-tag      { min-width: 120px; }
  .col-actions  { min-width: 120px; }

  @media (max-width: 991.98px) {
    .sfx-row {
      grid-template-columns: minmax(0, 1.6fr) auto;
    }
    .col-waveform,
    .col-tag {
      display: none !important;
    }
  }

  /* Thumb kecil di kiri title */
  .sfx-thumb {
    width: 40px;
    height: 40px;
    border-radius: .35rem;
    background: rgba(255,255,255,.08);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .sfx-thumb-inner {
    display: flex;
    gap: .16rem;
    width: 60%;
    height: 60%;
    align-items: flex-end;
  }
  .sfx-thumb-inner span {
    flex: 1 1 auto;
    border-radius: .12rem;
    background: rgba(0,0,0,.25);
  }
  .sfx-thumb-inner span:nth-child(2) { height: 90%; }
  .sfx-thumb-inner span:nth-child(1),
  .sfx-thumb-inner span:nth-child(3) { height: 60%; }

  /* Waveform container */
  .sfx-waveform {
    flex: 1 1 auto;
    height: 32px;                 /* kecil, mirip Epidemic */
    border-radius: .5rem;
    overflow: hidden;
    background: rgba(255,255,255,.02);
  }

  /* Waveform image styling */
  .sfx-waveform img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    /* bikin waveform merah jadi abu-abu soft */
    filter: grayscale(1) brightness(1.3) contrast(1.15);
    opacity: .9;
  }

  /* Icon buttons */
  .btn-ghost {
    background: transparent;
    border: 0;
    color: #aaa;
  }
  .btn-ghost:hover {
    color: #fff;
    background: rgba(255,255,255,.06);
  }
  .btn-icon {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 999px;
  }
  .sfx-play-btn.is-playing i {
    color: #fff;
  }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const player = new Audio();
  let currentBtn = null;

  const buttons = document.querySelectorAll('.sfx-play-btn');

  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      const src  = btn.dataset.audioSrc;
      const type = btn.dataset.audioType || 'audio/mpeg';
      if (!src) return;

      // klik tombol yang sama → toggle play/pause
      if (currentBtn === btn) {
        if (player.paused) {
          player.play();
          btn.classList.add('is-playing');
          btn.querySelector('i').className = 'bi bi-pause-fill';
        } else {
          player.pause();
          btn.classList.remove('is-playing');
          btn.querySelector('i').className = 'bi bi-play-fill';
        }
        return;
      }

      // stop tombol sebelumnya
      if (currentBtn) {
        currentBtn.classList.remove('is-playing');
        currentBtn.querySelector('i').className = 'bi bi-play-fill';
      }

      // mainkan yang baru
      currentBtn = btn;
      player.src = src;
      player.type = type;
      player.play();

      btn.classList.add('is-playing');
      btn.querySelector('i').className = 'bi bi-pause-fill';
    });
  });

  player.addEventListener('ended', () => {
    if (currentBtn) {
      currentBtn.classList.remove('is-playing');
      currentBtn.querySelector('i').className = 'bi bi-play-fill';
    }
  });
});
</script>
@endpush
