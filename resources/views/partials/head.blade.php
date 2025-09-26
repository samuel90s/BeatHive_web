@php
    $title = $title ?? 'BeatHive – Stock Music Admin';
@endphp
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>{{ $title }}</title>

<link rel="shortcut icon" href="{{ asset('assets/compiled/svg/favicon.svg') }}" type="image/x-icon" />

{{-- Core styles --}}
<link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}" />
