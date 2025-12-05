@extends('layouts.master')

@section('title', 'Add Sound Effect – BeatHive')
@section('heading', 'Add Sound Effect')

@section('content')
@php
    /**
     * @var \Illuminate\Support\Collection|\App\Models\SoundCategory[]    $categories
     * @var \Illuminate\Support\Collection|\App\Models\SoundSubcategory[] $subcategories
     * @var \Illuminate\Support\Collection|\App\Models\SoundTag[]         $tags
     */

    $oldTags = old('tags', []);
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

      {{-- Notifikasi sukses --}}
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <form method="POST"
            action="{{ route('sound_effects.store') }}"
            enctype="multipart/form-data">
        @csrf

        {{-- Title --}}
        <div class="mb-3">
          <label for="title" class="form-label">Title *</label>
          <input type="text"
                 id="title"
                 name="title"
                 class="form-control @error('title') is-invalid @enderror"
                 value="{{ old('title') }}"
                 required>
          @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Sound file --}}
        <div class="mb-3">
          <label for="file" class="form-label">Sound File *</label>
          <input type="file"
                 id="file"
                 name="file"
                 class="form-control @error('file') is-invalid @enderror"
                 accept=".wav,.mp3,.ogg,.flac,.aiff"
                 required>
          @error('file')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <div class="form-text text-muted mt-1">
            Allowed formats: WAV, MP3, OGG, FLAC, AIFF. Max size: 50 MB.
          </div>
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
                      {{ (string) old('category_id') === (string) $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
          @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Subcategory (filtered by Category) --}}
        <div class="mb-3">
          <label for="subcategory_id" class="form-label">Subcategory</label>
          <select id="subcategory_id"
                  name="subcategory_id"
                  class="form-select @error('subcategory_id') is-invalid @enderror">
            <option value="">-- Select Subcategory --</option>
            @foreach($subcategories as $sub)
              <option value="{{ $sub->id }}"
                      data-category-id="{{ $sub->category_id }}"
                      {{ (string) old('subcategory_id') === (string) $sub->id ? 'selected' : '' }}>
                {{ $sub->name }}
              </option>
            @endforeach
          </select>
          @error('subcategory_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <div class="form-text text-muted mt-1">
            Subcategory list will follow the selected category.
          </div>
        </div>

        {{-- Tags (multi-select) --}}
        <div class="mb-3">
          <label for="tags" class="form-label">Tags</label>
          <select id="tags"
                  name="tags[]"
                  class="form-select @error('tags') is-invalid @enderror @error('tags.*') is-invalid @enderror"
                  multiple>
            @foreach($tags as $tag)
              <option value="{{ $tag->id }}"
                      {{ in_array($tag->id, $oldTags) ? 'selected' : '' }}>
                {{ $tag->name }}
              </option>
            @endforeach
          </select>
          @error('tags')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
          @error('tags.*')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
          <div class="form-text text-muted mt-1">
            You can select more than one tag (Ctrl/⌘ + click).
          </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">Upload</button>
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

    // Filter on load (untuk handle old() saat ada error)
    filterSubcategories();

    // Filter ketika category berubah
    categorySelect.addEventListener('change', filterSubcategories);
});
</script>
@endpush
