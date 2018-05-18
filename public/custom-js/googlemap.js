var map;
var infowindow;
var drawingManager;
var selectedShape;
var routes = [];
var markers = [];
var create_markers = [];
var editRouteObj = null;
var selectFlag = 1;
var directionsService;
var directionsDisplay;
$(document).ready(function () {

    $(document).on('keydown', '#route-create-name', function (e) {
        if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
            //Route Create
            $("#route-create-save").click();
        }
    });


    $(document).on('keydown', '#marker-edit-name', function (e) {
        if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
            //Marker Create
            $("a.marker-edit-save").click();
        }
    });
    $(document).on('click', '.route-details table tr', function () {
        if (infowindow) {
            infowindow.close();
        }
        var index = $(this).index();
        var route_name = $(".route-details table caption").text();
        var route_markers = [];
        var start_marker = markers.filter(function (obj) {
            return obj.customInfo.type == 'Start' && obj.customInfo.route == route_name;
        });
        route_markers.push(start_marker[0]);
        var waypoint_array = markers.filter(function (obj) {
            return obj.customInfo.type != 'Start' && obj.customInfo.type != 'End' && obj.customInfo.route == route_name;
        });
        for (var i = 0; i < waypoint_array.length; i++) {
            route_markers.push(waypoint_array[i]);
        }
        var end_marker = markers.filter(function (obj) {
            return obj.customInfo.type == 'End' && obj.customInfo.route == route_name;
        });
        route_markers.push(end_marker[0]);
        var marker = route_markers[index];

        setSelection(marker);
        map.setCenter(marker.getPosition());
        map.setZoom(16);
        editInfowindow(marker.getPosition(), marker);
    });
    $(document).on('click', '.route-details a', function () {
        if (infowindow) {
            infowindow.close();
        }
        var route_name = $(this).text();
        var bounds = new google.maps.LatLngBounds();
        var route = routes.find(item => item.customInfo.name === route_name);
        route.setMap(map);
        for (var i = 0; i < markers.length; i++) {
            if (markers[i].customInfo.route == route.customInfo.name) {
                markers[i].setMap(map);
                if (markers[i].customInfo.type == 'Start') {
                    markers[i].setOptions({zIndex: 3});
                }
            }
        }
        var path = route.getPath();
        setSelection(route);
        route.getPath().forEach(function (element, index) {
            bounds.extend(element)
        });
        map.fitBounds(bounds);
    });

    $(".route-new a").click(function () {
        if ($(".route-create").css("display") == "none") {
            editRouteObj = null;
            $(".route-details").hide();
            $(".route-create").show();
            $("#route-create-name").val("");
            $(".route-create .route-point-list").html("");
            for (var i = 0; i < routes.length; i++) {
                routes[i].setMap(null);
            }
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
        }
    });

    $("#route-point-add-address").click(function () {
        drawingManager.setDrawingMode(null);
        var Inputs = document.getElementsByClassName("route-point-address");
        var new_number = Inputs.length + 1;
        var htmlStr = [
            '<div class="input-group">',
            '<span class="input-group-addon route-point-number">' + new_number + '</span>',
            '<input type="text" class="form-control route-point-address">',
            '<span class="input-group-addon">',
            '<span class="route-point-search glyphicon glyphicon-map-marker"></span>',
            '</span>',
            '<span class="input-group-addon">',
            '<span class="route-point-remove glyphicon glyphicon-remove"></span>',
            '</span>',
            '</div>'
        ].join('');


        debugger;

        $(".route-create .route-point-list").append(htmlStr);
        var marker;
        if (editRouteObj == null) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(0, 0),
                customInfo: {
                    route: null,
                    type: 'Stop',
                    name: '',
                    address: null,
                    layer: 'marker',
                }
            });
        }
        else {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(0, 0),
                customInfo: {
                    route: editRouteObj.customInfo.name,
                    type: 'Stop',
                    name: '',
                    address: null,
                    layer: 'marker',
                }
            });
        }

        create_markers.push(marker);
        var acInputs = document.getElementsByClassName("route-point-address");
        var autocomplete = new google.maps.places.Autocomplete(acInputs[acInputs.length - 1]);

        autocompleteEvent(autocomplete);
        //autocomplete.inputId = acInputs[i].id;
    });

    $("#route-point-add-close").click(function () {
        drawingManager.setDrawingMode(null);
        if (create_markers.length < 2) return;
        if (create_markers[0].customInfo.address == null) return;
        var Inputs;
        Inputs = document.getElementsByClassName("route-point-address");
        var new_number = Inputs.length + 1;
        var htmlStr = [
            '<div class="input-group">',
            '<span class="input-group-addon route-point-number">' + new_number + '</span>',
            '<input type="text" class="form-control route-point-address">',
            '<span class="input-group-addon">',
            '<span class="route-point-search glyphicon glyphicon-map-marker"></span>',
            '</span>',
            '<span class="input-group-addon">',
            '<span class="route-point-remove glyphicon glyphicon-remove"></span>',
            '</span>',
            '</div>'
        ].join('');
        $(".route-create .route-point-list").append(htmlStr);
        Inputs = document.getElementsByClassName("route-point-address");
        Inputs[Inputs.length - 1].value = create_markers[0].customInfo.address;
        var icon_size = 20;
        icon = {
            url: "img/marker-End.png",
            scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
            origin: new google.maps.Point(0, 0), // origin
            anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
            labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
        };
        var marker = new google.maps.Marker({
            map: map,
            position: create_markers[0].getPosition(),
            icon: icon,
            draggable: true,
            customInfo: {
                route: null,
                type: 'End',
                name: create_markers[0].customInfo.name,
                address: create_markers[0].customInfo.address,
                layer: 'marker',
            }
        });

        create_markers.push(marker);
        var acInputs = document.getElementsByClassName("route-point-address");
        var autocomplete = new google.maps.places.Autocomplete(acInputs[acInputs.length - 1]);
        autocompleteEvent(autocomplete);
        if (create_markers.length > 2) {
            for (var i = 1; i < create_markers.length - 1; i++) {
                if (create_markers[i].customInfo.type == 'End') {
                    var icon = {
                        url: "img/marker-Stop.png",
                        scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                        origin: new google.maps.Point(0, 0), // origin
                        anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                        labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
                    };
                    create_markers[i].setOptions({
                        icon: icon
                    });
                    create_markers[i].customInfo.type = "Stop";
                }
            }
        }
        //map.setCenter(marker.getPosition());
        //map.setZoom(16);
        setSelection(marker);
        editInfowindow(marker.getPosition(), marker);
        addEvent(marker);
        displayRoute();
    });

    $("#route-point-add-marker").click(function () {
        var icon_size = 20;
        if (create_markers.length == 0) {
            var icon = {
                url: "img/marker-Start.png", // url
                scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                origin: new google.maps.Point(0, 0), // origin
                anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
            };
        } else {
            var icon = {
                url: "img/marker-End.png", // url
                scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                origin: new google.maps.Point(0, 0), // origin
                anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
            };
        }

        drawingManager.setOptions({
            drawingMode: google.maps.drawing.OverlayType.MARKER,
            markerOptions: {
                icon: icon,
                draggable: true,
                zIndex: 2
            }
        });
    });

    // $(document).on('change', '.route-point-address:focus', function () {
    //     $('.route-point-search').click();
    //     // var index = $(this).parent().index();
    //     // var address = $(this).val();
    //     // searchPlace(address, index);
    // });
    // $(document).on('change', '.route-point-address', function () {
    //     $('.route-point-search').click();
    //
    //     // var index = $(this).parent().index();
    //     // var address = $(this).val();
    //     // searchPlace(address, index);
    // });
    $(document).on('click', '.route-point-search', function () {
        debugger;
        var index = $(this).parent().parent().index();
        var address = $(this).parent().prev().val();
        if (address == '') return;
        searchPlace(address, index);
    });
    $(document).on('click', '.route-point-locate', function () {
        if (infowindow) {
            infowindow.close();
        }
        var index = $(this).data("indexkey");
        var route_name = $(".route-details table caption").text();
        var route_markers = [];
        var start_marker = markers.filter(function (obj) {
            return obj.customInfo.type == 'Start' && obj.customInfo.route == route_name;
        });
        route_markers.push(start_marker[0]);
        var waypoint_array = markers.filter(function (obj) {
            return obj.customInfo.type != 'Start' && obj.customInfo.type != 'End' && obj.customInfo.route == route_name;
        });
        for (var i = 0; i < waypoint_array.length; i++) {
            route_markers.push(waypoint_array[i]);
        }
        var end_marker = markers.filter(function (obj) {
            return obj.customInfo.type == 'End' && obj.customInfo.route == route_name;
        });
        route_markers.push(end_marker[0]);
        var marker = route_markers[index];

        setSelection(marker);
        map.setCenter(marker.getPosition());
        map.setZoom(16);
        editInfowindow(marker.getPosition(), marker);
    });
    $(document).on('click', '.route-point-remove', function () {
        var index = $(this).parent().parent().index();
        create_markers[index].setMap(null);
        create_markers.splice(index, 1);
        $(this).parent().parent().remove();
        var spans = document.getElementsByClassName("route-point-number");
        for (var i = 0; i < spans.length; i++) {
            spans[i].innerHTML = i + 1;
        }
        if (create_markers.length > 1) {
            var icon_size = 20;
            var icon;
            icon = {
                url: "img/marker-Start.png",
                scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                origin: new google.maps.Point(0, 0), // origin
                anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
            };
            create_markers[0].setOptions({icon: icon});
            create_markers[0].customInfo.type = "Start";
            icon = {
                url: "img/marker-End.png",
                scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                origin: new google.maps.Point(0, 0), // origin
                anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
            };
            create_markers[create_markers.length - 1].setOptions({icon: icon});
            create_markers[create_markers.length - 1].customInfo.type = "End";
            displayRoute();
        } else if (create_markers.length == 1) {
            icon = {
                url: "img/marker-Start.png",
                scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                origin: new google.maps.Point(0, 0), // origin
                anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
            };
            create_markers[0].setOptions({icon: icon});
            create_markers[0].customInfo.type = "Start";
            directionsDisplay.setMap(null);
        }
    });
    $(document).on('click', 'a.marker-edit-delete', function () {
        if (selectedShape == null) return;
        deleteSelectedShape();
    });
    $("#route-create-cancel").click(function () {
        if (selectFlag == 0) return;
        drawingManager.setDrawingMode(null);
        $(".route-create").hide();
        for (var i = 0; i < create_markers.length; i++) {
            create_markers[i].setMap(null);
        }
        create_markers = [];
        directionsDisplay.setMap(null);
    });

    $("#route-create-save").click(function () {
        drawingManager.setDrawingMode(null);
        createRoute();
    });


    $(document).on('click', 'a.marker-edit-save', function () {

        selectedShape.customInfo.name = $("#marker-edit-name").val();
        selectedShape.customInfo.type = $("#marker-edit-type").val();

        if (selectedShape.customInfo.name == "") {
            $("#alert-content").html("Alias name cannot be empty");
            $("#alert-Modal").modal('show');
            return;
        }
        var icon_size = 24;
        var icon = {
            url: "img/marker-" + selectedShape.customInfo.type + ".png",
            scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
            origin: new google.maps.Point(0, 0), // origin
            anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
            labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
        };
        selectedShape.setOptions({
            icon: icon
        });
        if (infowindow) {
            infowindow.close();
        }
        displayRoute();
    });
    $(document).on('click', 'a.marker-edit-save-edit', function () {

        selectedShape.customInfo.name = $("#marker-edit-name").val();
        selectedShape.customInfo.type = $("#marker-edit-type").val();
        if (selectedShape.customInfo.name == "") {
            $("#alert-content").html("Alias name cannot be empty");
            $("#alert-Modal").modal('show');
            return;
        }


        var icon_size = 24;
        var icon = {
            url: "img/marker-" + selectedShape.customInfo.type + ".png",
            scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
            origin: new google.maps.Point(0, 0), // origin
            anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
            labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
        };
        selectedShape.setOptions({
            icon: icon
        });
        if (infowindow) {
            infowindow.close();
        }

    });


    $(document).on('click', 'a.marker-route-delete', function (event) {
        if (selectFlag == 0) return;

        var tempRoutes = routes.filter(function (obj) {
            return obj.customInfo.name == selectedShape.customInfo.route;
        });
        selectedShape = tempRoutes[0];
        $("#deleteModal").modal('show');
    });


    $(document).on('click', 'a.marker-route-edit', function (event) {
        create_markers = [];
        var shape = null;
        if (selectedShape.customInfo.layer == "marker") {
            var tempRoutes = routes.filter(function (obj) {
                return obj.customInfo.name == selectedShape.customInfo.route;
            });
            shape = selectedShape;
            tempRoutes[0].customInfo.isEdit = true;
            editInfowindow(selectedShape.position, selectedShape);
            selectedShape = tempRoutes[0];

        }
        else {
            shape = selectedShape;

            selectedShape.customInfo.isEdit = true;
        }

        editRouteObj = selectedShape;
        var tempMarkers = markers.filter(function (obj) {
            return obj.customInfo.route == selectedShape.customInfo.name;
        });
        //Route Edit Mode
        $(".route-details").hide();
        $(".route-create").show();
        $(".route-create").find('h4').html("Edit Route");
        $(".route-create").find('#route-create-save').html("Update Route");
        $("#route-create-name").val(selectedShape.customInfo.name);
        $(".route-create .route-point-list").html("");
        for (var i = 0; i < routes.length; i++) {
            routes[i].setMap(null);
        }
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        for (var i = 0; i < tempMarkers.length; i++) {
            tempMarkers[i].setMap(map);
            var Inputs;
            Inputs = document.getElementsByClassName("route-point-address");
            var new_number = Inputs.length + 1;
            var htmlStr = [
                '<div class="input-group">',
                '<span class="input-group-addon route-point-number">' + new_number + '</span>',
                '<input type="text" value="' + tempMarkers[i].customInfo.address + '" class="form-control route-point-address">',
                '<span class="input-group-addon">',
                '<span data-indexkey="' + i + '" class="route-point-locate glyphicon glyphicon-map-marker"></span>',
                '</span>',
                '<span class="input-group-addon">',
                '<span class="route-point-remove glyphicon glyphicon-remove"></span>',
                '</span>',
                '</div>'
            ].join('');
            $(".route-create .route-point-list").append(htmlStr);
            create_markers.push(tempMarkers[i]);
        }
        selectedShape.setMap(map);


        selectedShape = shape;


    });
    $(document).on('click', 'a.route-edit-delete', function (event) {
        if (selectFlag == 0) return;
        $("#deleteModal").modal('show');
    });
    $("#delete-yes").click(function () {
        if (infowindow) {
            infowindow.close();
        }
        $(".route-details").hide();
        deleteSelectedShape();
    });
    /*
    $(document).on('click','a.route-edit-vertex',function(event){
        if (infowindow) {infowindow.close();}
        setSelection(selectedShape);
        selectedShape.setOptions({
            strokeWeight:3,
            editable:true
        });
    });
    */
    $(document).on('click', 'a.route-edit-cancel', function (event) {
        if (selectFlag == 0) return;
        if (infowindow) {
            infowindow.close();
        }
        var origin_color = selectedShape.customInfo.color;
        selectedShape.setOptions({
            strokeColor: origin_color
        });
    });
    $(document).on('click', 'a.route-edit-save', function (event) {
        selectFlag = 0;
        if ($("#route-name").val() == '') {
            $("#alert-content").html("Please input route name");
            $("#alert-Modal").modal('show');
            selectFlag = 1;
            return;
        }
        var route_data = {
            id: selectedShape.customInfo.id,
            name: $("#route-name").val(),
            color: $("#route-color").val(),
        };
        $(".loader").css("display", "block");
        $.ajax({
            type: "POST",
            url: "routes-edit-callback.php",
            data: {table: 'route', action: 'update', data: JSON.stringify(route_data)},
            cache: false,
            timeout: 20000,
            success: function (response) {
                var json = JSON.parse(response);
                if (json.result == 'success') {
                    if (infowindow) {
                        infowindow.close();
                    }
                    $(".loader").css("display", "none");
                    $('.route-box a').filter(function () {
                        return $(this).text() === selectedShape.customInfo.name;
                    }).find("div.desc").html(route_data.name);
                    for (var i = 0; i < markers.length; i++) {
                        if (markers[i].customInfo.route == selectedShape.customInfo.name) {
                            markers[i].customInfo.route = route_data.name;
                        }
                    }
                    selectedShape.customInfo.name = route_data.name;
                    selectedShape.customInfo.color = route_data.color;
                } else {
                    $(".loader").css("display", "none");
                    $("#alert-content").html("Route name is duplicated");
                    $("#alert-Modal").modal('show');
                }
                if (infowindow) {
                    infowindow.close();
                }
                selectFlag = 1;
            },
            error: function (xmlhttprequest, textstatus, message) {
                if (textstatus === "timeout") {
                    $("#alert-content").html("Server Connection Failed");
                    $("#alert-Modal").modal('show');
                } else {
                    $("#alert-content").html(textstatus);
                    $("#alert-Modal").modal('show');
                }
                selectFlag = 1;
                $(".loader").css("display", "none");
            }
        });

    });

    $(document).on('click', '.route-box', function (event) {
        for (var i = 0; i < create_markers.length; i++) {
            create_markers[i].setMap(null);
        }
        create_markers = [];
        directionsDisplay.setMap(null);

        for (var i = 0; i < routes.length; i++) {
            routes[i].setMap(null);
        }
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        $(".route-create").hide();
        if (infowindow) {
            infowindow.close();
        }
        var route_name = $(this).text();
        var bounds = new google.maps.LatLngBounds();
        var route = routes.find(item => item.customInfo.name === route_name);
        route.setMap(map);
        for (var i = 0; i < markers.length; i++) {
            if (markers[i].customInfo.route == route.customInfo.name) {
                markers[i].setMap(map);
                if (markers[i].customInfo.type == 'Start') {
                    markers[i].setOptions({zIndex: 3});
                }
            }
        }
        var path = route.getPath();
        setSelection(route);
        route.getPath().forEach(function (element, index) {
            bounds.extend(element)
        });
        map.fitBounds(bounds);
        route_details(route_name);
    });
});

function autocompleteEvent(autoComplete) {
    autoComplete.addListener('place_changed', function () {
        //Register the destination markup
        $('.route-point-search').click();

    });
}

function initMap() {
    var mapOptions = {
        center: new google.maps.LatLng(38.9071923, -77.0368707),
        zoom: 16,
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL,
            position: google.maps.ControlPosition.LEFT_BOTTOM
        },
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.HYBRID, google.maps.MapTypeId.TERRAIN],
            style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,  // HORIZONTAL_BAR DROPDOWN_MENU
            position: google.maps.ControlPosition.TOP_RIGHT
        },
        mapTypeId: 'roadmap',
        streetViewControl: true,
        streetViewControlOptions: {
            position: google.maps.ControlPosition.LEFT_BOTTOM
        },
        fullscreenControl: true,
        fullscreenControlOptions: {
            position: google.maps.ControlPosition.RIGHT_TOP
        }

        //scaleControl:false
        //disableDefaultUI: true
    };

    map = new google.maps.Map(document.getElementById('map'), mapOptions);
    var clickHandler = new ClickEventHandler(map, new google.maps.LatLng(38.9071923, -77.0368707));
    directionsService = new google.maps.DirectionsService();
    directionsDisplay = new google.maps.DirectionsRenderer({
        suppressMarkers: true,
        draggable: false,
        //polylineOptions:
        map: map
    });
    infowindow = new google.maps.InfoWindow({
        content: ""
    });

    drawingManager = new google.maps.drawing.DrawingManager({
        drawingMode: null,
        drawingControl: false,  // show or hide drawing toolbar
        drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_LEFT,
            drawingModes: ['marker', 'polyline', 'polygon']
        },
        map: map
    });

    load_data();
    google.maps.event.addListener(drawingManager, 'overlaycomplete', function (e) {
        var newShape = e.overlay;
        newShape.type = e.type;
        // Switch back to non-drawing mode after drawing a shape.
        drawingManager.setDrawingMode(null);
        if (create_markers.length == 0) {
            newShape.customInfo = {
                route: null,
                name: 'New Place',
                type: 'Start',
                address: null,
                layer: 'marker',
            }
        } else {
            newShape.customInfo = {
                route: null,
                name: 'New Place',
                type: 'End',
                address: null,
                layer: 'marker',
            }
        }
        create_markers.push(newShape);
        if (create_markers.length > 2) {
            for (var i = 1; i < create_markers.length - 1; i++) {
                if (create_markers[i].customInfo.type == 'End') {
                    var icon_size = 20;
                    var icon = {
                        url: "img/marker-Stop.png", // url
                        scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                        origin: new google.maps.Point(0, 0), // origin
                        anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                        labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
                    };
                    create_markers[i].setOptions({icon: icon});
                    create_markers[i].customInfo.type = "Stop";
                }
            }
        }
        setSelection(newShape);
        addEvent(newShape);
        var Inputs = document.getElementsByClassName("route-point-address");
        var new_number = Inputs.length + 1;
        var htmlStr = [
            '<div class="input-group">',
            '<span class="input-group-addon route-point-number">' + new_number + '</span>',
            '<input type="text" class="form-control route-point-address">',
            '<span class="input-group-addon">',
            '<span class="route-point-search glyphicon glyphicon-map-marker"></span>',
            '</span>',
            '<span class="input-group-addon">',
            '<span class="route-point-remove glyphicon glyphicon-remove"></span>',
            '</span>',
            '</div>'
        ].join('');
        $(".route-create .route-point-list").append(htmlStr);
        var acInputs = document.getElementsByClassName("route-point-address");
        var autocomplete = new google.maps.places.Autocomplete(acInputs[acInputs.length - 1]);
        autocompleteEvent(autocomplete);
        searchAddress(newShape);
    });
    google.maps.event.addListener(map, 'click', clearSelection);

}

function clearSelection() {


    if (selectFlag == 0) return;
    if ($('a.marker-edit-save-edit')) {
        if ($("#marker-edit-name").val() == "") {
            $("#marker-edit-name").val("New Place");
        }
        $('a.marker-edit-save-edit').click();
    }
    if ($('a.marker-edit-save')) {
        if ($("#marker-edit-name").val() == "") {
            $("#marker-edit-name").val("New Place");
        }
        $('a.marker-edit-save').click();
    }
    if (infowindow) {
        infowindow.close();
    }

    if (selectedShape) {
        if (selectedShape.customInfo.layer == 'marker') {
            var icon_size = 20;
            var icon = {
                url: "img/marker-" + selectedShape.customInfo.type + ".png",
                scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                origin: new google.maps.Point(0, 0), // origin
                anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
            };
            if (selectedShape.customInfo.type == 'Start') {
                selectedShape.setOptions({
                    icon: icon,
                    zIndex: 3
                });
            } else {
                selectedShape.setOptions({
                    icon: icon,
                    zIndex: 2
                });
            }
            if (selectedShape.customInfo.route == null) {
                if (create_markers.length > 0) {
                    var index = create_markers.indexOf(selectedShape) + 1;
                    $(".route-point-list > .input-group").css("border", "none");
                }
            } else {
                $(".route-details table tr").css("border", "none");
            }
        } else if (selectedShape.customInfo.layer == 'route') {
            selectedShape.setOptions({
                strokeWeight: 3,
                editable: false
            });
        }
        selectedShape = null;
    }
}

function setSelection(shape) {
    if (selectFlag == 0) return;
    clearSelection();
    selectedShape = shape;
    if (selectedShape.customInfo.layer == 'marker') {
        var icon_size = 24;
        var icon = {
            url: "img/marker-" + selectedShape.customInfo.type + ".png",
            scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
            origin: new google.maps.Point(0, 0), // origin
            anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
            labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
        };
        selectedShape.setOptions({
            icon: icon,
            zIndex: 3
        });
        if (selectedShape.customInfo.route == null) {
            if (create_markers.length > 0) {
                var index = create_markers.indexOf(selectedShape) + 1;
                $(".route-point-list .input-group:nth-child(" + index + ")").css("border", "1px solid #ff0000");
                $(".route-point-list .input-group:nth-child(" + index + ")").css("border-radius", "4px");
            }
        } else {
            var route_markers = [];
            var start_marker = markers.filter(function (obj) {
                return obj.customInfo.type == 'Start' && obj.customInfo.route == selectedShape.customInfo.route;
            });
            route_markers.push(start_marker[0]);
            var waypoint_array = markers.filter(function (obj) {
                return obj.customInfo.type != 'Start' && obj.customInfo.type != 'End' && obj.customInfo.route == selectedShape.customInfo.route;
            });
            for (var i = 0; i < waypoint_array.length; i++) {
                route_markers.push(waypoint_array[i]);
            }
            var end_marker = markers.filter(function (obj) {
                return obj.customInfo.type == 'End' && obj.customInfo.route == selectedShape.customInfo.route;
            });
            route_markers.push(end_marker[0]);
            var index = route_markers.indexOf(selectedShape) + 1;
            $(".route-details table tr:nth-child(" + index + ")").css("border", "1px solid #ff0000");
        }
    } else if (selectedShape.customInfo.layer == 'route') {
        selectedShape.setOptions({
            strokeWeight: 5
            //editable:false
        });
    }
}

function deleteSelectedShape() {
    if (selectedShape == null) return;
    selectedShape.setMap(null);
    if (selectedShape.customInfo.layer == 'marker') {
        if (selectedShape.customInfo.route == null) {
            var index = create_markers.indexOf(selectedShape);
            create_markers.splice(index, 1);
            $(".route-point-list").children()[index].remove();
            if (create_markers.length > 1) {
                var icon_size = 20;
                var icon;
                icon = {
                    url: "img/marker-Start.png",
                    scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                    origin: new google.maps.Point(0, 0), // origin
                    anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                    labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
                };
                create_markers[0].setOptions({icon: icon});
                create_markers[0].customInfo.type = "Start";
                icon = {
                    url: "img/marker-End.png",
                    scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                    origin: new google.maps.Point(0, 0), // origin
                    anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                    labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
                };
                create_markers[create_markers.length - 1].setOptions({icon: icon});
                create_markers[create_markers.length - 1].customInfo.type = "End";
                displayRoute();
            } else if (create_markers.length == 1) {
                icon = {
                    url: "img/marker-Start.png",
                    scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                    origin: new google.maps.Point(0, 0), // origin
                    anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                    labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
                };
                create_markers[0].setOptions({icon: icon});
                create_markers[0].customInfo.type = "Start";
                directionsDisplay.setMap(null);
            }
        }
    } else if (selectedShape.customInfo.layer == 'route') {
        var index = routes.indexOf(selectedShape);
        routes.splice(index, 1);
        var deleteMarkers = markers.filter(function (obj) {
            return obj.customInfo.route == selectedShape.customInfo.name;
        });
        for (var i = 0; i < deleteMarkers.length; i++) {
            deleteMarkers[i].setMap(null);
        }
        markers = markers.filter(function (obj) {
            return obj.customInfo.route != selectedShape.customInfo.name;
        });
        $('.route-box a').filter(function () {
            return $(this).text() === selectedShape.customInfo.name;
        }).parent().remove();
        if (selectedShape.customInfo.id != null) {
            //if id is not null ajax
            $.ajax({
                type: "POST",
                url: "routes-edit-callback.php",
                data: {
                    table: selectedShape.customInfo.layer,
                    action: "delete",
                    id: selectedShape.customInfo.id,
                    name: selectedShape.customInfo.name
                },
                cache: false,
                timeout: 5000,
                success: function (response) {

                    //alert(response);
                },
                error: function (xmlhttprequest, textstatus, message) {
                    if (textstatus === "timeout") {
                        $("#alert-content").html("Server Connection Failed");
                        $("#alert-Modal").modal('show');
                    } else {
                        $("#alert-content").html(textstatus);
                        $("#alert-Modal").modal('show');
                    }
                }
            });
        }
    }

}

function editInfowindow(point, shape) {

    debugger;
    if (selectFlag == 0) return;
    if (infowindow) {
        infowindow.close();
    }
    if (shape.customInfo.layer == 'marker') {
        if (shape.customInfo.route == null) {
            var contentString = [
                '<table class="table table-bordred table-striped" style="max-width:300px">',
                '<caption><h4>Marker Attributes</h4></caption>',
                '<tr><td style="vertical-align:middle">Name</td>',
                '<td><input type="text" class="form-control" id="marker-edit-name" value="' + shape.customInfo.name + '"></td></tr>',
                '<tr>',
                '<td style="vertical-align:middle">Type</td>',
                '<td><select class="form-control" id="marker-edit-type">',
                '<option value="Start">Start</option>',
                '<option value="Stop">Stop</option>',
                '<option value="Waypoint">WayPoint</option>',
                '<option value="End">End</option>',
                '</td></tr>',
                '<tr><td style="vertical-align:middle">Address</td>',
                '<td>' + shape.customInfo.address + '</td></tr>',
                '<tr><td></td><td>',
                '<a href="#" class="marker-edit-save"><span class="glyphicon glyphicon-ok-sign"></span>&nbspSave</a>&nbsp&nbsp|&nbsp&nbsp',
                '<a href="#" class="marker-edit-delete"><span class="glyphicon glyphicon-trash"></span>&nbspRemove</a>',
                '</td></tr></table>'
            ].join('');
            infowindow.setContent(contentString);
            infowindow.open(map, shape);
            $("#marker-edit-type").val(shape.customInfo.type);
        } else {
            var selectedRoute = routes.filter(function (obj) {
                return obj.customInfo.name == shape.customInfo.route;
            })[0];
            if (selectedRoute.customInfo.isEdit == true) {
                var contentString = [
                    '<table class="table table-bordred table-striped" style="max-width:300px">',
                    '<caption><h4>Marker Attributes</h4></caption>',
                    '<tr><td style="vertical-align:middle">Name</td>',
                    '<td><input type="text" class="form-control" id="marker-edit-name" value="' + shape.customInfo.name + '"></td></tr>',
                    '<tr>',
                    '<td style="vertical-align:middle">Type</td>',
                    '<td><select  class="form-control typeSelect" id="marker-edit-type">',
                    '<option value="Start" >Start</option>',
                    '<option value="Stop" >Stop</option>',
                    '<option value="Waypoint">WayPoint</option>',
                    '<option value="End">End</option>',
                    '</td></tr>',
                    '<tr><td style="vertical-align:middle">Address</td>',
                    '<td>' + shape.customInfo.address + '</td></tr>',
                    '<tr><td></td><td>',
                    '<a href="#" class="marker-edit-save-edit"><span class="glyphicon glyphicon-ok-sign"></span>&nbspSave</a>&nbsp&nbsp|&nbsp&nbsp',
                    '<a href="#" class="marker-edit-delete"><span class="glyphicon glyphicon-trash"></span>&nbspRemove</a>',
                    '</td></tr></table>'
                ].join('');
                infowindow.setContent(contentString);
                infowindow.open(map, shape);


                $(".table-bordred.table-striped").find(".typeSelect").val(shape.customInfo.type);

            }
            else {
                var contentString = ['<table class="table table-bordred table-striped" style="max-width:300px">',
                    '<caption><h4>Marker Attributes</h4></caption>',
                    '<tr><td style="vertical-align:middle">Route</td><td>' + shape.customInfo.route + '</td></tr>',
                    '<tr><td style="vertical-align:middle">Name</td><td>' + shape.customInfo.name + '</td></tr>',
                    '<tr><td style="vertical-align:middle">Type</td><td>' + shape.customInfo.type + '</td></tr>',
                    '<tr><td style="vertical-align:middle">Address</td><td>' + shape.customInfo.address + '</td></tr>',
                    '<tr><td></td><td>',
                    '<a href="#" class="marker-route-delete"><span class="glyphicon glyphicon-trash"></span>&nbspRemove Route</a>&nbsp',
                    '<a href="#" class="marker-route-edit"><span class="glyphicon glyphicon-edit"></span>&nbspEdit Route</a>&nbsp',
                    '</td></tr></table>'].join(' ');
                infowindow.setContent(contentString);
                infowindow.open(map, shape);
            }

        }
    } else if (shape.customInfo.layer == 'route') {
        selectedShape.customInfo.length = getLength(selectedShape);
        var contentString = '<table class="table table-bordred table-striped" style="max-width:300px">' +
            '<caption><h4>Route Attributes</h4></caption>' +
            '<tr><td style="vertical-align:middle">Name</td><td><input type="text" class="form-control" id="route-name" value="' + shape.customInfo.name + '"></td></tr>' +
            '<tr><td style="vertical-align:middle">Color</td><td><input type="text" class="form-control" style="background: '+shape.customInfo.color+'" id="route-color" value="' + shape.customInfo.color + '"></td></tr>' +
            '<tr><td style="vertical-align:middle">Length(mile)</td><td><input type="text" class="form-control" id="route-length" value="' + shape.customInfo.length + '" disabled></td></tr>' +
            '<tr><td></td><td><a href="#" class="route-edit-save"><span class="glyphicon glyphicon-floppy-disk"></span>&nbspSave</a>&nbsp|&nbsp' +
            '<a href="#" class="route-edit-vertex"><span class="glyphicon glyphicon-edit"></span>&nbspEdit</a>&nbsp|&nbsp' +
            // '<a href="#" class="route-edit-delete"><span class="glyphicon glyphicon-trash"></span>&nbspRemove</a>&nbsp|&nbsp'+
            '<a href="#" class="route-edit-cancel">Cancel</a></td></tr>' +
            '</table>';
        infowindow.setContent(contentString);
        infowindow.setPosition(point);
        infowindow.open(map);
        $("#route-color").colorpicker({
            customClass: 'colorpicker-2x',
            colorSelectors: {
                'black': '#000000',
                'white': '#ffffff',
                'red': '#FF0000',
                'default': '#777777',
                'primary': '#337ab7',
                'success': '#5cb85c',
                'info': '#5bc0de',
                'warning': '#f0ad4e',
                'danger': '#d9534f'
            }
        });
        $("#route-color").colorpicker().on('changeColor', function (e) {
            //row.symbol=e.color.toString('hex');
            selectedShape.setOptions({
                strokeColor: e.color
            });
            $("#route-color").css("background",  e.color);
        });
    }
}

function getLength(polyline) {
    var length = google.maps.geometry.spherical.computeLength(polyline.getPath()) / 1000 * 0.62137119223;
    return length.toFixed(2);
}

function load_data() {
    $.ajax({
        type: "POST",
        url: "/api/routes/load",
        data: {action: "load"},
        cache: false,
        timeout: 20000,
        success: function (data) {
            // var data = JSON.parse(response);
            var route_data = data.route_data;
            var marker_data = data.marker_data;
            for (var i = 0; i < route_data.length; i++) {
                var coordinates_str = route_data[i].coordinates;
                //var decodedPath = google.maps.geometry.encoding.decodePath(coordinates_str);
                var path = coordinates_str.split("\n");
                var coordinates = [];
                for (var j = 0; j < path.length; j++) {
                    var point = path[j].split(",");
                    coordinates.push(new google.maps.LatLng(parseFloat(point[0]), parseFloat(point[1])));
                }

                var polyline = new google.maps.Polyline({
                    path: coordinates,
                    strokeColor: route_data[i].color,
                    strokeOpacity: 1,
                    strokeWeight: 3,
                    zIndex: 1,
                    customInfo: {
                        id: route_data[i].id,
                        name: route_data[i].name,
                        color: route_data[i].color,
                        length: route_data[i].length,
                        layer: 'route',
                        isEdit: false
                    }
                });
                //polyline.setMap(map);
                var htmlStr = '<div class="route-box"><a href="#"><img src="img/route.png" alt="Routes"><div class="desc">' + route_data[i].name + '</div></a></div>';
                $(".routes-box").append(htmlStr);
                routes.push(polyline);
                addEvent(polyline);
            }
            for (var i = 0; i < marker_data.length; i++) {
                var lat = parseFloat(marker_data[i].lat);
                var lng = parseFloat(marker_data[i].lng);
                var point = new google.maps.LatLng(lat, lng);
                var icon_size = 20;
                var icon = {
                    url: "img/marker-" + marker_data[i].type + ".png",
                    scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                    origin: new google.maps.Point(0, 0), // origin
                    anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size) / 2), // anchor
                    labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
                };
                var marker = new google.maps.Marker({
                    position: point,
                    icon: icon,
                    zIndex: 2,
                    //title:
                    customInfo: {
                        route: marker_data[i].route,
                        name: marker_data[i].name,
                        type: marker_data[i].type,
                        address: marker_data[i].address,
                        layer: 'marker',
                    }
                });
                //marker.setMap(map);
                markers.push(marker);
                addEvent(marker);
            }
            $(".loader").css("display", "none");
        },
        error: function (xmlhttprequest, textstatus, message) {
            if (textstatus === "timeout") {
                $("#alert-content").html("Server Connection Failed");
                $("#alert-Modal").modal('show');
            } else {
                $("#alert-content").html(textstatus);
                $("#alert-Modal").modal('show');
            }
        }
    });
}

function addEvent(shape) {
    if (shape.customInfo.layer == 'marker') {
        google.maps.event.addListener(shape, 'click', function (e) {
            if (shape.editable) return;
            setSelection(shape);
            editInfowindow(e.latLng, shape);
        });
        if (shape.customInfo.route == null) {
            /*
            google.maps.event.addListener(shape, 'dragstart', function() {

            });
            google.maps.event.addListener(shape, 'drag', function() {

            });
            */
            google.maps.event.addListener(shape, 'dragend', function (event) {
                setSelection(shape);
                searchAddress(shape);
            });
        }

    } else if (shape.customInfo.layer == 'route') {
        google.maps.event.addListener(shape, 'click', function (e) {
            if (e.vertex !== undefined) {
                var path = shape.getPath();
                path.removeAt(e.vertex);
                if (path.length < 2) {
                    //delete
                    deleteSelectedShape();
                }
            } else {
                setSelection(shape);
            }
        });
        google.maps.event.addListener(shape, 'click', function (e) {
            if (shape.editable) return;
            setSelection(shape);
            editInfowindow(e.latLng, shape);
            route_details(shape.customInfo.name);
        });
    }
}

function displayRoute() {


    debugger;
    var route_markers = create_markers.filter(function (obj) {
        return obj.customInfo.address != null;
    });
    if (route_markers.length < 2) return;
    var start_array = route_markers.filter(function (obj) {
        return obj.customInfo.type == 'Start';
    });
    if (start_array.length == 0 || start_array.length > 1) return;
    var end_array = route_markers.filter(function (obj) {
        return obj.customInfo.type == 'End';
    });
    if (end_array.length == 0 || end_array.length > 1) return;
    var waypoint_array = route_markers.filter(function (obj) {
        return obj.customInfo.type == 'Stop' || obj.customInfo.type == 'Waypoint';
    });

    var start_point = start_array[0].getPosition();
    var end_point = end_array[0].getPosition();
    //define waypoints
    var wayPoints = [];
    for (var i = 0; i < waypoint_array.length; i++) {
        if (waypoint_array[i].customInfo.type == 'Stop') {
            wayPoints.push({
                location: waypoint_array[i].getPosition(),
                stopover: true
            });
        } else if (waypoint_array[i].customInfo.type == 'Waypoint') {
            wayPoints.push({
                location: waypoint_array[i].getPosition(),
                stopover: false
            });
        }
    }
    var request = {
        origin: start_point,
        destination: end_point,
        waypoints: wayPoints,
        optimizeWaypoints: true,
        travelMode: google.maps.TravelMode.DRIVING
    };


    directionsService.route(request, function (response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            //console.log(JSON.stringify(response));
            directionsDisplay.setDirections(response);
            directionsDisplay.setMap(map);
        }
    });
}

function createRoute() {

    if ($("#route-create-name").val() == '') {
        $("#alert-content").html("Please input a new route name");
        $("#alert-Modal").modal('show');
        return;
    }


    if(editRouteObj!=null){
        selectedShape = editRouteObj;
        if (selectedShape.customInfo.layer == 'marker') {
            if (selectedShape.customInfo.route != null) {

                selectedRoute = routes.filter(function (obj) {
                    return obj.customInfo.name == selectedShape.customInfo.route;
                })[0];

                editRoute(selectedRoute);
                return;

            }
            else {

            }
        }
        else if (selectedShape.customInfo.layer == 'route') {
            if (selectedShape.customInfo.name != null) {

                editRoute(selectedShape);
                return;

            }
            else {

            }
        }
    }


    var route_name_check_array = routes.filter(function (obj) {
        return obj.customInfo.name == $("#route-create-name").val();
    });
    if (route_name_check_array.length > 0) {
        $("#alert-content").html("Route name is duplicated");
        $("#alert-Modal").modal('show');
        return;
    }
    var route_markers = create_markers.filter(function (obj) {
        return obj.customInfo.address != null;
    });
    if (route_markers.length < 2) {
        $("#alert-content").html("Please add more than 2 markers from search");
        $("#alert-Modal").modal('show');
        return;
    }

    var start_array = route_markers.filter(function (obj) {
        return obj.customInfo.type == 'Start';
    });
    if (start_array.length == 0 || start_array.length > 1) {
        $("#alert-content").html("Error: Start Point doesn't exsist or is duplicated");
        $("#alert-Modal").modal('show');
        return;
    }
    var end_array = route_markers.filter(function (obj) {
        return obj.customInfo.type == 'End';
    });
    if (end_array.length == 0 || end_array.length > 1) {
        $("#alert-content").html("Error: End Point doesn't exsist or is duplicated");
        $("#alert-Modal").modal('show');
        return;
    }
    var waypoint_array = route_markers.filter(function (obj) {
        return obj.customInfo.type == 'Stop' || obj.customInfo.type == 'Waypoint';
    });

    var start_point = start_array[0].getPosition();
    var end_point = end_array[0].getPosition();
    //define waypoints
    var wayPoints = [];
    for (var i = 0; i < waypoint_array.length; i++) {
        if (waypoint_array[i].customInfo.type == 'Stop') {
            wayPoints.push({
                location: waypoint_array[i].getPosition(),
                stopover: true
            });
        } else if (waypoint_array[i].customInfo.type == 'Waypoint') {
            wayPoints.push({
                location: waypoint_array[i].getPosition(),
                stopover: false
            });
        }
    }
    $(".loader").css("display", "block");

    var request = {
        origin: start_point,
        destination: end_point,
        waypoints: wayPoints,
        optimizeWaypoints: true,
        travelMode: google.maps.TravelMode.DRIVING
    };
    directionsService.route(request, function (response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            //console.log(JSON.stringify(response));

            directionsDisplay.setMap(null);

            var route = new google.maps.Polyline({
                path: [],
                geodesic: true,
                strokeColor: '#708090',
                strokeOpacity: 0.7,
                strokeWeight: 5
            });
            var bounds = new google.maps.LatLngBounds();

            var legs = response.routes[0].legs;
            for (var i = 0; i < legs.length; i++) {
                var steps = legs[i].steps;
                for (var j = 0; j < steps.length; j++) {
                    var nextSegment = steps[j].path;
                    for (var k = 0; k < nextSegment.length; k++) {
                        route.getPath().push(nextSegment[k]);
                        bounds.extend(nextSegment[k]);
                    }
                }
            }

            route.customInfo = {
                id: null,
                name: $("#route-create-name").val(),
                color: '#708090',
                length: getLength(route),
                layer: 'route',
            }

            map.fitBounds(bounds);
            route.setMap(map);

            routes.push(route);
            addEvent(route);
            for (var i = 0; i < route_markers.length; i++) {
                route_markers[i].customInfo.route = route.customInfo.name;
                route_markers[i].setOptions({draggable: false});
                markers.push(route_markers[i]);
            }
            create_markers = [];
            route_create_save(route, false);
        } else {
            $(".loader").css("display", "none");
            $("#alert-content").html(status);
            $("#alert-Modal").modal('show');
        }
    });
}

function editRoute(route) {


    var route_markers = create_markers.filter(function (obj) {
        return obj.customInfo.address != null;
    });
    if (route_markers.length < 2) {
        $("#alert-content").html("Please add more than 2 markers from search");
        $("#alert-Modal").modal('show');
        return;
    }

    var start_array = route_markers.filter(function (obj) {
        return obj.customInfo.type == 'Start';
    });
    if (start_array.length == 0 || start_array.length > 1) {
        $("#alert-content").html("Error: Start Point doesn't exsist or is duplicated");
        $("#alert-Modal").modal('show');
        return;
    }
    var end_array = route_markers.filter(function (obj) {
        return obj.customInfo.type == 'End';
    });
    if (end_array.length == 0 || end_array.length > 1) {
        $("#alert-content").html("Error: End Point doesn't exsist or is duplicated");
        $("#alert-Modal").modal('show');
        return;
    }
    var waypoint_array = route_markers.filter(function (obj) {
        return obj.customInfo.type == 'Stop' || obj.customInfo.type == 'Waypoint';
    });

    var start_point = start_array[0].getPosition();
    var end_point = end_array[0].getPosition();
    //define waypoints
    var wayPoints = [];
    for (var i = 0; i < waypoint_array.length; i++) {
        if (waypoint_array[i].customInfo.type == 'Stop') {
            wayPoints.push({
                location: waypoint_array[i].getPosition(),
                stopover: true
            });
        } else if (waypoint_array[i].customInfo.type == 'Waypoint') {
            wayPoints.push({
                location: waypoint_array[i].getPosition(),
                stopover: false
            });
        }
    }
    $(".loader").css("display", "block");
    var routeId = route.customInfo.id;
    var request = {
        origin: start_point,
        destination: end_point,
        waypoints: wayPoints,
        optimizeWaypoints: true,
        travelMode: google.maps.TravelMode.DRIVING
    };
    directionsService.route(request, function (response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            //console.log(JSON.stringify(response));

            directionsDisplay.setMap(null);

            var route = new google.maps.Polyline({
                path: [],
                geodesic: true,
                strokeColor: '#708090',
                strokeOpacity: 0.7,
                strokeWeight: 5
            });
            var bounds = new google.maps.LatLngBounds();

            var legs = response.routes[0].legs;
            for (var i = 0; i < legs.length; i++) {
                var steps = legs[i].steps;
                for (var j = 0; j < steps.length; j++) {
                    var nextSegment = steps[j].path;
                    for (var k = 0; k < nextSegment.length; k++) {
                        route.getPath().push(nextSegment[k]);
                        bounds.extend(nextSegment[k]);
                    }
                }
            }

            route.customInfo = {
                id: routeId,
                name: $("#route-create-name").val(),
                color: '#708090',
                length: getLength(route),
                layer: 'route',
            }

            map.fitBounds(bounds);
            route.setMap(map);

            routes.push(route);
            addEvent(route);


            markers = markers.filter(function (obj) {
                return obj.customInfo.route != route_markers[0].customInfo.route;
            });
            for (var i = 0; i < route_markers.length; i++) {
                route_markers[i].customInfo.route = route.customInfo.name;
                route_markers[i].setOptions({draggable: false});
                markers.push(route_markers[i]);
            }
            create_markers = [];
            route_create_save(route, true);
        } else {
            $(".loader").css("display", "none");
            $("#alert-content").html(status);
            $("#alert-Modal").modal('show');
        }
    });
}


function searchPlace(address, index) {


    var routeName = null;

    if (editRouteObj != null) {
        routeName = editRouteObj.customInfo.name;
    }


    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({'address': address}, function (results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            var icon_size = 20;
            if (index == 0) {
                var icon = {
                    url: "img/marker-Start.png",
                    scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                    origin: new google.maps.Point(0, 0), // origin
                    anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                    labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
                };
                create_markers[index].setOptions({
                    map: map,
                    position: results[0].geometry.location,
                    icon: icon,
                    draggable: true,
                    customInfo: {
                        route: routeName,
                        name: '',
                        type: 'Start',
                        address: results[0].formatted_address,
                        layer: 'marker',
                    }
                });
            } else if (index == create_markers.length - 1) {
                var icon = {
                    url: "img/marker-End.png",
                    scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                    origin: new google.maps.Point(0, 0), // origin
                    anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                    labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
                };
                create_markers[index].setOptions({
                    map: map,
                    position: results[0].geometry.location,
                    icon: icon,
                    draggable: true,
                    customInfo: {
                        route: routeName,
                        type: 'End',
                        address: results[0].formatted_address,
                        layer: 'marker',
                    }
                });
            } else {
                var icon = {
                    url: "img/marker-Stop.png",
                    scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                    origin: new google.maps.Point(0, 0), // origin
                    anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                    labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
                };
                create_markers[index].setOptions({
                    map: map,
                    position: results[0].geometry.location,
                    icon: icon,
                    draggable: true,
                    customInfo: {
                        route: routeName,
                        type: 'Stop',
                        address: results[0].formatted_address,
                        layer: 'marker',
                    }
                });
            }
            if (create_markers.length > 2) {
                for (var i = 1; i < create_markers.length - 1; i++) {
                    if (create_markers[i].customInfo.type == 'End') {
                        var icon = {
                            url: "img/marker-Stop.png",
                            scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                            origin: new google.maps.Point(0, 0), // origin
                            anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                            labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
                        };
                        create_markers[i].setOptions({
                            icon: icon,
                            draggable: true
                        });
                        create_markers[i].customInfo.type = "Stop";
                    }
                }
            }
            setSelection(create_markers[index]);
            editInfowindow(results[0].geometry.location, create_markers[index]);
            addEvent(create_markers[index]);
            if (create_markers.length == 1) {
                map.setCenter(results[0].geometry.location);
                map.setZoom(16);
            }

            debugger;
            displayRoute();
        }
    });
}

function searchAddress(marker) {
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({'location': marker.getPosition()}, function (results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            if (results[0]) {
                marker.customInfo.address = results[0].formatted_address;
                var index = create_markers.indexOf(marker);
                var inputs = $(".route-point-address");
                inputs[index].value = results[0].formatted_address;
                editInfowindow(marker.getPosition(), marker);
                displayRoute();
            }
        }
    });
}

function route_create_save(route, isEdit) {
    selectFlag = 0;
    var action = '';
    if (!isEdit) {
        if (route.customInfo.id == null) {
            action = "add";
        } else {
            action = "update";
        }
    }
    else {
        action = "updateForAll";
    }

    var path = route.getPath();
    //var encodeString = google.maps.geometry.encoding.encodePath(path);

    var coordinatesStr = "";
    for (var j = 0; j < path.length - 1; j++) {
        coordinatesStr += path.getAt(j).lat() + ',' + path.getAt(j).lng() + '\n';
    }
    coordinatesStr += path.getAt(path.length - 1).lat() + ',' + path.getAt(path.length - 1).lng();
    var route_data = {
        id: route.customInfo.id,
        name: route.customInfo.name,
        color: route.customInfo.color,
        length: route.customInfo.length,
        coordinates: coordinatesStr,
    };
    var route_save_markers = markers.filter(function (obj) {
        return obj.customInfo.route == route.customInfo.name;
    });
    var marker_data = [];
    for (var i = 0; i < route_save_markers.length; i++) {
        marker_data.push({
            type: route_save_markers[i].customInfo.type,
            address: route_save_markers[i].customInfo.address,
            name: route_save_markers[i].customInfo.name,
            lat: route_save_markers[i].getPosition().lat(),
            lng: route_save_markers[i].getPosition().lng()
        });
    }
    var data = {
        route_data: route_data,
        marker_data: marker_data
    };

    $.ajax({
        type: "POST",
        url: "/api/routes/save",
        data: {table: 'route', action: action, data: JSON.stringify(data)},
        cache: false,
        timeout: 20000,
        success: function (response) {
            var json = JSON.parse(response);
            if (json.result == 'success') {
                if (infowindow) {
                    infowindow.close();
                }
                $(".loader").css("display", "none");
                $(".route-create").hide();
                route_details(route.customInfo.name);
                if (route.customInfo.id == null) {
                    route.customInfo.id = json.id;
                    var htmlStr = '<div class="route-box"><a href="#"><img src="img/route.png" alt="Routes"><div class="desc">' + route_data.name + '</div></a></div>';
                    $(".routes-box").append(htmlStr);
                } else {
                    $('.route-box a').filter(function () {
                        return $(this).text() === route.customInfo.name;
                    }).find("div.desc").html(route_data.name);
                }
            }
            selectFlag = 1;
            $(".loader").css("display", "none");
        },
        error: function (xmlhttprequest, textstatus, message) {
            if (textstatus === "timeout") {
                $("#alert-content").html("Server Connection Failed");
                $("#alert-Modal").modal('show');
            } else {
                $("#alert-content").html(textstatus);
                $("#alert-Modal").modal('show');
            }
            selectFlag = 1;
            $(".loader").css("display", "none");
        }
    });
}

function route_details(route_name) {
    $(".route-details").show();
    var start_marker = markers.filter(function (obj) {
        return obj.customInfo.type == 'Start' && obj.customInfo.route == route_name;
    });
    var waypoint_array = markers.filter(function (obj) {
        return obj.customInfo.type != 'Start' && obj.customInfo.type != 'End' && obj.customInfo.route == route_name;
    });
    var end_marker = markers.filter(function (obj) {
        return obj.customInfo.type == 'End' && obj.customInfo.route == route_name;
    });
    $(".route-details").html("");
    htmlStr = '<table><caption><h4><a href="#">' + route_name + '</h4></a></caption' +
        '<tr><td><img src="img/marker-Start.png"></td><td><h4>' + start_marker[0].customInfo.name + '</h4>' + start_marker[0].customInfo.address + '</td></tr>';
    for (var i = 0; i < waypoint_array.length; i++) {
        if (waypoint_array[i].customInfo.type == 'Stop') {
            htmlStr += '<tr><td><img src="img/marker-Stop.png"></td><td><h4>' + waypoint_array[i].customInfo.name + '</h4>' + waypoint_array[i].customInfo.address + '</td></tr>';
        } else if (waypoint_array[i].customInfo.type == 'Waypoint') {
            htmlStr += '<tr><td><img src="img/marker-Waypoint.png"></td><td><h4>' + waypoint_array[i].customInfo.name + '</h4>' + waypoint_array[i].customInfo.address + '</td></tr>';
        }
    }
    htmlStr += '<tr><td><img src="img/marker-End.png"></td><td><h4>' + end_marker[0].customInfo.name + '</h4>' + end_marker[0].customInfo.address + '</h4></td></tr></table>';
    $(".route-details").html(htmlStr);
}

google.maps.event.addDomListener(window, 'load', initMap);


var ClickEventHandler = function (map, origin) {
    this.origin = origin;
    this.map = map;
    this.directionsService = new google.maps.DirectionsService;
    this.directionsDisplay = new google.maps.DirectionsRenderer;
    this.directionsDisplay.setMap(map);
    this.placesService = new google.maps.places.PlacesService(map);
    // this.infowindow = new google.maps.InfoWindow;
    // this.infowindowContent = document.getElementById('infowindow-content');
    // this.infowindow.setContent(this.infowindowContent);

    // Listen for clicks on the map.
    this.map.addListener('click', this.handleClick.bind(this));


    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            map.setCenter(pos);
        }, function () {
            handleLocationError(true, infoWindow, map.getCenter());
        });
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map.getCenter());
    }

    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        if (browserHasGeolocation) {
            console.log("browser supported but user did not allow locaiton");
        }
        else {
            console.log("Browser not supported geolocation");

        }
    }


};

ClickEventHandler.prototype.handleClick = function (event) {

    console.log('You clicked on: ' + event.latLng);
    // If the event has a placeId, use it.
    if (event.placeId) {
        console.log('You clicked on place:' + event.placeId);

        // Calling e.stop() on the event prevents the default info window from
        // showing.
        // If you call stop here when there is no placeId you will prevent some
        // other map click event handlers from receiving the event.
        event.stop();

        var service = new google.maps.places.PlacesService(map);

        service.getDetails({
            placeId: event.placeId
        }, function (loadedPlace, status) {
            if (status === google.maps.places.PlacesServiceStatus.OK) {
                place = loadedPlace;

                tempMarker = new google.maps.Marker({
                    map: map,
                    position: place.geometry.location
                });
                tempInfowindow = new google.maps.InfoWindow();
                tempInfowindow.setContent('<div><strong>' + place.name + '</strong><br>' +
                    '<p>' + place.formatted_address + '</p>' +
                    '<p><a onclick="addToRoute()"><strong>Add this to Route</strong> </a></p>' +
                    '</div>');

                tempInfowindow.open(map, tempMarker);


            }
        });


        // this.calculateAndDisplayRoute(event.placeId);
        // this.getPlaceInformation(event.placeId);
    }
};

var place, tempMarker, tempInfowindow;

function addToRoute() {
    $(".route-new a").click();
    tempMarker.setMap(null);
    tempInfowindow.setMap(null);
    if (create_markers.length == 0) {
        var icon = {
            url: "img/marker-Start.png", // url
            scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
            origin: new google.maps.Point(0, 0), // origin
            anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
            labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
        };
    } else {
        var icon = {
            url: "img/marker-End.png", // url
            scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
            origin: new google.maps.Point(0, 0), // origin
            anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
            labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
        };
    }

    var newShape = new google.maps.Marker({
        map: map,
        icon: icon,
        position: place.geometry.location
    });
    newShape.type = 'marker';
    if (create_markers.length == 0) {
        newShape.customInfo = {
            route: null,
            name: '',
            type: 'Start',
            address: null,
            layer: 'marker',
        }
    } else {
        newShape.customInfo = {
            route: null,
            name: '',
            type: 'End',
            address: null,
            layer: 'marker',
        }
    }
    create_markers.push(newShape);
    if (create_markers.length > 2) {
        for (var i = 1; i < create_markers.length - 1; i++) {
            if (create_markers[i].customInfo.type == 'End') {
                var icon_size = 20;
                var icon = {
                    url: "img/marker-Stop.png", // url
                    scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                    origin: new google.maps.Point(0, 0), // origin
                    anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                    labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
                };
                create_markers[i].setOptions({icon: icon});
                create_markers[i].customInfo.type = "Stop";
            }
        }
    }
    setSelection(newShape);
    addEvent(newShape);
    var Inputs = document.getElementsByClassName("route-point-address");
    var new_number = Inputs.length + 1;
    var htmlStr = [
        '<div class="input-group">',
        '<span class="input-group-addon route-point-number">' + new_number + '</span>',
        '<input type="text" class="form-control route-point-address">',
        '<span class="input-group-addon">',
        '<span class="route-point-search glyphicon glyphicon-map-marker"></span>',
        '</span>',
        '<span class="input-group-addon">',
        '<span class="route-point-remove glyphicon glyphicon-remove"></span>',
        '</span>',
        '</div>'
    ].join('');
    $(".route-create .route-point-list").append(htmlStr);
    var acInputs = document.getElementsByClassName("route-point-address");
    var autocomplete = new google.maps.places.Autocomplete(acInputs[acInputs.length - 1]);
    autocompleteEvent(autocomplete);
    searchAddress(newShape);

}