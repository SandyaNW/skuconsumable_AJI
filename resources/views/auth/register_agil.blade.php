<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AJI MIS | REGISTER</title>

    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <link href="{{asset('css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
</head>

<body class="gray-bg">
    <div class="ibox-content middle-box text-center loginscreen animated fadeInDown mt-5">
        <div>
            <div>
                <img src="{{asset('image/ajilogo.png')}}" alt="logo" width="70">
            </div>
            <h3>Welcome to AJI Portal</h3>
        <form class="m-t" role="form" method="post" action="{{route('register.perform')}}">
            @csrf
                <div class="form-group">
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" required value="{{old('email')}}">
                    @error('email') 
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" required value="{{old('name')}}">
                    @error('name') 
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" placeholder="Username" required value="{{old('username')}}">
                    @error('username') 
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <select class="form-control" name="dept_id" id="dept_id" required>
                        <option value="">--pilih--</option>
                        @foreach ($depts as $dept)
                        <option value="{{$dept->id}}">{{$dept->name}}</option>
                        @endforeach
                    </select>
                    @error('dept') 
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required>
                    @error('password') 
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" placeholder="Password Confirmation" required>
                    @error('password') 
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b"style="background-color:#225879">Register</button>

                <p class="text-muted text-center"><small>Already have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="/login">Login</a>
            </form>
            <p class="m-t"> <small>AJI Portal &copy;copyright 2022</small> </p>
        </div>
    </div>
    <!-- Mainly scripts -->
    <script src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{asset('js/popper.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.js')}}"></script>

</body>

</html>
