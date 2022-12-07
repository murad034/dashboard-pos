@extends('layouts.AdminLTE.index')

@section('icon_page', 'photo-video')

@section('title', 'Media Board Designer(MBD)')


@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped display responsive nowrap">
                        <thead style="text-align:left;">
                        <tr>
                            <th>Board ID</th>
                            <th>Board Name</th>
                            <th>Board Password</th>
                            <th>Orientation</th>
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
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel"
         style="width: 600px;">
        <div class="offcanvas-header">
            <h4 style="margin-top:0px;font-size: 30px;" class="offcanvas-title" id="temptitle">
                New Board
            </h4>
            <!--            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>-->
        </div>
        <form id="boardForm">
            <div class="offcanvas-body">
                <div class="row" style="margin-bottom: 20px; padding:0;">
                    <div class="col-md-12">
                        <label for="boardname">Board Name :</label>
                        <input type="text" class="board-input form-control" id="boardname" name="boardname"
                               placeholder="Board Name" required>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 20px;padding:0;">
                    <div class="col-md-12">
                        <select class="form-control board-input" id="orientation" name="orientation">
                            <option value="Landscape">
                                Landscape
                            </option>
                            <option value="Portrait">
                                Portrait
                            </option>
                        </select>
                    </div>
                </div>

                <button type="button" class="profile-btn-snd close-animatedModal"
                        style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas">
                    Close
                </button>
                <button type="button" onclick="addBoard()" class="profile-btn savebut" style="border: none;">Save
                </button>
                <button type="button" style="display: none; border: none;" onclick="updateBoard()"
                        class="profile-btn updatebut">Update
                </button>


            </div>
        </form>
    </div>

    <script src="{{ asset('/js/designer/media/index.js') }}"></script>
@endsection
