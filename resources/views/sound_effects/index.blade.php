@extends('layouts.master')

@section('title', 'Sound effects – BeatHive')
@section('heading', 'Sound effects')

@section('content')
@php
    /**
     * @var \Illuminate\Support\Collection|\App\Models\SoundCategory[] $categories
     */

    // Pastikan $q tetap jalan walaupun belum dikirim eksplisit
    $q = $q ?? request('q');

    // Deteksi apakah ada subgroups, fallback kalau controller belum nyiapin $hasSubgroups
    $hasSubgroups = $hasSubgroups
        ?? (isset($subGroups) && !empty($subGroups) && collect($subGroups)->flatten()->isNotEmpty());
@endphp

<div class="page-content">

  {{-- ================== HERO SEARCH BAR ================== --}}
  <section class="mb-4">
    <div class="card border-0 shadow-sm bg-dark-900 sfx-hero-card">
      <div class="card-body p-3 p-md-4">
        <form action="{{ route('sound_effects.browse') }}"
              method="get"
              class="d-flex flex-column flex-md-row align-items-stretch gap-2"
              role="search"
              aria-label="Search sound effects">

          {{-- Search input --}}
          <div class="flex-grow-1">
            <div class="input-group input-group-lg sfx-search-group">
              <span class="input-group-text border-0 bg-dark-800 text-muted">
                <i class="bi bi-search" aria-hidden="true"></i>
              </span>
              <input type="text"
                     name="q"
                     value="{{ $q ?? '' }}"
                     class="form-control border-0 bg-dark-800 text-light"
                     placeholder="Search for sound effects"
                     aria-label="Search for sound effects">
            </div>
          </div>

          {{-- Submit --}}
          <button class="btn btn-light btn-lg px-4 fw-semibold" type="submit">
            Search
          </button>
        </form>
      </div>
    </div>
  </section>

  {{-- ================== CATEGORY GRID ================== --}}
  <section class="mb-5">
    <div class="row g-3 g-md-4">

      @forelse($categories as $cat)
        @php
            $bg   = $cat->bg_color ?: '#FFBEB5';

            $icon = $cat->icon_path
                        ? (str_starts_with($cat->icon_path,'http') ? $cat->icon_path : asset($cat->icon_path))
                        : null;

            $to    = route('sound_effects.browse', ['category' => $cat->id]);
            $count = (int) ($cat->sound_effects_count ?? 0);
        @endphp

        <div class="col-6 col-md-4 col-lg-3">
          <a href="{{ $to }}" class="text-decoration-none text-reset">
            <div class="card h-100 border-0 sfx-category-tile"
                 style="background: {{ e($bg) }};">

              <div class="sfx-category-art d-flex align-items-center justify-content-center">
                @if($icon)
                  <img src="{{ $icon }}" class="img-fluid"
                       alt="{{ $cat->name }} icon"
                       style="max-height:100%;max-width:100%;object-fit:contain;">
                @else
                  <div class="sfx-wave-placeholder" aria-hidden="true">
                      <span></span><span></span><span></span><span></span>
                  </div>
                @endif
              </div>

              <div class="card-body d-flex flex-column">
                <div class="fw-semibold text-truncate">{{ $cat->name }}</div>
                <div class="small text-muted">
                  {{ $count }} {{ \Illuminate\Support\Str::plural('sound', $count) }}
                  {{-- Kalau mau i18n, bisa ganti ke trans_choice di sini --}}
                </div>
              </div>

            </div>
          </a>
        </div>

      @empty
        <div class="col-12">
          <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
              <div class="mb-2">
                <i class="bi bi-volume-mute fs-1 text-muted" aria-hidden="true"></i>
              </div>
              <h5 class="mb-1">No sound effect categories yet</h5>
              <p class="text-muted mb-0">
                Add categories from your dashboard and they’ll appear here.
              </p>
            </div>
          </div>
        </div>
      @endforelse

    </div>
  </section>

  {{-- ================== SUBCATEGORY FOOTER ================== --}}
  @if($hasSubgroups)
    <section class="mt-4 pt-4 border-top sfx-subfooter">
      <div class="row g-4">

        @foreach($categories as $cat)
          @php
              // Pastikan tetap aman kalau index nggak ada
              $subs = isset($subGroups[$cat->id])
                  ? collect($subGroups[$cat->id])
                  : collect();
          @endphp

          @if($subs->count())
            <div class="col-6 col-md-4 col-lg-3">
              <div class="fw-semibold mb-2">{{ $cat->name }}</div>

              <ul class="list-unstyled m-0 small">
                @foreach($subs as $s)
                  <li class="mb-1">
                    <a href="{{ route('sound_effects.browse', ['subcategory' => $s->id]) }}"
                       class="link-secondary text-decoration-none">
                      {{ $s->name }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          @endif

        @endforeach

      </div>
    </section>
  @endif

</div>
@endsection

@push('styles')
<style>
  /* ====== Hero Search bar ====== */
  .sfx-hero-card {
    background: #17181d;
    border-radius: 1rem;
  }

  .sfx-search-group .form-control::placeholder {
    color: #777;
  }

  .bg-dark-800 { background-color: #20222a !important; }
  .bg-dark-900 { background-color: #17181d !important; }

  /* ====== Category Grid ====== */
  .sfx-category-tile {
    border-radius: 1rem;
    overflow: hidden;
    transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
  }

  .sfx-category-tile:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 30px rgba(0,0,0,.18);
    filter: brightness(.96);
  }

  .sfx-category-art {
    height: 160px;
    padding: 1.5rem;
  }

  @media (max-width: 575px) {
    .sfx-category-art { height: 130px; }
  }

  /* Wave Placeholder */
  .sfx-wave-placeholder {
    display: flex;
    gap: 6px;
    width: 60%;
    max-width: 130px;
  }
  .sfx-wave-placeholder span {
    flex: 1;
    background: rgba(0,0,0,.22);
    border-radius: .35rem;
  }
  .sfx-wave-placeholder span:nth-child(1){height:45px;}
  .sfx-wave-placeholder span:nth-child(2){height:70px;}
  .sfx-wave-placeholder span:nth-child(3){height:35px;}
  .sfx-wave-placeholder span:nth-child(4){height:60px;}

  /* ====== Subcategory Footer ====== */
  .sfx-subfooter {
    font-size: .9rem;
  }
  .sfx-subfooter a:hover {
    text-decoration: underline;
  }
</style>
@endpush
