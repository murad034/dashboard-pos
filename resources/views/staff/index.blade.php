@extends('layouts.AdminLTE.index')

@section('icon_page', 'users')

@section('title', 'Staff')


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
                            <th>Avatar</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Terminal Name</th>
                            <th>Phone Number</th>
                            <th>Address</th>
                            <th>Next of Kin Name</th>
                            <th>Next of Kin Phone</th>
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
        <form id="staffForm">
            <div class="offcanvas-body">

                <div>
                    <div class="row" style="margin-bottom: 15px; padding:0;">
                        <div class="col-md-12">
                            <label for="staffimage">Staff Image :</label>
                            <input accept="image/*" type='file' id="staffimage" name="staffimage"/>
                            <img id="blah" src="#" style="display:none;" alt="staff image" width="100px"
                                 height="100px"/>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px; padding:0;">
                        <div class="col-md-6">
                            <label for="stafffirstname">First Name :</label>
                            <input type="text" class="staff-input form-control" id="stafffirstname"
                                   name="stafffirstname" placeholder="First Name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="stafflastname">Last Name :</label>
                            <input type="text" class="staff-input form-control" id="stafflastname" name="stafflastname"
                                   placeholder="Last Name" required>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px; padding:0;">
                        <div class="col-md-6">
                            <label for="terminaldisplayname">Name :</label>
                            <input type="text" class="staff-input form-control" id="terminaldisplayname"
                                   name="terminaldisplayname" placeholder="Terminal Display Name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phonenumber">Phone Number :</label>
                            <input type="text" class="staff-input form-control" id="phonenumber" name="phonenumber"
                                   placeholder="Phone Number">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px;padding:0;">
                        <div class="col-md-6">
                            <label for="staffpin">Pin :</label>
                            <input type="text" class="staff-input form-control" id="staffpin" name="staffpin"
                                   placeholder="Pin">
                        </div>
                        <div class="col-md-6">
                            <label for="department">Department :</label>
                            <input type="text" class="form-control docs-date" id="department" name="department"
                                   placeholder="Department">
                        </div>
                    </div>
                    {{--                    <div class="row" style="margin-bottom: 15px;padding:0;">--}}
                    {{--                        <div class="col-md-6">--}}
                    {{--                            <label for="status">Status :</label>--}}
                    {{--                            <select class="form-control customer-input" id="status" name="status">--}}
                    {{--                                <option value="active">--}}
                    {{--                                    Active--}}
                    {{--                                </option>--}}
                    {{--                                <option value="inactive">--}}
                    {{--                                    Deactive--}}
                    {{--                                </option>--}}
                    {{--                            </select>--}}
                    {{--                        </div>--}}
                    {{--                        <div class="col-md-6">--}}
                    {{--                            <label for="issalery">Is Salary :</label>--}}
                    {{--                            <select class="form-control customer-input" id="issalery" name="issalery">--}}
                    {{--                                <option value="yes">--}}
                    {{--                                    Yes--}}
                    {{--                                </option>--}}
                    {{--                                <option value="no">--}}
                    {{--                                    No--}}
                    {{--                                </option>--}}
                    {{--                            </select>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    <div class="row" style="margin-bottom: 15px;padding:0;">
                        <div class="col-md-6">
                            <label for="payrateperhour">Pay Rate Per Hour :</label>
                            <input type="text" class="staff-input form-control" id="payrateperhour"
                                   name="payrateperhour" placeholder="Pay rate per hour">
                        </div>
                        <div class="col-md-6">
                            {{--                            <label for="salery">Salary :</label>--}}
                            {{--                            <input type="text" class="form-control docs-date" id="salery" name="salery"--}}
                            {{--                                   placeholder="salary">--}}
                            <label for="taxfilenumber">Tax File Number :</label>
                            <input type="text" class="form-control docs-date" id="taxfilenumber" name="taxfilenumber"
                                   placeholder="Tax File Number">

                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px;padding:0;">
                        <div class="col-md-12">
                            <label for="staffnotes">Notes :</label>
                            <input type="text" class="staff-input form-control" id="staffnotes" name="staffnotes"
                                   placeholder="Notes">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px;padding:0;">
                        <div class="col-md-6">
                            <label for="nextofkinname">Next of Kin Name :</label>
                            <input type="text" class="staff-input form-control" id="nextofkinname" name="nextofkinname"
                                   placeholder="Next Kin Name">
                        </div>
                        <div class="col-md-6">
                            <label for="nextofkinphone">Next of Kin Phone :</label>
                            <input type="text" class="staff-input form-control" id="nextofkinphone"
                                   name="nextofkinphone" placeholder="Next Kin Phone">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px;padding:0;">
                        <div class="col-md-12">
                            <label for="address">Address :</label>
                            <input type="text" class="staff-input form-control" id="address" name="address"
                                   placeholder="Address">
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
                    <div class="row" style="margin-bottom: 15px;padding:0;">
                        <div class="col-md-6">
                            <label for="availmonday">Available Monday :</label>
                            <select class="form-control customer-input" id="availmonday" name="availmonday">
                                <option value="yes">
                                    Yes
                                </option>
                                <option value="no">
                                    No
                                </option>
                                <option value="custom">
                                    Custom
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6" style="visibility: hidden" id="div-monday">
                            <label for="custommonday">Custom Monday :</label>
                            <input type="text" class="staff-input form-control" id="custommonday" name="custommonday"
                                   placeholder="">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px;padding:0;">
                        <div class="col-md-6">
                            <label for="availtuesday">Available Tuesday :</label>
                            <select class="form-control customer-input" id="availtuesday" name="availtuesday">
                                <option value="yes">
                                    Yes
                                </option>
                                <option value="no">
                                    No
                                </option>
                                <option value="custom">
                                    Custom
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6" style="visibility: hidden" id="div-tuesday">
                            <label for="customtuesday">Custom Tuesday :</label>
                            <input type="text" class="staff-input form-control" id="customtuesday" name="customtuesday"
                                   placeholder="">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px;padding:0;">
                        <div class="col-md-6">
                            <label for="availwednesday">Available Wednesday :</label>
                            <select class="form-control customer-input" id="availwednesday" name="availwednesday">
                                <option value="yes">
                                    Yes
                                </option>
                                <option value="no">
                                    No
                                </option>
                                <option value="custom">
                                    Custom
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6" style="visibility: hidden" id="div-wednesday">
                            <label for="customwednesday">Custom Wednesday :</label>
                            <input type="text" class="staff-input form-control" id="customwednesday"
                                   name="customwednesday" placeholder="">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px;padding:0;">
                        <div class="col-md-6">
                            <label for="availthursday">Available Thursday :</label>
                            <select class="form-control customer-input" id="availthursday" name="availthursday">
                                <option value="yes">
                                    Yes
                                </option>
                                <option value="no">
                                    No
                                </option>
                                <option value="custom">
                                    Custom
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6" style="visibility: hidden" id="div-thursday">
                            <label for="customthursday">Custom Thursday :</label>
                            <input type="text" class="staff-input form-control" id="customthursday"
                                   name="customthursday" placeholder="">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px;padding:0;">
                        <div class="col-md-6">
                            <label for="availfriday">Available Friday :</label>
                            <select class="form-control customer-input" id="availfriday" name="availfriday">
                                <option value="yes">
                                    Yes
                                </option>
                                <option value="no">
                                    No
                                </option>
                                <option value="custom">
                                    Custom
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6" style="visibility: hidden" id="div-friday">
                            <label for="customfriday">Custom Friday :</label>
                            <input type="text" class="staff-input form-control" id="customfriday" name="customfriday"
                                   placeholder="">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px;padding:0;">
                        <div class="col-md-6">
                            <label for="availsaturday">Available Saturday :</label>
                            <select class="form-control customer-input" id="availsaturday" name="availsaturday">
                                <option value="yes">
                                    Yes
                                </option>
                                <option value="no">
                                    No
                                </option>
                                <option value="custom">
                                    Custom
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6" style="visibility: hidden" id="div-saturday">
                            <label for="customsaturday">Custom Saturday :</label>
                            <input type="text" class="staff-input form-control" id="customsaturday"
                                   name="customsaturday" placeholder="">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px;padding:0;">
                        <div class="col-md-6">
                            <label for="availsunday">Available Sunday :</label>
                            <select class="form-control customer-input" id="availsunday" name="availsunday">
                                <option value="yes">
                                    Yes
                                </option>
                                <option value="no">
                                    No
                                </option>
                                <option value="custom">
                                    Custom
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6" style="visibility: hidden" id="div-sunday">
                            <label for="customsunday">Custom Sunday :</label>
                            <input type="text" class="staff-input form-control" id="customsunday" name="customsunday"
                                   placeholder="">
                        </div>
                    </div>

                </div>
                <button type="button" class="profile-btn-snd close-animatedModal"
                        style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas">
                    Close
                </button>
                <button type="button" onclick="addStaff()" class="profile-btn savebut" style="border: none;">Save
                </button>
                <button type="button" style="display: none; border: none;" id="update-staff"
                        class="profile-btn updatebut">Edit
                </button>


            </div>
        </form>
    </div>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbkJwc1PP_We_Ro8wA1FNjTSK7ntj1T8U&libraries=places&callback=initAutocomplete"
        async defer></script>
    <script src="{{ asset('/js/staff/index.js') }}"></script>
@endsection
