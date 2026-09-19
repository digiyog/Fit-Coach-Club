<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- / CSRF Token -->

    <meta http-equiv="cache-control" content="max-age=0"/>
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>

    <!-- Favicon -->
    <link rel="icon" href="" type="image/x-icon">
    <title>{{ $pageTitle }}</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet" />
    <link href="{{ asset('admin-assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/plugins.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/plugins/loaders/custom-loader.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/elements/alert.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/elements/tooltip.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/basscss.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/iziToast.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/components/cards/card.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-assets/css/users/user-profile.css') }}" rel="stylesheet" type="text/css" />
    @stack('styles')
    <!-- / Styles -->

    <style type="text/css">
        .background{
            background-image: url('public/frontend/images/banner.jpg');
            background-size: 100% 100%;
            background-position: center center;
        }
        .navbar{
            text-align: center;
        }
        .bg-gradient-danger {
            background-color: #1b55e2;
            background-image: linear-gradient(to right, #d09693 0%, #c71d6f 100%);
        }
        .bg-gradient-primary {
            background-color: #1b55e2;
            background: linear-gradient(to right, #0081ff 0%, #0045ff 100%);
        }
        .bg-gradient-warning {
            background-color: #1b55e2;
            background-image: linear-gradient(to right, #f09819 0%, #ff5858 100%);
        }
    </style>
</head>

<body class="background">

    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm">
            <a href="{{ url('/') }}" class="m-auto">
                <img src="{{ asset('frontend/images/logo.jpg') }}" class="m-auto p-2" height="100px">
            </a> 
        </header>
    </div>

    <div class="" id="">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!-- Include Content  -->
        <div id="" class="main-content" style="margin-top: 130px;">
            
            <div class="container-fluid">
                @if(!empty($auctions))
                <div class="row">

                    @foreach($auctions as $auction)
                        <div class="col-lg-4 layout-spacing">
                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ url('auction-panel/dashboard') }}/{{ ev($auction->id) }}">
                                        <div class="statbox widget box box-shadow">
                                            <div class="card component-card_4 pt-1 pb-1" style="width:100%;">
                                                <div class="card-body align-items-center">

                                                    @php
                                                        $auctionImage = asset('admin-assets/images/logo-auction.png');
                                                        if(!empty($auction->image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.auctions.image_path').$auction->image))
                                                        {
                                                            $auctionImage = get_image_url(config('constants.auctions.image_path'), $auction->image);
                                                        }
                                                    @endphp

                                                    <div class="user-profile">
                                                        <img src="{{ $auctionImage }}" width="150px" class="" alt="...">
                                                    </div>
                                                    <div class="user-info">
                                                        <h5 class="card-user_name">{{ ucwords($auction->name) }}</h5>
                                                        <div class="user-info-list">
                                                            <label class="badge badge-primary text-white">Date : {{ date('d-m-Y', strtotime($auction->date.' '.$auction->time)); }}</label>
                                                            <label class="badge badge-primary text-white">Time : {{ date('h:i A', strtotime($auction->date.' '.$auction->time)); }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        <!-- Include Content  -->

        <!-- Modal -->
        <div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-labelledby="pageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-loading"> Loading.... </div>
                </div>
            </div>
        </div>
        <!-- / Modal -->


        <!-- Medium Modal -->
        <div class="modal fade" id="pageModalMedium" tabindex="-1" role="dialog" aria-labelledby="pageModalMediumLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-loading"> Loading.... </div>
                </div>
            </div>
        </div>
        <!-- / Medium Modal -->

    </div>

    <!-- Scripts -->
    <script src="{{ asset('admin-assets/js/libs/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/jquery.validate.file.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/admin.js') }}"></script>
    <script src="{{ asset('admin-assets/js/custom.js') }}"></script>
    <script src="{{ asset('admin-assets/js/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/app.js') }}"></script>

    <script>
        window.addEventListener("load", function(){
            var load_screen = document.getElementById("load_screen");
            document.body.removeChild(load_screen);
        });
        $(document).ready(function() {
            Admin.init();
            feather.replace();
        });
    </script>

    <script>
        @if (Session::has('notification'))
            var notification = @json(Session::get('notification'));

            // Show notification
            $(document).ready(function () {
                App.showNotification(notification);
            });
            //------------------
        @endif
    </script>

    @stack('scripts')
    <!-- / Scripts -->

</body>
</html>
