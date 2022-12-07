let editStatus = false;
let editStaffButtton = $("#update-staff");
let staffFirstName = $("#stafffirstname");
let staffLastName = $("#stafflastname");
let terminalDisplayName = $("#terminaldisplayname");
let staffPin = $("#staffpin");
let staffNotes = $("#staffnotes");
let department = $("#department");
let status = $("#status");
let isSalary = $("#issalery");
let payRate = $("#payrateperhour");
let salary = $("#salery");
let address = $("#address");
let nextOfName = $("#nextofkinname");
let nextOfPhone = $("#nextofkinphone");
let phone = $("#phonenumber");
let taxNumber = $("#taxfilenumber");
let availMonday = $("#availmonday");
let availTuesday = $("#availtuesday");
let availWednesday = $("#availwednesday");
let availThursday = $("#availthursday");
let availFriday = $("#availfriday");
let availSaturday = $("#availsaturday");
let availSunday = $("#availsunday");
let customMonday = $("#custommonday");
let customTuesday = $("#customtuesday");
let customWednesday = $("#customwednesday");
let customThursday = $("#customthursday");
let customFriday = $("#customfriday");
let customSaturday = $("#customsaturday");
let customSunday = $("#customsunday");
let divMonday = $("#div-monday");
let divTuesday = $("#div-tuesday");
let divWednesday = $("#div-wednesday");
let divThursday = $("#div-thursday");
let divFriday = $("#div-friday");
let divSaturday = $("#div-saturday");
let divSunday = $("#div-sunday");
let streetNumber = $("#street_number");
let route = $("#route");
let locality = $("#locality");
let administrative_area_level_1 = $("#administrative_area_level_1");
let country = $("#country");
let postalCode = $("#postal_code");
let cityLat = $("#cityLat");
let cityLng = $("#cityLng");

let search_status = "all";

let autocomplete;
let componentForm = {
    street_number: "short_name",
    route: "long_name",
    locality: "long_name",
    administrative_area_level_1: "short_name",
    country: "long_name",
    postal_code: "short_name",
};

$(function () {
    $("#example1").DataTable({
        // stateSave: true,
        columns: [
            { data: "staffimage" },
            { data: "stafffirstname" },
            { data: "stafflastname" },
            { data: "terminaldisplayname" },
            { data: "phonenumber" },
            { data: "address" },
            { data: "nextofkinname" },
            { data: "nextofkinphone" },
            { data: "status" },
            { data: "status" },
        ],
        ajax: {
            url: "/staff/api",
            method: "GET",
        },
        fnRowCallback: function (nRow, aData, iDisplayIndex) {
            nRow.setAttribute("id", aData["staffid"]);
        },
        columnDefs: [
            {
                targets: 0,
                data: null,
                className: "text-center",
                render: function (data, type, full, meta) {
                    if (type === "display") {
                        data = '<img class="avatar-img" src="' + data + '"/>';
                    }
                    return data;
                },
            },
            {
                targets: 8,
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
                targets: 9,
                data: null,
                render: function (data, type, full, meta) {
                    if (type === "display") {
                        if (data === "inactive") {
                            data =
                                '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editStaff(' +
                                full["staffid"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a> <a data-simpletooltip-text="Re-activate Item"  class="js-simple-tooltip profile-btn" onclick="Realert(' +
                                full["staffid"] +
                                ",`" +
                                full["stafffirstname"] +
                                '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; background: green;" ><i class="fa fa-check"></i></a>';
                        } else {
                            data =
                                '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editStaff(' +
                                full["staffid"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a> <a data-simpletooltip-text="De-activate Item"  class="js-simple-tooltip profile-btn-del" onclick="JSalert(' +
                                full["staffid"] +
                                ",`" +
                                full["stafffirstname"] +
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
            '<div style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; display: inline-flex;" class="simpletooltip_container"><select class="form-control" id="status_show" name="status_show" onchange="getShowStatus(this)"><option value="all">All</option><option value="active">Active</option><option value="inactive">Deactive</option></select></div><a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewStaff()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"><i class="fa fa-plus"></i></a>'
        );
});

function initAutocomplete() {
    // Create the autocomplete object, restricting the search to geographical
    // location types.
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */ (document.getElementById("address")),
        { types: ["geocode"] }
    );

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
        .ajax.url("staff/api?status=" + item.value)
        .load();
}

$("#staffimage").change(function () {
    $("#blah").show();
    let url = window.URL.createObjectURL(this.files[0]);
    $("#blah").attr("src", url);
});

availMonday.change(function (e) {
    if (e.target.value === "custom") {
        divMonday.css("visibility", "visible");
    } else {
        divMonday.css("visibility", "hidden");
        customMonday.val("");
    }
});
availTuesday.change(function (e) {
    if (e.target.value === "custom") {
        divTuesday.css("visibility", "visible");
    } else {
        divTuesday.css("visibility", "hidden");
        customTuesday.val("");
    }
});
availWednesday.change(function (e) {
    if (e.target.value === "custom") {
        divWednesday.css("visibility", "visible");
    } else {
        divWednesday.css("visibility", "hidden");
        customWednesday.val("");
    }
});
availThursday.change(function (e) {
    if (e.target.value === "custom") {
        divThursday.css("visibility", "visible");
    } else {
        divThursday.css("visibility", "hidden");
        customThursday.val("");
    }
});
availFriday.change(function (e) {
    if (e.target.value === "custom") {
        divFriday.css("visibility", "visible");
    } else {
        divFriday.css("visibility", "hidden");
        customFriday.val("");
    }
});
availSaturday.change(function (e) {
    if (e.target.value === "custom") {
        divSaturday.css("visibility", "visible");
    } else {
        divSaturday.css("visibility", "hidden");
        customSaturday.val("");
    }
});
availSunday.change(function (e) {
    if (e.target.value === "custom") {
        divSunday.css("visibility", "visible");
    } else {
        divSunday.css("visibility", "hidden");
        customSunday.val("");
    }
});
editStaffButtton.on("click", function (e) {
    if (editStaffButtton.text() === "Edit") {
        editStaffButtton.text("Update");
        staffFirstName.prop("disabled", false);
        staffLastName.prop("disabled", false);
        terminalDisplayName.prop("disabled", false);
        staffPin.prop("disabled", false);
        staffNotes.prop("disabled", false);
        department.prop("disabled", false);
        status.prop("disabled", false);
        isSalary.prop("disabled", false);
        payRate.prop("disabled", false);
        salary.prop("disabled", false);
        address.prop("disabled", false);
        nextOfName.prop("disabled", false);
        nextOfPhone.prop("disabled", false);
        phone.prop("disabled", false);
        taxNumber.prop("disabled", false);
        availMonday.prop("disabled", false);
        availTuesday.prop("disabled", false);
        availWednesday.prop("disabled", false);
        availThursday.prop("disabled", false);
        availFriday.prop("disabled", false);
        availSaturday.prop("disabled", false);
        availSunday.prop("disabled", false);
        customMonday.prop("disabled", false);
        customTuesday.prop("disabled", false);
        customWednesday.prop("disabled", false);
        customThursday.prop("disabled", false);
        customFriday.prop("disabled", false);
        customSaturday.prop("disabled", false);
        customSunday.prop("disabled", false);
    } else {
        updateStaff();
        editStaffButtton.text("Edit");
    }
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
                    url: "/staff/api/" + id,
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
                                        .ajax.url("staff/api?status=all")
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
                                        .ajax.url("staff/api?status=all")
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
                    contentType:
                        "application/x-www-form-urlencoded; charset=urf-8",
                    dataType: "json",
                    url: "/staff/api/" + id,
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
                                        .ajax.url("staff/api?status=all")
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
                                        .ajax.url("staff/api?status=all")
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

function addNewStaff() {
    tempId = "";
    $("#temptitle").html("New Staff");
    $(".savebut").show();
    $(".updatebut").hide();
    staffFirstName.val("");
    staffLastName.val("");
    terminalDisplayName.val("");
    staffPin.val("");
    staffNotes.val("");
    department.val("");
    status.val("active");
    isSalary.val("yes");
    payRate.val("");
    salary.val("");
    address.val("");
    nextOfName.val("");
    nextOfPhone.val("");
    phone.val("");
    taxNumber.val("");
    customMonday.val("");
    customTuesday.val("");
    customWednesday.val("");
    customThursday.val("");
    customFriday.val("");
    customSaturday.val("");
    customSunday.val("");
    availMonday.val("yes");
    availTuesday.val("yes");
    availWednesday.val("yes");
    availThursday.val("yes");
    availFriday.val("yes");
    availSaturday.val("yes");
    availSunday.val("yes");
    streetNumber.val("");
    route.val("");
    locality.val("");
    administrative_area_level_1.val("");
    country.val("");
    postalCode.val("");
    cityLat.val("");
    cityLng.val("");

    divMonday.css("visibility", "hidden");
    divTuesday.css("visibility", "hidden");
    divWednesday.css("visibility", "hidden");
    divThursday.css("visibility", "hidden");
    divFriday.css("visibility", "hidden");
    divSaturday.css("visibility", "hidden");
    divSunday.css("visibility", "hidden");

    staffFirstName.prop("disabled", false);
    staffLastName.prop("disabled", false);
    terminalDisplayName.prop("disabled", false);
    staffPin.prop("disabled", false);
    staffNotes.prop("disabled", false);
    department.prop("disabled", false);
    status.prop("disabled", false);
    isSalary.prop("disabled", false);
    payRate.prop("disabled", false);
    salary.prop("disabled", false);
    address.prop("disabled", false);
    nextOfName.prop("disabled", false);
    nextOfPhone.prop("disabled", false);
    phone.prop("disabled", false);
    taxNumber.prop("disabled", false);
    availMonday.prop("disabled", false);
    availTuesday.prop("disabled", false);
    availWednesday.prop("disabled", false);
    availThursday.prop("disabled", false);
    availFriday.prop("disabled", false);
    availSaturday.prop("disabled", false);
    availSunday.prop("disabled", false);
    customMonday.prop("disabled", false);
    customTuesday.prop("disabled", false);
    customWednesday.prop("disabled", false);
    customThursday.prop("disabled", false);
    customFriday.prop("disabled", false);
    customSaturday.prop("disabled", false);
    customSunday.prop("disabled", false);
}

function getFormData($form) {
    let formData = new FormData();
    let fileUpload = $("#staffimage");
    if (fileUpload[0].value === "" || fileUpload[0].value === null) {
        console.log(fileUpload);
    } else {
        formData.append("staffimage", fileUpload[0].files[0]);
    }

    let unindexed_array = $form.serializeArray();
    let indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        indexed_array[n["name"]] = n["value"];
    });

    indexed_array["staffname"] =
        indexed_array["stafffirstname"] + " " + indexed_array["stafflastname"];
    formData.append("data", JSON.stringify(indexed_array));
    return formData;
}

function getPutFormData($form) {
    let formData = new FormData();
    let fileUpload = $("#staffimage");
    if (fileUpload[0].value === "" || fileUpload[0].value === null) {
        console.log(fileUpload);
    } else {
        formData.append("staffimage", fileUpload[0].files[0]);
    }

    let unindexed_array = $form.serializeArray();
    let data_array = {};
    let indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        indexed_array[n["name"]] = n["value"];
    });
    indexed_array["staffname"] =
        indexed_array["stafffirstname"] + " " + indexed_array["stafflastname"];
    data_array["c_id"] = tempId;
    data_array["c_data"] = indexed_array;
    formData.append("data", JSON.stringify(data_array));
    return formData;
}

function addStaff() {
    let $form = $("#staffForm");

    if ($form.validate().form()) {
        $.ajax({
            type: "POST",
            url: "/staff/api",
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
                        .ajax.url("staff/api?status=all")
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

function updateStaff() {
    let $form = $("#staffForm");

    if ($form.validate().form()) {
        $.ajax({
            type: "POST",
            url: "/staff/api",
            data: getPutFormData($form),
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
                        .ajax.url("staff/api?status=all")
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

function editStaff(id) {
    tempId = id;
    $(".savebut").hide();
    $(".updatebut").show();
    $("#temptitle").html("Update staff");
    editStaffButtton.text("Edit");

    $.ajax({
        url: "/staff/api/",
        method: "GET",
        data: {
            id: id,
        },
    })
        .done(function (data) {
            if (data.status === "success") {
                let staff_data = data.data[0];
                $("#blah").show();
                $("#blah").attr("src", staff_data["staffimage"]);
                staffFirstName.val(staff_data["stafffirstname"]);
                staffLastName.val(staff_data["stafflastname"]);
                terminalDisplayName.val(staff_data["terminaldisplayname"]);
                staffPin.val(staff_data["staffpin"]);
                staffNotes.val(staff_data["staffnotes"]);
                department.val(staff_data["department"]);
                status.val(staff_data["status"]);
                isSalary.val(staff_data["issalery"]);
                payRate.val(staff_data["payrateperhour"]);
                salary.val(staff_data["salery"]);
                address.val(staff_data["address"]);
                nextOfName.val(staff_data["nextofkinname"]);
                nextOfPhone.val(staff_data["nextofkinphone"]);
                phone.val(staff_data["phonenumber"]);
                taxNumber.val(staff_data["taxfilenumber"]);
                availMonday.val(staff_data["availmonday"]);
                availTuesday.val(staff_data["availtuesday"]);
                availWednesday.val(staff_data["availwednesday"]);
                availThursday.val(staff_data["availthursday"]);
                availFriday.val(staff_data["availfriday"]);
                availSaturday.val(staff_data["availsaturday"]);
                availSunday.val(staff_data["availsunday"]);
                customMonday.val(staff_data["custommonday"]);
                customTuesday.val(staff_data["customtuesday"]);
                customWednesday.val(staff_data["customwednesday"]);
                customThursday.val(staff_data["customthursday"]);
                customFriday.val(staff_data["customfriday"]);
                customSaturday.val(staff_data["customsaturday"]);
                customSunday.val(staff_data["customsunday"]);
                streetNumber.val(staff_data["street_number"]);
                route.val(staff_data["route"]);
                locality.val(staff_data["locality"]);
                administrative_area_level_1.val(
                    staff_data["administrative_area_level_1"]
                );
                postalCode.val(staff_data["postal_code"]);
                country.val(staff_data["country"]);
                cityLat.val(staff_data["cityLat"]);
                cityLng.val(staff_data["cityLng"]);

                if (staff_data["availmonday"] === "custom") {
                    divMonday.css("visibility", "visible");
                }
                if (staff_data["availtuesday"] === "custom") {
                    divTuesday.css("visibility", "visible");
                }
                if (staff_data["availwednesday"] === "custom") {
                    divWednesday.css("visibility", "visible");
                }
                if (staff_data["availthursday"] === "custom") {
                    divThursday.css("visibility", "visible");
                }
                if (staff_data["availfriday"] === "custom") {
                    divFriday.css("visibility", "visible");
                }
                if (staff_data["availsaturday"] === "custom") {
                    divSaturday.css("visibility", "visible");
                }
                if (staff_data["availsunday"] === "custom") {
                    divSunday.css("visibility", "visible");
                }

                staffFirstName.prop("disabled", true);
                staffLastName.prop("disabled", true);
                terminalDisplayName.prop("disabled", true);
                staffPin.prop("disabled", true);
                staffNotes.prop("disabled", true);
                department.prop("disabled", true);
                status.prop("disabled", true);
                isSalary.prop("disabled", true);
                payRate.prop("disabled", true);
                salary.prop("disabled", true);
                address.prop("disabled", true);
                nextOfName.prop("disabled", true);
                nextOfPhone.prop("disabled", true);
                phone.prop("disabled", true);
                taxNumber.prop("disabled", true);
                availMonday.prop("disabled", true);
                availTuesday.prop("disabled", true);
                availWednesday.prop("disabled", true);
                availThursday.prop("disabled", true);
                availFriday.prop("disabled", true);
                availSaturday.prop("disabled", true);
                availSunday.prop("disabled", true);
                customMonday.prop("disabled", true);
                customTuesday.prop("disabled", true);
                customWednesday.prop("disabled", true);
                customThursday.prop("disabled", true);
                customFriday.prop("disabled", true);
                customSaturday.prop("disabled", true);
                customSunday.prop("disabled", true);
            } else {
                toastr.error("edit event failed", "error", { timeOut: 3000 });
            }
        })
        .fail(function (jqXHR, status, errorThrown) {
            alert(status + "<br>" + errorThrown);
        });
}
