@extends('layouts.AdminLTE.index')

@section('icon_page', 'keyboard')

@section('title', 'Order Keypad Designer(OKD)')


@section('content')

    <link rel="stylesheet" href="{{ asset('/js/designer/order/styles/spectrum.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/js/designer/order/styles/layout.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/js/designer/order/styles/main.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/js/designer/order/styles/left-content.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/js/designer/order/styles/right-content.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/js/designer/order/styles/toast.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/js/designer/order/styles/layout-input.css') }}"/>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="content profile" style="margin-top: 0px;">

                        <div class="block">
                            <main>
                                <div class="page-content-wrap">
                                    <div class="both-content-wrap">
                                        <div class="left-content-section">
                                            <div class="search-box">
                                                <form>
                                                    <input
                                                        id="search-section"
                                                        type="text"
                                                        name="search"
                                                        placeholder="Search for Products/Functions"
                                                    />
                                                </form>
                                            </div>
                                            <div class="layout-section">
                                                <div class="layout-head">
                                                    <div class="layout-title">
                                                        <p>LAYOUTS</p>
                                                    </div>
                                                    <div class="layout-action-btns">
                                                        <div class="can-disbl-btns">
                                                            <div
                                                                class="delete-layout"
                                                                id="dele-layout"
                                                                onclick="deleteLayout();"
                                                            >
                                                                <i class="fas fa-trash-alt"></i>
                                                                <span class="tooltip">Delete Layout</span>
                                                            </div>
                                                            <div class="clone-layout" onclick="CloneLay();">
                                                                <i class="fa fa-clone" aria-hidden="true"></i>
                                                                <span class="tooltip">Clone as new Layout</span>
                                                            </div>
                                                            <div class="clone-to-multiple"
                                                                 onclick="cloneToMultiple();">
                                                                <i class="fas fa-layer-group"></i>
                                                                <span class="tooltip">Clone to multiple Layouts</span>
                                                            </div>
                                                        </div>

                                                        <div class="add-layout" onclick="addLay();">
                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                            <span class="tooltip">Add Layout</span>
                                                        </div>
                                                        <div id="cloneToMulForm" class="multi-clone-list">
                                                            <div class="multi-top-head">
                                                                <div class="multi-title">Clone to multiple</div>
                                                                <div
                                                                    class="multi-select-all"
                                                                    onclick="selectAllMultiple();"
                                                                >
                                                                    <i class="fas fa-tasks"></i>
                                                                    <span class="tooltip">Select All</span>
                                                                </div>
                                                                <div class="multi-submit">
                                                                    <i
                                                                        class="far fa-check-circle"
                                                                        onclick="uploadMulClone();"
                                                                    ></i>
                                                                    <span class="tooltip">Clone to selected</span>
                                                                </div>
                                                                <div class="multi-cancel"
                                                                     onclick="cloneToMultiple();">
                                                                    <i class="far fa-times-circle"></i>
                                                                    <span class="tooltip">Cancel</span>
                                                                </div>
                                                            </div>
                                                            <div class="multi-lay-list">
                                                                <div id="clone-dest-list">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="layout-menu layout-menu-blink">
                                                    <div id="layoutsection" class="source">
                                                        @foreach ($keypad_layouts as $keypad_layout)
                                                            <a class="ui-draggable layout-item"
                                                               data-ref="L-{{ $keypad_layout->layoutid }}">{{ $keypad_layout->data }}</a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-section">
                                                <div class="product-title">
                                                    <button
                                                        class="product-tab active-tab tablink"
                                                        onclick="prodFuncToggle(event,'product-container')"
                                                    >
                                                        PRODUCTS
                                                    </button>
                                                    <button
                                                        class="function-tab tablink"
                                                        onclick="prodFuncToggle(event,'function-container')"
                                                    >
                                                        FUNCTIONS
                                                    </button>
                                                </div>
                                                <div class="prod-func-wrap">
                                                    <div
                                                        class="product-menu prod-func-title"
                                                        id="product-container"
                                                    >
                                                        <div class="source" id="product-source">
                                                            <p class="product-dummy-text">
                                                                Please select a layout to display the list of
                                                                products
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="function-menu prod-func-title"
                                                        id="function-container"
                                                        style="display: none"
                                                    >
                                                        <div class="source" id="function-source">
                                                            <p class="function-dummy-text">
                                                                Please select a layout to display the list of
                                                                Functions
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="customize-section">
                                                <div class="custom-head">
                                                    <div class="custom-title">
                                                        <p>CUSTOMIZE LAYOUT</p>
                                                    </div>
                                                    <div class="custom-action-icon">
                                                        <div class="sub-link-btn" onclick="sublinkToLay();">
                                                            <i class="fas fa-link"></i>
                                                            <span class="tooltip">Add Sublink</span>
                                                        </div>
                                                        <div class="select-all-btn" onclick="selectKeys();">
                                                            <i class="fas fa-object-group"></i>
                                                            <span class="tooltip">Select all keys</span>
                                                        </div>
                                                        <div class="de-select-all-btn" onclick="deselectKeys();">
                                                            <i class="fas fa-object-ungroup"></i>
                                                            <span class="tooltip">Deselect all keys</span>
                                                        </div>
                                                        <div class="undo-changes" onclick="clearLayout();">
                                                            <i class="fas fa-eraser"></i>
                                                            <span class="tooltip">Clear Layout</span>
                                                        </div>
                                                        <div class="save-changes" onclick="save_changes();">
                                                            <i class="fas fa-save"></i>
                                                            <span class="tooltip">Save Changes</span>
                                                        </div>
                                                        <div class="save-changes" onclick="saveDraft();">
                                                            <i class="fas fa-share-from-square"></i>
                                                            <span class="tooltip">Save Draft</span>
                                                        </div>
                                                        <div class="save-changes set-schedule" id="saveReceiptWithSchedulePopUp" >
                                                            <i class="fas fa-calendar-plus"></i>
                                                            <span class="tooltip">Save Draft & Schedule</span>
                                                        </div>
                                                        <section>
                                                            <div id="PopoverContent" class="d-none">
                                                                <div class="row" style="padding-top: 5px;">
                                                                    <div class="col-sm-9 text-center">
                                                                        <input type="datetime-local" id="set_schedule_at" class="form-control" name="schedule_at">
                                                                    </div>
                                                                    <div class="col-sm-3 text-center">
                                                                        <button type="button" class="btn btn-block btn-dark savbut" style="margin-left:-9px; margin-top:0px;"
                                                                                onclick="saveDraftScheduleAt()">Save
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </section>

                                                        <div id="sublinkForm" class="sublink-layout-list">
                                                            <div class="sublink-top-head">
                                                                <div class="sublink-title">Sublink to a layout</div>
                                                                <div class="sublink-submit">
                                                                    <i
                                                                        class="far fa-check-circle"
                                                                        onclick="assignSublink();"
                                                                    ></i>
                                                                    <span class="tooltip">Sublink to selected</span>
                                                                </div>
                                                                <div class="sublink-cancel"
                                                                     onclick="sublinkToLay();">
                                                                    <i class="far fa-times-circle"></i>
                                                                    <span class="tooltip">Cancel</span>
                                                                </div>
                                                            </div>
                                                            <div class="sublink-lay-list">
                                                                <div>
                                                                    @foreach ($keypad_layouts as $keypad_layout)
                                                                        <a class="sublink-list"
                                                                           data-ref="L-{{ $keypad_layout->layoutid }}">{{ $keypad_layout->data }}</a>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="custom-menu">
                                                    <div class="color-seg">
                                                        <div class="btn-bg-color">
                                                            <button type="button" class="bg-color-btn">
                                                                <label for="color-picker" style="font-size:11px;">Select
                                                                    Key Color</label>
                                                            </button>
                                                            <input class="input-color" id="color-picker"/>
                                                        </div>
                                                        <div class="font-color">
                                                            <button type="button" class="font-color-btn">
                                                                <label for="font-color-pick"
                                                                       style="font-size:11px;">Select Font
                                                                    Color</label>
                                                            </button>
                                                            <input class="input-color" id="font-color-pick"/>
                                                        </div>
                                                    </div>
                                                    <div class="font-section">
                                                        <!---  <div class="font-selector">
                                                            <select id="input-font" class="input">
                                                              <option value="Times New Roman" selected="selected">
                                                                Times New Roman
                                                              </option>
                                                              <option value="Arial">Arial</option>
                                                              <option value="fantasy">Fantasy</option>
                                                              <option value="cursive">cursive</option>
                                                            </select>
                                                          </div>--->
                                                        <div class="font-setter">
                                                            <div class="font-label">Font Size</div>
                                                            <div class="font-slider">
                                                                <input type="range" min="14" max="60" value="14"
                                                                       id="slider"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="btn-size">
                                                        <div class="upDwn">
                                                            <button
                                                                class="plus-height-btn"
                                                                onclick="twicePlusHeight();"
                                                            >
                                                                2x
                                                                <i
                                                                    class="fa fa-angle-down"
                                                                    id="btn-size"
                                                                    aria-hidden="true"
                                                                ></i>
                                                            </button>
                                                            <button
                                                                class="minus-height-btn"
                                                                onclick="twiceMinusHeight();"
                                                            >
                                                                2x
                                                                <i
                                                                    class="fa fa-angle-up"
                                                                    id="btn-size"
                                                                    aria-hidden="true"
                                                                ></i>
                                                            </button>
                                                        </div>
                                                        <div class="riteLeft">
                                                            <button
                                                                class="minus-width-btn"
                                                                onclick="twiceMinusWidth();"
                                                            >
                                                                2x
                                                                <i
                                                                    class="fa fa-angle-left"
                                                                    id="btn-size"
                                                                    aria-hidden="true"
                                                                ></i>
                                                            </button>
                                                            <button class="plus-width-btn"
                                                                    onclick="twicePlusWidth();">
                                                                2x
                                                                <i
                                                                    class="fa fa-angle-right"
                                                                    id="btn-size"
                                                                    aria-hidden="true"
                                                                ></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="btn-copy-group">
                                                        <div class="btn-copy-style">
                                                            <button
                                                                class="btn-copyStyle"
                                                                id=""
                                                                onclick="copyStyleBtn();"
                                                            >
                                                                Copy Style
                                                            </button>
                                                        </div>
                                                        <div class="btn-paste-style">
                                                            <button
                                                                class="btn-pasteStyle"
                                                                id="trash"
                                                                onclick=" pasteStyle();"
                                                            >
                                                                Paste Style
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="btn-del-paste-group">
                                                        <div class="btn-remove">
                                                            <button class="btn-del" id="trash" onclick=" del();">
                                                                Delete Key
                                                            </button>
                                                        </div>
                                                        <div class="btn-clone">
                                                            <button class="btn-copy" id="" onclick="cloneBtn();">
                                                                Clone Key
                                                            </button>
                                                            <!-- <button class="btn-paste">Paste</button> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="right-content-section">
                                            <div
                                                class="overview-section dest ui-droppable"
                                                id="droppable-layout-section" style="width: 100%;"
                                            >
                                                <div class="welcome-text">
                                                    <div class="arrow-scribble">
                                                        <img src="{{ asset('/js/designer/order/assets/arrow.png') }}"
                                                             alt="" srcset=""/>
                                                    </div>
                                                    <p class="welcome-title">IMReKe Keypad Builder!</p>
                                                    <p class="welcome-description">
                                                        (Display a Layout by clicking on any item from the Layouts
                                                        section)
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="toast">
                                    <div id="img" class="fa fa-check toast-icon" aria-hidden="true"></div>
                                    <div id="desc">Layout Updated Successfully</div>
                                </div>
                                <div class="form-popup" id="layoutForm">
                                    <form class="form-container">
                                        <label for="layout"><b>Add a new Layout</b></label>
                                        <input
                                            id="layName"
                                            type="text"
                                            placeholder="Name your layout"
                                            name="layout"
                                            required
                                        />
                                        <div class="lay_btn-group">
                                            <button id="laybtn" type="submit" class="btn">Add Layout</button>
                                            <button type="button" class="btn cancel" onclick="cancelLayout()">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="form-popup" id="ClonelayoutForm">
                                    <form class="form-container">
                                        <label for="layout"><b>Clone a new Layout</b></label>
                                        <input
                                            id="CloneLayName"
                                            type="text"
                                            placeholder="Name your layout"
                                            name="layout"
                                            required
                                        />
                                        <div class="lay_btn-group">
                                            <button id="clnlaybtn" type="submit" class="btn">
                                                Clone Layout
                                            </button>
                                            <button
                                                type="button"
                                                class="btn cancel"
                                                onclick="clonecancelLayout()"
                                            >
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </main>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>

    </div>
    <script>

        let keypad_id = '{{$keypad_id}}';
    </script>
    <script
        type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery.serializeJSON/3.2.1/jquery.serializejson.min.js"
    ></script>
    <script src="{{ asset('/js/designer/order/js/spectrum.js') }}"></script>

    <script src="{{ asset('/js/designer/order/js/layout.js') }}"></script>
    <script src="{{ asset('/js/designer/order/js/copy-paste-style.js') }}"></script>
    <script src="{{ asset('/js/designer/order/js/btn-clone.js') }}"></script>
    <script src="{{ asset('/js/designer/order/js/btn-size.js') }}"></script>
    <script src="{{ asset('/js/designer/order/js/font-mod.js') }}"></script>
    <script src="{{ asset('/js/designer/order/js/toast.js') }}"></script>
    <script src="{{ asset('/js/designer/order/js/savelay.js') }}"></script>
    <script src="{{ asset('/js/designer/order/js/color-picker.js') }}"></script>
    <script src="{{ asset('/js/designer/order/builder.js') }}"></script>
@endsection
