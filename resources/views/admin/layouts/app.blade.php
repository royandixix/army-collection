<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Meta SEO --}}
    <meta name="keywords" content="admin, dashboard, crm, analytics, eCommerce, team, vendor, ai chat bot, backend, panel">
    <meta name="description" content="Best multipurpose admin dashboard template.">
    <meta name="author" content="Maraviya Infotech">

    <title>@yield('title', 'Admin Dashboard | Army Collection')</title>

    {{-- Favicon & Styles --}}
    <link rel="shortcut icon" href="{{ asset('materialdesigndashboard/assets/img/favicon.png') }}">
    <link href="{{ asset('materialdesigndashboard/assets/css/materialdesignicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('materialdesigndashboard/assets/css/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('materialdesigndashboard/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('materialdesigndashboard/assets/css/apexcharts.css') }}" rel="stylesheet">
    <link href="{{ asset('materialdesigndashboard/assets/css/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('materialdesigndashboard/assets/css/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('materialdesigndashboard/assets/css/jquery.datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('materialdesigndashboard/assets/css/datatables.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('materialdesigndashboard/assets/css/slick.min.css') }}" rel="stylesheet">
    <link id="mainCss" href="{{ asset('materialdesigndashboard/assets/css/style.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @stack('styles')

    <style>
        * {
            font-family: "Poppins", sans-serif;
        }
    </style>
</head>

<body>
    <div class="wrapper">

        {{-- Sidebar --}}
        @include('admin.layouts.sidebar')

        <div class="main-wrapper">

            {{-- Header --}}
            @include('admin.layouts.header')

            {{-- Main Content --}}
            <main>
                @yield('content')
            </main>

        </div> <!-- /.main-wrapper -->
    </div> <!-- /.wrapper -->

    {{-- Scripts --}}
    <script src="{{ asset('materialdesigndashboard/assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('materialdesigndashboard/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('materialdesigndashboard/assets/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('materialdesigndashboard/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('materialdesigndashboard/assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('materialdesigndashboard/assets/js/daterangepicker.js') }}"></script>
    <script src="{{ asset('materialdesigndashboard/assets/js/jquery.simple-calendar.js') }}"></script>
    <script src="{{ asset('materialdesigndashboard/assets/js/jquery.datatables.min.js') }}"></script>
    <script src="{{ asset('materialdesigndashboard/assets/js/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('materialdesigndashboard/assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('materialdesigndashboard/assets/js/main.js') }}"></script>
    <script src="{{ asset('materialdesigndashboard/assets/js/chart-data.js') }}"></script>

    {{-- PENTING: Ini sekarang berada di tempat yang BENAR --}}
    @stack('scripts')

</body>
</html>
