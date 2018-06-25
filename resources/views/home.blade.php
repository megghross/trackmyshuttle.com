@extends('layouts.arclayout')


@section('content')
<div data-poster-url="https://uploads-ssl.webflow.com/5a73d5a5c14f9b0001c2a5b8/5a7d41c518f6e3000144342b_Comp 1-poster-00001.jpg" data-video-urls="https://uploads-ssl.webflow.com/5a85fbb738ceec00011b32bf/5a85fbb738ceec00011b32da_Comp%201-transcode.webm,https://uploads-ssl.webflow.com/5a85fbb738ceec00011b32bf/5a85fbb738ceec00011b32da_Comp%201-transcode.mp4" data-autoplay="true" data-loop="true" data-wf-ignore="true" class="hero-section w-background-video w-background-video-atom"><video autoplay="" loop="" style="background-image:url(&quot;https://uploads-ssl.webflow.com/5a73d5a5c14f9b0001c2a5b8/5a7d41c518f6e3000144342b_Comp 1-poster-00001.jpg&quot;)" data-wf-ignore="true"><source src="https://uploads-ssl.webflow.com/5a85fbb738ceec00011b32bf/5a85fbb738ceec00011b32da_Comp%201-transcode.webm" data-wf-ignore="true"><source src="https://uploads-ssl.webflow.com/5a85fbb738ceec00011b32bf/5a85fbb738ceec00011b32da_Comp%201-transcode.mp4" data-wf-ignore="true"></video>
    <div class="heroheadfor-cell w-hidden-main w-hidden-medium w-hidden-small">
        <h1 class="hero-heading">Get Customers <span class="text-span-5">Faster</span>, <span class="text-span-6">Safer</span> &amp; <span class="text-span-7">Happier</span></h1>
    </div>
    <div class="herohead w-hidden-tiny">
        <h1 class="hero-heading">Get Customers</h1>
        <div class="html-embed w-embed"><span id="typed"></span></div>
    </div>
    <div class="container-2 w-container">
        <p class="paragraph">An automated solution for shuttle tracking, route planning and customer arrival information.</p>
    </div>
    <div class="container-2 w-container"><a href="{{route('register')}}" class="button-3 w-button">GET STARTED</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="features" class="button-3 w-button">VIEW FEATURES</a></div>
</div>
<div class="summary-section w-hidden-tiny">
    <div class="w-container">
        <h1 class="heading-3">Grow Business &amp; Loyalty</h1>
        <h4 class="heading-3">Boost CLV | Enhance Brand Reputation | Enable Profitable Growth</h4>
    </div>
</div>
<div class="section-125">
    <div class="row-2 w-row">
        <div class="column-2 w-col w-col-9">
            <div class="text-block-7 w-hidden-small w-hidden-tiny"><span class="text-span-4"></span> 2018 Skylark Innovations LLC - All Rights Reserved</div>
            <div class="text-block-8"><a class="link-6">Legal</a></div>
        </div>
        <div class="w-col w-col-3">
            <h1 class="heading-15-copy">            </h1>
        </div>
    </div>
</div>

@endsection


@section('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js" type="text/javascript"></script>
<script src="{{asset("arc/js/tmsreal2fae42359873429834-900043727463e.js")}}" type="text/javascript"></script>
<!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->
<script>
    var options = {
        strings: [" Faster", " Safer"," Happier"],
        typeSpeed: 100,
        backSpeed: 50,
        cursorChar: "|",
        cursorBlinking: true,
        loop: true,
    }
    var typed = new Typed("#typed", options);
</script>

@endsection