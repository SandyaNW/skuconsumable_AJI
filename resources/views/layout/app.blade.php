<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    {{-- css --}}
    @include('script.css')
    {{-- JS --}}
    <script src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
</head>

<body class="top-navigation">

    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">

            {{-- nav --}}
            @include('layout.nav')

            <div class="wrapper mt-4">
                {{-- content --}}
                @yield('content')
            </div>

            <div class="footer">
            
            </div>

        </div>
    </div>

    {{-- js --}}
    @stack('scripts')
    <!-- Mainly scripts -->
 
    <script src="{{asset('js/popper.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.js')}}"></script>
</body>

</html>
