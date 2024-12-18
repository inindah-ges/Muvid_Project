<section id="event">
    <div class="container-fluid event py-3 my-6">
        <div class="container">
            <div class="text-center wow bounceInUp">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Acara
                    Terbaru</small>
                <h1 class="display-5 mb-5">Galeri Acara Sosial & Profesional Kami</h1>
            </div>
            <div class="tab-class text-center">
                <ul class="nav nav-pills d-inline-flex justify-content-center mb-5 wow bounceInUp">
                    <li class="nav-item p-2">
                        <a class="d-flex mx-2 py-2 border border-primary bg-light rounded-pill active"
                            data-bs-toggle="pill" href="#tab-1">
                            <span class="text-dark" style="width: 150px;">Semua Acara</span>
                        </a>
                    </li>
                    <li class="nav-item p-2">
                        <a class="d-flex py-2 mx-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill"
                            href="#tab-2">
                            <span class="text-dark" style="width: 150px;">Bazar</span>
                        </a>
                    </li>
                    <li class="nav-item p-2">
                        <a class="d-flex mx-2 py-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill"
                            href="#tab-3">
                            <span class="text-dark" style="width: 150px;">Live Musik</span>
                        </a>
                    </li>
                    <li class="nav-item p-2">
                        <a class="d-flex mx-2 py-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill"
                            href="#tab-4">
                            <span class="text-dark" style="width: 150px;">Nonton Bareng</span>
                        </a>
                    </li>
                    <li class="nav-item p-2">
                        <a class="d-flex mx-2 py-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill"
                            href="#tab-5">
                            <span class="text-dark" style="width: 150px;">Game Night</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="row g-4">
                                    @foreach ($events as $event)
                                    <div class="col-sm-12 col-md-6 col-lg-3 wow bounceInUp" data-wow-delay="0.1s">
                                        <div class="event-img position-relative">
                                            <img class="img-fluid rounded w-100"
                                                src="{{ asset('storage/'.$event->image) }}" alt="{{ $event->name }}">
                                            <div class="event-overlay d-flex flex-column p-4">
                                                <h4 class="me-auto">{{ $event->name }}</h4>
                                                <a href="{{ asset('storage/'.$event->image) }}"
                                                    data-lightbox="event-1" class="my-auto"><i
                                                        class="fas fa-search-plus text-dark fa-2x"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-2" class="tab-pane fade show p-0">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="row g-4">
                                    @foreach ($event_bazar as $bazar)
                                    <div class="col-sm-12 col-md-6 col-lg-3">
                                        <div class="event-img position-relative">
                                            <img class="img-fluid rounded w-100"
                                                src="{{ asset('storage/'.$bazar->image) }}" alt="{{ $bazar->name }}">
                                            <div class="event-overlay d-flex flex-column p-4">
                                                <h4 class="me-auto">{{ $bazar->name }}</h4>
                                                <a href="{{ asset('storage/'.$bazar->image) }}"
                                                    data-lightbox="event-8" class="my-auto"><i
                                                        class="fas fa-search-plus text-dark fa-2x"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-3" class="tab-pane fade show p-0">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="row g-4">
                                    @foreach ($event_live_musik as $live_musik)
                                    <div class="col-sm-12 col-md-6 col-lg-3">
                                        <div class="event-img position-relative">
                                            <img class="img-fluid rounded w-100"
                                                src="{{ asset('storage/'.$live_musik->image) }}" alt="{{ $live_musik->name }}">
                                            <div class="event-overlay d-flex flex-column p-4">
                                                <h4 class="me-auto">{{ $live_musik->name }}</h4>
                                                <a href="{{ asset('storage/'.$live_musik->image) }}"
                                                    data-lightbox="event-3" class="my-auto"><i
                                                        class="fas fa-search-plus text-dark fa-2x"></i></a>
                                            </div>
                                        </div>
                                    </div>  
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-4" class="tab-pane fade show p-0">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="row g-4">
                                    @foreach ($event_nonton_bareng as $nonton_bareng)
                                    <div class="col-sm-12 col-md-6 col-lg-3">
                                        <div class="event-img position-relative">
                                            <img class="img-fluid rounded w-100"
                                                src="{{ asset('storage/'.$nonton_bareng->image) }}" alt="{{ $nonton_bareng->name }}">
                                            <div class="event-overlay d-flex flex-column p-4">
                                                <h4 class="me-auto">{{ $nonton_bareng->name }}</h4>
                                                <a href="{{ asset('storage/'.$nonton_bareng->image) }}"
                                                    data-lightbox="event-3" class="my-auto"><i
                                                        class="fas fa-search-plus text-dark fa-2x"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-5" class="tab-pane fade show p-0">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="row g-4">
                                    @foreach ($event_game_night as $game_night)
                                    <div class="col-sm-12 col-md-6 col-lg-3">
                                        <div class="event-img position-relative">
                                            <img class="img-fluid rounded w-100"
                                                src="{{ asset('storage/'.$game_night->image) }}" alt="{{ $game_night->name }}">
                                            <div class="event-overlay d-flex flex-column p-4">
                                                <h4 class="me-auto">{{ $game_night->name }}</h4>
                                                <a href="{{ asset('storage/'.$game_night->image) }}"
                                                    data-lightbox="event-3" class="my-auto"><i
                                                        class="fas fa-search-plus text-dark fa-2x"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
