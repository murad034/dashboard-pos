@extends('layouts.AdminLTE.index')

@section('icon_page', 'receipt')

@section('title', 'Email Marketing Designer(EMD)')


@section('content')
    <script type="text/javascript" src="https://plugins.stripo.email/static/latest/stripo.js"></script>
    <style>
        #externalSystemContainer {
            background-color: darkgrey;
            padding: 5px 0 5px 20px;
        }

        #undoButton,
        #redoButton {
            display: none;
        }

        #stripoSettingsContainer {
            width: 400px;
            float: left;
        }

        #stripoPreviewContainer {
            width: calc(100% - 400px);
            float: left;
        }

        .notification-zone {
            position: fixed;
            width: 400px;
            z-index: 99999;
            right: 20px;
            bottom: 80px;
        }

        .control-button {
            border-radius: 17px;
            padding: 5px 10px;
            border-color: grey;
        }

        #changeHistoryLink {
            cursor: pointer;
        }

        .esdev-app .nav-tabs.nav-justified > li {
            width: 50% !important;
        }
    </style>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <!--                         <div class="box-header">
               <a class="profile-btn demo01"  href="#animatedModal" onclick="addnewproduct()">Add Product</a>
        </div> -->
                <div class="box-header">
                    <a class="profile-btn demo01" onclick="save()">Save Template</a>

                </div>

                <div class="box-body ">
                    <!--             <div id="externalSystemContainer">
    <button id="undoButton" class="control-button">Undo</button>
    <button id="redoButton" class="control-button">Redo</button>

    <span id="changeHistoryContainer" style="display: none;">Last change: <a id="changeHistoryLink"></a></span>
</div> -->
                    <div class="notification-zone"></div>
                    <div>
                        <!--Plugin containers -->
                        <div id="stripoSettingsContainer">Loading...</div>
                        <div id="stripoPreviewContainer"></div>
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

        let template_id = '{{$template_id}}';
    </script>
    <script src="{{ asset('/dist/js/notifications.js') }}"></script>
    <script src="{{ asset('/js/designer/email/builder.js') }}"></script>
@endsection
