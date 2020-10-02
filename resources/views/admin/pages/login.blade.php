@extends('admin/layouts.login')
@section('content')
<div class="kt-grid kt-grid--hor kt-grid--root kt-login kt-login--v2 kt-login--signin" id="kt_login">
  <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background-image: url(assets/media/bg/bg-1.jpg);">
    <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
      <div class="kt-login__container">

        <div class="kt-login__logo">
          <a href="/admin/index">
            <img src="assets/media/logos/logo.png">
          </a>
        </div>

        <div class="kt-login__signin">
          <div class="kt-login__head">
            <h3 class="kt-login__title">Sign In To Admin</h3>
          </div>

            @if (session('error'))
                <div class="alert alert-solid-danger alert-bold" role="alert">
                    <div class="alert-icon"><i class="flaticon2-warning"></i></div>
                    <div class="alert-text">{{ session('error') }}</div>
                    <div class="alert-close">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="la la-close"></i></span>
                        </button>
                    </div>
                </div>
            @endif
          <form class="kt-form" method="POST" action="{{ route('admin-login') }}">
            @csrf
            <div class="input-group">
              <input class="form-control @error('email') is-invalid @enderror" type="text" placeholder="Email" name="email" autocomplete="email" value="{{ old('email') }}" autofocus>
              @error('email')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <div class="input-group">
              <input class="form-control @error('password') is-invalid @enderror" type="password" placeholder="Password" name="password" autocomplete="current-password">
              @error('password')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <div class="kt-login__actions">
              <button id="kt_login_signin_submit" class="btn btn-pill kt-login__btn-primary" type="submit">Sign In</button>
            </div>
          </form>
        </div>

        <div class="kt-login__signup">
          <div class="kt-login__head">
            <h3 class="kt-login__title">Sign Up</h3>
            <div class="kt-login__desc">Enter your details to create your account:</div>
          </div>
          <form class="kt-login__form kt-form" action="" method="POST">
            @csrf
            <div class="input-group">
              <input class="form-control" type="text" placeholder="Fullname" name="name" autocomplete="name">
            </div>
            <div class="input-group">
              <input class="form-control" type="text" placeholder="Email" name="email" autocomplete="email">
            </div>
            <div class="input-group">
              <input class="form-control" type="password" placeholder="Password" name="password">
            </div>
            <div class="input-group">
              <input class="form-control" type="password" placeholder="Confirm Password" name="rpassword">
            </div>
            <div class="row kt-login__extra">
              <div class="col kt-align-left">
                <label class="kt-checkbox">
                  <input type="checkbox" name="agree">I Agree the <a href="#"
                    class="kt-link kt-login__link kt-font-bold">terms and conditions</a>.
                  <span></span>
                </label>
                <span class="form-text text-muted"></span>
              </div>
            </div>
            <div class="kt-login__actions">
              <button id="kt_login_signup_submit" class="btn btn-pill kt-login__btn-primary">Sign
                Up</button>&nbsp;&nbsp;
              <button id="kt_login_signup_cancel" class="btn btn-pill kt-login__btn-secondary">Cancel</button>
            </div>
          </form>
        </div>

        <div class="kt-login__forgot">
          <div class="kt-login__head">
            <h3 class="kt-login__title">Forgotten Password ?</h3>
            <div class="kt-login__desc">Enter your email to reset your password:</div>
          </div>
          <form class="kt-form" action="">
            <div class="input-group">
              <input class="form-control" type="text" placeholder="Email" name="email" id="kt_email" autocomplete="off">
            </div>
            <div class="kt-login__actions">
              <button id="kt_login_forgot_submit"
                class="btn btn-pill kt-login__btn-primary">Request</button>&nbsp;&nbsp;
              <button id="kt_login_forgot_cancel" class="btn btn-pill kt-login__btn-secondary">Cancel</button>
            </div>
          </form>
        </div>

        {{-- <div class="kt-login__account">
          <span class="kt-login__account-msg">
            Don't have an account yet ?
          </span>&nbsp;&nbsp;
          <a href="javascript:;" id="kt_login_signup" class="kt-link kt-link--light kt-login__account-link">Sign
            Up</a>
        </div> --}}

      </div>
    </div>
  </div>
</div>
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email"
                                class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password"
                                class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
