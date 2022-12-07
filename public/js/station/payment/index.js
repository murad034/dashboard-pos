let tempId;
let onlinePng = base_url + "img/Online.png";
let offlinePng = base_url + "img/Offline.png";
function buildDropdown(drp_down, data, value, opt, o_ption = null) {
    drp_down.empty();
    for (const element of data) {
        if (o_ption === null) {
            drp_down.append(
                '<option value="' +
                    element[value] +
                    '">' +
                    element[opt] +
                    "</option>"
            );
            drp_down.val(data[0][value]).change();
        } else {
            if (o_ption === element[value]) {
                drp_down.append(
                    '<option value="' +
                        element[value] +
                        '">' +
                        element[opt] +
                        "</option>"
                );
                drp_down.val(element[value]).change();
            } else {
                drp_down.append(
                    '<option value="' +
                        element[value] +
                        '">' +
                        element[opt] +
                        "</option>"
                );
            }
        }
    }
}

$("#terminalkeypad").change(function () {
    $.ajax({
        type: "GET",
        url: "/order-payment-station/table-api",
        data: {
            id: $(this).val(),
        },
        success: function (data) {
            if (data.status === "success") {
                let keypad_list = data.data;
                buildDropdown(
                    $("#homelayout"),
                    keypad_list,
                    "layoutid",
                    "data"
                );
            } else {
                toastr.error("event failed", "error", { timeOut: 3000 });
            }
        },
        error: function () {
            toastr.error("event failed", "error", { timeOut: 3000 });
        },
    });
});

$(function () {
    $("#allocateditem").select2();
    $.ajax({
        type: "GET",
        url: "/order-payment-station/table-api",
        success: function (data) {
            if (data.status === "success") {
                let keypad_list = data.data;
                buildDropdown(
                    $("#terminalkeypad"),
                    keypad_list,
                    "keypadid",
                    "keypadname"
                );
            } else {
                toastr.error("event failed", "error", { timeOut: 3000 });
            }
        },
        error: function () {
            toastr.error("event failed", "error", { timeOut: 3000 });
        },
    });

    $("#example1").DataTable({
        // stateSave: true,
        columns: [
            { data: "terminalid" },
            { data: "terminalname" },
            { data: "keypads.keypadname" },
            { data: "layouts.data" },
            { data: "locations.locationname" },
            { data: "online" },
            { data: "status" },
        ],
        ajax: {
            url: "/order-payment-station/get-table",
            method: "GET",
            data: function (d) {},
        },
        columnDefs: [
            {
                targets: 5,
                data: null,
                className: "text-center",
                render: function (data, type, full, meta) {
                    if (type === "display") {
                        if (data === "on") {
                            data =
                                '<img src="' +
                                onlinePng +
                                '" alt="online" width="40" height="40">';
                        } else {
                            data =
                                '<img src="' +
                                offlinePng +
                                '" alt="offline" width="40" height="40">';
                        }
                    }
                    return data;
                },
            },

            {
                targets: 6,
                data: null,
                render: function (data, type, full, meta) {
                    if (type === "display") {
                        data =
                            '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editTerminal(' +
                            full["terminalid"] +
                            ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a> <a data-simpletooltip-text="De-activate Item"  class="js-simple-tooltip profile-btn-del" onclick="JSalert(' +
                            full["terminalid"] +
                            ",`" +
                            full["terminalname"] +
                            '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" ><i class="fa fa-times"></i></a>';
                    }
                    return data;
                },
            },
        ],
    });
    $("#example1_wrapper")
        .find(".dataTables_filter")
        .prepend(
            '<a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewTerminal()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"><i class="fa fa-plus"></i></a>'
        );
});

function getShowStatus(item) {
    $("#example1")
        .DataTable()
        .ajax.url("api?status=" + item.value)
        .load();
}

function JSalert(id, protitle) {
    swal(
        {
            title: protitle + " will be deactivated",
            text: "Are you sure to proceed?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes Deactivate!",
            cancelButtonText: "No Take me back!",
            closeOnConfirm: false,
            closeOnCancel: false,
        },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "/order-payment-station/api/" + id,
                    method: "DELETE",
                })
                    .done(function (data) {
                        if (data.status === "success") {
                            swal(
                                {
                                    title: protitle + "  deactivated",
                                    text: "If this was a mistake you can re-activate the item in the inactive stocklist screen!",
                                    type: "success",
                                },
                                function (isConfirm) {
                                    location.reload();
                                }
                            );
                        } else {
                            swal(
                                {
                                    title: protitle + " can't be deactivated",
                                    text: "This stock item is currently active on a keypad, please remove it from all keypads first then deactivate again!",
                                    type: "warning",
                                },
                                function (isConfirm) {
                                    location.reload();
                                }
                            );
                        }
                    })
                    .fail(function (jqXHR, status, errorThrown) {
                        alert(errorThrown);
                    });
            } else {
                swal("Phew that was close!", "Nothing has changed!", "error");
            }
        }
    );
}

function addNewTerminal() {
    tempId = "";
    $("#temptitle").html("New Terminal");
    $(".savebut").show();
    $(".updatebut").hide();
    $("#terminalname").val("");
    $("#allocateditem").val("");
    $("#allocateditem").trigger("change");
    $.ajax({
        type: "GET",
        url: "/order-payment-station/table-api",
        success: function (data) {
            if (data.status === "success") {
                let keypad_list = data.data;
                buildDropdown(
                    $("#terminalkeypad"),
                    keypad_list,
                    "keypadid",
                    "keypadname"
                );
            } else {
                toastr.error("event failed", "error", { timeOut: 3000 });
            }
        },
        error: function () {
            toastr.error("event failed", "error", { timeOut: 3000 });
        },
    });
    $("#pricetier").val("rrp");
    $("#printreceipt").val("yes");
}

function getFormData($form) {
    let unindexed_array = $form.serializeArray();
    let indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        indexed_array[n["name"]] = n["value"];
    });

    indexed_array["allocateditem"] = $("#allocateditem").val().join(",");
    indexed_array["syncid"] = "LOC" + indexed_array["locationid"];

    return indexed_array;
}

function addTerminal() {
    let $form = $("#terminalForm");

    if ($("#terminalname").val() !== "" && $("#terminalkeypad").val() !== "") {
        $.ajax({
            type: "POST",
            url: "/order-payment-station/api",
            data: {
                _token: csrfToken,
                data: getFormData($form),
            },
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("add event success", "Success", {
                        timeOut: 3000,
                    });
                    location.reload();
                } else {
                    toastr.error("add event failed", "error", {
                        timeOut: 3000,
                    });
                }
            },
            error: function () {
                toastr.error("add event failed", "error", { timeOut: 3000 });
            },
        });
    }
}

function updateTerminal() {
    let $form = $("#terminalForm");

    if ($form.validate().form()) {
        $.ajax({
            type: "PUT",
            url: "/order-payment-station/api/" + tempId,
            data: getFormData($form),
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("update event success", "Success", {
                        timeOut: 3000,
                    });
                    location.reload();
                } else {
                    toastr.error("update event failed", "error", {
                        timeOut: 3000,
                    });
                }
            },
            error: function () {
                toastr.error("update event failed", "error", { timeOut: 3000 });
            },
        });
    }
}

function editTerminal(id) {
    tempId = id;
    $(".savebut").hide();
    $(".updatebut").show();
    $("#temptitle").html("Edit Terminal");

    $.ajax({
        url: "/order-payment-station/api/" + id,
        method: "GET",
    })
        .done(function (data) {
            if (data.status === "success") {
                let terminal_data = data.data[0];
                $("#terminalname").val(terminal_data["terminalname"]);
                $("#terminalkeypad").val(terminal_data["terminalkeypad"]);
                $.ajax({
                    type: "GET",
                    url: "/order-payment-station/table-api",
                    data: {
                        id: terminal_data["terminalkeypad"],
                    },
                    success: function (data) {
                        if (data.status === "success") {
                            let keypad_list = data.data;
                            buildDropdown(
                                $("#homelayout"),
                                keypad_list,
                                "layoutid",
                                "data"
                            );
                        } else {
                            toastr.error("event failed", "error", {
                                timeOut: 3000,
                            });
                        }
                    },
                    error: function () {
                        toastr.error("event failed", "error", {
                            timeOut: 3000,
                        });
                    },
                });
                $("#homelayout").val(terminal_data["homelayout"]);
                $("#locationid").val(terminal_data["locationid"]);
                $("#pricetier").val(terminal_data["pricetier"]);
                $("#printreceipt").val(terminal_data["printreceipt"]);
                let itemData = terminal_data["allocateditem"].split(",");
                $("#allocateditem").val(itemData);
                $("#allocateditem").trigger("change");
            } else {
                toastr.error("add event failed", "error", { timeOut: 3000 });
            }
        })
        .fail(function (jqXHR, status, errorThrown) {
            alert(status + "<br>" + errorThrown);
        });
}

setInterval(function () {
    $("#example1")
        .DataTable()
        .ajax.url("order-payment-station/get-table")
        .load();
}, 1000 * 60);
