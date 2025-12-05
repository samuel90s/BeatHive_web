@extends('layouts.master')

@section('title', 'Edit Sound Effect – BeatHive')
@section('heading', 'Edit Sound Effect')

@section('content')
@php
    /**
     * @var \App\Models\SoundEffect                           $sound_effect
     * @var \Illuminate\Support\Collection|\App\Models\SoundCategory[]    $categories
     * @var \Illuminate\Support\Collection|\App\Models\SoundSubcategory[] $subcategories
     * @var \Illuminate\Support\Collection|\App\Models\SoundTag[]         $tags
     */

    $selectedTags = old('tags', $sound_effect->tags->pluck('id')->all());

    // Kalau controller belum ngirim $licenses, fallback query di sini (biar nggak error)
    $licenses = $licenses ?? \App\Models\SoundLicense::orderBy('name')->get();
@endphp

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

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <form method="POST" action="{{ route('sound_effects.update', $sound_effect->id) }}">
        @csrf
        @method('PUT')

        {{-- Title --}}
        <div class="mb-3">
          <label for="title" class="form-label">Title *</label>
          <input type="text" id="title" name="title"
                 class="form-control @error('title') is-invalid @enderror"
                 value="{{ old('title', $sound_effect->title) }}" required>
          @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Category --}}
        <div class="mb-3">
          <label for="category_id" class="form-label">Category</label>
          <select id="category_id"
                  name="category_id"
                  class="form-select @error('category_id') is-invalid @enderror">
            <option value="">-- Select Category --</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}"
                      {{ (string) old('category_id', $sound_effect->category_id) === (string) $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
          @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Subcategory --}}
        <div class="mb-3">
          <label for="subcategory_id" class="form-label">Subcategory</label>
          <select id="subcategory_id"
                  name="subcategory_id"
                  class="form-select @error('subcategory_id') is-invalid @enderror">
            <option value="">-- Select Subcategory --</option>
            @foreach($subcategories as $sub)
              <option value="{{ $sub->id }}"
                      data-category-id="{{ $sub->category_id }}"
                      {{ (string) old('subcategory_id', $sound_effect->subcategory_id) === (string) $sub->id ? 'selected' : '' }}>
                {{ $sub->name }}
              </option>
            @endforeach
          </select>
          @error('subcategory_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- License --}}
        <div class="mb-3">
          <label for="license_type_id" class="form-label">License</label>
          <select id="license_type_id"
                  name="license_type_id"
                  class="form-select @error('license_type_id') is-invalid @enderror">
            <option value="">-- Select License --</option>
            @foreach($licenses as $license)
              <option value="{{ $license->id }}"
                      {{ (string) old('license_type_id', $sound_effect->license_type_id) === (string) $license->id ? 'selected' : '' }}>
                {{ $license->name }}
              </option>
            @endforeach
          </select>
          @error('license_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Tags --}}
        <div class="mb-3">
          <label for="tags" class="form-label">Tags</label>
          <select id="tags"
                  name="tags[]"
                  class="form-select @error('tags') is-invalid @enderror @error('tags.*') is-invalid @enderror"
                  multiple>
            @foreach($tags as $tag)
              <option value="{{ $tag->id }}"
                      {{ in_array($tag->id, $selectedTags) ? 'selected' : '' }}>
                {{ $tag->name }}
              </option>
            @endforeach
          </select>
          @error('tags') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          @error('tags.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        {{-- Status --}}
        <div class="mb-3">
          <label for="is_active" class="form-label">Status</label>
          <select id="is_active" name="is_active" class="form-select">
            <option value="1" {{ old('is_active', $sound_effect->is_active) ? 'selected' : '' }}>Active</option>
            <option value="0" {{ !old('is_active', $sound_effect->is_active) ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>

        {{-- Slug (readonly) --}}
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const categorySelect    = document.getElementById('category_id');
    const subcategorySelect = document.getElementById('subcategory_id');

    if (!categorySelect || !subcategorySelect) return;

    const filterSubcategories = () => {
        const selectedCategoryId = categorySelect.value;
        const options            = subcategorySelect.querySelectorAll('option[data-category-id]');

        options.forEach(option => {
            const optionCategoryId = option.getAttribute('data-category-id');

            if (!selectedCategoryId || optionCategoryId === selectedCategoryId) {
                option.hidden = false;
            } else {
                option.hidden = true;
                if (option.selected) {
                    option.selected = false;
                }
            }
        });
    };

    // Filter on load (handle old() / existing value)
    filterSubcategories();

    categorySelect.addEventListener('change', filterSubcategories);
});
</script>
@endpush
