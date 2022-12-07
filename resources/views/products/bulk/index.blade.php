@extends('layouts.AdminLTE.index')

@section('icon_page', 'clipboard-list')

@section('title', 'Bulk Pricing Edit')


@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped display responsive nowrap">
                        <thead>
                        <tr class="details">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-center">
                                <button class="btn btn-block btn-info btn-sm price-change" data-bs-toggle="popover"
                                        data-bs-html="true" data-bs-sanitize="false">SET Price
                                </button>
                            </th>
                            <th class="text-center">
                                <button class="btn btn-block btn-info btn-sm price-change" data-bs-toggle="popover"
                                        data-bs-html="true" data-bs-sanitize="false">SET Tier1
                                </button>
                            </th>
                            <th class="text-center">
                                <button class="btn btn-block btn-info btn-sm price-change" data-bs-toggle="popover"
                                        data-bs-html="true" data-bs-sanitize="false">SET Tier2
                                </button>
                            </th>
                            <th class="text-center">
                                <button class="btn btn-block btn-info btn-sm price-change" data-bs-toggle="popover"
                                        data-bs-html="true" data-bs-sanitize="false">SET Tier3
                                </button>
                            </th>
                            <th class="text-center">
                                <button class="btn btn-block btn-info btn-sm price-change" data-bs-toggle="popover"
                                        data-bs-html="true" data-bs-sanitize="false">SET Tier4
                                </button>
                            </th>
                            <th class="text-center">
                                <button class="btn btn-block btn-info btn-sm price-change" data-bs-toggle="popover"
                                        data-bs-html="true" data-bs-sanitize="false">SET Tier5
                                </button>
                            </th>
                            <th>
                            </th>
                            <th>
                            </th>
                            <th>
                            </th>
                        </tr>
                        <tr>
                            <th>PLU #</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Barcode</th>
                            <th>RRP</th>
                            <th>Tier1</th>
                            <th>Tier2</th>
                            <th>Tier3</th>
                            <th>Tier4</th>
                            <th>Tier5</th>
                            <th>Cost</th>
                            <th>SOH</th>
                            <th>Available on WEB</th>
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

    <script>

        let locationList = @json($locationList);
        let categoryList = @json($categoryList);
    </script>
    <script src="{{ asset('/js/products/bulk/index.js') }}"></script>
@endsection
