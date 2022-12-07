@extends('layouts.AdminLTE.index')

@section('icon_page', 'location-arrow')

@section('title', 'Locations')


@section('content')
    <style>
        .avatar-img {
            vertical-align: middle;
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }

        .pac-container {
            z-index: 10000 !important;
        }
    </style>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped display responsive nowrap">
                        <thead style="text-align:left;">
                        <tr>
                            <th>Location ID</th>
                            <th>Location Name</th>
                            <th>Location Email</th>
                            <th>Allocated Staff</th>
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
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist" style="padding-top: 18px;">
                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                        type="button" role="tab" aria-controls="nav-home" aria-selected="true">General
                </button>
                <button class="nav-link" id="nav-info-tab" data-bs-toggle="tab" data-bs-target="#nav-info" type="button"
                        role="tab" aria-controls="nav-info" aria-selected="false">Payments and Fee
                </button>
{{--                <button class="nav-link" id="nav-print-tab" data-bs-toggle="tab" data-bs-target="#nav-print"--}}
{{--                        type="button" role="tab" aria-controls="nav-print" aria-selected="false">Printing--}}
{{--                </button>--}}
                <button class="nav-link" id="nav-invoice-quote-tab" data-bs-toggle="tab" data-bs-target="#nav-invoice-quote" type="button"
                        role="tab" aria-controls="nav-invoice-quote" aria-selected="false">Invoice/Quotes Setup
                </button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="offcanvas-header">
                    <h4 style="margin-top:0px;font-size: 30px;" class="offcanvas-title" id="temptitle">
                        New Location
                    </h4>
                    <!--            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>-->
                </div>
                <form id="locationForm">
                    <div class="offcanvas-body">
                        <div class="row" style="margin-bottom: 20px; padding:0;">
                            <div class="col-md-6">
                                <label for="locationname">Location Name :</label>
                                <input type="text" class="location-input form-control" id="locationname"
                                       name="locationname" placeholder="Location Name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="locationabn">Location ABN :</label>
                                <input type="text" class="location-input form-control" id="locationabn"
                                       name="locationabn" placeholder="Location ABN" required>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 20px;padding:0;">
                            <div class="col-md-6">
                                <label for="locationemail">Location Email :</label>
                                <input type="email" class="location-input form-control" id="locationemail"
                                       name="locationemail" placeholder="Location Email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="locationphone">Location Phone :</label>
                                <input type="text" class="location-input form-control" id="locationphone"
                                       name="locationphone" placeholder="Location Phone" required>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 20px;padding:0;">
                            <div class="col-md-12">
                                <label for="allocatedstaff">Allocated Staffs :</label>
                                <select class="form-control select2" id="allocatedstaff" name="allocatedstaff"
                                        multiple="multiple">
                                    @if(!empty($staffs))
                                        @foreach($staffs as $staff)
                                            <option value="{{ $staff->staffid }}">
                                                {{ $staff->staffname }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="no">No Tags</option>
                                    @endif
                                </select>

                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 20px;padding:0;">
                            <div class="col-md-12">
                                <label for="address">Your Address :</label>
                                <input type="text" class="location-input form-control" id="address" name="address"
                                       placeholder="Address" onFocus="geolocate()">
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 20px;padding:0;">
                            <div class="col-md-6">
                                <label for="street_number">Street Number :</label>
                                <input type="text" class="location-input form-control" id="street_number"
                                       name="street_number" placeholder="Street Number" disabled="true">
                            </div>
                            <div class="col-md-6">
                                <label for="route">Street Name :</label><input type="text" class="location-input form-control" id="route" name="route"
                                                                               placeholder="Street Name" disabled="true">
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 20px;padding:0;">
                            <div class="col-md-6">
                                <label for="locality">Suburb / City :</label><input type="text" class="location-input form-control" id="locality" name="locality"
                                                                                    placeholder="Suburb/City" disabled="true">
                            </div>
                            <div class="col-md-6">
                                <label for="administrative_area_level_1">State :</label>
                                <input type="text" class="location-input form-control" id="administrative_area_level_1"
                                       name="administrative_area_level_1" placeholder="State" disabled="true">
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 20px;padding:0;">
                            <div class="col-md-6">
                                <label for="postal_code">Post Code :</label>
                                <input type="text" class="location-input form-control" id="postal_code"
                                       name="postal_code" placeholder="Postcode" disabled="true">
                            </div>
                            <div class="col-md-6">
                                <label for="country">Country :</label>
                                <input type="text" class="location-input form-control" id="country" name="country"
                                       placeholder="Country" disabled="true">
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 20px;padding:0;">
                            <div class="col-md-6">
                                <label for="cityLat">LAT :</label>
                                <input type="text" class="location-input form-control" id="cityLat" name="cityLat"
                                       placeholder="LAT">
                            </div>
                            <div class="col-md-6">
                                <label for="cityLng">LNG :</label>
                                <input type="text" class="location-input form-control" id="cityLng" name="cityLng"
                                       placeholder="LNG">
                            </div>
                        </div>

                        <button type="button" class="profile-btn-snd close-animatedModal"
                                style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas">
                            Close
                        </button>
                        <button type="button" onclick="addLocation()" class="profile-btn savebut" style="border: none;">
                            Save
                        </button>
                        <button type="button" style="display: none; border: none;" onclick="updateLocation()"
                                class="profile-btn updatebut">Update
                        </button>

                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="nav-info" role="tabpanel" aria-labelledby="nav-info-tab">
                <div class="offcanvas-body">
                    <div class="row" style="margin-bottom: 20px; padding:0;">
                        <div class="col-md-6">
                            <label for="marketingfee">Marketing Fee :</label>
                            <input type="text" class="location-input form-control" id="marketingfee" name="marketingfee"
                                   placeholder="Marketing Fee" required>
                        </div>
                        <div class="col-md-6">
                            <label for="franchfee">Franchise Fee :</label>
                            <input type="text" class="location-input form-control" id="franchfee" name="franchfee"
                                   placeholder="Franchise Fee" required>
                        </div>
                    </div>
{{--                    <div class="row" style="margin-bottom: 20px;padding:0;">--}}
{{--                        <div class="col-md-6">--}}
{{--                            <label for="bankname">Bank Name :</label>--}}
{{--                            <input type="text" class="location-input form-control" id="bankname" name="bankname"--}}
{{--                                   placeholder="Bank Name" required>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-6">--}}
{{--                            <label for="accountname">Account Name :</label>--}}
{{--                            <input type="text" class="location-input form-control" id="accountname" name="accountname"--}}
{{--                                   placeholder="Account Name" required>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="row" style="margin-bottom: 20px;padding:0;">--}}
{{--                        <div class="col-md-12">--}}
{{--                            <label for="bsb">BSB :</label>--}}
{{--                            <input type="text" class="location-input form-control" id="bsb" name="bsb" placeholder="BSB"--}}
{{--                                   required>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="row" style="margin-bottom: 20px;padding:0;">--}}
{{--                        <div class="col-md-12">--}}
{{--                            <label for="accountnumber">Account Number :</label>--}}
{{--                            <input type="text" class="location-input form-control" id="accountnumber"--}}
{{--                                   name="accountnumber" placeholder="Account Number">--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <button type="button" class="profile-btn-snd close-animatedModal"
                            style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas">
                        Close
                    </button>
                    <button type="button" onclick="addLocation()" class="profile-btn savebut" style="border: none;">Save
                    </button>
                    <button type="button" style="display: none; border: none;" onclick="updateLocation()"
                            class="profile-btn updatebut">Update
                    </button>

                </div>
            </div>
            <div class="tab-pane fade" id="nav-print" role="tabpanel" aria-labelledby="nav-print-tab">...</div>
            <div class="tab-pane fade" id="nav-invoice-quote" role="tabpanel" aria-labelledby="nav-invoice-quote-tab">
                <div class="offcanvas-body">
                    <div class="row" style="margin-bottom: 15px; padding:0;">
                        <div class="col-md-12">
                            <label for="image">Image :</label>
                            <input accept="image/*" type='file' id="image" name="image"/>
                            <img id="image_preview" src="#" style="display:none;" alt="image" width="100px"
                                 height="100px"/>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 20px; padding:0;">
                        <div class="col-md-12">
                            <label for="invoice-footer-message-1">Invoice Footer Message 1:</label>
                            <input type="text" class="location-input form-control" id="invoice-footer-message-1" name="invoice-footer-message-1"
                                   placeholder="" required>
                        </div>
                        <div class="col-md-12">
                            <label for="invoice-footer-message-2">Invoice Footer Message 2:</label>
                            <input type="text" class="location-input form-control" id="invoice-footer-message-2" name="invoice-footer-message-2"
                                   placeholder="" required>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 20px; padding:0;">
                        <div class="col-md-12">
                            <label for="quote-footer-message-2">Quote Footer Message 1:</label>
                            <input type="text" class="location-input form-control" id="quote-footer-message-1" name="quote-footer-message-1"
                                   placeholder="" required>
                        </div>
                        <div class="col-md-12">
                            <label for="quote-footer-message-2">Quote Footer Message 2:</label>
                            <input type="text" class="location-input form-control" id="quote-footer-message-2" name="quote-footer-message-2"
                                   placeholder="" required>
                        </div>
                    </div>
                    <button type="button" class="profile-btn-snd close-animatedModal"
                            style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas">
                        Close
                    </button>
                    <button type="button" onclick="addLocation()" class="profile-btn savebut" style="border: none;">Save
                    </button>
                    <button type="button" style="display: none; border: none;" onclick="updateLocation()"
                            class="profile-btn updatebut">Update
                    </button>

                </div>
            </div>
        </div>

    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample2" aria-labelledby="offcanvasExampleLabe2"
         style="width: 600px;">
        <div class="offcanvas-header">
            <h4 style="margin-top:0;font-size: 30px;" class="offcanvas-title" id="modaltitle1">
                CSV Column Mapping
            </h4>
        </div>

        <div class="offcanvas-body">
            <div id="message"></div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Select CSV File</h3>
                </div>
                <div class="panel-body">
                    <div class="row" id="upload_area">
                        <form method="post" id="upload_form" enctype="multipart/form-data">
                            <div class="col-md-12" align="center">
                                <input type="file" name="file" id="csv_file"/>
                            </div>
                            <br/>
                        </form>

                    </div>
                    <div class="table-responsive" id="process_area">

                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="offcanvas">Close</button>
            <button type="button" name="import" id="import" class="btn btn-success rounded-0" style="display:none;">
                Import
            </button>
            <button type="button" name="upload_file" id="upload_file" class="btn btn-success rounded-0">Upload</button>
        </div>
    </div>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbkJwc1PP_We_Ro8wA1FNjTSK7ntj1T8U&libraries=places&callback=initAutocomplete"
        async defer></script>
    <script src="{{ asset('/js/location/index.js') }}"></script>
@endsection
