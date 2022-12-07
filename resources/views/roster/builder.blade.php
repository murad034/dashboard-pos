@extends('layouts.AdminLTE.index')

@section('icon_page', 'clock')

@section('title', 'Rosters')


@section('content')
    <link rel="stylesheet" href="{{ asset('/js/roster/css/mobiscroll.jquery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/js/roster/css/style.css') }}">

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">

                    <div mbsc-page class="demo-employee-shifts">
                        <div style="height:100%">
                            <div id="demo-employee-shifts-calendar" class="md-employee-shifts"></div>

                            <div id="demo-employee-shifts-popup" class="employee-shifts-popup">
                                <div class="mbsc-form-group">
                                    <label for="employee-shifts-start">
                                        start
                                        <input mbsc-input data-dropdown="true" id="employee-shifts-start"/>
                                    </label>
                                    <label for="employee-shifts-end">
                                        end
                                        <input mbsc-input data-dropdown="true" id="employee-shifts-end"/>
                                    </label>
                                    <div id="demo-employee-shifts-date"></div>
                                </div>
                                <div class="mbsc-form-group">
                                    <label>
                                        Notes
                                        <textarea mbsc-textarea id="employee-shifts-notes"></textarea>
                                    </label>
                                </div>
                                <div class="mbsc-button-group">
                                    <button class="mbsc-button-block" id="employee-shifts-delete" mbsc-button
                                            data-color="danger" data-variant="outline">Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <!-- /.col -->
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">

                    <div id="custom_availabilty_info">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div id="tableDiv">
                <!--  JQuery data table content              -->
            </div>
        </div>
    </div>
    <script>

        let locationId = '{{ $location_id }}';
        let startDate = '{{ $start_date }}';
        let rosterId = '{{ $roster_id }}';
        let staffList = @json($staffs);
        let shiftData = @json($roster_data);
        let invalid = []

        //Sample data
        // let invalid = [{
        //     resource: 1,
        //     recurring: {
        //         repeat: 'weekly',
        //         weekDays: 'SA,SU'
        //     }
        // }]

        if (staffList) {
            staffList.forEach(staff => {
                let asArray = Object.entries(staff);
                let filtered = asArray.filter(([key, value]) => value == 'no'); //Filter for weekdays with no entry
                let justStrings = Object.fromEntries(filtered);

                // Making object for invalid entry
                let tempObj = {
                    resource: staff.id,
                    recurring: {
                        repeat: 'weekly',
                        weekDays: `${Object.keys(justStrings).toString()}`
                    }
                }
                invalid.push(tempObj);
            });
        }
    </script>

    <script src="{{ asset('/js/roster/js/mobiscroll.jquery.min.js') }}"></script>
    <script src="{{ asset('/js/roster/builder.js') }}"></script>
@endsection
