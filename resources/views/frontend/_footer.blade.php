<section id="footer">
    {{-- Footer Start --}}
    <div class="container-fluid footer py-6 my-6 mb-0 bg-light wow bounceInUp">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="footer-item">
                        <h1 class="text-danger">Mu<span class="text-dark">vid</span></h1>
                        <p class="lh-lg mb-4">Muvid adalah ruang untuk berbagi cerita,<br> inspirasi, dan momen santai bersama <br> teman-teman. Muvid siap menjadi destinasi hangout favorit untuk menghabiskan waktu berkualitas bersama.
                        </p>
                        <div class="footer-icon d-flex">
                            {{-- <a class="btn btn-danger btn-sm-square me-2 rounded-circle" href=""><i
                                    class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-danger btn-sm-square me-2 rounded-circle" href=""><i
                                    class="fab fa-twitter"></i></a> --}}
                            <a href="https://www.instagram.com/muvid_coffee.eatery/"
                                class="btn btn-danger btn-sm-square me-2 rounded-circle" target="_blank"><i
                                    class="fab fa-instagram"></i></a>
                            {{-- <a href="#" class="btn btn-danger btn-sm-square rounded-circle"><i
                                    class="fab fa-youtube"></i></a> --}}
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="footer-item">
                        <h4 class="mb-4">Quick Links</h4>
                        <div class="d-flex flex-column align-items-start">
                            <a class="text-body mb-3" href="{{ url('/') }}">
                                <i class="fa fa-chevron-right text-danger me-2"></i>Beranda
                            </a>
                            <a class="text-body mb-3" href="{{ route('frontend.about') }}">
                                <i class="fa fa-chevron-right text-danger me-2"></i>Tentang Kami
                            </a>
                            <a class="text-body mb-3" href="{{ route('frontend.menu') }}">
                                <i class="fa fa-chevron-right text-danger me-2"></i>Menu
                            </a>
                            <a class="text-body mb-3" href="{{ route('frontend.event') }}">
                                <i class="fa fa-chevron-right text-danger me-2"></i>Acara
                            </a>
                            <a class="text-body mb-3" href="{{ route('frontend.contact') }}">
                                <i class="fa fa-chevron-right text-danger me-2"></i>Hubungi
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="footer-item">
                        <h4 class="mb-4">Contact & Hours</h4>
                        <div class="d-flex flex-column align-items-start">
                            <p><i class="fa fa-map-marker-alt text-danger me-2"></i>Jl. Waduk Tunggu Pampamng, Makassar</p>
                            <p><i class="fa fa-phone-alt text-danger me-2"></i>0812 3456 7890</p>
                            <p><i class="fas fa-envelope text-danger me-2"></i>muvid@gmail.com</p>
                            <p class="mb-2"><i class="far fa-clock text-danger me-2"></i>Opening Hours:</p>
                            <p class="mb-0 ps-4">Everyday: 07:00 - 23:00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Footer End --}}

    <!-- Copyright Start -->
    <div class="container-fluid copyright bg-dark py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>Muvid</a>, All right reserved.</span>
                </div>

