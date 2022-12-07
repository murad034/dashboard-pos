@extends('layouts.AdminLTE.index')

@section('icon_page', 'tv')

@section('title', 'Order Make Station(OMS)')


@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped display responsive nowrap">
                        <thead style="text-align:left;">
                        <tr>
                            <th>Make Station ID</th>
                            <th>Make Station Name</th>
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
            <h4 style="margin-top:0px;font-size: 30px;" class="offcanvas-title" id="temptitle">
                New Order Make Station
            </h4>
            <!--            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>-->
        </div>
        <form id="ordermakeForm">
            <div class="offcanvas-body">
                <div class="row" style="margin-bottom: 20px; padding:0;">
                    <div class="col-md-12">
                        <label for="ordermakename">Order Make Name :</label>
                        <input type="text" class="ordermake-input form-control" id="ordermakename" name="ordermakename"
                               placeholder="Order Make Name" required>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 20px;padding:0;">
                    <div class="col-md-12">
                        <label for="ordermakedescription">Order Make Description :</label>
                        <input type="text" class="ordermake-input form-control" id="ordermakedescription"
                               name="ordermakedescription" placeholder="Order Make Description" required>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 20px;padding:0;">
                    <div class="col-md-12">
                        <label for="templatetype">Type:</label>
                        <select class="form-control select2" id="templatetype" name="templatetype" style="width: 100%;">
                            <option value="printer">Printer</option>
                            <option value="kvs">KVS</option>
                        </select>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 20px;padding:0;">
                    <div class="col-md-12" id="template-section">
                        <label for="allocatedprinttemplates">Printing Template :</label>
                        <select class="form-control select2" id="allocatedprinttemplates" name="allocatedprinttemplates"
                                data-placeholder="Select a State" style="width: 100%;">
                            @if(!empty($design_list))
                                @foreach($design_list as $order)
                                    <option value="{{ $order->templateid }}">
                                        {{ $order->templatename }}
                                    </option>
                                @endforeach
                            @else
                                <option value="no">No Template</option>
                            @endif
                        </select>
                    </div>
                </div>

                <button type="button" class="profile-btn-snd close-animatedModal"
                        style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas">
                    Close
                </button>
                <button type="button" onclick="addMakeStation()" class="profile-btn savebut" style="border: none;">Save
                </button>
                <button type="button" style="display: none; border: none;" onclick="updateMakeStation()"
                        class="profile-btn updatebut">Update
                </button>


            </div>
        </form>
    </div>

    <script src="{{ asset('/js/station/make/index.js') }}"></script>
@endsection
