<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon.png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ isset($title)?$title:config('app.name', 'Track My Shuttle') }}</title>


    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,400,500,600|Roboto:400" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/vendors/material-icons/material-icons.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/vendors/mono-social-icons/monosocialiconsfont.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/vendors/feather-icons/feather.css")}}" rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.7.0/css/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/css/style.css")}}" rel="stylesheet" type="text/css"/>


    @yield("styles")

    <!-- Head Libs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>

</head>


<body class="{{isset($bodyClass)?$bodyClass:"header-dark sidebar-light sidebar-expand pace-done"}}" >



@include('layouts.nav')



@yield("content")
<!-- Scripts -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/js/material-design.js"></script>




 </body>
</html>