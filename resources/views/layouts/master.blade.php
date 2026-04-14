<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head', [
        'title' => trim($__env->yieldContent('title', 'BeatHive – Stock Music Admin'))
    ])
    @stack('head')
</head>

<body>

<script src="{{ asset('assets/static/js/initTheme.js') }}"></script>

<div id="app">

    {{-- SIDEBAR --}}
    @include('partials.navbar')

    <div id="main">

        {{-- HEADER --}}
        @include('partials.header')

        <div class="page-heading">

            @hasSection('page-heading')

                @yield('page-heading')

            @else

                <div class="page-heading">

                    <h3>@yield('heading', 'Dashboard – Stock Music')</h3>

                    <p class="text-subtitle text-muted">
                        @yield('subheading','Ringkasan performa dan aktivitas katalog.')
                    </p>

                </div>
            @endif

        </div>

        <div class="page-content">

            @yield('content')

        </div>

        @include('partials.footer')

    </div>

</div>

@include('partials.scripts')

@stack('scripts')

</body>
</html>