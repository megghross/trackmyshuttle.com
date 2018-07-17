var map;
var liveTracker;
var currentShuttle = null;

let URL = $("body").data("url");
let fetchRouteUrl = $("body").data("fetchRouteUrl");
let BaseURL = $("body").data("baseurl") + '/';

$(document).ready(function () {
    initMap();
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

    loadMapAndApplication();
}


function loadMapAndApplication() {
    liveTracker = new LiveTrackingHelper(map);
    eventHandlers();

}


function eventHandlers(){
    $(document).on('click', '.route-box', function (event) {
        let routeIndex = $(this).data('routeindex');
        let shuttleIndex = $(this).data('shuttleindex');
        if(currentShuttle!=null){
            RemoveSubscription(currentShuttle);
        }
        currentShuttle = liveTracker.Route(routeIndex).Shuttle(shuttleIndex);
        currentShuttle.ShowOnMap(map);
        SubscribeLocationUpdateChannel(currentShuttle);
    });
}



function SubscribeLocationUpdateChannel(shuttleObj){
    let shuttle=  shuttleObj.Shuttle;
    console.log('Channel subscribing to location.'+shuttle.serialNumber+'.');
    Echo.private('location.'+shuttle.serialNumber)
        .listen('LocationUpdate', (e) => {
            console.log("Location Update from Server");
            console.log(e);
            liveTracker.hideRoute(e.currentRoute);
            liveTracker.showRoute(e.currentRoute);
            currentShuttle.setPosition(e.lat, e.long);
            currentShuttle.startAnimation(liveTracker, e.currentPoint, e.predictedPoint);
        });
}


function RemoveSubscription(shuttleObj){
    let shuttle=  shuttleObj.Shuttle;
    console.log('Unsubscribed Channel => location.'+shuttle.serialNumber+'.');
    Echo.leave('location.'+shuttle.serialNumber);
}