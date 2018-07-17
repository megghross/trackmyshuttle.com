 @extends('layouts.main')

@section('content')

    <main class="main-wrapper clearfix">
        <!-- Page Title Area -->
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5"><?php echo isset( $dasboardtitle ) && $dasboardtitle ? $dasboardtitle : 'Dashboard' ?></h6>
            </div><!-- /.page-title-left -->

        </div><!-- /.page-title -->

        <div class="widget-list new-widget-style">
            <div class="loader"></div>

            <div class="row">
                <div class="col-md-6">
                    <div class="widget-heading widget-heading-border">
                        <h5 class="widget-title">Devices</h5>
                    </div>
                    <div class="widget-body">
                        <ul class="nav nav-tabs contact-details-tab">
                            <li class="nav-item device-item">
                                <!-- devices list-->




                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="widget-heading widget-heading-border">
                    </div>
                    <div class="widget-body new-contents" id="tabPanesForDevices">

                    </div>
                </div>
            </div>
        </div>
        <div class="row page-title clearfix" style="margin-top: 10px;">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Tracking Lanes</h6>
            </div><!-- /.page-title-left -->
        </div>
        <div class="widget-list new-widget-style">
            <div class="widget-holder col-md-12">
                <div class="widget-body">
                    <div class="featured-jobs">
                        <!--shuttle route realtime view-->

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection


@section("styles")
    <link href="{{asset("https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css")}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset("https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css")}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset("https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css")}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.25/daterangepicker.min.css")}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-touchspin/3.1.2/jquery.bootstrap-touchspin.min.css")}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset("https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.1.7/css/ion.rangeSlider.min.css")}}"
          rel="stylesheet" type="text/css"/>
@endsection


@section("scripts")
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.2/umd/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.0/metisMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.7.0/js/perfect-scrollbar.jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/1.9.2/countUp.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.2.2/circle-progress.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.25/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mithril/1.1.1/mithril.js"></script>
    <script src="{{asset("assets/vendors/theme-widgets/widgets.js")}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-touchspin/3.1.2/jquery.bootstrap-touchspin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.1.7/js/ion.rangeSlider.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Knob/1.2.13/jquery.knob.min.js"></script>
    <script src="{{asset("assets/vendors/charts/excanvas.js")}}"></script>
    <script src="{{asset("assets/js/theme.js")}}"></script>
    <script src="{{asset("custom-js/dashboard.js")}}"></script>
    <script src="{{asset("assets/js/custom.js")}}"></script>
    <script src="{{asset("assets/js/svg.js")}}"></script>

@endsection