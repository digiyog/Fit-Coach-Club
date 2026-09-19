@extends('frontend.layouts.main-layout')

@section('content')
    
<main>
    <section class="thank-section bg-primary-color">
        <div class="container">
            <div class="col-lg-7 mx-auto text-center">
                <img src="{{ asset('frontend/images/thank.svg') }}" width="60" class="ms-2 d-lg-none mb-2" alt="">
                <h1 class="main-heading mb-40">Thank you <img src="{{ asset('frontend/images/thank.svg') }}" width="80" class="ms-2 d-none d-lg-inline-block" alt="">
                </h1>
                <p class="content label-white">The confirmation message has been sent to your email address. If you
                    didn’t receive one, check your spam.</p>
                <span class="fs-16 fw-400 lh-24 label-grey">If you think you’ve add something wrong or incorrect to
                    the form, feel free to resubmit
                    it.</span>
            </div>
        </div>
    </section>
    <section class="expect-next-section bg-secondary-color">
        <div class="container">
            <div class="col-lg-8 mx-auto">
                <h2 class="section-heading text-center mb-60">What to expect next:</h2>
                <div class="card thank-card position-relative">
                    <div class="card-body">
                        <ul class="list-unstyled ps-0 mb-0">
                            <li class="mb-4 d-flex align-items-center fw-700">
                                <span class="count-box fs-24 fw-700 me-3 me-lg-4">1</span>
                                In a moment you will start getting quotes from our dentists.
                            </li>
                            <li class="mb-4 d-flex align-items-center fw-700">
                                <span class="count-box fs-24 fw-700 me-3 me-lg-4">2</span>
                                Terms and conditions can be agreed with them.
                            </li>
                            <li class="mb-4 d-flex align-items-center fw-700">
                                <span class="count-box fs-24 fw-700 me-3 me-lg-4">3</span>
                                Choose the best option.
                            </li>
                            <li class="mb-4 d-flex align-items-center fw-700">
                                <span class="count-box fs-24 fw-700 me-3 me-lg-4">4</span>
                                Enjoy your smile :)
                            </li>
                        </ul>
                        <div>
                            <img src="{{ asset('frontend/images/thank-card.svg') }}" width="146" class="position-absolute card-thank-img"
                                alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    @include('frontend.pages.reviews')

</main>

@endsection