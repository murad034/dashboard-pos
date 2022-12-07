@extends('layouts.AdminLTE.index')

@section('icon_page', 'receipt')

@section('title', 'Customer Receipt Designer(CRD)')


@section('content')
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body">
        <table id="example1" class="table table-bordered table-striped display responsive nowrap">
          <thead style="text-align:left;">
            <tr>
              <th>Template ID</th>
              <th>Template Name</th>
              <th>Template Description</th>
              <th>Draft Action</th>
              <th>Schedule Time</th>
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
      New Template
    </h4>
    <!--            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>-->
  </div>
  <form id="templateForm">
    <div class="offcanvas-body">
      <div class="row" style="margin-bottom: 20px; padding:0;">
        <div class="col-md-12">
          <label for="templatename">Template Name :</label>
          <input type="text" class="template-input form-control" id="templatename" name="templatename"
            placeholder="Template Name" required>
        </div>
      </div>
      <div class="row" style="margin-bottom: 20px;padding:0;">
        <div class="col-md-12">
          <label for="templatedescription">Template Description :</label>
          <input type="text" class="template-input form-control" id="templatedescription" name="templatedescription"
            placeholder="Template Description" required>
        </div>
      </div>

      <button type="button" class="profile-btn-snd close-animatedModal" style="border: none;margin-bottom: 50px;"
        data-bs-dismiss="offcanvas">
        Close
      </button>
      <button type="button" onclick="addTemplate()" class="profile-btn savebut" style="border: none;">Save
      </button>
      <button type="button" style="display: none; border: none;" onclick="updateTemplate()"
        class="profile-btn updatebut">Update
      </button>


    </div>
  </form>
</div>

<script src="{{ asset('/js/designer/customer/index.js') }}"></script>
@endsection