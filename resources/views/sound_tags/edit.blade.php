@extends('layouts.master')

@section('title', 'Edit Tag – BeatHive')
@section('heading', 'Edit Tag')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-body">
      <form method="POST" action="{{ route('sound_tags.update', $sound_tag->id) }}">
        @csrf @method('PUT')

        <div class="mb-3">
          <label for="name" class="form-label">Name *</label>
          <input type="text" id="name" name="name"
                 class="form-control @error('name') is-invalid @enderror"
                 value="{{ old('name', $sound_tag->name) }}" required>
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label for="slug" class="form-label">Slug</label>
          <input type="text" id="slug" class="form-control" value="{{ $sound_tag->slug }}" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('sound_tags.index') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
</div>
@endsection
