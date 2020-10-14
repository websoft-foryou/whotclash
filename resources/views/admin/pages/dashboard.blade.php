@extends('admin/layouts.admin')
@section('content')
<link rel="stylesheet" href={{ asset('admin/customize/css/dashboard.css') }}>
<div class="row">
  <div class="col-lg-6">
    <div class="kt-portlet kt-iconbox">
      <div class="kt-portlet__body">
        <div class="kt-iconbox__body">

          <div class="kt-iconbox__icon">
            <i class="fa fa-user dashboard-icons dashboard-usermanagement-icon"></i>
          </div>
          <div class="kt-iconbox__desc">
            <h3 class="kt-iconbox__title">
              <a class="kt-link" href="{{route('open-usermanagement-page')}}">User Management</a>
            </h3>
            <div class="kt-iconbox__content">
              Manage Users of WhotClash
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="kt-portlet kt-iconbox">
      <div class="kt-portlet__body">
        <div class="kt-iconbox__body">
            <div class="kt-iconbox__icon">
                <i class="fa fa-gamepad dashboard-icons dashbaord-gamehistory-icon"></i>
            </div>
            <div class="kt-iconbox__desc">
                <h3 class="kt-iconbox__title">
                    <a class="kt-link" href="{{route('open-playhistory-page')}}">Game History</a>
                </h3>
                <div class="kt-iconbox__content">
                    Show history of Game
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>

</div>

@endsection
