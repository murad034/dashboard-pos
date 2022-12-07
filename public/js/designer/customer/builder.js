let editor = $("#edit");
const isLive = getParameter("isLive");
$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: "/customer-receipt-designer/editor/edit-api/" + template_id,
        async: false,
        success: function (data) {
            if (data.status === "success") {
                if (isLive === "true") {
                    $("#edit-design-panel").prepend("<h2>Live Data</h2>");
                    if (
                        data.data[0]["rectemplate"] === undefined ||
                        data.data[0]["rectemplate"] === null
                    ) {
                        insertText(edit, "");
                    } else {
                        insertText(edit, data.data[0]["rectemplate"]);
                    }
                } else {
                    $("#edit-design-panel").prepend("<h2>Draft Data</h2>");
                    if (
                        data.data[0]["draft"] === undefined ||
                        data.data[0]["draft"] === null
                    ) {
                        insertText(edit, "");
                    } else {
                        insertText(edit, data.data[0]["draft"]);
                    }
                    $("#saveTemplate").text("Publish");
                    $("#saveReceipt").text("Resave");
                    if (data.data[0]["scheduleAt"]) {
                        $("#saveReceiptWithSchedulePopUp").text("Reschedule");
                    }
                }
            } else {
                toastr.error("add event failed", "error", {
                    timeOut: 3000,
                });
            }
        },
        error: function () {
            toastr.error("add event failed", "error", {
                timeOut: 3000,
            });
        },
    });
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
initialize();

window.onbeforeunload = function () {
    // Your Code here
    return null; // return null to avoid pop up
};

function saveReceipt() {
    let data = {};
    if (isLive === "true") {
        data = {
            rectemplate: editor.val(),
        };
    } else {
        data = {
            rectemplate: editor.val(),
            draft: null,
        };
    }
    $.ajax({
        type: "POST",
        url: "/customer-receipt-designer/editor/edit-api",
        data: {
            _token: csrfToken,
            data: {
                id: template_id,
                data: data,
            },
        },
        async: false,
        success: function (data) {
            if (data.status === "success") {
                toastr.success("add event success", "Success", {
                    timeOut: 3000,
                });
                window.location.href =
                    window.location.origin + "/customer-receipt-designer";
            } else {
                toastr.error("add event failed", "error", {
                    timeOut: 3000,
                });
            }
        },
        error: function () {
            toastr.error("add event failed", "error", {
                timeOut: 3000,
            });
        },
    });
}

function saveReceiptDraft() {
    $.ajax({
        type: "POST",
        url: "/customer-receipt-designer/editor/edit-api/draft",
        data: {
            _token: csrfToken,
            data: {
                id: template_id,
                data: {
                    draft: editor.val(),
                },
                scheduleAt: null,
            },
        },
        async: false,
        success: function (data) {
            if (data.status === "success") {
                toastr.success("add event success", "Success", {
                    timeOut: 3000,
                });
                window.location.href =
                    window.location.origin + "/customer-receipt-designer";
            } else {
                toastr.error(data.message, "error", {
                    timeOut: 3000,
                });
            }
        },
        error: function (errorData) {
            toastr.error(errorData.message, "error", {
                timeOut: 3000,
            });
        },
    });
}

function saveReceiptDraftWithScheduleAt() {
    const inputValue = $(".popover-body").find("#set_schedule_at").val();
    let scheduleTime = null;
    if (inputValue) {
        const newTime = new Date(
            $(".popover-body").find("#set_schedule_at").val()
        );
        scheduleTime = newTime.toISOString();
    }
    $.ajax({
        type: "POST",
        url: "/customer-receipt-designer/editor/edit-api/draft-scheduleat",
        data: {
            _token: csrfToken,
            data: {
                id: template_id,
                data: {
                    draft: editor.val(),
                },
                scheduleAt: scheduleTime,
            },
        },
        async: false,
        success: function (data) {
            if (data.status === "success") {
                toastr.success("add event success", "Success", {
                    timeOut: 3000,
                });
                window.location.href =
                    window.location.origin + "/customer-receipt-designer";
            } else {
                toastr.error(data.message, "error", {
                    timeOut: 3000,
                });
            }
        },
        error: function (errorData) {
            toastr.error(errorData.message, "error", {
                timeOut: 3000,
            });
        },
    });
}

function getParameter(p) {
    var url = window.location.search.substring(1);
    var varUrl = url.split("&");
    for (var i = 0; i < varUrl.length; i++) {
        var parameter = varUrl[i].split("=");
        if (parameter[0] == p) {
            return parameter[1];
        }
    }
}
