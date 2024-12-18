<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid nav-bar">
        <div class="container">
            <nav class="navbar navbar-light navbar-expand-lg py-4">
                <a href="{{ url('/') }}" class="navbar-brand">
                    <h1 class="text-primary fw-bold mb-0">K<span class="text-dark">L.</span> </h1>
                </a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="{{ url('/') }}"
                            class="nav-item nav-link {{ request()->is('/') ? 'active' : '' }}">Beranda</a>
                        <a href="{{ route('frontend.about') }}"
                            class="nav-item nav-link {{ request()->routeIs('frontend.about') ? 'active' : '' }}">Tentang
                            Kami</a>
                        <a href="{{ route('frontend.service') }}"
                            class="nav-item nav-link {{ request()->routeIs('frontend.service') ? 'active' : '' }}">Layanan</a>
                        <a href="{{ route('frontend.event') }}"
                            class="nav-item nav-link {{ request()->routeIs('frontend.event') ? 'active' : '' }}">Acara</a>
                        <a href="{{ route('frontend.menu') }}"
                            class="nav-item nav-link {{ request()->routeIs('frontend.menu') ? 'active' : '' }}">Menu</a>
                        {{-- <a href="{{ route('frontend.testimonial') }}"
                            class="nav-item nav-link {{ request()->routeIs('frontend.testimonial') ? 'active' : '' }}">Testimoni</a> --}}
                        <a href="{{ route('frontend.contact') }}"
                            class="nav-item nav-link {{ request()->routeIs('frontend.contact') ? 'active' : '' }}">Hubungi</a>
                        <a href="{{ route('frontend.map') }}"
                            class="nav-item nav-link {{ request()->routeIs('frontend.map') ? 'active' : '' }}">Lokasi</a>
                    </div>
                    @auth
                        <a href="{{ route('cart.index') }}"
                        class="btn-search btn btn-primary btn-md-square me-4 rounded-circle d-inline-flex my-2">
                            <i class="fas fa-shopping-cart"></i>
                        </a>
                    @endauth

                    @auth
                        <div class="dropdown">
                            <button class="btn btn-primary py-2 px-4 rounded-pill dropdown-toggle" type="button"
                                id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-2"></i>{{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu sm-dropdown-menu-start mt-3" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ url('/panel/dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="btn btn-primary py-2 px-4 rounded-pill my-2">
                            Login/Register
                        </a>
                    @endauth
                </div>
            </nav>
        </div>
    </div>
</header>
