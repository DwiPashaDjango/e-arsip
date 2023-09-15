<form class="form-inline mr-auto" action="">
  <ul class="navbar-nav mr-3">
    <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
  </ul>
</form>
<ul class="navbar-nav navbar-right">
  <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
    <div class="d-sm-none d-lg-inline-block">{{Auth::user()->username}}</div></a>
    <div class="dropdown-menu dropdown-menu-right">
      <div class="dropdown-title">{{Auth::user()->email}}</div>
      <div class="dropdown-divider"></div>
      <form action="{{route('logout')}}" method="POST">
        @csrf
        <button class="dropdown-item  text-danger">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </button>
      </form>
    </div>
  </li>
</ul>
