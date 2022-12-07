//change Background color button

$("#color-picker").on("change", function () {
    var v = $(this).val();
    $(".selected").css("background", v);
    $(".exp").removeClass("selected");
});

//change font color button

$("#font-color-pick").on("change", function () {
    var v = $(this).val();
    $(".selected").css("color", v);
    $(".exp").removeClass("selected");
});

$("#color-picker").spectrum({
    type: "color",
    showPalette: false,
    showInitial: true,
    showAlpha: false,
    allowEmpty: false,
});
$("#font-color-pick").spectrum({
    type: "color",
    showPalette: false,
    showInitial: true,
    showAlpha: false,
    allowEmpty: false,
});
