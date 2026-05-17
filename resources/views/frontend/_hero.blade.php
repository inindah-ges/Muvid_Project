<section id="hero">
    <div class="container-fluid bg-light py-6 my-6 mt-0">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-sm-12 col-md-12 col-lg-8">
                    {{-- <small class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-4 animated bounceInDown">Selamat Datang di</small> --}}
                    <h1 class="display-2 mb-0 animated bounceInDown"><span class="text-danger">Mu</span>vid
                    </h1>
                    <h6 class="text1 mb-4 animated bounceInDown">Coffee & Eatery Food</h6>
                    <h6 class="mb-4 animated bounceInDown">Ini adalah ruang untuk berbagi cerita, inspirasi, dan momen 
                        <br> santai bersama teman-teman. Dengan suasana hangat, 
                        <br> aroma kopi yang menggugah, dan berbagai kegiatan positif, <br>tempat ini hadir untuk menciptakan kebersamaan serta pengalaman yang <br> bermakna dalam setiap pertemuan.
                    </h6>
                    <a href="{{ route('frontend.menu') }}"
                        class="btn btn-primary border-0 rounded-pill py-3 px-4 px-md-5 me-4 animated bounceInLeft">Pesan
                        Sekarang</a>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-4">
                    <img src="{{ asset('frontend/assets') }}/img/profil.jpg"
                        class="img-fluid w-100 rounded animated zoomIn" alt="Siap Melayani">
                </div>
            </div>
        </div>
    </div>
</section>
