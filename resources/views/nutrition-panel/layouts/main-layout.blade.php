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

    @php
    $company        = get_company_profile();
    @endphp
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('admin-assets/images/favicon.ico') }}" type="image/x-icon">
    <title>@yield('page-title')</title>

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
    @stack('styles')
    <!-- / Styles -->
    @php
        $user = auth()->user();
    @endphp
</head>

<body class="">

    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <!-- Include header -->
    @include('nutrition-panel.layouts.main-header')
    <!--/ Include header -->

    <!-- Include breadcrumb -->
    {{--@include('nutrition-panel.layouts.main-breadcrumb')--}}
    <!--/ Include breadcrumb -->


    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!-- Include sidebar  -->
        @include('nutrition-panel.layouts.main-sidebar')
        <!--/ Include sidebar  -->

        <!-- Include Content  -->
        <div id="content" class="main-content" style="margin-left: 95px;">
            @if(isset($breadcrumb))
                <!-- Include breadcrumb -->
                @include('nutrition-panel.layouts.main-breadcrumb')
                <!--/ Include breadcrumb -->
            @endif
            
            @yield('content')

            <!-- Include footer  -->
            @include('nutrition-panel.layouts.main-footer')
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

    <script>
        var SUPER_ADMIN = "{{ $user->hasRole('Super Admin') }}";
    </script>
    @stack('scripts')
    <!-- / Scripts -->

</body>
</html>
