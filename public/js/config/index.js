let mailElem = $("#send_mail");
let mailButton = $("#verify-mail");
let resendSection = $("#resend-section");
let resendButton = $("#resend-mail");
$(document).ready(function () {
    resendSection.hide();
    let timezoneSelect = $("#time_zone").timezones();
    timezoneSelect.val(timezoneVal);
    $.ajax({
        type: "GET",
        url: "/config/mail-confirm",
        async: false,
        success: function (data) {
            if (data.status === "success") {
                if (data.action === "confirm") {
                    mailButton.text("Confirm");
                    mailElem.attr("readonly", true);
                    resendSection.show();
                } else {
                    mailButton.text("Delete");
                    mailElem.addClass("is-valid");
                    mailElem.attr("readonly", true);
                }
            } else {
                toastr.error(data.message, data.errorCode, {
                    timeOut: 3000,
                });
            }
        },
        error: function () {
            toastr.error("confirm mail failed", "error", {
                timeOut: 3000,
            });
        },
    });
});
// resend the email
resendButton.click(function (event) {
    event.preventDefault();
    $.ajax({
        type: "POST",
        url: "/config/verify-mail",
        data: {
            action: "Resend",
        },
        async: false,
        success: function (data) {
            if (data.status === "success") {
                toastr.success(data.data.Message, "Success", {
                    timeOut: 3000,
                });
            } else {
                toastr.error(data.message, data.errorCode, {
                    timeOut: 3000,
                });
            }
        },
        error: function () {
            toastr.error("resend mail failed", "error", {
                timeOut: 3000,
            });
            if (mailElem.hasClass("is-invalid")) {
            } else {
                mailElem.removeClass("is-valid");
                mailElem.addClass("is-invalid");
            }
        },
    });
});

// verify, confirm, delete the email function

mailButton.click(function (event) {
    event.preventDefault();
    mailButton.prop("disabled", true);
    let action = $(this).text().trim();
    let sendMail = mailElem.val();
    switch (action) {
        case "Verify":
            $.ajax({
                type: "POST",
                url: "/config/verify-mail",
                data: {
                    action: action,
                    mail: sendMail,
                    name: AppName,
                },
                async: false,
                success: function (data) {
                    if (data.status === "success") {
                        toastr.success("verify mail success", "Success", {
                            timeOut: 3000,
                        });
                        mailButton.text("Confirm");
                        mailElem.attr("readonly", true);
                        resendSection.show();
                    } else {
                        toastr.error(data.message, data.errorCode, {
                            timeOut: 3000,
                        });
                    }
                },
                error: function () {
                    toastr.error("update signature failed", "error", {
                        timeOut: 3000,
                    });
                    if (mailElem.hasClass("is-invalid")) {
                    } else {
                        mailElem.removeClass("is-valid");
                        mailElem.addClass("is-invalid");
                    }
                },
            });
            break;
        case "Confirm":
            $.ajax({
                type: "POST",
                url: "/config/verify-mail",
                data: {
                    action: action,
                },
                async: false,
                success: function (data) {
                    if (data.status === "success") {
                        toastr.success("confirm mail success", "Success", {
                            timeOut: 3000,
                        });
                        mailElem.addClass("is-valid");
                        mailButton.text("Delete");
                    } else {
                        toastr.error(data.message, data.errorCode, {
                            timeOut: 3000,
                        });
                    }
                },
                error: function () {
                    toastr.error("confirm signature failed", "error", {
                        timeOut: 3000,
                    });
                    if (mailElem.hasClass("is-invalid")) {
                    } else {
                        mailElem.removeClass("is-valid");
                        mailElem.addClass("is-invalid");
                    }
                },
            });
            break;
        case "Delete":
            $.ajax({
                type: "POST",
                url: "/config/verify-mail",
                data: {
                    action: action,
                },
                async: false,
                success: function (data) {
                    if (data.status === "success") {
                        toastr.success(
                            data.data.Message,

                            "Success",
                            {
                                timeOut: 3000,
                            }
                        );
                        mailButton.text("Verify");
                        resendSection.hide();
                        mailElem.attr("readonly", false);
                        if (mailElem.hasClass("is-invalid")) {
                            mailElem.removeClass("is-invalid");
                        } else if (mailElem.hasClass("is-valid")) {
                            mailElem.removeClass("is-valid");
                        }
                    } else {
                        toastr.error(data.message, data.errorCode, {
                            timeOut: 3000,
                        });
                    }
                },
                error: function () {
                    toastr.error("delete signature failed", "error", {
                        timeOut: 3000,
                    });
                    if (mailElem.hasClass("is-invalid")) {
                    } else {
                        mailElem.removeClass("is-valid");
                        mailElem.addClass("is-invalid");
                    }
                },
            });
            break;
        default:
            break;
    }
    mailButton.prop("disabled", false);
});

function updateToken() {
    if (confirm("Are you sure update the token?")) {
        $.ajax({
            type: "POST",
            url: "/config/update-token",
            data: {
                id: user_id,
            },
            success: function (data) {
                if (data.status === "success") {
                    $("#api_token").val(data.data);
                    toastr.success("token update success", "Success", {
                        timeOut: 3000,
                    });
                } else {
                    toastr.error("update token failed", "error", {
                        timeOut: 3000,
                    });
                }
            },
            error: function () {
                toastr.error("update token failed", "error", { timeOut: 3000 });
            },
        });
    } else {
        toastr.warning("update token canceled.");
    }
}

function getFormData($form) {
    let formData = new FormData();
    formData.append("config_id", parseInt(configId));
    let fileUpload = $("#logo_background");
    if (fileUpload[0].value === "" || fileUpload[0].value === null) {
    } else {
        formData.append("logo_background", fileUpload[0].files[0]);
    }
    let fileUpload1 = $("#logo_internal");
    if (fileUpload1[0].value === "" || fileUpload1[0].value === null) {
    } else {
        formData.append("logo_internal", fileUpload1[0].files[0]);
    }
    let fileUpload2 = $("#logo_icon");
    if (fileUpload2[0].value === "" || fileUpload2[0].value === null) {
    } else {
        formData.append("logo_icon", fileUpload2[0].files[0]);
    }

    let unindexed_array = $form.serializeArray();
    let indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        indexed_array[n["name"]] = n["value"];
        formData.append(n["name"], n["value"]);
    });
    return formData;
}

$("#save-config").click(function (event) {
    event.preventDefault();
    let $form = $("#configForm");

    $.ajax({
        type: "POST",
        url: "/config/update",
        data: getFormData($form),
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.status === "success") {
                toastr.success("save event success", "Success", {
                    timeOut: 3000,
                });
            } else {
                toastr.error("save event failed", "error", {
                    timeOut: 3000,
                });
            }
        },
        error: function () {
            toastr.error("save event failed", "error", { timeOut: 3000 });
        },
    });
});
