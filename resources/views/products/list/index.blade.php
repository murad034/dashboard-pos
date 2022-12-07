@extends('layouts.AdminLTE.index')

@section('icon_page', 'clipboard-list')

@section('title', 'Product')

@section('content')
    <link rel="stylesheet" href="{{ asset('/assets/plugins/cropper/cropper.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/plugins/richText/richtext.min.css') }}">
    <style>
        .label {
            cursor: pointer;
        }

        .img-container img {
            max-width: 100%;
        }

    </style>

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
    </div>

    <div class="offcanvas offcanvas-start" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel"
         style="width: 1100px;">
        <ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-top:18px;">
            <li class="nav-item" role="home">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                        role="tab" aria-controls="home" aria-selected="true">General
                </button>
            </li>
            <li class="nav-item" role="builder">
                <button class="nav-link" id="builder-tab" data-bs-toggle="tab" data-bs-target="#builder" type="button"
                        role="tab" aria-controls="builder" aria-selected="false" style="display: none;">Cost Builder
                </button>
            </li>
            <li class="nav-item" role="information">
                <button class="nav-link" id="information-tab" data-bs-toggle="tab" data-bs-target="#information"
                        type="button" role="tab" aria-controls="information" aria-selected="false">Web Information
                </button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="offcanvas-header">
                    <h4 style="margin-top:0px;font-size: 30px;" class="offcanvas-title" id="temptitle">
                        New Product
                    </h4>
                    <!--            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>-->
                </div>
                <div class="offcanvas-body">
                    <div class="row" style="margin-bottom: 20px;padding:0px;">
                        <div class="col-md-12">
                            <input type="text" class="product-input form-control" id="productname"
                                   placeholder="Product Name"></div>
                    </div>
                    <div class="row" style="margin-bottom: 20px;padding:0px;">
                        <!--                        <div class="col-md-6">-->
                        <!--                            <input type="text" class="product-input form-control" id="plu" value="" style="display: none;"> </div>-->
                        <div class="col-md-4">
                            <input type="text" class="product-input form-control" id="barcode" placeholder="Barcode">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="product-input form-control" id="barcode1" placeholder="Barcode1">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="product-input form-control" id="barcode2" placeholder="Barcode2">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 20px;padding:0px;">
                        <div class="col-md-6">
                            <select class="form-control product-input" id="maincat">
                                <option value="no">Main Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->catid }}">
                                        {{ $category->catagoryname }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control product-input" id="subcat">
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
                        <div class="col-md-3 text-center my-auto"> GST Free
                            <input type="checkbox" class="checkgst" id="gstfreebox">
                            <input type="hidden" class="product-input gst" id="gstfree" value="0">
                        </div>
                        <div class="col-md-3 text-center my-auto"> Modifier
                            <input type="checkbox" class="checkmod" id="ismodbox">
                            <input type="hidden" class="product-input mod" id="ismod" value="0">
                        </div>
                        <div class="col-md-3 text-center my-auto"> Is KG
                            <input type="checkbox" class="checkkg" id="iskgbox">
                            <input type="hidden" class="product-input mod" id="iskg" value="0">
                        </div>
                        <div class="col-md-3 text-center my-auto">
                            <label class="label" data-bs-toggle="tooltip" title="Change image">
                                <img class="rounded" id="pos-image" name="avatar1"
                                     src="https://avatars0.githubusercontent.com/u/3456749?s=160" alt="avatar1"
                                     style="width: 160px; height: 160px;">
                                <input type="file" class="sr-only" id="pos-input" name="image" accept="image/*">

                            </label>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 20px;padding:0px;">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="builder_available" name="builder_available"
                                       value="off">
                                <label class="form-check-label" for="builder_available">Use Cost Builder</label>
                            </div>
                        </div>
                        <div class="col-md-6" id="allocate_field">
                            <label for="allocated_stock">Stock List : </label>
                            <select class="form-control" id="allocated_stock" style="width: 200px;">
                                <option value="0"> Select Stock</option>
                                @if(!empty($stockList))
                                    @foreach ($stockList as $stock)
                                        <option value="{{ $stock->sku }}">
                                            {{ $stock->stockname }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="no"> No Stock List</option>
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
                            <tr class="details">
                                <th style=" font-size: 12px;">BASE RRP/Cost</th>
                                <th class="text-center">
                                    <button class="btn btn-block btn-info btn-sm price-change" data-bs-toggle="popover"
                                            data-bs-html="true" data-bs-sanitize="false">SET Price</button>
                                </th>
                                <th class="text-center">
                                    <button class="btn btn-block btn-info btn-sm price-change" data-bs-toggle="popover"
                                            data-bs-html="true" data-bs-sanitize="false">SET Tier1</button>
                                </th>
                                <th class="text-center">
                                    <button class="btn btn-block btn-info btn-sm price-change" data-bs-toggle="popover"
                                            data-bs-html="true" data-bs-sanitize="false">SET Tier2</button>
                                </th>
                                <th class="text-center">
                                    <button class="btn btn-block btn-info btn-sm price-change" data-bs-toggle="popover"
                                            data-bs-html="true" data-bs-sanitize="false">SET Tier3</button>
                                </th>
                                <th class="text-center">
                                    <button class="btn btn-block btn-info btn-sm price-change" data-bs-toggle="popover"
                                            data-bs-html="true" data-bs-sanitize="false">SET Tier4</button>
                                </th>
                                <th class="text-center">
                                    <button class="btn btn-block btn-info btn-sm price-change" data-bs-toggle="popover"
                                            data-bs-html="true" data-bs-sanitize="false">SET Tier5</button>
                                </th>
                                <th class="text-center">
                                    <button class="btn btn-block btn-info btn-sm price-change" data-bs-toggle="popover"
                                            data-bs-html="true" data-bs-sanitize="false">SET Cost</button>
                                </th>
                                <th>
                                </th>
                            </tr>
                            <tr>
                                <th></th>
                                <th style=" font-size: 12px;">RRP</th>
                                <th style=" font-size: 12px;">Tier 1</th>
                                <th style=" font-size: 12px;">Tier 2</th>
                                <th style=" font-size: 12px;">Tier 3</th>
                                <th style=" font-size: 12px;">Tier 4</th>
                                <th style=" font-size: 12px;">Tier 5</th>
                                <th style=" font-size: 12px;">COST</th>
                                <th style=" font-size: 12px;">SOH</th>
                            </tr>
                            </thead>
                            @if(!empty($locations))
                                @foreach ($locations as $location)
                                    <tr class="details productrow">
                                        <th style=" font-size: 12px;">
                                            {{ $location->locationname }}
                                        </th>
                                        <td>$
                                            <input type="text" class="productprice"
                                                   data-id="{{ $location->locationid }}" placeholder="Price"
                                                   value="0.00" style="width: 85%;">
                                            <span class="cost"
                                                  style="color:red; display:block; text-align: center; font-weight: bold;">GP %</span>
                                        </td>
                                        <td>$
                                            <input type="text" class="producttier1"
                                                   data-id="{{ $location->locationid }}" placeholder="Price"
                                                   value="0.00" style="width: 85%;">
                                            <span class="cost"
                                                  style="color:red; display:block; text-align: center; font-weight: bold;">GP %</span>
                                        </td>
                                        <td>$
                                            <input type="text" class="producttier2"
                                                   data-id="{{ $location->locationid }}" placeholder="Price"
                                                   value="0.00" style="width: 85%;">
                                            <span class="cost"
                                                  style="color:red; display:block; text-align: center; font-weight: bold;">GP %</span>
                                        </td>
                                        <td>$
                                            <input type="text" class="producttier3"
                                                   data-id="{{ $location->locationid }}" placeholder="Price"
                                                   value="0.00" style="width: 85%;">
                                            <span class="cost"
                                                  style="color:red; display:block; text-align: center; font-weight: bold;">GP %</span>
                                        </td>
                                        <td>$
                                            <input type="text" class="producttier4"
                                                   data-id="{{ $location->locationid }}" placeholder="Price"
                                                   value="0.00" style="width: 85%;">
                                            <span class="cost"
                                                  style="color:red; display:block; text-align: center; font-weight: bold;">GP %</span>
                                        </td>
                                        <td>$
                                            <input type="text" class="producttier5"
                                                   data-id="{{ $location->locationid }}" placeholder="Price"
                                                   value="0.00" style="width: 85%;">
                                            <span class="cost"
                                                  style="color:red; display:block; text-align: center; font-weight: bold;">GP %</span>
                                        </td>
                                        <td>$
                                            <input type="text" class="productcost" data-id="{{ $location->locationid }}"
                                                   placeholder="Cost" value="0.00" style="width: 85%;">
                                        </td>
                                        <td>$
                                            <input disabled type="text" class="productsoh"
                                                   data-id="{{ $location->locationid }}" placeholder="SOH" value="0.00"
                                                   style="width: 85%;">
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                    <button type="button" class="profile-btn-snd close-animatedModal"
                            style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas"> Close
                    </button>
                    <button type="button" onclick="saveProduct()" class="profile-btn savebut" style="border: none;">Save
                        Product
                    </button>
                    <button type="button" style="display: none; border: none;" onclick="updateProduct()"
                            class="profile-btn updatebut">Update Product
                    </button>
                    <div class="spinner-border text-dark load-but" role="status"
                         style="display: none; margin-left:30px;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="builder" role="tabpanel" aria-labelledby="builder-tab">
                <div class="offcanvas-body">
                    <div class="box">
                        <div class="row" style="margin-bottom: 20px;padding:0px;">
                            <div class="col-md-12">
                                <button class="btn btn-primary" id="addRow"
                                        style="margin-top: 10px; margin-bottom: 10px;">Add Item
                                </button>
                                <button class="btn btn-primary" id="removeRow"
                                        style="margin-top: 10px; margin-bottom: 10px;">Remove Item
                                </button>
                                <button class="btn btn-primary" id="clearTable"
                                        style="margin-top: 10px; margin-bottom: 10px; float: right;">Clear Table
                                </button>
                            </div>

                            <div class="box-body table-responsive">
                                <table id="cost-builder"
                                       class="table table-bordered table-striped display responsive nowrap"
                                       style="width: 100%; overflow-x: scroll;">
                                    <thead style="text-align:left;">
                                    <tr>
                                        <th>Item</th>
                                        <th>QTY</th>
                                        <th>Unit Val</th>
                                        <th>Unit</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <button type="button" class="profile-btn-snd close-animatedModal"
                                style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas"> Close
                        </button>
                        <button type="button" onclick="calCost()" class="profile-btn" style="border: none;">Calculate
                            Cost
                        </button>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="information" role="tabpanel" aria-labelledby="information-tab">
                <div class="offcanvas-header">
                    <h4 style="margin-top:0px;font-size: 30px;" class="offcanvas-title" id="temptitle">
                        Web Information
                    </h4>

                </div>
                <div class="offcanvas-body">
                    <div class="row" style="margin-bottom: 20px;padding:0px;">
                        <div class="col-md-11">
                            <label for="webtitle">Title :</label>
                            <input type="text" class="product-input form-control" id="webtitle" name="webtitle"
                                   placeholder="Title">

                        </div>
                        <div class="col-md-1">
                            <i title='Copy from product title' onclick="copyTitle()" class="fa fa-copy"
                               style="padding-top:35px;"></i>
                        </div>

                    </div>
                    <div class="row" style="margin-bottom: 20px;padding:0px;">
                        <div class="col-md-12">
                            <label for="webdescription">Description :</label>
                            <section id="editor">
                                <textarea id="webdescription">
                                </textarea>
                            </section>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 20px;padding:0px;">
                        <div class="col-md-3">
                            <label for="avatar">Main Image :</label>
                            <div class="container">
                                <label class="label" data-bs-toggle="tooltip" title="Change image">
                                    <img class="rounded" id="avatar" name="avatar"
                                         src="https://avatars0.githubusercontent.com/u/3456749?s=160" alt="avatar"
                                         style="width: 160px; height: 160px;">
                                    <input type="file" class="sr-only" id="input" name="image" accept="image/*">
                                </label>

                            </div>
                        </div>
                        <div class="col-md-9">
                            <label for="avatar1">Gallery Images :</label>
                            <div class="container">
                                <label class="label" data-bs-toggle="tooltip" title="Change image">
                                    <img class="rounded" id="avatar1" name="avatar1"
                                         src="https://avatars0.githubusercontent.com/u/3456749?s=160" alt="avatar1"
                                         style="width: 160px; height: 160px;">
                                    <input type="file" class="sr-only" id="input1" name="image" accept="image/*">
                                </label>
                                <label class="label" data-bs-toggle="tooltip" title="Change image">
                                    <img class="rounded" id="avatar2" name="avatar2"
                                         src="https://avatars0.githubusercontent.com/u/3456749?s=160" alt="avatar2"
                                         style="width: 160px; height: 160px;">
                                    <input type="file" class="sr-only" id="input2" name="image" accept="image/*">
                                </label>
                                <label class="label" data-bs-toggle="tooltip" title="Change image">
                                    <img class="rounded" id="avatar3" name="avatar3"
                                         src="https://avatars0.githubusercontent.com/u/3456749?s=160" alt="avatar3"
                                         style="width: 160px; height: 160px;">
                                    <input type="file" class="sr-only" id="input3" name="image" accept="image/*">
                                </label>
                                <label class="label" data-bs-toggle="tooltip" title="Change image">
                                    <img class="rounded" id="avatar4" name="avatar4"
                                         src="https://avatars0.githubusercontent.com/u/3456749?s=160" alt="avatar4"
                                         style="width: 160px; height: 160px;">
                                    <input type="file" class="sr-only" id="input4" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 20px;padding:0px;">
                        <div class="col-md-12 table-responsive">
                            <table id="web-info" class="table table-bordered table-striped display responsive nowrap"
                                   style="width: 100%; overflow-x: scroll;">
                                <thead style="text-align:left;">
                                <tr>
                                    <th>Location Name</th>
                                    <th>Price on Web</th>
                                    <th>Available</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 20px;padding:0px;">
                        <div class="col-md-9">
                            <label for="webmapid">Mapped ID :</label>
                            <input type="text" class="product-input form-control" id="webmapid" name="webmapid"
                                   placeholder="Mapped ID">
                        </div>
                        <div class="col-md-3">
                            <button type="button" onclick="postServer()" style="margin-top:20px;"
                                    class="profile-btn">create
                            </button>
                        </div>
                    </div>
                    <button type="button" class="profile-btn-snd close-animatedModal"
                            style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas"> Close
                    </button>
                    <button type="button" onclick="saveProduct()" class="profile-btn savebut" style="border: none;">Save
                        Info
                    </button>
                    <button type="button" style="display: none; border: none;" onclick="updateProduct()"
                            class="profile-btn updatebut">Update Info
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
                    <div class="" id="process_area">

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

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">COPY FROM</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" style="padding-top: 5px;">
                        <div class="col-sm-6 text-center">RRP</div>
                        <div class="col-sm-6 text-center">
                            <button type="button" class="btn btn-dark"
                                    onclick="copyPriceToWeb('productprice')">copy
                            </button>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 5px;">
                        <div class="col-sm-6 text-center">TIER 1</div>
                        <div class="col-sm-6 text-center">
                            <button type="button" class="btn btn-dark"
                                    onclick="copyPriceToWeb('producttier1')">copy
                            </button>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 5px;">
                        <div class="col-sm-6 text-center">TIER 2</div>
                        <div class="col-sm-6 text-center">
                            <button type="button" class="btn btn-dark"
                                    onclick="copyPriceToWeb('producttier2')">copy
                            </button>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 5px;">
                        <div class="col-sm-6 text-center">TIER 3</div>
                        <div class="col-sm-6 text-center">
                            <button type="button" class="btn btn-dark"
                                    onclick="copyPriceToWeb('producttier3')">copy
                            </button>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 5px;">
                        <div class="col-sm-6 text-center">TIER 4</div>
                        <div class="col-sm-6 text-center">
                            <button type="button" class="btn btn-dark"
                                    onclick="copyPriceToWeb('producttier4')">copy
                            </button>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 5px;">
                        <div class="col-sm-6 text-center">TIER 5</div>
                        <div class="col-sm-6 text-center">
                            <button type="button" class="btn btn-dark"
                                    onclick="copyPriceToWeb('producttier5')">copy
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <section>
        <div id="PopoverContent" class="d-none">
            <div class="row" style="padding-top: 5px;">
                <div class="col-sm-6 text-center">
                    <input type="text" id="set_price_option" class="form-control" placeholder="Input Value" value="">
                    </input>
                </div>
                <div class="col-sm-6 text-center">
                    <button type="button" class="btn btn-block btn-dark"
                            onclick="copyBasePrice()">SET PRICE
                    </button>
                </div>
            </div>
            <div class="row" style="padding-top: 5px;">
                <div class="col-sm-6 text-center">
                    <button type="button" class="btn btn-block btn-dark"
                            onclick="setIncreasePercent()">INCREASE BY %
                    </button>
                </div>
                <div class="col-sm-6 text-center">
                    <button type="button" class="btn btn-block btn-dark"
                            onclick="setDecreasePercent()">DECREASE BY %
                    </button>
                </div>
            </div>
            <div class="row" style="padding-top: 5px;">
                <div class="col-sm-6 text-center">
                    <button type="button" class="btn btn-block btn-dark"
                            onclick="setIncreaseVal()">INCREASE BY $
                    </button>
                </div>
                <div class="col-sm-6 text-center">
                    <button type="button" class="btn btn-block btn-dark"
                            onclick="setDecreaseVal()">DECREASE BY $
                    </button>
                </div>
            </div>
        </div>
    </section>

<section>
    <div class="modal fade" id="modal" role="dialog"
         aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Crop the image</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <img id="image"
                             src="https://avatars0.githubusercontent.com/u/3456749"
                             alt="img">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="crop">Crop</button>
                </div>
            </div>
        </div>
    </div>
</section>
    <canvas id="canvas" width=600 height=600></canvas>
    <script>
        let wpUrl = '{{ $wp_url }}';
        let wpToken = '{{ $wp_token }}';
    </script>
    <script src="{{ asset('/assets/plugins/cropper/cropper.js') }}"></script>
    <script src="{{ asset('/assets/plugins/richText/jquery.richtext.min.js') }}"></script>
    <script src="{{ asset('/js/products/list/index.js') }}"></script>
@endsection
