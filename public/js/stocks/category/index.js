let tempMainId;
$(document).ready(function () {
    $("#upload_main_file").click(function () {
        $("#upload_main_form").submit();
        $("#upload_main_file").attr("disabled", "disabled");
    });

    $("#upload_main_form").on("submit", function (event) {
        event.preventDefault();
        $.ajax({
            url: "/stock-categories/cat-upload",
            method: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if (data.error !== "") {
                    $("#message").html(
                        '<div class="alert alert-danger">' +
                            data.error +
                            "</div>"
                    );
                } else {
                    $("#process_main_area").html(data.output);
                    $("#upload_main_area").css("display", "none");
                    $("#upload_main_file").hide();
                    $("#import_main").show();
                }
            },
        });
    });

    let column_data = {};

    let total_selection = 0;

    $(document).on("change", ".set_column_data", function () {
        let column_name = $(this).val();

        let column_number = $(this).data("column_number");

        for (let key in column_data) {
            if (column_data.hasOwnProperty(key)) {
                if (column_data[key] === column_number) {
                    delete column_data[key];
                }
            }
        }

        if (column_name in column_data) {
            alert("You have already define " + column_name + " column");

            $(this).val("");

            return false;
        }

        if (column_name !== "") {
            column_data[column_name] = column_number;
        } else {
            const entries = Object.entries(column_data);

            for (const [key, value] of entries) {
                if (value === column_number) {
                    delete column_data[key];
                }
            }
        }

        total_selection = Object.keys(column_data).length;

        if (total_selection > 0) {
            $("#import_main").attr("disabled", false);
        } else {
            $("#import_main").attr("disabled", "disabled");
        }
    });

    $(document).on("click", "#import_main", function (event) {
        if (!("catid" in column_data)) {
            toastr.error("you must set catid column", "error", {
                timeOut: 3000,
            });
            return false;
        } else if (!("catagoryname" in column_data)) {
            toastr.error("you must set categoryname column", "error", {
                timeOut: 3000,
            });
            return false;
        } else {
            event.preventDefault();

            $.ajax({
                url: "/stock-categories/cat-import",
                method: "POST",
                data: column_data,
                success: function (data) {
                    if (data.status === "success") {
                        toastr.success("csv import success", "Success", {
                            timeOut: 3000,
                        });
                        location.reload();
                    }
                },
            });
        }
    });

    // import and upload sub category csv

    $("#upload_sub_file").click(function () {
        $("#upload_sub_form").submit();
        $("#upload_sub_file").attr("disabled", "disabled");
    });

    $("#upload_sub_form").on("submit", function (event) {
        event.preventDefault();
        $.ajax({
            url: "/stock-categories/sub-upload",
            method: "POST",
            data: new FormData(this),
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if (data.error !== "") {
                    $("#message").html(
                        '<div class="alert alert-danger">' +
                            data.error +
                            "</div>"
                    );
                } else {
                    $("#process_sub_area").html(data.output);
                    $("#upload_sub_area").css("display", "none");
                    $("#upload_sub_file").hide();
                    $("#import_sub").show();
                }
            },
        });
    });

    $(document).on("change", ".set_column_data", function () {
        let column_name = $(this).val();

        let column_number = $(this).data("column_number");

        for (let key in column_data) {
            if (column_data.hasOwnProperty(key)) {
                if (column_data[key] === column_number) {
                    delete column_data[key];
                }
            }
        }

        if (column_name in column_data) {
            alert("You have already define " + column_name + " column");

            $(this).val("");

            return false;
        }

        if (column_name !== "") {
            column_data[column_name] = column_number;
        } else {
            const entries = Object.entries(column_data);

            for (const [key, value] of entries) {
                if (value === column_number) {
                    delete column_data[key];
                }
            }
        }

        total_selection = Object.keys(column_data).length;

        if (total_selection > 0) {
            $("#import_sub").attr("disabled", false);
        } else {
            $("#import_sub").attr("disabled", "disabled");
        }
    });

    $(document).on("click", "#import_sub", function (event) {
        if (!("subcatid" in column_data)) {
            toastr.error("you must set subcatid column", "error", {
                timeOut: 3000,
            });
            return false;
        } else if (!("subcatagoryname" in column_data)) {
            toastr.error("you must set subcategoryname column", "error", {
                timeOut: 3000,
            });
            return false;
        } else {
            event.preventDefault();

            $.ajax({
                url: "/stock-categories/sub-import",
                method: "POST",
                data: column_data,
                success: function (data) {
                    if (data.status === "success") {
                        toastr.success("csv import success", "Success", {
                            timeOut: 3000,
                        });
                        location.reload();
                    }
                },
            });
        }
    });

    $("#example1").DataTable({
        columns: [{ data: "catagoryname" }, { data: "status" }],
        ajax: {
            url: "/stock-categories/cat-api",
            method: "GET",
        },
        columnDefs: [
            {
                targets: 1,
                data: null,
                render: function (data, type, full, meta) {
                    if (type === "display") {
                        if (data === "inactive") {
                            data =
                                '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editMainCat(' +
                                full["catid"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a><a data-simpletooltip-text="Re-activate Item"  class="js-simple-tooltip profile-btn" onclick="Realert(' +
                                full["catid"] +
                                ",`" +
                                full["catagoryname"] +
                                '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; background: green;" ><i class="fa fa-check"></i></a>';
                        } else {
                            data =
                                '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editMainCat(' +
                                full["catid"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a> <a data-simpletooltip-text="De-activate Item"  class="js-simple-tooltip profile-btn-del" onclick="JSalert(' +
                                full["catid"] +
                                ",`" +
                                full["catagoryname"] +
                                '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" ><i class="fa fa-times"></i></a>';
                        }
                    }
                    return data;
                },
            },
        ],
    });
    $("#example12").DataTable({
        columns: [{ data: "subcatagoryname" }, { data: "status" }],
        ajax: {
            url: "/stock-categories/sub-api",
            method: "GET",
        },
        columnDefs: [
            {
                targets: 1,
                data: null,
                render: function (data, type, full, meta) {
                    if (type === "display") {
                        if (data === "inactive") {
                            data =
                                '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample2" aria-controls="offcanvasExample2" onclick="editSubCat(' +
                                full["subcatid"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a><a data-simpletooltip-text="Re-activate Item"  class="js-simple-tooltip profile-btn" onclick="ReSubalert(' +
                                full["subcatid"] +
                                ",`" +
                                full["subcatagoryname"] +
                                '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; background: green;" ><i class="fa fa-check"></i></a>';
                        } else {
                            data =
                                '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample2" aria-controls="offcanvasExample2" onclick="editSubCat(' +
                                full["subcatid"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a> <a data-simpletooltip-text="De-activate Item"  class="js-simple-tooltip profile-btn-del" onclick="JSSubalert(' +
                                full["subcatid"] +
                                ",`" +
                                full["subcatagoryname"] +
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
                '<a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewMainCat()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample">' +
                '<i class="fa fa-plus"></i>' +
                "</a>"
        );
    $("#example12_wrapper")
        .find(".dataTables_filter")
        .prepend(
            '<div style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; display: inline-flex;" class="simpletooltip_container">' +
                '<select class="form-control" id="status_show1" name="status_show1" onchange="getShowStatus1(this)">' +
                '<option value="all">All</option>' +
                '<option value="active">Active</option>' +
                '<option value="inactive">Deactive</option>' +
                "</select>" +
                "</div>" +
                '<a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewSubCat()" data-bs-toggle="offcanvas" href="#offcanvasExample2" aria-controls="offcanvasExample2">' +
                '<i class="fa fa-plus"></i>' +
                "</a>"
        );
});

function getShowStatus(item) {
    $("#example1")
        .DataTable()
        .ajax.url("stock-categories/cat-api?status=" + item.value)
        .load();
}

function getShowStatus1(item) {
    $("#example12")
        .DataTable()
        .ajax.url("stock-categories/sub-api?status=" + item.value)
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
                    url: "/stock-categories/cat-api/" + id,
                    data: {
                        status: "active",
                    },
                })
                    .done(function (data) {
                        if (data.status === "success") {
                            swal(
                                {
                                    title: protitle + "  reactivated",
                                    text: "If this was a mistake you can re-activate the item in the inactive stocklist screen!",
                                    type: "success",
                                },
                                function (isConfirm) {
                                    $("#status_show").val("all");
                                    $(".profile-btn-snd").click();
                                    $("#example1")
                                        .DataTable()
                                        .ajax.url("stock-categories/cat-api")
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
                                    $(".profile-btn-snd").click();
                                    $("#example1")
                                        .DataTable()
                                        .ajax.url("stock-categories/cat-api")
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
                    url: "/stock-categories/cat-api/" + id,
                    method: "DELETE",
                })
                    .done(function (data) {
                        if (data.status === "success") {
                            swal(
                                {
                                    title: protitle + "  deactivated",
                                    text: "If this was a mistake you can re-activate the item in the inactive main category screen!",
                                    type: "success",
                                },
                                function (isConfirm) {
                                    $("#status_show").val("all");
                                    $(".profile-btn-snd").click();
                                    $("#example1")
                                        .DataTable()
                                        .ajax.url("stock-categories/cat-api")
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
                                    $("#status_show").val("all");
                                    $(".profile-btn-snd").click();
                                    $("#example1")
                                        .DataTable()
                                        .ajax.url("stock-categories/cat-api")
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

function addNewMainCat() {
    tempMainId = "";
    $("#temptitle").html("New Main Category");
    $(".savemainbut").show();
    $(".updatemainbut").hide();
    $("#catagoryname").val("");
}

function saveMainCat() {
    if ($("#catagoryname").val() !== "") {
        $.ajax({
            type: "POST",
            url: "/stock-categories/cat-api",
            data: {
                _token: csrfToken,
                data: {
                    catagoryname: $("#catagoryname").val(),
                },
            },
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("add event success", "Success", {
                        timeOut: 3000,
                    });
                    $("#status_show").val("all");
                    $(".profile-btn-snd").click();
                    $("#example1")
                        .DataTable()
                        .ajax.url("stock-categories/cat-api")
                        .load();
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
    } else {
        toastr.error("must have input category name", "error", {
            timeOut: 3000,
        });
    }
}

function editMainCat(id) {
    tempMainId = id;
    $(".savemainbut").hide();
    $(".updatemainbut").show();
    $("#temptitle").html("Edit Main Category");

    $.ajax({
        url: "/stock-categories/cat-api",
        method: "GET",
        data: {
            id: id,
        },
    })
        .done(function (data) {
            if (data.status === "success") {
                if (data.data.length > 0) {
                    let category_data = data.data[0];
                    $("#catagoryname").val(category_data["catagoryname"]);
                } else {
                    $("#catagoryname").val("");
                }
            } else {
                toastr.error("add event failed", "error", { timeOut: 3000 });
            }
        })
        .fail(function (jqXHR, status, errorThrown) {
            toastr.error("add event failed", "error", { timeOut: 3000 });
        });
}

function updateMainCat() {
    if ($("#catagoryname").val() !== "") {
        $.ajax({
            type: "PUT",
            url: "/stock-categories/cat-api/" + tempMainId,
            data: {
                catagoryname: $("#catagoryname").val(),
            },
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("update event success", "Success", {
                        timeOut: 3000,
                    });
                    $("#status_show").val("all");
                    $(".profile-btn-snd").click();
                    $("#example1")
                        .DataTable()
                        .ajax.url("stock-categories/cat-api")
                        .load();
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
    } else {
        toastr.error("must have input category name", "error", {
            timeOut: 3000,
        });
    }
}

function ReSubalert(id, protitle) {
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
                    url: "/stock-categories/sub-api/" + id,
                    data: {
                        status: "active",
                    },
                })
                    .done(function (data) {
                        if (data.status === "success") {
                            swal(
                                {
                                    title: protitle + "  reactivated",
                                    text: "If this was a mistake you can re-activate the item in the inactive stocklist screen!",
                                    type: "success",
                                },
                                function (isConfirm) {
                                    $("#status_show1").val("all");
                                    $(".profile-btn-snd").click();
                                    $("#example12")
                                        .DataTable()
                                        .ajax.url("stock-categories/sub-api")
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
                                    $("#status_show1").val("all");
                                    $(".profile-btn-snd").click();
                                    $("#example12")
                                        .DataTable()
                                        .ajax.url("stock-categories/sub-api")
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

function JSSubalert(id, protitle) {
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
                    url: "/stock-categories/sub-api/" + id,
                    method: "DELETE",
                })
                    .done(function (data) {
                        if (data.status === "success") {
                            swal(
                                {
                                    title: protitle + "  deactivated",
                                    text: "If this was a mistake you can re-activate the item in the inactive sub category screen!",
                                    type: "success",
                                },
                                function (isConfirm) {
                                    $("#status_show1").val("all");
                                    $(".profile-btn-snd").click();
                                    $("#example12")
                                        .DataTable()
                                        .ajax.url("stock-categories/sub-api")
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
                                    $("#status_show1").val("all");
                                    $(".profile-btn-snd").click();
                                    $("#example12")
                                        .DataTable()
                                        .ajax.url("stock-categories/sub-api")
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

function addNewSubCat() {
    tempSubId = "";
    $("#temptitle").html("New Sub Category");
    $(".savesubbut").show();
    $(".updatesubbut").hide();
    $("#subcatagoryname").val("");
}

function saveSubCat() {
    if ($("#subcatagoryname").val() !== "") {
        $.ajax({
            type: "POST",
            url: "/stock-categories/sub-api",
            data: {
                _token: csrfToken,
                data: {
                    subcatagoryname: $("#subcatagoryname").val(),
                },
            },
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("add event success", "Success", {
                        timeOut: 3000,
                    });
                    $("#status_show1").val("all");
                    $(".profile-btn-snd").click();
                    $("#example12")
                        .DataTable()
                        .ajax.url("stock-categories/sub-api")
                        .load();
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
    } else {
        toastr.error("must have input category name", "error", {
            timeOut: 3000,
        });
    }
}

function editSubCat(id) {
    tempSubId = id;
    $(".savesubbut").hide();
    $(".updatesubbut").show();
    $("#temptitle").html("Edit Sub Category");

    $.ajax({
        url: "/stock-categories/sub-api",
        method: "GET",
        data: {
            id: id,
        },
    })
        .done(function (data) {
            if (data.status === "success") {
                if (data.data.length > 0) {
                    let category_data = data.data[0];
                    $("#subcatagoryname").val(category_data["subcatagoryname"]);
                } else {
                    $("#subcatagoryname").val("");
                }
            } else {
                toastr.error("add event failed", "error", { timeOut: 3000 });
            }
        })
        .fail(function (jqXHR, status, errorThrown) {
            toastr.error("add event failed", "error", { timeOut: 3000 });
        });
}

function updateSubCat() {
    if ($("#subcatagoryname").val() !== "") {
        $.ajax({
            type: "PUT",
            url: "/stock-categories/sub-api/" + tempSubId,
            data: {
                subcatagoryname: $("#subcatagoryname").val(),
            },
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("update event success", "Success", {
                        timeOut: 3000,
                    });
                    $("#status_show1").val("all");
                    $(".profile-btn-snd").click();
                    $("#example12")
                        .DataTable()
                        .ajax.url("stock-categories/sub-api")
                        .load();
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
    } else {
        toastr.error("must have input category name", "error", {
            timeOut: 3000,
        });
    }
}
