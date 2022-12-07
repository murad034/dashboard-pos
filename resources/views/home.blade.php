@extends('layouts.AdminLTE.index')

@section('icon_page', 'tachometer-alt')

@section('title', 'Dashboard ')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="filterbar row" style="background-color: #2C3D45;
    padding: 20px;
    margin-bottom: 10px;">
                        <div class="col-md-4">
                            <select class="store" style="width: 100%;
                                height: 40px; font-size: 18px;">
                                <option value="0">All Stores</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->locationid }}">
                                        {{ $location->locationname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control js-daterangepicker" data-auto-apply="true">
                        </div>
                        <div class="col-md-4">
                            <button id="filter-data"
                                    style="height: 40px; width: 150px; color: #000; background: #fff; border: none;">
                                Filter
                            </button>
                        </div>
                    </div>
                    <div class="row" style=" padding: 0px; margin-top:20px;">
                        <div class="col-md-4" style=" padding-right: 15px;
    color: #fff;
    font-size: 30px;
    padding-left: 0px;">
                            <div
                                style="background: green; background-image: url('{{ asset('img/totalsalesback.png')}}'); background-position: top right; background-size: cover; padding: 20px;">
                                <h3>
                                    TOTAL SALES
                                </h3>
                                <span class="font-size: 50px;" id="total-sale">$0.00</span>
                            </div>

                        </div>
                        <div class="col-md-4" style="padding-right: 15px;
    color: #000;
    font-size: 30px;
    padding-left: 0px;">
                            <div
                                style="background: yellow; background-image: url('{{ asset('img/averageback.png')}}'); background-position: top right; background-size: cover;  padding: 20px;">
                                <h3>
                                    ATV
                                </h3>
                                <span class="font-size: 50px;" id="atv">$0.00</span>
                            </div>

                        </div>
                        <div class="col-md-4" style="padding-right: 0px;
    color: #fff;
    font-size: 30px;
    padding-left: 0px;">
                            <div
                                style="background: purple; background-image: url('{{ asset('img/totaltransactions.png')}}'); background-position: top right; background-size: cover;  padding: 20px;">
                                <h3>
                                    TOTAL TRANSACTIONS
                                </h3>
                                <span class="font-size: 50px;" id="total-tran">0</span>
                            </div>

                        </div>
                    </div>
                    <div class="row" style=" padding: 0px; margin-top: 20px;">
                        <h3>Monthly Sales Comparison</h3>

                        <canvas id="monthlySales" height="500"></canvas>

                    </div>
                    <div class="row" style=" padding: 0px; margin-top: 20px;">
                        <h3>Sales with Weather</h3>

                        <canvas id="salesWeather" height="500"></canvas>

                    </div>
                    <div class="row" style=" padding: 0px; margin-top: 20px;">
                        <div class="col-md-3">
                            <h3>Top 5 Items by Sales</h3>

                            <canvas id="itemsales" width="300" height="300"></canvas>
                        </div>
                        <div class="col-md-3">
                            <h3>Top 5 Items by Cost</h3>

                            <canvas id="itemcost" width="300" height="300"></canvas>
                        </div>
                        <div class="col-md-3">
                            <h3>Top Staff by Sales</h3>

                            <canvas id="staffsales" width="300" height="300"></canvas>
                        </div>
                        <div class="col-md-3">
                            <h3>Top Customer by Sales</h3>

                            <canvas id="customersales" width="300" height="300"></canvas>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>

    <script src="{{ asset('/js/home/index.js') }}"></script>
@endsection
