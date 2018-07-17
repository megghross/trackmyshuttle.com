<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon.png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title)?$title:config('app.name', 'Track My Shuttle') }}</title>


    <!--Common CSS--->
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <link href="{{asset("assets/vendors/fontawesome-icons/fa-light.css")}}" rel="stylesheet">
    <link href="{{asset("assets/vendors/fontawesome-icons/fa-regular.css")}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset("assets/css/pace.css")}}" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,400,500,600|Roboto:400" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/vendors/material-icons/material-icons.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/vendors/linea-icons/styles.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/vendors/mono-social-icons/monosocialiconsfont.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/vendors/feather-icons/feather.css")}}" rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.7.0/css/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>

    <!--Common CSS end here--->

    <!---Page Specific CSS---->
    @yield("styles")
    <!---Page Specific CSS END HERE---->

    <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/new_style.css" rel="stylesheet" type="text/css"/>

<!-- Head Libs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <script data-pace-options='{ "ajax": false, "selectors": [ "img" ]}' src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>

</head>


<body class="{{isset($bodyClass)?$bodyClass:"header-dark sidebar-light sidebar-expand pace-done"}}">


@include('layouts.nav')
<div class="content-wrapper">
    @include('layouts.sidebar')
</div>

@yield("content")
<!-- Scripts -->




<!----Common Scripts for all pages----->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.2/umd/popper.min.js"></script>
<script src="{{asset("assets/js/bootstrap.min.js")}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.0/metisMenu.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.7.0/js/perfect-scrollbar.jquery.js"></script>


<!----Common Scripts for all pages END HERE----->

<!---Scripts for View--->
@yield("scripts")




<link href="assets/vendors/fontawesome-icons/fa-light.css" rel="stylesheet">
<link href="assets/vendors/fontawesome-icons/fa-regular.css" rel="stylesheet">


</body>
</html>