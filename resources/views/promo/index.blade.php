@extends('layouts.AdminLTE.index')

@section('icon_page', 'trophy')

@section('title', 'Promos')


@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped display responsive nowrap">
                        <thead style="text-align:left;">
                        <tr>
                            <th>Promos ID</th>
                            <th>Promos Name</th>
                            <th>Promos Description</th>
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
                New Promos
            </h4>
            <!--            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>-->
        </div>
        <form id="promosForm">
            <div class="offcanvas-body">
                <div class="row" style="margin-bottom: 20px; padding:0;">
                    <div class="col-md-12">
                        <label for="promosname">Promos Name :</label>
                        <input type="text" class="promos-input form-control" id="promosname" name="promosname"
                               placeholder="Promos Name" required>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 20px;padding:0;">
                    <div class="col-md-12">
                        <label for="promosdescription">Promos Description :</label>
                        <input type="text" class="promos-input form-control" id="promosdescription"
                               name="promosdescription" placeholder="Promos Description" required>
                    </div>
                </div>

                <button type="button" class="profile-btn-snd close-animatedModal"
                        style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas">
                    Close
                </button>
                <button type="button" onclick="addPromos()" class="profile-btn savebut" style="border: none;">Save
                </button>
                <button type="button" style="display: none; border: none;" onclick="updatePromos()"
                        class="profile-btn updatebut">Update
                </button>


            </div>
        </form>
    </div>


    <script src="{{ asset('/js/promo/index.js') }}"></script>
@endsection
