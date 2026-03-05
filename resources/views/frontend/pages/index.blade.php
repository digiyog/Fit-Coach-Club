@extends('frontend.layouts.main-layout')
@section('content')

    <section class="p-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-end">
                    <img class="pt-7 pt-md-0 img-fluid" src="{{ asset('frontend/images/hero_img.svg') }}" alt="{{ env('APP_NAME') }}">
                </div>
                <div class="col-md-6 text-md-start text-center py-6">
                    <h1 class="mb-4 fs-7 fw-bold">Experience the Thrill: Tournament Auctions Available</h1>
                    <p class="mb-6 lead text-secondary">Join us at FPLSpotz and unlock a world of possibilities where players are not just acquired but celebrated. Get ready to embark on an extraordinary journey that will elevate your player auction experiences to new heights.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="pt-3 mb-6" id="howworks">
        <div class="bg-holder z-index--1 bottom-0 d-none d-lg-block auctionFeatures"></div>
        <div class="container">
            <h2 class="fs-9 fw-bold mb-4 text-center"> Auction Features</h2>
            <div class="row text-center">
                <div class="col-lg-3 col-sm-6 mb-2">
                    <img class="mb-3 ms-n3" src="{{ asset('frontend/images/icon1.webp') }}" width="75" alt="{{ env('APP_NAME') }}">
                    <h4 class="mb-3">Select your sports</h4>
                    <p class="mb-0 fw-medium text-secondary">Choose your preferred sports, eg. cricket, football, etc.</p>
                </div>
                <div class="col-lg-3 col-sm-6 mb-2">
                    <img class="mb-3 ms-n3" src="{{ asset('frontend/images/icon2.webp') }}" width="75" alt="{{ env('APP_NAME') }}">
                    <h4 class="mb-3">Registration</h4>
                    <p class="mb-0 fw-medium text-secondary">Get your tournament, teams and players registered with us.</p>
                </div>
                <div class="col-lg-3 col-sm-6 mb-2">
                    <img class="mb-3 ms-n3" src="{{ asset('frontend/images/icon3.webp') }}" width="75" alt="{{ env('APP_NAME') }}">
                    <h4 class="mb-3">View players &amp; teams</h4>
                    <p class="mb-0 fw-medium text-secondary">Review the teams and players registered to the website.</p>
                </div>
                <div class="col-lg-3 col-sm-6 mb-2">
                    <img class="mb-3 ms-n3" src="{{ asset('frontend/images/icon4.png') }}" width="75" alt="{{ env('APP_NAME') }}">
                    <h4 class="mb-3">Initiate auction</h4>
                    <p class="mb-0 fw-medium text-secondary">Conduct the tournament auction using admin login.</p>
                </div>
            </div>
            <div class="text-center">&nbsp;<br>&nbsp;</div>
        </div>
    </section>

    <section class="pt-5" id="auction">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h5 class="text-secondary">Effortless Validation for</h5>
                    <h2 class="mb-2 fs-7 fw-bold">Tournament Organizers</h2>
                    <p class="mb-4 fw-medium text-secondary">
                        The tournament registration page is a simple form that asks the organiser to add tournament details and logo.
                    </p>
                    <h4 class="fs-1 fw-bold">Team registration</h4>
                    <p class="mb-4 fw-medium text-secondary">The tournament organizer can add the teams taking part in the<br>tournament including the team logo and manager name.</p>
                    <h4 class="fs-1 fw-bold">Player registration</h4>
                    <p class="mb-4 fw-medium text-secondary">This will be a public URL that the organizer could share with their players for the tournament registration.</p>
                    <h4 class="fs-1 fw-bold">View Registered players</h4>
                    <p class="mb-4 fw-medium text-secondary">There will be a page listing all the players registered for the tournament with their pic and profile info.</p>
                </div>
                <div class="col-lg-6">
                    <img class="img-fluid" src="{{ asset('frontend/images/validation.svg') }}" alt="validation">
                </div>
            </div>
        </div>
        <!-- end of .container-->
    </section>
    <section class="pt-5" id="manager">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <img class="img-fluid" alt="manager" src="{{ asset('frontend/images/manager.svg') }}">
                </div>
                <div class="col-lg-6">
                    <h5 class="text-secondary">Easier decision making for</h5>
                    <p class="fs-7 fw-bold mb-2">Auction Manager</p>
                    <p class="mb-4 fw-medium text-secondary">
                        There is an admin portal available for the auction manager to login and start the auction process.
                    </p>
                    <div class="d-flex align-items-center mb-3">
                        <img class="me-sm-4 me-2" src="{{ asset('frontend/images/tick.png') }}" width="35" alt="{{ env('APP_NAME') }}">
                        <p class="fw-medium mb-0 text-secondary">Bidders would be randomly chosen by the auction manager through a CTA.</p>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <img class="me-sm-4 me-2" src="{{ asset('frontend/images/tick.png') }}" width="35" alt="{{ env('APP_NAME') }}">
                        <p class="fw-medium mb-0 text-secondary">The profile of the player to be bid would be displayed along with his base price.</p>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <img class="me-sm-4 me-2" src="{{ asset('frontend/images/tick.png') }}" width="35" alt="{{ env('APP_NAME') }}">
                        <p class="fw-medium mb-0 text-secondary">The bid made by each team owner for a player would be monitored by the auctioneer via a Hand Raise button.</p>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <img class="me-sm-4 me-2" src="{{ asset('frontend/images/tick.png') }}" width="35" alt="{{ env('APP_NAME') }}">
                        <p class="fw-medium mb-0 text-secondary">Player bid updates would be made available live and viewed by public users.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-md-11 py-8" id="pricing">
        <div class="bg-holder z-index--1 bottom-0 d-none d-lg-block background-position-top contact-background">
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 440px;">
                <div class="modal-content" style="border-radius:0px;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Contact Us</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 text-center">
                    <h2 class="fw-bold mb-4 fs-7">Join the Ultimate Tournament <br>Auction Platform</h2>
                    <p class="mb-5 text-info fw-medium">Are you a tournament organizer in search of assistance? Look no further! We are available online &amp; specialize in providing comprehensive support for tournament organizers, covering key aspects such as tournament auctions and player registration. We also offer a free auction demo.</p>
                    
                    <div class="modal-body p-4 border text-start">
                        <form method="post" id="contact-us" autocomplete="off">
                            <!-- Name input -->
                            <div class="form-outline form-group mb-2">
                                <label class="form-label" for="name4">Name</label>
                                <input type="text" id="name4" name="name" required="" class="form-control">
                            </div>
                            <!-- Email input -->
                            <div class="form-outline form-group mb-2">
                                <label class="form-label" for="email4">Email address</label>
                                <input type="email" id="email4" name="email" required="" class="form-control">
                            </div>
                            <!-- Mobile input -->
                            <div class="form-outline form-group mb-2">
                                <label class="form-label" for="number">Mobile Number</label>
                                <input type="number" id="number" name="mobile_number" required="" class="form-control">
                            </div>
                            <!-- textarea input -->
                            <div class="form-outline form-group mb-2">
                                <label class="form-label" for="textarea4">Your message</label>
                                <textarea id="textarea4" rows="4" required="" name="message" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block contact-form" style="margin-top: 10px;">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-3 mb-6" id="howworks">
        <div class="bg-holder z-index--1 bottom-0 d-none d-lg-block auctionFeatures"></div>
        <div class="container">
            <h2 class="fs-9 fw-bold mb-4 text-center"> Pricing</h2>
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="pricing-card red">
                            <h3>Free</h3>
                            <div class="price">Up to <span>02 Teams</span></div>
                            <div class="footer">Per Auction</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="pricing-card green">
                            <h3>Rs. 3000/-</h3>
                            <div class="price fw-medium">Up to <span>04 Teams</span></div>
                            <div class="footer">Per Auction</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="pricing-card yellow">
                            <h3>Rs. 4000/-</h3>
                            <div class="price">Up to <span>07 Teams</span></div>
                            <div class="footer">Per Auction</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="pricing-card blue">
                            <h3>Rs. 5000/-</h3>
                            <div class="price">Up to <span>12 Teams</span></div>
                            <div class="footer">Per Auction</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection