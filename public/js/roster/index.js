function convertDate(dateString) {
    let today = new Date(dateString.replace(/-/g, "/"));
    const yyyy = today.getFullYear();
    let mm = today.getMonth() + 1; // Months start at 0!
    let dd = today.getDate();

    if (dd < 10) dd = "0" + dd;
    if (mm < 10) mm = "0" + mm;

    today = dd + "/" + mm + "/" + yyyy;
    return today;
}

$(function () {
    $("#example1").DataTable({
        // stateSave: true,
        columns: [
            { data: "locations.locationname" },
            { data: "startdate" },
            { data: "status" },
            { data: "status" },
        ],
        ajax: {
            url: "/rosters/api",
            method: "GET",
            data: function (d) {},
        },
        fnRowCallback: function (nRow, aData, iDisplayIndex) {
            nRow.setAttribute("id", aData["automationid"]);
        },
        columnDefs: [
            {
                targets: 1,
                data: null,
                render: function (data, type, full, meta) {
                    data = convertDate(full["startdate"]);
                    return data;
                },
            },
            {
                targets: 3,
                width: "10%",
                data: null,
                render: function (data, type, full, meta) {
                    if (type === "display") {
                        data =
                            '                      <a data-simpletooltip-text="Edit Item" class="js-simple-tooltip profile-btn"\n' +
                            '                         data-bs-toggle="offcanvas" href="#offcanvasExample"\n' +
                            '                         aria-controls="offcanvasExample" onclick="editRoster(' +
                            full["rosterid"] +
                            ')"\n' +
                            '                         style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;"><i\n' +
                            '                                  class="fa fa-pen"></i></a>\n' +
                            '                      <a data-simpletooltip-text="Open Schedular"  class="js-simple-tooltip profile-btn" href="rosters/builder?locationid=' +
                            full["locationid"] +
                            "&startdate=" +
                            full["startdate"] +
                            "&rosterid=" +
                            full["rosterid"] +
                            '"   style="background: purple; font-size: 18px; padding: 6px; padding-left: 15px; padding-right: 15px; margin-top: 0px;"  >\n' +
                            '                          <i class="fa fa-user-clock"></i>\n' +
                            "                      </a>\n" +
                            '                      <a data-simpletooltip-text="Download Report"  class="js-simple-tooltip profile-btn" onclick="downloadPDF(`' +
                            full["locationid"] +
                            "`,`" +
                            full["startdate"] +
                            "`, `" +
                            full["rosterid"] +
                            '`)"  style="background: green; font-size: 18px; padding: 6px; padding-left: 15px; padding-right: 15px; margin-top: 0px; margin-left: 5px;"  >\n' +
                            '                          <i class="fa fa-download"></i>\n' +
                            "                      </a>";
                    }
                    return data;
                },
            },
            {
                targets: 2,
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
    $("#example1_wrapper")
        .find(".dataTables_filter")
        .prepend(
            '<a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewRoster()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"><i class="fa fa-plus"></i></a>'
        );

    $("#locationid").on("change", function () {
        $.ajax({
            url: "/rosters/api",
            data: {
                locationid: this.value,
            },
            method: "GET",
        })
            .done(function (data) {
                if (data.status === "success") {
                    if (data.data.length !== 0) {
                        let roster_data = data.data[0];
                        $("#mondaybudget").val(roster_data["mondaybudget"]);
                        $("#tuesdaybudget").val(roster_data["tuesdaybudget"]);
                        $("#wednesdaybudget").val(
                            roster_data["wednesdaybudget"]
                        );
                        $("#thursdaybudget").val(roster_data["thursdaybudget"]);
                        $("#fridaybudget").val(roster_data["fridaybudget"]);
                        $("#saturdaybudget").val(roster_data["saturdaybudget"]);
                        $("#sundaybudget").val(roster_data["sundaybudget"]);
                    } else {
                        $("#mondaybudget").val("");
                        $("#tuesdaybudget").val("");
                        $("#wednesdaybudget").val("");
                        $("#thursdaybudget").val("");
                        $("#fridaybudget").val("");
                        $("#saturdaybudget").val("");
                        $("#sundaybudget").val("");
                    }
                } else {
                    toastr.error("add event failed", "error", {
                        timeOut: 3000,
                    });
                }
            })
            .fail(function (jqXHR, status, errorThrown) {
                alert(status + "<br>" + errorThrown);
            });
    });
});

function addNewRoster() {
    tempId = "";
    $("#temptitle").html("New Roster");
    $(".savebut").show();
    $(".updatebut").hide();
    $("#locationid").val("no");
    $("#status").val("published");
    $("#mondaybudget").val("");
    $("#tuesdaybudget").val("");
    $("#wednesdaybudget").val("");
    $("#thursdaybudget").val("");
    $("#fridaybudget").val("");
    $("#saturdaybudget").val("");
    $("#sundaybudget").val("");
    $("#startdate").val(new Date().toLocaleDateString("en-CA"));
}

function getFormData($form) {
    let unindexed_array = $form.serializeArray();
    let indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        if (n["name"].includes("budget")) {
            indexed_array[n["name"]] = parseFloat(n["value"]).toFixed(2);
        } else {
            indexed_array[n["name"]] = n["value"];
        }
    });
    return indexed_array;
}

function addRoster() {
    let $form = $("#rosterForm");

    if ($("#locationid").val() !== "no" && $("#status").val() !== "") {
        $.ajax({
            type: "POST",
            url: "/rosters/api",
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
                        .ajax.url("rosters/api?status=all")
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

function updateRoster() {
    let $form = $("#rosterForm");

    if ($form.validate().form()) {
        $.ajax({
            type: "PUT",
            url: "/rosters/api/" + tempId,
            data: getFormData($form),
            async: false,
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("update event success", "Success", {
                        timeOut: 3000,
                    });
                    $("#status_show").val("all");
                    $(".profile-btn-snd").click();
                    $("#example1")
                        .DataTable()
                        .ajax.url("rosters/api?status=all")
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

function editRoster(id) {
    tempId = id;
    $(".savebut").hide();
    $(".updatebut").show();
    $("#temptitle").html("Edit Roster");

    $.ajax({
        url: "/rosters/api",
        data: {
            id: id,
        },
        method: "GET",
    })
        .done(function (data) {
            if (data.status === "success") {
                let roster_data = data.data[0];
                $("#locationid").val(roster_data["locationid"]);
                $("#status").val(roster_data["status"]);
                $("#mondaybudget").val(roster_data["mondaybudget"]);
                $("#tuesdaybudget").val(roster_data["tuesdaybudget"]);
                $("#wednesdaybudget").val(roster_data["wednesdaybudget"]);
                $("#thursdaybudget").val(roster_data["thursdaybudget"]);
                $("#fridaybudget").val(roster_data["fridaybudget"]);
                $("#saturdaybudget").val(roster_data["saturdaybudget"]);
                $("#sundaybudget").val(roster_data["sundaybudget"]);
                $("#startdate").val(
                    new Date(
                        roster_data["startdate"].replace(/-/g, "/")
                    ).toLocaleDateString("en-CA")
                );
            } else {
                toastr.error("add event failed", "error", { timeOut: 3000 });
            }
        })
        .fail(function (jqXHR, status, errorThrown) {
            alert(status + "<br>" + errorThrown);
        });
}

function downloadPDF(locid, datefrom, rosid) {
    jsreport.serverUrl = "https://reports.imreke.com.au:5488";
    jsreport.headers["Authorization"] =
        "Basic " + btoa("ausittechdirect:#Au5T3chGR0up#");

    async function beforeRender(req, res) {
        const report = await jsreport.render({
            template: { name: "/imreke/supplingroster/main" },
            data: {
                database: databaseName,
                dateFrom: datefrom,
                locationid: locid,
                rosterid: rosid,
            },
            options: { reports: { save: true } },
        });

        report.download("Roster" + locid + "-Week-Start-" + datefrom + ".pdf");
    }

    beforeRender();
}
