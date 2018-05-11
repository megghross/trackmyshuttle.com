@extends('layouts.main')

@section('content')
    <main class="main-wrapper clearfix">



        <!-- =================================== -->
        <!-- TMS Data widgets ============ -->
        <!-- =================================== -->
        <div class="widget-list ">
            <div class="grid-container-shuttle">

                <div class="shuttle_item1">
                    <div class="grid_item_title">
                        <span class="item_title">Company Profile</span>
                        <div class="item_title_menu"><i class="fas fa-book" style="margin-right: 3px;"></i><i class="fas fa-ellipsis-v"></i></div>
                    </div>
                    <div class="shuttle_manage_body">
                        <div class="shuttle_manage_body_sub">
                            <div class="shuttle_manage_sub1">
                                <i class="list-icon fas fa-bus shuttle_busi"></i>
                                <span class="shuttle_busi_span">Shuttle 1</span>
                            </div>
                            <div class="shuttle_manage_sub1">
                                <span style="margin-left: 5px;">License Plate</span><br>
                                <span style="margin-left: 5px;">VIN</span>
                            </div>
                            <div class="shuttle_manage_sub1" style="background-color: white !important;">
                                <div class="shuttle_manage_sub1_sub"><div><span>Fault Codes</span></div><span class="shuttle_num1">1</span></div>
                                <div class="shuttle_manage_sub1_sub" style="margin-left: 2%;"><div><span>Alerts</span></div><span class="shuttle_num1" style="color: grey !important;">2</span></div>
                            </div>
                            <div class="shuttle_manage_logo">
                                <i class="fas fa-user shuttle_user_logo"></i>
                            </div>
                        </div>
                        <div class="shuttle_manage_body_sub">
                            <div class="shuttle_manage_sub1">
                                <i class="list-icon fas fa-bus shuttle_busi"></i>
                                <span class="shuttle_busi_span">Shuttle 1</span>
                            </div>
                            <div class="shuttle_manage_sub1">
                                <span style="margin-left: 5px;">License Plate</span><br>
                                <span style="margin-left: 5px;">VIN</span>
                            </div>
                            <div class="shuttle_manage_sub1" style="background-color: white !important;">
                                <div class="shuttle_manage_sub1_sub"><div><span>Fault Codes</span></div><span class="shuttle_num1" style="color: grey !important;">0</span></div>
                                <div class="shuttle_manage_sub1_sub" style="margin-left: 2%;"><div><span>Alerts</span></div><span class="shuttle_num1" style="color: grey !important;">4</span></div>
                            </div>
                            <div class="shuttle_manage_logo">
                                <i class="fas fa-user shuttle_user_logo"></i>
                            </div>
                        </div>
                        <div class="shuttle_manage_body_sub">
                            <div class="shuttle_manage_sub1">
                                <i class="list-icon fas fa-bus shuttle_busi"></i>
                                <span class="shuttle_busi_span">Shuttle 1</span>
                            </div>
                            <div class="shuttle_manage_sub1">
                                <span style="margin-left: 5px;">License Plate</span><br>
                                <span style="margin-left: 5px;">VIN</span>
                            </div>
                            <div class="shuttle_manage_sub1" style="background-color: white !important;">
                                <div class="shuttle_manage_sub1_sub"><div><span>Fault Codes</span></div><span class="shuttle_num1">2</span></div>
                                <div class="shuttle_manage_sub1_sub" style="margin-left: 2%;"><div><span>Alerts</span></div><span class="shuttle_num1" style="color: grey !important;">3</span></div>
                            </div>
                            <div class="shuttle_manage_logo">
                                <i class="fas fa-user shuttle_user_logo"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="shuttle_item3">
                    <span class="item_title">Hours of Operation</span>
                    <div class="shuttle_operation_body">
                        <div class="shuttle_operation_first">
                            <span>View Week</span><span style="float: right;margin-right: 20px;">Daily Average</span>
                            <div class="shuttle_operation_checkbody">
                                <div class="shuttle_operation_checkbody_sub" style="background-color: #a5c8f2;"><i class="far fa-check-circle shutt_check"></i><span class="shutt_check_span">Shuttl 1</span></div>
                                <div class="shuttle_operation_checkbody_sub" style="background-color: grey;"><i class="far fa-check-circle shutt_check"></i><span class="shutt_check_span">Shuttl 2</span></div>
                                <div class="shuttle_operation_checkbody_sub" style="background-color: #ffeeee;"><i class="far fa-check-circle shutt_check"></i><span class="shutt_check_span">Shuttl 3</span></div>
                            </div>
                        </div>
                        <div class="shuttle_operation_second">
                            <div id="barMorris1" style="position: absolute;margin-top: -210px;"></div>
                        </div>
                        <div class="col-md-12 col-sm-6 mr-b-40 center shutt_knob">
                            <input data-plugin="knob" data-width="100" data-height="100" data-angleOffset="180" data-linecap="round" data-fgColor="#4990E2" value="90"  id="input_percent" name="input_percent"/>
                        </div>

                    </div>
                </div>
                <div class="shuttle_item3" style="margin-left: 2%;">

                    <span class="item_title">Hours of Operation</span>
                    <div class="shuttle_operation_body">
                        <div class="shuttle_operation_first">
                            <span>View Week</span><span style="float: right;margin-right: 20px;">Daily Average</span>
                            <div class="shuttle_operation_checkbody">
                                <div class="shuttle_operation_checkbody_sub" style="background-color: #a5c8f2;"><i class="far fa-check-circle shutt_check"></i><span class="shutt_check_span">Shuttl 1</span></div>
                                <div class="shuttle_operation_checkbody_sub" style="background-color: grey;"><i class="far fa-check-circle shutt_check"></i><span class="shutt_check_span">Shuttl 2</span></div>
                                <div class="shuttle_operation_checkbody_sub" style="background-color: #ffeeee;"><i class="far fa-check-circle shutt_check"></i><span class="shutt_check_span">Shuttl 3</span></div>
                            </div>
                        </div>
                        <div class="shuttle_operation_second">
                            <div id="barMorris" style="position: absolute;margin-top: -210px;"></div>
                        </div>
                        <div class="col-md-12 col-sm-6 mr-b-40 center shutt_knob">
                            <input data-plugin="knob" data-width="100" data-height="100" data-angleOffset="180" data-linecap="round" data-fgColor="#4990E2" value="90"  id="input_percent" name="input_percent"/>
                        </div>

                    </div>
                </div>
            </div>
        </div><!-- /.widget-list -->

    </main><!-- /.main-wrappper -->

@endsection


@section("styles")
    <link href="{{asset("assets/vendors/linea-icons/styles.css")}}" rel="stylesheet" type="text/css"/>
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
    <link href="{{asset("assets/css/new_style.css")}}" rel="stylesheet" type="text/css"/>
@endsection


@section("scripts")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.2/umd/popper.min.js"></script>
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