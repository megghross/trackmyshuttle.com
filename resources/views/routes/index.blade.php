@extends('layouts.main')

@section('content')

    <main class="main-wrapper clearfix">

        <!-- Page Title Area -->
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">{{_("Routes")}}</h6>
            </div><!-- /.page-title-left -->

        </div><!-- /.page-title -->

        <!-- =================================== -->
        <!-- Different data widgets ============ -->
        <!-- =================================== -->
        <div class="widget-list">
            <div class="row">

                <iframe src="{{route('iframe.routes')}}" width="100%" height="630"></iframe>

{{--                @include("");--}}


                {{--<iframe src="liveTracking/test.php" width="100%" height="630"></iframe>--}}

            </div><!-- /.row -->
        </div><!-- /.widget-list -->
    </main>

@endsection


@section("styles")
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.1/jquery.toast.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.css" rel="stylesheet" type="text/css"/>
@endsection


@section("scripts")
    <!-- Scripts -->
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.1/jquery.toast.min.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.min.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Knob/1.2.13/jquery.knob.min.js"></script>--}}
    {{--<script src="assets/vendors/charts/excanvas.js"></script>--}}
    <script src="assets/js/theme.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/svg.js"></script>

    {{--<script src="http://localhost:35729/livereload.js"></script>--}}




@endsection