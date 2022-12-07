let tempId;
let autocomplete;
$(document).ready(function () {
    $("#allocatedtag").select2();
    $("#upload_file").click(function () {
        $("#upload_form").submit();
        $("#upload_file").attr("disabled", "disabled");
    });

    $("#upload_form").on("submit", function (event) {
        event.preventDefault();
        $.ajax({
            url: "customers/upload",
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
        if (!("email" in column_data)) {
            toastr.error("you must set email column", "error", {
                timeOut: 3000,
            });
            return false;
        } else {
            event.preventDefault();

            $.ajax({
                url: "customers/import",
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
                            .ajax.url("customers/api")
                            .load();
                    }
                },
            });
        }
    });
    $("#historytable").DataTable({
        // stateSave: true,
        columns: [
            { data: "recnum" },
            { data: "discounttotal" },
            { data: "gsttotal" },
            { data: "locationid" },
            { data: "mediatype" },
            { data: "saletotal" },
            { data: "staffid" },
            { data: "terminalid" },
        ],
        ajax: {
            url: "/customers/history",
            method: "GET",
        },
        responsive: true,
    });
    $("#example1").DataTable({
        // stateSave: true,
        columns: [
            { data: "customerid" },
            { data: "customername" },
            { data: "email" },
            { data: "mobile" },
            { data: "customerpoints" },
            { data: "accountbal" },
            { data: "status" },
            { data: "status" },
        ],
        ajax: {
            url: "/customers/api",
            method: "GET",
        },
        fnRowCallback: function (nRow, aData, iDisplayIndex) {
            nRow.setAttribute("id", aData["customerid"]);
        },
        columnDefs: [
            {
                targets: 6,
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
                targets: 7,
                data: null,
                render: function (data, type, full, meta) {
                    if (type === "display") {
                        if (data === "inactive") {
                            data =
                                '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editCustomer(' +
                                full["customerid"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a><a data-simpletooltip-text="Re-activate Item"  class="js-simple-tooltip profile-btn" onclick="Realert(' +
                                full["customerid"] +
                                ",`" +
                                full["customername"] +
                                '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; background: green;" ><i class="fa fa-check"></i></a>';
                        } else {
                            data =
                                '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editCustomer(' +
                                full["customerid"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a> <a data-simpletooltip-text="De-activate Item"  class="js-simple-tooltip profile-btn-del" onclick="JSalert(' +
                                full["customerid"] +
                                ",`" +
                                full["customername"] +
                                '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" ><i class="fa fa-times"></i></a>';
                        }
                    }
                    return data;
                },
            },
        ],
    });
    // $("#example1_wrapper").find('.dataTables_filter').prepend('<div style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; display: inline-flex;" class="simpletooltip_container"><select class="form-control" id="status_show" name="status_show" onchange="getShowStatus(this)"><option value="all">All</option><option value="active">Active</option><option value="inactive">Deactive</option></select></div><a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewCustomer()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"><i class="fa fa-plus"></i></a> <a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Import" class="js-simple-tooltip profile-btn" onclick="" data-bs-toggle="offcanvas" href="#offcanvasExample2" aria-controls="offcanvasExample2"><i class="fa fa-arrow-right"></i></a>');
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
                '<a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewCustomer()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample">' +
                '<i class="fa fa-plus"></i>' +
                "</a>"
        );
    $('button[data-bs-toggle="tab"]').on("shown.bs.tab", function (e) {
        $($.fn.dataTable.tables(true))
            .DataTable()
            .columns.adjust()
            .responsive.recalc();
    });


    $(document).on("click", "#enable_billing_address", function (e) {
        let is_enable = $("input[type='checkbox'][name='enable_billing_address']:checked").val();
        if(is_enable == "on"){
            let billingaddress = $("#billingaddress").val();
            $("#shippingaddress").val(billingaddress);
        }else{
            $("#shippingaddress").val("");
        }
    });
});

function initAutocomplete() {
    // Create the autocomplete object, restricting the search to geographical
    // location types.
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */ (document.getElementById("billingaddress")),
        { types: ["geocode"] }
    );

    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */ (document.getElementById("shippingaddress")),
        { types: ["geocode"] }
    );
}



// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            let geolocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
            };
            let circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy,
            });
            autocomplete.setBounds(circle.getBounds());
        });
    }
}

function getShowStatus(item) {
    $("#example1")
        .DataTable()
        .ajax.url("customers/api?status=" + item.value)
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
                    url: "/customers/api/" + id,
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
                                    $("#status_show").val("all");
                                    $("#example1")
                                        .DataTable()
                                        .ajax.url("customers/api")
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
                                    $("#example1")
                                        .DataTable()
                                        .ajax.url("customers/api")
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
                    url: "/customers/api/" + id,
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
                                        .ajax.url("customers/api")
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
                                        .ajax.url("customers/api")
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

function addNewCustomer() {
    tempId = "";
    // $("#historytable").DataTable().resize();
    $("#temptitle").html("New Customer");
    $(".savebut").show();
    $(".updatebut").hide();
    $("#customerfirstname").val("");
    $("#customerlastname").val("");
    $("#customerpoints").val("");

    $(".customer-points-value").text(0);
    $(".account-balance-value").text(0);

    $("#email").val("");
    $("#mobile").val("");
    $("#accountbal").val("");
    $("#card_number").val("");
    $("#terms").val("");
    $("#dob").val(new Date().toLocaleDateString("en-CA"));
    $("#allocatedtag").val("");
    $("#allocatedtag").trigger("change");

    $("#historytable").DataTable().ajax.url("customers/history").load();
    $("#historytable").DataTable().columns.adjust().responsive.recalc();

    $("#billingaddress").val("");
    $("#shippingaddress").val("");
}

function getFormData($form) {
    const unindexed_array = $form.serializeArray();
    const indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        indexed_array[n["name"]] = n["value"];
    });
    indexed_array["allocatedtags"] = $("#allocatedtag").val().join(",");
    indexed_array["billingaddress"] = $("#billingaddress").val();
    indexed_array["shippingaddress"] = $("#shippingaddress").val();
    indexed_array["terms"] = $("#terms").val();
    return indexed_array;
}

function addCustomer() {
    let $form = $("#customerForm");

    if ($form.validate().form()) {
        $.ajax({
            type: "POST",
            url: "/customers/api",
            data: {
                _token: csrfToken,
                data: getFormData($form),
            },
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("add event success", "Success", {
                        timeOut: 3000,
                    });
                    $("#status_show").val("all");
                    $(".profile-btn-snd").click();
                    $("#example1").DataTable().ajax.url("customers/api").load();
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

function updateCustomer() {
    let $form = $("#customerForm");

    if ($form.validate().form()) {
        $.ajax({
            type: "PUT",
            url: "/customers/api/" + tempId,
            data: getFormData($form),
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("update event success", "Success", {
                        timeOut: 3000,
                    });
                    $("#status_show").val("all");
                    $(".profile-btn-snd").click();
                    $("#example1").DataTable().ajax.url("customers/api").load();
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

function editCustomer(id) {
    tempId = id;
    $(".savebut").hide();
    $(".updatebut").show();
    $("#temptitle").html("Edit Customer");

    $.ajax({
        url: "/customers/api",
        data: {
            id: id,
        },
        method: "GET",
    })
        .done(function (data) {
            if (data.status === "success") {
                let customer_data = data.data[0];
                $("#customerfirstname").val(customer_data["customerfirstname"]);
                $("#customerlastname").val(customer_data["customerlastname"]);
                $("#customerpoints").val(customer_data["customerpoints"]);
                $("#email").val(customer_data["email"]);
                $("#mobile").val(customer_data["mobile"]);
                $("#accountbal").val(customer_data["accountbal"]);
                $("#card_number").val(customer_data["card_number"]);

                // for showing total price based customer
                if(data.total_sales_by_customerid){
                    $(".total-sales-value").text(data.total_sales_by_customerid);
                }
                if(customer_data["customerpoints"]){
                    $(".customer-points-value").text(customer_data["customerpoints"]);
                }

                if(customer_data["accountbal"]){
                    $(".account-balance-value").text(customer_data["accountbal"]);
                }

                $("#terms").val(customer_data["terms"]);
                $("#billingaddress").val(customer_data["billingaddress"]);
                $("#shippingaddress").val(customer_data["shippingaddress"]);
                $("#gender").val(customer_data["gender"]);
                $("#dob").val(
                    new Date(
                        customer_data["dob"].replace(/-/g, "/")
                    ).toLocaleDateString("en-CA")
                );

                let staffData = customer_data["allocatedtags"].split(",");
                $("#allocatedtag").val(staffData);
                $("#allocatedtag").trigger("change");
            } else {
                toastr.error("add event failed", "error", { timeOut: 3000 });
            }
        })
        .fail(function (jqXHR, status, errorThrown) {
            alert(status + "<br>" + errorThrown);
        });
    $("#historytable")
        .DataTable()
        .ajax.url("customers/history?id=" + id)
        .load();
}

