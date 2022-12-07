$(function () {
    $("#example1").DataTable({
        // stateSave: true,
        "columns": [
            {"data": "keypadname"},
            {"data": "keypadid"},
        ],
        "ajax": {
            "url": "/order-keypad-designer/api",
            "method": "GET",
        },
        "columnDefs": [
            {
                "targets": 1,
                "data": null,
                "render": function (data, type, full, meta) {
                    if (type === 'display') {
                        data = '                                <a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editKeyPad(`' + full["keypadid"] + '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" >\n' +
                            '                                    <i class="fa fa-info"></i>\n' +
                            '                                </a>\n' +
                            '                                <a data-simpletooltip-text="Open Builder"  class="js-simple-tooltip profile-btn" href="order-keypad-designer/builder?id=' + full["keypadid"] + '"   style="background: purple; padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;"  >\n' +
                            '                                    <i class="fa fa-keyboard"></i>\n' +
                            '                                </a>'

                    }
                    return data;
                }
            }]
    });
    $("#example1_wrapper").find('.dataTables_filter').prepend('<a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewKeyPad()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"><i class="fa fa-plus"></i></a>');
});

function addNewKeyPad() {
    tempId = "";
    $('#temptitle').html("New KeyPad");
    $('.savebut').show();
    $('.updatebut').hide();
    $('#keypadname').val('');

}

function getFormData($form) {
    let unindexed_array = $form.serializeArray();
    let indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        indexed_array[n['name']] = n['value'];
    });
    return indexed_array;
}

function addKeyPad() {
    let $form = $('#keypadForm');

    if ($('#keypadname').val() !== "") {
        $.ajax({
            type: "POST",
            url: "/order-keypad-designer/api",
            data: {
                _token: csrfToken,
                data: getFormData($form)
            },
            success: function (data) {
                if (data.status === "success") {
                    toastr.success('add event success', 'Success', {timeOut: 3000});
                    $('.profile-btn-snd').click();
                    $("#example1").DataTable().ajax.url("order-keypad-designer/api").load();
                } else {
                    toastr.error('add event failed', 'error', {timeOut: 3000});
                    $('.profile-btn-snd').click();
                    $("#example1").DataTable().ajax.url("order-keypad-designer/api").load();
                }

            },
            error: function () {
                toastr.error('add event failed', 'error', {timeOut: 3000})
            }
        });
    }

}

function updateKeyPad() {
    let $form = $('#keypadForm');

    if ($form.validate().form()) {
        $.ajax({
            type: "PUT",
            url: "/order-keypad-designer/api/" + tempId,
            data: getFormData($form),
            success: function (data) {
                if (data.status === "success") {
                    toastr.success('update event success', 'Success', {timeOut: 3000});
                    $('.profile-btn-snd').click();
                    $("#example1").DataTable().ajax.url("order-keypad-designer/api").load();
                } else {
                    toastr.error('update event failed', 'error', {timeOut: 3000});
                    $('.profile-btn-snd').click();
                    $("#example1").DataTable().ajax.url("order-keypad-designer/api").load();
                }

            },
            error: function () {
                toastr.error('update event failed', 'error', {timeOut: 3000})
            }
        });
    }
}

function editKeyPad(id) {
    tempId = id;
    $('.savebut').hide();
    $('.updatebut').show();
    $('#temptitle').html("Edit KeyPad");

    $.ajax({
        url: "/order-keypad-designer/api/" + id,
        method: 'GET',
    }).done(function (data) {
        if (data.status === "success") {
            let keypad_data = data.data[0];
            $('#keypadname').val(keypad_data["keypadname"]);

        } else {
            toastr.error('add event failed', 'error', {timeOut: 3000});
        }
    }).fail(function (jqXHR, status, errorThrown) {
        alert(status + "<br>" + errorThrown);
    });
}
