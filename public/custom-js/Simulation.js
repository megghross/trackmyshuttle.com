var map;
var directionsDisplay;
var marker = [];
var Markers = [];
var wayPointsMarker = [];
var infoWindows = [];
var speed = "";
var polyline = [];
var mapHelper = [];
var shuttleData = [];
var timeoutVar = [];
var stepIndex = 0;
var icon_size = 24;
var legIndex = 0;
var EtaMarker;
var EtaRouteId;
var route;
var randomid;
var startPos;
var speed = 100; // km/h
var delay = 100;
var infowindow;
var shuttleOrignalLine = null;


let URL = $("body").data("url");
let BaseURL = $("body").data("baseurl") + '/';

$(document).ready(function () {



    // Echo.private('location.5454')
    //     .listen('LocationUpdate', (e) => {
    //         debugger;
    //     });


    initMap();
    showLocationUpdateStatus('Location Updating');
    $(document).on('click', '.route-box', function (event) {
        for (var i = 1; i < polyline.length; i++) {
            polyline[i].setMap(null);
            for (var j = 0; j < marker[i].length; j++) {
                marker[i][j].setMap(null);
            }
            for (var j = 0; j < Markers[i].length; j++) {
                Markers[i][j].setMap(null);
            }
        }
        if (infowindow) {
            infowindow.close();
        }

        // var shuttleNumber = this.dataset.shuttlenumber;
        var id = this.dataset.routeid;
        if(shuttleOrignalLine!=null){
            shuttleOrignalLine.setMap(null);
        }
        else{
            alert('Choose a Shuttle first');
        }
        console.log(polyline[id]);
        shuttleOrignalLine = mapHelper[id].PolyLine;
        shuttleOrignalLine.setMap(map);
        shuttleOrignalLine.setOptions({strokeColor: '#ec00ff'});
        shuttleOrignalLine.setOptions({strokeOpacity: 0.2});
        var bounds = new google.maps.LatLngBounds();
        shuttleOrignalLine.getPath().forEach(function (element, index) {
            bounds.extend(element)
        });
        clearTimeout(timeoutVar[selectedShuttle.customInfo.routeId][selectedShuttle.customInfo.shuttleNumber]);
        animateMarker(selectedShuttle, mapHelper[id].PolyLineArray, speed, Markers[id], selectedShuttle.customInfo.name, id);


        map.fitBounds(bounds);
        selectedShuttle.setMap(map);
        // selectedShuttleNumber = shuttleNumber;
        map.setCenter(selectedShuttle.position);
        map.setZoom(15);
        for (var j = 0; j < Markers[id].length; j++) {
            Markers[id][j].setMap(map);
        }
        route_details(id, 0);
    });
    $(document).on('click', '.shuttle-box', function (event) {
        for (var i = 1; i < polyline.length; i++) {
            polyline[i].setMap(null);
            for (var j = 0; j < marker[i].length; j++) {
                marker[i][j].setMap(null);
            }
            for (var j = 0; j < Markers[i].length; j++) {
                Markers[i][j].setMap(null);
            }
        }
        if (infowindow) {
            infowindow.close();
        }
        var shuttleNumber = this.dataset.shuttlenumber;
        var id = this.dataset.routeid;

        if(shuttleOrignalLine!=null){
            shuttleOrignalLine.setMap(null);
        }
        console.log(polyline[id]);
        // shuttleOrignalLine = mapHelper[id].PolyLine;

        //Hide the polyline

        // polyline[id].setMap(map);

        // var bounds = new google.maps.LatLngBounds();
        // polyline[id].getPath().forEach(function (element, index) {
        //     bounds.extend(element)
        // });
        // map.fitBounds(bounds);

        marker[id][shuttleNumber].setMap(map);
        selectedShuttleNumber = shuttleNumber;
        selectedShuttle = marker[id][shuttleNumber];
        map.setCenter(marker[id].position);
        map.setZoom(15);
        // for (var j = 0; j < Markers[id].length; j++) {
        //     Markers[id][j].setMap(map);
        // }
        route_details(id, 0);
    });
    $(".route-new a").click(function () {
        for (var i = 1; i < polyline.length; i++) {
            polyline[i].setMap(map);

            for (var j = 0; j < Markers[i].length; j++) {
                Markers[i][j].setMap(map);
            }
            for (var j = 0; j < marker[i].length; j++) {
                marker[i][j].setMap(map);
            }
        }


        $(".route-details").hide();
        map.setZoom(6);


    });

    $(document).on('click','.route-details td',function(){
        let routeId = $(this).data('routeid');
        let markerId = $(this).data('markerid');

        google.maps.event.trigger(Markers[routeId][markerId], 'click');
    });

});

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        // center: {lat: 38.906013, lng: -77.037691},

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

    });
    // var centerControlDiv = document.createElement('div');
    // var centerControl = new CenterControl(centerControlDiv, map);
    // centerControlDiv.index = 1;
    // map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(centerControlDiv);
    loadMaps();

}


function route_details(id, shuttleNumber) {
    $(".route-details").show();
    var start_marker = Markers[id].filter(function (obj) {
        return obj.customInfo.type == 'Start' && obj.customInfo.routeId == id;
    });
    var waypoint_array = Markers[id].filter(function (obj) {
        return obj.customInfo.type != 'Start' && obj.customInfo.type != 'End' && obj.customInfo.routeId == id;
    });
    var end_marker = Markers[id].filter(function (obj) {
        return obj.customInfo.type == 'End' && obj.customInfo.routeId == id;
    });
    $(".route-details").html("");
    htmlStr = ' <button type="button" onclick="hideKeyBox()" class="hideKeyBox">X</button><table><caption><h4><a href="#">' + shuttleData[id].devices[shuttleNumber].shuttleName + ' (' + shuttleData[id].name + ')</h4></a></caption' +
        '<tr><td><img src="'+BaseURL+'img/marker-Start.png"></td><td data-routeid="'+start_marker[0].customInfo.routeId+'" data-markerid="'+start_marker[0].customInfo.markerId+'" ><h4>' + start_marker[0].customInfo.stopName + '</h4>' + start_marker[0].customInfo.address + '</td></tr>';
    for (var i = 0; i < waypoint_array.length; i++) {

        if (waypoint_array[i].customInfo.type == 'Stop') {
            htmlStr += '<tr><td><img src="'+BaseURL+'img/marker-Stop.png"></td><td data-routeid="'+waypoint_array[i].customInfo.routeId+'" data-markerid="'+waypoint_array[i].customInfo.markerId+'"><h4>' + waypoint_array[i].customInfo.stopName + '</h4>' + waypoint_array[i].customInfo.address + '</td></tr>';
        } else if (waypoint_array[i].customInfo.type == 'Waypoint') {
            htmlStr += '<tr><td><img src="'+BaseURL+'img/marker-Waypoint.png"></td><td data-routeid="'+waypoint_array[i].customInfo.routeId+'" data-markerid="'+waypoint_array[i].customInfo.markerId+'"><h4>' + waypoint_array[i].customInfo.stopName + '</h4>' + waypoint_array[i].customInfo.address + '</td></tr>';
        }
    }
    htmlStr += '<tr><td><img src="'+BaseURL+'img/marker-End.png"></td><td data-routeid="'+end_marker[0].customInfo.routeId+'" data-markerid="'+end_marker[0].customInfo.markerId+'"><h4>' + end_marker[0].customInfo.stopName + '</h4>' + end_marker[0].customInfo.address + '</h4></td></tr></table>';
    $(".route-details").html(htmlStr);
}

function route_details2(id) {
    $(".route-details").show();
    var start_marker = Markers[id].filter(function (obj) {
        return obj.customInfo.type == 'Start' && obj.customInfo.routeId == id;
    });
    var stop_array = Markers[id].filter(function (obj) {
        return obj.customInfo.type != 'Start' && obj.customInfo.routeId == id;
    });
    $(".route-details").html("");
    htmlStr = '<table><caption>Shuttle ' + id + '</caption' +
        '<tr><td><img src="'+BaseURL+'img/marker-start.png"></td><td><a href="#">' + start_marker[0].customInfo.address + '</a></td></tr>';
    for (var i = 0; i < stop_array.length; i++) {
        htmlStr += '<tr><td><img src="'+BaseURL+'img/marker-' + stop_array[i].customInfo.type + '.png"></td><td><a href="#">' + stop_array[i].customInfo.address + '</a></td></tr>';
    }
    // htmlStr+='<tr><td><img src="img/marker-end.png"></td><td><a href="#">'+start_marker[0].customInfo.address+'</a></td></tr></table>';
    $(".route-details").html(htmlStr);
}

function loadMaps() {
    $.ajax({
        url: URL,
        method: "GET",
        success: function (response) {
            console.log('Route  loading start now.');

            for (i = 0; i < response.length; i++) {
                console.log('Route  '+(i+1)+" loaded.");
                route = response[i];

                for (j = 0; j < route.devices.length; j++) {
                    var htmlStr = '<div class="shuttle-box" data-routeid="' + (i + 1) + '" data-shuttleNumber="' + j + '" id="' + route.id + '"><a href="#"><img src="'+BaseURL+'img/bus.png" alt="Routes"><div class="desc">' +
                        route.devices[j].shuttleName +'<br>'+route.name  + '</div></a></div>';
                    $(".shuttles-box").append(htmlStr);
                }


                var htmlStr = '<div class="route-box" data-routeid="'+(i+1)+'"><a href="#"><img src="'+BaseURL+'img/route.png" alt="Routes"><div class="desc">' + route.name + '</div></a></div>';
                $(".routes-box").append(htmlStr);

                shuttleData[i + 1] = route;
                showOnMap(route, (i + 1));
            }
            console.log('Route loading completed.');

            $(".loader").css("display", "none");
        }
    });
}


function showOnMap(route, index) {
    var directionsService = new google.maps.DirectionsService;
    directionsDisplay = new google.maps.DirectionsRenderer({
        suppressMarkers: true,
    });
    var startPoint, endPoint;
    markers = [];
    if (route.wayPoints.length > 0) {
        for (l = 0; l < route.wayPoints.length; l++) {
            if (route.wayPoints[l].type == 'Start') {
                startPoint = new google.maps.LatLng(route.wayPoints[l].lat, route.wayPoints[l].lng);
            }
            else if (route.wayPoints[l].type == 'End') {
                endPoint = new google.maps.LatLng(route.wayPoints[l].lat, route.wayPoints[l].lng);
            }
            else {
                markers.push({location: new google.maps.LatLng(route.wayPoints[l].lat, route.wayPoints[l].lng)});

            }


            // if(route.wayPoints[l].isLatLng==1){
            // }
            // else{
            //     markers.push({location: route.wayPoints[l].waypoint});
            // }
        }
    }

    displayRoute(startPoint, endPoint, directionsService,
        directionsDisplay, markers, route.shuttleName, route, index);

}

function doSomeTest(route){
    let legs = route.legs;
    let legIndex = 0;
    polyArray = [];
    legs.forEach(function(leg){
        let steps = leg.steps;
        let stepIndex = 0;
        steps.forEach(function(step){
            let temp = step.path;
            temp.forEach(function(item){item.stepIndex = stepIndex; item.legIndex = legIndex});
            polyArray = polyArray.concat(temp);

            stepIndex++;

        });
        legIndex++;
    });
    return polyArray;
}

function displayRoute(origin, destination, service, display, waypoints, markerTitle, shuttleData, index) {
    var id = index;
    randomid = id;
    // wait(1000);

    service.route({
        origin: origin,
        destination: destination,
        waypoints: waypoints,
        travelMode: 'DRIVING',

        avoidTolls: true
    }, function (response, status) {
        if (status === 'OK') {
            // display.setDirections(response);
            // var path=shuttleData.coordinates.split("\n");
            // var coordinates=[];
            // for(var j=0;j<path.length;j++){
            //     var point=path[j].split(",");
            //     coordinates.push(new google.maps.LatLng(parseFloat(point[0]),parseFloat(point[1])));
            // }

            mapHelper[id] = new MapHelper(response.routes[0]);
            polyline[id] = getPolyLines(response.routes[0].overview_polyline, shuttleData.color);

            // polyline[id] = getPolyLineFromLatLngs(coordinates, shuttleData.color);


            // polyline[id].setMap(map);

            var bounds = new google.maps.LatLngBounds();
            polyline[id].getPath().forEach(function (element, index) {
                bounds.extend(element)
            });
            map.fitBounds(bounds);

            let googleRoute = response.routes[0];


            arrayOfLatLng = null;
            // arrayOfLatLng = polyline[id].getPath().b;
            arrayOfLatLng = mapHelper[id].PolyLineArray;
            mPosition = response.routes[0].legs[legIndex].steps[stepIndex].start_location;


            // firstMarker = new google.maps.Marker({
            //     map: map,
            //     position: mPosition,
            //     icon: ""
            // });

            startPos = mPosition;
            map.setCenter(mPosition);
            route = response.routes[0];
            var indexK = 0;
            var localInfoWindows = [];
            var localStopMarker = [];
            if (shuttleData.wayPoints.length > 0) {

                for (k = 0; k < shuttleData.wayPoints.length; k++) {
                    wayPoint = shuttleData.wayPoints[k];
                    var name = wayPoint.name;
                    var waypointPosition, waypointaddress;
                    if (wayPoint.type == 'End') {
                        waypointPosition = route.legs[k - 1].end_location;
                        waypointaddress = route.legs[k - 1].end_address;
                    }
                    else {
                        if (route.legs.length <= k) {

                        }
                        else {
                            waypointPosition = route.legs[k].start_location;
                            waypointaddress = route.legs[k].start_address;
                        }
                    }

                    var icon = {
                        url: BaseURL+"img/marker-" + wayPoint.type + ".png",
                        scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                        origin: new google.maps.Point(0, 0), // origin
                        anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                        labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
                    };
                    localStopMarker[indexK] = {};

                    if (wayPoint.type == 'Stop' || wayPoint.type == 'End') {
                        localMarker = new google.maps.Marker({
                            // map: map,
                            position: waypointPosition,
                            icon: icon,
                            customInfo: {
                                routeId: id,
                                type: wayPoint.type,
                                labelId: 'shuttle' + id + "" + indexK,
                                name: id + '-' + indexK,
                                position: waypointPosition,
                                address: waypointaddress,
                                markerId: indexK,
                                shuttleNumber: id,
                                stopName: name,
                                layer: 'marker'
                            }
                        });

                        localStopMarker[indexK] = localMarker;
                        localInfoWindows[indexK] = {};
                        localInfoWindows[indexK] = new google.maps.InfoWindow({
                            content: "<div id='shuttle" + id + "" + indexK + "'>Loading</div>"
                        });

                    }
                    else {


                        if (wayPoint.type == 'Waypoint') {
                            localStopMarker[indexK] = new google.maps.Marker({
                                // position: waypointPosition,
                                // icon: icon,
                                customInfo: {
                                    position: waypointPosition,
                                    routeId: id,
                                    type: wayPoint.type,
                                    shuttleNumber: id,
                                    stopName: name,
                                    name: id + '-' + indexK,
                                    markerId: indexK,

                                    address: waypointaddress,
                                    layer: 'marker'
                                }
                            });
                        }
                        else {
                            localStopMarker[indexK] = new google.maps.Marker({
                                // map: map,
                                position: waypointPosition,
                                icon: icon,
                                customInfo: {
                                    routeId: id,
                                    type: wayPoint.type,
                                    shuttleNumber: id,
                                    name: id + '-' + indexK,
                                    markerId: indexK,
                                    address: waypointaddress,
                                    stopName: name,
                                    layer: 'marker'
                                }
                            });
                        }

                    }


                    indexK++;

                }
                var icon = {
                    url: BaseURL+"img/marker-end.png",
                    scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                    origin: new google.maps.Point(0, 0), // origin
                    anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                    labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
                };
                localStopMarker[localStopMarker.length - 1].setIcon(icon);
                infoWindows[id] = localInfoWindows;
                Markers[id] = localStopMarker;

            }
            if (infoWindows[id].length > 0) {
                for (k = 0; k < infoWindows[id].length; k++) {


                    try {
                        RegisterClickEvent(Markers[id][k], false, id);

                    }
                    catch (e) {

                    }


                }
            }
            var car = "M97.94,111.31a.69.69,0,0,0-.52-.21,1.19,1.19,0,0,0-.44.09l-11.12,4.22a2.62,2.62,0,0,0-.72.44l.89-8.31a5.64,5.64,0,0,0,4.92-4.17h.57V15.19C91.52,5.34,88.47,0,72.36,0h0L20.55.24C15.77.24,6.61,3.18,6.61,7.8v95.57h.57a5.69,5.69,0,0,0,5.28,4.2l.91,8.6a2.41,2.41,0,0,0-1-.75L1.21,111.19a1.11,1.11,0,0,0-.43-.09.69.69,0,0,0-.52.21c-.37.4-.23,1.26-.2,1.43v.08A7.33,7.33,0,0,0,3.32,116l7.53,3.06a2.5,2.5,0,0,0,1,.19,2,2,0,0,0,1.77-.94l4.83,45.54a7.32,7.32,0,0,0,2.36,4.79c3.85,4.07,11.44,6.76,14.8,6.76l25.34-.25h3.32c2.63-.11,6.33-1.59,9.49-3.76,2.19-1.48,6-4.54,6.38-8.37l4.74-44.36a2.09,2.09,0,0,0,1.53.62,2.5,2.5,0,0,0,1-.19L94.88,116a7.33,7.33,0,0,0,3.23-3.18v-.08A1.88,1.88,0,0,0,97.94,111.31ZM86.68,18.73a1.15,1.15,0,0,1,2.29,0V58.27a1.15,1.15,0,0,1-2.29,0Zm0,48.92a1.15,1.15,0,0,1,2.29,0v31a6.16,6.16,0,0,0-2.29-1ZM11.47,97.58a6.43,6.43,0,0,0-2.28,1v-31a1.09,1.09,0,0,1,1.14-1,1.09,1.09,0,0,1,1.14,1Zm0-39.31a1.09,1.09,0,0,1-1.14,1,1.09,1.09,0,0,1-1.14-1V18.73a1.08,1.08,0,0,1,1.14-1,1.08,1.08,0,0,1,1.14,1ZM68.35,104.1c3,0,5.37,1.57,5.37,3.51s-2.4,3.52-5.37,3.52H30.12c-3,0-5.37-1.57-5.37-3.52s2.41-3.51,5.37-3.51ZM15.17,3.56c-.06,0,0-.35,0-.53a6.22,6.22,0,0,0,0-.75,22.81,22.81,0,0,1,2.49-.69l.45-.07a3,3,0,0,1,.47,0l10.21.08a1,1,0,0,1,.56.11c.11.1.11.38.11.69v.38c0,.33,0,.64-.13.76a1,1,0,0,1-.54.11H15.5C15.22,3.6,15.17,3.56,15.17,3.56ZM21.37,168a6.26,6.26,0,0,1-2-4.1h0l-.21-2.05,2.65.16a19,19,0,0,0,2.45,4.26c1.55,2.17,3.26,5,6.18,7.1A24.3,24.3,0,0,1,21.37,168Zm57.84-5c-.46,4.37-6.35,8.49-11.09,10.27,2.89-2.06,4.65-4.85,6.2-7A19,19,0,0,0,76.77,162l2.56-.15Zm-1.85-6.8a21.78,21.78,0,0,1-6.9,6.1c-5.85,3.43-13.8,5.32-21.76,5.29s-15.87-2-21.66-5.43c-4.58-2.71-7.7-6.39-8.74-10.29H19a13.53,13.53,0,0,0,2,3.95,22,22,0,0,0,6.76,5.89c5.65,3.33,13.32,5.16,21,5.15s15.38-1.73,21.08-5a22.09,22.09,0,0,0,6.8-5.86,13.64,13.64,0,0,0,2.09-4.13h.67a13.28,13.28,0,0,1-2,4.32Zm2.46-41.88L78.48,131.9l-.36.1a3.12,3.12,0,0,1-2,1.83,100.33,100.33,0,0,1-54.41-.24,3,3,0,0,1-2.12-2.45l-1.68-18.35a1.3,1.3,0,0,1,.19-.85,1.09,1.09,0,0,1,.13-.17,1.22,1.22,0,0,1,1.58-.31c10.54,6.65,19.5,9.43,29.39,9.43,9.57,0,19-2.78,28.79-9.2a1.12,1.12,0,0,1,1.57.23l0,.06a1.56,1.56,0,0,1,.3,1.06ZM82.48,3.56a.73.73,0,0,1-.33,0H68.9a1,1,0,0,1-.54-.11c-.14-.12-.13-.43-.13-.76V2.35c0-.31,0-.59.11-.69a1,1,0,0,1,.56-.11L79,1.47c.76.11,1.47.25,2.14.4.5.14,1,.29,1.34.41a6.45,6.45,0,0,0,0,.75C82.52,3.21,82.54,3.51,82.48,3.56Z";
            var icon = {
                path: car,

                strokeColor: 'white',
                strokeWeight: .2,
                fillOpacity: 1.0,
                scale: 0.2,
                // origin: new google.maps.Point(0, 0), // origin
                anchor: new google.maps.Point(35, 40), // anchor
                labelOrigin: new google.maps.Point(90, -90),
                fillColor: '#1e1640',
                offset: '5%'
            };

            // icon.rotation = (google.maps.geometry.spherical.computeHeading(new google.maps.LatLng(lat, lng), new google.maps.LatLng(lat + deltaLat, lng + deltaLng))) + 180;
            //
            marker[id] = [];
            timeoutVar[id] = [];
            for (k = 0; k < shuttleData.devices.length; k++) {
                marker[id][k] = new google.maps.Marker({
                    icon: icon,
                    // map: map,
                    label: shuttleData.devices[k].shuttleName,
                    customInfo: {
                        shuttleNumber: k,
                        serialNumber: shuttleData.devices[k].serialNumber,
                        deviceToken: shuttleData.devices[k].deviceToken,
                        routeId: id,
                        name: shuttleData.devices[k].shuttleName,
                        type: "Shuttle"
                    }
                });
                marker[id][k].setPosition(mPosition);
                registerEventForEvent(marker[id][k]);
                // animateMarker(marker[id][k], arrayOfLatLng, speed, Markers[id], shuttleData.devices[k].shuttleName, id);

            }



            updateCurrentLocaiton();


        } else {

            wait(5000);
            displayRoute(origin, destination, service, display, waypoints, markerTitle, shuttleData, index);
            updateCurrentLocaiton();
        }
    });
}

function animateMarker(marker, coords1d, km_h, Markers, markerTitle, id) {

    var coords1 = null;
    coords1 = coords1d;
    var target = getRandomNumber(0, (coords1.length - 1));

    // var target = 0;
    marker.setPosition(coords1[target]);
    var km_h = km_h || 50;
    // coords1.push(startPos);
    // var car1 = car || "Car 1";

    // var timeInMilliSeconds = ((distnaceMeter/1000)/km_h) * 60 *60 *1000;

    function goToPoint() {

        var lat = marker.position.lat();
        var lng = marker.position.lng();
        var step = (km_h * 1000 * delay) / 3600000; // in meters
        var dest;

        if (Array.isArray(coords1[target])) {
            dest = new google.maps.LatLng(
                coords1[target][0], coords1[target][1]);
        }
        else {
            dest = new google.maps.LatLng(
                coords1[target].lat(), coords1[target].lng());
        }

        var distance =
            google.maps.geometry.spherical.computeDistanceBetween(
                dest, marker.position); // in meters

        var numStep = distance / step;
        var i = 0;
        var deltaLat = (dest.lat() - lat) / numStep;
        var deltaLng = (dest.lng() - lng) / numStep;

        function moveMarker() {

            lat += deltaLat;
            lng += deltaLng;
            i += step;
            // etaUpdate(coords1, target, marker, new google.maps.LatLng(lat, lng), id, km_h);
            etaUpdatev2(coords1, target, marker, id, km_h);


            if (i < distance) {
                marker.setPosition(new google.maps.LatLng(lat, lng));
                timeoutVar[marker.customInfo.routeId][marker.customInfo.shuttleNumber] = setTimeout(moveMarker, delay);
            }
            else {
                marker.setPosition(dest);

                target++;
                if (target == (coords1.length - 1)) {
                    target = 0;

                    marker.setPosition(coords1[target]);

                }
                if (IsTargetIsStop(dest, marker.customInfo.routeId)) {
                    timeoutVar[marker.customInfo.routeId][marker.customInfo.shuttleNumber]  = setTimeout(goToPoint, 5000);

                }
                else {
                    timeoutVar[marker.customInfo.routeId][marker.customInfo.shuttleNumber] = setTimeout(goToPoint, delay);
                }
            }
            var car = "M97.94,111.31a.69.69,0,0,0-.52-.21,1.19,1.19,0,0,0-.44.09l-11.12,4.22a2.62,2.62,0,0,0-.72.44l.89-8.31a5.64,5.64,0,0,0,4.92-4.17h.57V15.19C91.52,5.34,88.47,0,72.36,0h0L20.55.24C15.77.24,6.61,3.18,6.61,7.8v95.57h.57a5.69,5.69,0,0,0,5.28,4.2l.91,8.6a2.41,2.41,0,0,0-1-.75L1.21,111.19a1.11,1.11,0,0,0-.43-.09.69.69,0,0,0-.52.21c-.37.4-.23,1.26-.2,1.43v.08A7.33,7.33,0,0,0,3.32,116l7.53,3.06a2.5,2.5,0,0,0,1,.19,2,2,0,0,0,1.77-.94l4.83,45.54a7.32,7.32,0,0,0,2.36,4.79c3.85,4.07,11.44,6.76,14.8,6.76l25.34-.25h3.32c2.63-.11,6.33-1.59,9.49-3.76,2.19-1.48,6-4.54,6.38-8.37l4.74-44.36a2.09,2.09,0,0,0,1.53.62,2.5,2.5,0,0,0,1-.19L94.88,116a7.33,7.33,0,0,0,3.23-3.18v-.08A1.88,1.88,0,0,0,97.94,111.31ZM86.68,18.73a1.15,1.15,0,0,1,2.29,0V58.27a1.15,1.15,0,0,1-2.29,0Zm0,48.92a1.15,1.15,0,0,1,2.29,0v31a6.16,6.16,0,0,0-2.29-1ZM11.47,97.58a6.43,6.43,0,0,0-2.28,1v-31a1.09,1.09,0,0,1,1.14-1,1.09,1.09,0,0,1,1.14,1Zm0-39.31a1.09,1.09,0,0,1-1.14,1,1.09,1.09,0,0,1-1.14-1V18.73a1.08,1.08,0,0,1,1.14-1,1.08,1.08,0,0,1,1.14,1ZM68.35,104.1c3,0,5.37,1.57,5.37,3.51s-2.4,3.52-5.37,3.52H30.12c-3,0-5.37-1.57-5.37-3.52s2.41-3.51,5.37-3.51ZM15.17,3.56c-.06,0,0-.35,0-.53a6.22,6.22,0,0,0,0-.75,22.81,22.81,0,0,1,2.49-.69l.45-.07a3,3,0,0,1,.47,0l10.21.08a1,1,0,0,1,.56.11c.11.1.11.38.11.69v.38c0,.33,0,.64-.13.76a1,1,0,0,1-.54.11H15.5C15.22,3.6,15.17,3.56,15.17,3.56ZM21.37,168a6.26,6.26,0,0,1-2-4.1h0l-.21-2.05,2.65.16a19,19,0,0,0,2.45,4.26c1.55,2.17,3.26,5,6.18,7.1A24.3,24.3,0,0,1,21.37,168Zm57.84-5c-.46,4.37-6.35,8.49-11.09,10.27,2.89-2.06,4.65-4.85,6.2-7A19,19,0,0,0,76.77,162l2.56-.15Zm-1.85-6.8a21.78,21.78,0,0,1-6.9,6.1c-5.85,3.43-13.8,5.32-21.76,5.29s-15.87-2-21.66-5.43c-4.58-2.71-7.7-6.39-8.74-10.29H19a13.53,13.53,0,0,0,2,3.95,22,22,0,0,0,6.76,5.89c5.65,3.33,13.32,5.16,21,5.15s15.38-1.73,21.08-5a22.09,22.09,0,0,0,6.8-5.86,13.64,13.64,0,0,0,2.09-4.13h.67a13.28,13.28,0,0,1-2,4.32Zm2.46-41.88L78.48,131.9l-.36.1a3.12,3.12,0,0,1-2,1.83,100.33,100.33,0,0,1-54.41-.24,3,3,0,0,1-2.12-2.45l-1.68-18.35a1.3,1.3,0,0,1,.19-.85,1.09,1.09,0,0,1,.13-.17,1.22,1.22,0,0,1,1.58-.31c10.54,6.65,19.5,9.43,29.39,9.43,9.57,0,19-2.78,28.79-9.2a1.12,1.12,0,0,1,1.57.23l0,.06a1.56,1.56,0,0,1,.3,1.06ZM82.48,3.56a.73.73,0,0,1-.33,0H68.9a1,1,0,0,1-.54-.11c-.14-.12-.13-.43-.13-.76V2.35c0-.31,0-.59.11-.69a1,1,0,0,1,.56-.11L79,1.47c.76.11,1.47.25,2.14.4.5.14,1,.29,1.34.41a6.45,6.45,0,0,0,0,.75C82.52,3.21,82.54,3.51,82.48,3.56Z";
            var icon = {
                path: car,

                strokeColor: 'white',
                strokeWeight: .2,
                fillOpacity: 1.0,
                scale: 0.2,
                // origin: new google.maps.Point(0, 0), // origin
                anchor: new google.maps.Point(35, 40), // anchor
                labelOrigin: new google.maps.Point(90, -90),
                fillColor: '#1e1640',
                offset: '5%'
            };
            // var icon =  {
            //                 url: RotateIcon
            //                     .makeIcon(
            //                         'img/shuttle.png')
            //                     .setRotation({deg: (google.maps.geometry.spherical.computeHeading(new google.maps.LatLng(lat, lng), new google.maps.LatLng(lat + deltaLat, lng + deltaLng)))+90})
            //                     .getUrl(),
            //         scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
            //         origin: new google.maps.Point(0,0), // origin
            //         anchor: new google.maps.Point(parseInt(icon_size/2),parseInt(icon_size/2)), // anchor
            //         labelOrigin:new google.maps.Point(parseInt(icon_size/2),-10)
            //             };

            // var icon = {
            //     url: "img/shuttle.svg",
            //     scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
            //     origin: new google.maps.Point(0,0), // origin
            //     anchor: new google.maps.Point(parseInt(icon_size/2),parseInt(icon_size/2)), // anchor
            //     labelOrigin:new google.maps.Point(parseInt(icon_size/2),-10)
            // };
            icon.rotation = (google.maps.geometry.spherical.computeHeading(new google.maps.LatLng(lat, lng), new google.maps.LatLng(lat + deltaLat, lng + deltaLng))) + 180;
            //


            marker.setIcon(icon);
        }

        moveMarker();
    }

    goToPoint();
}

function registerEventForEvent(eventMarker) {
    google.maps.event.addListener(eventMarker, 'click', (function () {
        selectedShuttleNumber = eventMarker.customInfo.shuttleNumber;
    }));


}

function updateCurrentLocaiton() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            map.setCenter(pos);
        }, function () {
            handleLocationError(true, map.getCenter());
        });
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, map.getCenter());
    }

    function handleLocationError(browserHasGeolocation, pos) {
        if (browserHasGeolocation) {
            console.log("browser supported but user did not allow locaiton");
        }
        else {
            console.log("Browser not supported geolocation");

        }
    }
}

function RegisterClickEvent(stopMarker2, show, id) {
    var opened = false;
    google.maps.event.addListener(stopMarker2, 'click', (function (stopMarker2, opened, id) {
        return function () {
            var thisMarker = stopMarker2;
            if (EtaMarker == undefined) {
                opened = true;
            }
            else if (EtaMarker.customInfo.name == thisMarker.customInfo.name) {
                opened = !opened;
            }
            else if (EtaMarker.customInfo.name != thisMarker.customInfo.name) {
                opened = true;
            }


            if (opened) {

                EtaMarker = thisMarker;
                EtaRouteId = id;
                showETA("Loading");
            }
            else {
                hideEta();
            }

        };
    })(stopMarker2, opened, id));
}

function getRandomNumber(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}


var selectedShuttleNumber = 0;

function etaUpdatev2(coords, targetIndex, marker, id, km_h) {

    try {

        var shuttleName = marker.customInfo.name;
        if (shuttleName == selectedShuttle.customInfo.name) {

            markerPosition = marker.position;
            var position = null;

            if (targetIndex >= (coords.length - 1)) {

                if (coords.length - 2 < 0) {
                    targetIndex = 0;
                }
                else {
                    targetIndex = coords.length - 2;
                }
            }
            for (indexIJ = 0; indexIJ < Markers[id].length; indexIJ++) {
                if (Markers[id][indexIJ].customInfo.name == EtaMarker.customInfo.name) {
                    let stopPoint = mapHelper[id].NearestPointOfRoute(Markers[id][indexIJ]);
                    let markerPoint = {};
                    markerPoint.legIndex = coords[targetIndex].legIndex;
                    markerPoint.stepIndex = coords[targetIndex].stepIndex;
                    markerPoint.pointIndex = coords[targetIndex].pointIndex;
                    let time = mapHelper[id].CalculateETA(markerPoint, stopPoint, km_h);
                    let message = mapHelper[id].UpdateLocation(marker);
                    document.getElementById("locationUpdateStatus").innerHTML = message;
                    if (time == '0 sec' || time == '1 sec' || time == '2 sec' || time == '3 sec') {
                        document.getElementById("etaMessage").innerHTML = 'Shuttle just Arrived!';
                    }
                    else if(time=='Trip Completed!'){
                        document.getElementById("etaMessage").innerHTML = time;
                    }
                    else {
                        document.getElementById("etaMessage").innerHTML = 'Shuttle  ' + (selectedShuttle.customInfo.shuttleNumber + 1) + ' will arrive in ' + time;
                    }
                    return true;
                }

            }
        }

    }

    catch (e) {
        // console.log(e);
    }
}

function showETA(EtaMessage) {
    $(".eta-details").show();
    $(".eta-details").html("");
    htmlStr = '<button type="button" onclick="hideEta()" class="hideKeyBox">X</button><table><caption>ETA to '+EtaMarker.customInfo.stopName+'</caption' +
        '<tr><td id="etaMessage">' + EtaMessage + '</td></tr>';

    $(".eta-details").html(htmlStr);
}

function hideEta() {
    $(".eta-details").html("");
    $(".eta-details").hide();
}

function showLocationUpdateStatus(statusMessage) {
    $(".location-update-status").show();
    $(".location-update-status").html("");
    htmlStr = '<button type="button" onclick="hideLocationUpdateStatus()" class="hideKeyBox">X</button><table><caption>Server Response</caption' +
        '<tr><td id="locationUpdateStatus">' + statusMessage + '</td></tr>';

    $(".location-update-status").html(htmlStr);
}

function hideLocationUpdateStatus() {
    $(".location-update-status").html("");
    $(".location-update-status").hide();
}

function hideKeyBox() {
    $(".route-details").html("");
    $(".route-details").hide();
}

function wait(ms) {
    var start = new Date().getTime();
    var end = start;
    while (end < start + ms) {
        end = new Date().getTime();
    }
}

function getTime(timeInMiliseconds) {
    var mint = parseInt((timeInMiliseconds / 1000) / 60);
    if (mint == 0) {
        var seconds = parseInt((timeInMiliseconds / 1000) % 60);
        return seconds + " sec";

    }
    return mint + " mins";

}

function IsTargetIsStop(mPosition, shuttleId) {

    localArray = Markers[shuttleId];
    if (Array.isArray(localArray)) {
        for (i = 0; i < localArray.length; i++) {
            if (localArray[i].customInfo.type !== "Stop" && localArray[i].customInfo.type !== "End") {
                continue;
            }
            if (CompareLatLng(localArray[i].position, mPosition, 10)) {

                return true;
            }
            //
            // if (Compare(route.legs[i].steps[0].start_location.lat(), mPosition.lat()) && Compare(route.legs[i].steps[0].start_location.lng(), mPosition.lng())) {
            //     //return true if location found to be successor of previous quesiotns.
            //     return true;
            // }
        }
    }
    //Only to stop on stops
    return false;
}

function SearchInArray(coords, position) {


    var poly = new google.maps.Polyline({
        path: coords,
    });
    if (google.maps.geometry.poly.isLocationOnEdge(position, poly, .00001)) {

        for (i = 0; i < coords.length; i++) {

            j = 0;
            if (i - 1 >= 0) {
                j = i - 1;
            }

            if (CompareLatLng(coords[i], position, 5)) {
                return i;
            }
        }
        return -1;
    }
    return -1;

}

function CompareLatLng(latlng1, latlng2, tolerance) {

    var distance = google.maps.geometry.spherical.computeDistanceBetween(latlng1, latlng2);

    if (distance < tolerance) {
        return true;
    }
    else {
        false;
    }
    //
    //
    // if (Compare(latlng1.lat(), latlng2.lat()) && Compare(latlng1.lng(), latlng2.lng())) {
    //     return true;
    // }
    // return false;
}

function CalculateDistance(coords, markerPosition, endPosition, nextPosition) {
    var endIndex = SearchInArray(coords, endPosition);
    var startIndex = SearchInArray(coords, markerPosition);
    var nextIndex = SearchInArray(coords, nextPosition);
    if (endIndex == -1 || startIndex == -1) {
        return 0;
    }

    if (endIndex < startIndex) {
        return -1;
    }
    distance = 0;
    for (i = startIndex; i <= endIndex; i++) {
        j = 0;
        if (startIndex > 0) {
            j = i - 1;
        }
        if (i >= coords.length) {
            break;
        }
        try {
            dest = new google.maps.LatLng(
                coords[i].lat(), coords[i].lng());
            fromDest = new google.maps.LatLng(
                coords[j].lat(), coords[j].lng());

            distance +=
                google.maps.geometry.spherical.computeDistanceBetween(
                    dest, fromDest);
        }
        catch (e) {
        }

    }

    if (distance <= 2) {
        return -2;
    }
    return distance;
}

function getPolyLines(enocoded, color) {
    var decodedPoints =
        google.maps.geometry.encoding.decodePath(enocoded);
    return new google.maps.Polyline({
        path: decodedPoints,
        strokeColor: color,
        strokeOpacity: 1,
        geodesic: true,
        zIndex: 1,
        strokeWeight: 5
    });
}

function getPolyLineFromLatLngs(latLngs, color) {
    return new google.maps.Polyline({
        path: latLngs,
        strokeColor: color,
        strokeOpacity: 1,
        strokeWeight: 3,
        zIndex: 1

    });
}

function computeTotalDistance(result) {
    var total = 0;
    var myroute = result.routes[0];
    for (var i = 0; i < myroute.legs.length; i++) {
        total += myroute.legs[i].distance.value;
    }
    total = total / 1000;
    document.getElementById('total').innerHTML = total + ' km';
}

function Compare(val1, val2) {
    if (val1 + 0.0001 >= val2.toFixed(4) && val1 - 0.0001 <= val2.toFixed(4)) {
        return true;
    }
    return false;
}

function CenterControl(controlDiv, map) {

    // Set CSS for the control border.
    var controlUI = document.createElement('div');
    controlUI.id = "shuttleInfoBtn";
    controlUI.className = "mapButton";
    controlUI.style.cursor = 'pointer';

    controlUI.title = 'Click to recenter the map';
    controlDiv.appendChild(controlUI);


    var anotherUI = document.createElement('div');
    anotherUI.id = "addWayPoint";
    anotherUI.className = "mapButton";

    anotherUI.style.marginLeft = '20px';
    anotherUI.title = 'Add Waypoint to Shuttle';
    controlDiv.appendChild(anotherUI);

    // Set CSS for the control interior.
    var controlText = document.createElement('img');
    // controlText.style.color = 'rgb(25,25,25)';
    // controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
    // controlText.style.fontSize = '16px';
    // controlText.style.lineHeight = '38px';
    //
    controlText.src = BaseURL+"img/bus.png";
    controlText.height = "64";
    // controlText.innerHTML = 'Shuttles Info';

    controlUI.appendChild(controlText);

    var anotherText = document.createElement('img');
    anotherText.src = BaseURL+"img/gps.png";
    anotherText.height = "64";

    anotherUI.appendChild(anotherText);

    // // Setup the click event listeners: simply set the map to Chicago.
    // controlUI.addEventListener('click', function() {
    // });
    lazzyLoadShuttleData(controlDiv, 1);
    lazzyLoadShuttleData(controlDiv, 0);

}

function shuttlevisibleChanged(id) {
    if ($("#shuttle" + id).is(':checked')) {
        polyline[id].setMap(map);
        marker[id].setMap(map);
    }
    else {
        polyline[id].setMap(null);
        marker[id].setMap(null);
    }
}

function createShuttleList(data) {
    html = '<label style="color:white; font-size:16px;">Arriving Soon</label></br>';
    for (i = 0; i < data.length; i++) {
        shuttle = data[i];
        html += '<input type="checkbox" id="shuttle' + shuttle.id + '" onchange="shuttlevisibleChanged(' + shuttle.id + ')" checked/>' + shuttle.shuttleName + '<label for="shuttle\' + shuttle.id + \'"></label></br>';
    }
    return html;


}

//
// function addStop() {
//     var addrs = $('#address').val();
//     var type = $('#typeOfPoint').val();
//     var shuttle = $('#shuttle').val();
//     if (shuttle != -1 && type != -1 && addrs != "") {
//         $.ajax({
//             url: 'api.php?action=insertWayPoint',
//             data: {
//                 routeId: shuttle,
//                 waypoint: addrs,
//                 isstop: type
//             },
//             method: "POST",
//             success: function (response) {
//                 if (response.status) {
//                     console.log(response.message);
//                     location.reload();
//                 }
//                 else {
//                     alert("Error Occured");
//
//                 }
//             },
//             error: function (data) {
//             }
//         });
//     }
//     else {
//         alert("Please fill all the fields");
//     }
//     console.log(addrs + "--" + type + "--" + shuttle);
//     // alert('this is alert');
// }


//
//
//
// var RotateIcon = function(options){
//     this.options = options || {};
//     this.rImg = options.img || new Image();
//     this.rImg.src = this.rImg.src || this.options.url || '/static/groups/img/car_map_state_go.png';
//     this.options.width = this.options.width || this.rImg.width || 52;
//     this.options.height = this.options.height || this.rImg.height || 60;
//     canvas = document.createElement("canvas");
//     canvas.width = this.options.width;
//     canvas.height = this.options.height;
//     this.context = canvas.getContext("2d");
//     this.canvas = canvas;
// };
// RotateIcon.makeIcon = function(url) {
//     return new RotateIcon({url: url});
// };
// RotateIcon.prototype.setRotation = function(options){
//     var canvas = this.context,
//         angle = options.deg ? options.deg * Math.PI / 180:
//             options.rad,
//         centerX = this.options.width/2,
//         centerY = this.options.height/2;
//
//     canvas.clearRect(0, 0, this.options.width, this.options.height);
//     canvas.save();
//     canvas.translate(centerX, centerY);
//     canvas.rotate(angle);
//     canvas.translate(-centerX, -centerY);
//     canvas.drawImage(this.rImg, 0, 0);
//     canvas.restore();
//     return this;
// };
// RotateIcon.prototype.getUrl = function(){
//     return this.canvas.toDataURL('image/png');
// };