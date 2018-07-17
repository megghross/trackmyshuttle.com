<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Live Tracking Version 2</title>
    <link rel="stylesheet" href="{{asset("assets/livetracking/css/bootstrap.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/livetracking/css/bootstrap-colorpicker.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/livetracking/css/font-awesome.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/livetracking/css/style.css")}}">
    <script src="{{asset("assets/livetracking/js/jquery-3.2.1.min.js")}}"></script>
    <script src="{{asset("assets/livetracking/js/bootstrap.min.js")}}"></script>
    <script src="{{asset("assets/livetracking/js/bootstrap-colorpicker.min.js")}}"></script>
</head>
<body  data-url="{{route("util.getShuttles")}}" data-fetchRouteUrl="{{route('livetracking.fetchRoute')}}" data-baseurl="{{asset("assets/livetracking/")}}">
<div class="routes-edit-page-container">
    <!--    <div class="loader"></div>-->
    <div class="routes-edit-page-container">
        <div class="loader"></div>

        <div id="map"></div>

        <div class="route-new">
            <a href="#"><img src="{{asset("assets/livetracking/img/busses.png")}}" alt="New">
                <div class="desc">Show all shuttles</div></a>
        </div>
        <div class="routes-box">

        </div>
        <div class="mapLeftTop">
            <div class="route-details">



            </div>
            <div class="eta-details"> Loading ETA!</div>
        </div>
    </div>


</div>

<div id="deleteModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Alert Information</h4>
            </div>
            <div class="modal-body">
                <p>Are you really going to delete?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="delete-yes">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div id="alert-Modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Alert Information</h4>
            </div>
            <div class="modal-body">
                <p id="alert-content"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDNSD8o2CyNEWb73m62IUL9i7T4i9TF3rM&libraries=geometry,drawing,places"></script>
<script src="https://client.brainyloft.com/staging/trackmyshuttle/public/js/app.js"></script>

<script src="{{asset("custom-js/RouteHelper.js")}}"></script>
<script src="{{asset("custom-js/LiveTrackingHelper.js")}}"></script>
<script src="{{asset("custom-js/GoogleMapsV3.js")}}"></script>
</body>
</html>
