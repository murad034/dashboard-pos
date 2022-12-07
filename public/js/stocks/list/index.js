let tempId;
$(function () {
    $("#allocated-supplier").select2();
    $("#upload_file").click(function () {
        $("#upload_form").submit();
        $("#upload_file").attr("disabled", "disabled");
    });
    $("#upload_form").on("submit", function (event) {
        event.preventDefault();
        $.ajax({
            url: "stocks/upload",
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
                    $("#process_area").html(data.output);
                    $("#upload_area").css("display", "none");
                    $("#upload_file").hide();
                    $("#import").show();
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
            $("#import").attr("disabled", false);
        } else {
            $("#import").attr("disabled", "disabled");
        }
    });

    $(document).on("click", "#import", function (event) {
        if (!("sku" in column_data)) {
            toastr.error("you must set sku column", "error", { timeOut: 3000 });
            return false;
        } else {
            event.preventDefault();

            $.ajax({
                url: "/stocks/import",
                method: "POST",
                data: column_data,
                success: function (data) {
                    if (data.status === "success") {
                        toastr.success("csv import success", "Success", {
                            timeOut: 3000,
                        });
                        $("#status_show").val("all");
                        $(".btn-secondary").click();
                        $("#example1")
                            .DataTable()
                            .ajax.url("stocks/api?status=all")
                            .load();
                    }
                },
            });
        }
    });

    $("#example1").DataTable({
        // stateSave: true,
        columns: [
            { data: "sku" },
            { data: "stockname" },
            { data: "barcode" },
            { data: "catagory.catagoryname" },
            { data: "subcatagory.subcatagoryname" },
            { data: "status" },
            { data: "status" },
        ],
        ajax: {
            url: "/stocks/api",
            method: "GET",
        },
        columnDefs: [
            {
                targets: 5,
                data: null,
                className: "text-center",
                render: function (data, type, full, meta) {
                    if (type === "display") {
                        if (data === "inactive") {
                            data = "inactive";
                        } else {
                            data = "active";
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
                        if (data === "inactive") {
                            data =
                                '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editStock(' +
                                full["sku"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" >' +
                                '<i class="fa fa-info"></i>' +
                                "</a>" +
                                '<a data-simpletooltip-text="Re-activate Item"  class="js-simple-tooltip profile-btn" onclick="Realert(' +
                                full["sku"] +
                                ",`" +
                                full["stockname"] +
                                '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; background: green;" ><i class="fa fa-check"></i></a>';
                        } else {
                            data =
                                '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editStock(' +
                                full["sku"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" >' +
                                '<i class="fa fa-info"></i>' +
                                "</a>" +
                                ' <a data-simpletooltip-text="De-activate Item"  class="js-simple-tooltip profile-btn-del" onclick="JSalert(' +
                                full["sku"] +
                                ",`" +
                                full["stockname"] +
                                '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" ><i class="fa fa-times"></i></a>';
                        }
                    }
                    return data;
                },
            },
        ],
    });
    $("#exampletwo").DataTable({
        stateSave: true,
    });
    // $("#example1_wrapper")
    //     .find(".dataTables_filter")
    //     .prepend(
    //         '<div style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; display: inline-flex;" class="simpletooltip_container"><select class="form-control" id="status_show" name="status_show" onchange="getShowStatus(this)"><option value="all">All</option><option value="active">Active</option><option value="inactive">Deactive</option></select></div><a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewStock()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"><i class="fa fa-plus"></i></a><a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Import" class="js-simple-tooltip profile-btn" onclick="" data-bs-toggle="offcanvas" href="#offcanvasExample2" aria-controls="offcanvasExample2"><i class="fa fa-arrow-right"></i></a>'
    //     );
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
                '<a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewStock()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample">' +
                '<i class="fa fa-plus"></i>' +
                "</a>"
        );
});

function getShowStatus(item) {
    $("#example1")
        .DataTable()
        .ajax.url("stocks/api?status=" + item.value)
        .load();
}

function copyprice() {
    let productbaseprice = $("#baseprice").val();
    $(".stockprice").each(function (index) {
        $(this).val(productbaseprice);
    });
}

function copyreorder() {
    let productbasereorder = $("#basereorder").val();
    $(".stockreorder").each(function (index) {
        $(this).val(productbasereorder);
    });
}

function copyqty() {
    let productbaseqty = $("#baseqty").val();
    $(".stockqty").each(function (index) {
        $(this).val(productbaseqty);
    });
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
                    url: "/stocks/api/" + id,
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
                                    .ajax.url("stocks/api?status=all")
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
                                    .ajax.url("stocks/api?status=all")
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

$("#content-4").mCustomScrollbar({
    theme: "3d-dark",
});

$(".stockprice .stockreorder .stockqty").on("click", function () {
    $(this).select();
});
$(".stockaltprice").on("click", function () {
    $(this).select();
});
$(".stockcost").on("click", function () {
    $(this).select();
});
$(".stock-input").on("click", function () {
    $(this).select();
});
$(".stocksoh").on("click", function () {
    $(this).select();
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
                    url: "/stocks/api/" + id,
                    method: "DELETE",
                }).done(function (data) {
                    if (data.status === "success") {
                        swal(
                            {
                                title: protitle + "  deactivated",
                                text: "If this was a mistake you can re-activate the item in the inactive stocklist screen!",
                                type: "success",
                            },
                            function (isConfirm) {
                                if (isConfirm) {
                                    $("#status_show").val("all");
                                    $("#example1")
                                        .DataTable()
                                        .ajax.url("stocks/api?status=all")
                                        .load();
                                } else {
                                    $("#status_show").val("all");
                                    $("#example1")
                                        .DataTable()
                                        .ajax.url("stocks/api?status=all")
                                        .load();
                                }
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
                                if (isConfirm) {
                                    $("#status_show").val("all");
                                    $("#example1")
                                        .DataTable()
                                        .ajax.url("stocks/api?status=all")
                                        .load();
                                } else {
                                    $("#status_show").val("all");
                                    $("#example1")
                                        .DataTable()
                                        .ajax.url("stocks/api?status=all")
                                        .load();
                                }
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

function addNewStock() {
    //alert('new');
    $("#temptitle").html("New Stock Item");
    $(".savebut").show();
    $(".updatebut").hide();
    // $('#plu').prop('disabled', false);
    $("#maincat").val("no");
    $("#subcat").val("no");
    $("#unitval").val("");
    // $('#plu').val($('#newplu').val());
    $("#stockname").val("");
    $("#barcode").val("");
    $("#baseprice").val("0.00");
    $("#baseqty").val("0.00");

    $("#allocated-supplier").val("");
    $("#allocated-supplier").trigger("change");

    $(".stockprice").each(function (index) {
        $(this).val("0.00");
    });

    $(".stockreorder").each(function (index) {
        $(this).val("0.00");
    });

    $(".stockqty").each(function (index) {
        $(this).val("0.00");
    });

    $(".stockaltprice").each(function (index) {
        $(this).val("0.00");
    });
    $(".stockcost").each(function (index) {
        $(this).val("0.00");
    });
}

$(".checkgst").change(function () {
    if ($(this).is(":checked")) {
        $(".gst").val("1");
    } else if ($(this).not(":checked")) {
        $(".gst").val("0");
    }
});
$(".checkmod").change(function () {
    if ($(this).is(":checked")) {
        $(".mod").val("1");
    } else if ($(this).not(":checked")) {
        $(".mod").val("0");
    }
});

function getFormData() {
    let stock_data = {};

    $(".stock-input").each(function (index) {
        switch (index) {
            case 0:
                stock_data["stockname"] = $(this).val();
                break;
            case 1:
                stock_data["barcode"] = $(this).val();
                break;
            case 2:
                stock_data["maincat"] = $(this).val();
                break;
            case 3:
                stock_data["subcat"] = $(this).val();
                break;
            case 4:
                stock_data["stockoption"] = $(this).val();
                break;
            case 5:
                stock_data["unitval"] = $(this).val();
                break;
            case 6:
                stock_data["baseprice"] = $(this).val();
                break;
            case 8:
                stock_data["basereorder"] = $(this).val();
                break;
            case 7:
                stock_data["baseqty"] = $(this).val();
                break;
            default:
                break;
        }
    });
    stock_data["allocated_supplier"] = $("#allocated-supplier").val().join(",");
    let price_data = {};
    let qty_data = {};
    let priceArray = [];
    let qtyArray = [];

    $(".stockprice").each(function (index) {
        priceArray.push({
            storeid: "" + $(this).data("id") + "",
            stockprice: parseFloat($(this).val()).toFixed(2),
            sku: stock_data["sku"],
        });
    });
    price_data["prices"] = priceArray;

    $(".stockqty").each(function (index) {
        let loc_id = $(this).data("id");
        let stockreorder = "0.00";
        $(".stockreorder").each(function (index) {
            if (loc_id === $(this).data("id")) {
                stockreorder = parseFloat($(this).val()).toFixed(2);
            }
        });
        qtyArray.push({
            storeid: "" + $(this).data("id") + "",
            stockqty: parseFloat($(this).val()).toFixed(2),
            stockreorder: stockreorder,
            sku: stock_data["sku"],
            // syncid: "LOC" + $(this).data('id') + "",
        });
    });
    qty_data["qty"] = qtyArray;

    let post_data = {};
    post_data["stock_data"] = stock_data;
    post_data["price_data"] = price_data;
    post_data["qty_data"] = qty_data;

    return post_data;
}

function saveStock() {
    if ($("#maincat").val() === "no") {
        toastr.error("Please select a Main Catagory!", "error", {
            timeOut: 3000,
        });
        return;
    }
    if ($("#subcat").val() === "no") {
        toastr.error("Please select a Sub Catagory!", "error", {
            timeOut: 3000,
        });
        return;
    }
    if ($("#stockname").val() === "") {
        toastr.error("Please input Stock Name!", "error", { timeOut: 3000 });
        return;
    }

    $.ajax({
        type: "POST",
        url: "/stocks/api",
        data: {
            _token: csrfToken,
            data: getFormData(),
        },
        success: function (data) {
            if (data.status === "success") {
                toastr.success("save stock success", "Success", {
                    timeOut: 3000,
                });
                $("#status_show").val("all");
                $(".profile-btn-snd").click();
                $("#example1")
                    .DataTable()
                    .ajax.url("stocks/api?status=all")
                    .load();
            } else {
                toastr.error("save stock failed", "error", { timeOut: 3000 });
            }
        },
        error: function () {
            toastr.error("add event failed", "error", { timeOut: 3000 });
        },
    });
}

function updateStock() {
    if ($("#maincat").val() === "no") {
        toastr.error("Please select a Main Catagory!", "error", {
            timeOut: 3000,
        });
        return;
    }
    if ($("#subcat").val() === "no") {
        toastr.error("Please select a Sub Catagory!", "error", {
            timeOut: 3000,
        });
        return;
    }
    if ($("#stockname").val() === "") {
        toastr.error("Please input Stock Name!", "error", { timeOut: 3000 });
        return;
    }

    $.ajax({
        type: "PUT",
        url: "/stocks/api/" + tempId,
        data: getFormData(),
        success: function (data) {
            if (data.status === "success") {
                toastr.success("save stock success", "Success", {
                    timeOut: 3000,
                });
                $("#status_show").val("all");
                $(".profile-btn-snd").click();
                $("#example1")
                    .DataTable()
                    .ajax.url("stocks/api?status=all")
                    .load();
            } else {
                toastr.error("save stock failed", "error", { timeOut: 3000 });
            }
        },
        error: function () {
            toastr.error("add event failed", "error", { timeOut: 3000 });
        },
    });
}

function editStock(id) {
    tempId = id;
    //alert('edit');
    // $('#plu').prop('disabled', true);
    $(".savebut").hide();
    $(".updatebut").show();
    $("#temptitle").html("Edit Stock Item");
    $.ajax({
        url: "/stocks/api/",
        data: {
            id: id,
        },
        method: "GET",
    })
        .done(function (data) {
            if (data.status === "success") {
                let stockData = data.data.stock_data;
                $("#stockname").val(stockData[0]["stockname"]);
                $("#barcode").val(stockData[0]["barcode"]);
                $("#baseprice").val(stockData[0]["baseprice"]);
                $("#basereorder").val(stockData[0]["basereorder"]);
                $("#baseqty").val(stockData[0]["baseqty"]);

                let supplierData =
                    stockData[0]["allocated_supplier"].split(",");
                $("#allocated-supplier").val(supplierData);
                $("#allocated-supplier").trigger("change");

                $(
                    '#maincat option[value="' + stockData[0]["maincat"] + '"]'
                ).attr("selected", "selected");
                $(
                    '#subcat option[value="' + stockData[0]["subcat"] + '"]'
                ).attr("selected", "selected");
                $("#unitval").val(stockData[0]["unitval"]);
                $("#stockoption").val(stockData[0]["stockoption"]);
                let pricingData = data.data.pricing_data;
                let qtyData = data.data.qty_data;
                $(".stockprice").each(function (index) {
                    let dataId = $(this).data("id");
                    for (let data of pricingData) {
                        if (dataId === parseInt(data.storeid)) {
                            $(this).val(data.stockprice);
                        }
                    }
                });

                $(".stockqty").each(function (index) {
                    let dataId = $(this).data("id");
                    for (let data of qtyData) {
                        if (dataId === parseInt(data.storeid)) {
                            $(this).val(data.stockqty);
                        }
                    }
                });
                $(".stockreorder").each(function (index) {
                    let dataId = $(this).data("id");
                    for (let data of qtyData) {
                        if (dataId === parseInt(data.storeid)) {
                            $(this).val(data.stockreorder);
                        }
                    }
                });
            } else {
                toastr.error("add event failed", "error", { timeOut: 3000 });
            }
        })
        .fail(function (jqXHR, status, errorThrown) {
            alert(status + "<br>" + errorThrown);
        });
}
