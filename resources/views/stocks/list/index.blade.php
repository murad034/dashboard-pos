@extends('layouts.AdminLTE.index')

@section('icon_page', 'clipboard-list')

@section('title', 'Stock')


@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped display responsive nowrap">
                        <thead style="text-align:left;">
                        <tr>
                            <th>PLU</th>
                            <th>Name</th>
                            <th>Barcode</th>
                            <th>Main Category</th>
                            <th>Sub Category</th>
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
    <!--MODAL WINDOW-->

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel"
         style="width: 900px;">
        <div class="offcanvas-header">
            <h4 style="margin-top:0px;font-size: 30px;" class="offcanvas-title" id="temptitle">
                New Stock
            </h4>
            <!--            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>-->
        </div>
        <div class="offcanvas-body">
            <div class="row" style="margin-bottom: 20px;padding:0px;">
                <div class="col-md-12">
                    <input type="text" class="stock-input form-control" id="stockname" placeholder="Stock Name"
                           required></div>
            </div>
            <div class="row" style="margin-bottom: 20px;padding:0px;">
                <div class="col-md-12">
                    <input type="text" class="stock-input form-control" id="barcode" placeholder="Barcode" required>
                </div>
            </div>

            <div class="row" style="margin-bottom: 20px;padding:0px;">
                <div class="col-md-6">
                    <select class="form-control stock-input" id="maincat">
                        <option value="no">Main Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->catid }}">
                                {{ $category->catagoryname }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <select class="form-control stock-input" id="subcat">
                        <option value="no">Sub Categories</option>
                        @foreach ($subCategories as $subCategory)
                            <option value="{{ $subCategory->subcatid }}">
                                {{ $subCategory->subcatagoryname }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row" style="margin-bottom: 20px;padding:0px;">
                <div class="col-md-6">
                    <select class="form-control stock-input" id="stockoption">
                        <option value="each">
                            Each
                        </option>
                        <option value="mls">
                            MLs
                        </option>
                        <option value="kgs">
                            KGs
                        </option>
                        <option value="grams">
                            Grams
                        </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" class="stock-input form-control" id="unitval" placeholder="Pack Size"></div>
            </div>
            <div class="row" style="margin-bottom: 20px;padding:0;">
                <div class="col-md-12">
                    <label for="allocated-supplier">Allocated Suppliers :</label>
                    <select class="form-control select2" id="allocated-supplier" name="allocated-supplier"
                            multiple="multiple" data-placeholder="Select a Tag" style="width: 100%;">
                        @if(!empty($suppliers))
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->supplier_id }}">
                                    {{ $supplier->supplier_name }}
                                </option>
                            @endforeach
                        @else
                            <option value="no">No Suppliers</option>
                        @endif
                    </select>
                </div>
            </div>
            <h4 style="margin-top:0px;">
                Pricing
            </h4>
            <div class="row" style="margin-bottom: 20px;padding:0px;"></div>
            <div id="content-4" style="
                    Width: 100%;
                    font-size: 12px;
                    border: 0px;
                    margin-bottom: 20px;
                    height:400px;
                    margin-top: -15px;
                    overflow-x:hidden;
                    background-color: #fff;
                  padding-right: 10px;">
                <table id="exampletwo" class="table table-bordered table-striped display responsive nowrap">
                    <thead style="text-align:left;">
                    <tr>
                        <th></th>
                        <th style=" font-size: 12px;">Price</th>
                        <th style=" font-size: 12px;">Reorder Limit</th>
                        <th style=" font-size: 12px;">Stock On Hand</th>
                    </tr>
                    </thead>
                    <th style=" font-size: 12px;">BASE Price/SOH</th>
                    <td>$
                        <input id="baseprice" class="stock-input" placeholder="Base Price" value="0.00"
                               style="width: 75%;"></input>
                        <i title='Copy to all' onclick="copyprice()" class="fa fa-copy"></i>
                    </td>
                    <td>
                        <input id="basereorder" class="stock-input" placeholder="Base Reorder" value="0.00"
                                                       style="width: 75%;"></input>
                        <i title='Copy to all' onclick="copyreorder()" class="fa fa-copy"></i>
                    </td>
                    <td>
                        {{--                        <input id="baseqty" class="stock-input" placeholder="Base QTY" value="0.00"--}}
                        {{--                               style="width: 75%;"></input>--}}
                        {{--                        <i title='Copy to all' onclick="copyqty()" class="fa fa-copy"></i>--}}
                        To edit SOH please use the receive stock screen.
                    </td>
                    @if(!empty($locations))
                        @foreach ($locations as $location)
                            <tr class="details">
                                <th style=" font-size: 12px;">
                                    {{ $location->locationname }}
                                </th>
                                <td>$
                                    <input class="stockprice" data-id="{{ $location->locationid }}" placeholder="Price"
                                           value="0.00" style="width: 85%;"></input>
                                </td>
                                <td>
                                    <input class="stockreorder" data-id="{{ $location->locationid }}" placeholder="Reorder Limit"
                                           value="0.00" style="width: 85%;"></input>
                                </td>
                                <td>
                                    <input disabled class="stockqty" data-id="{{ $location->locationid }}"
                                           placeholder="QTY" value="0.00" style="width: 85%;"></input>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
            <button type="button" class="profile-btn-snd close-animatedModal" style="border: none;margin-bottom: 50px;"
                    data-bs-dismiss="offcanvas"> Close
            </button>
            <button type="button" onclick="saveStock()" class="profile-btn savebut" style="border: none;">Save Stock
            </button>
            <button type="button" style="display: none; border: none;" onclick="updateStock()"
                    class="profile-btn updatebut">Update Stock
            </button>
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

    <script src="{{ asset('/js/stocks/list/index.js') }}"></script>
@endsection
