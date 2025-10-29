@extends('layouts.master')

@section('title', 'Sound effects – BeatHive')
@section('heading', 'Sound effects')

@php
  use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="page-content">

  {{-- SEARCH --}}
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
      <form action="{{ route('sound_effects.browse') }}" method="get" class="d-flex align-items-center gap-2">
        <div class="input-group">
          <span class="input-group-text bg-body"><i class="bi bi-search"></i></span>
          <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control"
                 placeholder="Search for sound effects">
        </div>
        <button class="btn btn-dark" type="submit">Search</button>
      </form>
    </div>
  </div>

  {{-- GRID KATEGORI --}}
  <div class="row g-3">
    @forelse($categories as $cat)
      @php
        $bg = $cat->bg_color ?: '#FFD1C8'; // fallback warna
        $icon = $cat->icon_path ? (str_starts_with($cat->icon_path,'http') ? $cat->icon_path : asset($cat->icon_path))
                                : null;
        // link menuju halaman listing efek per kategori (sesuaikan route targetnya)
        $to = route('sound_effects.index', ['category' => $cat->id]);
      @endphp
      <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ $to }}" class="text-decoration-none text-reset">
          <div class="card h-100 border-0 sfx-tile" style="background: {{ $bg }};">
            <div class="p-4 d-flex align-items-center justify-content-center" style="height:150px;">
              @if($icon)
                <img src="{{ $icon }}" alt="" style="max-height:100%;max-width:100%;object-fit:contain;">
              @else
                <div class="display-5 text-secondary"><i class="bi bi-graph-up-arrow"></i></div>
              @endif
            </div>
            <div class="card-body">
              <div class="fw-semibold text-truncate">{{ $cat->name }}</div>
              <div class="small text-muted">{{ $cat->sound_effects_count ?? 0 }} sounds</div>
            </div>
          </div>
        </a>
      </div>
    @empty
      <div class="text-muted">No categories.</div>
    @endforelse
  </div>

  {{-- SUB-KATEGORI FOOTER (kategori → daftar link) --}}
  <div class="mt-5 pt-3 border-top">
    <div class="row g-4">
      @foreach($categories as $cat)
        @php $subs = $subGroups[$cat->id] ?? collect(); @endphp
        @if($subs->count())
          <div class="col-6 col-md-4 col-lg-3">
            <div class="fw-semibold mb-2">{{ $cat->name }}</div>
            <ul class="list-unstyled m-0">
              @foreach($subs as $s)
                <li class="mb-1">
                  <a class="link-secondary text-decoration-none"
                     href="{{ route('sound_effects.index', ['subcategory' => $s->id]) }}">
                    {{ $s->name }}
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
        @endif
      @endforeach
    </div>
  </div>

</div>
@endsection

@push('styles')
<style>
  .sfx-tile { transition: transform .12s ease, box-shadow .12s ease; border-radius: .75rem; }
  .sfx-tile:hover { transform: translateY(-2px); box-shadow: 0 10px 22px rgba(0,0,0,.08); }
</style>
@endpush
