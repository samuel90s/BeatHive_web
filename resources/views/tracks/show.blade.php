{{-- resources/views/tracks/show.blade.php --}}
@extends('layouts.master')

@section('title', 'Track Detail – ' . $track->title . ' | BeatHive')

@section('content')
<div class="content-wrapper container">
  <div class="page-heading d-flex justify-content-between align-items-center">
    <div>
      <h3>{{ $track->title }}</h3>
      <p class="text-muted">Detail informasi track musik.</p>
    </div>
    <a href="{{ route('tracks.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left"></i> Back
    </a>
  </div>

  <div class="row">
    <div class="col-md-4">
      {{-- Cover --}}
      @php
        $cover = $track->cover_path && Storage::disk('public')->exists($track->cover_path)
          ? asset('storage/'.$track->cover_path)
          : asset('assets/compiled/svg/favicon.svg');
      @endphp
      <img src="{{ $cover }}" alt="cover" class="img-fluid rounded shadow-sm mb-3"
           style="max-height:300px;object-fit:cover">

      {{-- Preview Audio --}}
      @if($track->preview_path && Storage::disk('public')->exists($track->preview_path))
        <div class="card p-3">
          <h6 class="fw-bold mb-2">Preview</h6>
          <audio src="{{ asset('storage/'.$track->preview_path) }}" controls preload="none" style="width:100%"></audio>
        </div>
      @endif
    </div>

    <div class="col-md-8">
      <div class="card">
        <div class="card-body">
          <h5 class="mb-3">Metadata</h5>
          <table class="table table-borderless">
            <tr>
              <th style="width:160px">Title</th>
              <td>{{ $track->title }}</td>
            </tr>
            <tr>
              <th>Slug</th>
              <td>{{ $track->slug }}</td>
            </tr>
            <tr>
              <th>Artist</th>
              <td>{{ $track->artist }}</td>
            </tr>
            <tr>
              <th>Genre</th>
              <td>{{ optional($track->genre)->name ?? '-' }}</td>
            </tr>
            <tr>
              <th>BPM / Key</th>
              <td>{{ $track->bpm ?? '-' }} / {{ $track->musical_key ?? '-' }}</td>
            </tr>
            <tr>
              <th>Mood</th>
              <td>{{ $track->mood ?? '-' }}</td>
            </tr>
            <tr>
              <th>Tags</th>
              <td>{{ $track->tags ?? '-' }}</td>
            </tr>
            <tr>
              <th>Description</th>
              <td>{{ $track->description ?? '-' }}</td>
            </tr>
            <tr>
              <th>Price (IDR)</th>
              <td>
                @if(!is_null($track->price_idr))
                  Rp {{ number_format($track->price_idr, 0, ',', '.') }}
                @else
                  -
                @endif
              </td>
            </tr>
            <tr>
              <th>Duration</th>
              <td>
                @php
                  $dur = (int)($track->duration_seconds ?? 0);
                  $mm = floor($dur / 60);
                  $ss = str_pad($dur % 60, 2, '0', STR_PAD_LEFT);
                @endphp
                {{ $dur ? $mm.':'.$ss : '-' }}
              </td>
            </tr>
            <tr>
              <th>Release Date</th>
              <td>{{ $track->release_date?->format('Y-m-d') ?? '-' }}</td>
            </tr>
            <tr>
              <th>Status</th>
              <td>
                @if($track->is_published)
                  <span class="badge bg-success">Published</span>
                @else
                  <span class="badge bg-secondary">Draft</span>
                @endif
              </td>
            </tr>
            <tr>
              <th>Created At</th>
              <td>{{ $track->created_at?->format('Y-m-d H:i') }}</td>
            </tr>
            <tr>
              <th>Updated At</th>
              <td>{{ $track->updated_at?->format('Y-m-d H:i') }}</td>
            </tr>
          </table>
        </div>
      </div>

      {{-- Actions khusus admin --}}
      @if(auth()->check() && auth()->user()->role == 1)
        <div class="d-flex gap-2 mt-3">
          <a href="{{ route('tracks.edit', $track) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit
          </a>
          <form action="{{ route('tracks.destroy', $track) }}" method="POST"
                onsubmit="return confirm('Delete this track?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger"><i class="bi bi-trash"></i> Delete</button>
          </form>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
