<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <title>Muvid - @yield('title')</title>

    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="description"
        content="Selamat Datang di Muvid. Ini adalah ruang untuk berbagi cerita, inspirasi, dan momen santai bersama teman-teman. Dari aroma kopi yang menggugah hingga lingkungan yang cozy, kami siap menjadi destinasi hangout favoritmu!" />
    <meta name="keywords"
        content="Muvid, Coffee, Mocktail, Food, Kafe, Makassar, Wijaya Kusuma">
    <meta name="author" content="Muh. Alif Anhar">
    <meta name="robots" content="index, follow">

    <meta property="og:title" content="Muvid" />
    <meta property="og:description"
        content="Selamat Datang di Muvid. Ini adalah ruang untuk berbagi cerita, inspirasi, dan momen santai bersama teman-teman. Dari aroma kopi yang menggugah hingga lingkungan yang cozy, kami siap menjadi destinasi hangout favoritmu!" />
    <meta property="og:image" content="{{ asset('img/logo.jpg') }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Muvid" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $title ?? 'Homepage' }}" />
    <meta name="twitter:description"
        content="Selamat Datang di Muvid. Ini adalah ruang untuk berbagi cerita, inspirasi, dan momen santai bersama teman-teman. Dari aroma kopi yang menggugah hingga lingkungan yang cozy, kami siap menjadi destinasi hangout favoritmu!" />
    <meta name="twitter:image" content="{{ asset('img/logo.jpg') }}" />
    <meta name="twitter:site" content="@crwnleaf" />
    <meta name="twitter:creator" content="@crwanleaf" />

    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.jpg') }}">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playball&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('frontend') }}/lib/animate/animate.min.css" rel="stylesheet">
    <link href="{{ asset('frontend') }}/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="{{ asset('frontend') }}/lib/owlcarousel/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('frontend/assets') }}/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('frontend/assets') }}/css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar start -->
    @include('frontend.template.navbar')
    {{-- @include('frontend._modal-search') --}}
    <!-- Navbar End -->

    {{-- Main Content --}}
    <main class="main">
        @yield('content')
    </main>
    {{-- Main End --}}

    <!-- Footer Start -->
    @include('frontend._footer')
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-md-square btn-primary rounded-circle back-to-top"><i
            class="fa fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('frontend') }}/lib/wow/wow.min.js"></script>
    <script src="{{ asset('frontend') }}/lib/easing/easing.min.js"></script>
    <script src="{{ asset('frontend') }}/lib/waypoints/waypoints.min.js"></script>
    <script src="{{ asset('frontend') }}/lib/counterup/counterup.min.js"></script>
    <script src="{{ asset('frontend') }}/lib/lightbox/js/lightbox.min.js"></script>
    <script src="{{ asset('frontend') }}/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('frontend/assets') }}/js/main.js"></script>
</body>

</html>
