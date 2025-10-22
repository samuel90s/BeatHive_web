@extends('layouts.master')

@section('title', 'Edit Subcategory – BeatHive')
@section('heading', 'Edit Subcategory')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-body">
      <form method="POST" action="{{ route('sound_subcategories.update', $sound_subcategory->id) }}">
        @csrf @method('PUT')

        <div class="mb-3">
          <label class="form-label">Category *</label>
          <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id', $sound_subcategory->category_id)==$cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
          @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Name *</label>
          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                 value="{{ old('name', $sound_subcategory->name) }}" required>
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Slug</label>
          <input type="text" class="form-control" value="{{ $sound_subcategory->slug }}" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('sound_subcategories.index') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
</div>
@endsection
