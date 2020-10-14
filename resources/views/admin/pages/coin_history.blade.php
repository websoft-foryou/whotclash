@extends('admin/layouts.admin')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="kt-portlet kt-portlet--mobile">

                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand fa fa-coins"></i>
                    </span>
                        <h3 class="kt-portlet__head-title">
                            [{{ $user_name }}]'s Transaction History
                        </h3>
                    </div>
                </div>

                <div class="kt-portlet__body">
                    <!--begin: Datatable -->
                    <table class="table table-striped- table-hover table-checkable" id="main_table">
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
                        @foreach($coin_data as $coins)
                            <tr>
                                <td>{{ $index ++ }}</td>
                                <td>{{ substr($coins->coin_date, 0, 10) }}</td>
                                <td>{{ $coins->in_coins > 0 ? $coins->in_coins : '' }}</td>
                                <td>{{ $coins->out_coins > 0 ? $coins->out_coins : '' }}</td>
                                <td>{{ $coins->in_coins > 0 || $coins->out_coins > 0 ? ($coin + $coins->in_coins - $coins->out_coins) : '' }}</td>
                                <td>{{ $coins->in_cash > 0 ? $coins->in_cash : '' }}</td>
                                <td>{{ $coins->out_cash > 0 ? $coins->out_cash : '' }}</td>
                                <td>{{ $coins->in_cash > 0 || $coins->out_cash > 0 ? ($cash + $coins->in_cash - $coins->out_cash) : '' }}</td>
                            </tr>
                            @php $coin = $coin + $coins->in_coins - $coins->out_coins @endphp
                            @php $cash = $cash + $coins->in_cash - $coins->out_cash @endphp
                        @endforeach
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
@endsection
@section('script')

    <script src="{{asset('admin/customize/js/coin_management.js')}}"></script>
@endsection
