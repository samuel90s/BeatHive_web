@extends('layouts.master')

@section('title', 'Edit Sound Effect – BeatHive')
@section('heading', 'Edit Sound Effect')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-body">

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <form method="POST" action="{{ route('sound_effects.update', $sound_effect->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label for="title" class="form-label">Title *</label>
          <input type="text" id="title" name="title"
                 class="form-control @error('title') is-invalid @enderror"
                 value="{{ old('title', $sound_effect->title) }}" required>
          @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label for="category_id" class="form-label">Category</label>
          <select id="category_id" name="category_id" class="form-select">
            <option value="">-- Select Category --</option>
            @foreach(\App\Models\SoundCategory::all() as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id', $sound_effect->category_id) == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label for="license_type_id" class="form-label">License</label>
          <select id="license_type_id" name="license_type_id" class="form-select">
            <option value="">-- Select License --</option>
            @foreach(\App\Models\SoundLicense::all() as $license)
              <option value="{{ $license->id }}" {{ old('license_type_id', $sound_effect->license_type_id) == $license->id ? 'selected' : '' }}>
                {{ $license->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label for="is_active" class="form-label">Status</label>
          <select id="is_active" name="is_active" class="form-select">
            <option value="1" {{ old('is_active', $sound_effect->is_active) ? 'selected' : '' }}>Active</option>
            <option value="0" {{ !old('is_active', $sound_effect->is_active) ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="slug" class="form-label">Slug</label>
          <input type="text" id="slug" name="slug" class="form-control" value="{{ $sound_effect->slug }}" readonly>
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">Update</button>
          <a href="{{ route('sound_effects.index') }}" class="btn btn-light">Cancel</a>
        </div>
      </form>

    </div>
  </div>
</div>
@endsection
