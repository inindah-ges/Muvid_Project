<section id="menu">
    <div class="container-fluid menu bg-light py-6">
        <div class="container">
            <div class="text-center wow bounceInUp">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Menu</small>
                <h1 class="display-5 mb-5">Menu Paling Populer di Cafe Kami</h1>
            </div>
            <div class="tab-class text-center">
                <ul class="nav nav-pills d-inline-flex justify-content-center mb-5 wow bounceInUp" data-wow-delay="0.1s">
                    <li class="nav-item p-2">
                        <a class="d-flex py-2 mx-2 border border-primary bg-white rounded-pill active"
                            data-bs-toggle="pill" href="#tab-6">
                            <span class="text-dark" style="width: 150px;">Coffee</span>
                        </a>
                    </li>
                    <li class="nav-item p-2">
                        <a class="d-flex py-2 mx-2 border border-primary bg-white rounded-pill" data-bs-toggle="pill"
                            href="#tab-7">
                            <span class="text-dark" style="width: 150px;">Mocktail</span>
                        </a>
                    </li>
                    <li class="nav-item p-2">
                        <a class="d-flex py-2 mx-2 border border-primary bg-white rounded-pill" data-bs-toggle="pill"
                            href="#tab-8">
                            <span class="text-dark" style="width: 150px;">Food</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="tab-6" class="tab-pane fade show p-0 active">
                        <div class="row g-4">
                            @foreach ($menu_coffee as $coffee)
                                <div class="col-sm-12 col-md-6 col-lg-6 wow bounceInUp" data-wow-delay="0.1s">
                                    <div class="menu-item d-flex align-items-center">
                                        <img class="flex-shrink-0 img-fluid rounded-circle"
                                            src="{{ asset('storage/' . $coffee->image) }}" width="70"
                                            alt="{{ $coffee->name }}">
                                        <div class="w-100 d-flex flex-column text-start ps-4">
                                            <div
                                                class="d-flex justify-content-between border-bottom border-primary pb-2 mb-2">
                                                <h4>{{ $coffee->name }}</h4>
                                                <h4 class="text-primary">Rp.
                                                    {{ number_format($coffee->price, 0, ',', '.') }}</h4>
                                            </div>
                                            <p class="mb-0">{{ Str::limit($coffee->description, 120) }}</p>
                                            <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $coffee->name }}">
                                                <input type="number" name="quantity" value="0" min="1"
                                                    class="form-control w-25 d-inline" style="display:inline-block;">
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-shopping-cart"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div id="tab-7" class="tab-pane fade show p-0">
                        <div class="row g-4">
                            @foreach ($menu_mocktail as $mocktail)
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="menu-item d-flex align-items-center">
                                        <img class="flex-shrink-0 img-fluid rounded-circle"
                                            src="{{ asset('storage/' . $mocktail->image) }}" width="70"
                                            alt="{{ $mocktail->name }}">
                                        <div class="w-100 d-flex flex-column text-start ps-4">
                                            <div
                                                class="d-flex justify-content-between border-bottom border-primary pb-2 mb-2">
                                                <h4>{{ $mocktail->name }}</h4>
                                                <h4 class="text-primary">Rp.
                                                    {{ number_format($mocktail->price, 0, ',', '.') }}</h4>
                                            </div>
                                            <p class="mb-0">{{ Str::limit($mocktail->description, 120) }}</p>
                                            <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $mocktail->name }}">
                                                <input type="number" name="quantity" value="0" min="1"
                                                    class="form-control w-25 d-inline" style="display:inline-block;">
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-shopping-cart"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div id="tab-8" class="tab-pane fade show p-0">
                        <div class="row g-4">
                            @foreach ($menu_food as $food)
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="menu-item d-flex align-items-center">
                                        <img class="flex-shrink-0 img-fluid rounded-circle"
                                            src="{{ asset('storage/' . $food->image) }}" width="70"
                                            alt="{{ $food->name }}">
                                        <div class="w-100 d-flex flex-column text-start ps-4">
                                            <div
                                                class="d-flex justify-content-between border-bottom border-primary pb-2 mb-2">
                                                <h4>{{ $food->name }}</h4>
                                                <h4 class="text-primary">Rp.
                                                    {{ number_format($food->price, 0, ',', '.') }}</h4>
                                            </div>
                                            <p class="mb-0">{{ Str::limit($food->description, 120) }}</p>
                                            <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $food->name }}">
                                                <input type="number" name="quantity" value="0" min="1"
                                                    class="form-control w-25 d-inline" style="display:inline-block;">
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-shopping-cart"></i></button>
                                            </form>
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
</section>

@if (session('success'))
    <script>
        alert('{{ session('success') }}');
    </script>
@endif
