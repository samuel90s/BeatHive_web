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

    #auth-right {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #0f172a;
      padding: 20px;
    }

    #auth-photo {
      width: 100%;
      max-width: 500px;
      height: auto;
      object-fit: contain;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.4);
    }

    @media (max-width: 991.98px){
      #auth-right { display:none; }
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

          {{-- Error handling --}}
          @if ($errors->any())
            <div class="alert alert-danger py-2">
              {{ $errors->first() }}
            </div>
          @endif

          <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group position-relative has-icon-left mb-4">
              <input name="email" type="email" class="form-control form-control-xl"
                     placeholder="Email" required autofocus
                     value="{{ old('email') }}">
              <div class="form-control-icon"><i class="bi bi-envelope"></i></div>
            </div>

            {{-- Password + toggle (fixed placement) --}}
            <div class="form-group position-relative has-icon-left mb-4">
              <input id="password" name="password" type="password"
                    class="form-control form-control-xl pe-5"  {{-- ruang kanan buat tombol --}}
                    placeholder="Password" required>
              <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
              </div>

              {{-- Tombol mata ditempatkan absolut di kanan, center vertikal --}}
              <button type="button" class="btn btn-outline-secondary btn-sm position-absolute top-50 end-0 translate-middle-y me-2"
                      id="togglePwd" tabindex="-1" aria-label="Show/Hide password">
                <i class="bi bi-eye" id="eyeOpen"></i>
                <i class="bi bi-eye-slash d-none" id="eyeClose"></i>
              </button>
            </div>

            <div class="form-check form-check-lg d-flex align-items-end">
              <input class="form-check-input me-2" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
              <label class="form-check-label text-gray-600" for="remember">Keep me logged in</label>
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

      <!-- RIGHT (gambar lokal cover) -->
      <div class="col-lg-7 d-none d-lg-flex" id="auth-right">
        <img id="auth-photo" src="{{ asset('assets/compiled/jpg/login_cover.jpg') }}" alt="Login Cover">
      </div>
    </div>
  </div>

  <script>
    (function(){
      const form = document.querySelector('form[action="{{ route('login') }}"]');
      const email = document.querySelector('input[name="email"]');
      const pwd = document.getElementById('password');
      const btn = document.getElementById('togglePwd');
      const eyeO = document.getElementById('eyeOpen');
      const eyeC = document.getElementById('eyeClose');
      const submit = form ? form.querySelector('button[type="submit"]') : null;

      // Smart focus
      if (email && pwd) (email.value ? pwd : email).focus();

      // Show/Hide password
      if (btn && pwd) {
        btn.addEventListener('click', () => {
          const isPwd = pwd.type === 'password';
          pwd.type = isPwd ? 'text' : 'password';
          eyeO.classList.toggle('d-none', !isPwd);
          eyeC.classList.toggle('d-none', isPwd);
          pwd.focus({preventScroll:true});
        });
      }

      // CapsLock detector
      if (pwd) {
        const detect = (e) => {
          const on = e.getModifierState && e.getModifierState('CapsLock');
          pwd.setCustomValidity(on ? 'Caps Lock is ON' : '');
          if (on) pwd.reportValidity();
        };
        pwd.addEventListener('keydown', detect);
        pwd.addEventListener('keyup', detect);
        pwd.addEventListener('blur', () => pwd.setCustomValidity(''));
      }

      // Prevent double submit
      if (form && submit) {
        let locked = false;
        form.addEventListener('submit', () => {
          if (locked) return;
          locked = true;
          submit.disabled = true;
          submit.setAttribute('aria-busy', 'true');
        });
      }
    })();
  </script>

  <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
</body>
</html>
