@extends('layouts.AdminLTE.index')

@section('icon_page', 'clipboard-list')

@section('title', 'Stock Categories & Sub Categories')


@section('content')
<div class="row">
  <div class="col-sm-6">
    <div class="box">
      <div class="box-body">
        <table id="example1" class="table table-bordered table-striped display responsive nowrap">
          <thead style="text-align:left;">
            <tr>
              <th>Category Name</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
          {{--                        @foreach ($categories as $category)--}}
          {{--                            <tr class="details">--}}
          {{--                                <td>{{ $category->catagoryname }}</td>--}}
          {{--                                <td>--}}
          {{--                                    <a data-simpletooltip-text="Edit Item" class="js-simple-tooltip profile-btn"--}}
          {{--                                       data-bs-toggle="offcanvas" href="#offcanvasExample"--}}
          {{--                                       aria-controls="offcanvasExample" onclick="editMainCat({{ $category->catid }})"--}}
          {{--                                       style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;"><i--}}
          {{--                                            class="fa fa-pen"></i></a>--}}
          {{--                                    <a data-simpletooltip-text="De-activate Item"--}}
          {{--                                       class="js-simple-tooltip profile-btn-del"--}}
          {{--                                       onclick="JSalert('{{ $category->catid }}','{{ $category->catagoryname }}')"--}}
          {{--                                       style="font-size: 18px; padding: 6px; padding-left: 15px; padding-right: 15px; margin-top: 0px;"><i--}}
          {{--                                            class="fa fa-times"></i></a>--}}
          {{--                                </td>--}}
          {{--                            </tr>--}}
          {{--                        @endforeach--}}
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
  <!-- /.col -->
  <div class="col-sm-6">

    <div class="box">

      <div class="box-body">
        <table id="example12" class="table table-bordered table-striped display responsive nowrap">
          <thead style="text-align:left;">
            <tr>
              <th>Sub Category Name</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          </tbody>

          {{--                        @foreach ($subCategories as $subCategory)--}}
          {{--                            <tr class="details">--}}
          {{--                                <td>{{ $subCategory->subcatagoryname }}</td>--}}
          {{--                                <td>--}}
          {{--                                    <a data-simpletooltip-text="Edit Item" class="js-simple-tooltip profile-btn"--}}
          {{--                                       data-bs-toggle="offcanvas" href="#offcanvasExample2"--}}
          {{--                                       aria-controls="offcanvasExample2"--}}
          {{--                                       onclick="editSubCat({{ $subCategory->subcatid }})"--}}
          {{--                                       style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;"><i--}}
          {{--                                            class="fa fa-pen"></i></a>--}}
          {{--                                    <a data-simpletooltip-text="De-activate Item"--}}
          {{--                                       class="js-simple-tooltip profile-btn-del"--}}
          {{--                                       onclick="JSSubalert({{ $subCategory->subcatid }},'{{ $subCategory->subcatagoryname }}')"--}}
          {{--                                       style="font-size: 18px; padding: 6px; padding-left: 15px; padding-right: 15px; margin-top: 0px;"><i--}}
          {{--                                            class="fa fa-times"></i></a>--}}
          {{--                                </td>--}}
          {{--                            </tr>--}}
          {{--                        @endforeach--}}
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!--MODAL WINDOW-->
<!-- Main Category ADD/EDIT -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabe"
  style="width: 600px;">
  <div class="offcanvas-header">
    <h4 style="margin-top:0;font-size: 30px;" class="offcanvas-title" id="temptitle">
      Add Category
    </h4>
    <!--            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>-->
  </div>
  <div class="offcanvas-body">
    <div class="col-md-12">
      <label for="catagoryname">
        Main Category Name :
      </label>
      <input type="text" class="productinput form-control" id="catagoryname" placeholder="Catagory Name">
    </div>
    <button type="button" class="profile-btn-snd close-animatedModal" style="border: none;margin-bottom: 50px;"
      data-bs-dismiss="offcanvas">
      Close
    </button>
    <button type="button" onclick="saveMainCat()" class="profile-btn savemainbut" style="border: none;">Save
    </button>
    <button type="button" style="display: none; border: none;" onclick="updateMainCat()"
      class="profile-btn updatemainbut">Update
    </button>
    </button>
  </div>
</div>

<!-- Sub Category ADD/EDIT -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample2" aria-labelledby="offcanvasExampleLabe2"
  style="width: 600px;">
  <div class="offcanvas-header">
    <h4 style="margin-top:0;font-size: 30px;" class="offcanvas-title" id="modaltitle">
      Add Sub Catergory
    </h4>
  </div>
  <div class="offcanvas-body">

    <div class="col-md-12">
      <label for="subcatagoryname">
        Sub Category Name :
      </label><input type="text" class="productinput form-control" id="subcatagoryname" placeholder="Sub Catagory Name">
    </div>
    <button type="button" class="profile-btn-snd close-animatedModal" style="border: none;margin-bottom: 50px;"
      data-bs-dismiss="offcanvas">
      Close
    </button>
    <button type="button" onclick="saveSubCat()" class="profile-btn savesubbut" style="border: none;">Save
    </button>
    <button type="button" style="display: none; border: none;" onclick="updateSubCat()"
      class="profile-btn updatesubbut">Update
    </button>
  </div>
</div>
<!-- Main Category CSV Import Modal -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample3" aria-labelledby="offcanvasExampleLabe3"
  style="width: 600px;">
  <div class="offcanvas-header">
    <h4 style="margin-top:0;font-size: 30px;" class="offcanvas-title" id="modaltitle">
      Import Main Categories
    </h4>
  </div>
  <div class="offcanvas-body">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Select CSV File</h3>
      </div>
      <div class="panel-body">
        <div class="row" id="upload_main_area">
          <form method="post" id="upload_main_form" enctype="multipart/form-data">
            <div class="col-md-12" align="center">
              <input type="file" name="file" id="csv_file" />
            </div>
            <br />
          </form>

        </div>
        <div class="table-responsive" id="process_main_area">

        </div>
      </div>
    </div>
    <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="offcanvas">Close</button>
    <button type="button" name="import" id="import_main" class="btn btn-success rounded-0" style="display:none;">Import
    </button>
    <button type="button" name="upload_file" id="upload_main_file" class="btn btn-success rounded-0">Upload
    </button>
  </div>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample4" aria-labelledby="offcanvasExampleLabe4"
  style="width: 600px;">
  <div class="offcanvas-header">
    <h4 style="margin-top:0;font-size: 30px;" class="offcanvas-title" id="modaltitle">
      Import Sub Catagories
    </h4>
    <!--            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>-->
  </div>
  <div class="offcanvas-body">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Select CSV File</h3>
      </div>
      <div class="panel-body">
        <div class="row" id="upload_sub_area">
          <form method="post" id="upload_sub_form" enctype="multipart/form-data">
            <div class="col-md-12" align="center">
              <input type="file" name="file" id="csv_file" />
            </div>
            <br />
          </form>

        </div>
        <div class="table-responsive" id="process_sub_area">

        </div>
      </div>
    </div>
    <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="offcanvas">Close</button>
    <button type="button" name="import" id="import_sub" class="btn btn-success rounded-0" style="display:none;">
      Import
    </button>
    <button type="button" name="upload_file" id="upload_sub_file" class="btn btn-success rounded-0">Upload
    </button>
  </div>
</div>

<script src="{{ asset('/js/stocks/category/index.js') }}"></script>
@endsection
