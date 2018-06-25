<!-- HEADER & TOP NAVIGATION -->
<nav class="navbar">
    <!-- Logo Area -->
    <div class="navbar-header">
        <a href="index.php" class="navbar-brand">
            <img class="logo-expand" alt="" src="assets/img/logo-expand.png"/>
            <img class="logo-collapse" alt="" src="assets/img/logo-collapse.png"/>
            <!-- <p>BonVue</p> -->
        </a>
    </div><!-- /.navbar-header -->

    <!-- Left Menu & Sidebar Toggle -->
    <ul class="nav navbar-nav">
        <li class="sidebar-toggle">
            <a href="javascript:void(0)" class="ripple">
                <i class="feather feather-menu list-icon fs-20"></i>
            </a>
        </li>
    </ul><!-- /.navbar-left -->

    <!-- Search Form -->
    <span class="navbar-search d-none d-sm-block" role="search">
    <i class="material-icons list-icon">business</i>
    <h6 class="navbar-hotel">The Ritz-Carlton</h6>
  </span><!-- /.navbar-search -->

    <div class="spacer"></div>

    <!-- User Image with Dropdown -->
    <ul class="nav navbar-nav">
        @guest
            <li>
                <a href="{{ __("admin/org.php")}}">
                <span>
                  <span class="align-middle">Admin Panel</span>
                </span>
                </a>

            </li>
        @else
            <li class="dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle ripple" data-toggle="dropdown">
        <span class="avatar thumb-xs2">
            <span class="profile-circle">
                <span>{{textInitials(Auth::user()->name, 2)}}</span>
            </span>
          {{--<img src="assets/img/user-nav.png" class="rounded-circle" alt=""/>--}}
          <i class="feather feather-chevron-down list-icon"></i>
        </span>
                </a>

                <div class="dropdown-menu dropdown-left dropdown-card dropdown-card-profile animated flipInY">
                    <div class="card">
                        <header class="card-header d-flex mb-0">
                            <a href="javascript:void(0);" class="col-md-4 text-center">
                                <i class="feather feather-user align-middle"></i>
                            </a>
                            <a href="javascript:void(0);" class="col-md-4 text-center">
                                <i class="feather feather-settings align-middle"></i>
                            </a>
                            <a href="javascript:void(0);" class="col-md-4 text-center">
                                <i class="feather feather-x align-middle"></i>
                            </a>
                        </header>

                        <ul class="list-unstyled card-body">
                            <li>
                                <a href="#">
                <span>
                  <span class="align-middle nav-profile-name">{{Auth::user()->name}}</span>
                </span>
                                </a>
                            </li>

                            {{--<li>--}}
                            {{--<a href="#">--}}
                            {{--<span>--}}
                            {{--<span class="align-middle">Change Password</span>--}}
                            {{--</span>--}}
                            {{--</a>--}}
                            {{--</li>--}}

                            {{--<li>--}}
                            {{--<a href="#">--}}
                            {{--<span>--}}
                            {{--<span class="align-middle">Check Inbox</span>--}}
                            {{--</span>--}}
                            {{--</a>--}}
                            {{--</li>--}}

                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                <span>
                  <span class="align-middle">Logout</span>
                </span>
                                </a>


                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        @endguest
    </ul><!-- /.navbar-right -->

    <!-- Right Menu -->
    <ul class="nav navbar-nav d-none d-lg-flex ml-2 ml-0-rtl">
        <li class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle ripple" data-toggle="dropdown">
                <i class="feather feather-bell list-icon"></i>
            </a>

            <div class="dropdown-menu dropdown-left dropdown-card animated flipInY">
                <div class="card">
                    <header class="card-header d-flex align-items-center mb-0">
                        <a href="javascript:void(0);"><i class="feather feather-bell color-color-scheme"
                                                         aria-hidden="true"></i></a>
                        <span class="heading-font-family flex-1 text-center fw-400">Notifications</span>
                        <a href="javascript:void(0);"><i class="feather feather-settings color-content"></i></a>
                    </header>

                    <ul class="card-body list-unstyled dropdown-list-group">
                        <li>
                            <a href="#" class="media">
                <span class="d-flex thumb-xs">
                   <i class="feather feather-cpu list-icon"></i>
                </span>

                                <span class="media-body">
                  <span class="heading-font-family media-heading">You</span>
                  <span class="media-content">activated device</span>
                  <span class="heading-font-family media-heading">Motorola 00000</span>
                  <span class="user--online float-right my-auto"></span>
                </span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="media">
                <span class="d-flex thumb-xs">
                   <i class="feather feather-navigation list-icon"></i>
                </span>

                                <span class="media-body">
                  <span class="heading-font-family media-heading">Aksh Bhakti</span>
                  <span class="media-content">is en route to</span>
                  <span class="heading-font-family media-heading">Ritz Carlton</span>
                </span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="media">
                <span class="d-flex thumb-xs">
                   <i class="feather feather-map-pin list-icon"></i>
                </span>

                                <span class="media-body">
                  <span class="heading-font-family media-heading">Bhavin Shah</span>
                  <span class="media-content">created new route to</span>
                  <span class="heading-font-family media-heading">Washington National Airport</span>
                </span>
                            </a>
                        </li>

                    </ul><!-- /.dropdown-list-group -->

                    <footer class="card-footer text-center">
                        <a href="javascript:void(0);" class="heading-font-family text-uppercase fs-13">See all
                            activity</a>
                    </footer>

                </div><!-- /.card -->
            </div><!-- /.dropdown-menu -->
        </li><!-- /.dropdown -->

    </ul><!-- /.navbar-right -->
</nav><!-- /.navbar -->
