@extends('layouts.master')

@section('title', 'Sound Effects – BeatHive')
@section('heading', 'Sound Effects')

@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">All Sound Effects</h5>
      <a href="{{ route('sound_effects.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i> Add Sound Effect
      </a>
    </div>

    <div class="card-body p-0">
      @if(session('success')) <div class="alert alert-success m-3">{{ session('success') }}</div> @endif

      @forelse($sounds as $sound)
        @php
          $prevRel = $sound->preview_path ? str_replace('storage/','',$sound->preview_path) : null;
          $waveRel = $sound->waveform_image ? str_replace('storage/','',$sound->waveform_image) : null;
          $hasPreview = $prevRel ? Storage::disk('public')->exists($prevRel) : false;
          $hasWave    = $waveRel ? Storage::disk('public')->exists($waveRel) : false;
          $audioSrc   = $hasPreview ? asset($sound->preview_path) : null;
          $waveSrc    = $hasWave ? asset($sound->waveform_image) : null;
          $dur = $sound->duration_seconds ? gmdate($sound->duration_seconds>=3600?'H:i:s':'i:s', (int)round($sound->duration_seconds)) : '00:00';
        @endphp

        <div class="d-flex align-items-center px-3 py-2 border-bottom">
          <div class="me-3">
            <button class="btn btn-dark btn-sm rounded-circle sfx-play"
                    data-id="{{ $sound->id }}"
                    data-src="{{ $audioSrc }}"
                    data-title="{{ $sound->title }}"
                    data-author="{{ $sound->author->name ?? 'Unknown' }}"
                    @if(!$hasPreview) disabled @endif>
              <i class="bi bi-play-fill"></i>
            </button>
          </div>

          <div class="flex-grow-1">
            <div class="fw-semibold">{{ $sound->title }}</div>
            <div class="text-muted small">{{ $sound->author->name ?? 'Unknown' }}</div>
          </div>

          <div class="px-3" style="width:45%">
            @if($hasWave)
              <div class="position-relative">
                <img src="{{ $waveSrc }}" class="img-fluid rounded-2 border" style="height:42px;object-fit:cover;width:100%;">
                <div class="progress-overlay" id="progress-{{ $sound->id }}"></div>
              </div>
            @else
              <div class="bg-light rounded-2 border d-flex align-items-center justify-content-center" style="height:42px;">
                <span class="text-muted small">No waveform</span>
              </div>
            @endif
          </div>

          <div class="text-muted small me-3" style="width:110px;">
            <div class="fw-semibold">{{ $dur }}</div>
            <div>{{ $sound->category->name ?? '—' }}</div>
          </div>

          <div class="d-flex align-items-center gap-2">
            <a href="{{ asset($sound->file_path) }}" class="btn btn-light btn-sm rounded-circle" title="Download" download>
              <i class="bi bi-download"></i>
            </a>

            @can('update', $sound)
              <a href="{{ route('sound_effects.edit', $sound->id) }}" class="btn btn-light btn-sm rounded-circle">
                <i class="bi bi-pencil"></i>
              </a>
            @endcan

            @can('delete', $sound)
              <form action="{{ route('sound_effects.destroy', $sound->id) }}" method="POST" onsubmit="return confirm('Delete this sound effect?')">
                @csrf @method('DELETE')
                <button class="btn btn-light btn-sm rounded-circle text-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            @endcan
          </div>

          @if($hasPreview)
            <audio id="audio-{{ $sound->id }}" preload="none" src="{{ $audioSrc }}"></audio>
          @endif
        </div>
      @empty
        <div class="px-4 py-5 text-center text-muted">No sound effects found.</div>
      @endforelse

      <div class="p-3">
        {{ $sounds->links() }}
      </div>
    </div>
  </div>

  {{-- Sticky mini player --}}
  <div id="sfxPlayer" class="sfx-player shadow d-none">
    <div class="container-fluid">
      <div class="d-flex align-items-center gap-3">
        <div class="thumb bg-light rounded-3 d-flex align-items-center justify-content-center">
          <i class="bi bi-soundwave text-secondary"></i>
        </div>
        <div class="flex-grow-1">
          <div class="fw-semibold" id="spTitle">—</div>
          <div class="small text-muted" id="spAuthor">—</div>
          <input type="range" id="spSeek" min="0" max="100" value="0" class="form-range mt-1">
        </div>
        <div class="d-flex align-items-center gap-2">
          <button class="btn btn-dark rounded-circle" id="spPlay" style="width:44px;height:44px">
            <i class="bi bi-play-fill"></i>
          </button>
          <div class="small text-muted" id="spTime">00:00</div>
        </div>
      </div>
    </div>
    <audio id="spAudio" preload="none"></audio>
  </div>
</div>
@endsection

@push('styles')
<style>
  .progress-overlay{position:absolute;left:0;top:0;height:100%;width:0%;border-radius:.5rem;background:rgba(148,86,255,.35)}
  .sfx-player{position:fixed;left:0;right:0;bottom:0;background:#1c1c1c;color:#fff;padding:.6rem .75rem;z-index:1040}
  .sfx-player .thumb{width:44px;height:44px}
</style>
@endpush

@push('scripts')
<script>
(function(){
  let currentRowProgress = null;
  const sp = {
    el: document.getElementById('sfxPlayer'),
    audio: document.getElementById('spAudio'),
    play: document.getElementById('spPlay'),
    seek: document.getElementById('spSeek'),
    time: document.getElementById('spTime'),
    title: document.getElementById('spTitle'),
    author: document.getElementById('spAuthor'),
  };
  const fmt = s => { s=Math.max(0,Math.floor(s||0));return String(Math.floor(s/60)).padStart(2,'0')+':'+String(s%60).padStart(2,'0'); };

  document.addEventListener('click', function(e){
    const btn = e.target.closest('.sfx-play');
    if(!btn) return;
    const id = btn.dataset.id;
    const src = btn.dataset.src;
    if(!src) return;

    sp.audio.src = src;
    sp.title.textContent = btn.dataset.title || '—';
    sp.author.textContent = btn.dataset.author || '—';
    sp.el.classList.remove('d-none');

    currentRowProgress = document.getElementById('progress-'+id) || null;
    sp.audio.play();
    sp.play.innerHTML = '<i class="bi bi-pause-fill"></i>';
  });

  sp.play.addEventListener('click', function(){
    if(sp.audio.paused){ sp.audio.play(); sp.play.innerHTML='<i class="bi bi-pause-fill"></i>'; }
    else { sp.audio.pause(); sp.play.innerHTML='<i class="bi bi-play-fill"></i>'; }
  });

  sp.audio.addEventListener('timeupdate', function(){
    if(!sp.audio.duration) return;
    const pct = sp.audio.currentTime / sp.audio.duration * 100;
    sp.seek.value = pct;
    sp.time.textContent = fmt(sp.audio.currentTime);
    if(currentRowProgress) currentRowProgress.style.width = pct + '%';
  });

  sp.audio.addEventListener('loadedmetadata', ()=>{ sp.seek.value=0; sp.time.textContent='00:00'; });
  sp.audio.addEventListener('ended', ()=>{ sp.play.innerHTML='<i class="bi bi-play-fill"></i>'; });
  sp.seek.addEventListener('input', ()=>{ if(sp.audio.duration){ sp.audio.currentTime = sp.seek.value/100*sp.audio.duration; }});
})();
</script>
@endpush
