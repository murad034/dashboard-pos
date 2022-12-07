@extends('layouts.AdminLTE.index')

@section('icon_page', 'user-friends')

@section('title', 'Customers')


@section('content')
    <style>
        .pac-container {
            z-index: 10000 !important;
        }
        .nab-total-sales{
            float: right;
            margin-top: 11px;
            margin-right: 30px;
            font-weight: bold;
        }
    </style>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped display responsive nowrap">
                        <thead style="text-align:left;">
                        <tr>
                            <th>Customer Id</th>
                            <th>Customer Name</th>
                            <th>Customer Email</th>
                            <th>Phone Number</th>
                            <th>Current Points</th>
                            <th>Balance</th>
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

    <div class="offcanvas offcanvas-start" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel"
         style="width: 100%;">
        <div class="nav-tabs nab-total-sales">Total Sales
            <br>
            $<span class="total-sales-value">0.00</span>
        </div>
        <div class="nav-tabs nab-total-sales">Account Balance
            <br>
            $<span class="account-balance-value">0.00</span>
        </div>
        <div class="nav-tabs nab-total-sales">Points
            <br>
            <span class="customer-points-value">0</span>
        </div>

        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist" style="margin-top:18px;">
                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                        type="button" role="tab" aria-controls="nav-home" aria-selected="true">General
                </button>
                <button class="nav-link" id="nav-history-tab" data-bs-toggle="tab" data-bs-target="#nav-history"
                        type="button" role="tab" aria-controls="nav-history" aria-selected="false">Purchase History
                </button>
                <button class="nav-link" id="nav-address-tab" data-bs-toggle="tab" data-bs-target="#nav-address"
                        type="button" role="tab" aria-controls="nav-address" aria-selected="false">Billing/Shipping address
                </button>
                <button class="nav-link" id="nav-payments-tab" data-bs-toggle="tab" data-bs-target="#nav-payments"
                        type="button" role="tab" aria-controls="nav-payments" aria-selected="false">Payments
                </button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="offcanvas-header">
                    <h4 style="margin-top:0;font-size: 30px;" class="offcanvas-title" id="temptitle">
                        New Customer
                    </h4>
                </div>
                <form id="customerForm">
                    <div class="offcanvas-body">
                        <div class="row" style="margin-bottom: 20px; padding:0;">
                            <div class="col-md-6">
                                <label for="customerfirstname">First Name :</label>
                                <input type="text"
                                       class="customer-input form-control"
                                       id="customerfirstname"
                                       name="customerfirstname"
                                       placeholder="First Name"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label for="customerlastname">Last Name :</label>
                                <input type="text"
                                       class="customer-input form-control"
                                       id="customerlastname"
                                       name="customerlastname"
                                       placeholder="Last Name"
                                       required>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 20px;padding:0;">
                            <div class="col-md-6">
                                <label for="dob">Birthday :</label>
                                <input type="date" class="form-control docs-date" id="dob" name="dob"
                                       placeholder="Birthday" autocomplete="off" required>
                            </div>
                            <div class="col-md-6">
                                <label for="mobile">Mobile Number :</label>
                                <input type="text" class="customer-input form-control" id="mobile" name="mobile"
                                       placeholder="Customer Mobile Number" required>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 20px;padding:0;">
                            <div class="col-md-6">
                                <label for="email">Email :</label>
                                <input type="email"
                                       class="customer-input form-control" id="email"
                                       name="email" placeholder="Customer EMail"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label for="gender">Gender :</label>
                                <select class="form-control customer-input"
                                        id="gender" name="gender">
                                    <option value="Male">
                                        Male
                                    </option>
                                    <option value="Female">
                                        Female
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 20px;padding:0;display: none">
                            <div class="col-md-6">
                                <label for="customerpoints">Current Points :</label>
                                <input type="text"
                                       class="customer-input form-control"
                                       id="customerpoints"
                                       name="customerpoints" value="0"
                                       placeholder="Customer Point">
                            </div>
                            <div class="col-md-6">
                                <label for="accountbal">Account Balance :</label>
                                <input type="text" class="customer-input form-control" value="0" id="accountbal" name="accountbal"
                                       placeholder="Account Balance">
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 20px;padding:0;">
                            <div class="col-md-12">
                                <label for="card_number">Members Card Number :</label>
                                <input type="text" class="customer-input form-control" id="card_number" name="card_number"
                                       placeholder="Members Card Number">
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 20px;padding:0;">
                            <div class="col-md-12">
                                <label for="allocatedtag">Allocated Tags :</label>
                                <select class="form-control select2" id="allocatedtag" name="allocatedtag"
                                        multiple="multiple" data-placeholder="Select a Tag" style="width: 100%;">
                                    @if(!empty($tags))
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->tagid }}">
                                                {{ $tag->tagname }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="no">No Tags</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <button type="button" class="profile-btn-snd close-animatedModal"
                                style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas">
                            Close
                        </button>
                        <button type="button" onclick="addCustomer()" class="profile-btn savebut" style="border: none;">
                            Save
                        </button>
                        <button type="button" style="display: none; border: none;" onclick="updateCustomer()"
                                class="profile-btn updatebut">Update
                        </button>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="nav-history" role="tabpanel" aria-labelledby="nav-history-tab">
                <div class="box">

                    <div class="box-body">
                        <table id="historytable" class="table table-bordered table-striped display responsive nowrap"
                               style="width: 100%; overflow-x: scroll;">
                            <thead style="text-align:left;">
                            <tr>
                                <th>RecNum</th>
                                <th>Discount Total</th>
                                <th>GST Total</th>
                                <th>Location ID</th>
                                <th>Media Type</th>
                                <th>Sale Total</th>
                                <th>Staff Name</th>
                                <th>Terminal ID</th>
                            </tr>
                            </thead>
                            <tbody>

                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="tab-pane fade" id="nav-address" role="tabpanel" aria-labelledby="nav-address-tab">
                <div class="offcanvas-header">
                    <h4 style="margin-top:0;font-size: 30px;" class="offcanvas-title" id="temptitle">
                        Billing/Shipping address
                    </h4>
                </div>
                <div class="offcanvas-body">
                        <div class="row" style="margin-bottom: 20px;padding:0;">
                            <div class="col-md-12">
                                <label for="address">Billing Address :</label>
                                <input type="text" class="customer-input form-control" id="billingaddress" name="billingaddress"
                                       placeholder="Billing Address" onFocus="geolocate()">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>
                                    <input id="enable_billing_address" name="enable_billing_address" type="checkbox"></input>
                                    Same as Billing Address
                                </label>
                            </div>
                        </div>

                        <div class="row" style="margin-bottom: 20px;padding:0;">
                            <div class="col-md-12">
                                <label for="address">Shipping Address :</label>
                                <input type="text" class="customer-input form-control" id="shippingaddress" name="shippingaddress"
                                       placeholder="Shipping Address" onFocus="geolocate()">
                            </div>
                        </div>

                    <div class="row" style="margin-bottom: 20px;padding:0;">
                        <div class="col-md-12">
                            <label for="address">Billing Profile ID :</label>
                            <input type="text" disabled class="customer-input form-control" id="billingprofileid" name="billingprofileid"
                                   placeholder="Billing Profile ID">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 20px;padding:0;">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary" style="border: none;">
                                Payment Detail Request <sup>COOMING SOON</sup>
                            </button>
                        </div>
                    </div>

                        <button type="button" class="profile-btn-snd close-animatedModal"
                                style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas">
                            Close
                        </button>
                        <button type="button" onclick="addCustomer()" class="profile-btn savebut" style="border: none;">
                            Save
                        </button>
                        <button type="button" style="display: none; border: none;" onclick="updateCustomer()"
                                class="profile-btn updatebut">Update
                        </button>
                    </div>
            </div>
            <div class="tab-pane fade" id="nav-payments" role="tabpanel" aria-labelledby="nav-payments-tab">
                <div class="offcanvas-header">
                    <h4 style="margin-top:0;font-size: 30px;" class="offcanvas-title" id="temptitle">
                        Payments
                    </h4>
                </div>
                <div class="offcanvas-body">
                    <div class="row" style="margin-bottom: 20px;padding:0;">
                        <div class="col-sm-6">
                            <label for="terms" class="form-label">
                                Terms <i class="fa fa-question-circle" title="Terms"></i>
                            </label>
                            <select class="form-control" id="terms" name="terms"
                                    placeholder="Select terms">
                                <option value="">Select</option>
                                <option value="cod">COD</option>
                                <option value="7_day">7 Days</option>
                                <option value="15_day">15 Days</option>
                                <option value="30_day">30 Days</option>
                            </select>
                        </div>
                    </div>

                    <button type="button" class="profile-btn-snd close-animatedModal"
                            style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas">
                        Close
                    </button>
                    <button type="button" onclick="addCustomer()" class="profile-btn savebut" style="border: none;">
                        Save
                    </button>
                    <button type="button" style="display: none; border: none;" onclick="updateCustomer()"
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
    <script src="{{ asset('/js/customer/index.js') }}"></script>
@endsection
