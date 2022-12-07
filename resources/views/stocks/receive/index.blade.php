@extends('layouts.AdminLTE.index')

@section('icon_page', 'truck-loading')

@section('title', 'Receive Stock')


@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped display responsive nowrap">
                        <thead style="text-align:left;">
                        <tr>
                            <th>ID</th>
                            <th>Store Name</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>

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
         style="width: 600px;">
        <div class="offcanvas-header">
            <h4 style="margin-top:0px;font-size: 30px;" class="offcanvas-title" id="temptitle">
                Receive Stock
            </h4>
            <!--            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>-->
        </div>
        <div class="offcanvas-body">
            <table id="stocktable" class="table table-bordered table-striped display responsive nowrap"
                   style="width:100%">
                <thead style="text-align:left;">
                <tr>
                    <th>sku</th>
                    <th>name</th>
                    <th>qtyreceived</th>
                    <th>used by</th>
                </tr>
                </thead>

            </table>

            <form id="stockForm">

                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-12">
                        <label for="datereceived">Date Received :</label>
                        <input type="date" class="stock-input form-control" id="datereceived" name="datereceived"
                               placeholder="Date Received" required>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-12">
                        <label for="refnum">
                            Reference Number :
                        </label>
                        <input type="text" class="stock-input form-control" id="refnum" name="refnum"
                               placeholder="Reference Number" required>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-12">
                        <label for="recby">Received By : </label>
                        <input type="text" class="stock-input form-control" id="receivedby" name="receivedby"
                               placeholder="Received By" required>
                    </div>
                </div>

                <button type="button" class="profile-btn-snd close-animatedModal"
                        style="border: none;margin-bottom: 50px;"  data-bs-dismiss="offcanvas">Close
                </button>
                <button type="button" onclick="saveStock()" class="profile-btn savebut" style="border: none;">Save
                </button>
            </form>
        </div>

    </div>

    <script src="{{ asset('/js/stocks/receive/index.js') }}"></script>
@endsection
