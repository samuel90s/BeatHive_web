{{-- resources/views/tracks/create.blade.php --}}
@extends('layouts.master')

@section('title', 'Tracks – Create | BeatHive')

@section('content')

 @if(auth()->check() && auth()->user()->role == 1)
<div class="content-wrapper container">
  <div class="page-heading d-flex justify-content-between align-items-center">
    <div>
      <h3>Create Track</h3>
      <p class="text-muted">Masukkan metadata, file audio, dan cover artwork.</p>
    </div>
    <a href="{{ route('tracks.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left"></i> Back
    </a>
  </div>

  {{-- Error summary --}}
  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Periksa lagi isianmu:</strong>
      <ul class="mb-0 mt-1">
        @foreach ($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <section id="basic-vertical-layouts">
    <div class="row match-height">
      <div class="col-md-12 col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title mb-0">Create New Track</h4>
          </div>

          <div class="card-body">
            <form class="form form-vertical"
                  method="POST"
                  action="{{ route('tracks.store') }}"
                  enctype="multipart/form-data">
              @csrf

              <div class="row">
                {{-- Title --}}
                <div class="col-12 mb-3">
                  <div class="form-group has-icon-left">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <div class="position-relative">
                      <input type="text" id="title" name="title"
                             class="form-control @error('title') is-invalid @enderror"
                             placeholder="e.g. Sunset Drive" value="{{ old('title') }}">
                      <div class="form-control-icon"><i class="bi bi-music-note-beamed"></i></div>
                      @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                  </div>
                </div>

                {{-- Artist --}}
                <div class="col-12 mb-3">
                  <div class="form-group has-icon-left">
                    <label for="artist">Artist <span class="text-danger">*</span></label>
                    <div class="position-relative">
                      <input type="text" id="artist" name="artist"
                             class="form-control @error('artist') is-invalid @enderror"
                             placeholder="e.g. BeatHive Studio" value="{{ old('artist') }}">
                      <div class="form-control-icon"><i class="bi bi-person"></i></div>
                      @error('artist')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                  </div>
                </div>

                {{-- Genre --}}
                <div class="col-12 mb-3">
                  <div class="form-group has-icon-left">
                    <label for="genre_id">Genre <span class="text-danger">*</span></label>
                    <div class="position-relative">
                      <select id="genre_id" name="genre_id"
                              class="form-control @error('genre_id') is-invalid @enderror">
                        <option value="">-- Select Genre --</option>
                        @foreach(($genres ?? []) as $g)
                          <option value="{{ $g->id }}" @selected(old('genre_id') == $g->id)>{{ $g->name }}</option>
                        @endforeach
                      </select>
                      <div class="form-control-icon"><i class="bi bi-tags"></i></div>
                      @error('genre_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                  </div>
                </div>
                {{-- BPM / Key --}}
                <div class="col-md-6 col-12 mb-3">
                  <div class="form-group has-icon-left">
                    <label for="bpm">BPM</label>
                    <div class="position-relative">
                      <input type="number" id="bpm" name="bpm" min="40" max="220"
                             class="form-control @error('bpm') is-invalid @enderror"
                             placeholder="e.g. 100" value="{{ old('bpm') }}">
                      <div class="form-control-icon"><i class="bi bi-speedometer2"></i></div>
                      @error('bpm')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-12 mb-3">
                  <div class="form-group has-icon-left">
                    <label for="musical_key">Key</label>
                    <div class="position-relative">
                      <input type="text" id="musical_key" name="musical_key"
                             class="form-control @error('musical_key') is-invalid @enderror"
                             placeholder="e.g. Am, C#" value="{{ old('musical_key') }}">
                      <div class="form-control-icon"><i class="bi bi-music-note"></i></div>
                      @error('musical_key')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                  </div>
                </div>

                {{-- Mood / Tags --}}
                <div class="col-md-6 col-12 mb-3">
                  <div class="form-group has-icon-left">
                    <label for="mood">Mood</label>
                    <div class="position-relative">
                      <input type="text" id="mood" name="mood"
                             class="form-control @error('mood') is-invalid @enderror"
                             placeholder="e.g. Uplifting, Chill" value="{{ old('mood') }}">
                      <div class="form-control-icon"><i class="bi bi-emoji-smile"></i></div>
                      @error('mood')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-12 mb-3">
                  <div class="form-group has-icon-left">
                    <label for="tags">Tags (comma separated)</label>
                    <div class="position-relative">
                      <input type="text" id="tags" name="tags"
                             class="form-control @error('tags') is-invalid @enderror"
                             placeholder="cinematic, epic, strings" value="{{ old('tags') }}">
                      <div class="form-control-icon"><i class="bi bi-hash"></i></div>
                      @error('tags')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                  </div>
                </div>

                {{-- Description --}}
                <div class="col-12 mb-3">
                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Brief description of the track...">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                </div>

                {{-- Price / Duration --}}
                <div class="col-md-6 col-12 mb-3">
                  <div class="form-group has-icon-left">
                    <label for="price_idr">Price (IDR)</label>
                    <div class="position-relative">
                      <input type="number" id="price_idr" name="price_idr" min="0" step="1000"
                             class="form-control @error('price_idr') is-invalid @enderror"
                             placeholder="e.g. 150000" value="{{ old('price_idr') }}">
                      <div class="form-control-icon"><i class="bi bi-currency-dollar"></i></div>
                      @error('price_idr')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-12 mb-3">
                  <div class="form-group has-icon-left">
                    <label for="duration_seconds">Duration (seconds)</label>
                    <div class="position-relative">
                      <input type="number" id="duration_seconds" name="duration_seconds" min="0"
                             class="form-control @error('duration_seconds') is-invalid @enderror"
                             placeholder="e.g. 152" value="{{ old('duration_seconds') }}">
                      <div class="form-control-icon"><i class="bi bi-stopwatch"></i></div>
                      @error('duration_seconds')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                  </div>
                </div>

                {{-- Release Date / Publish --}}
                <div class="col-md-6 col-12 mb-3">
                  <div class="form-group has-icon-left">
                    <label for="release_date">Release Date</label>
                    <div class="position-relative">
                      <input type="date" id="release_date" name="release_date"
                             class="form-control @error('release_date') is-invalid @enderror"
                             value="{{ old('release_date') }}">
                      <div class="form-control-icon"><i class="bi bi-calendar-date"></i></div>
                      @error('release_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-12 mb-3">
                  <div class="form-group mt-4">
                    <div class="form-check">
                      <input type="checkbox" id="is_published" name="is_published" class="form-check-input"
                             {{ old('is_published') ? 'checked' : '' }}>
                      <label for="is_published">Publish immediately</label>
                    </div>
                    @error('is_published')<div class="text-danger small">{{ $message }}</div>@enderror
                  </div>
                </div>

                {{-- Files --}}
                <div class="col-md-4 col-12 mb-3">
                  <div class="form-group">
                    <label for="cover_path">Cover Image (JPG/PNG)</label>
                    <input type="file" id="cover_path" name="cover_path"
                           class="form-control @error('cover_path') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                    @error('cover_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted d-block mt-1" id="cover_info"></small>
                  </div>
                </div>
                <div class="col-md-4 col-12 mb-3">
                  <div class="form-group">
                    <label for="preview_path">Audio Preview (MP3)</label>
                    <input type="file" id="preview_path" name="preview_path"
                           class="form-control @error('preview_path') is-invalid @enderror" accept=".mp3">
                    @error('preview_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted d-block mt-1" id="preview_info"></small>
                  </div>
                </div>
                <div class="col-md-4 col-12 mb-3">
                  <div class="form-group">
                    <label for="bundle_path">Audio File / Bundle (WAV/ZIP)</label>
                    <input type="file" id="bundle_path" name="bundle_path"
                           class="form-control @error('bundle_path') is-invalid @enderror" accept=".wav,.zip">
                    @error('bundle_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted d-block mt-1" id="bundle_info"></small>
                  </div>
                </div>

                {{-- Actions --}}
                <div class="col-12 d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary me-1 mb-1">
                    <i class="bi bi-save"></i> Save
                  </button>
                  <a href="{{ route('tracks.index') }}" class="btn btn-light-secondary me-1 mb-1">Cancel</a>
                </div>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
    @else
    <div class="alert alert-danger mt-5">
      <h5>Akses ditolak</h5>
      <p>Halaman ini hanya bisa diakses oleh <strong>Admin</strong>.</p>
      <a href="{{ route('tracks.index') }}" class="btn btn-outline-primary mt-2">Kembali ke Tracks</a>
    </div>
  @endif
  </section>
</div>
@endsection

@push('scripts')
<script>
  // Info file kecil: nama + size
  const human = (bytes) => {
    if (!bytes) return '';
    const units = ['B','KB','MB','GB']; let i=0;
    while (bytes >= 1024 && i < units.length-1) { bytes /= 1024; i++; }
    return bytes.toFixed(1)+' '+units[i];
  };

  const bindInfo = (inputId, infoId) => {
    const i = document.getElementById(inputId);
    const s = document.getElementById(infoId);
    if (!i || !s) return;
    i.addEventListener('change', () => {
      const f = i.files?.[0];
      s.textContent = f ? `${f.name} • ${human(f.size)}` : '';
    });
  };
  bindInfo('cover_path','cover_info');
  bindInfo('preview_path','preview_info');
  bindInfo('bundle_path','bundle_info');
</script>
@endpush
