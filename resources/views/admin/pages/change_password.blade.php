@extends('admin/layouts.admin')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="kt-portlet kt-portlet--mobile">
                <form action="{{route('change-password')}}" method="post">
                    @csrf
                    <div class="kt-portlet__head kt-portlet__head--lg">
                        <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand flaticon-lock"></i>
                    </span>
                            <h3 class="kt-portlet__head-title">
                                Change Password
                            </h3>
                        </div>
                    </div>

                    <div class="kt-portlet__body kt-form kt-form--label-right">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Current Password</label>
                            <div class="col-md-4 col-xl-3">
                                <input class="form-control {{ $errors->has('current_password') ? ' is-invalid' : '' }}" name="current_password" id="current_password" type="password" value="{{ old('current_password') }}">
                                @if ($errors->has('current_password'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('current_password') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">New Password</label>
                            <div class="col-md-4 col-xl-3">
                                <input class="form-control {{ $errors->has('new_password') ? ' is-invalid' : '' }}" name="new_password" id="new_password" type="password" value="{{ old('new_password') }}">
                                @if ($errors->has('new_password'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('new_password') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Confirm Password</label>
                            <div class="col-md-4 col-xl-3">
                                <input class="form-control {{ $errors->has('new_password_confirmation') ? ' is-invalid' : '' }}" name="new_password_confirmation" id="new_password_confirmation" type="password" value="{{ old('new_password_confirmation') }}">
                                @if ($errors->has('new_password_confirmation'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('new_password_confirmation') }}</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__foot">
                        <div class="row">
                            <label class="col-form-label col-lg-3 col-sm-12"></label>
                            <div class="col-lg-9 col-md-9 col-sm-12">
                                <button type="submit" id="save-data" class="btn btn-brand">Save</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{url('admin')}}/customize/js/profile.js"></script>
@endsection
