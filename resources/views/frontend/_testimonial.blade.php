<section id="testimonial">
    <div class="container-fluid py-6">
        <div class="container">
            <div class="text-center wow bounceInUp">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Testimonial</small>
                <h1 class="display-5 mb-5">Apa Kata Pelanggan Kami!</h1>
            </div>
            <div class="owl-carousel owl-theme testimonial-carousel testimonial-carousel-1 mb-4 wow bounceInUp">
                @foreach ($testimonials as $testimonial)
                <div class="testimonial-item rounded bg-light">
                    <div class="d-flex mb-3">
                        <img src="{{ asset('frontend/assets') }}/img/test.jpg"
                            class="img-fluid rounded-circle flex-shrink-0" alt="">
                        <div class="position-absolute" style="top: 15px; right: 20px;">
                            <i class="fa fa-quote-right fa-2x"></i>
                        </div>
                        <div class="ps-3 my-auto">
                            <h4 class="mb-0">{{ $testimonial->selling->user->name }}</h4>
                            <p class="m-0">{{ $testimonial->selling->user->email }}</p>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <div class="stars">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $testimonial->rate)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @else
                                    <i class="bi bi-star text-warning"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="fs-5 m-0 pt-3">{{ $testimonial->comment }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

