$(document).ready(function () {
    $("#set_schedule_at").daterangepicker({
        singleDatePicker: true,
        timePicker: true,
        showDropdowns: true,
        minYear: 1901,
        maxYear: parseInt(moment().format("YYYY"), 10),
        locale: {
            format: "YYYY-MM-DD HH:mm",
        },
    });
    // get history
    $("#historytable").DataTable({
        pageLength: 100,
        columns: [
            { data: "From" },
            { data: "To" },
            { data: "SubmittedAt" },
            { data: "Open" },
        ],
        ajax: {
            url: "/marketing/get_log",
            method: "GET",
            data: function (d) {},
        },
        responsive: true,
        columnDefs: [
            {
                targets: 3,
                data: null,
                className: "text-center",
                render: function (data, type, full, meta) {
                    if (type === "display") {
                        if (data === "no") {
                            data = '<i class="fa fa-eye-slash"></i>';
                        } else {
                            data = '<i class="fa fa-eye"></i>';
                        }
                    }
                    return data;
                },
            },
        ],
    });

    $("#example1").DataTable({
        // stateSave: true,
        columns: [
            { data: "marketingid" },
            { data: "marketingname" },
            { data: "marketingdescription" },
            { data: "marketing_status" },
            { data: "status" },
        ],
        ajax: {
            url: "/marketing/api",
            method: "GET",
            data: function (d) {},
        },
        fnRowCallback: function (nRow, aData, iDisplayIndex) {
            nRow.setAttribute("id", aData["marketingid"]);
        },
        columnDefs: [
            {
                targets: 4,
                width: "10%",
                data: null,
                render: function (data, type, full, meta) {
                    if (type === "display") {
                        if (data === "inactive") {
                            data =
                                '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editMarketing(' +
                                full["marketingid"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" >' +
                                '<i class="fa fa-info"></i>' +
                                "</a> " +
                                '<a data-simpletooltip-text="Send Campaign"  class="js-simple-tooltip profile-btn" onclick="sendTemplate(' +
                                full["marketingid"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" >' +
                                '<i class="fa fa-paper-plane"></i>' +
                                "</a>" +
                                '<a data-simpletooltip-text="De-activate Item"  class="js-simple-tooltip profile-btn-del" onclick="Realert(' +
                                full["marketingid"] +
                                ",`" +
                                full["marketingname"] +
                                '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; background: green;" ><i class="fa fa-check"></i></a>';
                        } else {
                            data =
                                '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editMarketing(' +
                                full["marketingid"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" >' +
                                '<i class="fa fa-info"></i>' +
                                "</a> " +
                                '<a data-simpletooltip-text="Send Campaign"  class="js-simple-tooltip profile-btn" onclick="sendTemplate(' +
                                full["marketingid"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" >' +
                                '<i class="fa fa-paper-plane"></i>' +
                                "</a>" +
                                '<a data-simpletooltip-text="De-activate Item"  class="js-simple-tooltip profile-btn-del" onclick="JSalert(' +
                                full["marketingid"] +
                                ",`" +
                                full["marketingname"] +
                                '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" ><i class="fa fa-times"></i></a>';
                        }
                    }
                    return data;
                },
            },
        ],
    });
    $("#example1_wrapper")
        .find(".dataTables_filter")
        .prepend(
            '<div style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; display: inline-flex;" class="simpletooltip_container">' +
                '<select class="form-control" id="status_show" name="status_show" onchange="getShowStatus(this)">' +
                '<option value="all">All</option>' +
                '<option value="active">Active</option>' +
                '<option value="inactive">Deactive</option>' +
                "</select>" +
                "</div>" +
                '<a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewMarketing()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample">' +
                '<i class="fa fa-plus"></i>' +
                "</a>"
        );
});

$("#schedule_available").on("change", function () {
    console.log($(this).is(":checked"));
    let status = $(this).is(":checked");
    if (status) {
        $("#schedule_section").show();
    } else {
        $("#schedule_section").hide();
    }
});

function getShowStatus(item) {
    $("#example1")
        .DataTable()
        .ajax.url("marketing/api?status=" + item.value)
        .load();
}

function Realert(id, protitle) {
    swal(
        {
            title: protitle + " will be reactivated",
            text: "Are you sure to proceed?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes Re-Activate!",
            cancelButtonText: "No Take me back!",
            closeOnConfirm: false,
            closeOnCancel: false,
        },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    method: "PUT",
                    url: "/marketing/api/" + id,
                    data: {
                        status: "active",
                    },
                }).done(function (data) {
                    if (data.status === "success") {
                        swal(
                            {
                                title: protitle + "  reactivated",
                                text: "If this was a mistake you can re-activate the item in the inactive stocklist screen!",
                                type: "success",
                            },
                            function (isConfirm) {
                                $("#status_show").val("all");
                                $("#example1")
                                    .DataTable()
                                    .ajax.url("marketing/api")
                                    .load();
                            }
                        );
                    } else {
                        swal(
                            {
                                title: protitle + " can't be deactivated",
                                text: "This stock item is currently active on a keypad, please remove it from all keypads first then reactivate again!",
                                type: "warning",
                            },
                            function (isConfirm) {
                                $("#status_show").val("all");
                                $("#example1")
                                    .DataTable()
                                    .ajax.url("marketing/api")
                                    .load();
                            }
                        );
                    }
                });
            } else {
                swal("Phew that was close!", "Nothing has changed!", "error");
            }
        }
    );
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
                    url: "/marketing/api/" + id,
                    method: "DELETE",
                })
                    .done(function (data) {
                        if (data.status === "success") {
                            swal(
                                {
                                    title: protitle + "  deactivated",
                                    text: "If this was a mistake you can re-activate the item in the inactive marketing screen!",
                                    type: "success",
                                },
                                function (isConfirm) {
                                    $(".profile-btn-snd").click();
                                    $("#status_show").val("all");
                                    $("#example1")
                                        .DataTable()
                                        .ajax.url("marketing/api")
                                        .load();
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
                                    $(".profile-btn-snd").click();
                                    $("#status_show").val("all");
                                    $("#example1")
                                        .DataTable()
                                        .ajax.url("marketing/api")
                                        .load();
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

function addNewMarketing() {
    tempId = "";
    $("#temptitle").html("New Marketing");
    $(".savebut").show();
    $(".updatebut").hide();
    $("#marketingname").val("");
    $("#marketingdescription").val("");
    $("#template_id").val("no");
    $("#tag_id").val("no");
    $("#schedule_available").prop("checked", false);
    $("#schedule_section").hide();
    let scheduleDate = new Date();

    scheduleDate =
        scheduleDate.getFullYear() +
        "-" +
        ("0" + (scheduleDate.getMonth() + 1)).slice(-2) +
        "-" +
        ("0" + scheduleDate.getDate()).slice(-2) +
        " " +
        ("0" + scheduleDate.getHours()).slice(-2) +
        ":" +
        ("0" + scheduleDate.getMinutes()).slice(-2);
    $("#set_schedule_at").val(scheduleDate);
    $("#historytable").DataTable().ajax.url("marketing/get_log").load();
}

function getFormData($form) {
    let unindexed_array = $form.serializeArray();
    let indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        indexed_array[n["name"]] = n["value"];
    });
    let status = $("#schedule_available").is(":checked");
    if (status) {
        indexed_array["schedule_available"] = "true";
        const inputValue = $("#set_schedule_at").val();
        let scheduleTime = null;
        if (inputValue) {
            const newTime = new Date($("#set_schedule_at").val());
            scheduleTime = newTime.toISOString();
        }
        indexed_array["schedule_at"] = scheduleTime;
    } else {
        indexed_array["schedule_available"] = "false";
        indexed_array["schedule_at"] = null;
    }

    return indexed_array;
}

function addMarketing() {
    let $form = $("#marketingForm");

    if (
        $("#marketingname").val() !== "" &&
        $("#marketingdescription").val() !== ""
    ) {
        $.ajax({
            type: "POST",
            url: "/marketing/api",
            data: {
                _token: csrfToken,
                data: getFormData($form),
            },
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("add event success", "Success", {
                        timeOut: 3000,
                    });
                    $(".profile-btn-snd").click();
                    $("#status_show").val("all");
                    $("#example1").DataTable().ajax.url("marketing/api").load();
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

function updateMarketing() {
    let $form = $("#marketingForm");

    if ($form.validate().form()) {
        $.ajax({
            type: "PUT",
            url: "/marketing/api/" + tempId,
            data: getFormData($form),
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("update event success", "Success", {
                        timeOut: 3000,
                    });
                    $(".profile-btn-snd").click();
                    $("#status_show").val("all");
                    $("#example1").DataTable().ajax.url("marketing/api").load();
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

function editMarketing(id) {
    tempId = id;
    $(".savebut").hide();
    $(".updatebut").show();
    $("#temptitle").html("Edit Marketing");

    $.ajax({
        url: "/marketing/api",
        data: {
            id: id,
        },
        method: "GET",
    })
        .done(function (data) {
            if (data.status === "success") {
                let Marketing_data = data.data[0];
                $("#marketingname").val(Marketing_data["marketingname"]);
                $("#marketingdescription").val(
                    Marketing_data["marketingdescription"]
                );
                $("#template_id").val(Marketing_data["template_id"]);
                $("#tag_id").val(Marketing_data["tag_id"]);

                if (Marketing_data["schedule_available"] === "true") {
                    $("#schedule_available").prop("checked", true);
                    $("#schedule_section").show();
                    let scheduleDate = new Date(Marketing_data["schedule_at"]);

                    scheduleDate =
                        scheduleDate.getFullYear() +
                        "-" +
                        ("0" + (scheduleDate.getMonth() + 1)).slice(-2) +
                        "-" +
                        ("0" + scheduleDate.getDate()).slice(-2) +
                        " " +
                        ("0" + scheduleDate.getHours()).slice(-2) +
                        ":" +
                        ("0" + scheduleDate.getMinutes()).slice(-2);
                    $("#set_schedule_at").val(scheduleDate);
                } else {
                    $("#schedule_available").prop("checked", false);
                    $("#schedule_section").hide();
                }
                $("#historytable")
                    .DataTable()
                    .ajax.url("marketing/get_log")
                    .load();
            } else {
                toastr.error("add event failed", "error", { timeOut: 3000 });
            }
        })
        .fail(function (jqXHR, status, errorThrown) {
            alert(status + "<br>" + errorThrown);
        });
}

function sendTemplate(marketingId) {
    $.ajax({
        url: "/marketing/send_mail",
        data: {
            marketingId: marketingId,
        },
        method: "POST",
    })
        .done(function (data) {
            if (data.status === "success") {
                toastr.success("send mail success", "success", {
                    timeOut: 3000,
                });
                $.ajax({
                    url: "/marketing/check_open",
                    data: {
                        marketingId: marketingId,
                    },
                    method: "POST",
                })
                    .done(function (data) {
                        if (data.status === "success") {
                            console.log("success");
                        } else {
                            toastr.error(data.message, "error", {
                                timeOut: 3000,
                            });
                        }
                    })
                    .fail(function (jqXHR, status, errorThrown) {
                        alert(status + "<br>" + errorThrown);
                    });
            } else {
                toastr.error(data.message, "error", { timeOut: 3000 });
            }
        })
        .fail(function (jqXHR, status, errorThrown) {
            alert(status + "<br>" + errorThrown);
        });
}

setInterval(function () {
    $("#example1").DataTable().ajax.url("marketing/api").load();
}, 1000 * 60);
