@extends('layouts.AdminLTE.index')

@section('icon_page', 'cloud')

@section('title', 'Automation')


@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/automation/beautiful.css') }}"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/jerosoler/Drawflow/dist/drawflow.min.css">

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="wrapper">
                        <div class="col">
                            <div class="drag-drawflow" draggable="true" ondragstart="drag(event)"
                                 data-node="customer">
                                <i class="fas fa-user"></i><span> Customer</span>
                            </div>
                            <div class="drag-drawflow" draggable="true" ondragstart="drag(event)"
                                 data-node="hastag">
                                <i class="fas fa-tag"></i><span> Has Tag </span>
                            </div>
                            <div class="drag-drawflow" draggable="true" ondragstart="drag(event)"
                                 data-node="visitstore">
                                <i class="fas fa-store"></i><span> Has Visit Store</span>
                            </div>
                            <div class="drag-drawflow" draggable="true" ondragstart="drag(event)"
                                 data-node="salevalue">
                                <i class="fas fa-shopping-cart"></i><span> Sales Value Greater</span>
                            </div>
                            <div class="drag-drawflow" draggable="true" ondragstart="drag(event)"
                                 data-node="lastsale">
                                <i class="fas fa-shopping-bag"></i><span> Last Sales</span>
                            </div>
                            <div class="drag-drawflow" draggable="true" ondragstart="drag(event)"
                                 data-node="and">
                                <i class="fas fa-check"></i><span> AND </span>
                            </div>
                            <div class="drag-drawflow" draggable="true" ondragstart="drag(event)"
                                 data-node="or">
                                <i class="fab fa-angle-up"></i><span> OR</span>
                            </div>
                            <div class="drag-drawflow" draggable="true" ondragstart="drag(event)"
                                 data-node="campaign">
                                <i class="fas fa-campground"></i><span> Send Campaign </span>
                            </div>
                            <div class="drag-drawflow" draggable="true" ondragstart="drag(event)"
                                 data-node="addtag">
                                <i class="fas fa-tag"></i><span> Add Tag </span>
                            </div>

                        </div>
                        <div class="col-right">
                            <div class="menu">
                                <ul>
                                    <li onclick="editor.changeModule('Home'); changeModule(event);"
                                        class="selected">Home
                                    </li>
                                </ul>
                            </div>
                            <div id="drawflow" ondrop="drop(event)" ondragover="allowDrop(event)">

                                <div class="btn-export" onclick="saveDraw()">Save Draw
                                </div>
                                <div class="btn-clear" onclick="editor.clearModuleSelected()">Clear</div>
                                <div class="btn-lock">
                                    <i id="lock" class="fas fa-lock"
                                       onclick="editor.editor_mode='fixed'; changeMode('lock');"></i>
                                    <i id="unlock" class="fas fa-lock-open"
                                       onclick="editor.editor_mode='edit'; changeMode('unlock');"
                                       style="display:none;"></i>
                                </div>
                                <div class="bar-zoom">
                                    <i class="fas fa-search-minus" onclick="editor.zoom_out()"></i>
                                    <i class="fas fa-search" onclick="editor.zoom_reset()"></i>
                                    <i class="fas fa-search-plus" onclick="editor.zoom_in()"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <!-- /.col -->
    </div>
    <script>
        let template_id = '{{$automation_id}}';
    </script>
    <script src="https://cdn.jsdelivr.net/gh/jerosoler/Drawflow/dist/drawflow.min.js"></script>
    <script src="{{ asset('/js/automation/builder.js') }}"></script>
@endsection
