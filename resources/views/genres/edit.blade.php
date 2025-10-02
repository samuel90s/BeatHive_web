@extends('layouts.master')

@section('title', 'Edit Genre – BeatHive')
@section('heading', 'Edit Genre')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-body">
      <form method="POST" action="{{ route('genres.update', $genre) }}">
        @csrf @method('PUT')
        <div class="mb-3">
          <label for="name" class="form-label">Name *</label>
          <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                 value="{{ old('name', $genre->name) }}">
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea id="description" name="description" class="form-control">{{ old('description', $genre->description) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('genres.index') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
</div>
@endsection
