@extends('layouts.auth-master')

@section('content')
<body class="gray-bg">
    {{-- <div class="ibox-content middle-box text-center loginscreen animated fadeInDown mt-5">
        <form method="post" class="m-t" action="{{ route('login.perform') }}">
        
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <img class="mb-4" src="{!! url('images/logo.png') !!}" alt="" width="" height="">
            
            <h4>Login</h4>
    
            @include('layouts.partials.messages')
            <div class="form-group form-floating mb-3">
                <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username" required="required" autofocus>
                <label for="floatingName">Email or Username</label>
                @if ($errors->has('username'))
                    <span class="text-danger text-left">{{ $errors->first('username') }}</span>
                @endif
            </div>
            
            <div class="form-group form-floating mb-3">
                <input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="Password" required="required">
                <label for="floatingPassword">Password</label>
                @if ($errors->has('password'))
                    <span class="text-danger text-left">{{ $errors->first('password') }}</span>
                @endif
            </div>
    
            <div class="form-group mb-3">
                <label for="remember">Remember me</label>
                <input type="checkbox" name="remember" value="1">
            </div>
            <a href="{{ route('forget.password.get') }}"><small>Forgot password?</small></a>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
            
            @include('auth.partials.copy')
        </form>
    </div> --}}

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
            <h3>AJI MIS</h3>

        <form class="m-t" role="form" method="post" action="{{ route('login.perform') }}">
            @csrf
            @include('layouts.partials.messages')
                <div class="form-group">
                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" placeholder="Username" required>
                    @if ($errors->has('username')) 
                    <div class="alert alert-danger">
                        {{ $errors->first('username') }}
                    </div>
                    @endif
                </div>
                <div class="form-group">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required>
                    @if ($errors->has('username')) 
                    <div class="alert alert-danger">
                        {{ $errors->first('password') }}
                    </div>
                    @endif
                </div>
                @error('main_alert') 
                    <div class="alert alert-danger">
                        {{$message}}
                    </div>
                @enderror
                <button type="submit" class="btn btn-info block full-width m-b" style="background-color:#225879">Login</button>
                <a href="{{ route('forget.password.get') }}"><small>Forgot password?</small></a>
                {{-- <p class="text-muted text-center"><small>Do not have an account?</small></p> --}}
                {{-- <a class="btn btn-sm btn-white btn-block" href="">Create an account</a> --}}
                @include('auth.partials.copy')
            </form>
            <p class="m-t"> <small>AJI MIS &copy;copyright 2022</small> </p>
        </div>
    </div>
@endsection
