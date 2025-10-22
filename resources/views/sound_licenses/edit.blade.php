@extends('layouts.master')

@section('title', 'Edit License – BeatHive')
@section('heading', 'Edit License')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-body">
      <form method="POST" action="{{ route('sound_licenses.update', $sound_license->id) }}">
        @csrf @method('PUT')

        <div class="mb-3">
          <label for="name" class="form-label">Name *</label>
          <input type="text" id="name" name="name"
                 class="form-control @error('name') is-invalid @enderror"
                 value="{{ old('name', $sound_license->name) }}" required>
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label for="price" class="form-label">Price (optional)</label>
          <input type="number" step="0.01" min="0" id="price" name="price"
                 class="form-control @error('price') is-invalid @enderror"
                 value="{{ old('price', $sound_license->price) }}">
          @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea id="description" name="description"
                    class="form-control @error('description') is-invalid @enderror"
                    rows="4">{{ old('description', $sound_license->description) }}</textarea>
          @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">Update</button>
          <a href="{{ route('sound_licenses.index') }}" class="btn btn-light">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
