<header class="mb-5">
  <div class="header-top">
    <div class="container">
      <div class="logo">
        <a href="{{ route('home') }}">
          <img src="{{ asset('assets/compiled/svg/logo.svg') }}" alt="BeatHive Logo" />
        </a>
      </div>

      <div class="header-top-right">
        {{-- Dark/Light toggle --}}
        <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
          <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="20" height="20"
               viewBox="0 0 21 21" class="iconify iconify--system-uicons">
            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
              <path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path>
              <g transform="translate(-210 -1)">
                <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                <circle cx="220.5" cy="11.5" r="4"></circle>
                <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
              </g>
            </g>
          </svg>
          <div class="form-check form-switch fs-6">
            <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer" />
            <label class="form-check-label" for="toggle-dark" aria-label="Toggle dark mode"></label>
          </div>
        </div>

        @guest
          {{-- (Top-right) tombol Login/Register tetap, bukan di navbar --}}
          <div class="d-none d-xl-flex align-items-center gap-2 ms-3">
            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
          </div>
        @else
          {{-- SUDAH LOGIN --}}
          <div class="dropdown ms-3">
            <a href="#" id="topbarUserDropdown"
               class="user-dropdown d-flex align-items-center dropend dropdown-toggle"
               data-bs-toggle="dropdown" aria-expanded="false">
              <div class="avatar avatar-md2">
                <img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="Avatar" />
              </div>
              <div class="text">
                <h6 class="user-dropdown-name">{{ Auth::user()->name ?? 'User' }}</h6>
                <p class="user-dropdown-status text-sm text-muted">Stock Music</p>
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="topbarUserDropdown">
              <li><a class="dropdown-item" href="{{ Route::has('account') ? route('account') : '#' }}">My Account</a></li>
              <li><a class="dropdown-item" href="{{ Route::has('settings') ? route('settings') : '#' }}">Settings</a></li>
              <li><hr class="dropdown-divider" /></li>
              <li>
                <form action="{{ Route::has('logout') ? route('logout') : '#' }}" method="POST">
                  @csrf
                  <button type="submit" class="dropdown-item">Logout</button>
                </form>
              </li>
            </ul>
          </div>
        @endguest

        {{-- Burger button responsive --}}
        <a href="#" class="burger-btn d-block d-xl-none"><i class="bi bi-justify fs-3"></i></a>
      </div>
    </div>
  </div>

  {{-- Main navbar --}}
  <nav class="main-navbar">
    <div class="container">
      <ul>
        {{-- Dashboard --}}
        <li class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}">
          <a href="{{ route('home') }}" class="menu-link">
            <span><i class="bi bi-grid-fill"></i> Dashboard</span>
          </a>
        </li>

        {{-- Catalog --}}
        <li class="menu-item has-sub {{ request()->routeIs('tracks.*') ? 'active' : '' }}">
          <a href="#" class="menu-link"><span><i class="bi bi-music-note-list"></i> Catalog</span></a>
          <div class="submenu">
            <div class="submenu-group-wrapper">
              <ul class="submenu-group">
                <li class="submenu-item"><a href="{{ route('tracks.index') }}" class="submenu-link">All Tracks</a></li>
                <li class="submenu-item"><a href="{{ Route::has('albums.index') ? route('albums.index') : '#' }}" class="submenu-link">Albums / Packs</a></li>
                <li class="submenu-item"><a href="{{ Route::has('genres.index') ? route('genres.index') : '#' }}" class="submenu-link">Genres & Tags</a></li>
              </ul>
            </div>
          </div>
        </li>

        {{-- Upload --}}
        <li class="menu-item has-sub {{ request()->routeIs('tracks.create') || request()->routeIs('tracks.bulk.*') ? 'active' : '' }}">
          <a href="#" class="menu-link"><span><i class="bi bi-cloud-upload-fill"></i> Upload</span></a>
          <div class="submenu">
            <div class="submenu-group-wrapper">
              <ul class="submenu-group">
                <li class="submenu-item"><a href="{{ route('tracks.create') }}" class="submenu-link">Upload Track</a></li>
                <li class="submenu-item"><a href="{{ route('tracks.bulk.import') }}" class="submenu-link">Bulk Import</a></li>
              </ul>
            </div>
          </div>
        </li>

        {{-- Sales --}}
        <li class="menu-item has-sub">
          <a href="#" class="menu-link"><span><i class="bi bi-bag-check-fill"></i> Sales</span></a>
          <div class="submenu">
            <div class="submenu-group-wrapper">
              <ul class="submenu-group">
                <li class="submenu-item"><a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}" class="submenu-link">Orders</a></li>
                <li class="submenu-item"><a href="{{ Route::has('licenses.index') ? route('licenses.index') : '#' }}" class="submenu-link">Licenses</a></li>
                <li class="submenu-item"><a href="{{ Route::has('payouts.index') ? route('payouts.index') : '#' }}" class="submenu-link">Payouts</a></li>
              </ul>
            </div>
          </div>
        </li>

        {{-- Analytics --}}
        <li class="menu-item {{ request()->routeIs('analytics') ? 'active' : '' }}">
          <a href="{{ Route::has('analytics') ? route('analytics') : '#' }}" class="menu-link">
            <span><i class="bi bi-graph-up"></i> Analytics</span>
          </a>
        </li>

        {{-- Support --}}
        <li class="menu-item has-sub">
          <a href="#" class="menu-link"><span><i class="bi bi-life-preserver"></i> Support</span></a>
          <div class="submenu">
            <div class="submenu-group-wrapper">
              <ul class="submenu-group">
                <li class="submenu-item"><a href="{{ Route::has('tickets.index') ? route('tickets.index') : '#' }}" class="submenu-link">Tickets</a></li>
                <li class="submenu-item"><a href="{{ Route::has('reviews.index') ? route('reviews.index') : '#' }}" class="submenu-link">Reviews</a></li>
                <li class="submenu-item"><a href="{{ Route::has('faq') ? route('faq') : '#' }}" class="submenu-link">FAQ</a></li>
              </ul>
            </div>
          </div>
        </li>

        {{-- (Dihapus) Login / Register di navbar --}}
      </ul>
    </div>
  </nav>
</header>
