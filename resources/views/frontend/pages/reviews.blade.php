@if($reviews[0] != '')
    <section class="reviews-section">
        <div class="container">
            <div class="col-lg-9 mx-auto">
                <h2 class="section-heading2 text-center mb-60">
                    <span class="d-block mb-4">Reviews</span>
                    What our clients say
                </h2>
            </div>
            <div class="review_slider owl-carousel">
                @foreach($reviews as $review)
                    <div class="item card border-0 review-card mx-5 mx-md-4 mx-lg-3">
                        <div class="card-body">
                            <img src="{{ asset('frontend/images/quotes.svg') }}" height="46" class="mb-4" alt="">
                            <p class="fs-16 lh-24 fw-400 text-center label-dark-white mb-4">
                                {{ $review['description'] }}
                            </p>
                        </div>
                        <div class="card-footer bg-transparent p-0 border-0">
                            <h4 class="fs-16 fw-600 lh-24 label-white text-center">{{ $review['title'] }}</h4>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif