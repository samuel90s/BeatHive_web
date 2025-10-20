<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Register - BeatHive Admin Dashboard</title>

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
      max-width: 500px;
      width: 100%;
      height: auto;
      object-fit: contain;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.4);
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

          <h1 class="auth-title">Sign Up</h1>
          <p class="auth-subtitle mb-5">Input your data to register to our website.</p>

          {{-- Error handling --}}
          @if ($errors->any())
            <div class="alert alert-danger py-2">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Full Name --}}
            <div class="form-group position-relative has-icon-left mb-4">
              <input type="text" name="name" class="form-control form-control-xl"
                     placeholder="Full Name" required value="{{ old('name') }}">
              <div class="form-control-icon"><i class="bi bi-person-badge"></i></div>
            </div>

            {{-- Username --}}
            <div class="form-group position-relative has-icon-left mb-4">
              <input type="text" name="username" class="form-control form-control-xl"
                     placeholder="Username (letters, numbers, - _)" required
                     value="{{ old('username') }}">
              <div class="form-control-icon"><i class="bi bi-person"></i></div>
            </div>

            {{-- Email --}}
            <div class="form-group position-relative has-icon-left mb-4">
              <input type="email" name="email" class="form-control form-control-xl"
                     placeholder="Email" required value="{{ old('email') }}">
              <div class="form-control-icon"><i class="bi bi-envelope"></i></div>
            </div>

            {{-- Password --}}
            <div class="form-group position-relative has-icon-left mb-4">
              <input id="password" type="password" name="password"
                     class="form-control form-control-xl pe-5" placeholder="Password" required>
              <div class="form-control-icon"><i class="bi bi-shield-lock"></i></div>
              <button type="button"
                      class="btn btn-outline-secondary btn-sm position-absolute top-50 end-0 translate-middle-y me-2"
                      id="togglePwd" tabindex="-1" aria-label="Show/Hide password">
                <i class="bi bi-eye" id="eyeOpen"></i>
                <i class="bi bi-eye-slash d-none" id="eyeClose"></i>
              </button>
            </div>

            {{-- Confirm Password --}}
            <div class="form-group position-relative has-icon-left mb-4">
              <input id="password_confirmation" type="password" name="password_confirmation"
                     class="form-control form-control-xl pe-5" placeholder="Confirm Password" required>
              <div class="form-control-icon"><i class="bi bi-shield-lock"></i></div>
              <button type="button"
                      class="btn btn-outline-secondary btn-sm position-absolute top-50 end-0 translate-middle-y me-2"
                      id="toggleConfirm" tabindex="-1" aria-label="Show/Hide confirm password">
                <i class="bi bi-eye" id="eyeOpen2"></i>
                <i class="bi bi-eye-slash d-none" id="eyeClose2"></i>
              </button>
            </div>

            <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5 w-100" type="submit">
              Sign Up
            </button>
          </form>

          <div class="text-center mt-5 text-lg fs-4">
            <p class="text-gray-600">Already have an account?
              <a href="{{ route('login') }}" class="font-bold">Log in</a>.
            </p>
          </div>
        </div>
      </div>

      <!-- RIGHT (gambar cover) -->
      <div class="col-lg-7 d-none d-lg-flex" id="auth-right">
        <img id="auth-photo" src="{{ asset('assets/compiled/jpg/login_cover.jpg') }}" alt="Register Cover">
      </div>
    </div>
  </div>

  <script>
    (function(){
      const form = document.querySelector('form[action="{{ route('register') }}"]');
      const fields = ['name','username','email','password'];
      const pwd = document.getElementById('password');
      const confirm = document.getElementById('password_confirmation');
      const btn1 = document.getElementById('togglePwd');
      const btn2 = document.getElementById('toggleConfirm');
      const eyeO1 = document.getElementById('eyeOpen');
      const eyeC1 = document.getElementById('eyeClose');
      const eyeO2 = document.getElementById('eyeOpen2');
      const eyeC2 = document.getElementById('eyeClose2');
      const submit = form ? form.querySelector('button[type="submit"]') : null;

      // Smart autofocus
      for (const f of fields) {
        const el = document.querySelector(`[name="${f}"]`);
        if (el && !el.value) { el.focus(); break; }
      }

      // Show/hide password
      const toggle = (btn, input, eyeO, eyeC) => {
        if (!btn || !input) return;
        btn.addEventListener('click', () => {
          const isPwd = input.type === 'password';
          input.type = isPwd ? 'text' : 'password';
          eyeO.classList.toggle('d-none', !isPwd);
          eyeC.classList.toggle('d-none', isPwd);
          input.focus({preventScroll:true});
        });
      };
      toggle(btn1, pwd, eyeO1, eyeC1);
      toggle(btn2, confirm, eyeO2, eyeC2);

      // CapsLock warning
      const watchCaps = (input) => {
        if (!input) return;
        const detect = (e) => {
          const on = e.getModifierState && e.getModifierState('CapsLock');
          input.setCustomValidity(on ? 'Caps Lock is ON' : '');
          if (on) input.reportValidity();
        };
        input.addEventListener('keydown', detect);
        input.addEventListener('keyup', detect);
        input.addEventListener('blur', () => input.setCustomValidity(''));
      };
      watchCaps(pwd);
      watchCaps(confirm);

      // Prevent double submit
      if (form && submit) {
        let locked = false;
        form.addEventListener('submit', () => {
          if (locked) return;
          locked = true;
          submit.disabled = true;
          submit.setAttribute('aria-busy','true');
        });
      }
    })();
  </script>

  <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
</body>
</html>
