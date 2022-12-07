let bg_color;
let font_color;
let font_size;
let font_family;

function copyStyleBtn() {
    bg_color = $(".selected").css("background-color");
    font_color = $(".selected").css("color");
    font_size = $(".selected").css("font-size");
    font_family = $(".selected").css("font-family");
    $(".selected").removeClass("selected");
}
function pasteStyle() {
    $(".selected").css({
        "background-color": bg_color,
        color: font_color,
        "font-size": font_size,
        "font-family": font_family,
    });
    $(".selected").removeClass("selected");
}
