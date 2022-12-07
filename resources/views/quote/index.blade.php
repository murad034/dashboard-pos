@extends('layouts.AdminLTE.index')

@section('icon_page', 'fa fa-undo')

@section('title', 'Invoicing & Quotes')


@section('content')
    <link rel="stylesheet" href="{{ asset('/js/quotes/css/style.css') }}">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped display responsive nowrap">
                        <thead style="text-align:left;">
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Type</th>
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
         style="width: 100%;">
        <div class="offcanvas-header">
            <h4 style="margin-top:0px;font-size: 30px;" class="offcanvas-title" id="temptitle">
                New Invoice
            </h4>
        </div>
        <form id="quotesForm">
            <div class="offcanvas-body">
                <div class="row">
                    <div class="col-md-2">
                            <label for="quote" class="form-label">Type</label>
                            <select class="form-control" id="quote" name="quote"
                                    placeholder="Select Type" >
                                <option value="sale">Sale</option>
                                <option value="quote">Quote</option>
                            </select>
                    </div>
                    <div class="col-sm-2">
                        <label for="Store" class="form-label">
                            Store <i class="" title="Store"></i>
                        </label>
                        <select class="form-control selectTag" id="store" name="store"
                                placeholder="Select Store">
                            <option value="">Select</option>
                            @if(!empty($storelist))
                                @foreach ($storelist as $store)
                                    <option value="{{ $store->locationid }}">
                                        {{ $store->locationname }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="quote" class="form-label">Frequency</label>
                        <select class="form-control" id="frequency" name="frequency"
                                placeholder="Select Frequency" >
                            <option value="">Select Frequency</option>
                            <option value="one_time" selected>One Time</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="3_monthly">3 Monthly</option>
                            <option value="6_monthly">6 Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="col-sm-4"></div>
                    <div class="col-sm-2">
                        <div>
                            BALANCE DUE
                            <button type="button" class="btn btn-success ml-2">Receive Payment</button>
                        </div>
                        <h2>
                            <strong id="balanceDue">A$0.00</strong>
                        </h2>
                    </div>
                </div>
                {{-- <div class="w-100"> &nbsp; </div>
                <div class="row">
                    <div class="col-md-2">
                        <div id="mark-paid-div" class="form-check">
                            <input class="form-check-input" type="checkbox" name="mark-paid" checked id="mark-paid">
                            <label class="form-check-label" for="mark-paid">
                                Mark as Paid <i class="fa fa-question-circle" title="Mark as Paid"></i>
                            </label>
                        </div>
                    </div>
                </div> --}}
                <div class="w-100"> &nbsp; </div>
                <div class="w-100"> &nbsp; </div>
                <div class="row">
                    <div class="col-sm-3">
                        <label for="customer" class="form-label">
                            Customer <i class="" title="Customer"></i>
                        </label>
                        <select class="form-control selectTag" id="customer" name="customer"
                                placeholder="Select Customer" >
                            <option value="no">Select</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->customerid }}">
                                    {{ $customer->customername }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="customer-email" class="form-label">
                            Customer email <i class="" title="Customer email"></i>
                        </label>
                        <input class="form-control" id="customer-email" name="customer-email"/>

                        <div id="send-email-div" class="form-check">
                            <input class="form-check-input" type="checkbox" name="send-email-customer" id="send-email-customer">
                            <label class="form-check-label" for="send-email-customer">
                                Send to Customer <i class="" title="Send Email to Customer"></i>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        {{-- <div class="lead">Accept Card Payments with PayPal :--}}
                        {{--     <img src="../../dist/img/credit/visa.png" alt="Visa">--}}
                        {{--     <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">--}}
                        {{--     <img src="../../dist/img/credit/american-express.png" alt="American Express">--}}
                        {{--     <img src="../../dist/img/credit/paypal2.png" alt="Paypal">--}}
                        {{-- </div>--}}
                        <label for="billing-address" class="form-label">
                            Billing address
                        </label>
                        <textarea class="form-control" id="billing-address" name="billing-address" rows="4"></textarea>
                    </div>
                    <div class="col-sm-3">
                        <label for="shipping-to" class="form-label">
                            Shipping to
                        </label>
                        <textarea class="form-control" id="shipping-to" name="shipping-to" rows="4"></textarea>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-2 offset-md-2">
                        <div id="mark-paid-div" class="form-check">
                            <input class="form-check-input" type="checkbox" name="send-email-customer" id="send-email-customer">
                            <label class="form-check-label" for="send-email-customer">
                                Send to Customer <i class="fa fa-question-circle" title="Send Email to Customer"></i>
                            </label>
                        </div>
                    </div>
                </div> --}}
                <div class="w-100"> &nbsp; </div>
                <div class="w-100"> &nbsp; </div>
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="row">
                            {{-- <div class="col-sm-6">
                                <label for="billing-address" class="form-label">
                                    Billing address
                                </label>
                                <textarea class="form-control" id="billing-address" name="billing-address" rows="4"></textarea>
                            </div> --}}
                            {{-- <div class="col-sm-6">
                                <label for="shipping-to" class="form-label">
                                    Shipping to
                                </label>
                                <textarea class="form-control" id="shipping-to" name="shipping-to" rows="4"></textarea>
                            </div> --}}
                            <div class="w-100"> &nbsp; </div>
                            {{-- <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <label for="pay-me" class="form-label">
                                        Pay me
                                    </label>
                                    <input type="text" class="form-control" id="pay-me" name="pay-me"/>
                                    <small id="pay-me-help" class="form-text"> <i>Not printed on form</i>  </small>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <label for="terms" class="form-label">
                            Terms <i class="" title="Terms"></i>
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
                    <div class="col-sm-2">
                        <label for="invoice-date" class="form-label">
                            Invoice date
                        </label>
                        <input type="text" class="form-control" id="invoice-date" name="invoice-date"/>
                    </div>
                    <div class="col-sm-2">
                        <label for="due-date" class="form-label">
                            Due date
                        </label>
                        <input type="date" class="form-control" id="due-date" name="due-date"/>
                    </div>
                    <div class="col-sm-2">
                        <label for="ship-via" class="form-label">
                            Ship via
                        </label>
                        <input type="text" class="form-control" id="ship-via" name="ship-via"/>
                    </div>
                    <div class="col-sm-2">
                        <label for="shipping-date" class="form-label">
                            Shipping date
                        </label>
                        <input type="date" class="form-control" id="shipping-date" name="shipping-date"/>
                    </div>
                    <div class="col-sm-2">
                        <label for="tracking-no" class="form-label">
                            Tracking no.
                        </label>
                        <input type="text" class="form-control" id="tracking-no" name="tracking-no"/>
                    </div>
                </div>
                <div class="w-100"> &nbsp; </div>
                {{-- <div class="w-100"> &nbsp; </div>--}}
                {{-- <div class="w-100"> &nbsp; </div>--}}
                {{-- <div class="row">--}}
                {{--     <div class="col-sm-12 col-md-6">--}}
                {{--         <label for="tags" class="form-label d-flex justify-content-between">--}}
                {{--             <div>--}}
                {{--                 Tags <i class="fa fa-question-circle" title="Tags"></i>--}}
                {{--             </div>--}}

                {{--             <a href="javascript:void(0)" class=""> Manage tags </a>--}}
                {{--         </label>--}}
                {{--         <input type="text" class="form-control" id="tags" name="tags"/>--}}
                {{--     </div>--}}
                {{-- </div>--}}
                <div class="w-100"> &nbsp; </div>
                <div class="w-100"> &nbsp; </div>
                <div class="row">
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2">

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 pt-3" style="background: #fff">
                        <div class="d-flex justify-content-end mb-3">
{{--                            <label for="inclusive_tax" class="form-label mt-1" style="margin-right: 5px">--}}
{{--                                Amounts are--}}
{{--                            </label>--}}
{{--                            <select class="form-control" id="inclusive_tax" name="inclusive_tax" style="width: 200px;">--}}
{{--                                <option value="">Inclusive of Tax</option>--}}
{{--                            </select>--}}
                        </div>

                        <table class="table table-bordered table-responsive" id="invoice-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>#</th>
                                    <th>SERVICE DATE</th>
                                    <th>PRODUCT/SERVICE <i class="" title="PRODUCT/SERVICE"></i> </th>
                                    <th>DESCRIPTION</th>
                                    <th>QTY</th>
                                    <th>RATE</th>
                                    <th>AMOUNT</th>
                                    <th>GST</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="col-sm-12 pt-3 pb-3" style="background: #fff">
                        <div class="float-end">


                            <table style="text-align:right; width:450px;">
                                <tr>
                                    <td><b>Subtotal</b></td>
                                    <td class="p-1">&nbsp;</td>
                                    <td><b id="subTotal">A$0.00</b></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="number" class="form-control" name="discount_per" id="discount_per" min="0" step="0.1" style="width: 70px;float: right;"/>
                                        {{-- <select class="form-control" style="width: 140px;float: right;">
                                            <option value="">Discount percent</option>
                                        </select> --}}
                                        <label for="discount_per" style="width:140px; float:right; padding:6px;" class="border">Discount percent</label>
                                    </td>
                                    <td class="p-1">&nbsp;</td>
                                    <td>
                                        <b id="subTotalPercent">A$0.00</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Shipping
                                        <select class="form-control d-inline-block" id="shipping_tax" name="shipping_tax" style="width: 210px;">
                                            <option value="">Select Shipping tax</option>
                                            <option value="gst">GST</option>
                                            <option value="gst_free">GST FREE</option>
                                        </select>
                                    </td>
                                    <td class="p-1">&nbsp;</td>
                                    <td>
                                        <input type="number" class="form-control d-inline-block" id="shipping_cost" name="shipping_cost" min="0" step="0.1" style="width: 100px;"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>GST</b></td>
                                    <td class="p-1">&nbsp;</td>
                                    <td><b id="gstValue">A$0.00</b></td>
                                </tr>
                                <tr>
                                    <td><b>Total</b></td>
                                    <td class="p-1">&nbsp;</td>
                                    <td><b id="total">A$0.00</b></td>
                                </tr>
                            </table>
                        </div>
                        <div class="float-start">
                            <button type="button" class="btn btn-secondary" id="addLine">Add lines</button>
                            <button type="button" class="btn btn-secondary" id="clearAllLines">Clear all lines</button>
                            {{--<button type="button" class="btn btn-secondary">Add subtotal</button>--}}
                            <br>
                            <br>
                            <label for="message-invoice" class="form-label">
                                Message on invoice
                            </label>
                            <textarea class="form-control" id="message-invoice" name="message-invoice" rows="4"></textarea>
                            {{-- <div class="w-100"> &nbsp; </div>

                            <label for="message-statement" class="form-label">
                                Message on statement
                            </label>
                            <textarea class="form-control" id="message-statement" name="message_statement" rows="4"></textarea> --}}
                        </div>
                    </div>
                </div>

                <button type="button" class="profile-btn-snd close-animatedModal"
                        style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas">
                    Close
                </button>
                <button type="button" onclick="addQuotes()" class="profile-btn savebut" id="saveBtn" style="border: none;">
                    Save Invoice </button>
                <button type="button" style="display: none; border: none;" onclick="updateQuotes()" class="profile-btn updatebut">
                    Update </button>

            </div>
        </form>
    </div>
<script>
    let customerList = @json($customers);
    let storelist = @json($storelist);
</script>

<script src="{{ asset('/js/quotes/index.js?v=1.0') }}"></script>
<link rel="stylesheet" href="{{ asset('/js/quotes/css/style.css?v=1.0') }}">
@endsection

@section('layout_js')

    <!-- RowReorder adds the ability for rows in a DataTable to be reordered through user interaction -->
    <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css">
    <script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.12.1/api/sum().js"></script>

    @if(session('error'))
        <script>
            $(document).ready(function () {
                toastr.error("{{ session('error') }}", "Error", {
                    timeOut: 3000,
                });
            })
        </script>
    @endif

@endsection
