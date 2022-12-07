mobiscroll.setOptions({
    locale: mobiscroll.localeEn, // Specify language like: locale: mobiscroll.localePl or omit setting to use default
    theme: "ios", // Specify theme like: theme: 'ios' or omit setting to use default
    themeVariant: "light", // More info about themeVariant: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-themeVariant
});

$(function () {
    // headers set
    let days = [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
    ];

    let tableHeaders = "<th></th>";
    let tempDate = new Date(startDate.replace(/-/g, "/"));
    for (let next = 0; next < 7; next++) {
        let temp = tempDate.toLocaleDateString("en-GB");
        let dayName = days[tempDate.getDay()];
        tableHeaders += "<th>" + temp + " " + dayName + "</th>";
        tempDate.setDate(tempDate.getDate() + 1);
    }
    tableHeaders += "<th>Total</th>";
    $("#tableDiv").empty();
    $("#tableDiv").append(
        '<table id="displayTable" class="display"><thead><tr>' +
            tableHeaders +
            "</tr></thead></table>"
    );

    $("#displayTable").dataTable({
        searching: false,
        paging: false,
        info: false,
        order: [],
        ajax: {
            url: "/rosters/table?id=" + rosterId + "&startdate=" + startDate,
            type: "GET",
            data: function (d) {
                return JSON.stringify(d);
            },
        },
        rowCallback: function (row, data, index) {
            for (let [index, val] of data.entries()) {
                if (val.includes("-") === true) {
                    $(row)
                        .find("td:eq(" + index + ")")
                        .css("color", "red");
                }
            }
        },
    });
    let calendar;
    let popup;
    let range;
    let oldShift;
    let tempShift;
    let deleteShift;
    let restoreShift;
    let formatDate = mobiscroll.util.datetime.formatDate;
    let $notes = $("#employee-shifts-notes");
    let $deleteButton = $("#employee-shifts-delete");

    let slots = [
        {
            id: 1,
            name: "Work",
        },
        {
            id: 2,
            name: "Rest Break",
        },

        {
            id: 3,
            name: "Meal Break",
        },
    ];

    // let invalid = [];

    function genRandName() {
        let desiredMaxLength = 19;
        let randomName = "mbsc_";
        for (let i = 0; i < desiredMaxLength; i++) {
            randomName += Math.floor(Math.random() * 10);
        }
        return randomName;
    }

    function createAddPopup(args) {
        // hide delete button inside add popup
        $deleteButton.hide();
        deleteShift = true;
        restoreShift = false;
        let slot = slots.find(function (s) {
            return s.id.toString() === tempShift.slot.toString();
        });

        // set popup header text and buttons for adding
        popup.setOptions({
            headerText:
                '<div class="employee-shifts-day">' + // More info about headerText: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-headerText
                formatDate("DDDD", new Date(tempShift.start)) +
                " " +
                slot.name +
                "," +
                formatDate("DD MMMM YYYY", new Date(tempShift.start)) +
                "</div>",
            buttons: [
                // More info about buttons: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-buttons
                "cancel",
                {
                    text: "Add",
                    keyCode: "enter",
                    handler: function () {
                        tempShift["id"] = genRandName();
                        calendar.updateEvent(tempShift);
                        // ajax call put temp shift data to roster data table
                        tempShift["end"] = tempShift["end"]
                            .toLocaleString("en-CA", {
                                year: "numeric",
                                month: "2-digit",
                                day: "2-digit",
                                hour: "2-digit",
                                hour12: false,
                                minute: "2-digit",
                            })
                            .replace(", ", "T");
                        tempShift["start"] = tempShift["start"]
                            .toLocaleString("en-CA", {
                                year: "numeric",
                                month: "2-digit",
                                day: "2-digit",
                                hour: "2-digit",
                                hour12: false,
                                minute: "2-digit",
                            })
                            .replace(", ", "T");
                        tempShift["locationid"] = locationId;
                        tempShift["rosterid"] = rosterId;
                        $.ajax({
                            type: "POST",
                            url: "/rosters/builder-api",
                            data: tempShift,
                            success: function (data) {
                                if (data.status === "success") {
                                    toastr.success(
                                        "add event success",
                                        "Success",
                                        { timeOut: 3000 }
                                    );
                                    calendar.updateEvent(data.data);
                                    $("#displayTable")
                                        .DataTable()
                                        .ajax.url(
                                            "table?id=" +
                                                rosterId +
                                                "&startdate=" +
                                                startDate
                                        )
                                        .load();
                                    $.ajax({
                                        url: "/rosters/get-resource",
                                        data: {
                                            locationid: locationId,
                                            startdate: startDate,
                                            rosterid: rosterId,
                                        },
                                        method: "GET",
                                    })
                                        .done(function (data) {
                                            if (data.status === "success") {
                                                calendar.setOptions({
                                                    resources: data.data,
                                                });
                                            }
                                        })
                                        .fail(function (
                                            jqXHR,
                                            status,
                                            errorThrown
                                        ) {
                                            alert(
                                                status + "<br>" + errorThrown
                                            );
                                        });
                                } else {
                                    toastr.error("add event failed", "error", {
                                        timeOut: 3000,
                                    });
                                }
                            },
                            error: function () {
                                toastr.error("add event failed", "error", {
                                    timeOut: 3000,
                                });
                            },
                        });
                        deleteShift = false;
                        popup.close();
                    },
                    cssClass: "mbsc-popup-button-primary",
                },
            ],
        });
        // fill popup with a new event data
        range.setOptions({
            // minTime: tempShift.slot === 1 ? '07:00' : tempShift.slot === 2 ? '12:00' : '07:00',
            // maxTime: tempShift.slot === 1 ? '13:00' : tempShift.slot === 2 ? '18:00' : '18:00'
            minTime: "07:00",
            maxTime: "18:00",
        });
        range.setVal([tempShift.start, tempShift.end]);

        popup.open();
    }

    function createEditPopup(args) {
        let ev = args.event;
        let resource = staffList.find(function (r) {
            return r.id === ev.resource;
        });
        let slot = slots.find(function (s) {
            return s.id.toString() === ev.slot.toString();
        });
        let headerText =
            "<div>Edit " +
            resource.name +
            '\'s hours</div><div class="employee-shifts-day">' +
            formatDate("DDDD", new Date(ev.start)) +
            " " +
            slot.name +
            "," +
            formatDate("DD MMMM YYYY", new Date(ev.start)) +
            "</div>";

        // show delete button inside edit popup
        $deleteButton.show();

        deleteShift = false;
        restoreShift = true;

        // // set popup header text and buttons for editing
        popup.setOptions({
            headerText: headerText, // More info about headerText: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-headerText
            buttons: [
                // More info about buttons: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-buttons
                "cancel",
                {
                    text: "Save",
                    keyCode: "enter",
                    handler: function () {
                        let date = range.getVal();
                        tempShift = {
                            id: ev.id,
                            title:
                                formatDate("HH:mm", date[0]) +
                                " - " +
                                formatDate(
                                    "HH:mm",
                                    date[1] ? date[1] : date[0]
                                ),
                            notes: $notes.val(),
                            start: date[0]
                                .toLocaleString("en-CA", {
                                    year: "numeric",
                                    month: "2-digit",
                                    day: "2-digit",
                                    hour: "2-digit",
                                    hour12: false,
                                    minute: "2-digit",
                                })
                                .replace(", ", "T"),
                            end: date[1]
                                ? date[1]
                                      .toLocaleString("en-CA", {
                                          year: "numeric",
                                          month: "2-digit",
                                          day: "2-digit",
                                          hour: "2-digit",
                                          hour12: false,
                                          minute: "2-digit",
                                      })
                                      .replace(", ", "T")
                                : date[0]
                                      .toLocaleString("en-CA", {
                                          year: "numeric",
                                          month: "2-digit",
                                          day: "2-digit",
                                          hour: "2-digit",
                                          hour12: false,
                                          minute: "2-digit",
                                      })
                                      .replace(", ", "T"),
                            resource: resource.id.toString(),
                            color: resource.color,
                            slot: slot.id,
                            locationid: locationId,
                            rosterid: rosterId,
                        };
                        let key_id;
                        if (ev["_id"] instanceof Object) {
                            key_id = ev["_id"]["$oid"];
                        } else {
                            key_id = ev["_id"];
                        }
                        $.ajax({
                            type: "PUT",
                            url: "/rosters/builder-api/" + key_id,
                            data: tempShift,
                            success: function (data) {
                                if (data.status === "success") {
                                    toastr.success(
                                        "update event success",
                                        "Success",
                                        { timeOut: 3000 }
                                    );
                                    // update event with the new properties on save button click
                                    // calendar.updateEvent(tempShift);
                                    calendar.updateEvent(data.data);
                                    $("#displayTable")
                                        .DataTable()
                                        .ajax.url(
                                            "table?id=" +
                                                rosterId +
                                                "&startdate=" +
                                                startDate
                                        )
                                        .load();
                                    $.ajax({
                                        url: "/rosters/get-resource",
                                        data: {
                                            locationid: locationId,
                                            startdate: startDate,
                                            rosterid: rosterId,
                                        },
                                        method: "GET",
                                    })
                                        .done(function (data) {
                                            if (data.status === "success") {
                                                calendar.setOptions({
                                                    resources: data.data,
                                                });
                                            }
                                        })
                                        .fail(function (
                                            jqXHR,
                                            status,
                                            errorThrown
                                        ) {
                                            alert(
                                                status + "<br>" + errorThrown
                                            );
                                        });
                                } else {
                                    toastr.error(
                                        "update event failed",
                                        "error",
                                        { timeOut: 3000 }
                                    );
                                }
                            },
                            error: function () {
                                toastr.error("update event failed", "error", {
                                    timeOut: 3000,
                                });
                            },
                        });

                        restoreShift = false;
                        popup.close();
                    },
                    cssClass: "mbsc-popup-button-primary",
                },
            ],
        });

        // fill popup with the selected event data
        $notes.mobiscroll("getInst").value = ev.notes || "";
        range.setOptions({
            // minTime: ev.slot === 1 ? '07:00' : ev.slot === 2 ? '12:00' : '07:00',
            // maxTime: ev.slot === 1 ? '13:00' : ev.slot === 2 ? '18:00' : '18:00'
            minTime: "07:00",
            maxTime: "18:00",
        });
        range.setVal([ev.start, ev.end]);

        popup.open();
    }

    calendar = $("#demo-employee-shifts-calendar")
        .mobiscroll()
        .eventcalendar({
            view: {
                // More info about view: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-view
                timeline: {
                    type: "day",
                    size: 7,
                    eventList: true,
                    startDay: 0,
                },
            },
            selectedDate: new Date(startDate.replace(/-/g, "/")),
            refDate: new Date(startDate.replace(/-/g, "/")),
            // height: 444,                                                               // More info about height: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-height
            data: shiftData, // More info about data: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-data
            dragToCreate: false, // More info about dragToCreate: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-dragToCreate
            dragToResize: false, // More info about dragToResize: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-dragToResize
            dragToMove: true, // More info about dragToMove: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-dragToMove
            clickToCreate: 'single', // More info about clickToCreate: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-clickToCreate
            resources: staffList, // More info about resources: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-resources
            invalid: invalid, // More info about invalid: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-invalid
            slots: slots, // More info about slots: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-slots
            renderHeader: function () {
                return '<div mbsc-calendar-nav class="md-custom-header-nav"></div>';
            },
            extendDefaultEvent: function (ev) {
                // More info about extendDefaultEvent: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-extendDefaultEvent
                let d = ev.start;
                // let start = new Date(d.getFullYear(), d.getMonth(), d.getDate(), ev.slot === 1 ? 7 : ev.slot === 2 ? 12 : 7);
                // let end = new Date(d.getFullYear(), d.getMonth(), d.getDate(), ev.slot === 1 ? 13 : ev.slot === 2 ? 18 : 18);
                let start = new Date(
                    d.getFullYear(),
                    d.getMonth(),
                    d.getDate(),
                    7
                );
                let end = new Date(
                    d.getFullYear(),
                    d.getMonth(),
                    d.getDate(),
                    18
                );

                return {
                    title:
                        formatDate("HH:mm", start) +
                        " - " +
                        formatDate("HH:mm", end),
                    start: start,
                    end: end,
                    resource: ev.resource,
                };
            },
            onEventCreate: function (args, inst) {
                // More info about onEventCreate: https://docs.mobiscroll.com/5-15-2/eventcalendar#event-onEventCreate
                // store temporary event
                tempShift = args.event;
                setTimeout(function () {
                    createAddPopup(args);
                }, 100);
            },
            onEventClick: function (args, inst) {
                // More info about onEventClick: https://docs.mobiscroll.com/5-15-2/eventcalendar#event-onEventClick
                oldShift = $.extend({}, args.event);
                tempShift = args.event;

                if (!popup.isVisible()) {
                    createEditPopup(args);
                }
            },
            onEventUpdate: function (args, inst) {
                tempShift = Object.assign({}, args.event);
                let shift_id;
                if (tempShift["_id"] instanceof Object) {
                    shift_id = tempShift["_id"]["$oid"];
                } else {
                    shift_id = tempShift["_id"];
                }
                delete tempShift._id;
                tempShift["end"] = tempShift["end"]
                    .toLocaleString("en-CA", {
                        year: "numeric",
                        month: "2-digit",
                        day: "2-digit",
                        hour: "2-digit",
                        hour12: false,
                        minute: "2-digit",
                    })
                    .replace(", ", "T");
                tempShift["start"] = tempShift["start"]
                    .toLocaleString("en-CA", {
                        year: "numeric",
                        month: "2-digit",
                        day: "2-digit",
                        hour: "2-digit",
                        hour12: false,
                        minute: "2-digit",
                    })
                    .replace(", ", "T");
                tempShift["locationid"] = locationId;
                tempShift["rosterid"] = rosterId;
                $.ajax({
                    type: "PUT",
                    url: "/rosters/builder-api/" + shift_id,
                    data: tempShift,
                    success: function (data) {
                        if (data.status === "success") {
                            toastr.success("update event success", "Success", {
                                timeOut: 3000,
                            });
                            calendar.updateEvent(args.event);
                            $("#displayTable")
                                .DataTable()
                                .ajax.url(
                                    "table?id=" +
                                        rosterId +
                                        "&startdate=" +
                                        startDate
                                )
                                .load();
                        } else {
                            toastr.error("update event failed", "error", {
                                timeOut: 3000,
                            });
                        }
                    },
                    error: function () {
                        toastr.error("update event failed", "error", {
                            timeOut: 3000,
                        });
                    },
                });
            },
            renderResource: function (resource) {
                // More info about renderResource: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-renderResource
                return (
                    '<div class="employee-shifts-cont" data-num="' +
                    resource.id +
                    '">' +
                    '<div data-num="' +
                    resource.id +
                    '" class="employee-shifts-name employee-profile">' +
                    resource.name +
                    "</div>" +
                    '<div id="custom_availabilty" data-num="' +
                    resource.id +
                    '" class="employee-shifts-info-icon custom-availabilty">' +
                    "<i class='fa fa-circle-info fa-lg'></i>" +
                    "</div>" +
                    '<div class="employee-shifts-title">' +
                    resource.title +
                    " " +
                    parseFloat(resource.totalhours).toFixed(2) +
                    "hrs" +
                    "</div>" +
                    '<img class="employee-shifts-avatar" src="' +
                    resource.img +
                    '"/>' +
                    "</div>"
                );
                // return '<div class="employee-shifts-cont" data-num="'+resource.id+'">' +
                //     '<div class="employee-shifts-name">' + resource.name + '</div>' +
                //     '<div class="employee-shifts-title">' + resource.title + '</div>' +
                //     '<img class="employee-shifts-avatar" src="' + resource.img + '"/>' +
                //     '</div>';
            },
            renderScheduleEvent: function (data) {
                var ev = data.original;
                var color = data.color;

                return `
                    <div class="mbsc-schedule-event-background mbsc-timeline-event-background mbsc-schedule-event-all-day-background mbsc-ios" style="height:20px;"></div>
                    <div class="mbsc-schedule-event-inner mbsc-ios mbsc-schedule-event-all-day-inner">
                        <div class="mbsc-schedule-event-title mbsc-schedule-event-all-day-title mbsc-ios">${ev.title}</div>
                    </div>`;
            },
        })
        .mobiscroll("getInst");

    // $('.employee-shifts-cont').on('mouseover',function(){
    //     let id = $(this).attr("data-num");
    //     console.log(id);
    // });

    // prevent click event on header date
    $("#mbsc-control-1").css("pointer-events", "none");

    popup = $("#demo-employee-shifts-popup")
        .mobiscroll()
        .popup({
            display: "bottom", // Specify display mode like: display: 'bottom' or omit setting to use default
            contentPadding: false,
            fullScreen: true,
            onClose: function () {
                // More info about onClose: https://docs.mobiscroll.com/5-15-2/eventcalendar#event-onClose
                if (deleteShift) {
                    calendar.removeEvent(tempShift);
                } else if (restoreShift) {
                    calendar.updateEvent(oldShift);
                }
            },
            responsive: {
                // More info about responsive: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-responsive
                medium: {
                    display: "center", // Specify display mode like: display: 'bottom' or omit setting to use default
                    width: 400, // More info about width: https://docs.mobiscroll.com/5-15-2/eventcalendar#opt-width
                    fullScreen: false,
                    touchUi: false,
                    showOverlay: false,
                },
            },
        })
        .mobiscroll("getInst");

    range = $("#demo-employee-shifts-date")
        .mobiscroll()
        .datepicker({
            controls: ["time"],
            select: "range",
            display: "anchored", // Specify display mode like: display: 'bottom' or omit setting to use default
            showRangeLabels: false,
            touchUi: false,
            startInput: "#employee-shifts-start",
            endInput: "#employee-shifts-end",
            stepMinute: 5,
            timeWheels: "|h:mm A|",
            onChange: function (args) {
                let date = args.value;

                // update shift's start/end date
                tempShift.start = date[0];
                tempShift.end = date[1] ? date[1] : date[0];
                tempShift.title =
                    formatDate("HH:mm", date[0]) +
                    " - " +
                    formatDate("HH:mm", date[1] ? date[1] : date[0]);
            },
        })
        .mobiscroll("getInst");

    $notes.on("change", function (ev) {
        // update current event's title
        tempShift.notes = ev.target.value;
    });

    $deleteButton.on("click", function () {
        let deletedShift = tempShift;
        let key_id;
        if (tempShift["_id"] instanceof Object) {
            key_id = tempShift["_id"]["$oid"];
        } else {
            key_id = tempShift["_id"];
        }
        $.ajax({
            type: "DELETE",
            url: "/rosters/builder-api/" + key_id,
            success: function (data) {
                if (data.status === "success") {
                    toastr.success("delete event success", "Success", {
                        timeOut: 3000,
                    });
                    // delete current event on button click
                    calendar.removeEvent(tempShift);
                    $("#displayTable")
                        .DataTable()
                        .ajax.url(
                            "table?id=" + rosterId + "&startdate=" + startDate
                        )
                        .load();
                    $.ajax({
                        url: "/rosters/get-resource",
                        data: {
                            locationid: locationId,
                            startdate: startDate,
                            rosterid: rosterId,
                        },
                        method: "GET",
                    })
                        .done(function (data) {
                            if (data.status === "success") {
                                calendar.setOptions({
                                    resources: data.data,
                                });
                            }
                        })
                        .fail(function (jqXHR, status, errorThrown) {
                            alert(status + "<br>" + errorThrown);
                        });
                } else {
                    toastr.error("delete event failed", "error", {
                        timeOut: 3000,
                    });
                }
            },
            error: function () {
                toastr.error("delete event failed", "error", { timeOut: 3000 });
            },
        });
        popup.close();

        mobiscroll.snackbar({
            button: {
                action: function () {
                    calendar.addEvent(deletedShift);
                },
                text: "Undo",
            },
            duration: 10000,
            message: "deleted",
        });
    });

    $('#custom_availabilty_info').mobiscroll().popup();

    $('.custom-availabilty').click(function(){
        var num = $(this).attr("data-num");
        $.ajax({
            type: "GET",
            url: "/rosters/staff",
            data: {num: num},
            success: function (result) {
                var custom_availabilty_info = "";
                if(result){
                    if(result.data.custommonday){
                        custom_availabilty_info += `<tr>
                        <td>Available Monday </td>
                        <td>:&nbsp;${result.data.custommonday}</td>
                    </tr>`;
                    }
                    if(result.data.customtuesday){
                        custom_availabilty_info += `<tr>
                        <td>Available Tuesday </td>
                        <td>:&nbsp;${result.data.customtuesday}</td>
                    </tr>`;
                    }
                    if(result.data.customwednesday){
                        custom_availabilty_info += `<tr>
                        <td>Available Wednesday </td>
                        <td>:&nbsp;${result.data.customwednesday}</td>
                    </tr>`;
                    }
                    if(result.data.customthursday){
                        custom_availabilty_info += `<tr>
                        <td>Available Thursday </td>
                        <td>:&nbsp;${result.data.customthursday}</td>
                    </tr>`;
                    }
                    if(result.data.customfriday){
                        custom_availabilty_info += `<tr>
                        <td>Available Friday </td>
                        <td>:&nbsp;${result.data.customfriday}</td>
                    </tr>`;
                    }
                    if(result.data.customsaturday){
                        custom_availabilty_info += `<tr>
                        <td>Available Saturday </td>
                        <td>:&nbsp;${result.data.customsaturday}</td>
                    </tr>`;
                    }
                    if(result.data.customsunday){
                        custom_availabilty_info += `<tr>
                        <td>Available Sunday </td>
                        <td>:&nbsp;${result.data.customsunday}</td>
                    </tr>`;
                    }

                }
                $("#custom_availabilty_info").html(custom_availabilty_info);
                $('#custom_availabilty_info').mobiscroll('open');
            },
            error: function () {
                toastr.error("Failed!! Try Again Later", "error", {
                    timeOut: 3000,
                });
            },
        });
        return false;
    });



    $('.employee-profile').click(function(){
        var num = $(this).attr("data-num");
        $.ajax({
            type: "GET",
            url: "/rosters/staff",
            data: {num: num},
            success: function (result) {
                var custom_availabilty_info = "";
                if(result){
                    if(result.data.staffname){
                        custom_availabilty_info += `<tr>
                        <td>Staff Name </td>
                        <td>:&nbsp;${result.data.staffname}</td>
                    </tr>`;
                    }
                    if(result.data.phonenumber){
                        custom_availabilty_info += `<tr>
                        <td>Phone Number </td>
                        <td>:&nbsp;${result.data.phonenumber}</td>
                    </tr>`;
                    }
                    if(result.data.staffpin){
                        custom_availabilty_info += `<tr>
                        <td>Pin </td>
                        <td>:&nbsp;${result.data.staffpin}</td>
                    </tr>`;
                    }
                    if(result.data.department){
                        custom_availabilty_info += `<tr>
                        <td>Department </td>
                        <td>:&nbsp;${result.data.department}</td>
                    </tr>`;
                    }
                    if(result.data.payrateperhour){
                        custom_availabilty_info += `<tr>
                        <td>Pay Rate Per Hour </td>
                        <td>:&nbsp;${result.data.payrateperhour}</td>
                    </tr>`;
                    }
                    if(result.data.taxfilenumber){
                        custom_availabilty_info += `<tr>
                        <td>Tax File Number</td>
                        <td>:&nbsp;${result.data.taxfilenumber}</td>
                    </tr>`;
                    }
                    if(result.data.staffnotes){
                        custom_availabilty_info += `<tr>
                        <td>Notes</td>
                        <td>:&nbsp;${result.data.staffnotes}</td>
                    </tr>`;
                    }
                    if(result.data.nextofkinname){
                        custom_availabilty_info += `<tr>
                        <td>Next of Kin Name</td>
                        <td>:&nbsp;${result.data.nextofkinname}</td>
                    </tr>`;
                    }
                    if(result.data.nextofkinphone){
                        custom_availabilty_info += `<tr>
                        <td>Next of Kin Phone</td>
                        <td>:&nbsp;${result.data.nextofkinphone}</td>
                    </tr>`;
                    }
                    if(result.data.address){
                        custom_availabilty_info += `<tr>
                        <td>Address </td>
                        <td>:&nbsp;${result.data.address}</td>
                    </tr>`;
                    }
                    if(result.data.street_number){
                        custom_availabilty_info += `<tr>
                        <td>Street Number</td>
                        <td>:&nbsp;${result.data.street_number}</td>
                    </tr>`;
                    }
                    if(result.data.route){
                        custom_availabilty_info += `<tr>
                        <td>Street Name</td>
                        <td>:&nbsp;${result.data.route}</td>
                    </tr>`;
                    }
                    if(result.data.locality){
                        custom_availabilty_info += `<tr>
                        <td>Suburb / City</td>
                        <td>:&nbsp;${result.data.locality}</td>
                    </tr>`;
                    }
                    if(result.data.administrative_area_level_1){
                        custom_availabilty_info += `<tr>
                        <td>State</td>
                        <td>:&nbsp;${result.data.administrative_area_level_1}</td>
                    </tr>`;
                    }
                    if(result.data.postal_code){
                        custom_availabilty_info += `<tr>
                        <td>Post Code</td>
                        <td>:&nbsp;${result.data.postal_code}</td>
                    </tr>`;
                    }
                    if(result.data.country){
                        custom_availabilty_info += `<tr>
                        <td>Country</td>
                        <td>:&nbsp;${result.data.country}</td>
                    </tr>`;
                    }
                    if(result.data.cityLat){
                        custom_availabilty_info += `<tr>
                        <td>LAT</td>
                        <td>:&nbsp;${result.data.cityLat}</td>
                    </tr>`;
                    }
                    if(result.data.cityLng){
                        custom_availabilty_info += `<tr>
                        <td>LNG</td>
                        <td>:&nbsp;${result.data.cityLng}</td>
                    </tr>`;
                    }
                }
                $("#custom_availabilty_info").html(custom_availabilty_info);
                $('#custom_availabilty_info').mobiscroll('open');
            },
            error: function () {
                toastr.error("Failed!! Try Again Later", "error", {
                    timeOut: 3000,
                });
            },
        });
        return false;
    });


});
