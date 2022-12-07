$(document).ready(function () {

    $("#example1").DataTable(
        {
            // stateSave: true,
            "columns": [
                {"data": "tagid"},
                {"data": "tagname"},
                {"data": "tagtrigger"},
                {"data": "status"},
            ],
            "ajax": {
                "url": "/tags/api",
                "method": "GET",
                "data": function (d) {
                }
            },
            "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                nRow.setAttribute('id', aData["tagid"]);
            },
            "columnDefs": [{
                "targets": 3,
                "width": "10%",
                "data": null,
                "render": function (data, type, full, meta) {
                    if (type === 'display') {
                        data = '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editTag(' + full["tagid"] + ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a> <a data-simpletooltip-text="De-activate Item"  class="js-simple-tooltip profile-btn-del" onclick="JSalert(' + full["tagid"] + ',`' + full["tagname"] + '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" ><i class="fa fa-times"></i></a>';
                    }
                    return data;
                }
            },
            ]
        }
    );
    $("#example1_wrapper").find('.dataTables_filter').prepend(
        '<a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewTag()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"><i class="fa fa-plus"></i></a> ');

});

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
                    url: "/tags/api/" + id,
                    method: 'DELETE',
                }).done(function (data) {
                    if (data.status === "success") {
                        swal({
                            title: protitle + "  deactivated",
                            text: "If this was a mistake you can re-activate the item in the inactive tag screen!",
                            type: "success",
                        }, function (isConfirm) {
                            $("#example1").DataTable().ajax.url("tags/api").load();
                        });
                    } else {
                        swal({
                            title: protitle + " can't be deactivated",
                            text: "This stock item is currently active on a keypad, please remove it from all keypads first then deactivate again!",
                            type: "warning",
                        }, function (isConfirm) {
                            $("#example1").DataTable().ajax.url("tags/api").load();
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

function addNewTag() {
    tempId = "";
    $('#temptitle').html("New Tag");
    $('.savebut').show();
    $('.updatebut').hide();
    $('#tagname').val('');
    $('#tagtrigger').val('');

}

function getFormData($form) {
    let unindexed_array = $form.serializeArray();
    let indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        indexed_array[n['name']] = n['value'];
    });
    return indexed_array;
}

function addTag() {
    let $form = $('#tagsForm');

    if ($('#tagname').val() !== "" && $('#tagtrigger').val() !== "") {
        $.ajax({
            type: "POST",
            url: "/tags/api",
            data: {
                _token: csrfToken,
                data: getFormData($form)
            },
            success: function (data) {
                if (data.status === "success") {
                    toastr.success('add event success', 'Success', {timeOut: 3000});
                    $('.profile-btn-snd').click();
                    $("#example1").DataTable().ajax.url("tags/api").load();
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

function updateTag() {
    let $form = $('#tagsForm');

    if ($form.validate().form()) {
        $.ajax({
            type: "PUT",
            url: "/tags/api/" + tempId,
            data: getFormData($form),
            success: function (data) {
                if (data.status === "success") {
                    toastr.success('update event success', 'Success', {timeOut: 3000});
                    $('.profile-btn-snd').click();
                    $("#example1").DataTable().ajax.url("tags/api").load();
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

function editTag(id) {
    tempId = id;
    $('.savebut').hide();
    $('.updatebut').show();
    $('#temptitle').html("Edit Tag");

    $.ajax({
        url: "/tags/api",
        data: {
            id: id
        },
        method: 'GET',
    }).done(function (data) {
        if (data.status === "success") {
            let tagData = data.data[0];
            $('#tagname').val(tagData["tagname"]);
            $('#tagtrigger').val(tagData["tagtrigger"]);

        } else {
            toastr.error('add event failed', 'error', {timeOut: 3000});
        }
    }).fail(function (jqXHR, status, errorThrown) {
        alert(status + "<br>" + errorThrown);
    });
}
