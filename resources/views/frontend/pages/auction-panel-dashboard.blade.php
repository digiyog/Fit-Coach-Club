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

<body class="auction_background">

    <div class="main-auction" id="main-auction">
        <div class="header-container  auction-fixed">
            <header class="header navbar-expand-sm auction-heading text-center p-3">
                <h1 class="text-center">{{ ucwords($auctionDetails->name) }}</h1>
                <img src="{{ asset('admin-assets/images/player_auction.png') }}" class="m-auto p-2" height="60px">
            </header>
        </div>
    
        <div id="" class="main-content main-content-auction">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="user-profile">
                            <img src="{{ asset('admin-assets/images/logo-auction.png') }}" width="300px" class="" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="live-auction" id="live-auction" style="display: none;">
        <div class="header-container  auction-fixed">
            <header class="header navbar-expand-sm auction-heading text-center p-3">
                <h1 class="text-center">{{ ucwords($auctionDetails->name) }}</h1>
                <img src="{{ asset('admin-assets/images/player_auction.png') }}" class="m-auto p-2" height="60px">
            </header>
        </div>
    
        <div id="" class="main-content" style="width:100%;">
            <div class="container-fluid">

                <input type="hidden" id="player_sold_url" value="{{ url('/') }}/auction-panel/player-sold">
                <input type="hidden" id="player_sold_update_url" value="{{ url('/') }}/auction-panel/player-sold-update">
                <input type="hidden" name="" id="auction_id" value="{{ $auctionDetails->id }}">
                <input type="hidden" name="" id="current_player" value="0">
                <input type="hidden" name="" id="current_team" value="0">
                <input type="hidden" name="" id="sold_player" value="0">
                <input type="hidden" name="" id="remainingPlayerIds" value="{{ json_encode($remainingPlayerIds) }}">

                @foreach($remainingPlayers as $players)
                <div class="player-div" style="display:none;" data-player-id="{{ $players->id }}">
                    <div class="row">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-4 layout-spacing">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="user-profile">
                                        <div class="widget-content widget-content-area">
                                            @php
                                                $imagePath = asset('admin-assets/images/player.png');
                                                if (!empty($players->image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.players.image_path').$players->image)) {
                                                    $imagePath = (get_image_url(config('constants.players.image_path'), $players->image) ?? '');
                                                }
                                            @endphp

                                            <div class="text-center user-info mt-3">
                                                <img src="{{ $imagePath }}" width="100px" alt="avatar">
                                                <p class="">{{ ucwords($players->name) }}</p>
                                            </div>
                                            <div class="user-info-list">
                                                <ul class="contacts-block list-unstyled m-0 text-center" style="max-width:100%">
                                                    @if($players->age)
                                                        <li class="contacts-block__item">
                                                            <b>Age : </b> {{ $players->age }}
                                                        </li>
                                                    @endif

                                                    @if($players->category_id)
                                                        <li class="contacts-block__item">
                                                            <b>Category : </b> {{ ucwords($players['categories']->name) }}
                                                        </li>
                                                    @endif

                                                    @if($players->tag)
                                                        <li class="contacts-block__item">
                                                            <b>Tag : </b> 
                                                            @if($players->tag == 1)
                                                                Owner
                                                            @elseif($players->tag == 2)
                                                                Co-Owner
                                                            @elseif($players->tag == 3)
                                                                Captain
                                                            @elseif($players->tag == 4)
                                                                Vice Captain
                                                            @elseif($players->tag == 5)
                                                                Icon
                                                            @elseif($players->tag == 6)
                                                                Retain
                                                            @endif
                                                        </li>
                                                    @endif

                                                    @if($players->playing_style)
                                                        <li class="contacts-block__item">
                                                            <b>Playing Style : </b> {{ $players->playing_style }}
                                                        </li>
                                                    @endif

                                                    @if($players->specification_1)
                                                        <li class="contacts-block__item">
                                                            <b>Specification 1 : </b> {{ $players->specification_1 }}
                                                        </li>
                                                    @endif

                                                    @if($players->specification_2)
                                                        <li class="contacts-block__item">
                                                            <b>Specification 2 : </b> {{ $players->specification_2 }}
                                                        </li>
                                                    @endif

                                                    @if($players->specification_3)
                                                        <li class="contacts-block__item">
                                                            <b>Specification 3 : </b> {{ $players->specification_3 }}
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div id="sold_image_{{ $players->id }}" style="display:none;">
                                <img src="{{ asset('admin-assets/images/soldstamp.png') }}">
                            </div>
                            <div id="unsold_image_{{ $players->id }}" style="display:none;">
                                <img src="{{ asset('admin-assets/images/UNSOLDstamp.png') }}">
                            </div>
                        </div>

                        @if($players['categories']['base_bid'] != '')
                            <input type="hidden" name="" id="current_bid_{{ $players->id }}" value="{{ $players['categories']['base_bid'] }}">
                            <input type="hidden" name="" id="bid_increase_{{ $players->id }}" value="{{ $players['categories']['increment'] }}">
                        @else
                            <input type="hidden" name="" id="current_bid_{{ $players->id }}" value="{{ $auctionDetails['base_bid'] }}">
                            <input type="hidden" name="" id="bid_increase_{{ $players->id }}" value="{{ $auctionDetails['bid_increase'] }}">
                        @endif

                        <input type="hidden" name="" id="point_per_team_{{ $players->id }}" value="{{ $auctionDetails['point_per_team'] }}">

                        <div class="col-lg-5 pr-3 layout-spacing" style="text-align: end; align-self: start;">

                            <div>
                                @if($players['categories']['base_bid'] != '')
                                    <button class="btn btn-warning mb-2 mr-2">
                                        <h1 class="font-size-18 p-0 m-0" id="current_bid_text_{{ $players->id }}"> Current Bid : {{ $players['categories']['base_bid'] }}</h1>
                                    </button><br>
                                @else
                                    <button class="btn btn-warning mb-2 mr-2">
                                        <h1 class="font-size-18 p-0 m-0" id="current_bid_text_{{ $players->id }}">Current Bid : {{ $auctionDetails['base_bid'] }}</h1>
                                    </button><br>
                                @endif
                            </div>

                            <div class="mt-3 mb-3">
                                <img src="{{ asset('admin-assets/images/logo-auction.png') }}" width="150px" class="" alt="...">
                            </div>
                            
                            <div>
                                <button class="btn btn-warning mb-2 mr-2">Point Per Team : {{ $auctionDetails['point_per_team'] }}</button><br>

                                @if($players['categories']['base_bid'] != '')
                                    <button class="btn btn-warning mb-2 mr-2">Base Value : {{ $players['categories']['base_bid'] }}</button><br>
                                    <button class="btn btn-warning mb-2 mr-2">Bid Increase : {{ $players['categories']['increment'] }}</button><br>
                                @else
                                    <button class="btn btn-warning mb-2 mr-2">Base Value : {{ $auctionDetails['base_bid'] }}</button><br>
                                    <button class="btn btn-warning mb-2 mr-2">Bid Increase : {{ $auctionDetails['bid_increase'] }}</button><br>
                                @endif
                                
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="all-teams" id="all-teams" style="display: none;">

        <div class="header-container  auction-fixed">
            <header class="header navbar-expand-sm auction-heading text-center p-3">
                <h1 class="text-center">{{ ucwords($auctionDetails->name) }}</h1>
                <img src="{{ asset('admin-assets/images/player_auction.png') }}" class="m-auto p-2" height="60px">
            </header>
        </div>

        <!-- Include Content  -->
        <div id="" class="main-content">
            <div class="container-fluid">
                @if(!empty($auctionDetails))
                <div class="row">
                    <div class="col-lg-12 layout-spacing">
                        <div class="row">
                            @foreach($allTeams as $team)
                                <div class="col-lg-4 mb-4">
                                    <a href="{{ url('team-details') }}/{{ ev($team->id) }}" target="_blank">
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
                                                        <p class="card-user_occupation">Players : {{ $team->auction_players_count }}/{{ $auctionDetails->maximum_player }}</p>
                                                        <div class="card-star_rating">
                                                           <span class="progress-bar bg-gradient-primary">Total Points : {{ $auctionDetails->point_per_team }}</span>
                                                        </div>
                                                        <div class="card-star_rating">
                                                           <span class="progress-bar bg-gradient-warning">Used Points : {{ $team->total_points }}</span>
                                                        </div>
                                                        <div class="card-star_rating">
                                                           <span class="progress-bar bg-gradient-danger">Remaining Points : {{ $auctionDetails->point_per_team - $team->total_points }}</span>
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

    </div>

    <div class="all-players" id="all-players" style="display: none;">

        <div class="header-container auction-fixed">
            <header class="header navbar-expand-sm auction-heading text-center p-3">
                <h1 class="text-center">{{ ucwords($auctionDetails->name) }}</h1>
                <img src="{{ asset('admin-assets/images/player_auction.png') }}" class="m-auto p-2" height="60px">

                <div class="ml-3 mt-3">
                    <button class="btn btn-primary" id="allPLayers" title="All Players">All Players</button>
                    <button class="btn btn-warning ml-3 mr-3" id="soldPlayers" title="Sold Players">Sold Players</button>
                    <button class="btn btn-secondary" id="unSoldPlayers" title="UnSold Players">UnSold Players</button>
                </div>
            </header>
        </div>

        <!-- Include Content  -->
        <div id="" class="main-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 layout-spacing">
                        <div class="row">
                            @foreach($allPlayers as $players)

                                @php
                                    if($players['auctionPlayers']['status'] == 1){
                                        $auctionClass = 'soldPlayers';
                                    } else if($players['auctionPlayers']['status'] == 2){
                                        $auctionClass = 'unSoldPlayers';
                                    } else {
                                        $auctionClass = 'allPlayers';
                                    }
                                @endphp
                                <div class="col-sm-4 {{ $auctionClass }}">
                                    <div class="user-profile layout-spacing">
                                        <div class="widget-content widget-content-area position-relative">

                                            @if($players['auctionPlayers']['status'] == 1)
                                                <div class="position-absolute auction_player_id_{{ $players['auctionPlayers']['id'] }}">
                                                    <img src="{{ asset('admin-assets/images/goldensold.jpg') }}" width="150px">
                                                </div>
                                            @elseif($players['auctionPlayers']['status'] == 2)
                                                <div class="position-absolute auction_player_id_{{ $players['auctionPlayers']['id'] }}">
                                                    <img src="{{ asset('admin-assets/images/redunsold.jpg') }}" width="150px">
                                                </div>
                                            @else
                                            @endif

                                            @php
                                                $imagePath = asset('admin-assets/images/player.png');
                                                if(!empty($players->image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.players.image_path').$players->image))
                                                {
                                                    $imagePath = (get_image_url(config('constants.players.image_path'), $players->image) ?? '');
                                                }
                                            @endphp

                                            <div class="text-center user-info mt-3">
                                                <img src="{{ $imagePath }}" width="100px" alt="avatar">
                                                <p class="">{{ ucwords($players->name) }}</p>
                                            </div>

                                            @if($players['auctionPlayers']['status'] == 1)
                                                <div class="text-center mt-0">
                                                    <p><b>Team : </b>{{ $players['auctionPlayers']['team']['name'] }}  - {{ $players['auctionPlayers']['points'] }} Points</p>
                                                </div>
                                            @endif

                                            <div class="user-info-list">
                                                <ul class="contacts-block list-unstyled m-0 text-center" style="max-width:100%">
                                                    
                                                    @if($players->age)
                                                        <li class="contacts-block__item">
                                                            <b>Age : </b> {{ $players->age }}
                                                        </li>
                                                    @endif

                                                    @if($players->category_id)
                                                        <li class="contacts-block__item">
                                                            <b>Category : </b> {{ ucwords($players['categories']->name) }}
                                                        </li>
                                                    @endif

                                                    @if($players->tag)
                                                        <li class="contacts-block__item">
                                                            <b>Tag : </b> 
                                                            @if($players->tag == 1)
                                                                Owner
                                                            @elseif($players->tag == 2)
                                                                Co-Owner
                                                            @elseif($players->tag == 3)
                                                                Captain
                                                            @elseif($players->tag == 4)
                                                                Vice Captain
                                                            @elseif($players->tag == 5)
                                                                Icon
                                                            @elseif($players->tag == 6)
                                                                Retain
                                                            @endif
                                                        </li>
                                                    @endif

                                                    @if($players->playing_style)
                                                        <li class="contacts-block__item">
                                                            <b>Playing Style : </b> {{ $players->playing_style }}
                                                        </li>
                                                    @endif

                                                    @if($players->specification_1)
                                                        <li class="contacts-block__item">
                                                            <b>Specification 1 : </b> {{ $players->specification_1 }}
                                                        </li>
                                                    @endif

                                                    @if($players->specification_2)
                                                        <li class="contacts-block__item">
                                                            <b>Specification 2 : </b> {{ $players->specification_2 }}
                                                        </li>
                                                    @endif

                                                    @if($players->specification_3)
                                                        <li class="contacts-block__item">
                                                            <b>Specification 3 : </b> {{ $players->specification_3 }}
                                                        </li>
                                                    @endif

                                                    @if($players['auctionPlayers']['status'] == 1)
                                                        <li class="contacts-block__item auction_player_button_{{ $players['auctionPlayers']['id'] }}">
                                                            <button class="btn btn-secondary" id="unSoldAuctionPlayers" auction_player_id={{$players['auctionPlayers']['id']}} title="UnSold Players">UnSold Players</button>
                                                        </li>
                                                    @endif
                                                </ul>                              
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Include Content  -->

    </div>


    <div class="auction-footer">
        <div class="ml-3">
            <div id="autionplayerright" style="display:none;">
                <button class="btn btn-primary" id="newPlayer" title="New Player">New Player</button>

                @foreach($allTeams as $team)
                    <button class="btn btn-warning" id="teamShortName" team_id="{{ ucwords($team->id) }}" title="{{ ucwords($team->name) }}">{{ ucwords($team->short_name) }}</button>

                    <input type="hidden" id="maximum_player_{{ $team->id }}" value="{{ $auctionDetails->maximum_player }}">
                    <input type="hidden" id="purchased_player_{{ $team->id }}" value="{{ $team->auction_players_count }}">
                    <input type="hidden" id="total_points_{{ $team->id }}" value="{{ $auctionDetails->point_per_team }}">
                    <input type="hidden" id="used_points_{{ $team->id }}" value="{{ $team->total_points }}">
                    <input type="hidden" id="remaining_points_{{ $team->id }}" value="{{ $auctionDetails->point_per_team - $team->total_points }}">
                @endforeach

                <button class="btn btn-success soldPlayer" id="soldPlayer" title="Sold">Sold</button>
                <button class="btn btn-danger unsoldPlayer" id="unsoldPlayer" title="UnSold">UnSold</button>
            </div>
        </div>
        <div class="mr-3">
            <button class="btn btn-primary" id="auctionScreen" title="Auction">A</button>
            <button class="btn btn-warning" id="auctionSummary" title="Summary">S</button>
            <button class="btn btn-secondary" id="auctionPlayer" title="Players">P</button>
            <button class="btn btn-success" id="fullscreenBtn" title="Full Screen">F</button>
        </div>
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
    <script src="{{ asset('admin-assets/js/auctions/auction-panel.js') }}"></script>

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
