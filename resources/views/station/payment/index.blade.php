@extends('layouts.AdminLTE.index')

@section('icon_page', 'tv')

@section('title', 'Order Payment Station(OPS)')


@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped display responsive nowrap">
                        <thead style="text-align:left;">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Keypad</th>
                            <th>Home Layout</th>
                            <th>Location</th>
                            <th>Online Status</th>
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
                New Terminal
            </h4>
        </div>
        <form id="terminalForm">
            <div class="offcanvas-body">
                <div class="row" style="margin-bottom: 20px; padding:0;">
                    <div class="col-md-6">
                        <label for="terminalname">Terminal Name :</label>
                        <input type="text" class="terminal-input form-control" id="terminalname" name="terminalname"
                               placeholder="terminal name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="terminalkeypad">Terminal Keypad :</label>
                        <select class="terminal-input form-control" id="terminalkeypad" name="terminalkeypad"
                                required></select>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 20px;padding:0;">
                    <div class="col-md-6" id="pass_section">
                        <label for="homelayout">Home Layout :</label>
                        <select type="text" class="form-control" id="homelayout" name="homelayout" required></select>
                    </div>
                    <div class="col-md-6">
                        <label for="locationid">Locations :</label>
                        <select class="form-control" id="locationid" name="locationid">
                            @if(!empty($locations))
                                @foreach($locations as $location)
                                    <option value="{{ $location->locationid }}">
                                        {{ $location->locationname }}
                                    </option>
                                @endforeach
                            @else
                                <option value="no">No Locations</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 20px; padding:0;">
                    <div class="col-md-6">
                        <label for="pricetier">Price Tier :</label>
                        <select class="form-control" id="pricetier" name="pricetier" required>
                            <option value="rrp">RRP</option>
                            <option value="tier1">Tier 1</option>
                            <option value="tier2">Tier 2</option>
                            <option value="tier3">Tier 3</option>
                            <option value="tier4">Tier 4</option>
                            <option value="tier5">Tier 5</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="printreceipt">Print Receipt :</label>
                        <select class="form-control" id="printreceipt" name="printreceipt" required>
                            <option value="yes">YES</option>
                            <option value="prompt">Prompt</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                </div>

                <div class="row" style="margin-bottom: 20px;padding:0;">
                    <div class="col-md-12">
                        <label for="allocateditem">Order Make Stations :</label>
                        <select class="form-control select2" id="allocateditem" name="allocateditem" multiple="multiple"
                                data-placeholder="Select a State" style="width: 100%;">
                            @if(!empty($orderList))
                                @foreach($orderList as $order)
                                    <option value="{{ $order->ordermakeid }}">
                                        {{ $order->ordermakename }}
                                    </option>
                                @endforeach
                            @else
                                <option value="no">No Order</option>
                            @endif
                        </select>
                    </div>
                </div>
                <button type="button" class="profile-btn-snd close-animatedModal"
                        style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas">
                    Close
                </button>
                <button type="button" onclick="addTerminal()" class="profile-btn savebut" style="border: none;">Save
                </button>
                <button type="button" style="display: none; border: none;" onclick="updateTerminal()"
                        class="profile-btn updatebut">Update
                </button>
            </div>



    </form>

    </div>
    <script>

        let tagList = @json($orderList);
    </script>
    <script src="{{ asset('/js/station/payment/index.js') }}"></script>
@endsection
