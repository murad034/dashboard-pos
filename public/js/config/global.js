$(document).ready(function () {

    $("#example1").DataTable(
        {
            // stateSave: true,
            "columns": [
                {"data": "global_id"},
                {"data": "logo_background"},
                {"data": "logo_icon"},
                {"data": "load_gif"},
                {"data": "global_id"},
            ],
            "ajax": {
                "url": "/global/api",
                "method": "GET",
                "data": function (d) {
                }
            },
            "columnDefs": [{
                "targets": 4,
                "width": "10%",
                "data": null,
                "render": function (data, type, full, meta) {
                    if (type === 'display') {
                        data = '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editGlobal(' + full["promosid"] + ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a>';
                    }
                    return data;
                }
            },
            ]
        }
    );

});


function getFormData($form) {
    let unindexed_array = $form.serializeArray();
    let indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        indexed_array[n['name']] = n['value'];
    });
    return indexed_array;
}


function updateGlobalSetting() {
    let $form = $('#promosForm');

    if ($form.validate().form()) {
        $.ajax({
            type: "POST",
            url: "/global/api",
            data: getFormData($form),
            success: function (data) {
                if (data.status === "success") {
                    toastr.success('update event success', 'Success', {timeOut: 3000});
                    $('.profile-btn-snd').click();
                    $("#example1").DataTable().ajax.url("global/api").load();
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

function editGlobal(id) {
    tempId = id;
    $('#temptitle').html("Edit Promos");

    $.ajax({
        url: "/global/api",
        method: 'GET',
    }).done(function (data) {
        if (data.status === "success") {
            let Promos_data = data.data[0];
            $('#promosname').val(Promos_data["promosname"]);
            $('#promosdescription').val(Promos_data["promosdescription"]);

        } else {
            toastr.error('add event failed', 'error', {timeOut: 3000});
        }
    }).fail(function (jqXHR, status, errorThrown) {
        alert(status + "<br>" + errorThrown);
    });
}
