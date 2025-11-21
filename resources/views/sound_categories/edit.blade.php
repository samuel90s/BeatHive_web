{{-- resources/views/sound_categories/edit.blade.php --}}
@extends('layouts.master')

@section('title', 'Edit Category – BeatHive')
@section('heading', 'Edit Category')

@section('content')
@php
    /** @var \App\Models\SoundCategory $sound_category */
    $bg   = $sound_category->bg_color ?: '#FFBEB5';
    $icon = $sound_category->icon_path
        ? (str_starts_with($sound_category->icon_path, 'http')
            ? $sound_category->icon_path
            : asset($sound_category->icon_path))
        : null;
@endphp

<div class="page-content">
  <div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Edit Sound Category</h5>
      <a href="{{ route('sound_categories.index') }}" class="btn btn-sm btn-light">
        <i class="bi bi-arrow-left me-1"></i> Back
      </a>
    </div>

    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
      @endif

      <form method="POST"
            action="{{ route('sound_categories.update', $sound_category->id) }}"
            enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Preview tile ala Epidemic --}}
        <div class="mb-4">
          <label class="form-label text-muted">Preview</label>
          <div class="d-flex align-items-center gap-3">
            <div id="catPreviewBox"
                 style="
                    width:96px;
                    height:96px;
                    border-radius:14px;
                    background: {{ $bg }};
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    overflow:hidden;
                 ">
              @if($icon)
                <img id="catPreviewIcon"
                     src="{{ $icon }}"
                     alt="{{ $sound_category->name }}"
                     style="width:56px;height:56px;object-fit:contain;display:block;">
              @else
                <div id="catPreviewWave" style="
                      display:flex;
                      gap:4px;
                      width:56px;
                      height:56px;
                      align-items:flex-end;
                    ">
                  <span style="flex:1;height:30%;background:rgba(0,0,0,.25);border-radius:4px;"></span>
                  <span style="flex:1;height:70%;background:rgba(0,0,0,.25);border-radius:4px;"></span>
                  <span style="flex:1;height:50%;background:rgba(0,0,0,.25);border-radius:4px;"></span>
                  <span style="flex:1;height:65%;background:rgba(0,0,0,.25);border-radius:4px;"></span>
                </div>
              @endif
            </div>

            <div class="small text-muted">
              This preview is used on the Sound Effects landing page.
            </div>
          </div>
        </div>

        {{-- Name --}}
        <div class="mb-3">
          <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
          <input type="text"
                 id="name"
                 name="name"
                 class="form-control @error('name') is-invalid @enderror"
                 value="{{ old('name', $sound_category->name) }}"
                 required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Background color --}}
        <div class="mb-3">
          <label for="bg_color" class="form-label">Background Color</label>
          <div class="d-flex align-items-center gap-2">
            <input type="color"
                   id="bg_color"
                   name="bg_color"
                   class="form-control form-control-color @error('bg_color') is-invalid @enderror"
                   value="{{ old('bg_color', $sound_category->bg_color ?? '#ff9900') }}"
                   title="Choose category color">
            <input type="text"
                   class="form-control form-control-sm"
                   id="bg_color_text"
                   value="{{ old('bg_color', $sound_category->bg_color ?? '#ff9900') }}">
          </div>
          @error('bg_color')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
          <small class="text-muted">
            This color will be used as the tile background on the Sound Effects page.
          </small>
        </div>

        {{-- Slug (readonly) --}}
        <div class="mb-3">
          <label for="slug" class="form-label">Slug</label>
          <input type="text"
                 id="slug"
                 name="slug"
                 class="form-control"
                 value="{{ $sound_category->slug }}"
                 readonly>
        </div>

        {{-- Icon upload --}}
        <div class="mb-3">
          <label for="icon" class="form-label">Icon</label>
          <input type="file"
                 id="icon"
                 name="icon"
                 class="form-control @error('icon') is-invalid @enderror"
                 accept=".jpg,.jpeg,.png,.webp,.svg">
          @error('icon')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="text-muted d-block mt-1">
            JPG/PNG/WEBP/SVG, max 2MB. For best results use a square image with transparent background.
          </small>

          @if($icon)
            <div class="form-check mt-2">
              <input class="form-check-input"
                     type="checkbox"
                     id="remove_icon"
                     name="remove_icon"
                     value="1">
              <label class="form-check-label" for="remove_icon">
                Remove current icon
              </label>
            </div>
          @endif
        </div>

        <div class="d-flex gap-2 mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Update Category
          </button>
          <a href="{{ route('sound_categories.index') }}" class="btn btn-light">
            Cancel
          </a>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bgPicker   = document.getElementById('bg_color');
    const bgText     = document.getElementById('bg_color_text');
    const previewBox = document.getElementById('catPreviewBox');
    const iconInput  = document.getElementById('icon');
    const imgEl      = document.getElementById('catPreviewIcon');
    const waveEl     = document.getElementById('catPreviewWave');

    // sync color input <-> text input
    if (bgPicker && bgText && previewBox) {
        bgPicker.addEventListener('input', function () {
            bgText.value = this.value;
            previewBox.style.background = this.value;
        });
        bgText.addEventListener('input', function () {
            bgPicker.value = this.value;
            previewBox.style.background = this.value;
        });
    }

    // live preview icon
    if (iconInput && previewBox) {
        iconInput.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                // kalau sebelumnya wave placeholder, hide
                if (waveEl) waveEl.style.display = 'none';

                let img = imgEl;
                if (!img) {
                    img = document.createElement('img');
                    img.id = 'catPreviewIcon';
                    img.style.width = '56px';
                    img.style.height = '56px';
                    img.style.objectFit = 'contain';
                    img.style.display = 'block';
                    previewBox.appendChild(img);
                }
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }
});
</script>
@endpush
