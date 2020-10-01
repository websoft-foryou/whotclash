@extends('admin/layouts.admin')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="kt-portlet kt-portlet--mobile">

                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand fa fa-gamepad"></i>
                    </span>
                        <h3 class="kt-portlet__head-title">
                            Game History
                        </h3>
                    </div>
                </div>

                <div class="kt-portlet__body">
                    <!--begin: Datatable -->
                    <table class="table table-striped- table-hover table-checkable" id="main_table">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Play Date</th>
                            <th>Players</th>
                            <th>User1</th>
                            <th>User2</th>
                            <th>User3</th>
                            <th>User4</th>
                            <th>Bet Amount</th>
                            <th>Win Prize</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $index = 1; @endphp
                        @foreach($game_data as $game)
                            <tr>
                                <td>{{ $index ++ }}</td>
                                <td>{{ substr($game->created_at, 0, 10) }}</td>
                                <td>{{ $game->game_type }} </td>
                                <td>{{ $game->user1 }}
                                    {!! $game->uid1 == $game->winner_user ? "<span class='kt-badge kt-badge--success  kt-badge--inline kt-badge--pill'>winner</span>" : "" !!}
                                </td>
                                <td>{{ $game->user2 }}
                                    {!! $game->uid2 == $game->winner_user ? "<span class='kt-badge kt-badge--success  kt-badge--inline kt-badge--pill'>winner</span>" : "" !!}
                                </td>
                                <td>{{ $game->user3 }}
                                    {!! $game->uid3 == $game->winner_user ? "<span class='kt-badge kt-badge--success  kt-badge--inline kt-badge--pill'>winner</span>" : "" !!}
                                </td>
                                <td>{{ $game->user4 }}
                                    {!! $game->uid4 == $game->winner_user ? "<span class='kt-badge kt-badge--success  kt-badge--inline kt-badge--pill'>winner</span>" : "" !!}
                                </td>
                                <td>{{ $game->bet_amount }}</td>
                                <td>{{ $game->win_amount }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('admin/customize/js/user_management.js')}}"></script>
@endsection
