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
    $("#allocatedstaff").select2();

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

    // upload image

    $("#image").change(function () {
        $("#image_preview").show();
        let url = window.URL.createObjectURL(this.files[0]);
        $("#image_preview").attr("src", url);
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
        if (!("locationemail" in column_data)) {
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
                            .ajax.url("locations/api?status=all")
                            .load();
                    }
                },
            });
        }
    });
    $("#example1").DataTable({
        // stateSave: true,
        columns: [
            { data: "locationid" },
            { data: "locationname" },
            { data: "locationemail" },
            { data: "staffLists" },
            { data: "status" },
            { data: "status" },
        ],
        ajax: {
            url: "/locations/api",
            method: "GET",
        },
        fnRowCallback: function (nRow, aData, iDisplayIndex) {
            nRow.setAttribute("id", aData["staffid"]);
        },
        columnDefs: [
            {
                targets: 5,
                width: "10%",
                data: null,
                render: function (data, type, full, meta) {
                    if (type === "display") {
                        if (data === "inactive") {
                            data =
                                '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editLocation(' +
                                full["locationid"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a><a data-simpletooltip-text="Re-activate Item"  class="js-simple-tooltip profile-btn" onclick="Realert(' +
                                full["locationid"] +
                                ",`" +
                                full["locationname"] +
                                '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; background: green;" ><i class="fa fa-check"></i></a>';
                        } else {
                            data =
                                '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editLocation(' +
                                full["locationid"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a><a data-simpletooltip-text="De-activate Item"  class="js-simple-tooltip profile-btn-del" onclick="JSalert(' +
                                full["locationid"] +
                                ",`" +
                                full["locationname"] +
                                '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" ><i class="fa fa-times"></i></a>';
                        }
                    }
                    return data;
                },
            },
            {
                targets: 3,
                width: "30%",
                data: null,
                render: function (data, type, full, meta) {
                    if (type === "display") {
                        let staff_lists = JSON.parse(full["staffLists"]);
                        data = "";
                        for (let i = 0; i < staff_lists.length; i++) {
                            data =
                                data +
                                '<img class="avatar-img" src="' +
                                staff_lists[i][1] +
                                '"/>' +
                                staff_lists[i][0] +
                                "<br>";
                        }
                    }
                    return data;
                },
            },
            {
                targets: 4,
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
    //     '<div style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; display: inline-flex;" class="simpletooltip_container"><select class="form-control" id="status_show" name="status_show" onchange="getShowStatus(this)"><option value="all">All</option><option value="active">Active</option><option value="inactive">Deactive</option></select></div><a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewLocation()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"><i class="fa fa-plus"></i></a> <a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Import" class="js-simple-tooltip profile-btn" onclick="" data-bs-toggle="offcanvas" href="#offcanvasExample2" aria-controls="offcanvasExample2"><i class="fa fa-arrow-right"></i></a>');
    $("#example1_wrapper")
        .find(".dataTables_filter")
        .prepend(
            '<div style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; display: inline-flex;" class="simpletooltip_container"><select class="form-control" id="status_show" name="status_show" onchange="getShowStatus(this)"><option value="all">All</option><option value="active">Active</option><option value="inactive">Deactive</option></select></div><a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewLocation()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"><i class="fa fa-plus"></i></a>'
        );
});

function initAutocomplete() {
    // Create the autocomplete object, restricting the search to geographical
    // location types.
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
            let val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
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
        .ajax.url("locations/api?status=" + item.value)
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
                    url: "/locations/api/" + id,
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
                                        .ajax.url("locations/api?status=all")
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
                                        .ajax.url("locations/api?status=all")
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
                    method: "POST",
                    url: "/locations/api/" + id,
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
                                        .ajax.url("locations/api?status=all")
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
                                        .ajax.url("locations/api?status=all")
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

function addNewLocation() {
    tempId = "";
    $("#temptitle").html("New Location");
    $(".savebut").show();
    $(".updatebut").hide();
    $("#locationname").val("");
    $("#locationemail").val("");
    $("#locationabn").val("");
    $("#locationphone").val("");

    $("#marketingfee").val("");
    $("#franchfee").val("");
    $("#bankname").val("");
    $("#accountname").val("");
    $("#bsb").val("");
    $("#accountnumber").val("");
    $("#allocatedstaff").val("");
    $("#allocatedstaff").trigger("change");
    $("#invoice-footer-message").val("");
    $("#quote-footer-message").val("");
}

function getFormData($form) {
    let formData = new FormData();
    let fileImage = $("#image");
    if (fileImage[0].value === "" || fileImage[0].value === null) {
        console.log(fileImage);
    } else {
        formData.append("image", fileImage[0].files[0]);
    }

    let unindexed_array = $form.serializeArray();
    let indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        indexed_array[n["name"]] = n["value"];
    });

    indexed_array["allocatedstaff"] = $("#allocatedstaff").val().join(",");

    // set form data payments
    indexed_array["marketingfee"] = $("#marketingfee").val();
    indexed_array["franchfee"] = $("#franchfee").val();
    indexed_array["bankname"] = $("#bankname").val();
    indexed_array["accountname"] = $("#accountname").val();
    indexed_array["bsb"] = $("#bsb").val();
    indexed_array["accountnumber"] = $("#accountnumber").val();
    indexed_array["invoice-footer-message-1"] = $("#invoice-footer-message-1").val();
    indexed_array["invoice-footer-message-2"] = $("#invoice-footer-message-2").val();
    indexed_array["quote-footer-message-1"] = $("#quote-footer-message-1").val();
    indexed_array["quote-footer-message-2"] = $("#quote-footer-message-2").val();
    formData.append("data", JSON.stringify(indexed_array));
    return formData;
}

function addLocation() {
    let $form = $("#locationForm");

    if ($("#locationname").val() !== "" && $("#locationemail").val() !== "") {
        $.ajax({
            type: "POST",
            url: "/locations/api",
            data: getFormData($form),
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("add event success", "Success", {
                        timeOut: 3000,
                    });
                    $("#status_show").val("all");
                    $(".profile-btn-snd").click();
                    $("#example1")
                        .DataTable()
                        .ajax.url("locations/api?status=all")
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

function updateLocation() {
    let $form = $("#locationForm");

    if ($form.validate().form()) {
        $.ajax({
            type: "POST",
            url: "/locations/api/" + tempId,
            data: getFormData($form),
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("update event success", "Success", {
                        timeOut: 3000,
                    });
                    $("#status_show").val("all");
                    $(".profile-btn-snd").click();
                    $("#example1")
                        .DataTable()
                        .ajax.url("locations/api?status=all")
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

function editLocation(id) {
    tempId = id;
    $(".savebut").hide();
    $(".updatebut").show();
    $("#temptitle").html("Edit Location");

    $.ajax({
        url: "/locations/api",
        data: {
            id: id,
        },
        method: "GET",
    })
        .done(function (data) {
            if (data.status === "success") {
                let location_data = data.data[0];
                $("#locationname").val(location_data["locationname"]);
                $("#locationemail").val(location_data["locationemail"]);
                $("#locationabn").val(location_data["locationabn"]);
                $("#locationphone").val(location_data["locationphone"]);
                $("#address").val(location_data["address"]);
                $("#street_number").val(location_data["street_number"]);
                $("#route").val(location_data["route"]);
                $("#locality").val(location_data["locality"]);
                $("#administrative_area_level_1").val(
                    location_data["administrative_area_level_1"]
                );
                $("#postal_code").val(location_data["postal_code"]);
                $("#country").val(location_data["country"]);
                $("#cityLat").val(location_data["cityLat"]);
                $("#cityLng").val(location_data["cityLng"]);
                let staffData = location_data["allocatedstaff"].split(",");
                $("#allocatedstaff").val(staffData);
                $("#allocatedstaff").trigger("change");

                //    set Payments and Fee

                $("#marketingfee").val(location_data["marketingfee"]);
                $("#franchfee").val(location_data["franchfee"]);
                $("#bankname").val(location_data["bankname"]);
                $("#accountname").val(location_data["accountname"]);
                $("#bsb").val(location_data["bsb"]);
                $("#accountnumber").val(location_data["accountnumber"]);
                $("#image_preview").show();
                $("#image_preview").attr("src", location_data["image"]);
                $("#invoice-footer-message-1").val(location_data["invoice-footer-message-1"]);
                $("#invoice-footer-message-2").val(location_data["invoice-footer-message-2"]);
                $("#quote-footer-message-1").val(location_data["quote-footer-message-1"]);
                $("#quote-footer-message-2").val(location_data["quote-footer-message-2"]);
            } else {
                toastr.error("add event failed", "error", { timeOut: 3000 });
            }
        })
        .fail(function (jqXHR, status, errorThrown) {
            alert(status + "<br>" + errorThrown);
        });
}
