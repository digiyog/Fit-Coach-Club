<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/images/favicon.png') }}">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescrption }}">
    <link rel="canonical" href="{{ url('/') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('frontend/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/custom.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.2/css/bootstrapValidator.min.css">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<script>
    var BASE_URL = "{{ url('/') }}";
</script>

<body>

    <main class="main" id="top">
        <!-- Header Top -->
        <div class="header-top sticky-top backdrop">
            <div class="container">
                <div class="left">
                    <i class="bi bi-phone"></i> 
                    <a href="tel:+1234567890">+1 234 567 890</a>
                </div>
                <div class="right">
                    <i class="bi bi-envelope"></i> 
                    <a href="mailto:support@example.com">support@example.com</a>
                </div>
            </div>
        </div>

        <nav class="navbar navbar-expand-lg navbar-light sticky-top backdrop" data-navbar-on-scroll="data-navbar-on-scroll" style="background-image: none; background-color: rgb(249, 250, 253);">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('frontend/images/logo.jpg') }}" height="80" alt="{{ env('APP_NAME') }}">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"> </span>
                </button>
                <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav m-auto">
                        <li class="nav-item px-3">
                            <a class="nav-link fw-bold" aria-current="page" href="{{ url('today-auctions') }}">Today Auctions</a>
                        </li>
                        <li class="nav-item px-3">
                            <a class="nav-link fw-bold" aria-current="page" href="{{ url('upcoming-auctions') }}">Upcoming Auctions</a>
                        </li>
                        <li class="nav-item px-3">
                            <a class="nav-link fw-bold" aria-current="page" href="{{ url('pricing') }}">Pricing</a>
                        </li>
                    </ul>
                </div>

                <a href="{{ url('login') }}">
                    <button class="btn btn-warning" type="button">
                        Login
                    </button>    
                </a>
                <a href="{{ url('register') }}">
                    <button class="btn btn-warning ms-3" type="button">
                        Register
                    </button>
                </a>

                <!-- <div class="dropdown">
                    <button class="btn btn-warning dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        My Profile
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="border-radius: 15px;">
                        <li>
                            <a class="dropdown-item" href="#">Dashboard</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">Profile</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">Logout</a>
                        </li>
                    </ul>
                </div> -->
            </div>
        </nav>

        @yield('content')
    
        <section class="text-center py-0">
            <div class="container">
                <div class="container border-top py-3">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-auto mb-1 mb-md-0">
                            <p class="mb-0">© {{ date('Y') }} {{ env('APP_NAME') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.2/js/bootstrapValidator.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('frontend/js/custom.js') }}"></script>
</body>

</html>