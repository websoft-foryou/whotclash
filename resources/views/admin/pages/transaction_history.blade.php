@extends('admin/layouts.admin')
@section('content')
    <form id="main-form" name="main-form" action="{{ route('view-history') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="playdate_from" id="playdate_from" value="{{$playdate_from}}" />
    <input type="hidden" name="playdate_to" id="playdate_to" value="{{$playdate_to}}" />
    <input type="hidden" name="transaction_date_from" id="transaction_date_from" value="{{$transaction_date_from}}"/>
    <input type="hidden" name="transaction_date_to" id="transaction_date_to" value="{{$transaction_date_to}}" />

    <div class="row">
        <div class="col-12">
            <div class="kt-portlet kt-portlet--mobile">

                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <span class="kt-portlet__head-icon">
                            <i class="kt-font-brand fa fa-gamepad"></i>
                        </span>
                        <h3 class="kt-portlet__head-title">
                            Play History
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar col-lg-4 col-md-9 col-sm-12">
                        <div class="input-group pull-right" id="view_play_date">
                            <input type="text" class="form-control" readonly="" placeholder="Select date range" value="{{ $playdate_from != '' ? $playdate_from . ' ~ ' . $playdate_to : '' }}" >
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="kt-portlet__body">
                    <!--begin: Datatable -->
                    <table class="table table-striped- table-hover table-checkable" id="play_history_table">
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
                                <td>{{ $game->game_type }}
                                    {!! $game->winner_user == null ? "<span class='kt-badge kt-badge--warning  kt-badge--inline kt-badge--pill'>no winner</span>" : "" !!}
                                </td>
                                <td>{{ $game->user1 }}
                                    {!! $game->uid1 == $game->winner_user ? "<span class='kt-badge kt-badge--success  kt-badge--inline kt-badge--pill'>winner</span>" : "" !!}
                                </td>
                                <td>{{ $game->user2 }}
                                    {!! $game->uid2 == $game->winner_user ? "<span class='kt-badge kt-badge--success  kt-badge--inline kt-badge--pill'>winner</span>" : "" !!}
                                </td>
                                <td>{{ $game->user3 }}
                                    {!! $game->uid3 != null && $game->uid3 == $game->winner_user ? "<span class='kt-badge kt-badge--success  kt-badge--inline kt-badge--pill'>winner</span>" : "" !!}
                                </td>
                                <td>{{ $game->user4 }}
                                    {!! $game->uid4 != null && $game->uid4 == $game->winner_user ? "<span class='kt-badge kt-badge--success  kt-badge--inline kt-badge--pill'>winner</span>" : "" !!}
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


    <div class="row">
        <div class="col-12">
            <div class="kt-portlet kt-portlet--mobile">

                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <span class="kt-portlet__head-icon">
                            <i class="kt-font-brand fa fa-money-check-alt"></i>
                        </span>
                        <h3 class="kt-portlet__head-title">
                            Transaction History
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar col-lg-4 col-md-9 col-sm-12">
                        <div class="input-group pull-right" id="view_transaction_date">
                            <input type="text" class="form-control" readonly="" placeholder="Select date range" value="{{ $transaction_date_from != '' ? $transaction_date_from . ' ~ ' . $transaction_date_to : '' }}" >
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kt-portlet__body">
                    <!--begin: Datatable -->
                    <table class="table table-striped- table-hover table-checkable" id="transaction_history_table">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Date</th>
                            <th>Coins(+)</th>
                            <th>Coins(-)</th>
                            <th>Coins</th>
                            <th>Cash(+)</th>
                            <th>Withdraw</th>
                            <th>Cash</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $index = 1; @endphp
                        @php $coin = 0; @endphp
                        @php $cash = 0; @endphp
                        @foreach($transaction_data as $transaction)
                            <tr>
                                <td>{{ $index ++ }}</td>
                                <td>{{ substr($transaction->transaction_date, 0, 10) }}</td>
                                <td>{{ $transaction->in_coins > 0 ? $transaction->in_coins : '' }}</td>
                                <td>{{ $transaction->out_coins > 0 ? $transaction->out_coins : '' }}</td>
                                <td>{{ $transaction->in_coins > 0 || $transaction->out_coins > 0 ? ($coin + $transaction->in_coins - $transaction->out_coins) : '' }}</td>
                                <td>{{ $transaction->in_cash > 0 ? $transaction->in_cash : '' }}</td>
                                <td>{{ $transaction->out_cash > 0 ? $transaction->out_cash : '' }}</td>
                                <td>{{ $transaction->in_cash > 0 || $transaction->out_cash > 0 ? ($cash + $transaction->in_cash - $transaction->out_cash) : '' }}</td>
                            </tr>
                            @php $coin = $coin + $transaction->in_coins - $transaction->out_coins @endphp
                            @php $cash = $cash + $transaction->in_cash - $transaction->out_cash @endphp
                        @endforeach
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
    </form>
@endsection
@section('script')
    <script src="{{asset('admin/customize/js/game_history.js')}}"></script>
@endsection
