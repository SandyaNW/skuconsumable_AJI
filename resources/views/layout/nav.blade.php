<div class="row border-bottom white-bg">
    <nav class="navbar navbar-expand-lg navbar-static-top" role="navigation">

            <a href="{{route('home.index')}}" class="navbar-brand " style="background-color:#225879">AJI INTERNAL PORTAL</a>
            <button class="navbar-toggler" style="background-color:#225879" type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-reorder"></i>
            </button>

        <div class="navbar-collapse collapse" id="navbar">
            <ul class="nav navbar-nav mr-auto">
                {{-- <li >
                    <img class="m-1" src="{{url('/image/ajilogo.png')}}" alt="aji" width="40" height="40">
                </li> --}}
                <li >
                    @if (Auth::check())
                        <a aria-expanded="false" role="button" href="#" > 
                            {{Auth::user()->name." | ".Auth::user()->getRoleNames()->implode(' | ').""}}
                        </a>
                    @endif
                </li>
                @role('super-admin')
                <li class="dropdown">
                    <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Superadmin Panel</a>
                    <ul role="menu" class="dropdown-menu">
                        <li><a href="{{route('superadmin-panel')}}">Access Control</a></li>
                        <li><a href="{{route('user-data')}}">Users</a></li>
                    </ul>
                </li>
                @endrole
                @role('admin-quality')
                <li class="dropdown">
                    <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Admin Quality Panel</a>
                    <ul role="menu" class="dropdown-menu">
                        <li><a href="{{url('admin/admin-quality')}}">Access Control</a></li>
                    </ul>
                </li>
                @endrole
                @role('admin-produksi')
                <li class="dropdown">
                    <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Admin Produksi Panel</a>
                    <ul role="menu" class="dropdown-menu">
                        <li><a href="{{url('admin/admin-produksi')}}">Access Control</a></li>
                    </ul>
                </li>
                @endrole
                @role('admin-delivery')
                <li class="dropdown">
                    <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Admin Delivery Panel</a>
                    <ul role="menu" class="dropdown-menu">
                        <li><a href="{{url('admin/admin-delivery')}}">Access Control</a></li>
                    </ul>
                </li>
                @endrole
            </ul>
            <ul class="nav navbar-top-links navbar-right">
                @guest
                <li>
                    <a href="{{route('login.show')}}">
                        <i class="fa fa-sign-in"></i> Log in
                    </a>
                </li>
                @endguest
            </ul>
        </div>
    </nav>
</div>