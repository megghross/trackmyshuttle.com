@extends('layouts.arclayout', ["title"=> "Register | Track My Shuttle"])



@section('content')

<div class="section-127">
    <div class="div-block-2"></div>
    <div class="div-block-3">
        <div class="container-8 w-container">
            <h1 class="heading-9">Enter Invitation Code</h1>
            <div class="form-block-2 w-form">
                <form id="email-form" name="email-form" data-name="Email Form" class="form-2">
                    <label class="field-label">Enter your personalized invitation code:</label>
                    <input type="password" class="text-field-3 w-input" maxlength="256" autofocus="true" name="invitation_Code" data-name="invitation_Code" placeholder="Invitation code" id="invitation_Code" required="">
                    <label for="register_Email" class="field-label">Create Account:</label>
                    <input type="text" class="text-field-4 w-input" maxlength="256" name="register_Email" data-name="register_Email" placeholder="Enter your email" id="register_Email" required="">
                    <input type="password" class="text-field-5 w-input" maxlength="256" name="register_pwd" data-name="register_pwd" placeholder="Enter password" id="new_password" onchange="form.confirm_password.pattern = this.value;" minlength=8 required="">
                    <input type="password" class="text-field-6 w-input" maxlength="256" name="register_pwd2" data-name="register_pwd2" placeholder="Reenter password" id="confirm_password" required="">
                    <!-- <input type="submit" value="Start Tracking" data-wait="Please wait..." class="submit-button-2 w-button"> -->
                    <button id="register-btn" class="submit-button-2 w-button" name="submit_register" type="submit">Start Tracking</button>
                </form>
                <div class="w-form-done" style="display:none">
                    <div>Thank you! Your submission has been received!</div>
                </div>
                <div class="w-form-fail" style="display:none">
                    <div>Oops! Something went wrong while submitting the form.</div>
                </div>
            </div>
            <div class="text-block-4-bottom">By signing up, you agree to the <a class="link-3">Terms of Use</a></div>
        </div>
    </div>
</div>

@endsection


@section('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js" type="text/javascript"></script>
<!-- <script src="js/tmsreal2fae42359873429834-900043727463e.js" type="text/javascript"></script> -->
<script src="admin/plus/js/gate.js" type="text/javascript"></script>
<!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->

@endsection