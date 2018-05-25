@extends('layouts.main')

@section('content')

    <main class="main-wrapper clearfix">

        <!-- Page Title Area -->
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">{{_("Live Tracking")}}</h6>
            </div><!-- /.page-title-left -->

        </div><!-- /.page-title -->

        <!-- =================================== -->
        <!-- Different data widgets ============ -->
        <!-- =================================== -->
        <div class="widget-list">
            <div class="row">


                {{--@include("livetracking.fragments.livetracking");--}}
                <iframe src="{{route("iframe.livetracking")}}" width="100%" height="630"></iframe>

            </div><!-- /.row -->
        </div><!-- /.widget-list -->
    </main>
@endsection


@section("styles")
    {{--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />--}}
    {{--<title>Live Tracking</title>--}}


    {{--<link rel="stylesheet" href="{{asset("css/bootstrap.min.css")}}">--}}
    <link rel="stylesheet" href="{{asset("css/bootstrap-colorpicker.min.css")}}">
    <link rel="stylesheet" href="{{asset("css/font-awesome.min.css")}}">
    {{--<link rel="stylesheet" href="{{asset("css/style.css")}}">--}}
    <script src="{{asset("js/jquery-3.2.1.min.js")}}"></script>
    <script src="{{asset("js/bootstrap.min.js")}}"></script>
    <script src="{{asset("js/bootstrap-colorpicker.min.js")}}"></script>


@endsection


@section("scripts")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.2/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.0/metisMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.7.0/js/perfect-scrollbar.jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Knob/1.2.13/jquery.knob.min.js"></script>

    <script src="{{asset("assets/js/theme.js")}}"></script>
{{--    <script src="{{asset("custom-js/dashboard.js")}}"></script>--}}
    <script src="{{asset("assets/js/custom.js")}}"></script>
    <script src="{{asset("assets/js/svg.js")}}"></script>


    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDNSD8o2CyNEWb73m62IUL9i7T4i9TF3rM&libraries=geometry,drawing,places"></script>
    <script src="{{asset("custom-js/GoogleMapsV2.js")}}"></script>

@endsection