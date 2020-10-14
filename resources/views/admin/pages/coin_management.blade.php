@extends('admin/layouts.admin')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="kt-portlet kt-portlet--mobile">

                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand fa fa-users"></i>
                    </span>
                        <h3 class="kt-portlet__head-title">
                            Coin Management
                        </h3>
                    </div>
                </div>

                <form id="main-form" name="main-form" method="post" action="" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="data_id" id="data_id" />
                <input type="hidden" name="history_url" id="history_url" value="{{ route('coin-history') }}"/>
                <div class="kt-portlet__body">
                    <!--begin: Datatable -->
                    <table class="table table-striped- table-hover table-checkable" id="main_table">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>User Name</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Coins</th>
                            <th>Cash</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $index = 1; @endphp
                        @foreach($user_data as $user)
                            <tr>
                                <td>{{ $index ++ }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{!! $user->block == 1 ? "<span class='kt-badge kt-badge--danger kt-badge--inline kt-badge--pill'>Blocked</span>" : "<span class='kt-badge kt-badge--success kt-badge--inline kt-badge--pill'>Normal</span>" !!}</td>
                                <td>{{ $user->coins }}</td>
                                <td>{{ $user->cash }}</td>
                                <td>
                                    <a href="javascript:;" data-id="{{$user->id}}" class="btn btn-sm btn-clean btn-icon btn-icon-md coin-history" title="Transaction History">
                                        <i class="la la-history"></i>
                                    </a>
                                    <a href="javascript:;" data-id="{{$user->id}}" class="btn btn-sm btn-clean btn-icon btn-icon-md add-coins" title="Add Coins">
                                        <i class="la la-plus-circle"></i>
                                    </a>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </form>

                <div class="modal fade" id="add_coins_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm" role="document">
                        <form id="data-form" class="kt-form kt-form--label-right" name="data-form" action="{{ route('add-user-coins') }}" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                            <input type="hidden" id="data_id" name="data_id">
                            <input type="hidden" name="history_url" id="history_url" value="{{ route('coin-history') }}"/>
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add Coins</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="kt-form__body">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Coins</label>
                                            <div class="col-lg-9 col-xl-9">
                                                <input class="form-control" name="coins" id="coins" type="text" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" id="save-data">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')

    <script src="{{asset('admin/customize/js/coin_management.js')}}"></script>
@endsection
