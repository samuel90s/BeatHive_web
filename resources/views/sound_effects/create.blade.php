@extends('layouts.master')

@section('title', 'Add Sound Effect – BeatHive')
@section('heading', 'Add Sound Effect')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-body">

      {{-- Notifikasi error --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Notifikasi sukses --}}
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <form method="POST" action="{{ route('sound_effects.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
          <label for="title" class="form-label">Title *</label>
          <input type="text" id="title" name="title"
                 class="form-control @error('title') is-invalid @enderror"
                 value="{{ old('title') }}" required>
          @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="file" class="form-label">Sound File *</label>
          <input type="file" id="file" name="file"
                 class="form-control @error('file') is-invalid @enderror"
                 accept=".wav,.mp3,.ogg,.flac,.aiff" required>
          @error('file')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <div class="form-text text-muted mt-1">Allowed formats: WAV, MP3, OGG, FLAC, AIFF. Max size: 50 MB.</div>
        </div>

        <div class="mb-3">
          <label for="category_id" class="form-label">Category</label>
          <select id="category_id" name="category_id"
                  class="form-select @error('category_id') is-invalid @enderror">
            <option value="">-- Select Category --</option>
            @foreach(\App\Models\SoundCategory::all() as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
          @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">Upload</button>
          <a href="{{ route('sound_effects.index') }}" class="btn btn-light">Cancel</a>
        </div>
      </form>

    </div>
  </div>
</div>
@endsection
