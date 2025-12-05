@extends('layouts.master')

@section('title', $sound->title . ' – Sound Effect – BeatHive')
@section('heading', 'Sound effect')

@section('content')
@php
    $waveUrl    = $sound->waveform_image ? asset($sound->waveform_image) : null;
    $previewUrl = $sound->preview_path ? asset($sound->preview_path) : asset($sound->file_path);

    $duration = '—:—';
    if (!is_null($sound->duration_seconds)) {
        $sec = (int) $sound->duration_seconds;
        $m   = floor($sec / 60);
        $s   = $sec % 60;
        $duration = sprintf('%d:%02d', $m, $s);
    }

    $categoryName = optional($sound->category)->name ?? 'Sound Effect';
@endphp

<div class="page-content">

  {{-- ================= HERO ================= --}}
  <section class="mb-4">
    <div class="card border-0 shadow-sm sfx-detail-hero">
      <div class="card-body">
        <p class="small text-muted text-uppercase mb-1">Sound Effect</p>
        <h1 class="fw-bold mb-3">{{ $sound->title }}</h1>

        {{-- ACTION BUTTONS --}}
        <div class="d-flex flex-wrap gap-2 mb-4">
          <button class="btn btn-outline-light btn-sm"><i class="bi bi-heart"></i></button>
          <button class="btn btn-outline-light btn-sm"><i class="bi bi-download"></i></button>
          <button class="btn btn-outline-light btn-sm"><i class="bi bi-cart"></i></button>
          <button class="btn btn-outline-light btn-sm"><i class="bi bi-link-45deg"></i></button>
        </div>

        {{-- BIG WAVEFORM PLAYER --}}
        <div class="sfx-big-player bg-dark p-3 rounded-3 d-flex align-items-center gap-3">
          <button type="button"
                  class="btn btn-icon btn-lg btn-ghost sfx-detail-play"
                  data-audio-src="{{ $previewUrl }}">
            <i class="bi bi-play-fill fs-3"></i>
          </button>

          <div class="flex-grow-1">
            <div class="sfx-big-waveform mb-1">
              @if($waveUrl)
                <img src="{{ $waveUrl }}" alt="waveform" class="img-fluid">
              @endif
            </div>
            <div class="small text-muted">{{ $duration }}</div>
          </div>
        </div>

      </div>
    </div>
  </section>


  {{-- ================= SIMILAR TRACKS ================= --}}
  <section>
    <h5 class="fw-semibold mb-3">Similar tracks</h5>

    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">

        @forelse($similar as $item)
          @php
              $simWave = $item->waveform_image ? asset($item->waveform_image) : null;
              $simPrev = $item->preview_path ? asset($item->preview_path) : asset($item->file_path);

              $simDuration = '—:—';
              if (!is_null($item->duration_seconds)) {
                  $sec = (int) $item->duration_seconds;
                  $m   = floor($sec / 60);
                  $s   = $sec % 60;
                  $simDuration = sprintf('%d:%02d', $m, $s);
              }
          @endphp

          <a href="{{ route('sound_effects.show', $item->id) }}"
             class="sfx-sim-row px-3 px-md-4 py-3 text-decoration-none text-light d-block">

            <div class="d-flex align-items-center gap-3">

              {{-- ICON --}}
              <div class="sfx-sim-icon"></div>

              {{-- TITLE --}}
              <div class="flex-grow-1">
                <div class="fw-semibold">{{ $item->title }}</div>
                <div class="small text-muted">{{ optional($item->category)->name }}</div>
              </div>

              {{-- WAVEFORM --}}
              <div class="sfx-sim-waveform d-none d-md-block">
                @if($simWave)
                  <img src="{{ $simWave }}" class="img-fluid" alt="">
                @endif
              </div>

              {{-- DURATION --}}
              <div class="small text-muted">{{ $simDuration }}</div>
            </div>

          </a>

        @empty
          <div class="text-center text-muted py-4">
            No similar tracks found.
          </div>
        @endforelse

      </div>
    </div>
  </section>

</div>

@endsection

@push('styles')
<style>
  .sfx-detail-hero {
    background: #191b20;
    border-radius: 1rem;
  }

  .sfx-big-player {
    background: #0e0f12;
  }

  .btn-ghost {
    background: transparent;
    color: #ddd;
  }

  .btn-ghost:hover {
    background: rgba(255,255,255,0.08);
    color: #fff;
  }

  .sfx-big-waveform img {
    width: 100%;
    height: 60px;
    object-fit: cover;
    filter: grayscale(1) brightness(1.2);
    border-radius: .35rem;
  }

  .sfx-sim-row:hover {
    background: rgba(255,255,255,0.05);
  }

  .sfx-sim-waveform img {
    width: 220px;
    height: 40px;
    object-fit: cover;
    filter: grayscale(1) brightness(1.2);
    border-radius: .25rem;
  }

  .sfx-sim-icon {
    width: 40px;
    height: 40px;
    background: #ff9f2c;
    border-radius: .35rem;
  }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const audio = new Audio();
    const btn = document.querySelector('.sfx-detail-play');

    if (!btn) return;

    btn.addEventListener('click', () => {
        const src = btn.dataset.audioSrc;

        if (!src) return;

        if (audio.src !== src) {
            audio.src = src;
        }

        if (audio.paused) {
            audio.play();
            btn.querySelector('i').className = 'bi bi-pause-fill fs-3';
        } else {
            audio.pause();
            btn.querySelector('i').className = 'bi bi-play-fill fs-3';
        }
    });

    audio.addEventListener('ended', () => {
        if (btn) {
            btn.querySelector('i').className = 'bi bi-play-fill fs-3';
        }
    });
});
</script>
@endpush
