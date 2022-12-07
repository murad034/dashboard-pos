var cloneCount = 2;
function cloneBtn() {
    $(".selected")
        .clone()
        .draggable(droppedConfig)
        // .attr("id", "test-" + cloneCount++)
        .insertAfter($("[id^=btn-pos-last]:last"));
    $(".selected").droppable({
        greedy: false,
        tolerance: "touch",
        drop: function (event, ui) {
            ui.draggable.draggable("option", "revert", true);
        },
    });
    $(".exp").removeClass("selected");
}
