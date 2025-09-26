{{-- ====== [PARTIAL: NAVBAR] ====== --}}
<nav class="main-navbar">
  <div class="container">
    <ul>
      <li class="menu-item">
        <a href="{{ route('home') }}" class="menu-link">
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
              <li class="submenu-item"><a href="{{ route('tracks.bulk-import') }}" class="submenu-link">Bulk Import</a></li>
            </ul>
          </div>
        </div>
      </li>
      <li class="menu-item"><a href="#" class="menu-link"><span><i class="bi bi-graph-up"></i> Analytics</span></a></li>
      <li class="menu-item has-sub">
        <a href="#" class="menu-link"><span><i class="bi bi-life-preserver"></i> Support</span></a>
        <div class="submenu"> ... </div>
      </li>
      @guest
        <li class="menu-item"><a href="{{ route('login') }}" class="menu-link"><span><i class="bi bi-box-arrow-in-right"></i> Login</span></a></li>
        <li class="menu-item"><a href="{{ route('register') }}" class="menu-link"><span><i class="bi bi-person-plus"></i> Register</span></a></li>
      @endguest
    </ul>
  </div>
</nav>
