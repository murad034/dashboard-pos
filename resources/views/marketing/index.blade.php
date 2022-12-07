@extends('layouts.AdminLTE.index')

@section('icon_page', 'mail-bulk')

@section('title', 'Marketing')


@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped display responsive nowrap">
                        <thead style="text-align:left;">
                        <tr>
                            <th>Marketing ID</th>
                            <th>Marketing Name</th>
                            <th>Marketing Description</th>
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

    <div class="offcanvas offcanvas-start" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel"
         style="width: 900px;">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist" style="margin-top:18px;">
                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                        type="button" role="tab" aria-controls="nav-home" aria-selected="true">General
                </button>
                <button class="nav-link" id="nav-history-tab" data-bs-toggle="tab" data-bs-target="#nav-history"
                        type="button" role="tab" aria-controls="nav-history" aria-selected="false">Delivery Log
                </button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="offcanvas-header">
                    <h4 style="margin-top:0px;font-size: 30px;" class="offcanvas-title" id="temptitle">
                        New Marketing
                    </h4>
                    <!--            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>-->
                </div>
                <form id="marketingForm">
                    <div class="offcanvas-body">
                        <div class="row" style="margin-bottom: 20px; padding:0;">
                            <div class="col-md-12">
                                <label for="marketingname">Marketing Name :</label>
                                <input type="text" class="marketing-input form-control" id="marketingname" name="marketingname"
                                       placeholder="Marketing Name" required>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 20px;padding:0;">
                            <div class="col-md-12">
                                <label for="marketingdescription">Marketing Description :</label>
                                <input type="text" class="marketing-input form-control" id="marketingdescription"
                                       name="marketingdescription" placeholder="Marketing Description" required>
                            </div>
                        </div>

                        <div class="row" style="margin-bottom: 20px;padding:0px;">
                            <div class="col-md-6">
                                <label for="template_id">

                                </label><select class="form-control marketing-input" id="template_id" name="template_id">
                                    <option value="no">Email Templates :</option>
                                    @foreach ($templates as $template)
                                        <option value="{{ $template->templateid }}">
                                            {{ $template->templatename }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tag_id"></label><select class="form-control marketing-input" id="tag_id" name="tag_id">
                                    <option value="no">Tags :</option>
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->tagid }}">
                                            {{ $tag->tagname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row" style="margin-bottom: 20px;padding:0px;">
                            <div class="col-md-6">
                                <div class="form-check form-switch" style="padding-top: 30px;">
                                    <label for="schedule_available">Schedule available</label>
                                    <input class="form-check-input form-control" type="checkbox" name="schedule_available" id="schedule_available">
                                </div>
                            </div>
                            <div class="col-md-6" id="schedule_section" style="display: none;">
                                <label for="set_schedule_at">SET Schedule :</label>
{{--                                <input type="datetime-local" id="set_schedule_at" class="form-control" name="schedule_at">--}}
                                <input type="text" id="set_schedule_at" class="form-control" name="schedule_at">
                            </div>
                        </div>

                        <button type="button" class="profile-btn-snd close-animatedModal"
                                style="border: none;margin-bottom: 50px;" data-bs-dismiss="offcanvas">
                            Close
                        </button>
                        <button type="button" onclick="addMarketing()" class="profile-btn savebut" style="border: none;">Save
                        </button>
                        <button type="button" style="display: none; border: none;" onclick="updateMarketing()"
                                class="profile-btn updatebut">Update
                        </button>


                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="nav-history" role="tabpanel" aria-labelledby="nav-history-tab">
                <div class="box">

                    <div class="box-body">
                        <table id="historytable" class="table table-bordered table-striped display responsive nowrap"
                               style="width: 100%; overflow-x: scroll;">
                            <thead style="text-align:left;">
                            <tr>
{{--                                <th>Marketing Name</th>--}}
                                <th>From</th>
                                <th>To</th>
                                <th>Time</th>
                                <th>Open</th>
                            </tr>
                            </thead>
                            <tbody>

                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>


    </div>



    <script src="{{ asset('/js/marketing/index.js') }}"></script>
@endsection
