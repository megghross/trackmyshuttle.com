@extends('layouts.pagelayout',  ["title"=>"Reset Password | Track My Shuttle"])

@section('content')
    <div id="wrapper" class="wrapper">

        <div class="row container-min-full-height align-items-center">
            <div class="col-10 ml-sm-auto col-sm-6 col-md-4 ml-md-auto login-center login-center-mini mx-auto">
                <div class="navbar-header text-center mb-3">
                    <a href="index.php">
                        <img alt="" src="{{asset("assets/demo/logo-expand-dark.png")}}">
                    </a>
                </div><!-- /.navbar-header -->
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <form class="form-material" method="POST" action="{{ route('password.email') }}">
                @csrf
                    <p class="text-center text-muted">Enter your email address and we'll send you an email with instructions to reset your password.</p>

                    <div class="form-group no-gutters">
                        <input id="email" type="email" class="form-control form-control-line {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                        <label for="email" class="col-md-12 mb-1">Email</label>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="form-group mb-5">
                        <button class="btn btn-block btn-lg btn-color-scheme text-uppercase fs-12 fw-600" type="submit">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </div>
                </form><!-- /.form-material -->

                <footer class="col-sm-12 text-center">
                    <hr />
                    <p>Back to <a href="{{Route("login")}}" class="text-primary m-l-5"><b>Login</b></a></p>
                </footer>

            </div><!-- /.login-right -->
        </div><!-- /.row -->
    </div><!-- /.body-container -->

@endsection
