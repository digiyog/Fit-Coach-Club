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
/*            background-color: rgba(0, 0, 0, 0.6);*/
            background-position: center center;
        }
        /* Dark overlay */
        /*body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Adjust opacity here */
/*            z-index: 1;*/
/*        }*/
        .navbar{
/*            background: rgba(0, 0, 0, 0.3) !important;*/
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
                @if(!empty($auction))
                <div class="row">
                    <div class="col-lg-3 layout-spacing">
                        <div class="row">
                            <div class="col-12">
                                <div class="statbox widget box box-shadow">
                                    
                                    <input type="hidden" value="{{ $auction->id }}" id="auction_id">
                                    <input type="hidden" value="{{ url('/') }}/auction-last-player" id="last_auction_url">

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
                                                    <label class="badge badge-primary text-white">Avialable : {{ $playerCount }}</label>
                                                    <label class="badge badge-warning text-white">Sold : {{ $soldPlayers }}</label>
                                                    <label class="badge badge-info text-white">UnSold : {{ $playerCount - $soldPlayers }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-12 layout-top-spacing auction-live-layout">
                                @if(!empty($lastAuctionPlayer))
                                    <div class="user-profile layout-spacing">
                                        <div class="widget-content widget-content-area position-relative">

                                            @if($lastAuctionPlayer['status'] == 1)
                                                <div class="position-absolute" style="top: 55px; right: 10px; transform: rotate(-35deg);">
                                                    <img src="{{ asset('admin-assets/images/goldensold.jpg') }}" width="150px">
                                                </div>
                                            @elseif($lastAuctionPlayer['status'] == 2)
                                                <div class="position-absolute" style="top: 55px; right: 10px; transform: rotate(-35deg);">
                                                    <img src="{{ asset('admin-assets/images/redunsold.jpg') }}" width="150px">
                                                </div>
                                            @else
                                            @endif

                                            @php
                                                $imagePath = asset('admin-assets/images/player.png');
                                                if(!empty($lastAuctionPlayer['player']->image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.players.image_path').$lastAuctionPlayer['player']->image))
                                                {
                                                    $imagePath = (get_image_url(config('constants.players.image_path'), $lastAuctionPlayer['player']->image) ?? '');
                                                }
                                            @endphp

                                            <div class="text-center user-info mt-3">
                                                <img src="{{ $imagePath }}" width="100px" alt="avatar">
                                                <p class="">{{ ucwords($lastAuctionPlayer['player']->name) }}</p>
                                            </div>

                                            @if($lastAuctionPlayer['status'] == 1)
                                                <div class="text-center mt-0">
                                                    <p><b>Team : </b>{{ $lastAuctionPlayer['team']['name'] }}  - {{ $lastAuctionPlayer['points'] }} Points</p>
                                                </div>
                                            @endif
            
                                            <div class="user-info-list">
                                                <ul class="contacts-block list-unstyled m-0 text-center" style="max-width:100%">
                                                    @if($lastAuctionPlayer['player']->age)
                                                        <li class="contacts-block__item">
                                                            <b>Age : </b> {{ $lastAuctionPlayer['player']->age }}
                                                        </li>
                                                    @endif

                                                    @if($lastAuctionPlayer['player']->category_id)
                                                        <li class="contacts-block__item">
                                                            <b>Category : </b> {{ ucwords($lastAuctionPlayer['player']['categories']->name) }}
                                                        </li>
                                                    @endif

                                                    @if($lastAuctionPlayer['player']->tag)
                                                        <li class="contacts-block__item">
                                                            <b>Tag : </b> 
                                                            @if($lastAuctionPlayer['player']->tag == 1)
                                                                Owner
                                                            @elseif($lastAuctionPlayer['player']->tag == 2)
                                                                Co-Owner
                                                            @elseif($lastAuctionPlayer['player']->tag == 3)
                                                                Captain
                                                            @elseif($lastAuctionPlayer['player']->tag == 4)
                                                                Vice Captain
                                                            @elseif($lastAuctionPlayer['player']->tag == 5)
                                                                Icon
                                                            @elseif($lastAuctionPlayer['player']->tag == 6)
                                                                Retain
                                                            @endif
                                                        </li>
                                                    @endif

                                                    @if($lastAuctionPlayer['player']->playing_style)
                                                        <li class="contacts-block__item">
                                                            <b>Playing Style : </b> {{ $lastAuctionPlayer['player']->playing_style }}
                                                        </li>
                                                    @endif

                                                    @if($lastAuctionPlayer['player']->specification_1)
                                                        <li class="contacts-block__item">
                                                            <b>Specification 1 : </b> {{ $lastAuctionPlayer['player']->specification_1 }}
                                                        </li>
                                                    @endif

                                                    @if($lastAuctionPlayer['player']->specification_2)
                                                        <li class="contacts-block__item">
                                                            <b>Specification 2 : </b> {{ $lastAuctionPlayer['player']->specification_2 }}
                                                        </li>
                                                    @endif

                                                    @if($lastAuctionPlayer['player']->specification_3)
                                                        <li class="contacts-block__item">
                                                            <b>Specification 3 : </b> {{ $lastAuctionPlayer['player']->specification_3 }}
                                                        </li>
                                                    @endif
                                                    
                                                </ul>                             
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 layout-spacing">
                        <div class="row">
                            @foreach($allTeams as $team)
                                <div class="col-lg-4 mb-4 pl-0">
                                    <a href="{{ url('team-details') }}/{{ ev($team->id) }}">
                                        <div class="statbox widget box box-shadow">
                                            <div class="card component-card_4" style="width:100%;">
                                                <div class="card-body align-items-center">

                                                    @php
                                                        $imagePath = asset('admin-assets/images/player.png');
                                                        if(!empty($team->image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.teams.image_path').$team->image))
                                                        {
                                                            $imagePath = (get_image_url(config('constants.teams.image_path'), $team->image) ?? '');
                                                        }
                                                    @endphp

                                                    <div class="user-profile">
                                                        <img src="{{ $imagePath }}" width="100px" class="" alt="...">
                                                    </div>
                                                    <div class="user-info pb-0">
                                                        <h5 class="card-user_name">{{ ucwords($team->name) }}</h5>
                                                        <p class="card-user_occupation">Players : {{ $team->auction_players_count }}/{{ $auction->maximum_player }}</p>
                                                        <div class="card-star_rating">
                                                           <span class="progress-bar bg-gradient-primary">Total Points : {{ $auction->point_per_team }}</span>
                                                        </div>
                                                        <div class="card-star_rating">
                                                           <span class="progress-bar bg-gradient-warning">Used Points : {{ $team->total_points }}</span>
                                                        </div>
                                                        <div class="card-star_rating">
                                                           <span class="progress-bar bg-gradient-danger">Remaining Points : {{ $auction->point_per_team - $team->total_points }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
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

    <script type="text/javascript">
        
        // last Auction Details
        function lastAuctionDetails(){
            var id  = $('#auction_id').val();
            var URL = $('#last_auction_url').val();
            URL = URL+'?auction_id='+id;

            $.getJSON(URL, function (response) {
                // if (response._status === false) {
                //     setTimeout(abouts, 1000);
                // } else {
                //     // Hide the loading spinner and show the top things section
                    $('.auction-live-layout').html(response._data);
                //     $('#search_about').val(1);
                // }
                setTimeout(lastAuctionDetails, 3000);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                // console.error("Request failed: " + textStatus, errorThrown);
                // // Retry after 3 seconds if the request fails
                setTimeout(lastAuctionDetails, 3000);
            });
        }

        lastAuctionDetails();

    </script>

    @stack('scripts')
    <!-- / Scripts -->

</body>
</html>
