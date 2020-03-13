<!doctype html>
<html lang="en">
<head>
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Basic Page Needs
================================================== -->
<title>{{ config('app.name', 'Yohli') }} - @yield('title')</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<!-- CSS
================================================== -->
@include('partials.styles')

</head>
<body>
    <!-- Wrapper -->
    <div id="wrapper">
        <!-- Header Container
        ================================================== -->
        <header id="header-container" class="fullwidth dashboard-header not-sticky">
            @include('partials.header')
        </header>
        <div class="clearfix"></div>
        <!-- Header Container / End -->

        
        <!-- Dashboard Container -->
        <div class="dashboard-container" id="app">
            <!-- Dashboard Sidebar
            ================================================== -->
            @include('partials.dashboard_sidebar')
            <!-- Dashboard Sidebar / End -->


            <!-- Dashboard Content
            ================================================== -->
            <div class="dashboard-content-container" data-simplebar>
                <div class="dashboard-content-inner">
                    <!-- Dashboard Headline -->
                    @include('partials.dashboard_headline')

                    @include('partials.alert')

                    
                    @yield('content')

                    @php //echo fb_chat(); @endphp
                    <!-- Footer -->
                    @include('partials.dashboard_footer')
                    <!-- Footer / End -->


                </div>
            </div>
            <!-- Dashboard Content / End -->


        </div>
        <!-- Dashboard Container / End -->



    </div>
    <!-- Wrapper / End -->

    <!-- Scripts
    ================================================== -->

    @include('partials.scripts')
    
</body>