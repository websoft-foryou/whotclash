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
                            User List
                        </h3>
                    </div>
                </div>

                <form id="main-form" name="main-form" method="post" action="" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="data_id" id="data_id" />
                <input type="hidden" name="block_url" id="block_url" value="{{ route('block-user') }}"/>
                <input type="hidden" name="remove_url" id="remove_url" value="{{ route('remove-user') }}"/>
                <input type="hidden" name="history_url" id="history_url" value="{{ route('user-game-history') }}"/>
                <div class="kt-portlet__body">
                    <!--begin: Datatable -->
                    <table class="table table-striped- table-hover table-checkable" id="main_table">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>User Name</th>
                            <th>Gender</th>
                            <th>Birthday</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $index = 1; @endphp
                        @foreach($user_data as $user)
                            <tr>
                                <td>{{ $index ++ }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->gender }}</td>
                                <td>{{ $user->birthday }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{!! $user->block == 1 ? "<span class='kt-badge kt-badge--danger kt-badge--inline kt-badge--pill'>Blocked</span>" : "<span class='kt-badge kt-badge--success kt-badge--inline kt-badge--pill'>Normal</span>" !!}</td>
                                <td>
                                    <span class="dropdown">
                                        <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true" title="actions">
                                          <i class="la la-ellipsis-h"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item history" href="javascript:;" data-id="{{ $user->id }}" row-index="{{ ($index - 1) }}" title="Game History"><i class="flaticon-statistics"></i>Gmae History</a>
                                            <a class="dropdown-item edit" href="javascript:;" data-id="{{ $user->id }}" row-index="{{ $index - 1 }}" title="Edit User"><i class="flaticon-edit"></i>Edit User</a>
                                            <a class="dropdown-item block" href="javascript:;" data-id="{{ $user->id }}" row-index="{{ $index - 1 }}" title="Block User"><i class="flaticon-warning"></i>Block User</a>
                                        </div>
                                    </span>
                                    <a href="javascript:;" data-id="{{$user->id}}" class="btn btn-sm btn-clean btn-icon btn-icon-md remove" title="Delete User">
                                        <i class="la la-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </form>

                <div class="modal fade" id="edit_user_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <form id="data-form" name="data-form" action="{{ route('update-user-info') }}" method="POST" enctype="multipart/form-data">
                            <input type="hidden" id="data_id" name="data_id">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Save User</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="kt-form kt-form--label-right">
                                        <div class="kt-form__body">
                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label">Full Name</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <input class="form-control" name="user_name" id="user_name" type="text" value="">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Gender</label>
                                                <div class="col-lg-9 col-xl-6" style="padding-top:10px">
                                                    <label class="kt-radio kt-radio--bold kt-radio--success">
                                                        <input type="radio" name="gender" id="gender_male" value="Male">Male
                                                        <span></span>
                                                    </label>&nbsp;&nbsp;
                                                    <label class="kt-radio kt-radio--bold kt-radio--success">
                                                        <input type="radio" name="gender" id="gender_female" value="Female">Female
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Birthday</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group date">
                                                        <input type="text" class="form-control" name="birthday" id="birthday" readonly="">
                                                        <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="la la-calendar"></i>
                                                        </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Phone</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text"><i class="la la-phone"></i></span></div>
                                                        <input type="text" class="form-control" value="+35278953712" name="phone" id="phone" placeholder="Phone" aria-describedby="basic-addon1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" id="save-data">Save</button>
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

    <script src="{{asset('admin/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <script src="{{asset('admin/customize/js/user_management.js')}}"></script>
@endsection
