<section id="chef-team">
    <div class="container-fluid team py-6">
        <div class="container">
            <div class="text-center wow bounceInUp">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Tim
                    Kami</small>
                <h1 class="display-5 mb-5">Tim Profesional Kami</h1>
            </div>
            <div class="row g-4">

                @foreach ($chefs as $chef)
                    <div class="col-sm-12 col-md-6 col-lg-3 wow bounceInUp">
                        <div class="team-item rounded">
                            <img class="img-fluid rounded-top" src="{{ asset('storage/' . $chef->photo) }}"
                                alt="{{ $chef->name }}">
                            <div class="team-content text-center py-3 bg-dark rounded-bottom">
                                <h4 class="text-primary">{{ $chef->name }}</h4>
                                <p class="text-white mb-0">{{ $chef->position }}</p>
                            </div>
                            <div class="team-icon d-flex flex-column justify-content-center m-4">
                                <a class="share btn btn-primary btn-md-square rounded-circle mb-2" href=""><i
                                        class="fas fa-share-alt"></i></a>
                                <a class="share-link btn btn-primary btn-md-square rounded-circle mb-2"
                                    href="{{ $chef->fb_link }}"><i class="fab fa-facebook-f"></i></a>
                                <a class="share-link btn btn-primary btn-md-square rounded-circle mb-2"
                                    href="{{ $chef->linkedin_link }}"><i class="fab fa-linkedin"></i></a>
                                <a class="share-link btn btn-primary btn-md-square rounded-circle mb-2"
                                    href="{{ $chef->insta_link }}"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</section>
