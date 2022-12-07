let tempId;
let autocomplete;
let componentForm = {
    street_number: "short_name",
    route: "long_name",
    locality: "long_name",
    administrative_area_level_1: "short_name",
    country: "long_name",
    postal_code: "short_name",
};

$(document).ready(function () {
    $("#upload_file").click(function () {
        $("#upload_form").submit();
        $("#upload_file").attr("disabled", "disabled");
    });
    // CSV mapping import data to table
    $("#upload_form").on("submit", function (event) {
        event.preventDefault();
        $.ajax({
            url: "upload",
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
        if (!("supplieremail" in column_data)) {
            toastr.error("you must set email column", "error", {
                timeOut: 3000,
            });
            return false;
        } else {
            event.preventDefault();

            $.ajax({
                url: "import",
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
                            .ajax.url("suppliers/api?status=all")
                            .load();
                    }
                },
            });
        }
    });
    $("#example1").DataTable({
        // stateSave: true,
        columns: [
            { data: "supplier_id" },
            { data: "supplier_name" },
            { data: "supplier_email" },
            { data: "status" },
            { data: "status" },
        ],
        ajax: {
            url: "/suppliers/api",
            method: "GET",
        },
        fnRowCallback: function (nRow, aData, iDisplayIndex) {
            nRow.setAttribute("id", aData["staffid"]);
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
                                '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editSupplier(' +
                                full["supplier_id"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a><a data-simpletooltip-text="Re-activate Item"  class="js-simple-tooltip profile-btn" onclick="Realert(' +
                                full["supplier_id"] +
                                ",`" +
                                full["supplier_name"] +
                                '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; background: green;" ><i class="fa fa-check"></i></a>';
                        } else {
                            data =
                                '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editSupplier(' +
                                full["supplier_id"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a><a data-simpletooltip-text="De-activate Item"  class="js-simple-tooltip profile-btn-del" onclick="JSalert(' +
                                full["supplier_id"] +
                                ",`" +
                                full["supplier_name"] +
                                '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" ><i class="fa fa-times"></i></a>';
                        }
                    }
                    return data;
                },
            },
            {
                targets: 3,
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
        ],
    });
    // $("#example1_wrapper").find('.dataTables_filter').prepend(
    //     '<div style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; display: inline-flex;" class="simpletooltip_container"><select class="form-control" id="status_show" name="status_show" onchange="getShowStatus(this)"><option value="all">All</option><option value="active">Active</option><option value="inactive">Deactive</option></select></div><a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewSupplier()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"><i class="fa fa-plus"></i></a> <a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Import" class="js-simple-tooltip profile-btn" onclick="" data-bs-toggle="offcanvas" href="#offcanvasExample2" aria-controls="offcanvasExample2"><i class="fa fa-arrow-right"></i></a>');
    $("#example1_wrapper")
        .find(".dataTables_filter")
        .prepend(
            '<div style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; display: inline-flex;" class="simpletooltip_container"><select class="form-control" id="status_show" name="status_show" onchange="getShowStatus(this)"><option value="all">All</option><option value="active">Active</option><option value="inactive">Deactive</option></select></div><a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewSupplier()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"><i class="fa fa-plus"></i></a>'
        );
});

function initAutocomplete() {
    // Create the autocomplete object, restricting the search to geographical
    // supplier types.
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */ (document.getElementById("address")),
        { types: ["geocode"] }
    );

    // When the user selects an address from the dropdown, populate the address
    // fields in the form.
    autocomplete.addListener("place_changed", fillInAddress);
}

function fillInAddress() {
    // Get the place details from the autocomplete object.
    let place = autocomplete.getPlace();

    for (let component in componentForm) {
        document.getElementById(component).value = "";
        document.getElementById(component).disabled = false;
    }

    document.getElementById("cityLat").value = place.geometry.location.lat();
    document.getElementById("cityLng").value = place.geometry.location.lng();
    // Get each component of the address from the place details
    // and fill the corresponding field on the form.
    for (let i = 0; i < place.address_components.length; i++) {
        let addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
            document.getElementById(addressType).value =
                place.address_components[i][componentForm[addressType]];
        }
    }
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
        .ajax.url("suppliers/api?status=" + item.value)
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
                    url: "/suppliers/api/" + id,
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
                                    $(".profile-btn-snd").click();
                                    $("#example1")
                                        .DataTable()
                                        .ajax.url("suppliers/api?status=all")
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
                                        .ajax.url("suppliers/api?status=all")
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
                    url: "/suppliers/api/" + id,
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
                                        .ajax.url("suppliers/api?status=all")
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
                                        .ajax.url("suppliers/api?status=all")
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

function addNewSupplier() {
    tempId = "";
    $("#temptitle").html("New Supplier");
    $(".savebut").show();
    $(".updatebut").hide();
    $("#supplier_name").val("");
    $("#supplier_email").val("");
    $("#supplier_abn").val("");
    $("#supplier_phone").val("");

    $("#marketingfee").val("");
    $("#franchfee").val("");
    $("#bankname").val("");
    $("#accountname").val("");
    $("#bsb").val("");
    $("#accountnumber").val("");
}

function getFormData($form) {
    let unindexed_array = $form.serializeArray();
    let indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        indexed_array[n["name"]] = n["value"];
    });

    // set form data payments
    indexed_array["marketingfee"] = $("#marketingfee").val();
    indexed_array["franchfee"] = $("#franchfee").val();
    indexed_array["bankname"] = $("#bankname").val();
    indexed_array["accountname"] = $("#accountname").val();
    indexed_array["bsb"] = $("#bsb").val();
    indexed_array["accountnumber"] = $("#accountnumber").val();

    return indexed_array;
}

function addSupplier() {
    let $form = $("#supplierForm");
    if ($form.validate().form()) {
        $.ajax({
            type: "POST",
            url: "/suppliers/api",
            data: getFormData($form),
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("add event success", "Success", {
                        timeOut: 3000,
                    });
                    $("#status_show").val("all");
                    $(".profile-btn-snd").click();
                    $("#example1")
                        .DataTable()
                        .ajax.url("suppliers/api?status=all")
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
    }
}

function updateSupplier() {
    let $form = $("#supplierForm");

    if ($form.validate().form()) {
        $.ajax({
            type: "PUT",
            url: "/suppliers/api/" + tempId,
            data: getFormData($form),
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("update event success", "Success", {
                        timeOut: 3000,
                    });
                    $("#status_show").val("all");
                    $(".profile-btn-snd").click();
                    $("#example1")
                        .DataTable()
                        .ajax.url("suppliers/api?status=all")
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
    }
}

function editSupplier(id) {
    tempId = id;
    $(".savebut").hide();
    $(".updatebut").show();
    $("#temptitle").html("Edit Supplier");

    $.ajax({
        url: "/suppliers/api",
        data: {
            id: id,
        },
        method: "GET",
    })
        .done(function (data) {
            if (data.status === "success") {
                let supplierData = data.data[0];
                $("#supplier_name").val(supplierData["supplier_name"]);
                $("#supplier_email").val(supplierData["supplier_email"]);
                $("#supplier_abn").val(supplierData["supplier_abn"]);
                $("#supplier_phone").val(supplierData["supplier_phone"]);
                $("#address").val(supplierData["address"]);
                $("#street_number").val(supplierData["street_number"]);
                $("#route").val(supplierData["route"]);
                $("#locality").val(supplierData["locality"]);
                $("#administrative_area_level_1").val(
                    supplierData["administrative_area_level_1"]
                );
                $("#postal_code").val(supplierData["postal_code"]);
                $("#country").val(supplierData["country"]);
                $("#cityLat").val(supplierData["cityLat"]);
                $("#cityLng").val(supplierData["cityLng"]);

                //    set Payments and Fee

                $("#marketingfee").val(supplierData["marketingfee"]);
                $("#franchfee").val(supplierData["franchfee"]);
                $("#bankname").val(supplierData["bankname"]);
                $("#accountname").val(supplierData["accountname"]);
                $("#bsb").val(supplierData["bsb"]);
                $("#accountnumber").val(supplierData["accountnumber"]);
            } else {
                toastr.error("add event failed", "error", { timeOut: 3000 });
            }
        })
        .fail(function (jqXHR, status, errorThrown) {
            alert(status + "<br>" + errorThrown);
        });
}
