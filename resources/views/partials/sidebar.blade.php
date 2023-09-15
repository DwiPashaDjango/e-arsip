<aside id="sidebar-wrapper">
  <div class="sidebar-brand pt-3">
    <img src="{{asset('assets/img/logo.png')}}" width="80">
  </div>
  <div class="sidebar-brand sidebar-brand-sm">
    <a href="#">
        <img src="{{asset('assets/img/logo.png')}}" width="40">
        {{-- {{ strtoupper(substr(config('app.name'), 0, 2)) }} --}}
    </a>
  </div>
  <ul class="sidebar-menu mt-5">
    <li class="menu-header">Dashboard</li>
    <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/dashboard') }}"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
    </li>
    @if (Auth::user()->role == 'admin')
        <li class="menu-header">Users</li>
        <li class="{{ request()->is('petugas') ? 'active' : '' }}">
            <a class="nav-link" href="{{url('/petugas')}}"><i class="fas fa-user-tie"></i> <span>Petugas</span></a>
        </li>
        <li class="{{ request()->is('users') ? 'active' : '' }}">
            <a class="nav-link" href="{{url('/users')}}"><i class="fas fa-user-plus"></i> <span>Sekolah</span></a>
        </li>
        <li class="menu-header">Arsip</li>
        <li class="{{ request()->is('arsip*') ? 'active' : '' }}">
            <a class="nav-link" href="{{url('/arsip')}}"><i class="fas fa-folder-open"></i> <span>Arsip</span></a>
        </li>
         <li class="menu-header">Setting</li>
        <li class="{{ request()->is('reset-sekolah') ? 'active' : '' }}">
            <a class="nav-link" href="{{url('/reset-sekolah')}}"><i class="fas fa-lock"></i> <span>Reset Password Sekolah</span></a>
        </li>
    @elseif(Auth::user()->role == 'petugas')
        <li class="menu-header">Users</li>
        <li class="{{ request()->is('users') ? 'active' : '' }}">
            <a class="nav-link" href="{{url('/users')}}"><i class="fas fa-user-plus"></i> <span>Sekolah</span></a>
        </li>
        <li class="menu-header">Arsip</li>
        <li class="{{ request()->is('arsip*') ? 'active' : '' }}">
            <a class="nav-link" href="{{url('/arsip')}}"><i class="fas fa-folder-open"></i> <span>Arsip</span></a>
        </li>
    @else
        @if (Auth::user()->bio != null)
            <li class="menu-header">Arsip</li>
            <li class="{{ request()->is('arsip*') ? 'active' : '' }}">
                <a class="nav-link" href="{{url('/arsip')}}"><i class="fas fa-folder-open"></i> <span>Arsip</span></a>
            </li>
            <li class="{{ request()->is('my-arsip') ? 'active' : '' }}">
                <a class="nav-link" href="{{url('/my-arsip')}}"><i class="fas fa-folder"></i> <span>My Arsip</span></a>
            </li>
        @endif
    @endif
  </ul>
</aside>
