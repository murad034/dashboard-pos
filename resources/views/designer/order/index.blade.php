@extends('layouts.AdminLTE.index')

@section('icon_page', 'keyboard')

@section('title', 'Order Keypad Designer(OKD)')


@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped display responsive nowrap">
                        <thead style="text-align:left;">
                        <tr>
                            <th>Keypad Name</th>
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
         style="width: 600px;">
        <div class="offcanvas-header">
            <h4 style="margin-top:0px;font-size: 30px;" class="offcanvas-title" id="temptitle">
                New KeyPad
            </h4>
            <!--            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>-->
        </div>
        <form id="keypadForm">
            <div class="offcanvas-body">
                <div class="row" style="margin-bottom: 20px; padding:0;">
                    <div class="col-md-12">
                        <label for="keypadname">KeyPad Name :</label>
                        <input type="text" class="keypad-input form-control" id="keypadname" name="keypadname"
                               placeholder="KeyPad Name" required>
                    </div>
                </div>

                <button type="button" class="profile-btn-snd close-animatedModal"
                        style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas">
                    Close
                </button>
                <button type="button" onclick="addKeyPad()" class="profile-btn savebut" style="border: none;">Save
                </button>
                <button type="button" style="display: none; border: none;" onclick="updateKeyPad()"
                        class="profile-btn updatebut">Update
                </button>

            </div>
        </form>
    </div>

    <script src="{{ asset('/js/designer/order/index.js') }}"></script>
@endsection
