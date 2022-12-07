$(document).ready(
    function () {
        window.FakeLoader.init({auto_hide: true});
    }
);
$("#brand_change").select2();

$('.js-daterangepicker').daterangepicker({
    "showWeekNumbers": true,
    "autoApply": true,
    ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    "alwaysShowCalendars": true,
    "startDate": moment(),
    "endDate": moment()
}, function (start, end, label) {
    console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
});


(function () {
    "use strict";

    /*
       * jQuery accessible simple (non-modal) tooltip window, using ARIA
       * @version v2.0.4
       * Website: https://a11y.nicolas-hoffmann.net/simple-tooltip/
       * License MIT: https://github.com/nico3333fr/jquery-accessible-simple-tooltip-aria/blob/master/LICENSE
       */

    function accessibleSimpleTooltipAria(options) {
        let element = $(this);
        options = options || element.data();
        let text = options.simpletooltipText || "";
        let prefix_class = typeof options.simpletooltipPrefixClass !== "undefined"
            ? options.simpletooltipPrefixClass + "-"
            : "";
        let content_id = typeof options.simpletooltipContentId !== "undefined"
            ? "#" + options.simpletooltipContentId
            : "";

        let index_lisible = Math.random().toString(32).slice(2, 12);

        element.attr({
            "aria-describedby": "label_simpletooltip_" + index_lisible
        })

        element.wrap(
            '<span class="' + prefix_class + 'simpletooltip_container"></span>'
        );

        let html =
            '<span class="js-simpletooltip ' +
            prefix_class +
            'simpletooltip" id="label_simpletooltip_' +
            index_lisible +
            '" role="tooltip" style="visibility: hidden;">';

        if (text !== "") {
            html += "" + text + "";
        } else {
            let $contentId = $(content_id);
            if (content_id !== "" && $contentId.length) {
                html += $contentId.html();
            }
        }
        html += "</span>";

        $(html).insertAfter(element);
    }

    // Bind as a jQuery plugin
    $.fn.accessibleSimpleTooltipAria = accessibleSimpleTooltipAria;

    $(document).ready(function () {
        $(".js-simple-tooltip").each(function () {
            // Call the function with this as the current tooltip
            accessibleSimpleTooltipAria.apply(this);
        });

        // events ------------------
        $('.js-simple-tooltip').hover(function () {
            let $this = $(this);
            let $tooltip_to_show = $("#" + $this.attr("aria-describedby"));
            // $tooltip_to_show.attr("aria-hidden", "false");

            // $tooltip_to_show.attr("visibility", "visible");
            $tooltip_to_show.css("visibility", "visible");

        }, function () {
            let $this = $(this);
            let $tooltip_to_show = $("#" + $this.attr("aria-describedby"));
            // $tooltip_to_show.attr("aria-hidden", "true");
            $tooltip_to_show.css("visibility", "hidden");
        })
        $("body")
            // .on("mouseenter focusin", ".js-simple-tooltip", function () {
            //     let $this = $(this);
            //     let $tooltip_to_show = $("#" + $this.attr("aria-describedby"));
            //     // $tooltip_to_show.attr("aria-hidden", "false");
            //
            //     // $tooltip_to_show.attr("visibility", "visible");
            //     $tooltip_to_show.css("visibility", "visible");
            //
            // })
            // .on("mouseleave focusout", ".js-simple-tooltip", function () {
            //     let $this = $(this);
            //     let $tooltip_to_show = $("#" + $this.attr("aria-describedby"));
            //     // $tooltip_to_show.attr("aria-hidden", "true");
            //     $tooltip_to_show.css("visibility", "hidden");
            // })
            .on("keydown", ".js-simple-tooltip", function (event) {
                // close esc key

                let $this = $(this);
                let $tooltip_to_show = $("#" + $this.attr("aria-describedby"));

                if (event.keyCode === 27) {
                    // esc
                    // $tooltip_to_show.attr("aria-hidden", "true");
                    $tooltip_to_show.css("visibility", "hidden");
                }
            });
    });
})();


$('.brand').change(function (event) {
    let selectedcategory = $(this).children("option:selected").val();
    sessionStorage.setItem("itemName", selectedcategory);
});

$('.brand').find('option[value=' + sessionStorage.getItem('itemName') + ']').attr('selected', 'selected');

$("#offcanvasExample").mCustomScrollbar({
    theme: "3d-dark"
});


function changeBrand(select) {
    let brand_val = select.value;
    $.ajax({
        type: "PUT",
        url: "/user/brand/" + brand_val,
        success: function (data) {
            if (data.status === "success") {
                toastr.success('update brand success', 'Success', {timeOut: 3000});
                location.reload();
            } else {
                toastr.error('update brand failed', 'error', {timeOut: 3000});
            }

        },
        error: function () {
            toastr.error('update brand failed', 'error', {timeOut: 3000})
        }
    });
}

function addNewBrand() {
    tempId = "";
    $('#modaltitle').html("New Brand");
    $('#brandname').val('');
}

function getBrandFormData($form) {
    let unindexed_array = $form.serializeArray();
    let indexed_array = {};

    $.map(unindexed_array, function (n, i) {
        indexed_array[n['name']] = n['value'];
    });
    return indexed_array;
}

function addBrand(event) {
    let $form = $('#brandForm');
    event.preventDefault();
    if ($form.validate().form()) {
        $.ajax({
            type: "POST",
            url: "/user/brand",
            data: getBrandFormData($form),
            success: function (data) {
                if (data.status === "success") {
                    toastr.success('add brand success', 'Success', {timeOut: 3000});
                    location.reload();
                } else {
                    toastr.error(data.message, 'error', {timeOut: 3000});
                }

            },
            error: function () {
                toastr.error('add brand failed', 'error', {timeOut: 3000})
            }
        });
    }

}
