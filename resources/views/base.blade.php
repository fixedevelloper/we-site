<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Wetransfertcash| envoyer l'argent facilement</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('lib/animate/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('lib/owlcarousel/assets/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('lib/lightbox/css/lightbox.min.css')}}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

    @stack('css_or_js')
    <!-- endinject -->
    <link rel="shortcut icon" href="{{asset('images/logo-small.png')}}" />
</head>

<body>
<div class="container-xxl bg-white p-0">
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->
    @include('_partials._navbar')
    <div class="">
        @yield('content')
    </div>
    @include('_partials._footer')
</div>
<!-- container-scroller -->
<!-- plugins:js -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<!-- endinject -->
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script src="{{asset('lib/wow/wow.min.js')}}"></script>
<script src="{{asset('lib/owlcarousel/owl.carousel.min.js')}}"></script>
<script src="{{asset('lib/easing/easing.min.js')}}"></script>
<script src="{{asset('lib/waypoints/waypoints.min.js')}}"></script>
<script src="{{asset('lib/counterup/counterup.min.js')}}"></script>
<script src="{{asset('lib/isotope/isotope.pkgd.min.js')}}"></script>
<script src="{{asset('lib/lightbox/js/lightbox.min.js')}}"></script>
<script src="{{asset('js/main.js')}}"></script>

@stack('script_2')
<!-- endinject -->
</body>

</html>
