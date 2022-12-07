let editor;
let tempId;
$(function () {
    $("#stocktable").DataTable({
        // stateSave: true,
        columns: [
            { data: "sku" },
            { data: "stock.stockname" },
            // { "data": "stockqty",  className: 'editable'},
        ],
        ajax: {
            url: "/receive-stock/data-api",
            method: "GET",
        },
        columnDefs: [
            {
                targets: 2,
                defaultContent: "",
                class: "editable",
            },
            {
                targets: 3,
                data: null,
                render: function (data, type, full, meta) {
                    if (type === "display") {
                        let today = new Date().toISOString().split("T")[0];
                        data =
                            '<input type="date" class="stock-input form-control">';
                    }
                    return data;
                },
            },
        ],
    });

    $("#example1").DataTable({
        // stateSave: true,
        columns: [
            { data: "locationid" },
            { data: "locationname" },
            { data: "address" },
            { data: "status" },
        ],
        ajax: {
            url: "/receive-stock/api",
            method: "GET",
        },
        fnRowCallback: function (nRow, aData, iDisplayIndex) {
            nRow.setAttribute("id", aData["locationid"]);
        },
        columnDefs: [
            {
                targets: 3,
                width: "10%",
                data: null,
                render: function (data, type, full, meta) {
                    if (type === "display") {
                        data =
                            '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="getRceiveStock(' +
                            full["locationid"] +
                            ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a>';
                    }
                    return data;
                },
            },
        ],
    });
    $(document).on("click", ".editable", function () {
        let html = $(this).text();
        let input = $('<input type="text" />');
        input.val(html);
        $(this).replaceWith(input);
        $(this).focus().select();
    });

    $(document).on("blur", "#stocktable input", function () {
        if ($(this).attr("type") !== "date") {
            $(this).replaceWith(
                '<td class="editable edited">' + this.value + "</td>"
            );
        }
    });
});

function tableToJSON(tblObj) {
    let data = [];
    let $rows = $(tblObj)
        .find("tbody tr")
        .each(function (index) {
            $cells = $(this).find("td");
            data[index] = {};
            $cells.each(function (cellIndex) {
                if (cellIndex === 0) {
                    data[index]["sku"] = $(this).html();
                } else if (cellIndex === 2) {
                    data[index]["stockqty"] = parseFloat(
                        $(this).html()
                    ).toFixed(2);
                } else if (cellIndex === 3) {
                    data[index]["usedbydate"] = $(this)
                        .children(":first")
                        .val();
                }
            });
        });
    return data;
}

function getFormData($form) {
    let unindexed_array = $form.serializeArray();
    let indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        indexed_array[n["name"]] = n["value"];
    });

    indexed_array["storeid"] = tempId;
    return indexed_array;
}

function getRceiveStock(id) {
    tempId = id;
    $("#stocktable")
        .DataTable()
        .ajax.url("receive-stock/data-api?id=" + id)
        .load();
}

function saveStock() {
    let $form = $("#stockForm");
    if ($form.validate().form()) {
        $.ajax({
            type: "POST",
            url: "/receive-stock/api",
            data: {
                _token: csrfToken,
                data: {
                    stockqty: tableToJSON($("#stocktable")),
                    stockinlog: getFormData($form),
                    storeid: tempId,
                },
            },
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("save event success", "Success", {
                        timeOut: 3000,
                    });
                    location.reload();
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
    }
}
