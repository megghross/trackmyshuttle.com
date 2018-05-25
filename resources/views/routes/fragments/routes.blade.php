
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Test Route</title>
    <link rel="stylesheet" href="{{asset("assets/route/css/bootstrap.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/route/css/bootstrap-colorpicker.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/route/css/font-awesome.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/route/css/style.css")}}">
    <script src="{{asset("assets/route/js/jquery-3.2.1.min.js")}}"></script>
    <script src="{{asset("assets/route/js/bootstrap.min.js")}}"></script>
    <script src="{{asset("assets/route/js/bootstrap-colorpicker.min.js")}}"></script>
</head>
<body data-url="{{route("routes.main")}}" data-baseurl="{{asset("assets/route/")}}">
<div class="routes-edit-page-container">
    <div class="loader"></div>
    <div id="map"></div>
    <div class="route-new">
        <a href="#"><img src="{{asset("assets/route/img/new.png")}}" alt="New">
            <div class="desc">New</div></a>
    </div>
    <div class="routes-box">
        <!--
        <div class="route-box">
            <a href="#"><img src="img/route.png" alt="Routes">
            <div class="desc">Route-1</div></a>
        </div>
        -->
    </div>
    <div class="route-details"></div>
    <div class="route-create">
        <h4>Creating a new Route</h4>
        <div class="input-group">
            <span class="input-group-addon">Route Name</span>
            <input type="text"  class="form-control" placeholder="Route Name" id="route-create-name">
        </div>
        <div class="route-point-list"></div>
        <div class="route-point-add">
            <a href="#" id="route-point-add-address"><span class="glyphicon glyphicon-plus-sign"></span>&nbspAdd Address</a>&nbsp|&nbsp
            <a href="#" id="route-point-add-close"><span class="glyphicon glyphicon-ok"></span>&nbspClose Loop</a>&nbsp|&nbsp
            <a href="#" id="route-point-add-marker"><span class="glyphicon glyphicon-map-marker"></span>&nbspAdd Route Point</a>
        </div>
        <div class="route-create-bottom">
            <button type="button" class="btn btn-success" id="route-create-save">Create</button>
            <button type="button" class="btn btn-default" id="route-create-cancel">Cancel</button>
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCO9v3UBJDfOTFC_wcIK7UzhLRjBWQIJ9M&libraries=geometry,drawing,places"></script>
<script src="{{asset("custom-js/googlemap.js")}}"></script>
</body>
</html>
