@extends('layouts.AdminLTE.index')

@section('icon_page', 'clock')

@section('title', 'Rosters')


@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped display responsive nowrap">
                        <thead style="text-align:left;">
                        <tr>
                            <th>Store</th>
                            <th>Start Date</th>
                            <th>Status</th>
                            <th>Actions</th>
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
    {{--    modal --}}

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel"
         style="width: 600px;">
        <div class="offcanvas-header">
            <h4 style="margin-top:0;font-size: 30px;" class="offcanvas-title" id="temptitle">
                New Staff
            </h4>
        </div>
        <form id="rosterForm">
            <div class="offcanvas-body">

                <div>
                    <div class="row" style="margin-bottom: 15px; padding:0;">
                        <div class="col-md-6">
                            <label for="locationid">Locations :</label>
                            <select class="form-control productinput" id="locationid" name="locationid">
                                @if(!empty($locations))
                                    @foreach($locations as $location)
                                        <option
                                            value="{{ $location->locationid }}">{{ $location->locationname }}</option>
                                    @endforeach
                                @else
                                    <option value="no">No Locations</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="status">Status :</label>
                            <select class="form-control customer-input" id="status" name="status">
                                <option value="published">
                                    Published
                                </option>
                                <option value="draft">
                                    Draft
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px;padding:0;">
                        <div class="col-md-6">
                            <label for="mondaybudget">Monday Budget :</label>
                            <input type="text" class="staff-input form-control" id="mondaybudget" name="mondaybudget">
                        </div>
                        <div class="col-md-6">
                            <label for="tuesdaybudget">Tuesday Budget :</label>
                            <input type="text" class="form-control docs-date" id="tuesdaybudget" name="tuesdaybudget">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px;padding:0;">
                        <div class="col-md-6">
                            <label for="wednesdaybudget">Wednesday Budget :</label>
                            <input type="text" class="staff-input form-control" id="wednesdaybudget"
                                   name="wednesdaybudget">
                        </div>
                        <div class="col-md-6">
                            <label for="thursdaybudget">Thursday Budget :</label>
                            <input type="text" class="form-control docs-date" id="thursdaybudget" name="thursdaybudget">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px;padding:0;">
                        <div class="col-md-6">
                            <label for="fridaybudget">Friday Budget :</label>
                            <input type="text" class="staff-input form-control" id="fridaybudget" name="fridaybudget">
                        </div>
                        <div class="col-md-6">
                            <label for="saturdaybudget">Saturday Budget :</label>
                            <input type="text" class="form-control docs-date" id="saturdaybudget" name="saturdaybudget">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px;padding:0;">
                        <div class="col-md-6">
                            <label for="sundaybudget">Sunday Budget :</label>
                            <input type="text" class="staff-input form-control" id="sundaybudget" name="sundaybudget">
                        </div>
                        <div class="col-md-6">
                            <label for="startdate">Start Date :</label>
                            <input type="date" class="stock-input form-control" id="startdate" name="startdate"
                                   placeholder="Start Date" required>
                        </div>
                    </div>


                </div>
                <button type="button" class="profile-btn-snd close-animatedModal"
                        style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas">
                    Close
                </button>
                <button type="button" onclick="addRoster()" class="profile-btn savebut" style="border: none;">Save
                </button>
                <button type="button" style="display: none; border: none;" onclick="updateRoster()"
                        class="profile-btn updatebut">Update
                </button>


            </div>
        </form>
    </div>


    <script src="{{ asset('/js/roster/index.js') }}"></script>
@endsection
