@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('./css/cryptocoins.css') }}">
    {{--<script src="http://code.highcharts.com/highcharts.js"></script>--}}
    <script src="{{URL::asset('./assets/js/highcharts.js') }}"></script>
    <style>
        th,td{
            text-align: center;
        }
        form {
            padding:20px;
        }
        .pull-center {
            text-align: center;
        }
        .th-title {
            font-size:16px;
            font-weight: bold;
        }
        .modal-lg {
            width: 95%!important;
        }
    </style>
    <div class="container">
        {{ csrf_field() }}
        <?php
            ($btc_data['percent_change_24h']<0) ? $btcColorStr = "red" : $btcColorStr = "green";
            ($eth_data['percent_change_24h']<0) ? $ethColorStr = "red" : $ethColorStr = "green";
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h3>Chart {{ $dateStr }}</h3>
                </div>
                <div class="col-md-12 text-center">
                    <div class="col-md-12 title-content-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="th-title" width="10%">Name</th>
                                    <th class="text-center th-title" width="15%;">Market Cap</th>
                                    <th class="text-center th-title" width="15%">Price</th>
                                    <th class="text-center th-title" width="15%">Circulating Supply</th>
                                    <th class="text-center th-title" width="15%">Volume (24h)</th>
                                    <th class="text-center th-title" width="15%">% Change (24h)</th>
                                    <th class="text-center th-title" width="15%">Price Graph (7d)</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td align="left"><i class="cc BTC"></i>{{ $btc_data['name'] }}</td>
                                        <td>${{ number_format($btc_data['market_cap_usd'],2,".",",")  }}</td>
                                        <td>${{ number_format($btc_data['price_usd'],2,".",",") }}</td>
                                        <td>{{ number_format($btc_data['available_supply'],2,".",",") }}BTC</td>
                                        <td>${{ number_format($btc_data['24h_volume_usd'],2,".",",") }}</td>
                                        <td style="color:{{ $btcColorStr }}">{{ number_format($btc_data['percent_change_24h'],2,".",",") }}%</td>
                                        <td>
                                            <button type="button" id="btc" title="Bitcoin" data-toggle="modal" data-target="#chart_dialog" class="btn btn-default a_view_chart">View Chart</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left"><i class="cc ETH"></i>{{ $eth_data['name'] }}</td>
                                        <td>${{ number_format($eth_data['market_cap_usd'],2,".",",") }}</td>
                                        <td>${{ number_format($eth_data['price_usd'],2,".",",") }}</td>
                                        <td>{{ number_format($eth_data['available_supply'],2,".",",") }}ETH</td>
                                        <td>${{ number_format($eth_data['24h_volume_usd'],2,".",",") }}</td>
                                        <td style="color:{{ $ethColorStr }}">{{ number_format($eth_data['percent_change_24h'],2,".",",") }}%</td>
                                        <td>
                                            <button type="button" id="eth" title="Ethereum" data-toggle="modal" data-target="#chart_dialog" class="btn btn-default a_view_chart">View Chart</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
<!-- Modal -->
<div class="modal fade" id="chart_dialog" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <div id="container" style="width:100%; height:400px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('./assets/jquery-1.10.2.min.js') }}"></script>
<script src="{{URL::asset('./js/chart/index.js')}}" ></script>
@endsection
