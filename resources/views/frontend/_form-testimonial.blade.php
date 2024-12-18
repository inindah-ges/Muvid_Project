<div class="container-fluid contact py-3 wow bounceInUp">
    <div class="container">
        <div class="p-5 rounded contact-form">
            <div class="row g-4">
                <div class="col-12 text-center pb-3">
                    <small
                        class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">form
                        testimonial</small>
                    <h1 class="display-5 mb-0">Berikan Testimonial Untuk Kami</h1>
                </div>


                <div class="card border-0">
                    <div class="card-body">
                        <form action="{{ route('frontend.service.store') }}" method="post">
                            @csrf
                            @if (session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                    <strong>Success!</strong> {{ session()->get('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @elseif (session()->has('error'))
                                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                    {{ session()->get('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <div class="mb-3">
                                <label for="invoice" class="form-label">Order Invoice</label>
                                <input type="text" name="invoice" id="invoice" placeholder="Your Order Invoice"
                                    class="form-control @error('invoice') is-invalid @enderror"
                                    value="{{ old('invoice') }}">

                                @error('invoice')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="rate" class="form-label">Rating (1-5)</label>
                                <select name="rate" id="rate"
                                    class="form-select @error('rate') is-invalid @enderror" value="{{ old('rate') }}">
                                    <option value="" hidden>== Select Rating ==</option>
                                    <option value="1">&#9733;</option>
                                    <option value="2">&#9733;&#9733;</option>
                                    <option value="3">&#9733;&#9733;&#9733;</option>
                                    <option value="4">&#9733;&#9733;&#9733;&#9733;</option>
                                    <option value="5">&#9733;&#9733;&#9733;&#9733;&#9733;</option>
                                </select>

                                @error('rate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="comment" class="form-label">Comment</label>
                                <textarea name="comment" id="comment" cols="5" rows="5" placeholder="Write your comment here"
                                    class="form-control"></textarea>
                            </div>

                            <div class="float-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
