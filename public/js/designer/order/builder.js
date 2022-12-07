//TOGGLT MULTI-CLONE-SELECTION
$(document).on("click", ".clone-list", function () {
    $(this).toggleClass("multi-clone-menu-selected");
});

function assignSublink() {
    let data_sub = $(".sublink-selected").data("ref");
    $(".selected").attr("data-sub", data_sub);
    $(".exp").removeClass("selected");
    sublinkToLay();
    launch_toast();
}

//**********************************/

//SHOW/HIDE SUBLINK FORM
function sublinkToLay() {
    let x = document.getElementById("sublinkForm");
    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
    $(".sublink-list").removeClass("sublink-selected");
}

//**********************************/

//TOGGLT KEY-SELECTION
$(document).on("click", ".exp", function () {
    $(this).toggleClass("selected");
});

//**********************************/

//TOGGLE PRODUCT/FUCTION TAB
function prodFuncToggle(evt, prodFunc) {
    let i, x, tablinks;
    x = document.getElementsByClassName("prod-func-title");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < x.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(
            " active-tab",
            ""
        );
    }
    document.getElementById(prodFunc).style.display = "block";
    evt.currentTarget.className += " active-tab";
}

//**********************************/

// MULTICLONE UPLOAD FUNCTION
function uploadMulClone() {
    let old_json_data = JSON.stringify({
        data: $("#droppable-layout-section").html(),
    });
    let rem_quo = old_json_data.replace(/\\"/gi, "`");
    let json_data = rem_quo.replace(/\\n/gi, "");
    let idArray = [];
    $(".multi-clone-menu-selected").each(function () {
        idArray.push(this.id);
    });
    $.ajax({
        url: "/order-keypad-designer/builder/push_multiple_json",
        method: "POST",
        data: {
            ids: idArray,
            jdata: json_data,
        },
        async: false,
        success: function (data) {
            cloneToMultiple();
            launch_toast();
            // $("#layoutsection").load("load_layout_list.php");
            setTimeout(() => {
                location.reload();
            }, 300);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("Error Loading");
        },
    });
}

//**********************************/

// SELECT ALL KEYS AT ONCE
function selectAllMultiple() {
    $(".clone-list").addClass("multi-clone-menu-selected");
}

// DESELECT ALL KEYS AT ONCE
function deselectKeys() {
    $(".exp").removeClass("selected");
}

// SELECT A KEY
function selectKeys() {
    $(".exp").addClass("selected");
}

// ADD A LAYOUT FORM
function addLay() {
    document.getElementById("layoutForm").style.display = "block";
}

// HIDE A LAYOUT FORM
function cancelLayout() {
    document.getElementById("layoutForm").style.display = "none";
}

// CLONE A LAYOUT FORM
function CloneLay() {
    document.getElementById("ClonelayoutForm").style.display = "block";
}

// HIDE CLONE LAYOUT FORM
function cancelLayout() {
    document.getElementById("layoutForm").style.display = "none";
}

//**********************************/

//SHOW/HIDE MULTICLONE FORM

function cloneToMultiple() {
    $(".layout-menu").toggleClass("disable-div");
    //setTimeout(() => {
    let data_ref = $(".menu-selected").data("ref");
    let data_id = data_ref.replace(/\D/g, "");
    $(".multi-lay-list [id=" + data_id + "]").toggle();
    let x = document.getElementById("cloneToMulForm");
    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
    $(".clone-list").removeClass("multi-clone-menu-selected");
    //  }, 30);
    $("#clone-dest-list").load(
        "/order-keypad-designer/builder/load_clone_layout_list"
    );
}

//CANCEL MULTICLONE FORM

function clonecancelLayout() {
    document.getElementById("ClonelayoutForm").style.display = "none";
}

//**********************************/

//SEARCH FUNCTION

$(document).ready(function () {
    $("#search-section").on("keyup", function () {
        // Search functionality
        let value = $(this).val().toLowerCase();
        $("#product-source *").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
        $("#function-source *").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    let dest_ht = parseFloat($(".dest").css("height"));
    $(".dest").css({ width: dest_ht + "px" });
    if ($(".layout-item").hasClass("menu-selected")) {
        // Welcome text
        $(".welcome-text").hide();
    } else {
        $(".welcome-text").show();
    }
    checkDisable();
    $(".set-schedule").each(function (index) {
        $(this).popover({
            placement: "top",
            html: true,
            container: "body",
            title: "Set schedule time",
            content: function () {
                return $("#PopoverContent").html();
            },
            sanitize: false,
        });
    });
});

//**********************************/

//DELETE A LAYOUT

function deleteLayout() {
    // To delete a layout
    if (confirm("Are you sure you want to delete the layout?")) {
        let data_ref = $(".menu-selected").data("ref");
        let data_id = data_ref.replace(/\D/g, "");
        $.ajax({
            url: "/order-keypad-designer/builder/delay_out/" + data_id,
            method: "DELETE",
            async: false,
            success: function (data) {
                // setTimeout(() => {
                //    $("#layoutsection").load("load_layout_list.php");
                // }, 300);
                // launch_toast();
                // checkDisable();
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(".dest").html("");
                alert("Error Loading");
            },
        });
        console.log("Thing was saved to the database.");
    } else {
        console.log("Thing was not saved to the database.");
    }
}

//**********************************/

// CLONE A LAYOUT

$("#ClonelayoutForm").on("submit", function (e) {
    e.preventDefault();
    e.stopPropagation();

    // To clone a layout
    let layName = $("#CloneLayName").val();
    let url = window.location.href;
    let keyidval = keypad_id;
    // alert(url);
    // alert(keyidval);
    if (layName.length > 0) {
        $("#CloneLayName").disabled = false;
        let layName = $("#CloneLayName").val();
        let data_ref = $(".menu-selected").data("ref");
        let data_id = data_ref.replace(/\D/g, "");
        // alert(data_id);
        $.ajax({
            url: "/order-keypad-designer/builder/clone_layout",
            method: "POST",
            data: {
                id: data_id,
                clone_name: layName,
                keyid: keyidval,
            },
            async: false,
            success: function (data) {
                console.log(data);

                launch_toast();

                setTimeout(() => {
                    // $("#layoutsection").load("load_layout_list.php");
                    // checkDisable();
                    // alert (url);
                    window.location.href = url;
                }, 300);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(".dest").html("");
                alert("Error Loading");
            },
        });
    } else {
        $("#CloneLayName").disabled = true;
    }
});

//**********************************/

//ADD A LAYOUT

$("#laybtn").on("click", function (event) {
    // To add a new layout
    event.preventDefault();
    let url = window.location.href;
    let layName = $("#layName").val();
    if (layName.length > 0) {
        $("#layName").disabled = false;
        $.ajax({
            url: "/order-keypad-designer/builder/add_layout",
            method: "POST",
            data: {
                input_name: layName,
                keyid: keypad_id,
            },
            async: false,
            success: function (data) {
                //alert(data);
                //console.log(data);
                setTimeout(() => {
                    // alert (url);
                    window.location.href = url;
                }, 300);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error Loading");
            },
        });
    } else {
        $("#layName").disabled = true;
    }
});

//**********************************/

//AUTOSAVE FUNCTION 30 SECS

// setInterval(() => {
//     // To Auto-save layout in 30 sec intervals
//     let old_json_data = JSON.stringify({
//         data: $("#droppable-layout-section").html(),
//     });
//     let rem_quo = old_json_data.replace(/\\"/gi, "`");
//     let json_data = rem_quo.replace(/\\n/gi, "");
//     let data_ref = $(".menu-selected").data("ref");
//     let data_id = data_ref.replace(/\D/g, "");
//     $.ajax({
//         url: "pushjson.php",
//         method: "POST",
//         data: {id: data_id, jdata: json_data},
//         dataType: "html",
//         success: function (data) {
//             console.log("data uploaded");
//         },
//         error: function (jqXHR, textStatus, errorThrown) {
//             console.log("Error Loading");
//         },
//     });
// }, 30000);

//**********************************/

//SHOW PRODS

$(".layout-item").on("click", function () {
    $(".layout-menu").removeClass("layout-menu-blink");
    $(".can-disbl-btns").removeClass("disable-div");
    $(".custom-action-icon").removeClass("disable-div");
    let data_ref = $(this).data("ref");
    let data_id = data_ref.replace(/\D/g, "");
    $.ajax({
        url: "/order-keypad-designer/builder/show_products",
        method: "GET",
        async: false,
        success: function (data) {
            if (data.status === "success") {
                $("#product-source").html(data.data);
                $.ajax({
                    url: base_url + "/js/designer/order/js/layout.js",
                    dataType: "script",
                });
            } else {
                $("#product-source").html("");
                $.ajax({
                    url: base_url + "/js/designer/order/js/layout.js",
                    dataType: "script",
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $("#product-source").html("");
            console.log("Error Loading");
        },
    });

    //**********************************/

    // SHOW FUNCTION

    $.ajax({
        url: "/order-keypad-designer/builder/show_functions",
        method: "GET",
        async: false,
        success: function (data) {
            if (data.status === "success") {
                $("#function-source").html(data.data);
                $.ajax({
                    url: base_url + "/js/designer/order/js/layout.js",
                    dataType: "script",
                });
            } else {
                $("#function-source").html("");
                $.ajax({
                    url: base_url + "/js/designer/order/js/layout.js",
                    dataType: "script",
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $("#function-source").html("");
            console.log("Error Loading");
        },
    });

    //**********************************/

    //SHOW LAYOUT TO DROPABBLE AREA

    $.ajax({
        url: "/order-keypad-designer/builder/pull_json/" + data_id,
        method: "GET",
        async: false,
        success: function (data) {
            if (data.status === "success") {
                let stored_json = data.data;
                let filtered_json = stored_json.replace(/`/gi, "'");
                $("#droppable-layout-section").html(filtered_json);
                $(".exp").draggable(droppedConfig);
                $(".exp").droppable({
                    greedy: false,
                    tolerance: "touch",
                    drop: function (event, ui) {
                        ui.draggable.draggable("option", "revert", true);
                    },
                });
                $.ajax({
                    url: base_url + "/js/designer/order/js/layout.js",
                    dataType: "script",
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $("#droppable-layout-section").html("");
            console.log("Error Loading");
        },
    });

    //**********************************/
});

// save draft
function saveDraft() {
    let old_json_data = JSON.stringify({
        data: $("#droppable-layout-section").html(),
    });
    let rem_quo = old_json_data.replace(/\\"/gi, "`");
    let json_data = rem_quo.replace(/\\n/gi, "");
    let data_ref = $(".menu-selected").data("ref");
    let data_id = data_ref.replace(/\D/g, "");
    $.ajax({
        url: "/order-keypad-designer/builder/push_draft",
        method: "POST",
        data: {
            id: data_id,
            data: json_data,
        },
        success: function (data) {
            console.log("data uploaded");
            launch_toast();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $(".dest").html("");
            alert("Error Loading");
        },
    });
}

// save draft and schedule
function saveDraftScheduleAt() {
    const inputValue = $(".popover-body").find("#set_schedule_at").val();
    let scheduleTime = null;
    if (inputValue) {
        const newTime = new Date(
            $(".popover-body").find("#set_schedule_at").val()
        );
        scheduleTime = newTime.toISOString();
    }

    let old_json_data = JSON.stringify({
        data: $("#droppable-layout-section").html(),
    });
    let rem_quo = old_json_data.replace(/\\"/gi, "`");
    let json_data = rem_quo.replace(/\\n/gi, "");
    let data_ref = $(".menu-selected").data("ref");
    let data_id = data_ref.replace(/\D/g, "");
    $.ajax({
        url: "/order-keypad-designer/builder/push_draft_schedule",
        method: "POST",
        data: {
            id: data_id,
            data: json_data,
            scheduleTime: scheduleTime,
        },
        success: function (data) {
            console.log("data uploaded");
            launch_toast();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $(".dest").html("");
            alert("Error Loading");
        },
    });
}
