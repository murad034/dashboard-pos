@extends('layouts.AdminLTE.index')

@section('icon_page', 'bag-shopping')

@section('title', 'Purchase Order')


@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped display responsive nowrap">
                        <thead style="text-align:left;">
                        <tr>
                            <th>Purchase Order ID</th>
                            <th>Purchase Order Name</th>
                            <th>Purchase Order Description</th>
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
            <h4 style="margin-top:0px;font-size: 30px;" class="offcanvas-title" id="temptitle">
                New Purchase Order
            </h4>
            <!--            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>-->
        </div>
        <form id="purchaseForm">
            <div class="offcanvas-body">
                <div class="row" style="margin-bottom: 20px; padding:0;">
                    <div class="col-md-12">
                        <label for="purchase_name">Purchase Order Name :</label>
                        <input type="text" class="purchase-input form-control" id="purchase_name" name="purchase_name"
                               placeholder="Purchase Order Name" required>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 20px;padding:0;">
                    <div class="col-md-12">
                        <label for="purchase_description">Purchase Order Description :</label>
                        <input type="text" class="purchase-input form-control" id="purchase_description"
                               name="purchase_description" placeholder="Purchase Order Description" required>
                    </div>
                </div>

                <button type="button" class="profile-btn-snd close-animatedModal"
                        style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas">
                    Close
                </button>
                <button type="button" onclick="addPurchase()" class="profile-btn savebut" style="border: none;">Save
                </button>
                <button type="button" style="display: none; border: none;" onclick="updatePurchase()"
                        class="profile-btn updatebut">Update
                </button>


            </div>
        </form>
    </div>


    <script src="{{ asset('/js/stocks/purchase/index.js') }}"></script>
@endsection
