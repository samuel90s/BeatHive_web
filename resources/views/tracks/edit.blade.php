{{-- resources/views/tracks/edit.blade.php --}}
@extends('layouts.master')

@section('title', 'Edit Track | BeatHive')
@section('heading', 'Edit Track')
@section('subheading', 'Perbarui metadata, file, dan status publikasi track.')

@section('content')
<section class="section">
  <div class="mb-3">
    <a href="{{ route('tracks.index') }}" class="btn btn-sm btn-light">
      <i class="bi bi-arrow-left"></i> Back to Tracks
    </a>
    <a href="{{ route('tracks.show', $track) }}" class="btn btn-sm btn-outline-secondary">
      <i class="bi bi-eye"></i> View
    </a>
    <form action="{{ route('tracks.publish', $track) }}" method="POST" class="d-inline">
      @csrf @method('PATCH')
      <button class="btn btn-sm {{ $track->is_published ? 'btn-warning' : 'btn-success' }}">
        <i class="bi {{ $track->is_published ? 'bi-eye-slash' : 'bi-cloud-upload' }}"></i>
        {{ $track->is_published ? 'Unpublish' : 'Publish' }}
      </button>
    </form>
  </div>

  <form action="{{ route('tracks.update', $track) }}" method="POST" enctype="multipart/form-data" class="card">
    @csrf
    @method('PUT')

    <div class="card-header">
      <h5 class="card-title mb-0">Edit: {{ $track->title }}</h5>
      <small class="text-muted">Slug: <code>{{ $track->slug }}</code></small>
    </div>

    <div class="card-body">
      <div class="row g-4">
        {{-- LEFT: Metadata --}}
        <div class="col-12 col-lg-8">
          {{-- Flash messages --}}
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif
          @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif

          {{-- Global errors --}}
          @if ($errors->any())
            <div class="alert alert-danger">
              <div class="fw-semibold mb-1">Please fix the following:</div>
              <ul class="mb-0">
                @foreach ($errors->all() as $err)
                  <li>{{ $err }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label">Title <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                     value="{{ old('title', $track->title) }}" maxlength="255" required>
              @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
              <label class="form-label">Artist <span class="text-danger">*</span></label>
              <input type="text" name="artist" class="form-control @error('artist') is-invalid @enderror"
                     value="{{ old('artist', $track->artist) }}" maxlength="255" required>
              @error('artist') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">Genre <span class="text-danger">*</span></label>
              <select name="genre_id" class="form-select @error('genre_id') is-invalid @enderror" required>
                <option value="" disabled {{ old('genre_id', $track->genre_id) ? '' : 'selected' }}>-- Pilih genre --</option>
                @foreach($genres as $g)
                  <option value="{{ $g->id }}" {{ (int)old('genre_id', $track->genre_id) === $g->id ? 'selected' : '' }}>
                    {{ $g->name }}
                  </option>
                @endforeach
              </select>
              @error('genre_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
              <label class="form-label">BPM</label>
              <input type="number" name="bpm" min="40" max="220"
                     class="form-control @error('bpm') is-invalid @enderror"
                     value="{{ old('bpm', $track->bpm) }}">
              @error('bpm') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
              <label class="form-label">Key</label>
              <input type="text" name="musical_key" maxlength="8"
                     class="form-control @error('musical_key') is-invalid @enderror"
                     value="{{ old('musical_key', $track->musical_key) }}">
              @error('musical_key') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">Mood</label>
              <input type="text" name="mood" maxlength="100"
                     class="form-control @error('mood') is-invalid @enderror"
                     value="{{ old('mood', $track->mood) }}">
              @error('mood') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">Tags (comma separated)</label>
              <input type="text" name="tags" maxlength="255"
                     class="form-control @error('tags') is-invalid @enderror"
                     value="{{ old('tags', $track->tags) }}">
              @error('tags') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea name="description" rows="4"
                        class="form-control @error('description') is-invalid @enderror"
              >{{ old('description', $track->description) }}</textarea>
              @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
              <label class="form-label">Price (IDR)</label>
              <input type="number" name="price_idr" min="0"
                     class="form-control @error('price_idr') is-invalid @enderror"
                     value="{{ old('price_idr', $track->price_idr) }}">
              @error('price_idr') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
              <label class="form-label">Duration (seconds)</label>
              <input type="number" name="duration_seconds" min="0"
                     class="form-control @error('duration_seconds') is-invalid @enderror"
                     value="{{ old('duration_seconds', $track->duration_seconds) }}">
              @error('duration_seconds') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
              <label class="form-label">Release Date</label>
              <input type="date" name="release_date"
                     class="form-control @error('release_date') is-invalid @enderror"
                     value="{{ old('release_date', optional($track->release_date)->format('Y-m-d')) }}">
              @error('release_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="is_published" name="is_published"
                       {{ old('is_published', $track->is_published) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_published">Published</label>
              </div>
              @error('is_published') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        {{-- RIGHT: Files & Preview --}}
        <div class="col-12 col-lg-4">
          <div class="border rounded p-3 h-100">
            <h6 class="mb-3">Media Files</h6>

            {{-- Cover --}}
            <div class="mb-3">
              <label class="form-label">Cover (jpg/jpeg/png, max 2MB)</label>
              @php
                $cover = $track->cover_path ? asset('storage/'.$track->cover_path) : asset('assets/compiled/svg/favicon.svg');
              @endphp
              <div class="d-flex align-items-center gap-3">
                <img src="{{ $cover }}" alt="cover" class="rounded border"
                     style="width:88px;height:88px;object-fit:cover">
                <div class="flex-grow-1">
                  <input type="file" name="cover_path" accept=".jpg,.jpeg,.png"
                         class="form-control @error('cover_path') is-invalid @enderror">
                  @error('cover_path') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  @if($track->cover_path)
                    <small class="text-muted d-block mt-1">
                      Current: <code>storage/{{ $track->cover_path }}</code>
                    </small>
                  @endif
                </div>
              </div>
            </div>

            {{-- Preview MP3 --}}
            <div class="mb-3">
              <label class="form-label">Preview (mp3, max 10MB)</label>
              @if($track->preview_path)
                <audio controls preload="none" style="width:100%"
                       src="{{ asset('storage/'.$track->preview_path) }}"></audio>
                <small class="text-muted d-block mt-1">
                  Current: <code>storage/{{ $track->preview_path }}</code>
                </small>
              @endif
              <input type="file" name="preview_path" accept="audio/mpeg"
                     class="form-control mt-2 @error('preview_path') is-invalid @enderror">
              @error('preview_path') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Bundle WAV/ZIP --}}
            <div class="mb-3">
              <label class="form-label">Bundle (wav/zip, max 200MB)</label>
              @if($track->bundle_path)
                <div class="small text-muted mb-1">
                  Current: <code>storage/{{ $track->bundle_path }}</code>
                </div>
              @endif
              <input type="file" name="bundle_path" accept=".wav,.zip"
                     class="form-control @error('bundle_path') is-invalid @enderror">
              @error('bundle_path') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="alert alert-info small mb-0">
              Upload baru akan <b>mengganti</b> file lama pada kolom yang sama.
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
      <div>
        <span class="text-muted">Last updated: {{ $track->updated_at?->format('Y-m-d H:i') ?? '-' }}</span>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('tracks.index') }}" class="btn btn-light">Cancel</a>
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-save"></i> Save Changes
        </button>
      </div>
    </div>
  </form>
</section>
@endsection
