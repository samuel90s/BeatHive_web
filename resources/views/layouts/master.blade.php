<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head', ['title' => trim($__env->yieldContent('title', 'BeatHive – Stock Music Admin'))])
    @stack('head') {{-- kalau butuh tambahan head per-halaman --}}
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>

    <div id="app">
        <div id="main" class="layout-horizontal">
            <header class="mb-5">
                @include('partials.header')
                @include('partials.navbar')
            </header>
            <div class="content-wrapper container">
                <div class="page-heading">
                    @hasSection('page-heading')
                    @yield('page-heading')
                    @else
                    <h3>@yield('heading', 'Dashboard – Stock Music')</h3>
                    <p class="text-muted">@yield('subheading', 'Ringkasan performa marketplace BeatHive kamu.')</p>
                    @endif
                </div>

                <div class="page-content">
                    @yield('content')
                </div>
            </div>

            @include('partials.footer')
        </div>
    </div>

    @include('partials.scripts')
    @stack('scripts') {{-- script khusus halaman --}}
</body>

</html>