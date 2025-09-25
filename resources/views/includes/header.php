<header class="mb-5">
    <div class="header-top">
        <div class="container">
            <div class="logo">
                <a href="index.php">
                    <img src="./assets/compiled/svg/logo.svg" alt="BeatHive Logo" />
                </a>
            </div>
            <div class="header-top-right">
                <!-- Dark / Light toggle -->
                <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                    <!-- Icon Sun -->
                    <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-brightness-high" viewBox="0 0 16 16" style="display: none;">
                        <path d="M12 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0z" />
                        <path d="M8 0a.5.5 0 0 1 .5.5V2a.5.5 0 0 1-1 0V.5A.5.5 0 0 1 8 0zm0 14a.5.5 0 0 1 .5.5V16a.5.5 0 0 1-1 0v-1.5a.5.5 0 0 1 .5-.5zM16 8a.5.5 0 0 1-.5.5H14a.5.5 0 0 1 0-1h1.5A.5.5 0 0 1 16 8zM2 8a.5.5 0 0 1-.5.5H0a.5.5 0 0 1 0-1h1.5A.5.5 0 0 1 2 8z" />
                        <path d="M13.657 2.343a.5.5 0 0 1 0 .707L12.5 4.207a.5.5 0 1 1-.707-.707l1.157-1.157a.5.5 0 0 1 .707 0zm-9.9 9.9a.5.5 0 0 1 0 .707L2.5 14.207a.5.5 0 0 1-.707-.707l1.157-1.157a.5.5 0 0 1 .707 0z" />
                    </svg>

                    <!-- Icon Moon -->
                    <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-moon" viewBox="0 0 16 16">
                        <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.278 7.278 7.278 1.248 0 2.427-.314 3.46-.878a.768.768 0 0 1 .858.08.75.75 0 0 1 .146.99A8.001 8.001 0 1 1 6 .278z" />
                    </svg>

                    <!-- Toggle -->
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer" />
                        <label class="form-check-label" for="toggle-dark"></label>
                    </div>
                </div>

                <script>
                    const toggle = document.getElementById('toggle-dark');
                    const sun = document.getElementById('icon-sun');
                    const moon = document.getElementById('icon-moon');

                    // cek preferensi awal
                    if (document.documentElement.classList.contains('dark')) {
                        toggle.checked = true;
                        sun.style.display = 'block';
                        moon.style.display = 'none';
                    } else {
                        toggle.checked = false;
                        sun.style.display = 'none';
                        moon.style.display = 'block';
                    }

                    // toggle event
                    toggle.addEventListener('change', () => {
                        document.documentElement.classList.toggle('dark');
                        if (document.documentElement.classList.contains('dark')) {
                            sun.style.display = 'block';
                            moon.style.display = 'none';
                        } else {
                            sun.style.display = 'none';
                            moon.style.display = 'block';
                        }
                    });
                </script>


                <!-- BELUM LOGIN -->
                <div class="d-none d-xl-flex align-items-center gap-2 ms-3">
                    <a href="auth/auth-login.php" class="btn btn-outline-primary btn-sm">Login</a>
                    <a href="auth/auth-register.php" class="btn btn-primary btn-sm">Register</a>
                </div>

                <!-- Burger button responsive -->
                <a href="#" class="burger-btn d-block d-xl-none"><i class="bi bi-justify fs-3"></i></a>
            </div>
        </div>
    </div>
    <!-- Main navbar -->
    <!-- navbar -->
    <nav class="main-navbar">
        <div class="container">
            <ul>
                <li class="menu-item">
                    <a href="index.php" class="menu-link"><span><i class="bi bi-grid-fill"></i>
                            Dashboard</span></a>
                </li>

                <!-- Catalog -->
                <li class="menu-item has-sub">
                    <a href="#" class="menu-link"><span><i class="bi bi-music-note-list"></i>
                            Catalog</span></a>
                    <div class="submenu">
                        <div class="submenu-group-wrapper">
                            <ul class="submenu-group">
                                <li class="submenu-item"><a href="tracks.php" class="submenu-link">All
                                        Tracks</a></li>
                                <li class="submenu-item"><a href="albums.php" class="submenu-link">Albums /
                                        Packs</a></li>
                                <li class="submenu-item"><a href="genres.php" class="submenu-link">Genres &
                                        Tags</a></li>
                            </ul>
                        </div>
                    </div>
                </li>

                <!-- Upload -->
                <li class="menu-item has-sub">
                    <a href="#" class="menu-link"><span><i class="bi bi-cloud-upload-fill"></i>
                            Upload</span></a>
                    <div class="submenu">
                        <div class="submenu-group-wrapper">
                            <ul class="submenu-group">
                                <li class="submenu-item"><a href="tracks"
                                        class="submenu-link">Upload Track</a></li>
                                <li class="submenu-item"><a href="bulk-import.php" class="submenu-link">Bulk
                                        Import</a></li>
                            </ul>
                        </div>
                    </div>
                </li>

                <!-- Sales -->
                <li class="menu-item has-sub">
                    <a href="#" class="menu-link"><span><i class="bi bi-bag-check-fill"></i>
                            Sales</span></a>
                    <div class="submenu">
                        <div class="submenu-group-wrapper">
                            <ul class="submenu-group">
                                <li class="submenu-item"><a href="orders.php"
                                        class="submenu-link">Orders</a></li>
                                <li class="submenu-item"><a href="licenses.php"
                                        class="submenu-link">Licenses</a></li>
                                <li class="submenu-item"><a href="payouts.php"
                                        class="submenu-link">Payouts</a></li>
                            </ul>
                        </div>
                    </div>
                </li>

                <!-- Analytics -->
                <li class="menu-item">
                    <a href="analytics.php" class="menu-link"><span><i class="bi bi-graph-up"></i>
                            Analytics</span></a>
                </li>

                <!-- Support -->
                <li class="menu-item has-sub">
                    <a href="#" class="menu-link"><span><i class="bi bi-life-preserver"></i>
                            Support</span></a>
                    <div class="submenu">
                        <div class="submenu-group-wrapper">
                            <ul class="submenu-group">
                                <li class="submenu-item"><a href="tickets.php"
                                        class="submenu-link">Tickets</a></li>
                                <li class="submenu-item"><a href="reviews.php"
                                        class="submenu-link">Reviews</a></li>
                                <li class="submenu-item"><a href="faq.php" class="submenu-link">FAQ</a></li>
                            </ul>
                        </div>
                    </div>
                </li>

                <!-- Login/Register di navbar -->
                <li class="menu-item">
                    <a href="auth/auth-login.php" class="menu-link">
                        <span><i class="bi bi-box-arrow-in-right"></i> Login</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="auth/auth-register.php" class="menu-link">
                        <span><i class="bi bi-person-plus"></i> Register</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav> <!-- end navbar -->
</header>