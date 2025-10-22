@extends('layouts.master')

@section('title', 'Edit Category – BeatHive')
@section('heading', 'Edit Category')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-body">

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <form method="POST" action="{{ route('sound_categories.update', $sound_category->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label for="name" class="form-label">Name *</label>
          <input type="text" id="name" name="name"
                 class="form-control @error('name') is-invalid @enderror"
                 value="{{ old('name', $sound_category->name) }}" required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="bg_color" class="form-label">Background Color</label>
          <input type="color" id="bg_color" name="bg_color"
                 class="form-control form-control-color"
                 value="{{ old('bg_color', $sound_category->bg_color ?? '#ff9900') }}">
        </div>

        <div class="mb-3">
          <label for="slug" class="form-label">Slug</label>
          <input type="text" id="slug" name="slug" class="form-control" value="{{ $sound_category->slug }}" readonly>
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">Update</button>
          <a href="{{ route('sound_categories.index') }}" class="btn btn-light">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
