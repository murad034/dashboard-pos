function twicePlusWidth() {
    var keyWidth = parseFloat($(".selected").css("width")) * 2;
    console.log(keyWidth);
    var destWidth = parseFloat($(".dest").css("width"));
    var position = $(".selected").position();
    if (position.left + keyWidth > destWidth) {
        alert("Max button width limit reached");
    } else {
        $(".selected").css({ width: keyWidth + 5 + "px" });
    }
}
function twicePlusHeight() {
    var keyHeight = parseFloat($(".selected").css("height")) * 2;
    var destHeight = parseFloat($(".dest").css("height"));
    var position = $(".selected").position();
    if (position.top + keyHeight > destHeight) {
        alert("Max button Height limit reached");
    } else {
        $(".selected").css({ height: keyHeight + 5 + "px" });
    }
}
function twiceMinusWidth() {
    var keyWidth = $(".selected").css("width");
    var roundWidth = parseFloat(keyWidth);
    var new2xWidth = roundWidth / 2;
    $(".selected").css({ width: new2xWidth - 2.5 + "px" });
}
function twiceMinusHeight() {
    var keyHeight = $(".selected").css("height");
    var roundHeight = parseFloat(keyHeight);
    var new2xHeight = roundHeight / 2;
    $(".selected").css({ height: new2xHeight - 2.5 + "px" });
}
