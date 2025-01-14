<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Primary Meta Tags -->
    <title>Kanal Social Space - @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title" content="Kanal Social Space">
    <meta name="author" content="Themesberg">
    <meta name="description"
        content="Selamat Datang di Kanal Social Space. Ini adalah ruang untuk berbagi cerita, inspirasi, dan momen santai bersama teman-teman. Dari aroma kopi yang menggugah hingga lingkungan yang cozy, kami siap menjadi destinasi hangout favoritmu!" />
    <meta name="keywords"
        content="Kanal Social Space, Kanal, Social, Space, Coffee, Mocktail, Food, Kafe, Makassar, Wijaya Kusuma">
    <meta name="author" content="Muh. Alif Anhar">
    <link rel="canonical" href="https://themesberg.com/product/admin-dashboard/volt-premium-bootstrap-5-dashboard">

    <meta property="og:title" content="Kanal Social Space" />
    <meta property="og:description"
        content="Selamat Datang di Kanal Social Space. Ini adalah ruang untuk berbagi cerita, inspirasi, dan momen santai bersama teman-teman. Dari aroma kopi yang menggugah hingga lingkungan yang cozy, kami siap menjadi destinasi hangout favoritmu!" />
    <meta property="og:image" content="{{ asset('img/logo.jpg') }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Kanal Social Space" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $title ?? 'Homepage' }}" />
    <meta name="twitter:description"
        content="Selamat Datang di Kanal Social Space. Ini adalah ruang untuk berbagi cerita, inspirasi, dan momen santai bersama teman-teman. Dari aroma kopi yang menggugah hingga lingkungan yang cozy, kami siap menjadi destinasi hangout favoritmu!" />
    <meta name="twitter:image" content="{{ asset('img/logo.jpg') }}" />
    <meta name="twitter:site" content="@crwnleaf" />
    <meta name="twitter:creator" content="@crwanleaf" />

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('img/logo.jpg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo.jpg') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/logo.jpg') }}">
    <link rel="manifest" href="{{ asset('auth/assets/img/favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('auth/assets/img/favicon/safari-pinned-tab.svg') }}" color="#ffffff">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <!-- Sweet Alert -->
    <link type="text/css" href="{{ asset('auth/vendor/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">

    <!-- Notyf -->
    <link type="text/css" href="{{ asset('auth/vendor/notyf/notyf.min.css') }}" rel="stylesheet">

    <!-- Volt CSS -->
    <link type="text/css" href="{{ asset('auth/css/volt.css') }}" rel="stylesheet">

</head>

<body>
    @yield('content')
    <!-- Core -->
    <script src="{{ asset('auth/vendor/@popperjs/core/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('auth/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- Vendor JS -->
    <script src="{{ asset('auth/vendor/onscreen/dist/on-screen.umd.min.js') }}"></script>

    <!-- Slider -->
    <script src="{{ asset('auth/vendor/nouislider/dist/nouislider.min.js') }}"></script>

    <!-- Smooth scroll -->
    <script src="{{ asset('auth/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js') }}"></script>

    <!-- Charts -->
    <script src="{{ asset('auth/vendor/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('auth/vendor/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>

    <!-- Datepicker -->
    <script src="{{ asset('auth/vendor/vanillajs-datepicker/dist/js/datepicker.min.js') }}"></script>

    <!-- Sweet Alerts 2 -->
    <script src="{{ asset('auth/vendor/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>

    <!-- Moment JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>

    <!-- Vanilla JS Datepicker -->
    <script src="{{ asset('auth/vendor/vanillajs-datepicker/dist/js/datepicker.min.js') }}"></script>

    <!-- Notyf -->
    <script src="{{ asset('auth/vendor/notyf/notyf.min.js') }}"></script>

    <!-- Simplebar -->
    <script src="{{ asset('auth/vendor/simplebar/dist/simplebar.min.js') }}"></script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js') }}"></script>

    <!-- Volt JS -->
    <script src="{{ asset('auth/assets/js/volt.js') }}"></script>

</body>

</html>
