let tempId;
let type = $("#type");
let customer = $("#customer");
let customerEmail = $("#customer-email");
let terms = $("#terms");
let invoiceDate = $("#invoice-date");
let dueDate = $("#due-date");
let invoiceTable = $("#invoice-table");
var ajaxProducts = []
var invoiceDataTable = '';

function calculateTotal(){
    if(invoiceDataTable){
        //sub total calculation
        let subTotal = invoiceDataTable.column(7).data().sum();

        //sub total with discount calculation
        let discount        = $('#discount_per').val()
        let subTotalPercent = (subTotal * (discount/100))

        //shippingCost
        let shippingCost = $('#shipping_cost').val()
        shippingCost = shippingCost == '' ? 0: parseFloat(shippingCost)

        //gst calculation
        let shippingCostGst = $('#shipping_tax').val() == 'gst' ? (shippingCost/11):0
        let gst = invoiceDataTable.column(8).data().sum() + shippingCostGst;

        //total cost
        let total = (subTotal - subTotalPercent) + shippingCost + gst

        $('#subTotal').html(`A$${subTotal}`)
        $('#subTotalPercent').html(`A$${subTotalPercent.toFixed(2)}`)

        $('#gstValue').html(`A$${gst.toFixed(2)}`)

        $('#total').html(`A$${total.toFixed(2)}`)
        $('#balanceDue').html(`A$${total.toFixed(2)}`)
    }
}

function addRow(inv_products='', mode='') {

    let rowCount;
    let last_row = invoiceDataTable.row(':last').data();
    if( last_row == undefined){
        rowCount = invoiceDataTable.rows().count();
    }else{
        rowCount = $(last_row[2]).attr('id').replace ( /[^\d.]/g, '' );
        rowCount = Number(rowCount)+1;
    }

    if(mode){
        invoiceDataTable.clear().draw();
        inv_products.forEach((val, index) => {
                rowCount = invoiceDataTable.rows().count();
                invoiceDataTable.row.add(
                    [ `<i class="fa fa-th"></i> <div class="d-none">${rowCount}</div>`,'',
                        `<input type="date" class="form-control" id="serviceSelect${rowCount}" name="serviceDate" value="${val['service-date']}">`,
                        `<select class="product-select2 form-control" id="productSelect${rowCount}"><option selected value="${val['sku-id']}">${val['sku-name']}</option></select>`,`<div>${val['description'] == null ? '': val['description']}</div>`,
                        `<input type="number" min="1" name="qty" value="${val['quantity']}" id="qtySelect${rowCount}" class="product-qty">`,
                        `<input type="text" name="price" value="${val['price'] ??  0 }" id="priceSelect${rowCount}" class="product-price">`,
                        `${((val['price'] ?? 0) * (val['quantity'] ?? 0)).toFixed(2) }`,`${val['gst'] ?? 0}`,'<i class="fa fa-trash pointer" title="DELETE"></i>']
                ).draw();
                $(".product-select2").select2({
                    placeholder: 'Select product',
                    dropdownParent: $("#offcanvasExample"),
                    tags: false,
                    allowClear: false,
                    ajax: {
                        url: '/products/api/getAllAjax',
                        dataType: 'json',
                        data: function(params) {
                            return {
                                storeId: $('#store').val(),
                                term   : params.term || '',
                                page   : params.page || 1
                            }
                        },
                        cache: true,
                        processResults: function (data, params) {
                            params.page  = params.page || 1;
                            ajaxProducts = data.results
                            return {
                                results: data.results,
                                pagination: {
                                    more: (params.page * 10) < data.count_filtered
                                }
                            };
                        }
                    },
                });
            });
        product_def_load();
    }else{
        invoiceDataTable.row.add(
            [ `<i class="fa fa-th"></i> <div class="d-none">${rowCount}</div>`,'',
                `<input type="date" class="form-control" id="serviceSelect${rowCount}" name="serviceDate" value="${new Date().toISOString().slice(0, 10)}">`,
                `<select class="product-select2 form-control" id="productSelect${rowCount}"></select>`,'',
                `<input type="number" min="1" name="qty" value="1" id="qtySelect${rowCount}" class="product-qty">`,
                `<input type="text" name="price" value="" id="priceSelect${rowCount}" class="product-price">`,
                '','','<i class="fa fa-trash pointer" title="DELETE"></i>']
        ).draw();
        $(".product-select2").select2({
            placeholder: 'Select product',
            dropdownParent: $("#offcanvasExample"),
            tags: false,
            allowClear: false,
            ajax: {
                url: '/products/api/getAllAjax',
                dataType: 'json',
                data: function(params) {
                    return {
                        storeId: $('#store').val(),
                        term   : params.term || '',
                        page   : params.page || 1
                    }
                },
                cache: true,
                processResults: function (data, params) {
                    params.page  = params.page || 1;
                    ajaxProducts = data.results

                    return {
                        results: data.results,
                        pagination: {
                            more: (params.page * 10) < data.count_filtered
                        }
                    };
                }
            },
        });
    }

}

$(document).ready(function () {
    invoiceDate.daterangepicker({
        singleDatePicker: true,
        timePicker: false,
        showDropdowns: true,
        minYear: 1901,
        maxYear: parseInt(moment().format("YYYY"), 10),
        locale: {
            format: "MM/DD/YYYY",
        },
    });
    dueDate.daterangepicker({
        singleDatePicker: true,
        timePicker: false,
        showDropdowns: true,
        minYear: 1901,
        maxYear: parseInt(moment().format("YYYY"), 10),
        locale: {
            format: "YYYY-MM-DD",
        },
    });

    invoiceDataTable =  invoiceTable.DataTable({
        rowReorder: true,
        columnDefs: [
            { searchable: false, orderable: false, className: 'reorder', targets: 0 },
            { searchable: false, orderable: false, targets: 1},
            { searchable: false, orderable: false, targets: 9},
        ]
    });

    if (invoiceDataTable) {

        function getTableIndexes(cellDataId) {
            return invoiceDataTable.cell($(`#${cellDataId}`).parent('td')).index();
        }

        function updateAmountGst(rowId, product, qty) {
            let gst = '';
            let productPrice = product.pricings.productprice;

            if (product.gstfree == '0') {
                gst = ((productPrice * qty) / 11).toFixed(2)
            }
            invoiceDataTable.cell({row: rowId, column:8}).data(gst).draw(); //gst

            invoiceDataTable.cell({row: rowId, column:7}).data((productPrice * qty).toFixed(2)).draw(); //amount
        }

        function getProduct(productId) {
            let product = ajaxProducts.filter( product => product.id == productId )
            return product[0];
        }

        $(document).on('change', '.product-select2', function(e) {

            let idx = getTableIndexes($(this).attr('id'))
            let product = getProduct($(this).val())

            invoiceDataTable.cell({row: idx.row, column:4}).data(product.webdescription).draw();
            //invoiceDataTable.cell({row: idx.row, column:6}).data(product.pricings.productprice).draw();

            let qty = $(`#qtySelect${idx.row}`).val()
            $(`#priceSelect${idx.row}`).val(product.pricings.productprice);

            updateAmountGst(idx.row, product, qty);

            calculateTotal()

            if(tempId == ""){
                addRow();
            }

        });

        $(document).on('change keyup', '.product-qty', function(e) {

            let idx     = getTableIndexes($(this).attr('id'))
            let product = getProduct($(`#productSelect${idx.row}`).val())

            updateAmountGst(idx.row, product, $(this).val());
            calculateTotal()
        });

        $(document).on('change keyup', '#discount_per, #shipping_cost, #shipping_tax', function(e) {
            calculateTotal()
        });

        $('#addLine').on('click', function () {
            addRow()
        });
        // Automatically add a first row of data
        $('#addLine').click();

        $('#clearAllLines').on('click', function () {
            invoiceDataTable.clear().draw();
            addRow()
            calculateTotal()
        });

        $('#invoice-table tbody').on( 'click', '.fa.fa-trash.pointer', function () {
            invoiceDataTable.row( $(this).parents('tr') ).remove().draw();
            calculateTotal()
        });

        invoiceDataTable.on('order.dt search.dt', function () {
            let i = 1;

            invoiceDataTable.cells(null, 1, { search: 'applied'}).every(function (cell) {
                this.data(i++);
            });
        }).draw();
    }

    $("#example1").DataTable({
        // stateSave: true,
        columns: [
            { data: "quote_id" },
            { data: "customer_info.customername" },
            {
                data: "quote",
                render: function (data) {
                    return data.charAt(0).toUpperCase()+ data.slice(1);
                }
            },
            { data: "status" },
        ],
        ajax: {
            url: "/pos/api",
            method: "GET",
            data: function (d) {},
        },
        fnRowCallback: function (nRow, aData, iDisplayIndex) {
            nRow.setAttribute("id", aData["quote_id"]);
        },
        columnDefs: [
            {
                targets: 3,
                width: "10%",
                data: null,
                render: function (data, type, full, meta) {
                    if (type === "display") {
                        data = `
                            <a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"
                                onclick="editQuotes(${full["quote_id"]})" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;">
                                <i class="fa fa-info"></i></a>
                            <a data-simpletooltip-text="De-activate Item"  class="js-simple-tooltip profile-btn-del" onclick="JSalert(${full["quote_id"]},'${full["quote_name"]}')"
                                style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" >
                                <i class="fa fa-times"></i></a>
                            <a data-simpletooltip-text="Download Report" href="javascript:void(0)"  class="js-simple-tooltip profile-btn" onclick="downloadPdf(${full["quote_id"]})"
                                style="background: green; font-size: 18px; padding: 6px; padding-left: 15px; padding-right: 15px; margin-top: 0px; margin-left: 5px;" >
                                <i class="fa fa-download"></i></a>
                        `
                    }
                    return data;
                },
            },
        ],
    });
    $("#example1_wrapper")
        .find(".dataTables_filter")
        .prepend(
            '<a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewQuotes()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"><i class="fa fa-plus"></i></a> '
        );
});

function product_def_load(){
    $.ajax({
        url: '/products/api/getAllAjax',
        dataType: 'json',
        data: {
            storeId: $('#store').val(),
        },
        success: function(data){
            ajaxProducts = data.results
        }
    });
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
                    url: "/pos/api/" + id,
                    method: "DELETE",
                })
                    .done(function (data) {
                        if (data.status === "success") {
                            swal(
                                {
                                    title: protitle + "  deactivated",
                                    text: "If this was a mistake you can re-activate the item in the inactive quotes screen!",
                                    type: "success",
                                },
                                function (isConfirm) {
                                    $("#example1")
                                        .DataTable()
                                        .ajax.url("pos/api")
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
                                        .ajax.url("pos/api")
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

function addNewQuotes() {
    tempId = "";
    $(".savebut").show();
    $(".updatebut").hide();
    $("#quotes_name").val("");
    $("#quotes_description").val("");
    $("#billing-address").val();
    $("#shipping-to").val();
    $("#terms").val();
    $("#invoice-date").val();
    $("#due-date").val();
    $("#ship-via").val();
    $("#shipping-date").val();
    $("#tracking-no").val();
    $("#message-invoice").val();
    $("#discount_per").val();
    $("#shipping_tax").val();
    $("#shipping_cost").val();
}

function getFormData($form, $products) {
    let unindexed_array = $form.serializeArray();
    let products = $products;
    let indexed_array = {};
    $.map(unindexed_array, function (n, i) {
        indexed_array[n["name"]] = n["value"];
    });
    indexed_array["products"] =products;
    indexed_array["subTotal"] = $("#subTotal").text();
    indexed_array["subTotalPercent"] = $("#subTotalPercent").text();
    indexed_array["gstValue"] = $("#gstValue").text();
    indexed_array["total"] = $("#gstValue").text();
    return indexed_array;
}

function addQuotes() {
    let $form           = $("#quotesForm");
    let customerId      = $('#customer').val()
    let customerDetails = ''

    if (customerId != '') {

        customerDetails = customerList.find((customer) => customer.customerid == customerId)

    }

    let invoiceProducts = invoiceDataTable.data().toArray()

    invoiceProducts = invoiceProducts.map((product, index) => ({
        'service-date'   : $(`#serviceSelect${index}`).val(),
        'sku-id'   : $(`#productSelect${index}`).val(),
        'sku-name'   : $(`#productSelect${index}`).find('option:selected').text(),
        'quantity'   : $(`#qtySelect${index}`).val(),
        'description': $(product[4]).text(),
        'price'   : $(`#priceSelect${index}`).val(),
        'gst'   : product[8],
    }));
    let $products = invoiceProducts;

    if (
        $("#quotes_name").val() !== "" &&
        $("#quotes_description").val() !== ""
    ) {
        $.ajax({
            type: "POST",
            url: "/pos/api",
            data: {
                _token: csrfToken,
                data: getFormData($form, $products),
            },
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("add event success", "Success", {
                        timeOut: 3000,
                    });
                    $(".profile-btn-snd").click();
                    $("#example1").DataTable().ajax.url("pos/api").load();
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

function updateQuotes() {
    let $form = $("#quotesForm");
    let pre_row;
    let invoiceProducts = invoiceDataTable.data().toArray();
    invoiceProducts = invoiceProducts.map(
        (product, index) => (
            index = $(product[2]).attr('id').replace ( /[^\d.]/g, '' ),
            {
                'service-date'   : $(`#serviceSelect${index}`).val(),
                'sku-id'   : $(`#productSelect${index}`).val(),
                'sku-name'   : $(`#productSelect${index}`).find('option:selected').text(),
                'quantity'   : $(`#qtySelect${index}`).val(),
                'description': $(product[4]).text(),
                'price'   : $(`#priceSelect${index}`).val(),
                'gst'   : product[8],
            }
        )
    );
    let $products = invoiceProducts;
    if ($form.validate().form()) {
        $.ajax({
            type: "PUT",
            url: "/pos/api/" + tempId,
            data: getFormData($form, $products),
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("update event success", "Success", {
                        timeOut: 3000,
                    });
                    $(".profile-btn-snd").click();
                    $("#example1").DataTable().ajax.url("pos/api").load();
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

function editQuotes(id) {
    tempId = id;
    $(".savebut").hide();
    $(".updatebut").show();
    $("#temptitle").html("Edit Quotes");

    $.ajax({
        url: "/pos/api",
        data: {
            id: id,
        },
        method: "GET",
    })
        .done(function (data) {
            if (data.status === "success") {
                let quotesData = data.data[0];
                $("#quotes_name").val(quotesData["quotes_name"]);
                $("#quotes_description").val(quotesData["quotes_description"]);
                $("#quote").val(quotesData["quote"]);
                if(quotesData["mark-paid"] == 1){
                    $("#mark-paid").prop("checked", true);
                }else{
                    $("#mark-paid").prop("checked", false);
                }
                if(quotesData["quote"] == "sale"){
                    $("#mark-paid-div").show();
                }else{
                    $("#mark-paid-div").hide();
                }
                $("#store").val(quotesData["store"]);
                $("#frequency").val(quotesData["frequency"]);
                $("#customer").val(quotesData["customer"]);
                $("#customer-email").val(quotesData["customer-email"]);
                if(quotesData["send-email-customer"] == 1){
                    $("#send-email-customer").prop("checked", true);
                }else{
                    $("#send-email-customer").prop("checked", false);
                }
                $("#billing-address").val(quotesData["billing-address"]);
                $("#shipping-to").val(quotesData["shipping-to"]);
                $("#terms").val(quotesData["terms"]);
                $("#invoice-date").val(quotesData["invoice-date"]);
                $("#due-date").val(quotesData["due-date"]);
                $("#ship-via").val(quotesData["ship-via"]);
                $("#shipping-date").val(quotesData["shipping-date"]);
                $("#tracking-no").val(quotesData["tracking-no"]);
                $("#message-invoice").val(quotesData["message-invoice"]);
                $("#discount_per").val(quotesData["discount_per"]);
                $("#shipping_tax").val(quotesData["shipping_tax"]);
                $("#shipping_cost").val(quotesData["shipping_cost"]);
                selectTagging();
                addRow(quotesData["products"], "edit");
                calculateTotal();
            } else {
                toastr.error("add event failed", "error", { timeOut: 3000 });
            }
        })
        .fail(function (jqXHR, status, errorThrown) {
            alert(status + "<br>" + errorThrown);
        });
}

function downloadPdf(id) {
    $.ajax({
        url: `/pos/pdf/download`,
        data: {
            id: id
        },
        method: "POST",
        xhrFields: {
            responseType: 'blob'
        },
        success: function(response, textStatus, xhr){
            console.log(xhr.status);
            var blob = new Blob([response]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "Sample.pdf";
            link.click();
        },
        error: function (response, status, xhr) {
            // Custom event to handle the error.

            console.log(response, status, xhr);
        }
    })
}

// change Type Combo Function

$(document).on("change", "#quote", function () {
    let type = this.value;
    if (type === "sale") {
        $("#temptitle").text("New Invoice");
        $("#mark-paid").prop("checked", true);
        $("#mark-paid-div").show();
        $("#saveBtn").html('Save Invoice');

    } else {
        $("#temptitle").text("New Quote");
        $("#mark-paid").prop("checked", false);
        $("#mark-paid-div").hide();
        $("#saveBtn").html('Save Quote');
    }
});

$(document).on("click", "#send-email-customer", function () {

    let shouldSend = ''
    if ($(this).is(":checked")) {
        shouldSend = ' & Send'
    }

    if ($('#quote').val() == "sale") {
        $("#saveBtn").html('Save Invoice'+shouldSend);
    } else {
        $("#saveBtn").html('Save Quote'+shouldSend);
    }
});

// change customer list Function

$(document).on("change", "#customer", function () {
    let customer_id = this.value;
    const item = customerList.find((x) => x.customerid === customer_id);
    if (item !== undefined) {
        $("#customer-email").val(item["email"]);
        $("#billing-address").val(item["billingaddress"]);
        $("#shipping-to").val(item["shippingaddress"]);
        $("#terms").val(item["terms"]);
        let term_id = item["terms"];
        let day;
        if(term_id == "7_day"){
            day = 7;
        }else if(term_id == "15_day"){
            day = 15;
        }else if(term_id == "30_day"){
            day = 30;
        }else{
            day =0;
        }
        due_date = new Date();
        due_date.setDate(due_date.getDate() + day);
        due_date = due_date.toISOString().slice(0, 10);
        $("#due-date").val(due_date);

    } else {
        $("#customer-email").val("");
        $("#billing-address").val("");
    }
});

// update due date by selected term

$(document).on("change", "#terms", function () {
    let term_id = this.value;
    let day;
    if(term_id == "7_day"){
        day = 7;
    }else if(term_id == "15_day"){
        day = 15;
    }else if(term_id == "30_day"){
        day = 30;
    }else{
        day =0;
    }
    due_date = new Date();
    due_date.setDate(due_date.getDate() + day);
    due_date = due_date.toISOString().slice(0, 10);
    $("#due-date").val(due_date);
});

//for select2 option dropdown list

function selectTagging() {
    $('.selectTag').select2({
        dropdownParent: $("#offcanvasExample")
    });
}
$(document).ready(function () {
    selectTagging();
});
