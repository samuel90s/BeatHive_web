{{-- resources/views/master.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title','BeatHive')</title>

  {{-- Favicon --}}
  <link rel="shortcut icon" href="{{ asset('assets/compiled/svg/favicon.svg') }}" type="image/svg+xml" />

  {{-- ===== CSS GLOBAL (WAJIB) ===== --}}
  <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}" />
  {{-- (opsional) Bootstrap Icons jika dipakai di view --}}
  <link rel="stylesheet" href="{{ asset('assets/extensions/bootstrap-icons/font/bootstrap-icons.min.css') }}"/>

  {{-- ===== Slot CSS per-halaman (index mendorong DataTables di sini) ===== --}}
  @stack('styles')
</head>

<body>
  <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
  <div id="app">
    <div id="main" class="layout-horizontal">

      {{-- =================== HEADER =================== --}}
      <header class="mb-5">
        <div class="header-top">
          <div class="container">
            <div class="logo">
              <a href="{{ url('/') }}">
                <img src="{{ asset('assets/compiled/svg/logo.svg') }}" alt="BeatHive Logo" />
              </a>
            </div>

            <div class="header-top-right d-flex align-items-center">
              {{-- Toggle Dark/Light --}}
              <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                     class="bi bi-brightness-high" viewBox="0 0 16 16" style="display:none">
                  <path d="M12 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0z"/>
                  <path d="M8 0a.5.5 0 0 1 .5.5V2a.5.5 0 0 1-1 0V.5A.5.5 0 0 1 8 0zM8 14a.5.5 0 0 1 .5.5V16a.5.5 0 0 1-1 0v-1.5a.5.5 0 0 1 .5-.5zM16 8a.5.5 0 0 1-.5.5H14a.5.5 0 0 1 0-1h1.5a.5.5 0 0 1 .5.5zM2 8a.5.5 0 0 1-.5.5H0a.5.5 0 0 1 0-1h1.5a.5.5 0 0 1 .5.5z"/>
                  <path d="M13.657 2.343a.5.5 0 0 1 0 .707L12.5 4.207a.5.5 0 1 1-.707-.707l1.157-1.157a.5.5 0 0 1 .707 0zm-9.9 9.9a.5.5 0 0 1 0 .707L2.5 14.207a.5.5 0 0 1-.707-.707l1.157-1.157a.5.5 0 0 1 .707 0z"/>
                </svg>
                <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                     class="bi bi-moon" viewBox="0 0 16 16">
                  <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.278 7.278 7.278 1.248 0 2.427-.314 3.46-.878a.768.768 0 0 1 .858.08.75.75 0 0 1 .146.99A8.001 8.001 0 1 1 6 .278z"/>
                </svg>
                <div class="form-check form-switch fs-6">
                  <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor:pointer" />
                  <label class="form-check-label" for="toggle-dark"></label>
                </div>
              </div>

              {{-- Auth buttons (dummy) --}}
              <div class="d-none d-xl-flex align-items-center gap-2 ms-3">
                <a href="#" class="btn btn-outline-primary btn-sm">Login</a>
                <a href="#" class="btn btn-primary btn-sm">Register</a>
              </div>

              <a href="#" class="burger-btn d-block d-xl-none"><i class="bi bi-justify fs-3"></i></a>
            </div>
          </div>
        </div>

        {{-- NAVBAR utama --}}
        <nav class="main-navbar">
          <div class="container">
            <ul>
              <li class="menu-item">
                <a href="{{ url('/') }}" class="menu-link">
                  <span><i class="bi bi-grid-fill"></i> Dashboard</span>
                </a>
              </li>

              <li class="menu-item has-sub">
                <a href="#" class="menu-link"><span><i class="bi bi-music-note-list"></i> Catalog</span></a>
                <div class="submenu">
                  <div class="submenu-group-wrapper">
                    <ul class="submenu-group">
                      <li class="submenu-item"><a href="{{ route('tracks.index') }}" class="submenu-link">All Tracks</a></li>
                      <li class="submenu-item"><a href="#" class="submenu-link">Albums / Packs</a></li>
                      <li class="submenu-item"><a href="#" class="submenu-link">Genres & Tags</a></li>
                    </ul>
                  </div>
                </div>
              </li>

              <li class="menu-item has-sub">
                <a href="#" class="menu-link"><span><i class="bi bi-cloud-upload-fill"></i> Upload</span></a>
                <div class="submenu">
                  <div class="submenu-group-wrapper">
                    <ul class="submenu-group">
                      <li class="submenu-item"><a href="{{ route('tracks.create') }}" class="submenu-link">Upload Track</a></li>
                      <li class="submenu-item"><a href="#" class="submenu-link">Bulk Import</a></li>
                    </ul>
                  </div>
                </div>
              </li>

              <li class="menu-item has-sub">
                <a href="#" class="menu-link"><span><i class="bi bi-bag-check-fill"></i> Sales</span></a>
                <div class="submenu">
                  <div class="submenu-group-wrapper">
                    <ul class="submenu-group">
                      <li class="submenu-item"><a href="#" class="submenu-link">Orders</a></li>
                      <li class="submenu-item"><a href="#" class="submenu-link">Licenses</a></li>
                      <li class="submenu-item"><a href="#" class="submenu-link">Payouts</a></li>
                    </ul>
                  </div>
                </div>
              </li>

              <li class="menu-item">
                <a href="#" class="menu-link"><span><i class="bi bi-graph-up"></i> Analytics</span></a>
              </li>

              <li class="menu-item has-sub">
                <a href="#" class="menu-link"><span><i class="bi bi-life-preserver"></i> Support</span></a>
                <div class="submenu">
                  <div class="submenu-group-wrapper">
                    <ul class="submenu-group">
                      <li class="submenu-item"><a href="#" class="submenu-link">Tickets</a></li>
                      <li class="submenu-item"><a href="#" class="submenu-link">Reviews</a></li>
                      <li class="submenu-item"><a href="#" class="submenu-link">FAQ</a></li>
                    </ul>
                  </div>
                </div>
              </li>

              {{-- Login/Register nav (dummy) --}}
              <li class="menu-item">
                <a href="#" class="menu-link"><span><i class="bi bi-box-arrow-in-right"></i> Login</span></a>
              </li>
              <li class="menu-item">
                <a href="#" class="menu-link"><span><i class="bi bi-person-plus"></i> Register</span></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>

      {{-- =================== PAGE CONTENT =================== --}}
      @yield('content')
      @section('content')
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

  {{-- Error summary (opsional) --}}
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
            <form class="form form-vertical" method="POST"
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
                        @foreach($genres ?? [] as $g)
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

                {{-- Files (match controller: cover_path / preview_path / bundle_path) --}}
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
  </section>
</div>


      {{-- =================== FOOTER =================== --}}
      <footer>
        <div class="container">
          <div class="footer clearfix mb-0 text-muted">
            <div class="float-start">
              <p>BeatHive &copy; {{ date('Y') }}</p>
            </div>
            <div class="float-end">
              <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a href="#">TRPL-516</a></p>
            </div>
          </div>
        </div>
      </footer>

    </div>
  </div>
  
  {{-- =================== JS GLOBAL =================== --}}
  <script>
    // toggle dark/light kecil
    (function() {
      const toggle = document.getElementById('toggle-dark');
      const sun = document.getElementById('icon-sun');
      const moon = document.getElementById('icon-moon');
      if (!toggle || !sun || !moon) return;
      const apply = () => {
        const dark = document.documentElement.classList.contains('dark');
        sun.style.display = dark ? 'block' : 'none';
        moon.style.display = dark ? 'none' : 'block';
        toggle.checked = dark;
      };
      apply();
      toggle.addEventListener('change', () => {
        document.documentElement.classList.toggle('dark');
        apply();
      });
    })();
  </script>

  <script src="{{ asset('assets/static/js/pages/horizontal-layout.js') }}"></script>
  <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/compiled/js/app.js') }}"></script>

  {{-- (opsional) Charts lib jika dipakai di halaman tertentu --}}
  {{-- <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script> --}}

  {{-- ===== Slot JS per-halaman (index: DataTables, create: custom js) ===== --}}
  @stack('scripts')
</body>
</html>
