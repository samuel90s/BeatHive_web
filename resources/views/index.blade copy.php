<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BeatHive – Stock Music Admin</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon" />

    <!-- Core styles (light/dark already supported by your template) -->
    <link rel="stylesheet" href="./assets/compiled/css/app.css" />
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css" />
    <link rel="stylesheet" href="./assets/compiled/css/iconly.css" />

</head>

<body>
    <script src="assets/static/js/initTheme.js"></script>
    <div id="app">
        <div id="main" class="layout-horizontal">
            <!-- Header -->
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
          <!-- ikon toggle -->
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
            <label class="form-check-label" for="toggle-dark"></label>
          </div>
        </div>

        <?php if (!isset($_SESSION)) { session_start(); } ?>
        <?php if (!isset($_SESSION['user'])): ?>
          <!-- BELUM LOGIN -->
          <div class="d-none d-xl-flex align-items-center gap-2 ms-3">
            <a href="auth/auth-login.php" class="btn btn-outline-primary btn-sm">Login</a>
            <a href="auth/auth-register.php" class="btn btn-primary btn-sm">Register</a>
          </div>
        <?php else: ?>
          <!-- SUDAH LOGIN -->
          <div class="dropdown ms-3">
            <a href="#" id="topbarUserDropdown"
               class="user-dropdown d-flex align-items-center dropend dropdown-toggle"
               data-bs-toggle="dropdown" aria-expanded="false">
              <div class="avatar avatar-md2">
                <img src="./assets/compiled/jpg/1.jpg" alt="Avatar" />
              </div>
              <div class="text">
                <h6 class="user-dropdown-name"><?= htmlspecialchars($_SESSION['user']['name'] ?? 'User'); ?></h6>
                <p class="user-dropdown-status text-sm text-muted">Stock Music</p>
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="topbarUserDropdown">
              <li><a class="dropdown-item" href="account.php">My Account</a></li>
              <li><a class="dropdown-item" href="settings.php">Settings</a></li>
              <li><hr class="dropdown-divider" /></li>
              <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
          </div>
        <?php endif; ?>

        <!-- Burger button responsive -->
        <a href="#" class="burger-btn d-block d-xl-none"><i class="bi bi-justify fs-3"></i></a>
      </div>
    </div>
  </div>

  <!-- Main navbar -->
  <nav class="main-navbar">
    <div class="container">
      <ul>
        <li class="menu-item">
          <a href="index.php" class="menu-link"><span><i class="bi bi-grid-fill"></i> Dashboard</span></a>
        </li>

        <!-- Catalog -->
        <li class="menu-item has-sub">
          <a href="#" class="menu-link"><span><i class="bi bi-music-note-list"></i> Catalog</span></a>
          <div class="submenu">
            <div class="submenu-group-wrapper">
              <ul class="submenu-group">
                <li class="submenu-item"><a href="tracks.php" class="submenu-link">All Tracks</a></li>
                <li class="submenu-item"><a href="albums.php" class="submenu-link">Albums / Packs</a></li>
                <li class="submenu-item"><a href="genres.php" class="submenu-link">Genres & Tags</a></li>
              </ul>
            </div>
          </div>
        </li>

        <!-- Upload -->
        <li class="menu-item has-sub">
          <a href="#" class="menu-link"><span><i class="bi bi-cloud-upload-fill"></i> Upload</span></a>
          <div class="submenu">
            <div class="submenu-group-wrapper">
              <ul class="submenu-group">
                <li class="submenu-item"><a href="upload-track.php" class="submenu-link">Upload Track</a></li>
                <li class="submenu-item"><a href="bulk-import.php" class="submenu-link">Bulk Import</a></li>
              </ul>
            </div>
          </div>
        </li>

        <!-- Sales -->
        <li class="menu-item has-sub">
          <a href="#" class="menu-link"><span><i class="bi bi-bag-check-fill"></i> Sales</span></a>
          <div class="submenu">
            <div class="submenu-group-wrapper">
              <ul class="submenu-group">
                <li class="submenu-item"><a href="orders.php" class="submenu-link">Orders</a></li>
                <li class="submenu-item"><a href="licenses.php" class="submenu-link">Licenses</a></li>
                <li class="submenu-item"><a href="payouts.php" class="submenu-link">Payouts</a></li>
              </ul>
            </div>
          </div>
        </li>

        <!-- Analytics -->
        <li class="menu-item">
          <a href="analytics.php" class="menu-link"><span><i class="bi bi-graph-up"></i> Analytics</span></a>
        </li>

        <!-- Support -->
        <li class="menu-item has-sub">
          <a href="#" class="menu-link"><span><i class="bi bi-life-preserver"></i> Support</span></a>
          <div class="submenu">
            <div class="submenu-group-wrapper">
              <ul class="submenu-group">
                <li class="submenu-item"><a href="tickets.php" class="submenu-link">Tickets</a></li>
                <li class="submenu-item"><a href="reviews.php" class="submenu-link">Reviews</a></li>
                <li class="submenu-item"><a href="faq.php" class="submenu-link">FAQ</a></li>
              </ul>
            </div>
          </div>
        </li>

        <!-- Login/Register di navbar -->
        <?php if (!isset($_SESSION['user'])): ?>
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
        <?php endif; ?>
      </ul>
    </div>
  </nav>
</header>
</header>

            <!-- Content -->
            <div class="content-wrapper container">
                <div class="page-heading">
                    <h3>Dashboard – Stock Music</h3>
                    <p class="text-muted">Ringkasan performa marketplace BeatHive kamu.</p>
                </div>

                <div class="page-content">
                    <section class="row">
                        <div class="col-12 col-lg-9">
                            <!-- KPI Cards -->
                            <div class="row g-4">
                                <div class="col-6 col-lg-4 col-md-6">
                                    <div class="card">
                                    <div class="card-body py-4 px-5">
                                        <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white d-flex align-items-center justify-content-center rounded me-3" style="width:46px; height:46px;">
                                            <i class="bi bi-soundwave fs-5"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold">1,245</h5>
                                            <h6 class="mb-0 text-muted fs-6">Total Tracks</h6>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <div class="col-6 col-lg-4 col-md-6">
                                    <div class="card">
                                    <div class="card-body py-4 px-5">
                                        <div class="d-flex align-items-center">
                                        <div class="bg-info text-white d-flex align-items-center justify-content-center rounded me-3" style="width:46px; height:46px;">
                                            <i class="bi bi-download fs-5"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold">6,980</h5>
                                            <h6 class="mb-0 text-muted fs-6">Downloads (30d)</h6>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <div class="col-6 col-lg-4 col-md-6">
                                    <div class="card">
                                    <div class="card-body py-4 px-5">
                                        <div class="d-flex align-items-center">
                                        <div class="bg-success text-white d-flex align-items-center justify-content-center rounded me-3" style="width:46px; height:46px;">
                                            <i class="bi bi-currency-dollar fs-5"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold">125,450,000</h5>
                                            <h6 class="mb-0 text-muted fs-6">Revenue (IDR)</h6>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>


                            <!-- Charts -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header"><h4>Revenue & Downloads</h4></div>
                                        <div class="card-body">
                                            <div id="chart-revenue-downloads"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Table: Latest Sales -->
                            <div class="row">
                                <div class="col-12 col-xl-4">
                                    <div class="card">
                                        <div class="card-header"><h4>Top Genres</h4></div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div id="chart-top-genres"></div>
                                                </div>
                                            </div>
                                            <div class="row mt-3 g-3">
                                                <div class="col-6 d-flex align-items-center">
                                                    <svg class="bi text-primary" width="32" height="32">
                                                        <use xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                                    </svg>
                                                    <h6 class="mb-0 ms-3">Cinematic</h6>
                                                </div>
                                                <div class="col-6 text-end"><h6 class="mb-0">2,315 dl</h6></div>

                                                <div class="col-6 d-flex align-items-center">
                                                    <svg class="bi text-success" width="32" height="32">
                                                        <use xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                                    </svg>
                                                    <h6 class="mb-0 ms-3">Hip-Hop</h6>
                                                </div>
                                                <div class="col-6 text-end"><h6 class="mb-0">1,870 dl</h6></div>

                                                <div class="col-6 d-flex align-items-center">
                                                    <svg class="bi text-danger" width="32" height="32">
                                                        <use xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                                    </svg>
                                                    <h6 class="mb-0 ms-3">Lo‑Fi</h6>
                                                </div>
                                                <div class="col-6 text-end"><h6 class="mb-0">1,246 dl</h6></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-xl-8">
                                    <div class="card">
                                        <div class="card-header"><h4>Latest Sales</h4></div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-lg">
                                                    <thead>
                                                        <tr>
                                                            <th>Buyer</th>
                                                            <th>Item</th>
                                                            <th>License</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="col-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar avatar-md"><img src="./assets/compiled/jpg/5.jpg" alt="avatar" /></div>
                                                                    <p class="font-bold ms-3 mb-0">Studio Nusantara</p>
                                                                </div>
                                                            </td>
                                                            <td class="col-5"><p class="mb-0">"Sunset Vibes" – Lo‑Fi</p></td>
                                                            <td class="col-2"><span class="badge bg-primary">Standard</span></td>
                                                            <td class="col-2"><strong>IDR 149,000</strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar avatar-md"><img src="./assets/compiled/jpg/2.jpg" alt="avatar" /></div>
                                                                    <p class="font-bold ms-3 mb-0">Citra Media</p>
                                                                </div>
                                                            </td>
                                                            <td class="col-5"><p class="mb-0">"Epic Horizon" – Cinematic</p></td>
                                                            <td class="col-2"><span class="badge bg-success">Broadcast</span></td>
                                                            <td class="col-2"><strong>IDR 2,400,000</strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar avatar-md"><img src="./assets/compiled/jpg/1.jpg" alt="avatar" /></div>
                                                                    <p class="font-bold ms-3 mb-0">PixelHub</p>
                                                                </div>
                                                            </td>
                                                            <td class="col-5"><p class="mb-0">"Street Bounce" – Hip‑Hop</p></td>
                                                            <td class="col-2"><span class="badge bg-warning">Extended</span></td>
                                                            <td class="col-2"><strong>IDR 599,000</strong></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right column -->
                        <div class="col-12 col-lg-3">
                            <!-- Profile card -->
                            <div class="card">
                                <div class="card-body py-4 px-5">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xl"><img src="./assets/compiled/jpg/1.jpg" alt="Face 1" /></div>
                                        <div class="ms-3 name">
                                            <h5 class="font-bold">BeatHive Admin</h5>
                                            <h6 class="text-muted mb-0">@beathive</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Tickets / Messages -->
                            <div class="card">
                                <div class="card-header"><h4>Recent Tickets</h4></div>
                                <div class="card-content pb-4">
                                    <div class="recent-message d-flex px-4 py-3">
                                        <div class="avatar avatar-lg"><img src="./assets/compiled/jpg/4.jpg" alt="avatar" /></div>
                                        <div class="name ms-4">
                                            <h5 class="mb-1">Ardi Susanto</h5>
                                            <h6 class="text-muted mb-0">License question</h6>
                                        </div>
                                    </div>
                                    <div class="recent-message d-flex px-4 py-3">
                                        <div class="avatar avatar-lg"><img src="./assets/compiled/jpg/5.jpg" alt="avatar" /></div>
                                        <div class="name ms-4">
                                            <h5 class="mb-1">Dina Creative</h5>
                                        <h6 class="text-muted mb-0">Invoice request</h6>
                                        </div>
                                    </div>
                                    <div class="recent-message d-flex px-4 py-3">
                                        <div class="avatar avatar-lg"><img src="./assets/compiled/jpg/1.jpg" alt="avatar" /></div>
                                        <div class="name ms-4">
                                            <h5 class="mb-1">MotionLab</h5>
                                            <h6 class="text-muted mb-0">Track edit</h6>
                                        </div>
                                    </div>
                                    <div class="px-4">
                                        <button class="btn btn-block btn-xl btn-light-primary font-bold mt-3">Open Inbox</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Visitors / Audience profile (renamed to Traffic Sources) -->
                            <div class="card">
                                <div class="card-header"><h4>Traffic Sources</h4></div>
                                <div class="card-body">
                                    <div id="chart-traffic-sources"></div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Footer -->
            <footer>
                <div class="container">
                    <div class="footer clearfix mb-0 text-muted">
                        <div class="float-start">
                            <p>2025 &copy; BeatHive</p>
                        </div>
                        <div class="float-end">
                            <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a href="#">samx</a></p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Core scripts -->
    <script src="assets/static/js/components/dark.js"></script>
    <script src="assets/static/js/pages/horizontal-layout.js"></script>
    <script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/compiled/js/app.js"></script>

    <!-- Charts -->
    <script src="assets/extensions/apexcharts/apexcharts.min.js"></script>

    <!-- Demo chart wiring (IDs match the elements above). Replace with real data calls later. -->
    <script>
        // Revenue & Downloads (area + column)
        (function () {
            const el = document.querySelector('#chart-revenue-downloads');
            if (!el || typeof ApexCharts === 'undefined') return;
            const options = {
                chart: { type: 'line', height: 320, toolbar: { show: false } },
                stroke: { width: [3, 3] },
                series: [
                    { name: 'Revenue (IDR juta)', data: [12, 10, 14, 18, 22, 25, 28, 30, 27, 29, 33, 35] },
                    { name: 'Downloads', data: [380, 420, 510, 690, 720, 760, 820, 910, 860, 900, 980, 1050] }
                ],
                xaxis: { categories: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'] },
                yaxis: [{ labels: { formatter: (val) => `${val}` } }],
                tooltip: { shared: true },
            };
            new ApexCharts(el, options).render();
        })();

        // Top Genres (donut)
        (function () {
            const el = document.querySelector('#chart-top-genres');
            if (!el || typeof ApexCharts === 'undefined') return;
            const options = {
                chart: { type: 'donut', height: 280 },
                series: [35, 28, 18, 10, 9],
                labels: ['Cinematic', 'Hip-Hop', 'Lo‑Fi', 'EDM', 'Acoustic'],
                legend: { position: 'bottom' },
            };
            new ApexCharts(el, options).render();
        })();

        // Traffic sources (radial or pie)
        (function () {
            const el = document.querySelector('#chart-traffic-sources');
            if (!el || typeof ApexCharts === 'undefined') return;
            const options = {
                chart: { type: 'pie', height: 280 },
                series: [44, 26, 18, 12],
                labels: ['Direct', 'Search', 'Referral', 'Social'],
                legend: { position: 'bottom' },
            };
            new ApexCharts(el, options).render();
        })();
    </script>
</body>

</html>