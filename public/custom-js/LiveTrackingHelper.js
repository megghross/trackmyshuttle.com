class LiveTrackingHelper {
    constructor(Map) {
        this.Routes = [];
        this.FetchRouteUrl = $("body").data("fetchrouteurl");
        this.RouteOnDisplay = false;
        this.CurrentRoute = null;
        this.MapHelper = null;
        this.Polyline = null;
        this.StopMarkers = [];
        this.InfoWindows = [];
        this.LatLngArray = [];
        this.Map = Map;
        this.InitRoutes();
    }
    searchIndex(position){
        if(this.LatLngArray.length===0){
            return false;
        }
        let latLngArray = this.LatLngArray;
        for(let i=0; i<latLngArray.length; i++){
            let point = latLngArray[i];
            if(point.legIndex===position.legIndex && point.stepIndex===position.stepIndex && point.pointIndex===position.pointIndex){
                return i;
            }
        }
    }

    InitRoutes() {
        let helper = this;

        $.ajax({
            url: URL,
            async: false,
            method: "GET",
            success: function (response) {
                for (let i = 0; i < response.length; i++) {

                    let route = response[i];

                    helper.Routes.push(new Route(route, i));
                }
                $(".loader").css("display", "none");
            }
        });
    }

    Route(i) {
        if (i >= this.Routes.length) {
            return null;
        }
        return this.Routes[i];
    }

    FindRouteById(routeid) {
        let returnResult = null;
        for (let i = 0; i < this.Routes.length; i++) {
            let route = this.Routes[i].Route;
            if (route.id == routeid) {
                returnResult = this.Route(i);
                break;
            }
        }

        return returnResult;

    }

    showRoute(routeId) {
        if (!this.RouteOnDisplay) {
            this.loadRoute(routeId);
        }
        else {
            if (this.CurrentRoute.Route.id !== routeId) {
                this.loadRoute();
            }
        }
    }

    loadRoute(routeId) {
        let route = this.FindRouteById(routeId);
        this.CurrentRoute = route;
        let helper = this;
        $.ajax({
            url: helper.FetchRouteUrl,
            method: "POST",
            data: {
                routeId: routeId
            },
            success: function (response) {
                console.log(response);
                if (helper.Polyline !== null) {
                    helper.polyline.setMap(null);
                }
                if(helper.LatLngArray.length>0){
                    helper.LatLngArray = [];
                }
                helper.MapHelper = new RouteHelper(response.data.routes[0]);
                helper.polyline = helper.MapHelper.PolyLine;
                helper.polyline.setMap(map);
                helper.polyline.setOptions({strokeColor: helper.CurrentRoute.Route.color});
                var bounds = new google.maps.LatLngBounds();
                helper.polyline.getPath().forEach(function (element, index) {
                    bounds.extend(element)
                });

                let stopmarkers = response.markers;
                helper.showStopMarker(stopmarkers);


                helper.RouteOnDisplay = true;
            }
        });
    }


    showStopMarker(stopMarkers) {
        let route = this.MapHelper.Route;
        let icon_size = 24;
        for (let k = 0; k < stopMarkers.length; k++) {
            let wayPoint = stopMarkers[k];
            let name = wayPoint.name;
            let waypointPosition, waypointaddress;
            if (wayPoint.type === 'End') {
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

            let icon = {
                url: BaseURL + "img/marker-" + wayPoint.type + ".png",
                scaledSize: new google.maps.Size(icon_size, icon_size), // scaled size
                origin: new google.maps.Point(0, 0), // origin
                anchor: new google.maps.Point(parseInt(icon_size / 2), parseInt(icon_size / 2)), // anchor
                labelOrigin: new google.maps.Point(parseInt(icon_size / 2), -10)
            };

            let localMarker = new google.maps.Marker({
                map: map,
                position: waypointPosition,
                icon: icon,
                customInfo: {
                    routeId: this.CurrentRoute.Route.id,
                    type: wayPoint.type,
                    labelId: this.CurrentRoute.Route.id+''+k,
                    name: this.CurrentRoute.Route.id + '-' + k,
                    position: waypointPosition,
                    address: waypointaddress,
                    markerId: k,
                    shuttleNumber: this.CurrentRoute.Route.id,
                    stopName: name,
                    layer: 'marker'
                }
            });

            this.StopMarkers[k] = localMarker;
            this.InfoWindows[k] = {};
            this.InfoWindows[k] = new google.maps.InfoWindow({
                content: "<div id='shuttle" + this.CurrentRoute.Route.id + "" + k + "'>Loading</div>"
            });

        }
    }


}


class Route {

    constructor(RouteDetail, index) {
        this.Route = RouteDetail;
        this.RouteIndex = index;
        this.Shuttles = [];

        this.InitShuttles();
    }

    InitShuttles() {
        let route = this.Route;
        for (let j = 0; j < route.devices.length; j++) {

            let shuttleObj = route.devices[j];
            //SHow shuttles on View
            var htmlStr = ['<div class="route-box"',
                'data-routeid="' + route.id + '"',
                'data-routeIndex="' + this.RouteIndex + '"',
                'data-shuttleIndex="' + j + '"',
                'id="' + route.id + '">',
                '<a href="#">',
                '<img src="' + BaseURL + 'img/bus.png" alt="Routes">',
                '<div class="desc">',
                shuttleObj.shuttleName
                , '</div></a></div>'
            ].join(' ');
            $(".routes-box").append(htmlStr);

            this.Shuttles.push(new Shuttle(shuttleObj, j));
        }
    }

    Shuttle(i) {
        if (i >= this.Shuttles.Length) {
            return null;
        }
        return this.Shuttles[i];
    }
}


class Shuttle {
    constructor(ShuttleDetail, index) {
        this.Shuttle = ShuttleDetail;
        this.ShuttleIndex = index;
        this.shuttleMarker = null;
        this.InitShuttleAsMarker();
        this.timeOutVar = null;
    }


    InitShuttleAsMarker() {
        let car = "M97.94,111.31a.69.69,0,0,0-.52-.21,1.19,1.19,0,0,0-.44.09l-11.12,4.22a2.62,2.62,0,0,0-.72.44l.89-8.31a5.64,5.64,0,0,0,4.92-4.17h.57V15.19C91.52,5.34,88.47,0,72.36,0h0L20.55.24C15.77.24,6.61,3.18,6.61,7.8v95.57h.57a5.69,5.69,0,0,0,5.28,4.2l.91,8.6a2.41,2.41,0,0,0-1-.75L1.21,111.19a1.11,1.11,0,0,0-.43-.09.69.69,0,0,0-.52.21c-.37.4-.23,1.26-.2,1.43v.08A7.33,7.33,0,0,0,3.32,116l7.53,3.06a2.5,2.5,0,0,0,1,.19,2,2,0,0,0,1.77-.94l4.83,45.54a7.32,7.32,0,0,0,2.36,4.79c3.85,4.07,11.44,6.76,14.8,6.76l25.34-.25h3.32c2.63-.11,6.33-1.59,9.49-3.76,2.19-1.48,6-4.54,6.38-8.37l4.74-44.36a2.09,2.09,0,0,0,1.53.62,2.5,2.5,0,0,0,1-.19L94.88,116a7.33,7.33,0,0,0,3.23-3.18v-.08A1.88,1.88,0,0,0,97.94,111.31ZM86.68,18.73a1.15,1.15,0,0,1,2.29,0V58.27a1.15,1.15,0,0,1-2.29,0Zm0,48.92a1.15,1.15,0,0,1,2.29,0v31a6.16,6.16,0,0,0-2.29-1ZM11.47,97.58a6.43,6.43,0,0,0-2.28,1v-31a1.09,1.09,0,0,1,1.14-1,1.09,1.09,0,0,1,1.14,1Zm0-39.31a1.09,1.09,0,0,1-1.14,1,1.09,1.09,0,0,1-1.14-1V18.73a1.08,1.08,0,0,1,1.14-1,1.08,1.08,0,0,1,1.14,1ZM68.35,104.1c3,0,5.37,1.57,5.37,3.51s-2.4,3.52-5.37,3.52H30.12c-3,0-5.37-1.57-5.37-3.52s2.41-3.51,5.37-3.51ZM15.17,3.56c-.06,0,0-.35,0-.53a6.22,6.22,0,0,0,0-.75,22.81,22.81,0,0,1,2.49-.69l.45-.07a3,3,0,0,1,.47,0l10.21.08a1,1,0,0,1,.56.11c.11.1.11.38.11.69v.38c0,.33,0,.64-.13.76a1,1,0,0,1-.54.11H15.5C15.22,3.6,15.17,3.56,15.17,3.56ZM21.37,168a6.26,6.26,0,0,1-2-4.1h0l-.21-2.05,2.65.16a19,19,0,0,0,2.45,4.26c1.55,2.17,3.26,5,6.18,7.1A24.3,24.3,0,0,1,21.37,168Zm57.84-5c-.46,4.37-6.35,8.49-11.09,10.27,2.89-2.06,4.65-4.85,6.2-7A19,19,0,0,0,76.77,162l2.56-.15Zm-1.85-6.8a21.78,21.78,0,0,1-6.9,6.1c-5.85,3.43-13.8,5.32-21.76,5.29s-15.87-2-21.66-5.43c-4.58-2.71-7.7-6.39-8.74-10.29H19a13.53,13.53,0,0,0,2,3.95,22,22,0,0,0,6.76,5.89c5.65,3.33,13.32,5.16,21,5.15s15.38-1.73,21.08-5a22.09,22.09,0,0,0,6.8-5.86,13.64,13.64,0,0,0,2.09-4.13h.67a13.28,13.28,0,0,1-2,4.32Zm2.46-41.88L78.48,131.9l-.36.1a3.12,3.12,0,0,1-2,1.83,100.33,100.33,0,0,1-54.41-.24,3,3,0,0,1-2.12-2.45l-1.68-18.35a1.3,1.3,0,0,1,.19-.85,1.09,1.09,0,0,1,.13-.17,1.22,1.22,0,0,1,1.58-.31c10.54,6.65,19.5,9.43,29.39,9.43,9.57,0,19-2.78,28.79-9.2a1.12,1.12,0,0,1,1.57.23l0,.06a1.56,1.56,0,0,1,.3,1.06ZM82.48,3.56a.73.73,0,0,1-.33,0H68.9a1,1,0,0,1-.54-.11c-.14-.12-.13-.43-.13-.76V2.35c0-.31,0-.59.11-.69a1,1,0,0,1,.56-.11L79,1.47c.76.11,1.47.25,2.14.4.5.14,1,.29,1.34.41a6.45,6.45,0,0,0,0,.75C82.52,3.21,82.54,3.51,82.48,3.56Z";
        let icon = {
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
        this.shuttleMarker = new google.maps.Marker({
            icon: icon,
            label: this.Shuttle.shuttleName,
            customInfo: {
                serialNumber: this.Shuttle.serialNumber,
                deviceToken: this.Shuttle.deviceToken,
                name: this.Shuttle.shuttleName,
                type: "Shuttle"
            }
        });
    }

    ShowOnMap(map) {
        this.shuttleMarker.setMap(map);
    }

    setPosition(lat, long) {
        let position = new google.maps.LatLng(
            lat, long);

        map.setCenter(position);

        // this.shuttleMarker.setPosition(position);


    }


    startAnimation(liveTracker, currentPosition, predictedPosition) {

        console.log(currentPosition);
        console.log(predictedPosition);
        if(liveTracker.LatLngArray.length==0){
            liveTracker.LatLngArray = liveTracker.MapHelper.GetFullPolyLineArray();
        }

        let startIndex = liveTracker.searchIndex(currentPosition);
        let endIndex = liveTracker.searchIndex(predictedPosition);


        console.log(startIndex);
        console.log(endIndex);
        if(this.timeOutVar!=null){
            clearTimeout(this.timeOutVar);
            this.timeOutVar= null;
        }
        this.animateMarker(liveTracker.LatLngArray, startIndex, endIndex,100, 'Don')
        // let position = new google.maps.LatLng(
        //     lat, long);
        //
        // map.setCenter(position);
        //
        // this.shuttleMarker.setPosition(position);delay


    }

    animateMarker(latLngArray, startIndex, endIndex, km_h, markerTitle) {

        let coords1 = latLngArray;
        let target = startIndex;
        let marker = this.shuttleMarker;
        let delay = 100;
        let helper = this;
        marker.setPosition(coords1[target]);

        // var timeInMilliSeconds = ((distnaceMeter/1000)/km_h) * 60 *60 *1000;

        function goToPoint() {

            let lat = marker.position.lat();
            let lng = marker.position.lng();
            let step = (km_h * 1000 * delay) / 3600000; // in meters
            let dest;

            if (Array.isArray(coords1[target])) {
                dest = new google.maps.LatLng(
                    coords1[target][0], coords1[target][1]);
            }
            else {
                dest = new google.maps.LatLng(
                    coords1[target].lat(), coords1[target].lng());
            }

            let distance =
                google.maps.geometry.spherical.computeDistanceBetween(
                    dest, marker.position); // in meters

            let numStep = distance / step;
            let i = 0;
            let deltaLat = (dest.lat() - lat) / numStep;
            let deltaLng = (dest.lng() - lng) / numStep;

            function moveMarker() {

                lat += deltaLat;
                lng += deltaLng;
                i += step;
                // etaUpdate(coords1, target, marker, new google.maps.LatLng(lat, lng), id, km_h);
                // etaUpdatev2(coords1, target, marker, id, km_h);


                if (i < distance) {
                    marker.setPosition(new google.maps.LatLng(lat, lng));
                    helper.timeOutVar = setTimeout(moveMarker, delay);
                }
                else {
                    marker.setPosition(dest);

                    target++;
                    if (target == (coords1.length - 1)) {
                        target = 0;

                        marker.setPosition(coords1[target]);

                    }
                        helper.timeOutVar = setTimeout(goToPoint, delay);
                }
                let car = "M97.94,111.31a.69.69,0,0,0-.52-.21,1.19,1.19,0,0,0-.44.09l-11.12,4.22a2.62,2.62,0,0,0-.72.44l.89-8.31a5.64,5.64,0,0,0,4.92-4.17h.57V15.19C91.52,5.34,88.47,0,72.36,0h0L20.55.24C15.77.24,6.61,3.18,6.61,7.8v95.57h.57a5.69,5.69,0,0,0,5.28,4.2l.91,8.6a2.41,2.41,0,0,0-1-.75L1.21,111.19a1.11,1.11,0,0,0-.43-.09.69.69,0,0,0-.52.21c-.37.4-.23,1.26-.2,1.43v.08A7.33,7.33,0,0,0,3.32,116l7.53,3.06a2.5,2.5,0,0,0,1,.19,2,2,0,0,0,1.77-.94l4.83,45.54a7.32,7.32,0,0,0,2.36,4.79c3.85,4.07,11.44,6.76,14.8,6.76l25.34-.25h3.32c2.63-.11,6.33-1.59,9.49-3.76,2.19-1.48,6-4.54,6.38-8.37l4.74-44.36a2.09,2.09,0,0,0,1.53.62,2.5,2.5,0,0,0,1-.19L94.88,116a7.33,7.33,0,0,0,3.23-3.18v-.08A1.88,1.88,0,0,0,97.94,111.31ZM86.68,18.73a1.15,1.15,0,0,1,2.29,0V58.27a1.15,1.15,0,0,1-2.29,0Zm0,48.92a1.15,1.15,0,0,1,2.29,0v31a6.16,6.16,0,0,0-2.29-1ZM11.47,97.58a6.43,6.43,0,0,0-2.28,1v-31a1.09,1.09,0,0,1,1.14-1,1.09,1.09,0,0,1,1.14,1Zm0-39.31a1.09,1.09,0,0,1-1.14,1,1.09,1.09,0,0,1-1.14-1V18.73a1.08,1.08,0,0,1,1.14-1,1.08,1.08,0,0,1,1.14,1ZM68.35,104.1c3,0,5.37,1.57,5.37,3.51s-2.4,3.52-5.37,3.52H30.12c-3,0-5.37-1.57-5.37-3.52s2.41-3.51,5.37-3.51ZM15.17,3.56c-.06,0,0-.35,0-.53a6.22,6.22,0,0,0,0-.75,22.81,22.81,0,0,1,2.49-.69l.45-.07a3,3,0,0,1,.47,0l10.21.08a1,1,0,0,1,.56.11c.11.1.11.38.11.69v.38c0,.33,0,.64-.13.76a1,1,0,0,1-.54.11H15.5C15.22,3.6,15.17,3.56,15.17,3.56ZM21.37,168a6.26,6.26,0,0,1-2-4.1h0l-.21-2.05,2.65.16a19,19,0,0,0,2.45,4.26c1.55,2.17,3.26,5,6.18,7.1A24.3,24.3,0,0,1,21.37,168Zm57.84-5c-.46,4.37-6.35,8.49-11.09,10.27,2.89-2.06,4.65-4.85,6.2-7A19,19,0,0,0,76.77,162l2.56-.15Zm-1.85-6.8a21.78,21.78,0,0,1-6.9,6.1c-5.85,3.43-13.8,5.32-21.76,5.29s-15.87-2-21.66-5.43c-4.58-2.71-7.7-6.39-8.74-10.29H19a13.53,13.53,0,0,0,2,3.95,22,22,0,0,0,6.76,5.89c5.65,3.33,13.32,5.16,21,5.15s15.38-1.73,21.08-5a22.09,22.09,0,0,0,6.8-5.86,13.64,13.64,0,0,0,2.09-4.13h.67a13.28,13.28,0,0,1-2,4.32Zm2.46-41.88L78.48,131.9l-.36.1a3.12,3.12,0,0,1-2,1.83,100.33,100.33,0,0,1-54.41-.24,3,3,0,0,1-2.12-2.45l-1.68-18.35a1.3,1.3,0,0,1,.19-.85,1.09,1.09,0,0,1,.13-.17,1.22,1.22,0,0,1,1.58-.31c10.54,6.65,19.5,9.43,29.39,9.43,9.57,0,19-2.78,28.79-9.2a1.12,1.12,0,0,1,1.57.23l0,.06a1.56,1.56,0,0,1,.3,1.06ZM82.48,3.56a.73.73,0,0,1-.33,0H68.9a1,1,0,0,1-.54-.11c-.14-.12-.13-.43-.13-.76V2.35c0-.31,0-.59.11-.69a1,1,0,0,1,.56-.11L79,1.47c.76.11,1.47.25,2.14.4.5.14,1,.29,1.34.41a6.45,6.45,0,0,0,0,.75C82.52,3.21,82.54,3.51,82.48,3.56Z";
                let icon = {
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

                icon.rotation = (google.maps.geometry.spherical.computeHeading(new google.maps.LatLng(lat, lng), new google.maps.LatLng(lat + deltaLat, lng + deltaLng))) + 180;


                marker.setIcon(icon);
            }

            moveMarker();
        }

        goToPoint();
    }


}


//Helper Functions

function padLeft(nr, n, str) {
    return Array(n - String(nr).length + 1).join(str || '0') + nr;
}

//or as a Number prototype method:
Number.prototype.padLeft = function (n, str) {
    return Array(n - String(this).length + 1).join(str || '0') + this;
}