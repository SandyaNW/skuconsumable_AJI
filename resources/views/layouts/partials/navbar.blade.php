<header class="p-3 bg-dark text-white">
  <div class="container">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
      <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
        <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
      </a>

      <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
        <li><a href="{{ route('home.index') }}" class="nav-link px-2 text-white">Home</a></li>
        @auth

          @role('admin')
          <li class="nav-item px-2 text-white dropdown" id="myDropdown">
            <a class="nav-link text-white dropdown-toggle" href="#" data-bs-toggle="dropdown">Users</a>
            <ul class="dropdown-menu">
              <li><a href="{{ route('users.index') }}" class="dropdown-item">Users</a></li>
              <li><a href="{{ route('roles.index') }}" class="dropdown-item">Roles</a></li>
              <li><a href="{{ route('permissions.index') }}" class="dropdown-item">Permissions</a></li>
            </ul>
          </li>
          <li class="nav-item px-2 text-white dropdown" id="myDropdown">
            <a class="nav-link text-white dropdown-toggle" href="#" data-bs-toggle="dropdown">Masters</a>
            <ul class="dropdown-menu">
              <li><a href="{{ route('departments.index') }}" class="dropdown-item">Departments</a></li>
            </ul>
          </li>
          <li class="nav-item px-2 text-white dropdown" id="myDropdown">
            <a class="nav-link text-white dropdown-toggle" href="#" data-bs-toggle="dropdown">Files</a>
            <ul class="dropdown-menu">
              <li><a href="{{ route('files.index') }}" class="dropdown-item">Files</a></li>
              <li><a href="{{ route('files.alldept') }}" class="dropdown-item">Files All Dept</a></li>
            </ul>            
          </li>
          <li><a href="{{ route('logs.index') }}" class="nav-link px-2 text-white">Logs</a></li>
          @endrole

          <!-- <li><a href="{{ route('posts.index') }}" class="nav-link px-2 text-white">Posts</a></li> -->
          <!-- SIAPKAN ROLE HRGA, FINANCE, DAN DEPT LAINNYA -->
          <!-- BUAT PAGE KHUSUS UNTUK ROLE2 TERSEBUT -->
        @endauth

        @auth
          @role('HRGA')
            <!-- harusnya files.hrga.index -->
            <!-- <li><a href="{{ route('files.index') }}" class="nav-link px-2 text-white">Files {{$tests_navbar ?? 'gak ada'}}</a></li> -->
            <li><a href="{{ route('files.index') }}" class="nav-link px-2 text-white">Files</a></li>
            <li><a href="{{ route('categories.index') }}" class="nav-link px-2 text-white">Categories</a></li>
          @endrole
          @role('user')
            <li class="nav-item px-2 text-white dropdown" id="myDropdown">
              <a class="nav-link text-white dropdown-toggle" href="#" data-bs-toggle="dropdown">Files</a>
              <ul class="dropdown-menu">
                <li><a href="{{ route('files.index') }}" class="dropdown-item">Files</a></li>
                <li><a href="{{ route('files.alldept') }}" class="dropdown-item">Files All Dept</a></li>
              </ul>            
            </li>
            <li><a href="{{ route('categories.index') }}" class="nav-link px-2 text-white">Categories</a></li>
          @endrole
        @endauth

      </ul>

      <!-- <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
        <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
      </form> -->

      @auth
        {{auth()->user()->name}}&nbsp;
        <div class="text-end">
          <a href="{{ route('logout') }}" class="btn btn-outline-light me-2">Logout</a>
        </div>
      @endauth

      @guest
        <div class="text-end">
          <a href="{{ route('login.perform') }}" class="btn btn-outline-light me-2">Login</a>
          <!-- <a href="{{ route('register.perform') }}" class="btn btn-warning">Sign-up</a> -->
        </div>
      @endguest
    </div>
  </div>
</header>