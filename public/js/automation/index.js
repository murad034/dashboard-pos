$(document).ready(function () {

    $("#example1").DataTable(
        {
            // stateSave: true,
            "columns": [
                {"data": "automationid"},
                {"data": "automationname"},
                {"data": "automationdescription"},
                {"data": "status"},
                {"data": "status"},
            ],
            "ajax": {
                "url": "/automation/api",
                "method": "GET",
                "data": function (d) {
                }
            },
            "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                nRow.setAttribute('id', aData["automationid"]);
            },
            "columnDefs": [{
                "targets": 4,
                "width": "10%",
                "data": null,
                "render": function (data, type, full, meta) {
                    if (type === 'display') {
                        if (data === "inactive") {
                            data = '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editAutomation(' + full["automationid"] + ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a><a data-simpletooltip-text="Open Builder"  class="js-simple-tooltip profile-btn" href="automation/builder?automationid=' + full["automationid"] + '" style="background: purple; font-size: 18px; padding: 6px; padding-left: 15px; padding-right: 15px; margin-top: 0px; margin-right: 5px;"><i class="fa fa-user-clock"></i></a><a data-simpletooltip-text="Re-activate Item"  class="js-simple-tooltip profile-btn" onclick="Realert(' + full["automationid"] + ',`' + full["automationname"] + '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; background: green;" ><i class="fa fa-check"></i></a>';
                        } else {
                            data = '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editAutomation(' + full["automationid"] + ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a><a data-simpletooltip-text="Open Builder"  class="js-simple-tooltip profile-btn" href="automation/builder?automationid=' + full["automationid"] + '" style="background: purple; font-size: 18px; padding: 6px; padding-left: 15px; padding-right: 15px; margin-top: 0px; margin-right: 5px;"><i class="fa fa-user-clock"></i></a><a data-simpletooltip-text="De-activate Item"  class="js-simple-tooltip profile-btn-del" onclick="JSalert(' + full["automationid"] + ',`' + full["automationname"] + '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" ><i class="fa fa-times"></i></a>';
                        }

                    }
                    return data;
                }
            },
                {
                    "targets": 3,
                    "data": null,
                    "className": "text-center",
                    "render": function (data, type, full, meta) {
                        if (type === 'display') {
                            if (data === "inactive") {
                                data = 'inactive';
                            } else {
                                data = 'active';
                            }
                        }
                        return data;
                    }
                },
            ]
        }
    );
    $("#example1_wrapper").find('.dataTables_filter').prepend(
        '<div style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; display: inline-flex;" class="simpletooltip_container"><select class="form-control" id="status_show" name="status_show" onchange="getShowStatus(this)"><option value="all">All</option><option value="active">Active</option><option value="inactive">Deactive</option></select></div><a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewAutomation()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"><i class="fa fa-plus"></i></a> ');

});

function getShowStatus(item) {
    $("#example1").DataTable().ajax.url("automation/api?status=" + item.value).load();
}

function JSalert(id, protitle) {
    swal({
            title: protitle + " will be deactivated",
            text: "Are you sure to proceed?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes Deactivate!",
            cancelButtonText: "No Take me back!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "/automation/api/" + id,
                    method: 'DELETE',
                }).done(function (data) {
                    if (data.status === "success") {
                        swal({
                            title: protitle + "  deactivated",
                            text: "If this was a mistake you can re-activate the item in the inactive automation screen!",
                            type: "success",
                        }, function (isConfirm) {
                            $('#status_show').val('all');
                            $('.profile-btn-snd').click();
                            $("#example1").DataTable().ajax.url("automation/api?status=all").load();
                        });
                    } else {
                        swal({
                            title: protitle + " can't be deactivated",
                            text: "This stock item is currently active on a keypad, please remove it from all keypads first then deactivate again!",
                            type: "warning",
                        }, function (isConfirm) {
                            $('#status_show').val('all');
                            $('.profile-btn-snd').click();
                            $("#example1").DataTable().ajax.url("automation/api?status=all").load();
                        });
                    }
                }).fail(function (jqXHR, status, errorThrown) {
                    alert(errorThrown);
                });


            } else {
                swal("Phew that was close!", "Nothing has changed!", "error");
            }
        });
}

function Realert(id, protitle) {
    swal({
            title: protitle + " will be reactivated",
            text: "Are you sure to proceed?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes Re-Activate!",
            cancelButtonText: "No Take me back!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    method: 'PUT',
                    url: "/automation/api/" + id,
                    data: {
                        status: "active"
                    },
                }).done(function (data) {
                    if (data.status === "success") {
                        swal({
                            title: protitle + "  reactivated",
                            text: "If this was a mistake you can re-activate the item in the inactive stocklist screen!",
                            type: "success",
                        }, function (isConfirm) {
                            $('#status_show').val('all');
                            $('.profile-btn-snd').click();
                            $("#example1").DataTable().ajax.url("automation/api?status=all").load();
                        });
                    } else {
                        swal({
                            title: protitle + " can't be deactivated",
                            text: "This stock item is currently active on a keypad, please remove it from all keypads first then reactivate again!",
                            type: "warning",
                        }, function (isConfirm) {
                            $('#status_show').val('all');
                            $('.profile-btn-snd').click();
                            $("#example1").DataTable().ajax.url("automation/api?status=all").load();
                        });
                    }
                }).fail(function (jqXHR, status, errorThrown) {
                    alert(errorThrown);
                });


            } else {
                swal("Phew that was close!", "Nothing has changed!", "error");
            }
        });
}

function addNewAutomation() {
    tempId = "";
    $('#temptitle').html("New Automation");
    $('.savebut').show();
    $('.updatebut').hide();
    $('#automationname').val('');
    $('#automationdescription').val('');

}

function getFormData($form) {
    let unindexed_array = $form.serializeArray();
    let indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        indexed_array[n['name']] = n['value'];
    });
    return indexed_array;
}

function addAutomation() {
    let $form = $('#automationForm');

    if ($('#automationname').val() !== "" && $('#automationdescription').val() !== "") {
        $.ajax({
            type: "POST",
            url: "/automation/api",
            data: getFormData($form),
            async: false,
            success: function (data) {
                if (data.status === "success") {
                    toastr.success('add event success', 'Success', {timeOut: 3000});
                    $('#status_show').val('all');
                    $('.profile-btn-snd').click();
                    $("#example1").DataTable().ajax.url("automation/api?status=all").load();
                } else {
                    toastr.error('add event failed', 'error', {timeOut: 3000});
                }

            },
            error: function () {
                toastr.error('add event failed', 'error', {timeOut: 3000})
            }
        });
    }

}

function updateAutomation() {
    let $form = $('#automationForm');

    if ($form.validate().form()) {
        $.ajax({
            type: "PUT",
            url: "/automation/api/" + tempId,
            data: getFormData($form),
            success: function (data) {
                if (data.status === "success") {
                    toastr.success('update event success', 'Success', {timeOut: 3000});
                    $('#status_show').val('all');
                    $('.profile-btn-snd').click();
                    $("#example1").DataTable().ajax.url("automation/api?status=all").load();
                } else {
                    toastr.error('update event failed', 'error', {timeOut: 3000});
                }

            },
            error: function () {
                toastr.error('update event failed', 'error', {timeOut: 3000})
            }
        });
    }
}

function editAutomation(id) {
    tempId = id;
    $('.savebut').hide();
    $('.updatebut').show();
    $('#temptitle').html("Edit Automation");

    $.ajax({
        url: "/automation/api",
        data: {
            id: id
        },
        method: 'GET',
    }).done(function (data) {
        if (data.status === "success") {
            let automation_data = data.data[0];
            $('#automationname').val(automation_data["automationname"]);
            $('#automationdescription').val(automation_data["automationdescription"]);

        } else {
            toastr.error('add event failed', 'error', {timeOut: 3000});
        }
    }).fail(function (jqXHR, status, errorThrown) {
        alert(status + "<br>" + errorThrown);
    });
}
