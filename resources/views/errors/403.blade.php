@extends('layouts.pagelayout', ["bodyClass"=>"body-bg-full error-page error-403", "title"=>"Error 403"])

@section('content')
    <div id="wrapper" class="wrapper">
        <div class="content-wrapper">

            <main class="main-wrapper">
                <div class="page-title">
                    <h1 class="color-white">403</h1>
                </div>

                <h3 class="mr-b-5 color-white">Access Denied!</h3>
                <p class="mr-b-30 color-white fs-18 fw-200 heading-font-family">
                    You don't have permission to access this server.
                </p>
                <a href="javascript: history.back();" class="btn btn-outline-white btn-rounded btn-block fw-700 text-uppercase">Go Back</a>
            </main>

        </div><!-- .content-wrapper -->
    </div><!-- .wrapper -->
@endsection
