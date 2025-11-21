@extends('layouts.master')

@section('title', 'Add Category – BeatHive')
@section('heading', 'Add Category')

@section('content')
<div class="page-content">
  <div class="row g-4">
    {{-- Form --}}
    <div class="col-12 col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h5 class="card-title mb-3">New Sound Category</h5>
          <p class="text-muted small mb-4">
            Set a name, color, and optional icon. This will be used on the Sound Effects browse page.
          </p>

          <form method="POST"
                action="{{ route('sound_categories.store') }}"
                enctype="multipart/form-data">
            @csrf

            {{-- Name --}}
            <div class="mb-3">
              <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
              <input type="text"
                     id="name"
                     name="name"
                     class="form-control @error('name') is-invalid @enderror"
                     value="{{ old('name') }}"
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
                       value="{{ old('bg_color', '#ff9900') }}"
                       title="Choose category color">
                <input type="text"
                       id="bg_color_hex"
                       class="form-control form-control-sm"
                       value="{{ old('bg_color', '#ff9900') }}">
              </div>
              @error('bg_color')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
              <small class="text-muted">
                This color will be used as the tile background on the Sound Effects page.
              </small>
            </div>

            {{-- Icon upload --}}
            <div class="mb-3">
              <label for="icon" class="form-label">Icon (optional)</label>
              <input type="file"
                     id="icon"
                     name="icon"
                     class="form-control @error('icon') is-invalid @enderror"
                     accept=".png,.jpg,.jpeg,.webp,.svg">
              @error('icon')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <small class="text-muted d-block mt-1">
                PNG/JPG/WEBP/SVG, max 2MB. For best results use a square image with transparent background.
              </small>
            </div>

            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Save
              </button>
              <a href="{{ route('sound_categories.index') }}" class="btn btn-light">
                Cancel
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- Preview tile --}}
    <div class="col-12 col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header">
          <h6 class="mb-0">Preview</h6>
        </div>
        <div class="card-body">
          <div id="catPreview"
               style="
                 width:96px;
                 height:96px;
                 border-radius:14px;
                 background:#ff9900;
                 display:flex;
                 align-items:center;
                 justify-content:center;
                 overflow:hidden;
               ">
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
            <img id="catPreviewIcon"
                 src=""
                 alt="Icon preview"
                 style="width:56px;height:56px;object-fit:contain;display:none;">
          </div>

          <div class="mt-3">
            <div class="fw-semibold" id="catPreviewName">
              New Category
            </div>
            <div class="small text-muted">
              0 sounds
            </div>
          </div>

          <p class="small text-muted mt-2 mb-0">
            This is how the category will look on the Sound Effects page.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const nameInput   = document.getElementById('name');
  const colorInput  = document.getElementById('bg_color');
  const colorHex    = document.getElementById('bg_color_hex');
  const iconInput   = document.getElementById('icon');

  const previewBox  = document.getElementById('catPreview');
  const previewName = document.getElementById('catPreviewName');
  const previewIcon = document.getElementById('catPreviewIcon');
  const waveEl      = document.getElementById('catPreviewWave');

  // Nama → label di bawah tile
  if (nameInput && previewName) {
    nameInput.addEventListener('input', () => {
      previewName.textContent = nameInput.value || 'New Category';
    });
  }

  // Sinkron warna (picker ↔ text) + ubah background preview
  if (colorInput && colorHex && previewBox) {
    colorInput.addEventListener('input', () => {
      colorHex.value = colorInput.value;
      previewBox.style.background = colorInput.value;
    });

    colorHex.addEventListener('input', () => {
      const val = colorHex.value;
      if (!val) return;
      colorInput.value = val;
      previewBox.style.background = val;
    });
  }

  // Live preview icon
  if (iconInput && previewBox) {
    iconInput.addEventListener('change', () => {
      const file = iconInput.files && iconInput.files[0];
      if (!file) {
        // reset ke wave placeholder
        previewIcon.style.display = 'none';
        if (waveEl) waveEl.style.display = 'flex';
        return;
      }

      const reader = new FileReader();
      reader.onload = (e) => {
        previewIcon.src = e.target.result;
        previewIcon.style.display = 'block';
        if (waveEl) waveEl.style.display = 'none';
      };
      reader.readAsDataURL(file);
    });
  }
});
</script>
@endpush
