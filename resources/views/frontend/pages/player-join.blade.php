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
    <link href="{{ asset('admin-assets/css/plugins/dropify/dropify.css') }}" rel="stylesheet">
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

        .dropify-wrapper
        {
            width:100% !important;
            margin-bottom: unset !important;
            height: 215px;
        }
        .textarea-height{
            size: none;
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

    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!-- Include sidebar  -->
        <!--  BEGIN SIDEBAR  -->
        <div class="sidebar-wrapper sidebar-theme d-none">
            <nav id="sidebar">
                <div class="shadow-bottom"></div>
                <ul class="list-unstyled menu-categories ps" id="accordionExample"></ul>
            </nav>
        </div>
        <!--  END SIDEBAR  -->
        <!--/ Include sidebar  -->

        <!-- Include Content  -->
        <div id="" class="main-content" style="width:100%;">
            <div class="layout-px-spacing" style="margin-top: 130px;">
                <div class="row layout-top-spacing">
                    <div class="col-xl-3 col-lg-3 col-md-3 col-12 layout-spacing" style="width:100%;">
                        <div class="statbox widget box box-shadow" style="width:100%;">
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
                    </div>

                    @if($auction->form_customization_status == 0)
                        <div class="col-xl-9 col-lg-9 col-md-9 col-12 layout-spacing text-center">
                            <div class="widget-content widget-content-area br-6">
                                <div class="container-fluid mt2">
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-12 text-center">
                                            <h4> Registration Closed </h4>
                                        </div>
                                    </div>

                                    <div class="form pb-2">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-xl-9 col-lg-9 col-md-9 col-12 layout-spacing">

                            <!-- Validation error -->
                            @component('auction-panel.validation.errors') @endcomponent
                            <!-- / Validation error -->

                            <div class="widget-content widget-content-area br-6">
                                <div class="container-fluid mt2">
                                    <div class="row">
                                        <div class="col-xl-8 col-lg-8 col-md-8 col-8">
                                            <h4> Player Registration </h4>
                                        </div>
                                    </div>

                                    <div class="form pb-2">
                                        {!! Form::open(['class' => 'players-form', 'method' => 'post', 'url' => route('playerJoin.players.store'), 'enctype' => 'multipart/form-data', 'autocomplete' => 'off' ]) !!}

                                            @php
                                                foreach($formCustomizations as $formCustomization){
                                                    if($formCustomization['key'] == 'image' && $formCustomization['show_hide'] == 1){
                                                        $image = 1;
                                                    } else if($formCustomization['key'] == 'form_no' && $formCustomization['show_hide'] == 1){
                                                        $form_no = 1;
                                                    } else if($formCustomization['key'] == 'name' && $formCustomization['show_hide'] == 1){
                                                        $name = 1;
                                                    } else if($formCustomization['key'] == 'mobile_number' && $formCustomization['show_hide'] == 1){
                                                        $mobile_number = 1;
                                                    } else if($formCustomization['key'] == 'father_name' && $formCustomization['show_hide'] == 1){
                                                        $father_name = 1;
                                                    } else if($formCustomization['key'] == 'age' && $formCustomization['show_hide'] == 1){
                                                        $age = 1;
                                                    } else if($formCustomization['key'] == 'category' && $formCustomization['show_hide'] == 1){
                                                        $category = 1;
                                                    } else if($formCustomization['key'] == 'playing_style' && $formCustomization['show_hide'] == 1){
                                                        $playing_style = 1;
                                                    } else if($formCustomization['key'] == 'specification_1' && $formCustomization['show_hide'] == 1){
                                                        $specification_1 = 1;
                                                    } else if($formCustomization['key'] == 'specification_2' && $formCustomization['show_hide'] == 1){
                                                        $specification_2 = 1;
                                                    } else if($formCustomization['key'] == 'specification_3' && $formCustomization['show_hide'] == 1){
                                                        $specification_3 = 1;
                                                    } else if($formCustomization['key'] == 'tshirt_size' && $formCustomization['show_hide'] == 1){
                                                        $tshirt_size = 1;
                                                    } else if($formCustomization['key'] == 'jersey_name' && $formCustomization['show_hide'] == 1){
                                                        $jersey_name = 1;
                                                    } else if($formCustomization['key'] == 'jersey_number' && $formCustomization['show_hide'] == 1){
                                                        $jersey_number = 1;
                                                    } else if($formCustomization['key'] == 'trouser_size' && $formCustomization['show_hide'] == 1){
                                                        $trouser_size = 1;
                                                    } else if($formCustomization['key'] == 'details' && $formCustomization['show_hide'] == 1){
                                                        $details = 1;
                                                    }



                                                    if($formCustomization['key'] == 'image' && $formCustomization['required'] == 1){
                                                        $image_required = 1;
                                                    } else if($formCustomization['key'] == 'form_no' && $formCustomization['required'] == 1){
                                                        $form_no_required = 1;
                                                    } else if($formCustomization['key'] == 'name' && $formCustomization['required'] == 1){
                                                        $name_required = 1;
                                                    } else if($formCustomization['key'] == 'mobile_number' && $formCustomization['required'] == 1){
                                                        $mobile_number_required = 1;
                                                    } else if($formCustomization['key'] == 'father_name' && $formCustomization['required'] == 1){
                                                        $father_name_required = 1;
                                                    } else if($formCustomization['key'] == 'age' && $formCustomization['required'] == 1){
                                                        $age_required = 1;
                                                    } else if($formCustomization['key'] == 'category' && $formCustomization['required'] == 1){
                                                        $category_required = 1;
                                                    } else if($formCustomization['key'] == 'playing_style' && $formCustomization['required'] == 1){
                                                        $playing_style_required = 1;
                                                    } else if($formCustomization['key'] == 'specification_1' && $formCustomization['required'] == 1){
                                                        $specification_1_required = 1;
                                                    } else if($formCustomization['key'] == 'specification_2' && $formCustomization['required'] == 1){
                                                        $specification_2_required = 1;
                                                    } else if($formCustomization['key'] == 'specification_3' && $formCustomization['required'] == 1){
                                                        $specification_3_required = 1;
                                                    } else if($formCustomization['key'] == 'tshirt_size' && $formCustomization['required'] == 1){
                                                        $tshirt_size_required = 1;
                                                    } else if($formCustomization['key'] == 'jersey_name' && $formCustomization['required'] == 1){
                                                        $jersey_name_required = 1;
                                                    } else if($formCustomization['key'] == 'jersey_number' && $formCustomization['required'] == 1){
                                                        $jersey_number_required = 1;
                                                    } else if($formCustomization['key'] == 'trouser_size' && $formCustomization['required'] == 1){
                                                        $trouser_size_required = 1;
                                                    } else if($formCustomization['key'] == 'details' && $formCustomization['required'] == 1){
                                                        $details_required = 1;
                                                    }

                                                }
                                            @endphp

                                            <div class="row mb-1">
                                                <div class="col-md-3">
                                                    @if($image == 1)
                                                        <div class="custom-dropify">
                                                            <label class="form-control-label" for="image">@lang('language.image') 
                                                                @if($image_required)<span class="text-danger">*</span>@endif
                                                            </label>
                                                            {!! Form::file('image', ['class' => 'image-preview', 'id' => 'image', 'autocomplete' => 'off', 'data-show-remove' => 'false', 'accept' => 'image/*', "data-default-file" => '', 'required' => $image_required ]) !!}

                                                            {!! Form::hidden('image_name', '' ,['class' => 'form-control','id' => 'image_name']) !!}
                                                        </div>
                                                    @endif
                                                </div>

                                                {!! Form::hidden('auction_id', $auction->id ,['class' => 'form-control','id' => 'auction_id']) !!}

                                                <div class="col-md-9">
                                                    <div class="row">
                                                        @if($form_no == 1)
                                                            <div class="col-md-6 pl-0 mb-3">
                                                                <label for="form_no">Form Number @if($form_no_required)<span class="text-danger">*</span>@endif
                                                                </label>
                                                                {!! Form::text('form_no', '', ['class' => 'form-control', 'id' => 'form_no', 'placeholder' => 'Form Number', 'required' => $form_no_required ]) !!}
                                                            </div>
                                                        @endif

                                                        @if($name == 1)
                                                            <div class="col-md-6 pl-0 mb-3">
                                                                <label for="name">Name @if($name_required)<span class="text-danger">*</span>@endif
                                                                </label>
                                                                {!! Form::text('name', '', ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Name', 'required' => $name_required ]) !!}
                                                            </div>
                                                        @endif

                                                        @if($mobile_number == 1)
                                                            <div class="col-md-6 pl-0 mb-3">
                                                                <label for="mobile_number">Mobile Number @if($mobile_number_required)<span class="text-danger">*</span>@endif
                                                                </label>
                                                                {!! Form::text('mobile_number', '', ['class' => 'form-control', 'id' => 'mobile_number', 'placeholder' => 'Mobile Number', 'required' => $mobile_number_required ]) !!}
                                                            </div>
                                                        @endif

                                                        @if($father_name == 1)
                                                            <div class="col-md-6 pl-0 mb-3">
                                                                <label for="father_name">Father Name @if($father_name_required)<span class="text-danger">*</span>@endif
                                                                </label>
                                                                {!! Form::text('father_name', '', ['class' => 'form-control', 'id' => 'father_name', 'placeholder' => 'Father Name', 'required' => $father_name_required ]) !!}
                                                            </div>
                                                        @endif

                                                        @if($age == 1)
                                                            <div class="col-md-6 pl-0 mb-3">
                                                                <label for="age"> Age @if($age_required)<span class="text-danger">*</span>@endif
                                                                </label>
                                                                {!! Form::text('age', '', ['class' => 'form-control numeric', 'id' => 'age', 'placeholder' => 'Age', 'required' => $age_required ]) !!}
                                                            </div>
                                                        @endif

                                                        @if($category == 1)
                                                            <div class="col-md-6 pl-0 mb-3">
                                                                <label for="category_id">Select Category @if($category_required)<span class="text-danger">*</span>@endif
                                                                </label>
                                                                {!! Form::select('category_id', create_select_options($categories, 'name', 'id', 'Select Category'), '',  ['class' => 'form-control select-picker', 'id' => 'category_id', 'required' => $category_required ]) !!}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-4 pl-3">

                                                @if($playing_style == 1)
                                                    <div class="col-md-4 pl-0 mt-3">
                                                        <label for="playing_style">Select Playing Style @if($playing_style_required)<span class="text-danger">*</span>@endif
                                                        </label>
                                                        {!! Form::select('playing_style', create_select_options(config('constants.playing_style'), 'display', 'value', 'Select Select Playing Style'), '',  ['class' => 'form-control select-picker', 'id' => 'playing_style_required', 'required' => $playing_style_required ]) !!}
                                                    </div>
                                                @endif

                                                @if($specification_1 == 1)
                                                    <div class="col-md-4 pl-0 mt-3">
                                                        <label for="specification_1">Select Specification 1 @if($specification_1_required)<span class="text-danger">*</span>@endif
                                                        </label>
                                                        {!! Form::select('specification_1', create_select_options($specifications1, 'name', 'name', 'Select Specification 1'), '',  ['class' => 'form-control select-picker', 'id' => 'specification_1', 'required' => $specification_1_required ]) !!}
                                                    </div>
                                                @endif

                                                @if($specification_2 == 1)
                                                    <div class="col-md-4 pl-0 mt-3">
                                                        <label for="specification_2">Select Specification 2 @if($specification_2_required)<span class="text-danger">*</span>@endif
                                                        </label>
                                                        {!! Form::select('specification_2', create_select_options($specifications2, 'name', 'name', 'Select Specification 2'), '',  ['class' => 'form-control select-picker', 'id' => 'specification_2', 'required' => $specification_2_required ]) !!}
                                                    </div>
                                                @endif

                                                @if($specification_3 == 1)
                                                    <div class="col-md-4 pl-0 mt-3">
                                                        <label for="specification_3">Select Specification 3 @if($specification_3_required)<span class="text-danger">*</span>@endif
                                                        </label>
                                                        {!! Form::select('specification_3', create_select_options($specifications3, 'name', 'name', 'Select Specification 3'), '',  ['class' => 'form-control select-picker', 'id' => 'specification_3', 'required' => $specification_3_required ]) !!}
                                                    </div>
                                                @endif

                                                @if($tshirt_size == 1)
                                                    <div class="col-md-4 pl-0 mt-3">
                                                        <label for="tshirt_size">Select T-shirt Size @if($tshirt_size_required)<span class="text-danger">*</span>@endif
                                                        </label>
                                                        {!! Form::select('tshirt_size', create_select_options(config('constants.sizes'), 'display', 'value', 'Select T-shirt Size'), '',  ['class' => 'form-control select-picker', 'id' => 'tshirt_size', 'required' => $tshirt_size_required ]) !!}
                                                    </div>
                                                @endif
                                                
                                                @if($jersey_name == 1)  
                                                    <div class="col-md-4 pl-0 mt-3">
                                                        <label for="jersey_name">Jersey Name @if($jersey_name_required)<span class="text-danger">*</span>@endif
                                                        </label>
                                                        {!! Form::text('jersey_name', '', ['class' => 'form-control', 'id' => 'jersey_name', 'placeholder' => 'Jersey Name', 'required' => $jersey_name_required ]) !!}
                                                    </div>
                                                @endif

                                                @if($jersey_number == 1)
                                                    <div class="col-md-4 pl-0 mt-3">
                                                        <label for="jersey_number">Jersey Number @if($jersey_number_required)<span class="text-danger">*</span>@endif
                                                        </label>
                                                        {!! Form::text('jersey_number', '', ['class' => 'form-control', 'id' => 'jersey_number', 'placeholder' => 'Jersey Number', 'required' => $jersey_number_required ]) !!}
                                                    </div>
                                                @endif

                                                @if($trouser_size == 1)
                                                    <div class="col-md-4 pl-0 mt-3">
                                                        <label for="trouser_size">Select Trouser Size @if($trouser_size_required)<span class="text-danger">*</span>@endif
                                                        </label>
                                                        {!! Form::select('trouser_size', create_select_options(config('constants.sizes'), 'display', 'value', 'Select Trouser Size'), '',  ['class' => 'form-control select-picker', 'id' => 'trouser_size', 'required' => $trouser_size_required ]) !!}
                                                    </div>
                                                @endif

                                                @if($details == 1)
                                                    <div class="col-md-12 pl-0 mt-3">
                                                        <div class="form-group">
                                                            <label class="form-control-label" for="details">Details @if($details_required)<span class="text-danger">*</span>@endif
                                                            </label>
                                                            {!! Form::textarea('details', '', ['class' => 'form-control textarea-height', 'id' => 'details', 'placeholder' => 'Enter Details', 'rows' => 5 , "cols" => 40, 'required' => $details_required ]) !!}
                                                        </div>
                                                    </div>
                                                @endif
                                                    
                                            </div>
                                            {{ Form::button( '<i class="fa fa-save"></i> &nbsp; Join Auction', ['class' => 'btn btn-primary btn-submit', 'type' => 'submit', 'title' => 'Join Auction' ] )}}
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Include footer  -->
            <!-- @include('auction-panel.layouts.main-footer') -->
            <!--/ Include footer  -->
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
    <script src="{{ asset('admin-assets/js/plugins/dropify/dropify.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/components.js') }}"></script>
    <script src="{{ asset('admin-assets/js/players/player-join.js') }}"></script>

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
