<header class="mb-3">

    <div class="d-flex justify-content-end align-items-center gap-3">

        {{-- DARK MODE --}}
        <div class="theme-toggle d-flex align-items-center gap-2">

            <i class="bi bi-sun"></i>

            <div class="form-check form-switch fs-6 m-0">
                <input class="form-check-input" type="checkbox" id="toggle-dark" style="cursor:pointer">
            </div>

            <i class="bi bi-moon"></i>

        </div>


        {{-- AUTH --}}
        @guest

            <div class="d-flex gap-2">

                <a href="{{ Route::has('login') ? route('login') : '#' }}" class="btn btn-outline-primary btn-sm">
                    Login
                </a>

                <a href="{{ Route::has('register') ? route('register') : '#' }}" class="btn btn-primary btn-sm">
                    Register
                </a>

            </div>

        @else

            {{-- USER DROPDOWN --}}
            <div class="dropdown">

                <a href="#" class="d-flex align-items-center dropdown-toggle" data-bs-toggle="dropdown">

                    <div class="avatar avatar-md">
                        <img src="{{ asset('assets/compiled/jpg/1.jpg') }}">
                    </div>

                    <div class="ms-2 d-none d-lg-block">
                        <h6 class="mb-0">{{ Auth::user()->name ?? 'User' }}</h6>
                    </div>

                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow">

                    <li>
                        <a class="dropdown-item" href="{{ Route::has('profile.index') ? route('profile.index') : '#' }}">
                            My Account
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="{{ Route::has('settings') ? route('settings') : '#' }}">
                            Settings
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <form action="{{ Route::has('logout') ? route('logout') : '#' }}" method="POST">
                            @csrf
                            <button class="dropdown-item">
                                Logout
                            </button>
                        </form>
                    </li>

                </ul>

            </div>

        @endguest


        {{-- BURGER --}}
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>

    </div>

</header>