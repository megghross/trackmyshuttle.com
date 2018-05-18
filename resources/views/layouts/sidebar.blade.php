<!-- SIDEBAR -->
<aside class="site-sidebar scrollbar-enabled" data-suppress-scroll-x="true">

    <!-- Sidebar Menu -->
    <nav class="sidebar-nav">
        <ul class="nav in side-menu">
            <li class="{{isActive("dashboard")}}">
                <a class="bluer" href="{{Route("dashboard")}}">
                    <i class="list-icon feather feather-grid" ></i>
                    <span class="hide-menu">
						Dashboard
					</span>
                </a>
            </li>

            <li class="{{isActive("live-tracking")}}">
                <a class="bluer" href="{{Route("live-tracking")}}">
                    <i class="list-icon feather feather-navigation"></i>
                    <span class="hide-menu">
						Live Tracking
					</span>
                </a>
            </li>

            <li class="{{isActive("routes")}}">
                <a class="bluer" href="{{Route("routes")}}">
                    <i class="list-icon feather feather-map"></i>
                    <span class="hide-menu">
						Routes
					</span>
                </a>
            </li>

            <li class="{{isActive("shuttles")}}">
                <a class="bluer" href="{{Route("shuttles")}}">
                    <i class="list-icon fal fa-bus"></i>
                    <span class="hide-menu">
						Shuttles
					</span>
                </a>
            </li>

            <li class="{{isActive("settings")}}">
                <a class="bluer" href="{{Route("routes")}}">
                    <i class="list-icon feather feather-settings"></i>
                    <span class="hide-menu">
						Settings
					</span>
                </a>
            </li>

            <li class="{{isActive("support")}}">
                <a class="bluer" href="{{Route("support")}}">
                    <i class="list-icon feather feather-life-buoy"></i>
                    <span class="hide-menu">
						Support
					</span>
                </a>
            </li>

        </ul><!-- /.side-menu -->
    </nav><!-- /.sidebar-nav -->
</aside><