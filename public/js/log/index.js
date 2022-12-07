$(document).ready(function () {
    $("#example1").DataTable({
        // pageLength: 100,
        columns: [
            { data: "user_id" },
            { data: "user_name" },
            { data: "action" },
            { data: "table" },
            { data: "created_at" },
        ],
        autoWidth: false,
        ajax: {
            url: "/logs/api",
            method: "GET",
            data: function (d) {},
        },
        columnDefs: [
            { width: "10%", targets: 0 },
            { width: "10%", targets: 1 },
            { width: "55%", targets: 2 },
            { width: "10%", targets: 3 },
            { width: "15%", targets: 4 },
        ],
    });
});

setInterval(function () {
    $("#example1").DataTable().ajax.url("logs/api").load();
}, 1000 * 60);
