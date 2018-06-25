<!DOCTYPE html>
<!--  Last Published: Sun Feb 18 2018 03:16:51 GMT+0000 (UTC)  -->
<html data-wf-page="5a85fbb738ceec00011b32c6" data-wf-site="5a85fbb738ceec00011b32bf">
<head>
    <meta charset="utf-8">
    <title>{{ isset($title)?$title:config('app.name', 'Track My Shuttle') }}</title>
    {{--<meta content="register" property="og:title">--}}
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link href="{{asset("arc/css/normalize.css")}}" rel="stylesheet" type="text/css">
    <link href="{{asset("arc/css/components.css")}}" rel="stylesheet" type="text/css">
    <link href="{{asset("arc/css/tmsreal2fae42359873429834-900043727463e.css")}}" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js" type="text/javascript"></script>
    <script type="text/javascript">WebFont.load({  google: {    families: ["Montserrat:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic","Varela Round:400","Exo:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic","Catamaran:100,200,300,regular,500,600,700,800,900","Mukta Mahee:200,300,regular,500,600,700,800"]  }});</script>
    <!-- [if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" type="text/javascript"></script><![endif] -->
    <script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
    <link href="{{asset("arc/images/S-Square32.png")}}" rel="shortcut icon" type="image/x-icon">
    <link href="{{asset("arc/images/S-Square256.png")}}" rel="apple-touch-icon">
    <script src="{{asset("arc/js/typed.js")}}"></script>
</head>


<body>
<div data-collapse="small" data-animation="default" data-duration="400" class="navbar w-nav">
    <a href="{{route("home")}}" class="w-nav-brand">
        <img src="{{asset("arc/images/logo-v2-light.png")}}" width="257" srcset="{{asset("arc/images/logo-v2-light-p-500.png")}} 500w, {{asset("arc/images/logo-v2-light.png")}} 514w" sizes="(max-width: 479px) 100vw, 257px" class="image w-hidden-tiny">
        <img src="{{asset("arc/images/S-Square570.png")}}" height="35" srcset="{{asset("arc/images/S-Square570-p-500.png")}} 500w, {{asset("arc/images/S-Square570.png")}} 570w" sizes="(max-width: 479px) 34.9375px, 100vw" class="image w-hidden-main w-hidden-medium w-hidden-small">
    </a>
    <nav role="navigation" class="nav-menu w-nav-menu"><a href="{{route('login')}}" class="button_login w-button">LOG IN</a></nav>
    <div class="menu-button w-nav-button">
        <div class="icon w-icon-nav-menu"></div>
    </div>
</div>

@yield("content")



@yield("scripts")





</body>
</html>