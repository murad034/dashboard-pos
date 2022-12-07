@extends('layouts.AdminLTE.index')

@section('icon_page', 'chart-line')

@section('title', 'Reports')


@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="filterbar row" style="background-color: #2C3D45;
                                    padding: 20px;
                                    margin-bottom: 10px;
                                    margin-left: 0px;
                                    margin-right: 0px;">
                        <div class="col-md-3">
                            <select class="report" style="width: 100%;
                                height: 40px; font-size: 18px;">
                                <option value="1">Select A Report</option>
                                @foreach($reports as $report)
                                    <option value="{{ $report->value }}">{{ $report->title }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="store" style="width: 100%;
                                height: 40px; font-size: 18px;">
                                <option value="">All Stores</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->locationname }}">{{ $location->locationname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control js-daterangepicker" data-auto-apply="true">
                        </div>
                        <div class="col-md-3">
                            <button onclick="filter()"
                                    style="height: 40px; width: 150px; color: #000; background: #fff; border: none;">
                                Filter
                            </button>
                        </div>
                    </div>

                    <object id="pdf" data="{{ asset('/img/reportfilters.jpg') }}" width="100%"
                            style="height:70vh"></object>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <!-- /.col -->
    </div>
    {{--    modal --}}



    <script src="{{ asset('/js/report/index.js') }}"></script>
@endsection
