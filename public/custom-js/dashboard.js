$(document).ready(function () {


    var red = '#FC754F';
    var interval = [];
    var num = [];
    var light_blue = '#4990E2';
    var deep_blue = '#235c9e';
    var light_red = '#e6614f';
    var instance = [];
    var currentIndex = 0;
    var length;
    var routesHtml = '';
    var driversHtml = '';
    var devices = [];

    function loadRoutes() {
        $.ajax({
            url: 'api/dashboard/load',
            success: function (data) {

                htmlArray = [];
                htmlArray.push('<div class="routesArray">');
                for (i = 0; i < data.length; i++) {
                    htmlArray.push('<a id="' + data[i].id + '"><div class="wrapRouteIcon"><center class="round_Image"><img src="assets/img/route.png" alt="Routes"></center><label style="font-size: 12px;">' + data[i].name + '</label></div></a>');
                }
                htmlArray.push('<a href="routes.php"><i class="fal fa-plus-circle fa-6x route_size2"></i></a>');
                htmlArray.push('</div>');
                routesHtml = htmlArray.join(" ");
                loadDrivers();

            }
        });
    }


    function loadDrivers(){
        $.ajax({
            url: '/api/dashboard/loaddrivers',
            success: function (data) {

                htmlArray = [];

                htmlArray.push('<h5 style="margin-top: 20px !important;">Pick Driver:</h5>');
                // htmlArray.push('<h8>OR Create a new one</h8>\n');
                htmlArray.push('<select id="dropdown" class="form-control driversDrop">');
                htmlArray.push('<option value="0">Please Select</option>');
                for (i = 0; i < data.length; i++) {
                    htmlArray.push('<option value=\''+data[i].id+'\'>'+data[i].firstName +" " + data[i].lastName+'</option>');
                }
                // htmlArray.push('<a href="routes.php"><i class="fal fa-plus-circle fa-6x route_size2"></i></a>');
                htmlArray.push('</select>');
                driversHtml = htmlArray.join(" ");
                ajaxRequestToLoad();

            }
        });
    }

    loadRoutes();

    function ajaxRequestToLoad() {

        $.ajax({
            url: '/api/dashboard/getdashboarddata',

            success: function (data) {

                if (data.status) {
                    devices = data.devices;
                    loadDevices(devices);
                    length = data.devices.length;
                }
                else {
                    devices = [
                        {
                            name: 'Device-1',
                            serial_number: '81A345675'
                        }, {
                            name: 'Device-2',
                            serial_number: '81A345676'
                        }, {
                            name: 'Device-3',
                            serial_number: '81A345677'
                        }, {
                            name: 'Device-4',
                            serial_number: '81A345678'
                        }, {
                            name: 'Device-5',
                            serial_number: '81A345679'
                        }];
                    loadDevices(devices);
                    length = devices.length;
                }
                Custom.init();

            }
        });

    }

    function loadDevices(devices) {


        $(".nav-item.device-item").html("");
        for (var i = 0; i < devices.length; i++) {

            var htmlStr = [
                '<a href="#' + devices[i].name + '" id="dev' + (i + 1) + '" class="new_dev nav-link" data-toggle="tab" aria-expanded="true">',
                '<div class="col-sm-12">',
                '<div class="media" style="min-height: 100px;">',
                '<figure class="thumb-xs2 mr-4 mr-0-rtl ml-4-rtl new-figure">',
                '<i class="feather feather-cpu mega new-mega"></i>',
                '</figure>',
                '<div class="media-body">',
                '<ul class="list-unstyled list-inline text-muted fs-13 mb-3">',
                '<li class="list-inline-item mr-4 mr-0-rtl ml-4-rtl cereal new-serial">Serial <i class="feather feather-hash fs-base mr-1"></i>' + devices[i].serial_number + '</li>',
                '</ul>',
                '</div>',
                '<div class="new-chart">',
                '<div class="col-sm-12" id="knob' + devices[i].name + '">',
                '<input class="dial" data-plugin="knob" data-width="100" data-height="100" data-angleOffset="100" data-linecap="round" data-fgColor="#4990E2" value="100"  id="input_percent" name="input_percent"/>',
                '</div>',
                '</div>',
                '</div>',
                '</div>',
                '</a>'
            ].join('');
            $(".nav-item.device-item").append(htmlStr);

            var htmlstr11 = [
                '<div class="tab-content" style="display: none; width: 40%;float: left;padding: 0 !important; padding-top: 1.4em !important; background-color: white;">\n' +

                '<div role="tabpanel" class="tab-pane" id="' + devices[i].name + '">',
                '<ul id="rightside-menuu" class="nav nav-tabs contact-details-tab width-100">\n' +
                '<li class="nav-item width-100">',
                '<a href="#' + devices[i].name + '_1" id="plugin" class="nav-link width-100 active" data-toggle="tab" style="padding-right: 0px;"><span class="righty"><i class="far fa-check-circle plugin_tab new-tap"></i><p class="new-tap">Plug-In</p></span></a>\n',
                '</li>\n',
                '<li class="nav-item width-100">\n',
                '<a href="#' + devices[i].name + '_2" id="assign_shuttle"  class="nav-link width-100" style="padding-right: 0px;" data-toggle="tab"><span class="righty"><i class="far fa-check-circle plugin_tab new-tap"></i><p class="new-tap">Assign Shuttle</p></span></a>\n',
                '</li>\n',
                '<li class="nav-item width-100">\n',
                ' <a href="#' + devices[i].name + '_3" id="assign_route"  class="nav-link width-100" style="padding-right: 0px;" data-toggle="tab"><span class="righty"><i class="far fa-check-circle plugin_tab new-tap"></i><p class="new-tap">Assign Route</p></span></a>\n',
                '</li>\n',
                '</ul>\n',
                '</div>\n',
                '</div>\n',
                '<div class="tab-content" style="width: 240px; float: left;">',
                '<div role="tabpanel" class="tab-pane " id="' + devices[i].name + '_1">\n',
                '<h5 style="margin: 0 0 !important;">Status:</h5>\n',
                '<input type="hidden" value="' + devices[i].name + '">\n',
                '<div class="plugin">\n',
                '<div class="plugin_back">\n',
                '<h3 class="plugin_txt">CONNECTED</h3>\n',
                '</div>\n',
                '<div class="plugin_circle"><i class="fas fa-plug plugin_rotate"></i>',
                '</div>',
                '</div>',
                '<h8>Congratulations!You\'ve just completed the hardest task.</h8>',
                '</div>',
                '<div role="tabpanel" class="tab-pane" id="' + devices[i].name + '_2">',
                '<h5 style="margin: 0 0 !important;">Name Your Shuttle:</h5>',
                '<h8>Example Back Ford Transit</h8>',
                '<input type="hidden" value="device1">',
                '<div class="personal">',
                '<div class="personal_back">',
                '<input type="text" id="shuttleName' + devices[i].id + '" class="personal_txt customInput" value="' + devices[i].shuttleName + '"/>',
                '</div>',
                '<div class="personal_circle"><a id="' + devices[i].id + '" class="saveShuttleName"><i class="fas fa-save personal_size"></i></a>\n' +
                '</div>',
                '</div>',
                '<h8 style="margin-top: 20px;float: left;">Add more vehicle details</h8>\n',
                '</div>',
                '<div role="tabpanel" class="tab-pane" id="' + devices[i].name + '_3">',
                '<h5 style="margin: 0 0 !important;">Pick a Route:</h5>',
                '<h8>OR Create a new one</h8>\n' ,
                '<input type="hidden" value="device1">',
                '<div id="routeSection' + devices[i].id + '" class="route">',
                routesHtml,
                driversHtml,

                ' </div>',
                ' <h8 style="margin-top: 200px; display: block;">Add more vehicle details</h8>',
                ' </div>',
                '</div>'
            ].join('');
            $("#tabPanesForDevices").append(htmlstr11);
            var target = $('#routeSection' + devices[i].id);

            var childs = target.children().eq(0).children();
            for (j = 0; j < childs.length; j++) {
                var child = $(childs[j]);
                if (child[0].id == devices[i].routeId) {
                    child.children().eq(0).children().eq(0).addClass("imageActive");
                }
            }


            //Select the Selected Driver
            var dropDown = target.children().eq(2);
            dropDown.val(devices[i].driverId+"");

        }
        $(".driversDrop").on('change', function(event){
            $(".loader").css('display', 'block');
            var driverId = this.value;
            var deviceId = $(this).parent()[0].id.replace("routeSection", "");
            $.ajax({
                url: "api.php?action=assignDriverToDevice",
                method: "POST",
                data:{
                    driverId: driverId,
                    deviceId: deviceId
                },
                success: function (data) {
                    $(".loader").css('display', 'none');
                    location.reload();
                }
            });
        });
        $(".routesArray a").on('click', function (event) {
            $(".loader").css('display', 'block');
            var id = this.id;
            var deviceId = $(this).parent().parent()[0].id.replace("routeSection", "");
            var thisElement = this;
            $.ajax({
                url: "api.php?action=assignRouteToDriver",
                method: "POST",
                data: {
                    routeId: id,
                    deviceId: deviceId
                },
                success: function (data) {


                    for (i = 0; i < devices.length; i++) {
                        var target = $('#routeSection' + devices[i].id);

                        var childs = target.children().eq(0).children();

                        for (j = 0; j < childs.length; j++) {
                            var child = $(childs[j]);
                            child.children().eq(0).children().eq(0).removeClass("imageActive");
                        }
                    }
                    $(thisElement).children().eq(0).children().eq(0).addClass("imageActive");
                    $(".loader").css('display', 'none');
                    location.reload();

                }
            });

        });
        $('.nav-item.device-item a').on('click', function (event) {
            for (i = 1; i <= length; i++) {
                $("#device" + i).removeClass('active');
                $("#device" + i).parent().css('display', 'none');
                for (k = 1; k <= 3; k++) {
                    $("#device" + i + "_" + k).removeClass('active');
                    $("#device" + i + "_" + k).parent().css('display', 'none');
                }
            }
            for (i = 1; i <= length; i++) {
                $("#dev" + i).removeClass('active');

            }
            // $('.assign_shuttle').each(function(){
            //     $(this).removeClass('active');
            // });
            // $('.plugin').each(function(){
            //     $(this).removeClass('active');
            // });
            // $('.assign_route').each(function(){
            //     $(this).removeClass('active');
            // });
            var href = $(this).attr('href');

            var childs = $(href).children().eq(0).children();
            for(m=0; m<childs.length; m++){
                var child = $(childs[m]).children().eq(0);
                child.removeClass("active");
            }
            $(childs[0]).children().eq(0).addClass("active");
            $(href).addClass('active');

            $(href).parent().css('display', 'block');
            $(href + "_1").addClass('active');
            $(href + "_1").parent().css('display', 'block');
        });


        $(".saveShuttleName").click(function () {
            $(".loader").css('display', 'block');
            var name = $('#shuttleName' + this.id).val();
            var id = this.id;
            $.ajax({
                url: "api.php?action=updateShuttleName",
                method: "POST",
                data: {
                    id: id,
                    name: name
                },
                success: function (data) {
                    console.log("Shuttle Name updated");
                    $(".loader").css('display', 'none');
                    location.reload();

                },
                error: function (data) {

                }
            })
        });
        $('#dev1').click();


        $(".plugin").click(function () {
            pluginClicked(this);
        });
    }


    function pluginClicked(element) {
        // click plug-in

        var str = $(element).children().eq(0).children().eq(0).html();
        if (str == 'CONNECTED') {
            $(element).children().eq(0).css('border-color', red);
            $(element).children().eq(0).children().eq(0).css('color', red);
            $(element).children().eq(0).children().eq(0).html('NOT CONNECTED');
            $(element).children().eq(1).css('border-color', red);
            $(element).children().eq(1).children().eq(0).css('color', red);
            $(element).next().html("Plugin tracking device into the shuttle's OBDII Port. Can't find OBDII Port?");
            var ss = $(element).parent().children().eq(1).val();

            sss = ss.substring(6, 7);
            var ff = sss;
            sss = "#dev" + sss; // cpu
            $(sss).children().eq(0).css('color', red);

            ss = '#' + ss;
            $(ss).children().eq(0).children().eq(0).children().eq(0).children().eq(0).children().eq(0).removeClass('fa-check-circle');
            $(ss).children().eq(0).children().eq(0).children().eq(0).children().eq(0).children().eq(0).addClass('fa-circle');


            currentIndex = ff;
            instance[ff] = $('#knobdevice' + ff + ' .dial');
            if (interval[ff] != null)
                clearInterval(interval[ff]);

            instance[ff].val(100).trigger('change');
            num[ff] = -1;
            interval[ff] = setInterval(increateval, 50);


        } else {
            $(element).children().eq(0).css('border-color', light_blue);
            $(element).children().eq(0).children().eq(0).css('color', light_blue);
            $(element).children().eq(0).children().eq(0).html('CONNECTED');
            $(element).children().eq(1).css('border-color', light_blue);
            $(element).children().eq(1).children().eq(0).css('color', light_blue);
            $(element).next().html("Congratulations!You've just completed the hardest task.");
            var ss = $(element).parent().children().eq(1).val();

            sss = ss.substring(6, 7);
            var ff = sss;
            sss = "#dev" + sss;
            $(sss).children().eq(0).css('color', 'grey');

            ss = '#' + ss;
            $(ss).children().eq(0).children().eq(0).children().eq(0).children().eq(0).children().eq(0).removeClass('fa-circle');
            $(ss).children().eq(0).children().eq(0).children().eq(0).children().eq(0).children().eq(0).addClass('fa-check-circle');


            currentIndex = ff;
            instance[ff] = $('#knobdevice' + ff + ' .dial');
            if (interval[ff] != null)
                clearInterval(interval[ff]);

            instance[ff].val(0).trigger('change');
            num[ff] = 1;
            interval[ff] = setInterval(increateval, 50);
        }
        // end of click plug-in
    }

    function increateval() {
        var num1 = num[currentIndex];
        var instance1 = instance[currentIndex];
        var val = parseInt(instance1.val());
        val = val + num1;
        instance1.val(val).trigger('change');
        if (val >= 75) {
            instance1.trigger('configure', {"fgColor": light_blue});
            instance1.css({color: light_blue});
            instance1.parent().parent().parent().children().eq(1).css({color: light_blue});
        } else if (val >= 50) {
            instance1.trigger('configure', {"fgColor": "orange"});
            instance1.css({color: 'orange'});
            instance1.parent().parent().parent().children().eq(1).css({color: 'orange'});
        } else {
            instance1.trigger('configure', {"fgColor": light_red});
            instance1.css({color: light_red});
            instance1.parent().parent().parent().children().eq(1).css({color: light_red});
        }
        if (val == 100 || val == 0)
            clearInterval(interval[currentIndex]);
        instance1.css('margin-left', '-77px');
        instance1.css('margin-top', '33px');
        instance1.css('font-size', '20px');
    }
//The right side widget JS ends here








    //Bottom Side Widget Start from here



    var laneRecords = [];
    $.ajax({
        url: "api.php?action=getTrackLaneData",
        method: "GET",
        success: function(data){
            laneRecords = data;
            loadLaneTrack();
        }
    });






    function loadLaneTrack(){

        for(i=0; i<laneRecords.length; i++){
            laneTrack = laneRecords[i];

            debugger;
            if(laneTrack.shuttleName!="N/A"){
                drawLaneTrack(laneTrack);

            }
            else{

            }
        }

    }


    function drawLaneTrack(laneTrack){
        var htmlArray = [
            '<div class="job-single row border-bottom pb-4 hover-parent tracking-dash">\n' ,
            '   <div class="col-sm-12">\n' ,
            '       <div class="media">\n' ,
            '           <figure class="thumb-xs2 mr-4 mr-0-rtl ml-4-rtl">\n' ,
            '               <i class="material-icons list-icon hotel-bigga">business</i>\n' ,
            '           </figure>\n' ,
            '       <div class="media-body">\n',
            '           <div id="timeline">\n' ,
            '              '+getStopPoints(laneTrack) ,
            '               <div id="bus'+laneTrack.id+'">\n' ,
            '                   <span>\n' ,
            '                       <img id="bus-logo" class="svg route-bus-dash-red" src="assets/img/bus-b.svg"/>\n',
            '                   </span>\n',
            '               </div>\n',
            '               <div class="inside " id="line'+laneTrack.id+'"></div>\n',
            '           </div>\n',
            '           <ul class="list-unstyled list-inline text-muted fs-13 mb-3 blues" style="padding-top: 17px;">' +
            '               <li class="list-inline-item mr-4 mr-0-rtl ml-4-rtl"><b>Shuttle<b> Name: '+laneTrack.shuttleName+'</li>' ,
            '               <li class="list-inline-item mr-4 mr-0-rtl ml-4-rtl"><b>Route</b> Name: '+laneTrack.name+'</li>' ,
           '               <li class="list-inline-item mr-4 mr-0-rtl ml-4-rtl"><b>Duration</b> <i class="feather feather-clock fs-base mr-1"></i></i>'+laneTrack.duration+'</li>' ,
            // '               <li class="list-inline-item mr-4 mr-0-rtl ml-4-rtl"><b>Status</b> <i class="feather feather-zap fs-base mr-1"></i>On time</li>' ,
            '           </ul>',
            '                                </div><!-- /.media-body -->\n',
            '                                <figure class="thumb-xs2 mr-4 mr-0-rtl ml-4 end-route">\n',
            '                                    <i class="material-icons list-icon hotel-bigga">business</i>\n',
            '                                </figure>\n',
            '                            </div><!-- /.media -->\n',
            '                        </div><!-- /.col-sm-10 -->\n',
            '                    </div><!-- /.job-single -->\n'

        ];


        $('.featured-jobs').append(htmlArray.join(" "));

        animateBus(laneTrack);

    }
    function getStopPoints(laneTrack){

        var stops = laneTrack.stop;
        var htmlArray = [];
        debugger;
        for(k=0; k<stops.length; k++){
            var stop = stops[k];
            htmlArray.push(['<div class="dot purp-empty" id="stop'+laneTrack.id+''+stop.id+'">\n' ,
                '                   <span></span>' ,
                '                   <date class="greener" id="date'+laneTrack.id+''+stop.id+'">Loading</date>\n' ,
                '<div class="greener" id="stopName'+laneTrack.id+''+stop.id+'">Loading</div>',
                '               </div>'].join(" "));
        }


        return htmlArray.join(" ");

    }
    var percent = [];

    function animateBus(laneTrack){
        var bus = $('div#timeline #bus'+laneTrack.id);
        var progress = $('div#timeline #line'+laneTrack.id);

debugger;
        progress.css('background-color', laneTrack.color);






        var delay = ((laneTrack.length/100)/60)*60*60*1000;

        percent[laneTrack.id] =0;
        function moveObject(){
            bus.css('position', 'relative');
            bus.css('left', percent[laneTrack.id]+'%');
            progress.css('width', (percent[laneTrack.id]-1)+'%');
            if(percent[laneTrack.id]>100){
                percent[laneTrack.id] = 0 ;
            }
            percent[laneTrack.id]++;

            //Make stop point full or empty
            var stops = laneTrack.stop;

            for(k=0; k<stops.length; k++){
                var stop = stops[k];
                var test = $('div#timeline #stop'+laneTrack.id+''+stop.id);
debugger;

                var stopPercent = test.get(0).style.left.replace("%", "");
                if(stopPercent!=""){

                if(percent[laneTrack.id]>stopPercent){
                    test.removeClass('purp-empty');
                    test.addClass('purp-full');
                }
                else{
                    test.removeClass('purp-full');
                    test.addClass('purp-empty');
                }

                    var steps = stopPercent - percent[laneTrack.id];
                    var etaMilliSeconds = steps * delay;
                    var time = getTime(Math.abs(etaMilliSeconds));
                    var etaElem = $('div#timeline #date'+laneTrack.id+''+stop.id);

                    if(etaMilliSeconds<0){
                        // etaElem.html("COMPLETED · "+ time+" ago");
                        etaElem.html(time+" ago");
                    }
                    else{
                        // etaElem.html("STOP "+(k+1)+" · ETA "+ time);
                        etaElem.html("ETA "+ time);
                    }

                }

            }





                setTimeout(moveObject, delay);
        }
        moveObject();


        var stops = laneTrack.stop;
        var htmlArray = [];

        for(k=0; k<stops.length; k++){
            var stop = stops[k];
            var test = $('div#timeline #stop'+laneTrack.id+''+stop.id);

            var stopName = $('div#timeline #stopName'+laneTrack.id+''+stop.id);

            debugger;
            stopName.html(stop.name);
            test.css('left', stop.stopPercentage+'%');
            test.children().eq(0).css('background-color', laneTrack.color);
            test.children().eq(0).css('border-color', laneTrack.color);
        }




    }

    function getRandomNumber(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
    function getTime(timeInMiliseconds) {
        var mint = parseInt((timeInMiliseconds / 1000) / 60);
        if (mint == 0) {
            var seconds = parseInt((timeInMiliseconds / 1000) % 60);
            return seconds + "s";

        }
        return mint + "m";

    }




//Document ready method end here
});
