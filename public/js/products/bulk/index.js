let setPriceClass;
let selectLocationId;
let tableData;
let priceTable;
// let priceTable = $("#example1").DataTable();
$(document).ready(function () {
    $("#set_price_option").numeric({ negative: false });
    if (locationList.length === 0) {
        setTimeout(
            toastr.warning("location list is empty. please make location"),
            3000
        );

        window.location.href = "/locations";
    } else {
        selectLocationId = locationList[0]["locationid"];
    }
    $("#example1").on("click", ".price-change", function () {
        $('[data-bs-toggle="popover"]').not(this).popover("hide");
        let val = $(this)
            .text()
            .replace(/^\s+|\s+$/g, "");
        switch (val) {
            case "SET Price":
                setPriceClass = "productprice";
                break;
            case "SET Tier1":
                setPriceClass = "producttier1";
                break;
            case "SET Tier2":
                setPriceClass = "producttier2";
                break;
            case "SET Tier3":
                setPriceClass = "producttier3";
                break;
            case "SET Tier4":
                setPriceClass = "producttier4";
                break;
            case "SET Tier5":
                setPriceClass = "producttier5";
                break;
            default:
                break;
        }
    });

    $(".price-change").each(function (index) {
        $(this).popover({
            // trigger   : "click",
            // container: 'body',
            placement: "top",
            html: true,
            title: "PRICE CHANGE",
            content: function () {
                return $("#PopoverContent").html();
            },
            sanitize: false, // here it is
        });
    });

    priceTable = $("#example1").DataTable({
        // stateSave: true,
        columns: [
            { data: "sku" },
            { data: "product.productname" },
            { data: "product.maincat" },
            { data: "product.barcode" },
            { data: "productprice" },
            { data: "producttier1" },
            { data: "producttier2" },
            { data: "producttier3" },
            { data: "producttier4" },
            { data: "producttier5" },
            { data: "cost.productcost" },
            { data: "producttier4" },
            { data: "product.web_info" },
        ],
        ajax: {
            url: "/bulk-edit/api?id=" + selectLocationId,
            method: "GET",
            data: function (d) {},
        },
        fnRowCallback: function (nRow, aData, iDisplayIndex) {
            nRow.setAttribute("data-id", nRow["_DT_RowIndex"]);
        },
        columnDefs: [
            {
                targets: 2,
                data: null,
                render: function (data, type, full, meta) {
                    if (categoryList.length > 0) {
                        for (let dx = 0; dx < categoryList.length; dx++) {
                            if (categoryList[dx]["catid"] === data) {
                                data = categoryList[dx]["catagoryname"];
                            }
                        }
                    }

                    return data;
                },
            },
            {
                targets: 3,
                data: null,
                render: function (data, type, full, meta) {
                    data =
                        full["product"]["barcode"] +
                        "<br>" +
                        ("barcode1" in full["product"]
                            ? full["product"]["barcode1"]
                            : "") +
                        "<br>" +
                        ("barcode2" in full["product"]
                            ? full["product"]["barcode2"]
                            : "");
                    return data;
                },
            },
            {
                targets: 4,
                data: null,
                render: function (data, type, full, meta) {
                    let gpVal;
                    if (
                        parseFloat(full["cost"]["productcost"]) === 0 ||
                        isNaN(full["cost"]["productcost"]) ||
                        parseFloat(full["productprice"]) === 0 ||
                        isNaN(full["productprice"])
                    ) {
                        gpVal = "GP %";
                    } else {
                        gpVal =
                            "GP " +
                            (
                                100 -
                                (parseFloat(full["cost"]["productcost"]) *
                                    100) /
                                    parseFloat(full["productprice"])
                            ).toFixed(2) +
                            "%";
                    }
                    data =
                        ' <input class="form-control productprice" style="width: 90px; margin:auto;" data-id=' +
                        full["sku"] +
                        " value='" +
                        data +
                        "'>" +
                        '<span class="cost" style="color:red; display:block; text-align: center; font-weight: bold;">' +
                        gpVal +
                        "</span>";
                    return data;
                },
            },
            {
                targets: 5,
                data: null,
                render: function (data, type, full, meta) {
                    let gpVal;
                    if (
                        parseFloat(full["cost"]["productcost"]) === 0 ||
                        isNaN(full["cost"]["productcost"]) ||
                        parseFloat(full["producttier1"]) === 0 ||
                        isNaN(full["producttier1"])
                    ) {
                        gpVal = "GP %";
                    } else {
                        gpVal =
                            "GP " +
                            (
                                100 -
                                (parseFloat(full["cost"]["productcost"]) *
                                    100) /
                                    parseFloat(full["producttier1"])
                            ).toFixed(2) +
                            "%";
                    }
                    data =
                        ' <input class="form-control producttier1" style="width: 90px; margin:auto;" data-id=' +
                        full["sku"] +
                        " value='" +
                        data +
                        "'>" +
                        '<span class="cost" style="color:red; display:block; text-align: center; font-weight: bold;">' +
                        gpVal +
                        "</span>";
                    return data;
                },
            },
            {
                targets: 6,
                data: null,
                render: function (data, type, full, meta) {
                    let gpVal;
                    if (
                        parseFloat(full["cost"]["productcost"]) === 0 ||
                        isNaN(full["cost"]["productcost"]) ||
                        parseFloat(full["producttier2"]) === 0 ||
                        isNaN(full["producttier2"])
                    ) {
                        gpVal = "GP %";
                    } else {
                        gpVal =
                            "GP " +
                            (
                                100 -
                                (parseFloat(full["cost"]["productcost"]) *
                                    100) /
                                    parseFloat(full["producttier2"])
                            ).toFixed(2) +
                            "%";
                    }
                    data =
                        ' <input class="form-control producttier2" style="width: 90px; margin:auto;" data-id=' +
                        full["sku"] +
                        " value='" +
                        data +
                        "'>" +
                        '<span class="cost" style="color:red; display:block; text-align: center; font-weight: bold;">' +
                        gpVal +
                        "</span>";
                    return data;
                },
            },
            {
                targets: 7,
                data: null,
                render: function (data, type, full, meta) {
                    let gpVal;
                    if (
                        parseFloat(full["cost"]["productcost"]) === 0 ||
                        isNaN(full["cost"]["productcost"]) ||
                        parseFloat(full["producttier3"]) === 0 ||
                        isNaN(full["producttier3"])
                    ) {
                        gpVal = "GP %";
                    } else {
                        gpVal =
                            "GP " +
                            (
                                100 -
                                (parseFloat(full["cost"]["productcost"]) *
                                    100) /
                                    parseFloat(full["producttier3"])
                            ).toFixed(2) +
                            "%";
                    }
                    data =
                        ' <input class="form-control producttier3" style="width: 90px; margin:auto;" data-id=' +
                        full["sku"] +
                        " value='" +
                        data +
                        "'>" +
                        '<span class="cost" style="color:red; display:block; text-align: center; font-weight: bold;">' +
                        gpVal +
                        "</span>";
                    return data;
                },
            },
            {
                targets: 8,
                data: null,
                render: function (data, type, full, meta) {
                    let gpVal;
                    if (
                        parseFloat(full["cost"]["productcost"]) === 0 ||
                        isNaN(full["cost"]["productcost"]) ||
                        parseFloat(full["producttier4"]) === 0 ||
                        isNaN(full["producttier4"])
                    ) {
                        gpVal = "GP %";
                    } else {
                        gpVal =
                            "GP " +
                            (
                                100 -
                                (parseFloat(full["cost"]["productcost"]) *
                                    100) /
                                    parseFloat(full["producttier4"])
                            ).toFixed(2) +
                            "%";
                    }
                    data =
                        ' <input class="form-control producttier4" style="width: 90px; margin:auto;" data-id=' +
                        full["sku"] +
                        " value='" +
                        data +
                        "'>" +
                        '<span class="cost" style="color:red; display:block; text-align: center; font-weight: bold;">' +
                        gpVal +
                        "</span>";
                    return data;
                },
            },
            {
                targets: 9,
                data: null,
                render: function (data, type, full, meta) {
                    let gpVal;
                    if (
                        parseFloat(full["cost"]["productcost"]) === 0 ||
                        isNaN(full["cost"]["productcost"]) ||
                        parseFloat(full["producttier5"]) === 0 ||
                        isNaN(full["producttier5"])
                    ) {
                        gpVal = "GP %";
                    } else {
                        gpVal =
                            "GP " +
                            (
                                100 -
                                (parseFloat(full["cost"]["productcost"]) *
                                    100) /
                                    parseFloat(full["producttier5"])
                            ).toFixed(2) +
                            "%";
                    }
                    data =
                        ' <input class="form-control producttier5" style="width: 90px; margin:auto;" data-id=' +
                        full["sku"] +
                        " value='" +
                        data +
                        "'>" +
                        '<span class="cost" style="color:red; display:block; text-align: center; font-weight: bold;">' +
                        gpVal +
                        "</span>";
                    return data;
                },
            },
            {
                targets: 12,
                data: null,
                render: function (data, type, full, meta) {
                    let check_status = "off";
                    if (data.length !== 0) {
                        for (let idx = 0; idx < data.length; idx++) {
                            if (
                                parseInt(data[idx]["locationid"]) ===
                                parseInt(selectLocationId)
                            ) {
                                check_status = data[idx]["available"];
                            }
                        }
                    }
                    let statusCheck = "";
                    if (check_status === "on") {
                        statusCheck = "checked";
                    }
                    data =
                        '<div class="form-check form-switch" style="padding-top: 30px;">' +
                        '<input class="form-check-input" disabled type="checkbox" name="web_available" value=\'' +
                        check_status +
                        "' " +
                        statusCheck +
                        '><label class="form-check-label" for="web_available"></label></div>';

                    return data;
                },
            },
        ],
    });

    // calculate GP % when change product price and alt pricing.
    $(document).on(
        "change",
        ".productprice, .producttier1, .producttier2, .producttier3, .producttier4, .producttier5",
        function () {
            let pro_price = this.value;
            let updataTD = $(this).parent("td");
            priceTable
                .cell(updataTD)
                .data(parseFloat(pro_price).toFixed(2))
                .draw();
        }
    );

    $("#example1_length").parent().siblings().removeClass("col-sm-6");
    $("#example1_length").parent().addClass("col-sm-4");

    $("#example1_filter").parent().siblings().removeClass("col-sm-6");
    $("#example1_filter").parent().addClass("col-sm-8");
    $("#example1_wrapper")
        .find(".dataTables_filter")
        .prepend(function () {
            let selectHtml =
                '<div style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; display: inline-flex;" class="simpletooltip_container">' +
                '<label class="form-check-label" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; display: inline-flex;" for="location_select">Location:</label>' +
                '<select id="location_select" class="form-control" style="width: 150px;" onchange="getProductByStore()">';
            let seloption = "";
            for (let i = 0; i < locationList.length; i++) {
                seloption +=
                    '<option value="' +
                    locationList[i]["locationid"] +
                    '">' +
                    locationList[i]["locationname"] +
                    "</option>";
            }

            selectHtml = selectHtml + seloption + "</select>";

            selectHtml =
                selectHtml +
                '<label class="form-check-label" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; display: inline-flex;" for="category_select">Category:</label>' +
                '<select id="category_select" class="form-control" style="width: 150px;">';
            seloption = "";
            for (let i = 0; i < categoryList.length; i++) {
                seloption +=
                    '<option value="' +
                    categoryList[i]["catid"] +
                    '">' +
                    categoryList[i]["catagoryname"] +
                    "</option>";
            }

            selectHtml = selectHtml + seloption + "</select></div>";

            return selectHtml;
        });

    $("#example1_wrapper")
        .find(".dataTables_filter")
        .append(function () {
            return (
                '<div style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; display: inline-flex;" class="simpletooltip_container">' +
                '<button type="button" class="btn btn-block btn-dark btn-sm form-control" onclick="saveBulkProduct()">Save</button>' +
                '<button type="button" class="btn btn-block btn-dark btn-sm form-control" onclick="saveDraftProduct()">Save to Draft</button>' +
                '        <button type="button" id="saveWithSchedulePopUp" class="btn btn-block btn-dark btn-sm form-control set-schedule"\n' +
                '          style="border: none;" data-bs-toggle="true" data-bs-html="true" data-bs-saitize="false">\n' +
                "          Save & Schedule\n" +
                "        </button>\n" +
                "        <section>\n" +
                '          <div id="PopoverContent" class="d-none">\n' +
                '            <div class="row" style="padding-top: 5px;">\n' +
                '              <div class="col-sm-9 text-center">\n' +
                '                <input type="datetime-local" id="set_schedule_at" class="form-control" name="schedule_at">\n' +
                "              </div>\n" +
                '              <div class="col-sm-3 text-center">\n' +
                '                <button type="button" class="btn btn-block btn-dark savbut" style="margin-left:-9px; margin-top:0px;"\n' +
                '                  onclick="saveDraftWithScheduleAt()">Save\n' +
                "                </button>\n" +
                "              </div>\n" +
                "            </div>\n" +
                "          </div>\n" +
                "        </section>" +
                "</div>"
            );
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
        // $(".popover-body").find("#set_schedule_at").val("fdsafds");
    });
    // $(".popover-body").find("#set_schedule_at").val("fdsafds");
    // $("#set_schedule_at").daterangepicker({
    //     singleDatePicker: true,
    //     timePicker: true,
    //     showDropdowns: true,
    //     minYear: 1901,
    //     maxYear: parseInt(moment().format("YYYY"), 10),
    //     locale: {
    //         format: "YYYY-MM-DD HH:mm",
    //     },
    // });
});
// get product data from
function getProductByStore() {
    selectLocationId = $("#location_select").val();
    $("#example1")
        .DataTable()
        .ajax.url("/bulk-edit/api?id=" + selectLocationId)
        .load();
}

// save draft to draft table
function saveDraftProduct() {
    let parseData = priceTable.rows().data().toArray();
    $.ajax({
        type: "POST",
        url: "/bulk-edit/draft",
        data: {
            data: JSON.stringify(parseData),
        },
        success: function (data) {
            if (data.status === "success") {
                toastr.success("save bulk products success", "Success", {
                    timeOut: 3000,
                });
            } else {
                toastr.error("save bulk products failed", "error", {
                    timeOut: 3000,
                });
            }
        },
        error: function () {
            toastr.error("save bulk products failed", "error", {
                timeOut: 3000,
            });
        },
    });
}

// save draft to draft table and schedule at
function saveDraftWithScheduleAt() {
    const inputValue = $(".popover-body").find("#set_schedule_at").val();
    let scheduleTime = null;
    if (inputValue) {
        const newTime = new Date(
            $(".popover-body").find("#set_schedule_at").val()
        );
        scheduleTime = newTime.toISOString();
    }
    let parseData = priceTable.rows().data().toArray();
    $.ajax({
        type: "POST",
        url: "/bulk-edit/draft_schedule",
        data: {
            data: JSON.stringify(parseData),
            scheduleAt: scheduleTime,
        },
        success: function (data) {
            if (data.status === "success") {
                toastr.success("save bulk products success", "Success", {
                    timeOut: 3000,
                });
            } else {
                toastr.error("save bulk products failed", "error", {
                    timeOut: 3000,
                });
            }
        },
        error: function () {
            toastr.error("save bulk products failed", "error", {
                timeOut: 3000,
            });
        },
    });
}
// save bulk price to pricings table
function saveBulkProduct() {
    let parseData = priceTable.rows().data().toArray();
    $.ajax({
        type: "POST",
        url: "/bulk-edit/api",
        data: {
            data: JSON.stringify(parseData),
        },
        success: function (data) {
            if (data.status === "success") {
                toastr.success("save bulk products success", "Success", {
                    timeOut: 3000,
                });
            } else {
                toastr.error("save bulk products failed", "error", {
                    timeOut: 3000,
                });
            }
        },
        error: function () {
            toastr.error("save bulk products failed", "error", {
                timeOut: 3000,
            });
        },
    });
}

// when click SET Price button
function copyBasePrice() {
    let basePrice = $(".popover-body").find("#set_price_option").val();
    let allData = priceTable.rows().data();
    let parseData = priceTable.rows({ filter: "applied" }).data();
    for (let idx = 0; idx < parseData.length; idx++) {
        for (let dx = 0; dx < allData.length; dx++) {
            if (parseData[idx]["sku"] === allData[dx]["sku"]) {
                allData[dx][setPriceClass] = parseFloat(basePrice).toFixed(2);
            }
        }
    }

    priceTable.clear();
    priceTable.rows.add(allData);
    priceTable.draw();
    $('[data-bs-toggle="popover"]').popover("hide");
}

function setIncreasePercent() {
    let IncreasePercent = $(".popover-body").find("#set_price_option").val();
    let allData = priceTable.rows().data();
    let parseData = priceTable.rows({ filter: "applied" }).data();
    for (let idx = 0; idx < parseData.length; idx++) {
        for (let dx = 0; dx < allData.length; dx++) {
            if (parseData[idx]["sku"] === allData[dx]["sku"]) {
                allData[dx][setPriceClass] = parseFloat(
                    (parseFloat(allData[dx][setPriceClass]) *
                        (100 + parseFloat(IncreasePercent))) /
                        100
                ).toFixed(2);
            }
        }
    }

    priceTable.clear();
    priceTable.rows.add(allData);
    priceTable.draw();
    $('[data-bs-toggle="popover"]').popover("hide");
}

function setDecreasePercent() {
    let DecreasePercent = $(".popover-body").find("#set_price_option").val();
    let allData = priceTable.rows().data();
    let parseData = priceTable.rows({ filter: "applied" }).data();
    for (let idx = 0; idx < parseData.length; idx++) {
        for (let dx = 0; dx < allData.length; dx++) {
            if (parseData[idx]["sku"] === allData[dx]["sku"]) {
                allData[dx][setPriceClass] = parseFloat(
                    (parseFloat(allData[dx][setPriceClass]) *
                        (100 - parseFloat(DecreasePercent))) /
                        100
                ).toFixed(2);
            }
        }
    }

    priceTable.clear();
    priceTable.rows.add(allData);
    priceTable.draw();
    $('[data-bs-toggle="popover"]').popover("hide");
}

function setIncreaseVal() {
    let increaseVal = $(".popover-body").find("#set_price_option").val();
    let allData = priceTable.rows().data();
    let parseData = priceTable.rows({ filter: "applied" }).data();
    for (let idx = 0; idx < parseData.length; idx++) {
        for (let dx = 0; dx < allData.length; dx++) {
            if (parseData[idx]["sku"] === allData[dx]["sku"]) {
                allData[dx][setPriceClass] = parseFloat(
                    parseFloat(allData[dx][setPriceClass]) +
                        parseFloat(increaseVal)
                ).toFixed(2);
            }
        }
    }
    priceTable.clear();
    priceTable.rows.add(allData);
    priceTable.draw();
    $('[data-bs-toggle="popover"]').popover("hide");
}

function setDecreaseVal() {
    let decreaseVal = $(".popover-body").find("#set_price_option").val();
    let allData = priceTable.rows().data();
    let parseData = priceTable.rows({ filter: "applied" }).data();
    for (let idx = 0; idx < parseData.length; idx++) {
        for (let dx = 0; dx < allData.length; dx++) {
            if (parseData[idx]["sku"] === allData[dx]["sku"]) {
                allData[dx][setPriceClass] = parseFloat(
                    parseFloat(allData[dx][setPriceClass]) +
                        parseFloat(decreaseVal)
                ).toFixed(2);
            }
        }
    }

    priceTable.clear();
    priceTable.rows.add(parseData);
    priceTable.draw();
    $('[data-bs-toggle="popover"]').popover("hide");
}
