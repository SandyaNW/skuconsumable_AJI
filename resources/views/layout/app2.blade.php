
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    {{-- css --}}
    @include('script.css')
    {{-- JS --}}
    <script src="{{asset('js/jquery-3.5.1.js')}}"></script>

</head>

<body class="">

    <div id="wrapper">

        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="block m-t-xs font-bold">
                                    @if (Auth::check())
                                        {{Auth::user()->name}}
                                    @endif
                                </span>
                                <span class="text-muted text-xs block">
                                    {{-- {{
                                        Auth::user()->getRoleNames()->implode(' | ')
                                    }} --}}
                                </span>
                            </a>
                        </div>
                        <div class="logo-element">
                            @if (Auth::check())
                            {{ substr(Auth::user()->name,0, 2)}}
                            @endif
                        </div>
                    </li>
                    <li>
                        <a href="index.html"><i class="fa fa-bar-chart"></i> <span class="nav-label">Dashboards</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse" aria-expanded="false">
                            <li><a href="#">Delivery Achievment</a></li>
                            <li><a href="#">Claim Customer</a></li>
                            <li><a href="#">Packaging Problem</a></li>
                            <li><a href="#">Preparation Status </a></li>
                            <li><a href="#">Delivery Status </a></li>
                            <li><a href="#">Henkaten Status </a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-truck"></i> <span class="nav-label">Delivery Daily</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-tasks"></i> <span class="nav-label">Preparation Delivery</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-envelope"></i> <span class="nav-label">Delivery Note</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-cubes"></i> <span class="nav-label">Packaging</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-exclamation-triangle"></i> <span class="nav-label">Claim Customer</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-group"></i> <span class="nav-label">Henkaten</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse" aria-expanded="false">
                            {{-- <li><a href="{{route('henkaten_skill_matrix')}}">Skill Matrix</a></li>
                            <li><a href="{{route('henkaten_layout_area')}}">Layout Area</a></li>
                            <li><a href="{{route('henkaten_planning_refreshment')}}">Planning Refreshment </a></li>
                            <li><a href="{{route('list_henkaten')}}">Henkaten </a></li> --}}
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-table"></i> <span class="nav-label">Master Data</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse" aria-expanded="false">
                            {{-- <li><a href="{{route('master_part')}}">Part</a></li>
                            <li><a href="{{route('master_packaging')}}">Packaging</a></li>
                            <li><a href="{{route('master_line')}}">Line</a></li>
                            <li><a href="{{route('master_customer')}}">Customer </a></li>
                            <li><a href="{{route('master_user')}}">Users </a></li>
                            <li><a href="{{route('master_man_power')}}">Man Power </a></li> --}}
                        </ul>
                    </li>
                </ul>

            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <a href="/"><span class="m-r-sm text-muted welcome-message">Welcome to @yield('title')</span></a>
                        </li>
                        <li>
                            <a href="/logout">
                                <i class="fa fa-sign-out"></i> Log out
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="wrapper wrapper-content animated animated-fadeRight">
                {{-- content --}}
                @yield('content')
            </div>
        
        </div>
    </div>

    {{-- js --}}
    @stack('scripts')
    <!-- Mainly scripts -->
 
    <script src="{{asset('js/popper.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.js')}}"></script>
    <script src="{{asset('js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
    <script src="{{asset('js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
    <script src="{{asset('js/plugins/pace/pace.min.js')}}"></script>
    <script src="{{asset('js/inspinia.js')}}"></script>

</body>

</html>
