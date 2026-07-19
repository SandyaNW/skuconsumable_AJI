<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AJI MIS | LOGIN</title>

    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <link href="{{asset('css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
</head>

<body class="gray-bg">
    <div class="ibox-content middle-box text-center loginscreen animated fadeInDown mt-5">
        <div>
            @if (session()->has('success'))
            <div class="alert alert-success">
                {{session('success')}}
            </div>
            @endif
            <div>
                <img src="{{asset('image/ajilogo.png')}}" alt="logo" width="100">
            </div>
            <h3>AJI INTERNAL PORTAL</h3>
        <form class="m-t" role="form" method="post" action="{{route('login.perform')}}">
            @csrf
                <div class="form-group">
                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" placeholder="Username" required>
                    @error('username') 
                    <div class="alert alert-danger">
                        {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required>
                    @error('password') 
                    <div class="alert alert-danger">
                        {{$message}}
                    </div>
                    @enderror
                </div>
                @error('main_alert') 
                    <div class="alert alert-danger">
                        {{$message}}
                    </div>
                @enderror
                <button type="submit" class="btn btn-info block full-width m-b" style="background-color:#225879">Login</button>
                <a href="{{ route('forget.password.get') }}"><small>Forgot password?</small></a>
                {{-- <p class="text-muted text-center"><small>Do not have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="{{route('NewProductPortalSignupController.create')}}">Create an account</a> --}}
            </form>
            <p class="m-t"> <small>AJI INTERNAL PORTAL &copy;copyright 2022</small> </p>
        </div>
    </div>
    <!-- Mainly scripts -->
    <script src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{asset('js/popper.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.js')}}"></script>

</body>

</html>
