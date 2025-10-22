@extends('layouts.master')

@section('title', 'Add Category – BeatHive')
@section('heading', 'Add Category')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-body">
      <form method="POST" action="{{ route('sound_categories.store') }}">
        @csrf
        <div class="mb-3">
          <label for="name" class="form-label">Name *</label>
          <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                 value="{{ old('name') }}">
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label for="bg_color" class="form-label">Background Color</label>
          <input type="color" id="bg_color" name="bg_color" class="form-control form-control-color"
                 value="{{ old('bg_color', '#ff9900') }}">
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('sound_categories.index') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
</div>
@endsection
