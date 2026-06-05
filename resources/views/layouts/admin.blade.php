<!doctype html>
<html lang="en">

<head>
    <title>Slip Management App</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description"
        content="Old City Sand Washing LLC provides premium sand washing, aggregate processing, and construction material supply services with reliable quality and timely delivery." />

    <meta name="keywords"
        content="Old City Sand Washing, sand washing, washed sand, construction sand, aggregate supplier, sand processing, construction materials, building materials, gravel supply, sand and gravel" />

    <meta name="author" content="Old City Sand Washing LLC" />

    {{-- Icon Agriculture App --}}
    <link rel="shortcut icon" href="{{ asset('assets/images/old.jpeg') }}" />
    <link rel="apple-touch-icon-precomposed" href="{{ asset('assets/images/old.jpeg') }}" />

    {{-- [CSS Files] --}}
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/inter/inter.css') }}" id="main-font-link" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/phosphor/duotone/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link" />
    <script src="{{ asset('assets/js/tech-stack.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset(path: 'assets/css/plugins/animate.min.css') }}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    {{-- Stack for additional styles from child pages --}}
    @stack('styles')

</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr"
    data-pc-theme_contrast="">

    {{-- Page loader --}}
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    @if (!in_array(Route::currentRouteName(), ['password.request', 'login', 'password.reset', 'register', 'landing.home']))
        @include('profile.partials.admin.sidebar')
        @include('profile.partials.admin.topbar')
    @endif

    {{-- main contents --}}
    @yield('content')

    {{-- js files --}}
    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/icon/custom-font.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    {{-- Dark mode and light mode --}}
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script src="{{ asset('assets/js/custom_pages/inputdateclickevent.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/elements/ac-alert.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

    {{-- <script src="{{ asset('assets/js/plugins/simple-datatables.js') }}"></script> --}}

    {{-- jQuery pehle, phir DataTables --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    {{-- Stack for additional scripts from child pages --}}
    @stack('scripts')

    {{-- Stats Counter --}}

</body>

</html>
