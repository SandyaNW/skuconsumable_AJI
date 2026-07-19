<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AJI MIS | REGISTER</title>

    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('font-awesome/css/font-awesome.css')}}" rel="stylesheet">
    <link href="{{asset('css/plugins/chosen/bootstrap-chosen.css')}}" rel="stylesheet">
    <link href="{{asset('css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
</head>

<body class="gray-bg">
    <div class="m-4 text-center loginscreen animated fadeInDown mt-5">
        <div>
            <div>
                <img src="{{asset('image/ajilogo.png')}}" alt="logo" width="70">
            </div>
            <h3>Welcome to AJI MIS</h3>
            
            <div class="row">
                <div class="col-lg-4"></div>
                <div class="col-lg-4">
                    @if(session()->has('success'))
                        <div class="alert alert-primary mb-1">{{session('success')}}</div>
                    @endif
                    <form class="m-t" class="form" role="form" method="post" action="{{route('NewProductPortalSignupController.store')}}">
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
                            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" placeholder="Username" required value="{{old('username')}}" autocomplete="off">
                            @error('username') 
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="dept" id="dept" required>
                                <option value="">-- Department --</option>
                                @foreach ($depts as $dept)
                                <option value="{{ $dept->id }}" {{ (old("dept") == $dept->id ? "selected":"") }}>{{ $dept->name }}</option>

                                @endforeach
                            </select>
                            @error('dept') 
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="position_id" id="position_id" required>
                                <option value="">-- Position --</option>
                                @foreach ($positions as $position)
                                <option value="{{ $position->id }}" {{ (old("position_id") == $position->id ? "selected":"") }}>{{ $position->position }}</option>

                                @endforeach
                            </select>
                            @error('position_id') 
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="detail_dept_id" id="detail_dept_id" required>
                                <option value="">-- Detail Dept --</option>
                                @foreach ($detail_depts as $detail_dept)
                                <option value="{{ $detail_dept->id }}" {{ (old("detail_dept_id") == $detail_dept->id ? "selected":"") }}>{{ $detail_dept->code }}</option>

                                @endforeach
                            </select>
                            @error('detail_dept_id') 
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <select class="form-control chosen-select" data-placeholder="    -- Role --" name="role[]" id="role" tabindex="4" multiple required>
                                @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ (old("role") == $role->name ? "selected":"") }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role') 
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="number"  size="5" class="form-control @error('npk') is-invalid @enderror" name="npk" placeholder="NPK" value="{{old('npk')}}" autocomplete="off" required pattern="\d{5}" title="Please enter a 5-digit number (00000).">
                            @error('npk') 
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" autocomplete="off" required>
                            @error('password') 
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" placeholder="Password Confirmation" autocomplete="off" required>
                            @error('password') 
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary block full-width m-b"style="background-color:#225879">Register</button>
        
                        <p class="text-muted text-center"><small>Already have an account?</small></p>
                        <a class="btn btn-sm btn-white btn-block" href="{{route('home.index')}}">Login</a>
                    </form>
                </div>
                <div class="col-lg-4"></div>
            </div>
            <p class="m-t"> <small>AJI Portal &copy;copyright 2022</small> </p>
        </div>
    </div>
    <!-- Mainly scripts -->
    <script src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{asset('js/popper.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.js')}}"></script>
    <script src="{{asset('js/plugins/chosen/chosen.jquery.js')}}"></script>
    <script>
        $(document).ready(function(){
            document.getElementById('npk').addEventListener('input', function () {
                if (!/^\d{5}$/.test(this.value)) {
                    this.setCustomValidity('Please enter a 5-digit number.');
                } else {
                    this.setCustomValidity('');
                }
            });
        });
        $('.chosen-select').chosen({width: "100%"});
        var form = document.querySelector('form');
        form.addEventListener("submit", function(event) {
            if (roleSelect.value.length === 0) {
                roleSelect.setCustomValidity('Please select at least one role.');
                event.preventDefault();
            }
        });

    </script>

</body>

</html>
