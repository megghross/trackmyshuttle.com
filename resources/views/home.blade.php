@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        You are logged in!
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section("styles")
    <link href="{{asset("assets/vendors/linea-icons/styles.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.25/daterangepicker.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-touchspin/3.1.2/jquery.bootstrap-touchspin.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.1.7/css/ion.rangeSlider.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/css/new_style.css")}}" rel="stylesheet" type="text/css"/>
@endsection


