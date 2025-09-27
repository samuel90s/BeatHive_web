<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Login - BeatHive Admin Dashboard</title>

  <!-- Favicon & Styles -->
  <link rel="shortcut icon" href="{{ asset('assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
  <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/compiled/css/auth.css') }}">

  <style>
    #auth, #auth .row.h-100 { min-height: 100vh; }

    /* panel kanan */
    #auth-right {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #0f172a; /* background gelap */
    }

    #auth-photo {
      max-width: 90%;
      max-height: 90%;
      object-fit: contain;
      object-position: center;
      display: block;
    }

    @media (max-width: 991.98px){
      #auth-right{ display:none; }
    }
  </style>
</head>
<body>
  <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>

  <div id="auth">
    <div class="row h-100">
      <!-- LEFT -->
      <div class="col-lg-5 col-12 d-flex align-items-center">
        <div id="auth-left" class="w-100">
          <div class="auth-logo mb-3">
            <a href="{{ url('/') }}"><img src="{{ asset('assets/compiled/svg/logo.svg') }}" alt="Logo"></a>
          </div>

          <h1 class="auth-title">Log in.</h1>
          <p class="auth-subtitle mb-5">Log in dengan akun yang terdaftar.</p>

          @if(session('error'))
            <div class="alert alert-danger py-2">{{ session('error') }}</div>
          @endif

          <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group position-relative has-icon-left mb-4">
              <input name="email" type="email" class="form-control form-control-xl" placeholder="Email" required autofocus>
              <div class="form-control-icon"><i class="bi bi-envelope"></i></div>
            </div>

            <div class="form-group position-relative has-icon-left mb-4">
              <input name="password" type="password" class="form-control form-control-xl" placeholder="Password" required>
              <div class="form-control-icon"><i class="bi bi-shield-lock"></i></div>
            </div>

            <div class="form-check form-check-lg d-flex align-items-end">
              <input class="form-check-input me-2" type="checkbox" id="keepLoggedIn" disabled>
              <label class="form-check-label text-gray-600" for="keepLoggedIn">Keep me logged in</label>
            </div>

            <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5 w-100" type="submit">Log in</button>
          </form>

          <div class="text-center mt-5 text-lg fs-4">
            <p class="text-gray-600">Don't have an account?
              <a href="{{ route('register') }}" class="font-bold">Sign up</a>.
            </p>
            <p><a class="font-bold" href="{{ url('forgot-password') }}">Forgot password?</a>.</p>
          </div>
        </div>
      </div>

      <!-- RIGHT (gambar local) -->
      <div class="col-lg-7 d-none d-lg-flex" id="auth-right">
        <img id="auth-photo" src="{{ asset('assets/compiled/jpg/login_cover.jpg') }}" alt="Login Cover">
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
</body>
</html>
