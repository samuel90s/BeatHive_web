@extends('layouts.master')

@section('title', 'Sound Effects – BeatHive')
@section('heading', 'Sound Effects')

@php
  use Illuminate\Support\Facades\Storage;
  $heroBg = '#CFEEDC';
  $heroIcon = '<i class="bi bi-clock-history"></i>';
  $sectionTitle = request('category_name', 'Sound Effects');
@endphp

@section('content')
<div class="page-content">

  {{-- HERO / BANNER --}}
  <div class="rounded-4 border mb-4" style="background: {{ $heroBg }};">
    <div class="p-4 d-flex align-items-center justify-content-between">
      <div>
        <div class="text-uppercase small fw-semibold mb-2">Sound Effects</div>
        <h2 class="m-0" style="font-weight:800">{{ $sectionTitle }}</h2>
      </div>
      <div class="display-5 text-secondary">{!! $heroIcon !!}</div>
    </div>
  </div>

  {{-- TOOLBAR --}}
  <div class="d-flex align-items-center justify-content-between mb-2">
    <div class="d-flex align-items-center gap-3 text-muted">
      <span><i class="bi bi-sliders"></i> Duration</span>
    </div>
    <div class="d-flex align-items-center gap-3">
      <div class="text-muted"><i class="bi bi-activity"></i> Variations</div>
      <div class="text-muted"><i class="bi bi-sort-down-alt"></i> Popular</div>
    </div>
  </div>

  {{-- LIST ala Epidemic --}}
  <div class="sfx-list card border-0">
    <div class="card-body p-0">
      @forelse($sounds as $sound)
        @php
          $prevRel = $sound->preview_path ? str_replace('storage/', '', $sound->preview_path) : null;
          $waveRel = $sound->waveform_image ? str_replace('storage/', '', $sound->waveform_image) : null;

          $hasPreview = $prevRel ? Storage::disk('public')->exists($prevRel) : false;
          $hasWave    = $waveRel ? Storage::disk('public')->exists($waveRel) : false;

          $audioSrc   = $hasPreview ? asset($sound->preview_path) : null;
          $waveSrc    = $hasWave ? asset($sound->waveform_image) : null;

          $dur = $sound->duration_seconds ? gmdate($sound->duration_seconds >= 3600 ? "H:i:s" : "i:s", (int)round($sound->duration_seconds)) : '00:00';
        @endphp

        <div class="sfx-row d-flex align-items-center px-3 py-2 border-bottom">
          {{-- icon --}}
          <div class="me-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center"
                 style="width:48px;height:48px;background:#F3F3F3;">
              <i class="bi bi-soundwave text-secondary"></i>
            </div>
          </div>

          {{-- title + author --}}
          <div class="col-title flex-grow-1">
            <div class="fw-semibold">{{ $sound->title }}</div>
            <div class="small text-muted">
              {{ $sound->author->name ?? 'Unknown' }}
            </div>
          </div>

          {{-- waveform --}}
          <div class="col-wave px-3" style="width:42%">
            <div class="wave-wrap position-relative">
              @if($hasWave)
                <img src="{{ $waveSrc }}" class="img-fluid rounded-2 border" style="height:42px;object-fit:cover;width:100%;">
                <div class="progress-overlay" id="progress-{{ $sound->id }}"></div>
              @else
                <div class="bg-light rounded-2 d-flex align-items-center justify-content-center border" style="height:42px;">
                  <span class="text-muted small">No waveform</span>
                </div>
              @endif
            </div>
          </div>

          {{-- duration & category --}}
          <div class="col-meta text-muted small me-3" style="width:120px;">
            <div class="fw-semibold">{{ $dur }}</div>
            <div>{{ $sound->category->name ?? '—' }}</div>
          </div>

          {{-- actions --}}
          <div class="col-actions d-flex align-items-center gap-2">
            <button class="btn btn-dark btn-sm rounded-circle sfx-play"
                    data-id="{{ $sound->id }}"
                    data-src="{{ $audioSrc }}"
                    data-title="{{ $sound->title }}"
                    data-author="{{ $sound->author->name ?? 'Unknown' }}"
                    data-wave="{{ $hasWave ? $waveSrc : '' }}"
                    @if(!$hasPreview) disabled @endif
                    title="Play">
              <i class="bi bi-play-fill"></i>
            </button>

            <button class="btn btn-light btn-sm rounded-circle" title="Save">
              <i class="bi bi-heart"></i>
            </button>
            <button class="btn btn-light btn-sm rounded-circle" title="Add">
              <i class="bi bi-plus"></i>
            </button>
            <a href="{{ asset($sound->file_path) }}" class="btn btn-light btn-sm rounded-circle" title="Download" download>
              <i class="bi bi-download"></i>
            </a>

            @can('update', $sound)
              <a href="{{ route('sound_effects.edit', $sound->id) }}" class="btn btn-light btn-sm rounded-circle" title="Edit">
                <i class="bi bi-pencil"></i>
              </a>
            @endcan

            @can('delete', $sound)
              <button class="btn btn-light btn-sm rounded-circle text-danger sfx-del"
                      data-del-id="{{ $sound->id }}"
                      title="Delete">
                <i class="bi bi-trash"></i>
              </button>
              <form id="del-form-{{ $sound->id }}" action="{{ route('sound_effects.destroy', $sound->id) }}"
                    method="POST" class="d-none">
                @csrf
                @method('DELETE')
              </form>
            @endcan
            
            {{-- Hidden audio per-row (untuk progress overlay) --}}
            @if($hasPreview)
              <audio id="audio-{{ $sound->id }}" preload="none" src="{{ $audioSrc }}"></audio>
            @endif
          </div>
        </div>
      @empty
        <div class="px-4 py-5 text-center text-muted">No sound effects found.</div>
      @endforelse
    </div>
  </div>

  <div class="mt-3">
    {{ $sounds->links() }}
  </div>

  {{-- STICKY MINI PLAYER --}}
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
  .sfx-row:hover { background:#0f0f0f08; }
  .wave-wrap { position:relative; }
  .progress-overlay{
    position:absolute; left:0; top:0; height:100%;
    width:0%; pointer-events:none; border-radius:.5rem;
    background: linear-gradient(90deg, rgba(148,86,255,.35), rgba(148,86,255,.35));
  }
  .sfx-player{
    position: fixed; left:0; right:0; bottom:0;
    background:#1c1c1c; color:#fff; padding:.6rem .75rem;
    z-index: 1040;
  }
  .sfx-player .thumb{ width:44px;height:44px; }
</style>
@endpush

@push('scripts')
<script>
(function(){
  let currentRowAudio = null;
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

  const fmt = s => {
    s = Math.max(0, Math.floor(s||0));
    const m = String(Math.floor(s/60)).padStart(2,'0');
    const ss = String(s%60).padStart(2,'0');
    return `${m}:${ss}`;
  };

  // Play button per-row
  document.addEventListener('click', function(e){
    const btn = e.target.closest('.sfx-play');
    if(!btn) return;

    const id  = btn.getAttribute('data-id');
    const src = btn.getAttribute('data-src');
    const title = btn.getAttribute('data-title');
    const author = btn.getAttribute('data-author');

    const rowAudio = document.getElementById('audio-'+id);
    const progress = document.getElementById('progress-'+id);
    if(!src || !rowAudio) return;

    sp.audio.src = src;
    sp.title.textContent = title;
    sp.author.textContent = author;
    sp.el.classList.remove('d-none');

    if(currentRowAudio && currentRowAudio !== rowAudio){
      currentRowAudio.pause();
    }
    currentRowAudio = rowAudio;
    currentRowProgress = progress;

    sp.audio.play();
    btn.innerHTML = '<i class="bi bi-pause-fill"></i>';
    sp.play.innerHTML = '<i class="bi bi-pause-fill"></i>';
  });

  // Sticky player play/pause
  sp.play.addEventListener('click', function(){
    if(sp.audio.paused){
      sp.audio.play();
      sp.play.innerHTML = '<i class="bi bi-pause-fill"></i>';
    } else {
      sp.audio.pause();
      sp.play.innerHTML = '<i class="bi bi-play-fill"></i>';
    }
  });

  // Progress & time
  sp.audio.addEventListener('timeupdate', function(){
    if(sp.audio.duration){
      const pct = (sp.audio.currentTime / sp.audio.duration) * 100;
      sp.seek.value = pct;
      sp.time.textContent = fmt(sp.audio.currentTime);
      if(currentRowProgress) currentRowProgress.style.width = pct + '%';
    }
  });

  sp.audio.addEventListener('loadedmetadata', function(){
    sp.seek.value = 0;
    sp.time.textContent = '00:00';
  });

  sp.audio.addEventListener('ended', function(){
    sp.play.innerHTML = '<i class="bi bi-play-fill"></i>';
  });

  // Seek
  sp.seek.addEventListener('input', function(){
    if(sp.audio.duration){
      sp.audio.currentTime = (sp.seek.value/100)*sp.audio.duration;
    }
  });

  // DELETE confirm (per-row)
  document.addEventListener('click', function(e){
    const delBtn = e.target.closest('.sfx-del');
    if(!delBtn) return;
    const id = delBtn.getAttribute('data-del-id');
    if(confirm('Delete this sound effect?')){
      document.getElementById('del-form-' + id).submit();
    }
  });
})();
</script>
@endpush
