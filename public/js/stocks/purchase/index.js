let tempId;
$(document).ready(function () {
    $("#example1").DataTable({
        // stateSave: true,
        columns: [
            { data: "purchase_id" },
            { data: "purchase_name" },
            { data: "purchase_description" },
            { data: "status" },
        ],
        ajax: {
            url: "/purchase/api",
            method: "GET",
            data: function (d) {},
        },
        fnRowCallback: function (nRow, aData, iDisplayIndex) {
            nRow.setAttribute("id", aData["purchase_id"]);
        },
        columnDefs: [
            {
                targets: 3,
                width: "10%",
                data: null,
                render: function (data, type, full, meta) {
                    if (type === "display") {
                        data =
                            '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editPurchase(' +
                            full["purchase_id"] +
                            ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a> <a data-simpletooltip-text="De-activate Item"  class="js-simple-tooltip profile-btn-del" onclick="JSalert(' +
                            full["purchase_id"] +
                            ",`" +
                            full["quote_name"] +
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
            '<a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewPurchase()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"><i class="fa fa-plus"></i></a> '
        );
});

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
                    url: "/purchase/api/" + id,
                    method: "DELETE",
                })
                    .done(function (data) {
                        if (data.status === "success") {
                            swal(
                                {
                                    title: protitle + "  deactivated",
                                    text: "If this was a mistake you can re-activate the item in the inactive purchase screen!",
                                    type: "success",
                                },
                                function (isConfirm) {
                                    $("#example1")
                                        .DataTable()
                                        .ajax.url("purchase/api")
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
                                    $("#example1")
                                        .DataTable()
                                        .ajax.url("purchase/api")
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

function addNewPurchase() {
    tempId = "";
    $("#temptitle").html("New Purchase");
    $(".savebut").show();
    $(".updatebut").hide();
    $("#purchase_name").val("");
    $("#purchase_description").val("");
}

function getFormData($form) {
    let unindexed_array = $form.serializeArray();
    let indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        indexed_array[n["name"]] = n["value"];
    });
    return indexed_array;
}

function addPurchase() {
    let $form = $("#purchaseForm");

    if (
        $("#purchase_name").val() !== "" &&
        $("#purchase_description").val() !== ""
    ) {
        $.ajax({
            type: "POST",
            url: "/purchase/api",
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
                    $("#example1").DataTable().ajax.url("purchase/api").load();
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

function updatePurchase() {
    let $form = $("#purchaseForm");

    if ($form.validate().form()) {
        $.ajax({
            type: "PUT",
            url: "/purchase/api/" + tempId,
            data: getFormData($form),
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("update event success", "Success", {
                        timeOut: 3000,
                    });
                    $(".profile-btn-snd").click();
                    $("#example1").DataTable().ajax.url("purchase/api").load();
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

function editPurchase(id) {
    tempId = id;
    $(".savebut").hide();
    $(".updatebut").show();
    $("#temptitle").html("Edit Purchase");

    $.ajax({
        url: "/purchase/api",
        data: {
            id: id,
        },
        method: "GET",
    })
        .done(function (data) {
            if (data.status === "success") {
                let purchaseData = data.data[0];
                $("#purchase_name").val(purchaseData["purchase_name"]);
                $("#purchase_description").val(
                    purchaseData["purchase_description"]
                );
            } else {
                toastr.error("add event failed", "error", { timeOut: 3000 });
            }
        })
        .fail(function (jqXHR, status, errorThrown) {
            alert(status + "<br>" + errorThrown);
        });
}
