@extends('layouts.AdminLTE.index')

@section('icon_page', 'book')

@section('title', 'Logs')


@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped display responsive nowrap">
                        <thead style="text-align:left;">
                        <tr>
                            <th>User ID</th>
                            <th>User Name</th>
                            <th>Action</th>
                            <th>Table</th>
                            <th>Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <!-- /.col -->
    </div>

    <script src="{{ asset('/js/log/index.js') }}"></script>
@endsection
