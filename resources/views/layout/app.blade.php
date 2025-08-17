<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
<!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'VotingSystem') }}</title>

    <!-- Scripts and Styles -->
    @yield('head')
    
    @include('_partials.head')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" rel="stylesheet">
    @stack('styles')
</head>
<!-- END: Head -->

<body class="py-5">

    {{-- <script>
        $('#show_click_btn')
    </script> --}}

    <!-- BEGIN: Mobile Menu -->
    @include('_partials.mobile')
    <!-- END: Mobile Menu -->


    <div class="flex mt-[4.7rem] md:mt-0">
        <!-- BEGIN: Side Menu -->
        @include('_partials.sidebar')
        <!-- END: Side Menu -->


        <!-- BEGIN: Content -->
        <div class="content">
            <!-- BEGIN: Top Bar -->
            @include('_partials.topbar')
            <!-- END: Top Bar -->

            @yield('content')




    </div>

    @include('_partials.scripts')

    <script src="{{asset('js/app.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        // Initialize Lucide icons globally
        lucide.createIcons({
            attrs: {
                class: ["w-4", "h-4"]
            }
        });
    </script>
    @stack('scripts')

</body>

</html>
