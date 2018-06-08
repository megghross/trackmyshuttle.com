
class MapHelper {
    constructor(Route) {
        this.Route = Route;
        this.time = new Date();
        this.time.setSeconds(this.time.getSeconds() - 10);
        this.offRouteMessage = 'The vehicle is off route.';

    }

    get PolyLineArray() {
        return this.GetFullPolyLineArray();
    }

    get RouteArray() {
        return this.routeArray;
    }

    GetFullPolyLineArray(){
        let legs = this.Route.legs;
        let legIndex = 0;
        let polyArray = [];
        var routeArray = [];
        legs.forEach(function(leg){
            let steps = leg.steps;
            let stepIndex = 0;
            routeArray.push([]);
            steps.forEach(function(step){
                let temp = step.path;
                routeArray[legIndex].push([]);
                routeArray[legIndex][stepIndex] = temp;
                let pointIndex = 0;
                temp.forEach(function(item){
                    item.stepIndex = stepIndex;
                    item.legIndex = legIndex;
                    item.pointIndex = pointIndex;
                    pointIndex++;
                });
                polyArray = polyArray.concat(temp);
                stepIndex++;

            });
            legIndex++;
        });
        this.routeArray = routeArray;
        return polyArray;
    };



    NearestPointOfRoute(marker)
    {
        let lat = marker.position.lat();
        let lng = marker.position.lng();
        if (this.Route == null) {
            return false;
        }
        let minDistance = 99999999;
        let nearestMark = {};
        let legIndex = 0;
        let stepIndex = 0;
        let pointIndex = 0;
        this.Route.legs.forEach(function (leg){
            stepIndex = 0;
            leg.steps.forEach(function(step){
                let latlngArray = step.path;
                pointIndex = 0;
                latlngArray.forEach(function(latlng){
                    let curDistance = MapHelper.FindDistance(latlng.lat(), latlng.lng(), lat, lng, 'meters');
                    if (curDistance < minDistance) {
                        minDistance = curDistance;
                        nearestMark.distance = minDistance;
                        nearestMark.unit = 'meters';
                        nearestMark.stepIndex = stepIndex;
                        nearestMark.legIndex = legIndex;
                        nearestMark.pointIndex = pointIndex;
                    }
                    pointIndex++;
                });
                stepIndex++;
            });
            legIndex++;
        });


        if(marker.customInfo.type=='End'){
            nearestMark.unit = 'meters';
            nearestMark.stepIndex = stepIndex-1;
            nearestMark.legIndex = legIndex-1;
            nearestMark.pointIndex = pointIndex-1;
        }
        else if(marker.customInfo.type=='Start'){
            nearestMark.unit = 'meters';
            nearestMark.stepIndex = 0;
            nearestMark.legIndex = 0;
            nearestMark.pointIndex = 0;
        }


        return nearestMark;
    };


    DetectOffRoute(marker){
        let now = new Date();
        let seconds = (now.getTime() - this.time.getTime()) / 1000;

        if(seconds>5){
            let nearestRoutePoint = this.NearestPointOfRoute(marker);

            if(nearestRoutePoint.distance>40){
                this.time = new Date();
                this.RequestToServerForDetail(marker);
                return this.offRouteMessage;
            }

            return "Vehicle is on its assigned route";
        }

    }



    RequestToServerForDetail(marker){
        let msg = 'Off Route';
        let mapHelper = this;
        $.ajax({
           url: $("body").data("offroute"),
            "method": "POST",
            data:{
                "serialNumber":marker.customInfo.serialNo,
                "lat" : marker.position.lat(),
                "long": marker.position.lng()
            },
            success: function(data){
                // data = JSON.parse(data);
                let now = new Date();
                mapHelper.offRouteMessage = "Message:"+ data.msg +
                    "<br> Probability of Fueling : " + data.probablity  +  "%.</br> Distance from Route:"
                    +Math.round(data.routeDistance)+ " meters. </br>Distance from Gas Station:"
                    +Math.round(data.gas_stationDistance)+" meters.</br>Synced Time : "+now.getHours()+":"+now.getMinutes()+":"+now.getSeconds();


            }

        });

    }



    CalculateETA(markerPoint, stopPoint, km_h){
        let markerLegIndex = markerPoint.legIndex;
        let markerStepIndex = markerPoint.stepIndex;
        let markerPointIndex = markerPoint.pointIndex;

        let compareMarkerIndexes = padLeft(markerLegIndex, 4)+""+padLeft(markerStepIndex, 4)+""+padLeft(markerPointIndex, 4)

        let stopLegIndex = stopPoint.legIndex;
        let stopStepIndex = stopPoint.stepIndex;
        let stopPointIndex = stopPoint.pointIndex;
        let compareStopIndexes = padLeft(stopLegIndex, 4)+""+padLeft(stopStepIndex, 4)+""+padLeft(stopPointIndex, 4)

        if(compareMarkerIndexes<=compareStopIndexes){
                    let route = this.Route;
                    var time = 0;
                    var distance = 0;
                    for(let li = markerLegIndex; li<=stopLegIndex; li++){

                        if(markerLegIndex==li){
                            for(let si= markerStepIndex; si<route.legs[li].steps.length; si++){
                                time = time + route.legs[li].steps[si].duration.value;
                                distance = distance + route.legs[li].steps[si].distance.value;
                                if(si==markerStepIndex && li==markerLegIndex){
                                    distance -= MapHelper.FindDistance(route.legs[li].steps[si].path[0].lat(), route.legs[li].steps[si].path[0].lng(), route.legs[li].steps[si].path[markerPointIndex].lat(), route.legs[li].steps[si].path[markerPointIndex].lng())
                                }
                            }
                        }
                        else if(markerLegIndex==li && stopLegIndex==li){
                            for(let si= markerStepIndex; si<=stopStepIndex; si++){
                                time = time + route.legs[li].steps[si].duration.value;
                                distance = distance + route.legs[li].steps[si].distance.value;
                                if(si==markerStepIndex && li==markerLegIndex){
                                    distance -= MapHelper.FindDistance(route.legs[li].steps[si].path[0].lat(), route.legs[li].steps[si].path[0].lng(), route.legs[li].steps[si].path[markerPointIndex].lat(), route.legs[li].steps[si].path[markerPointIndex].lng())
                                }
                            }
                        }
                        else{
                            for(let si= 0; si<route.legs[li].steps.length; si++){
                                time = time + route.legs[li].steps[si].duration.value;
                                distance = distance + route.legs[li].steps[si].distance.value;
                                if(si==markerStepIndex && li==markerLegIndex){
                                    distance -= MapHelper.FindDistance(route.legs[li].steps[si].path[0].lat(), route.legs[li].steps[si].path[0].lng(), route.legs[li].steps[si].path[markerPointIndex].lat(), route.legs[li].steps[si].path[markerPointIndex].lng())
                                }
                            }
                        }

                    }
                    var timeinmilliseconds = ((distance / 1000) / km_h) * 60 * 60 * 1000;
                    var time = this.getTime(timeinmilliseconds);
                    return time;
                }


        return 'Trip Completed!';







    }

    getTime(timeInMiliseconds) {
        var mint = parseInt((timeInMiliseconds / 1000) / 60);
        if (mint == 0) {
            var seconds = parseInt((timeInMiliseconds / 1000) % 60);
            return seconds + " sec";

        }
        return mint + " mins";
    }

    static FindDistance(lat1, lon1, lat2, lon2, unit = "K"){
        let dest = new google.maps.LatLng(lat1, lon1);
        let fromDest = new google.maps.LatLng(lat2, lon2);
        return google.maps.geometry.spherical.computeDistanceBetween(dest, fromDest);
    };
}

function padLeft(nr, n, str){
    return Array(n-String(nr).length+1).join(str||'0')+nr;
}
//or as a Number prototype method:
Number.prototype.padLeft = function (n,str){
    return Array(n-String(this).length+1).join(str||'0')+this;
}