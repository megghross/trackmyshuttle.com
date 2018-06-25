//You can calculate directions (using a variety of methods of transportation) by using the DirectionsService object.
var directionsService;

//Define a variable with all map points.
var _mapPoints = new Array();

var map;
var marker;
var stopCnt = 0;
var cnt = 0;
//Define a DirectionsRenderer variable.
var _directionsRenderer = '';
var gmarkers = [];
var gmarkersinfo = [];


var icon_stop = {
  url: 'https://png.icons8.com/ultraviolet/2x/marker.png',//place.icon,
  size: new google.maps.Size(71, 71),
  origin: new google.maps.Point(0, 0),
  anchor: new google.maps.Point(17, 34),
  scaledSize: new google.maps.Size(35, 35)
};

var icon_hotel = {
    url: "https://png.icons8.com/color/2x/4-star-hotel.png", // url
    scaledSize: new google.maps.Size(50, 50), // scaled size
};

//InitializeMap() function is used to initialize google map on page load.
function InitializeMap() {

    directionsService = new google.maps.DirectionsService();

    //DirectionsRenderer() is a used to render the direction
    _directionsRenderer = new google.maps.DirectionsRenderer({
      suppressMarkers: true
    });

   
    //Set the your own options for map.
    var myOptions = {
        zoom: 18,
        center: new google.maps.LatLng(33.945834, -118.377578),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: true,
        mapTypeControlOptions: {
          style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
          position: google.maps.ControlPosition.LEFT_BOTTOM
        },
        zoomControl: true,
        zoomControlOptions: {
          position: google.maps.ControlPosition.LEFT_CENTER
        },
        scaleControl: true,
        streetViewControl: true,
        streetViewControlOptions: {
          position: google.maps.ControlPosition.LEFT_TOP
        },
        fullscreenControl: true
    };

    //Define the map.
    map = new google.maps.Map(document.getElementById("dvMap"), myOptions);
    
    //Set the map for directionsRenderer
    _directionsRenderer.setMap(map);
    
    //Set different options for DirectionsRenderer mehtods.
    //draggable option will used to drag the route.

    
    
    //Add an event to route direction. This will fire when the direction is changed.
    google.maps.event.addListener(_directionsRenderer, 'directions_changed', function () {
        computeTotalDistanceforRoute(_directionsRenderer.directions);
    });

    google.maps.event.addListener(map, "click", function (event) {
        console.log("Latitude: "+event.latLng.lat()+" "+", longitude: "+event.latLng.lng() );    
    });


    // Create the search box and link it to the UI element.
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
      searchBox.setBounds(map.getBounds());
    });
    var markers = [];
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function() {
      var places = searchBox.getPlaces();

      if (places.length == 0) {
        return;
      }

      // Clear out the old markers.
      markers.forEach(function(marker) {
        marker.setMap(null);
      });
      markers = [];

      // For each place, get the icon, name and location.
      var bounds = new google.maps.LatLngBounds();
      places.forEach(function(place) {
        if (!place.geometry) {
          //console.log("Returned place contains no geometry");
          return;
        }
        // Create a marker for each place.
        markers.push(new google.maps.Marker({
          map: map,
          icon: icon_stop,
          title: place.name,
          position: place.geometry.location
        }));

        if (place.geometry.viewport) {
          // Only geocodes have viewport.
          bounds.union(place.geometry.viewport);
        } else {
          bounds.extend(place.geometry.location);
        }
      });
      map.fitBounds(bounds);


    });

   // showvahicle();

}

function showvahicle(){
    setTimeout(function() {
            $.post("vahiclestatus.php?agencies=371&ind=" + cnt, function(data) {
               var myObj = JSON.parse(data);
               //console.log(myObj);
                var lat_val = myObj.vehicles.gps.lat;
                var lng_val = myObj.vehicles.gps.lon;
                var heading = myObj.vehicles.gps.heading;
                //console.log(lat_val + '--' + lng_val);
                // marker.setMap(null);
                setmarker(lat_val, lng_val,heading);
            });
            if (cnt < 5)
                cnt+=1;
            else 
                cnt=0;
             showvahicle();
         }, 5000);
}

  function setmarker(mlat, mlng,heading) {

        //console.log(heading);
        mlat = parseFloat(mlat);
        mlng = parseFloat(mlng);
        /*
        var image = 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0b/MTS_Bus_icon.svg/500px-MTS_Bus_icon.svg.png';
        var icon = {
            url: image, // url
            scaledSize: new google.maps.Size(25, 25), // scaled size
            origin: new google.maps.Point(0, 0), // origin
            anchor: new google.maps.Point(0, 0) // anchor
        };  */
        var car = "M17.402,0H5.643C2.526,0,0,3.467,0,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759c3.116,0,5.644-2.527,5.644-5.644 V6.584C23.044,3.467,20.518,0,17.402,0z M22.057,14.188v11.665l-2.729,0.351v-4.806L22.057,14.188z M20.625,10.773 c-1.016,3.9-2.219,8.51-2.219,8.51H4.638l-2.222-8.51C2.417,10.773,11.3,7.755,20.625,10.773z M3.748,21.713v4.492l-2.73-0.349 V14.502L3.748,21.713z M1.018,37.938V27.579l2.73,0.343v8.196L1.018,37.938z M2.575,40.882l2.218-3.336h13.771l2.219,3.336H2.575z M19.328,35.805v-7.872l2.729-0.355v10.048L19.328,35.805z";
        var icon = {
            path: car,
            scale: .7,
            strokeColor: 'white',
            strokeWeight: .10,
            fillOpacity: 1,
            fillColor: '#404040',
            offset: '5%',
            rotation: parseInt(heading),
            anchor: new google.maps.Point(10, 25) // orig 10,50 back of car, 10,0 front of car, 10,25 center of car
        };
        //icon.rotation = 160;
        if (marker)
            marker.setMap(null);
        var newLatLng = new google.maps.LatLng(mlat, mlng);

        marker = new google.maps.Marker({
            position: newLatLng,
            map: map,
            icon: icon
        });

        buspossition_lat = mlat;
        buspossition_lng = mlng;
        // map.panTo(marker.getPosition());
        //map.setZoom(15);
        //map.setCenter(marker.getPosition());


        /*
        GET ALL STATUS ETA*/
        var origin1 = new google.maps.LatLng(mlat, mlng);
        //var origin1 = origin;

        var destinationA = 'Stockholm, Sweden';
        var destinationB = new google.maps.LatLng(50.087692, 14.421150);

        var _waypoints = new Array();
       // debugger
        var stops = new Array();       
        $.each(_mapPoints, function(index, value) {
            stops.push(value[0]);
        });       
       // console.log('stops ' + stops);
        var service = new google.maps.DistanceMatrixService();
        service.getDistanceMatrix({
            origins: [origin1],
            destinations: stops,
            travelMode: 'DRIVING',
          }, callback);

        function callback(response, status) {
          // See Parsing the Results for
          // the basics of a callback function.
         /* console.log(response.rows[0]);
            var cnt = 0;
             $.each(response.rows[0], function(index, value) {
                console.log(value[cnt]['distance']);
                cnt++;
               // console.log(value[0]['duration']['text']);
                //console.log(gmarkers[index].title='vijay');
            });       */

            if (status == 'OK') {
                var origins = response.originAddresses;
                var destinations = response.destinationAddresses;
                console.log('**origins : ' + origins);

                for (var i = 0; i < origins.length; i++) {
                  var results = response.rows[i].elements;
                  for (var j = 0; j < results.length; j++) {
                    var element = results[j];
                    var distance = element.distance.text;
                    var duration = element.duration.text;
                    var from = origins[i];
                    var to = destinations[j];
                    console.log('**from ' + from + ' **to' + to + ' **duration ' + duration);
                    console.log(origins);

                    gmarkers[j].setTitle('ETA : ' + duration); 
                    //gmarkers[j].infoWindow.setMap(null);

                    gmarkersinfo[j].close();
                    var infowindow = new google.maps.InfoWindow();
                    infowindow.setContent('ETA : ' + duration);
                    infowindow.open(map,gmarkers[j]);
                    gmarkersinfo[j] = infowindow;
                    /*
                    google.maps.event.addListener(gmarkers[j], 'click', function() {
                        infowindow.open(map, gmarkers[j]);
                    });
                    infowindow.open(map,gmarkers[j]);*/
                    
                    console.log('markertitle : ' + gmarkers[j].title);

                  }
                }

                console.log('**destinations : ' + destinations);


              }
        }   

    }

//getRoutePointsAndWaypoints() will help you to pass points and waypoints to drawRoute() function
function getRoutePointsAndWaypoints() {
    //Define a variable for waypoints.
    var _waypoints = new Array();
    //console.log(_mapPoints.length);
    if (_mapPoints.length > 2) //Waypoints will be come.
    {
        //console.log('_mapPoints' + _mapPoints);
        for (var j = 1; j < _mapPoints.length - 1; j++) {
            var address = _mapPoints[j][0];
            var stopover = false;
            if (_mapPoints[j][1]==1)
                stopover = true;
            //console.log('stopover' + stopover + 'J - ' + j);
            if (address !== "") {
                _waypoints.push({
                    location: address,
                    stopover: stopover  //stopover is used to show marker on map for waypoints
                });
            }
        }
        //Call a drawRoute() function
        drawRoute(_mapPoints[0][0], _mapPoints[_mapPoints.length - 1][0], _waypoints);
    } else if (_mapPoints.length > 1) {
        //Call a drawRoute() function only for start and end locations
        drawRoute(_mapPoints[_mapPoints.length - 2][0], _mapPoints[_mapPoints.length - 1][0], _waypoints);
    } else {
        //Call a drawRoute() function only for one point as start and end locations.
        //drawRoute(_mapPoints[_mapPoints.length - 1], _mapPoints[_mapPoints.length - 1], _waypoints);
        //setmarker($("#pac-input").val(),map);
        addOrigin();
    }
}

function addOrigin(){
    var address = document.getElementById("pac-input").value;
    var geocoder = new google.maps.Geocoder();
    //var image_icon = 'https://png.icons8.com/color/2x/4-star-hotel.png'; //http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png';
    

    geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location,
                icon: icon_hotel
            });
            _mapPoints.push([results[0].geometry.location,1]);
            //console.log("Add origin" + results[0].geometry.location);
        }           
    });
}


//drawRoute() will help actual draw the route on map.
function drawRoute(originAddress, destinationAddress, _waypoints) {
    //console.log('originAddress' + originAddress);
    //Define a request variable for route .
    var _request = '';    
    
    //console.log('_waypoints : ' + _waypoints[0].stopover);
    //This is for more then two locatins
    if (_waypoints.length > 0) {
        _request = {
            origin: originAddress,
            destination: destinationAddress,
            waypoints: _waypoints, //an array of waypoints
            optimizeWaypoints: false, //set to true if you want google to determine the shortest route or false to use the order specified.
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        };
    } else {
        //This is for one or two locations. Here noway point is used.
        _request = {
            origin: originAddress,
            destination: destinationAddress,
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        };
        //console.log(_request);
    }

    //This will take the request and draw the route and return response and status as output
    directionsService.route(_request, function (_response, _status) {
        //console.log('directionsService - ' + _status);
        if (_status == google.maps.DirectionsStatus.OK) {
            _directionsRenderer.setDirections(_response);
            clearOverlays();
            RenderCustomDirections(_response, _status);

        }
    });
}

function clearOverlays() {
  for (var i = 0; i < gmarkers.length; i++ ) {
    gmarkers[i].setMap(null);
  }
  gmarkers.length = 0;
  //_directionsRenderer.setMap(null);
}

//calculateAndDisplayRoute END HERE
function RenderCustomDirections(response, status) {
  if (status == google.maps.DirectionsStatus.OK) {
    waypts = [];
    var bounds = new google.maps.LatLngBounds();
    var route = response.routes[0];
    startLocation = new Object();
    endLocation = new Object();

    var path = response.routes[0].overview_path;
    var legs = response.routes[0].legs;

    for (i = 0; i < legs.length; i++) {
      if (i == 0) {
        startLocation.latlng = legs[i].start_location;
        startLocation.address = legs[i].start_address;
        startLocation.marker = createMarker(legs[i].start_location, "Hotel IX", legs[i].start_address, "hotel", String.fromCharCode("A".charCodeAt(0)));
      } else {

        //console.log('legs - ');
        //console.log(legs[i].via_waypoint);
        if(legs[i].via_waypoint.length>0)
        {
            for (var l = 0; l < legs[i].via_waypoint.length; l++) {
                _temPoint.push([legs[i].via_waypoint[l].location,0]); //E.g: C to may
                _htmlTrCount++;                
                createMarker(legs[i].via_waypoint[l].location,'Waypoint',legs[i].via_waypoint[l].location, "waypts", "");
            }
        }

        waypts[i] = new Object();
        waypts[i].latlng = legs[i].start_location;
        waypts[i].address = legs[i].start_address;
        //waypts[i].marker = createMarker(legs[i].start_location, res.data[(i - 1)].empName + " " + res.data[(i -1)].stratingTime+  " " + res.data[(i -1)].presentStatus, legs[i].start_address, "yellow", String.fromCharCode("A".charCodeAt(0) + i));
        //waypts[i].marker = createMarker(legs[i].start_location,'Stop ' + i,legs[i].start_address, "yellow", String.fromCharCode("A".charCodeAt(0) + i));
        waypts[i].marker = createMarker(legs[i].start_location,legs[i].start_address,legs[i].start_address, "yellow", String.fromCharCode("A".charCodeAt(0) + i));

      }

      endLocation.latlng = legs[i].end_location;
      endLocation.address = legs[i].end_address;
      var steps = legs[i].steps;
      for (j = 0; j < steps.length; j++) {
        var nextSegment = steps[j].path;
        for (k = 0; k < nextSegment.length; k++) {
          bounds.extend(nextSegment[k]);
        }
      }
    }
    if (startLocation.latlng==endLocation.latlng)
        endLocation.marker = createMarker(endLocation.latlng, "Hotel IX", endLocation.address, "hotel", String.fromCharCode("A".charCodeAt(0) + waypts.length));
    else
        //endLocation.marker = createMarker(endLocation.latlng, 'Stop ' + i, endLocation.address, "red", String.fromCharCode("A".charCodeAt(0) + waypts.length));
        endLocation.marker = createMarker(endLocation.latlng, endLocation.address, endLocation.address, "red", String.fromCharCode("A".charCodeAt(0) + waypts.length));

    google.maps.event.trigger(endLocation.marker, 'click');
  } else alert(status);
}

//Create a html tr count variable
var _htmlTrCount = 0;

//computeTotalDistanceforRoute() will help you to calculate the total distance and render dynamic html.
function computeTotalDistanceforRoute(_result) {

    //console.log('_result');
    //console.log(_result);
    //Get the route
    var _route = _result.routes[0];

    //This will remove all rows from table with id=HtmlTable
    $("#HtmlTable").find("tr").remove();
    $("#tblRoutes").find("tr.waypoints").remove();

    //Create temporary points variables.
    var _temPoint = new Array();

    _htmlTrCount = 0;
    for (var k = 0; k < _route.legs.length; k++) {
        //START Get the max lenth of steps.
        var lenght = 0;
        if ((_route.legs[k].steps.length) - 1 < 0) {
            var lenght = _route.legs[k].steps.length;
        } else {
            var lenght = _route.legs[k].steps.length - 1;
        }
        

        if (_route.legs[k].distance.value >= 0) //This look is for more then one point i,e after B pionts
        {
            if(_route.legs[k].via_waypoint.length>0)
            {
                for (var l = 0; l < _route.legs[k].via_waypoint.length; l++) {
                    _temPoint.push([_route.legs[k].via_waypoint[l].location,0]); //E.g: C to may
                    _htmlTrCount++;
                    var stop = k+l+2;
                    CreateHTMTable(_route.legs[k].via_waypoint[l].location, 0,'Waypoint ' + k,'Waypoint');
                }
            }

            if (k == 0) //If there are only one route with two points.
            {
                _temPoint.push([_route.legs[k].steps[0].start_point,1]); //E.g: A
                _htmlTrCount++;
               // CreateHTMTable(_route.legs[k].steps[0].start_point, _route.legs[k].distance.value,_route.legs[k].start_address); //Create html table
                CreateHTMTable(_route.legs[k].steps[0].start_point, _route.legs[k].distance.value,'Hotel IX','Hotel'); //Create html table
                _temPoint.push([_route.legs[k].steps[lenght].end_point,1]); //E.g: B
                _htmlTrCount++;
                CreateHTMTable(_route.legs[k].steps[lenght].end_point, _route.legs[k].distance.value,'Stop 1','Stop'); //Create html table
            } else // more routes and more points
            {

                _temPoint.push([_route.legs[k].steps[lenght].end_point,1]); //E.g: C to may
                _htmlTrCount++;
                //CreateHTMTable(_route.legs[k].steps[lenght].end_point, _route.legs[k].distance.value,_route.legs[k].end_address); //Create html table
                var stop = k+1;
                CreateHTMTable(_route.legs[k].steps[lenght].end_point, _route.legs[k].distance.value,_route.legs[k].end_address + 'Stop ' + stop,'Stop'); //Create html table
            }
           
        } else // if distance is zero then it is the first point ie A
        {
            _temPoint.push([_route.legs[k].steps[lenght].start_point,1]); //E.g: A
            _htmlTrCount++;
            CreateHTMTable(_route.legs[k].steps[lenght].start_point, _route.legs[k].distance.value,_route.legs[k].end_address,'Hotel'); //Create html table
        }
    }
    
    //console.log('_mapPoints before');
    //console.log(_mapPoints);
    //console.log(_temPoint);
    //Assigne temporary ponts to _mapPoints array
    _mapPoints = new Array();
    // for (var y = 0; y < _temPoint.length; y++) {
    //     _mapPoints.push([_temPoint[y]]);
    // }
    _mapPoints = _temPoint;
    //console.log('_mapPoints after');
    //console.log(_mapPoints);
}

//CreateHTMTable() will help you to create a dynamic html table
function CreateHTMTable(_latlng, _distance,_address,type) {
    var _Speed = 'km';
    var _Time = parseInt(((_distance / 1000) / _Speed) * 60);
    var lable = "";
    if (_htmlTrCount - 1 == 0) {
        _Time = 0;
        _distance = 0;
        lable= "hotel";
    }

   /* var html = '';
    var title = new Array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O");
    html = html + "<tr id=\"" + _htmlTrCount + "\">";
    html = html + "<td style=\"width: 80px;\">" + _htmlTrCount + "</td>";
    html = html + "<td style=\"width: 80px;\"><span id=\"Title_" + _htmlTrCount + "\">" + type + "</span></td>";
    html = html + "<td style=\"width: 100px;\"><span id=\"lat_" + _htmlTrCount + "\">" + parent.String(_latlng).split(",")[0].substring(1, 8) + "</span></td>";
    html = html + "<td style=\"width: 100px;\"><span id=\"lng_" + _htmlTrCount + "\">" + parent.String(_latlng).split(",")[1].substring(1, 8) + "</span></td>";
    html = html + "<td style=\"width: 100px;\"><span id=\"dir_" + _htmlTrCount + "\">" + _distance + "</span></td>";
    html = html + "<td style=\"width: 70px;\"><span id=\"time_" + _htmlTrCount + "\">" + _Time + "</span></td>";
    html = html + "<td style=\"width: 60px;\"><img alt=\"DeleteLocation\" src=\"assets/img/Delete.jpg\" onclick=\"return deleteLocation(" + _htmlTrCount + ");\" /></td>";
    html = html + "</tr>";
    $("#HtmlTable").append(html);*/
    var img ='';
    type = type.toLowerCase();
    if (type=="hotel") {
        img = "https://png.icons8.com/color/2x/4-star-hotel.png";
    } else if (type=="stop") {
        img = "https://png.icons8.com/ultraviolet/2x/marker.png";
     } else if (type=="waypoint") {
        img = "https://www.anytimefitnessfranchise.com/wp-content/plugins/wp-google-maps/images/marker_blue.png";
     }
    html = "<tr id=\"" + _htmlTrCount + "\" class='waypoints'>";
    html = html + "<td style=\"width: 10%;\"><span id=\"Title_" + _htmlTrCount + "\"><img src='" + img + "' style='width:30px'/></span></td>";
    html = html + "<td style=\"width: 70%;\"><span id=\"Title_" + _htmlTrCount + "\">" + _address + "</span></td>";
    html = html + "<td style=\"width: 10%;\"><img alt=\"DeleteLocation\" src=\"assets/img/Delete.jpg\" onclick=\"return deleteLocation(" + _htmlTrCount + ");\" /></td>";
    html = html + "</tr>";

    $("#tblRoutes").append(html);
    draganddrophtmltablerows();
}

    //This will useful to delete the location
    function deleteLocation(trid) {
        if (confirm("Are you sure want to delete this location?") == true) {
            var _temPoint = new Array();
            for (var w = 0; w < _mapPoints.length; w++) {
                if (trid != w + 1) {
                    _temPoint.push([_mapPoints[w][0],_mapPoints[w][1]]);
                }
            }
            _mapPoints = new Array();
            for (var y = 0; y < _temPoint.length; y++) {
                _mapPoints.push([_temPoint[y][0],_temPoint[y][1]]);
            }
            stopCnt--;       
            getRoutePointsAndWaypoints();
        } else {
            return false;
        }
    }
    //This will useful to swap rows the location
    function draganddrophtmltablerows() {
        var _tempPoints = new Array();

        // Initialise the first table (as before)
        //$("#HtmlTable").tableDnD();

        // Initialise the second table specifying a dragClass and an onDrop function that will display an alert
       /* $("#HtmlTable").tableDnD({
            onDrop: function (table, row) {
                var rows = table.tBodies[0].rows;

                for (var q = 0; q < rows.length; q++) {
                    _tempPoints.push([_mapPoints[rows[q].id - 1][0],_mapPoints[rows[q].id - 1][1]]);
                }

                _mapPoints = new Array();
                for (var y = 0; y < _tempPoints.length; y++) {
                    _mapPoints.push([_tempPoints[y][0],_tempPoints[y][1]]);
                }

                getRoutePointsAndWaypoints();
            }
        });*/
    }

    function RouteAdded(){
        google.maps.event.clearListeners(map, 'click');
        //console.log('route added');
    }

    function StopAdd(stop) {
        if (_mapPoints.length-1==stopCnt) {
            //Add the doubel click event to map.
            $("#tblRoutes > tbody").append('<tr id=\"' + stopCnt + '\" class="waypoints"><td>&nbsp;</td><td><input name="locinput" id="pac-input'+stopCnt+'" class="controls autocomplete" type="text" placeholder="Search Box" value=""></td><td style=\"width: 60px;\"><img alt=\"DeleteLocation\" src=\"assets/img/Delete.jpg\" onclick=\"return deleteLocation('+ stopCnt + ');\" /></td></tr>');
            var input = document.getElementById('pac-input'+stopCnt);
            var searchBox = new google.maps.places.SearchBox(input);
            $(input).focus();
            var searchBox = new google.maps.places.SearchBox(input);
            //BOUND SEARCHBOX START ***********************
            //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function() {
              searchBox.setBounds(map.getBounds());
            });
            var markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

               /* // Clear out the old markers.
                markers.forEach(function(marker) {
                marker.setMap(null);
                });
                markers = [];

                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {
                    if (!place.geometry) {
                      console.log("Returned place contains no geometry");
                      return;
                    }
                    // Create a marker for each place.
                    markers.push(new google.maps.Marker({
                      map: map,
                      icon: icon_stop,
                      title: place.name,
                      position: place.geometry.location
                    }));

                    if (place.geometry.viewport) {
                      // Only geocodes have viewport.
                      bounds.union(place.geometry.viewport);
                    } else {
                      bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);*/
                 var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {
                    var _currentPoints = place.geometry.location;
                    _mapPoints.push([_currentPoints,stop]); 
                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode({
                        'latLng': _currentPoints.latLng
                    }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                          if (results[0]) {
                                $(input).val(results[0].formatted_address);
                          }
                        }
                    });  
                });          

                RouteAdded();
                getRoutePointsAndWaypoints();  
            });
            //BOUND SEARCHBOX END ***********************
            google.maps.event.addListener(map, "click", function (event) {
                console.log("Latitude: "+event.latLng.lat()+" "+", longitude: "+event.latLng.lng() );    
                var _currentPoints = event.latLng;
                _mapPoints.push([_currentPoints,stop]); 
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                    'latLng': event.latLng
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                      if (results[0]) {
                            $(input).val(results[0].formatted_address);
                      }
                    }
                });            

                RouteAdded();
                getRoutePointsAndWaypoints();            
            });
            //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function() {
              searchBox.setBounds(map.getBounds());
            });
            stopCnt++;            
            
        } else {
            alert("Please select previous stop");
        }
    }

    function getdata(){
        $.ajax({        
            url: "getData.php",
            complete: function(data) {
                if (data.responseText!='') {
                    arr = JSON.parse(data.responseText);
                    if (arr !=null) {
                        _temPoint = JSON.parse(arr["route_points"]);
                        _mapPoints = new Array();
                        stopCnt = -1;
                        for (var y = 0; y < _temPoint.length; y++) {
                            //console.log(_temPoint[y]);
                            _mapPoints.push([_temPoint[y][0],_temPoint[y][1]]);
                            stopCnt++;
                        }
                        getRoutePointsAndWaypoints();  

                        showvahicle();

                    } else {
                        addOrigin();
                    }
                }
           },
            error: function() {
                console.log("Function: forward_to_server() error");
           }});
    }

    var icons = new Array();
        icons["red"] = {
          url: "http://maps.google.com/mapfiles/ms/micons/red.png",
          // This marker is 32 pixels wide by 32 pixels tall.
          size: new google.maps.Size(32, 32),
          // The origin for this image is 0,0.
          origin: new google.maps.Point(0, 0),
          // The anchor for this image is at 9,34.
          anchor: new google.maps.Point(16, 32),
          labelOrigin: new google.maps.Point(16, 10)
        };

        function getMarkerImage(iconColor) {
              if ((typeof(iconColor) == "undefined") || (iconColor == null)) {
                iconColor = "red";
              }
              if (!icons[iconColor]) {
                    icons[iconColor] = {
                        //url: "http://maps.google.com/mapfiles/ms/micons/" + iconColor + ".png",
                        url: "https://cdn2.iconfinder.com/data/icons/metro-uinvert-dock/256/Google_Maps.png",
                        // This marker is 32 pixels wide by 32 pixels tall.
                        size: new google.maps.Size(71, 71),
                        // The origin for this image is 0,0.
                        origin: new google.maps.Point(0, 0),
                        // The anchor for this image is at 6,20.
                        anchor: new google.maps.Point(17, 34),
                        labelOrigin: new google.maps.Point(16, 10),
                        scaledSize: new google.maps.Size(35, 35)
                    };
              }
              return icons[iconColor];
        }

        // var iconImage = {
        //       url: 'http://maps.google.com/mapfiles/ms/micons/red.png',
        //       // This marker is 20 pixels wide by 34 pixels tall.
        //       size: new google.maps.Size(20, 34),
        //       // The origin for this image is 0,0.
        //       origin: new google.maps.Point(0, 0),
        //       // The anchor for this image is at 9,34.
        //       anchor: new google.maps.Point(9, 34)
        // };

        var iconShape = {
          coord: [9, 0, 6, 1, 4, 2, 2, 4, 0, 8, 0, 12, 1, 14, 2, 16, 5, 19, 7, 23, 8, 26, 9, 30, 9, 34, 11, 34, 11, 30, 12, 26, 13, 24, 14, 21, 16, 18, 18, 16, 20, 12, 20, 8, 18, 4, 16, 2, 15, 1, 13, 0],
          type: 'poly'
        };

        

function createMarker(latlng, title, html, color, label) {
    //var contentString = '<b>' + title + '</b><br>' + html;
    var contentString = '<b>' + title + '</b>';
    var marker;
    var icon;
    if (color=="hotel")
    {
        icon = getMarkerImage(color);
        marker = new google.maps.Marker({
            position: latlng,
            draggable: false,
            map: map,
            icon: icon,
            title: title
        });
    }
    else if (color=="waypts")
    {
        var icon = {
          path: google.maps.SymbolPath.CIRCLE,
          scale: 5
        };
        marker = new google.maps.Marker({
            position: latlng,
            draggable: false,
            map: map,
            title: title,
            icon:icon
            //label: "waypts"
        });
    }
    else
    {
        //icon =  getMarkerImage(color);

        var image = {
          url: 'https://png.icons8.com/ultraviolet/1x/marker.png',//place.icon,
        };

        //var image = 'https://png.icons8.com/ultraviolet/30/marker.png';
       
        marker = new google.maps.Marker({
            position: latlng,
            draggable: false,
            map: map,icon:image,
            title: title
        });
    }

    marker.myname = title;
    gmarkers.push(marker);
    var infowindow = new google.maps.InfoWindow();
    infowindow.setContent(contentString);
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map, marker);
    });
    infowindow.open(map,marker);
    gmarkersinfo.push(infowindow);
    return marker;
}


$(function() {
    //getRoutePointsAndWaypoints();   
    $("#btnAddStop").on("click",function(){
        StopAdd(1);
    });
    $("#btnAddWaPoint").on("click",function(){
        StopAdd(0);
    });

    $("#btnBackToHotel").on("click",function(){
        
        var address = document.getElementById("pac-input").value;       
        var geocoder = new google.maps.Geocoder();
        var image_icon = 'http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png';
        geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                _mapPoints.push([results[0].geometry.location,1]);
                //console.log("Add Hotel : " + results[0].geometry.location);
                getRoutePointsAndWaypoints();  

                $("#tblRoutes > tbody").append('<tr id=\"' + stopCnt + '\" class="waypoints"><td><input name="locinput" id="pac-input'+stopCnt+'" class="controls autocomplete" type="text" placeholder="Search Box" value="'+address+'"></td><td style=\"width: 60px;\"><img alt=\"DeleteLocation\" src=\"Images/Delete.jpg\" onclick=\"return deleteLocation('+ stopCnt + ');\" /></td></tr>');
                var input = document.getElementById('pac-input'+stopCnt);
                var searchBox = new google.maps.places.SearchBox(input);
                //$(input).focus();
                stopCnt++;   

            }           
        });      

    });
    getdata();



    $("#btnSaveRoute").on("click",function(){
        var routename = $("#txtRouteName").val();
        if (routename=="")
        {
            alert("Please enter route name");
            return false;
        } else {

            jsonString = JSON.stringify(_mapPoints);
            $.ajax({        
                type: "POST",
                url: "sendData.php",
                data: {points:jsonString, routename:routename},
                complete: function() {
                    console.log("Success");
               },
               error: function() {
                   console.log("Function: forward_to_server() error")
               }
           });
        }

    });

    $("#btnNew").on("click",function(){
        _mapPoints = new Array();
         getRoutePointsAndWaypoints();    
        clearOverlays();        

    });

});
