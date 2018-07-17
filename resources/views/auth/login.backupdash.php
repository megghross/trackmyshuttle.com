@extends('layouts.arclayout', ["title"=> "Login | Track My Shuttle"])
@section('content')


    <div class="section-126">
        <div class="w-container">
            <div class="div-block">
                <h1 class="heading-8">Welcome back.</h1>
                <div class="w-form">
                    <form id="frm_login" name="email-form" data-name="Email Form" class="form">
                        <input type="email" class="text-field w-input" maxlength="256" name="login_email" data-name="login_email" placeholder="Email Address" id="login_email" required="">
                        <input type="password" class="text-field-2 w-input" maxlength="256" name="login_pass" data-name="login_pass" placeholder="Password" id="login_pass" required=""><!-- <input type="submit" value="LOGIN" data-wait="Please wait..." class="submit-button  w-button"> -->
                        <button id="login-btn" class="submit-button w-button" name="submit_login" type="submit">LOGIN</button>
                    </form>
                    <div class="w-form-done" style="display: none;">
                        <div>Thank you! Your submission has been received!</div>
                    </div>
                    <div class="w-form-fail" style="display: none;">
                        <div>Oops! Something went wrong while submitting the form.</div>
                    </div>
                </div>
                <div class="text-block-2">Forgot username or password? <a href="recover-account.php" class="link-2">Recover account</a></div>
            </div>
        </div>
    </div>


@endsection



@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js" type="text/javascript"></script>
    <!-- <script src="js/tmsreal2fae42359873429834-900043727463e.js" type="text/javascript"></script> -->
    <script src="{{asset("admin/plus/js/gate.js")}}" type="text/javascript"></script>
    <!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->

@endsection