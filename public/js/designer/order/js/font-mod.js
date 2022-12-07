//change font size slider

$("#slider").on("change", function () {
    var v = $(this).val();
    $(".selected").css("font-size", v + "px");
});



//change font family button

$("#input-font").on("change", function () {
    var v = $(this).val();
    $(".selected").css("fontFamily", v);
    $(".exp").removeClass("selected");
});

//change font family  function

function changeFontStyle(font) {
    document.getElementsByClassName("selected").style.fontFamily = font.value;
}
