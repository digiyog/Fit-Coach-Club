<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- / CSRF Token -->

    <title>@yield('page-title')</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="{{ asset('admin-assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/bootstrap-extend.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/plugins.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/auth-modern.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/css/elements/alert.css') }}" rel="stylesheet">
    @stack('styles')
    <!-- / Styles -->

</head>

<body class="auth-body">

    <!-- Include Content  -->
    @yield('content')
    <!-- Include Content  -->

    <!-- Scripts -->
    <script src="{{ asset('admin-assets/js/libs/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/bootstrap/js/bootstrap-compat.js') }}"></script>
    <script src="{{ asset('admin-assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/additional-methods.min.js') }}"></script>

    <script>
        feather.replace();
    </script>

    @stack('scripts')
    <!-- / Scripts -->

</body>
</html>
