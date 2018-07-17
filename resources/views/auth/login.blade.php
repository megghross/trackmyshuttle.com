@extends('layouts.pagelayout', ["title"=> "Login | Track My Shuttle"])
@section('content')


    <div id="wrapper" class="row wrapper">

        <div class="container-min-full-height d-flex justify-content-center align-items-center">
            <div class="login-center">
                <div class="navbar-header text-center mb-5">
                    <a href="index.php">
                        <img alt="" src="assets/demo/logo-expand-dark.png">
                    </a>
                </div><!-- /.navbar-header -->

                <form method="POST" action="{{ route('login') }}">
                    @csrf


                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" class="form-control form-control-line {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                        @endif
                    </div>



                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" class="form-control form-control-line {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <button class="btn btn-block btn-lg btn-color-scheme text-uppercase fs-12 fw-600" type="submit">
                            Login
                        </button>
                    </div>

                    <div class="form-group no-gutters mb-0">
                        <div class="col-md-12 d-flex">
                            <div class="checkbox checkbox-primary mr-auto mr-0-rtl ml-auto-rtl">
                                <label class="d-flex">
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <span class="label-text"> {{ __('Remember Me') }}</span>
                                </label>
                            </div>
                            <a href="{{Route("password.request")}}" id="to-recover" class="my-auto pb-2 text-right">
                                <i class="material-icons mr-2 fs-18">lock</i> Forgot Password?
                            </a>
                        </div><!-- /.col-md-12 -->
                    </div><!-- /.form-group -->
                </form><!-- /.form-material -->

                <hr />

                <div class="row btn-list">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-block btn-facebook ripple" data-toggle="tooltip" data-placement="top" title="Login with Facebook">
                            <i class="social-icons list-icon">facebook</i>
                            facebook
                        </button>
                    </div>

                    <div class="col-md-6">
                        <button type="button" class="btn btn-block btn-googleplus ripple" data-toggle="tooltip" data-placement="top" title="Login with Google">
                            <i class="social-icons list-icon">googleplus</i>
                            google
                        </button>
                    </div>
                </div><!-- /.btn-list -->

                <footer class="col-sm-12 text-center">
                    <hr />
                    {{--<p>Don't have an account? <a href="page-register.php" class="text-primary m-l-5"><b>Sign Up</b></a></p>--}}
                </footer>

            </div><!-- /.login-center -->
        </div><!-- /.d-flex -->
    </div><!-- /.body-container -->

@endsection
