<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Directions service</title>
    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 100%;
        }
        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        #floating-panel {
            position: absolute;
            top: 10px;
            left: 25%;
            z-index: 5;
            background-color: #fff;
            padding: 5px;
            border: 1px solid #999;
            text-align: center;
            font-family: 'Roboto','sans-serif';
            line-height: 30px;
            padding-left: 10px;
        }
    </style>
</head>
<body>
<div id="map"></div>

<script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
<script>
    function initMap() {
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 7,
            center: {lat: 41.85, lng: -87.65}
        });
        directionsDisplay.setMap(map);

        var onChangeHandler = function() {
            calculateAndDisplayRoute(directionsService, directionsDisplay);
        };
        onChangeHandler();


        google.maps.event.addListener(map,'click',function(event) {
            event.latLng.lat()
            $.ajax({
                url: "{{route("util.update")}}",
                "method": "POST",
                data:{
                    "serialNumber":"98556448522",
                    "lat" : event.latLng.lat(),
                    "long": event.latLng.lng()
                },
                success: function(data){
                    // data = JSON.parse(data);
                    alert(data.msg + ", With probablity of fuelling " + data.probablity  +  "%. Distance from Route is "+Math.round(data.routeDistance)+ " meters. Distance from Gas Station is "+Math.round(data.gas_stationDistance)+" meters.");
                }
            })

        });

        // document.getElementById('start').addEventListener('change', onChangeHandler);
        // document.getElementById('end').addEventListener('change', onChangeHandler);
    }
    function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        directionsService.route({
            origin: "The Westin Crystal City, 1800 Jefferson Davis Hwy, Arlington, VA 22202, USA",
            destination: "The Westin Crystal City, 1800 Jefferson Davis Hwy, Arlington, VA 22202, USA",
            waypoints: [{
                location: "Terminal A, Arlington, VA 22202, USA",
                stopover: false
            }],
            optimizeWaypoints: true,
            travelMode: 'DRIVING'
        }, function(response, status) {
            if (status === 'OK') {
                directionsDisplay.setDirections(response);
            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
    }



</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDNSD8o2CyNEWb73m62IUL9i7T4i9TF3rM&callback=initMap">
</script>
</body>
</html>