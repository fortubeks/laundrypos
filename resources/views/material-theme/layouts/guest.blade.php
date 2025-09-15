<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <!-- Nucleo Icons -->
    <link href="{{url('material/assets/css/nucleo-icons.css')}}" rel="stylesheet" />
    <link href="{{url('material/assets/css/nucleo-svg.css')}}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/b755aa5ef0.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{url('material/assets/css/material-dashboard.css?v=3.2.0')}}" rel="stylesheet" />
    <title>{{env('APP_NAME')}}</title>
</head>

<body class="bg-gray-200">
    @include('material-theme.layouts.partials.guest.header')
    <main class="main-content  mt-0">
        @yield('contents')
    </main>
    @include('material-theme.layouts.partials.guest.footer')
    <!--   Core JS Files   -->
    <script src="{{url('material/assets/js/core/popper.min.js')}}"></script>
    <script src="{{url('material/assets/js/core/bootstrap.min.js')}}"></script>
    <script src="{{url('material/assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
    <script src="{{url('material/assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{url('material/assets/js/material-dashboard.min.js?v=3.2.0')}}"></script>
</body>

</html>