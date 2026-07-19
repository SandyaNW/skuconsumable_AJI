<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="profile-element text-center">
                    <span class="block m-t-xs font-bold" style="color: white;">
                        {{ auth()->user()->name }}
                    </span>
                    <span class="text-muted text-xs block">
                        {{ auth()->user()->position->position ?? 'No Position' }} 
                    </span>
                </div>
                <div class="logo-element">AJI</div>
            </li>

            @auth
                {{-- 1. Main Dashboard --}}
                <li class="{{ Request::is('sku/dashboard*') ? 'active' : '' }}">
                    <a href="{{ route('sku.dashboard') }}">
                        <i class="fa fa-th-large"></i> 
                        <span class="nav-label">Dashboard</span>
                    </a>
                </li>

                {{-- 2. SKU Management System --}}
                <li class="{{ Request::is('sku') || Request::is('sku/create') || Request::is('sku/show*') || Request::is('sku/*/edit') ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-shopping-cart"></i> 
                        <span class="nav-label">SKU Management</span> 
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse {{ Request::is('sku*') && !Request::is('sku/dashboard*') ? 'in' : '' }}">
                        @if(auth()->user()->position_id != 2)
                        <li class="{{ Request::is('sku/create') ? 'active' : '' }}">
                            <a href="{{ route('sku.create') }}">
                                <i class="fa fa-plus-circle"></i> Create Submission
                            </a>
                        </li>
                        @endif
                        <li class="{{ Request::is('sku') || Request::is('sku/show*') || Request::is('sku/*/edit') ? 'active' : '' }}">
                            <a href="{{ route('sku.index') }}">
                                <i class="fa fa-list"></i> List Submission
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- @role('MasterProduct') --}}
                {{-- 3. Master Data (PPIC Restricted) --}}
                @if(auth()->user()->hasRole('AdminSKU') || auth()->user()->position_id == 3)
                <li class="{{ Request::is('products*') ? 'active' : '' }}">
                    <a href="{{ route('products.index') }}">
                        <i class="fa fa-database"></i> 
                        <span class="nav-label">Master Product</span>
                    </a>
                </li>
                @endif
            @endauth
        </ul>
    </div>
</nav>