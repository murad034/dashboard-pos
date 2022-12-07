let stock_list;
let builder;
let webInfo;
let editor;
let stock_data = [];
let image = document.getElementById("image");
let avatar = document.getElementById("avatar");
let input = document.getElementById("input");
let avatar1 = document.getElementById("avatar1");
let input1 = document.getElementById("input1");
let avatar2 = document.getElementById("avatar2");
let input2 = document.getElementById("input2");
let avatar3 = document.getElementById("avatar3");
let input3 = document.getElementById("input3");
let avatar4 = document.getElementById("avatar4");
let input4 = document.getElementById("input4");
let activeAvatar = avatar;

let posInput = document.getElementById("pos-input");
let posImage = document.getElementById("pos-image");

let $modal = $("#modal");

let stockSelect = $("#allocated_stock");
let availableBuilder = $("#builder_available");
let basePrice = $("#baseprice");
let tier1 = $("#tier1");
let tier2 = $("#tiee2");
let tier3 = $("#tier3");
let tier4 = $("#tier4");
let tier5 = $("#tier5");
let baseCost = $("#basecost");
let mainCat = $("#maincat");
let subCat = $("#subcat");
let builderTab = $("#builder-tab");
let allocateField = $("#allocate_field");
let saveButton = $(".savebut");
let updateButton = $(".updatebut");
let loadButton = $(".load-but");
let cropper;
let canvas;
let formData = new FormData();
let tempId;
let copyTempId;
let allocId = "0";
let setPriceClass = "";

// build dropdown list function

function buildDropdown(data, o_ption) {
    let dropdown = '<select style="width:100%; font-size:18px;">';
    for (let i = 0; i < data.length; i++) {
        if (o_ption === data[i]["sku"]) {
            let option =
                '<option data-id="' +
                data[i]["unitval"] +
                '" data-option="' +
                data[i]["stockoption"] +
                '" value="' +
                data[i]["sku"] +
                '" selected>' +
                data[i]["stockname"] +
                "</option>";
            dropdown = dropdown + option;
        } else {
            let option =
                '<option data-id="' +
                data[i]["unitval"] +
                '" data-option="' +
                data[i]["stockoption"] +
                '" value="' +
                data[i]["sku"] +
                '">' +
                data[i]["stockname"] +
                "</option>";
            dropdown = dropdown + option;
        }
    }
    dropdown = dropdown + "</select>";
    return dropdown;
}

// set attribute toggle switch on web info page.
$(document).on("click", ".form-check-input", function () {
    let val = $(this).val();
    if (val === "on") {
        $(this).attr("value", "off");
        $(this).attr("checked", false);
        $(this).prop("checked", false);
    } else {
        $(this).attr("value", "on");
        $(this).attr("checked", true);
        $(this).prop("checked", true);
    }
});

// available builder toggle switch
$(document).on("click", "#builder_available", function () {
    let val = $(this).val();
    if (val === "on") {
        builderTab.show();
        allocateField.hide();
    } else {
        builderTab.hide();
        allocateField.show();
    }
});

$('[data-bs-toggle="tooltip"]').tooltip();

// image upload input change function

posInput.addEventListener("change", function (e) {
    activeAvatar = posImage;
    let files = e.target.files;
    let done = function (url) {
        posInput.value = "";
        image.src = url;
        $modal.modal("show");
    };
    let reader;
    let file;
    if (files && files.length > 0) {
        file = files[0];

        if (URL) {
            let img;
            if ((file = this.files[0])) {
                img = new Image();
                let objectUrl = URL.createObjectURL(file);
                img.onload = function () {
                    if (this.height < 600 || this.width < 600) {
                        toastr.warning(
                            "Height and Width must bigger than 600px."
                        );
                        return false;
                    } else {
                        done(URL.createObjectURL(file));
                    }
                };
                img.src = objectUrl;
            }
        } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
                let image = new Image();

                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;

                //Validate the File Height and Width.
                image.onload = function () {
                    let height = this.height;
                    let width = this.width;
                    if (height < 600 || width < 600) {
                        toastr.warning(
                            "Height and Width must bigger than 600px."
                        );
                        return false;
                    } else {
                        done(reader.result);
                        return true;
                    }
                };
            };
            reader.readAsDataURL(file);
        }
    }
});

input.addEventListener("change", function (e) {
    activeAvatar = avatar;
    let files = e.target.files;
    let done = function (url) {
        input.value = "";
        image.src = url;
        $modal.modal("show");
    };
    let reader;
    let file;
    if (files && files.length > 0) {
        file = files[0];

        if (URL) {
            let img;
            if ((file = this.files[0])) {
                img = new Image();
                let objectUrl = URL.createObjectURL(file);
                img.onload = function () {
                    if (this.height < 600 || this.width < 600) {
                        toastr.warning(
                            "Height and Width must bigger than 600px."
                        );
                        return false;
                    } else {
                        done(URL.createObjectURL(file));
                    }
                };
                img.src = objectUrl;
            }
        } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
                let image = new Image();

                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;

                //Validate the File Height and Width.
                image.onload = function () {
                    let height = this.height;
                    let width = this.width;
                    if (height < 600 || width < 600) {
                        toastr.warning(
                            "Height and Width must bigger than 600px."
                        );
                        return false;
                    } else {
                        done(reader.result);
                        return true;
                    }
                };
            };
            reader.readAsDataURL(file);
        }
    }
});

input1.addEventListener("change", function (e) {
    activeAvatar = avatar1;
    let files = e.target.files;
    let done = function (url) {
        input1.value = "";
        image.src = url;
        $modal.modal("show");
    };
    let reader;
    let file;
    if (files && files.length > 0) {
        file = files[0];

        if (URL) {
            let img;
            if ((file = this.files[0])) {
                img = new Image();
                let objectUrl = URL.createObjectURL(file);
                img.onload = function () {
                    if (this.height < 600 || this.width < 600) {
                        toastr.warning(
                            "Height and Width must bigger than 600px."
                        );
                        return false;
                    } else {
                        done(URL.createObjectURL(file));
                    }
                };
                img.src = objectUrl;
            }
        } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
                let image = new Image();

                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;

                //Validate the File Height and Width.
                image.onload = function () {
                    let height = this.height;
                    let width = this.width;
                    if (height < 600 || width < 600) {
                        toastr.warning(
                            "Height and Width must bigger than 600px."
                        );
                        return false;
                    } else {
                        done(reader.result);
                        return true;
                    }
                };
            };
            reader.readAsDataURL(file);
        }
    }
});

input2.addEventListener("change", function (e) {
    activeAvatar = avatar2;
    let files = e.target.files;
    let done = function (url) {
        input2.value = "";
        image.src = url;
        $modal.modal("show");
    };
    let reader;
    let file;
    if (files && files.length > 0) {
        file = files[0];

        if (URL) {
            let img;
            if ((file = this.files[0])) {
                img = new Image();
                let objectUrl = URL.createObjectURL(file);
                img.onload = function () {
                    if (this.height < 600 || this.width < 600) {
                        toastr.warning(
                            "Height and Width must bigger than 600px."
                        );
                        return false;
                    } else {
                        done(URL.createObjectURL(file));
                    }
                };
                img.src = objectUrl;
            }
        } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
                let image = new Image();

                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;

                //Validate the File Height and Width.
                image.onload = function () {
                    let height = this.height;
                    let width = this.width;
                    if (height < 600 || width < 600) {
                        toastr.warning(
                            "Height and Width must bigger than 600px."
                        );
                        return false;
                    } else {
                        done(reader.result);
                        return true;
                    }
                };
            };
            reader.readAsDataURL(file);
        }
    }
});

input3.addEventListener("change", function (e) {
    activeAvatar = avatar3;
    let files = e.target.files;
    let done = function (url) {
        input3.value = "";
        image.src = url;
        $modal.modal("show");
    };
    let reader;
    let file;
    if (files && files.length > 0) {
        file = files[0];

        if (URL) {
            let img;
            if ((file = this.files[0])) {
                img = new Image();
                let objectUrl = URL.createObjectURL(file);
                img.onload = function () {
                    if (this.height < 600 || this.width < 600) {
                        toastr.warning(
                            "Height and Width must bigger than 600px."
                        );
                        return false;
                    } else {
                        done(URL.createObjectURL(file));
                    }
                };
                img.src = objectUrl;
            }
        } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
                let image = new Image();

                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;

                //Validate the File Height and Width.
                image.onload = function () {
                    let height = this.height;
                    let width = this.width;
                    if (height < 600 || width < 600) {
                        toastr.warning(
                            "Height and Width must bigger than 600px."
                        );
                        return false;
                    } else {
                        done(reader.result);
                        return true;
                    }
                };
            };
            reader.readAsDataURL(file);
        }
    }
});

input4.addEventListener("change", function (e) {
    activeAvatar = avatar4;
    let files = e.target.files;
    let done = function (url) {
        input4.value = "";
        image.src = url;
        $modal.modal("show");
    };
    let reader;
    let file;
    if (files && files.length > 0) {
        file = files[0];

        if (URL) {
            let img;
            if ((file = this.files[0])) {
                img = new Image();
                let objectUrl = URL.createObjectURL(file);
                img.onload = function () {
                    if (this.height < 600 || this.width < 600) {
                        toastr.warning(
                            "Height and Width must bigger than 600px."
                        );
                        return false;
                    } else {
                        done(URL.createObjectURL(file));
                    }
                };
                img.src = objectUrl;
            }
        } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
                let image = new Image();

                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;

                //Validate the File Height and Width.
                image.onload = function () {
                    let height = this.height;
                    let width = this.width;
                    if (height < 600 || width < 600) {
                        toastr.warning(
                            "Height and Width must bigger than 600px."
                        );
                        return false;
                    } else {
                        done(reader.result);
                        return true;
                    }
                };
            };
            reader.readAsDataURL(file);
        }
    }
});

// show crop modal
$modal
    .on("shown.bs.modal", function () {
        let minCroppedWidth = 600;
        let minCroppedHeight = 600;
        let maxCroppedWidth = 600;
        let maxCroppedHeight = 600;
        cropper = new Cropper(image, {
            viewMode: 3,
            aspectRatio: 1,
            zoomable: false,

            data: {
                width: (minCroppedWidth + maxCroppedWidth) / 2,
                height: (minCroppedHeight + maxCroppedHeight) / 2,
            },

            crop: function (event) {
                let width = event.detail.width;
                let height = event.detail.height;

                if (
                    width < minCroppedWidth ||
                    height < minCroppedHeight ||
                    width > maxCroppedWidth ||
                    height > maxCroppedHeight
                ) {
                    cropper.setData({
                        width: Math.max(
                            minCroppedWidth,
                            Math.min(maxCroppedWidth, width)
                        ),
                        height: Math.max(
                            minCroppedHeight,
                            Math.min(maxCroppedHeight, height)
                        ),
                    });
                }
            },
        });
    })
    .on("hidden.bs.modal", function () {
        cropper.destroy();
        cropper = null;
    });

// get crop image
document.getElementById("crop").addEventListener("click", function () {
    $modal.modal("hide");

    if (cropper) {
        canvas = cropper.getCroppedCanvas({
            width: 600,
            height: 600,
        });
        // activeAvatar.src = canvas.toDataURL();
        let formData = new FormData();
        let blob = dataURItoBlob(canvas.toDataURL());

        formData.append("saveImg", blob);

        $.ajax({
            type: "POST",
            url: "/products/save-img",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.status === "success") {
                    activeAvatar.src = data.data;
                } else {
                    toastr.error("save image failed", "error", {
                        timeOut: 3000,
                    });
                }
            },
            error: function () {
                toastr.error("save image failed", "error", { timeOut: 3000 });
            },
        });
    }
});

// copy price, tier from general to web panel
function copyPriceToWeb(class_name) {
    let copyVal = "";
    $("#exampletwo")
        .find("tbody ." + class_name)
        .map(function () {
            if ($(this).data("id") === copyTempId) {
                copyVal = $(this).val();
            }
        });
    $("#web-info")
        .find("tbody .web-info-price")
        .map(function () {
            if ($(this).data("id") === copyTempId) {
                $(this).val(copyVal);
            }
        });
}

$(function () {
    $("#exampletwo").on("click", ".price-change", function () {
        $('[data-bs-toggle="popover"]').not(this).popover("hide");
        let val = $(this)
            .text()
            .replace(/^\s+|\s+$/g, "");
        switch (val) {
            case "SET Price":
                setPriceClass = ".productprice";
                break;
            case "SET Tier1":
                setPriceClass = ".producttier1";
                break;
            case "SET Tier2":
                setPriceClass = ".producttier2";
                break;
            case "SET Tier3":
                setPriceClass = ".producttier3";
                break;
            case "SET Tier4":
                setPriceClass = ".producttier4";
                break;
            case "SET Tier5":
                setPriceClass = ".producttier5";
                break;
            case "SET Cost":
                setPriceClass = ".productcost";
                break;
            default:
                break;
        }
    });

    $(".price-change").each(function (index) {
        $(this).popover({
            // trigger   : "click",
            // container: 'body',
            placement: "top",
            html: true,
            title: "PRICE CHANGE",
            content: function () {
                return $("#PopoverContent").html();
            },
            sanitize: false, // here it is
        });
    });

    $("#allocated_stock").select2({});

    basePrice.numeric({ negative: false });
    tier1.numeric({ negative: false });
    tier2.numeric({ negative: false });
    tier3.numeric({ negative: false });
    tier4.numeric({ negative: false });
    tier5.numeric({ negative: false });
    baseCost.numeric({ negative: false });
    $("#basesoh").numeric({ negative: false });
    $(".productprice").numeric({ negative: false });
    $(".producttier1").numeric({ negative: false });
    $(".producttier2").numeric({ negative: false });
    $(".producttier3").numeric({ negative: false });
    $(".producttier4").numeric({ negative: false });
    $(".producttier5").numeric({ negative: false });
    $(".productcost").numeric({ negative: false });
    $(".productsoh").numeric({ negative: false });
    $(".popover-body").find("#set_price_option").numeric({ negative: false });

    // initialize rich editor

    editor = $("#webdescription").richText({
        // uploads
        imageUpload: false,
        fileUpload: false,
        videoEmbed: false,
    });

    // get stock list
    $.ajax({
        url: "/products/builder-api",
        method: "GET",
        async: false,
    }).done(function (data) {
        if (data.status === "success") {
            if (data.data.length === 0) {
                toastr.error("no stock list. you have to put stock.", "error", {
                    timeOut: 3000,
                });
            } else {
                stock_list = data.data;
            }
        } else {
            toastr.error(data.message, "error", { timeOut: 3000 });
        }
    });

    // cost-builder table initialize.
    builder = $("#cost-builder").DataTable({
        columnDefs: [
            {
                targets: 0,
                data: function (row) {
                    return buildDropdown(stock_list, null);
                },
            },
            {
                targets: 1,
                class: "editable",
            },
        ],
    });

    $(document).on("click", ".copy-price-web", function () {
        copyTempId = $(this).prev().data("id");
    });

    // web information table initialize.
    webInfo = $("#web-info").DataTable({
        // stateSave: true,
        columns: [
            { data: "locationname" },
            {
                data: null,
                targets: -1,
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).css("display", "flex");
                },
            },
            {
                data: null,
                targets: -1,
                className: "text-center",
                defaultContent:
                    '<div class="col-md-6"><div class="form-check form-switch" style="padding-top: 30px;"><input class="form-check-input" type="checkbox" name="web_available" value=\'off\'><label class="form-check-label" for="web_available"></label></div>',
            },
        ],
        ajax: {
            url: "/products/web/api",
            method: "GET",
        },
        columnDefs: [
            {
                orderable: false,
                targets: [1, 2],
            },
            {
                targets: 1,
                data: null,
                render: function (data, type, full, meta) {
                    data =
                        ' <input class="form-control web-info-price" name="web_price" data-id=' +
                        full["locationid"] +
                        '><button type="button" class="btn copy-price-web" data-bs-toggle="modal" data-bs-target="#exampleModal"><i title=\'Copy to price\' class="fa fa-copy"></i></button>';
                    return data;
                },
            },
        ],
    });
    // set unit value column
    $("#cost-builder tbody").on("change", "td", function () {
        if ($(this).html().includes("select")) {
            let rowIndex = builder.cell(this).index().row;
            builder
                .cell(rowIndex, 2)
                .data($(this).find(":selected").data("id"))
                .draw(false);
            builder
                .cell(rowIndex, 3)
                .data($(this).find(":selected").data("option"))
                .draw(false);
        }
    });

    $("#cost-builder tbody").on("click", "tr", function () {
        if ($(this).hasClass("selected")) {
            $(this).removeClass("selected");
        } else {
            builder.$("tr.selected").removeClass("selected");
            $(this).addClass("selected");
        }
    });

    $("#removeRow").click(function () {
        if (builder.rows(".selected").any()) {
            if (confirm("Are you sure remove selected row?")) {
                builder.row(".selected").remove().draw(false);
                toastr.success("remove selected row success.");
            } else {
                toastr.warning("remove event canceled.");
            }
        } else {
            toastr.warning("no selected row.");
        }
    });
    // click add Item button
    $("#addRow").on("click", function () {
        if (stock_list !== undefined && stock_list.length !== 0) {
            builder.row
                .add([
                    "",
                    "",
                    stock_list[0]["unitval"],
                    stock_list[0]["stockoption"],
                ])
                .draw(false);
        } else {
            toastr.error("No Stock List, put add stock on Stock page");
        }
    });
    $("#clearTable").on("click", function () {
        if (confirm("Are you sure clear Table?")) {
            builder.clear().destroy();
            builder = $("#cost-builder").DataTable({
                columnDefs: [
                    {
                        targets: 0,
                        data: function (row) {
                            return buildDropdown(stock_list, null);
                        },
                    },
                    {
                        targets: 1,
                        class: "editable",
                    },
                    {
                        targets: 1,
                        data: null,
                    },
                ],
            });
            toastr.success("clear table success.");
        } else {
            toastr.warning("clear table canceled.");
        }
    });

    // edit QTY value
    let rowTempId;
    $(document).on("click", ".editable", function () {
        // rowTempId = builder.cell(this).index().row;
        let tr = $(this).closest("tr");
        rowTempId = tr.index();

        let html = $(this).text();
        let input = $('<input type="text" style="width:100%;"/>');
        input.val(html);
        $(this).replaceWith(input);
        $("#cost-builder input").focus().select();
    });

    $(document).on("blur", "#cost-builder input", function () {
        this.value = this.value.replace(/[^0-9\.]/g, "");
        builder.cell(rowTempId, 1).data(this.value).draw(false);
        $(this).replaceWith(
            '<td class="editable edited">' + this.value + "</td>"
        );
    });
    // calculate GP % when change product price and alt pricing.
    $(document).on("change", ".productprice", function () {
        let pro_price = this.value;
        let pro_cost = $(this).parent().parent().find("input").eq(6).val();
        if (
            parseFloat(pro_price) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price;
            $(this)
                .next()
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).next().text("GP % ");
        }
    });

    $(document).on("change", ".producttier1", function () {
        let pro_price = this.value;
        let pro_cost = $(this).parent().parent().find("input").eq(6).val();
        if (
            parseFloat(pro_price) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price;
            $(this)
                .next()
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).next().text("GP % ");
        }
    });

    $(document).on("change", ".producttier2", function () {
        let pro_price = this.value;
        let pro_cost = $(this).parent().parent().find("input").eq(6).val();
        if (
            parseFloat(pro_price) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price;
            $(this)
                .next()
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).next().text("GP % ");
        }
    });

    $(document).on("change", ".producttier3", function () {
        let pro_price = this.value;
        let pro_cost = $(this).parent().parent().find("input").eq(6).val();
        if (
            parseFloat(pro_price) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price;
            $(this)
                .next()
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).next().text("GP % ");
        }
    });

    $(document).on("change", ".producttier4", function () {
        let pro_price = this.value;
        let pro_cost = $(this).parent().parent().find("input").eq(6).val();
        if (
            parseFloat(pro_price) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price;
            $(this)
                .next()
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).next().text("GP % ");
        }
    });

    $(document).on("change", ".producttier5", function () {
        let pro_price = this.value;
        let pro_cost = $(this).parent().parent().find("input").eq(6).val();
        if (
            parseFloat(pro_price) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price;
            $(this)
                .next()
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).next().text("GP % ");
        }
    });

    $(document).on("change", "#baseprice", function () {
        let pro_price = this.value;
        let pro_cost = $(this).parent().parent().find("input").eq(6).val();
        if (
            parseFloat(pro_price) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price;
            $(this)
                .next()
                .next()
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).next().next().text("GP % ");
        }
    });

    $(document).on("change", "#tier1", function () {
        let pro_price = this.value;
        let pro_cost = $(this).parent().parent().find("input").eq(6).val();
        if (
            parseFloat(pro_price) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price;
            $(this)
                .next()
                .next()
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).next().next().text("GP % ");
        }
    });

    $(document).on("change", "#tier2", function () {
        let pro_price = this.value;
        let pro_cost = $(this).parent().parent().find("input").eq(6).val();
        if (
            parseFloat(pro_price) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price;
            $(this)
                .next()
                .next()
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).next().next().text("GP % ");
        }
    });

    $(document).on("change", "#tier3", function () {
        let pro_price = this.value;
        let pro_cost = $(this).parent().parent().find("input").eq(6).val();
        if (
            parseFloat(pro_price) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price;
            $(this)
                .next()
                .next()
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).next().next().text("GP % ");
        }
    });

    $(document).on("change", "#tier4", function () {
        let pro_price = this.value;
        let pro_cost = $(this).parent().parent().find("input").eq(6).val();
        if (
            parseFloat(pro_price) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price;
            $(this)
                .next()
                .next()
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).next().next().text("GP % ");
        }
    });

    $(document).on("change", "#tier5", function () {
        let pro_price = this.value;
        let pro_cost = $(this).parent().parent().find("input").eq(6).val();
        if (
            parseFloat(pro_price) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price;
            $(this)
                .next()
                .next()
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).next().next().text("GP % ");
        }
    });

    $(document).on("change", "#basecost", function () {
        let pro_cost = this.value;
        let pro_price = $(this).parent().parent().find("input").eq(0).val();
        let pro_price_tier1 = $(this)
            .parent()
            .parent()
            .find("input")
            .eq(1)
            .val();
        let pro_price_tier2 = $(this)
            .parent()
            .parent()
            .find("input")
            .eq(2)
            .val();
        let pro_price_tier3 = $(this)
            .parent()
            .parent()
            .find("input")
            .eq(3)
            .val();
        let pro_price_tier4 = $(this)
            .parent()
            .parent()
            .find("input")
            .eq(4)
            .val();
        let pro_price_tier5 = $(this)
            .parent()
            .parent()
            .find("input")
            .eq(5)
            .val();

        if (
            parseFloat(pro_price) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price;
            $(this)
                .parent()
                .parent()
                .find("span")
                .eq(0)
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).parent().parent().find("span").eq(0).text("GP % ");
        }

        if (
            parseFloat(pro_price_tier1) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price_tier1)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price_tier1;
            $(this)
                .parent()
                .parent()
                .find("span")
                .eq(1)
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).parent().parent().find("span").eq(1).text("GP % ");
        }

        if (
            parseFloat(pro_price_tier2) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price_tier2)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price_tier2;
            $(this)
                .parent()
                .parent()
                .find("span")
                .eq(2)
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).parent().parent().find("span").eq(2).text("GP % ");
        }
        if (
            parseFloat(pro_price_tier3) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price_tier3)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price_tier3;
            $(this)
                .parent()
                .parent()
                .find("span")
                .eq(3)
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).parent().parent().find("span").eq(3).text("GP % ");
        }
        if (
            parseFloat(pro_price_tier4) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price_tier4)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price_tier4;
            $(this)
                .parent()
                .parent()
                .find("span")
                .eq(4)
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).parent().parent().find("span").eq(4).text("GP % ");
        }
        if (
            parseFloat(pro_price_tier5) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price_tier5)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price_tier5;
            $(this)
                .parent()
                .parent()
                .find("span")
                .eq(5)
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).parent().parent().find("span").eq(5).text("GP % ");
        }
    });

    $(document).on("change", ".productcost", function () {
        let pro_cost = this.value;
        let pro_price = $(this).parent().parent().find("input").eq(0).val();
        let pro_price_tier1 = $(this)
            .parent()
            .parent()
            .find("input")
            .eq(1)
            .val();
        let pro_price_tier2 = $(this)
            .parent()
            .parent()
            .find("input")
            .eq(2)
            .val();
        let pro_price_tier3 = $(this)
            .parent()
            .parent()
            .find("input")
            .eq(3)
            .val();
        let pro_price_tier4 = $(this)
            .parent()
            .parent()
            .find("input")
            .eq(4)
            .val();
        let pro_price_tier5 = $(this)
            .parent()
            .parent()
            .find("input")
            .eq(5)
            .val();
        if (
            parseFloat(pro_price) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price;
            $(this)
                .parent()
                .parent()
                .find("span")
                .eq(0)
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).parent().parent().find("span").eq(0).text("GP % ");
        }

        if (
            parseFloat(pro_price_tier1) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price_tier1)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price_tier1;
            $(this)
                .parent()
                .parent()
                .find("span")
                .eq(1)
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).parent().parent().find("span").eq(1).text("GP % ");
        }

        if (
            parseFloat(pro_price_tier2) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price_tier2)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price_tier2;
            $(this)
                .parent()
                .parent()
                .find("span")
                .eq(2)
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).parent().parent().find("span").eq(2).text("GP % ");
        }

        if (
            parseFloat(pro_price_tier3) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price_tier3)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price_tier3;
            $(this)
                .parent()
                .parent()
                .find("span")
                .eq(3)
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).parent().parent().find("span").eq(3).text("GP % ");
        }

        if (
            parseFloat(pro_price_tier4) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price_tier4)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price_tier4;
            $(this)
                .parent()
                .parent()
                .find("span")
                .eq(4)
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).parent().parent().find("span").eq(4).text("GP % ");
        }

        if (
            parseFloat(pro_price_tier5) !== 0 &&
            parseFloat(pro_cost) !== 0 &&
            !isNaN(parseFloat(pro_price_tier5)) &&
            !isNaN(parseFloat(pro_cost))
        ) {
            let percent = 100 - (pro_cost * 100) / pro_price_tier5;
            $(this)
                .parent()
                .parent()
                .find("span")
                .eq(6)
                .text("GP " + percent.toFixed(2) + "%");
        } else {
            $(this).parent().parent().find("span").eq(6).text("GP % ");
        }
    });

    $("#upload_file").click(function () {
        $("#upload_form").submit();
        $("#upload_file").attr("disabled", "disabled");
    });
    $("#upload_form").on("submit", function (event) {
        event.preventDefault();
        $.ajax({
            url: "/products/upload",
            method: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if (data.error !== "") {
                    $("#message").html(
                        '<div class="alert alert-danger">' +
                            data.error +
                            "</div>"
                    );
                } else {
                    $("#process_area").html(data.output);
                    $("#upload_area").css("display", "none");
                    $("#upload_file").hide();
                    $("#import").show();
                }
            },
        });
    });

    let column_data = {};

    let total_selection = 0;

    $(document).on("change", ".set_column_data", function () {
        let column_name = $(this).val();

        let column_number = $(this).data("column_number");

        for (let key in column_data) {
            if (column_data.hasOwnProperty(key)) {
                if (column_data[key] === column_number) {
                    delete column_data[key];
                }
            }
        }

        if (column_name in column_data) {
            alert("You have already define " + column_name + " column");

            $(this).val("");

            return false;
        }

        if (column_name !== "") {
            column_data[column_name] = column_number;
        } else {
            const entries = Object.entries(column_data);

            for (const [key, value] of entries) {
                if (value === column_number) {
                    delete column_data[key];
                }
            }
        }

        total_selection = Object.keys(column_data).length;

        if (total_selection > 0) {
            $("#import").attr("disabled", false);
        } else {
            $("#import").attr("disabled", "disabled");
        }
    });

    $(document).on("click", "#import", function (event) {
        if (!("sku" in column_data)) {
            toastr.error("you must set sku column", "error", { timeOut: 3000 });
            return false;
        } else {
            event.preventDefault();

            $.ajax({
                url: "/products/import",
                method: "POST",
                data: column_data,
                success: function (data) {
                    if (data.status === "success") {
                        toastr.success("csv import success", "Success", {
                            timeOut: 3000,
                        });
                        $("#status_show").val("all");
                        $(".btn-secondary").click();
                        $("#example1")
                            .DataTable()
                            .ajax.url("products/api?status=all")
                            .load();
                    }
                },
            });
        }
    });

    $("#example1").DataTable({
        // stateSave: true,
        columns: [
            { data: "sku" },
            { data: "productname" },
            { data: "barcode" },
            { data: "catagory.catagoryname" },
            { data: "subcatagory.subcatagoryname" },
            { data: "status" },
            { data: "status" },
        ],
        ajax: {
            url: "/products/api",
            method: "GET",
        },
        columnDefs: [
            {
                targets: 5,
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
            {
                targets: 6,
                data: null,
                render: function (data, type, full, meta) {
                    if (type === "display") {
                        if (data === "inactive") {
                            data =
                                '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editProduct(' +
                                full["sku"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a><a data-simpletooltip-text="Re-activate Item"  class="js-simple-tooltip profile-btn" onclick="Realert(' +
                                full["sku"] +
                                ",`" +
                                full["productname"] +
                                '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; background: green;" ><i class="fa fa-check"></i></a>';
                        } else {
                            data =
                                '<a data-simpletooltip-text="Edit Item"  class="js-simple-tooltip profile-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample" onclick="editProduct(' +
                                full["sku"] +
                                ')" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" ><i class="fa fa-info"></i></a> <a data-simpletooltip-text="De-activate Item"  class="js-simple-tooltip profile-btn-del" onclick="JSalert(' +
                                full["sku"] +
                                ",`" +
                                full["productname"] +
                                '`)" style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" ><i class="fa fa-times"></i></a>';
                        }
                    }
                    return data;
                },
            },
        ],
    });

    $("#exampletwo").DataTable({
        stateSave: true,
    });
    // $("#example1_wrapper")
    //     .find(".dataTables_filter")
    //     .prepend(
    //         '<div style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; display: inline-flex;" class="simpletooltip_container"><select class="form-control" id="status_show" name="status_show" onchange="getShowStatus(this)"><option value="all">All</option><option value="active">Active</option><option value="inactive">Deactive</option></select></div><a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewProduct()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"><i class="fa fa-plus"></i></a><a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px;" data-simpletooltip-text="Import" class="js-simple-tooltip profile-btn" onclick="" data-bs-toggle="offcanvas" href="#offcanvasExample2" aria-controls="offcanvasExample2"><i class="fa fa-arrow-right"></i></a>'
    //     );
    $("#example1_wrapper")
        .find(".dataTables_filter")
        .prepend(
            '<div style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; display: inline-flex;" class="simpletooltip_container"><select class="form-control" id="status_show" name="status_show" onchange="getShowStatus(this)"><option value="all">All</option><option value="active">Active</option><option value="inactive">Deactive</option></select></div><a style="padding: 6px; padding-left: 13px; padding-right: 13px; margin-top: 0px; font-size: 18px; margin-right:5px;" data-simpletooltip-text="Add New" class="js-simple-tooltip profile-btn" onclick="addNewProduct()" data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"><i class="fa fa-plus"></i></a>'
        );
});

function getShowStatus(item) {
    $("#example1")
        .DataTable()
        .ajax.url("products/api?status=" + item.value)
        .load();
}

$("#content-4").mCustomScrollbar({
    theme: "3d-dark",
});

$(".productprice").on("click", function () {
    $(this).select();
});
$(
    ".producttier1, .producttier2, .producttier3, .producttier4, .producttier5"
).on("click", function () {
    $(this).select();
});
$(".productcost").on("click", function () {
    $(this).select();
});
$(".product-input").on("click", function () {
    $(this).select();
});
$(".productsoh").on("click", function () {
    $(this).select();
});

// get web information table data
function convertTableToArrayObject() {
    let data = [];
    let table = $("#web-info").DataTable();

    table.rows().every(function (rowIdx, tableLoop, rowLoop) {
        let rowData = {};

        let cell = table.cell({ row: rowIdx, column: 0 }).node();
        let cell1 = table.cell({ row: rowIdx, column: 1 }).node();
        let cell2 = table.cell({ row: rowIdx, column: 2 }).node();
        rowData["locationname"] = $(cell).text();
        rowData["locationid"] = $("input", cell1).data("id");
        rowData["price"] = $("input", cell1).val();
        rowData["available"] = $("input", cell2).val();

        data.push(rowData);
    });

    return data;
}

// table data to json data
function tableToJSON() {
    let data = [];
    let status = true;
    $("#cost-builder")
        .find("tbody select")
        .map(function () {
            let row_data = [];
            let rowIndex = builder.cell($(this).parent()).index().row;
            row_data.push($(this).find(":selected").val());
            if (builder.cell(rowIndex, 1).data() !== "") {
                row_data.push(builder.cell(rowIndex, 1).data());
                row_data.push(builder.cell(rowIndex, 2).data().toString());
                row_data.push(builder.cell(rowIndex, 3).data().toString());
                data.push(row_data);
            } else {
                toastr.warning("please input QTY value.");
                status = false;
            }
        });
    if (status === false) {
        return [];
    } else {
        return data;
    }
}

// calculate Cost builder
function calCost() {
    stock_data = tableToJSON();

    if (stock_data.length !== 0) {
        $.ajax({
            type: "POST",
            url: "/products/cost-api",
            data: {
                _token: csrfToken,
                data: stock_data,
            },
            success: function (data) {
                if (data.status === "success") {
                    let allData = data.data;
                    let cost_data = allData["costData"];
                    let soh_data = allData["sohData"];
                    baseCost.val(parseFloat(cost_data[0]).toFixed(2));
                    $(".productcost").each(function (index) {
                        let location_id = $(this).data("id");
                        $(this).val(
                            parseFloat(cost_data[location_id]).toFixed(2)
                        );
                    });

                    $("#basesoh").val(parseFloat(soh_data[0]).toFixed(2));
                    $(".productsoh").each(function (index) {
                        let location_id = $(this).data("id");
                        $(this).val(
                            parseFloat(soh_data[location_id]).toFixed(2)
                        );
                    });
                    $("#home-tab").trigger("click");
                    toastr.success(
                        "cost builder calculation success",
                        "Success",
                        { timeOut: 3000 }
                    );
                } else {
                    toastr.error("save product failed", "error", {
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

function JSalert(id, protitle) {
    swal(
        {
            title: protitle + " will be deactivated",
            text: "Are you sure to proceed?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes Deactivate!",
            cancelButtonText: "No Take me back!",
            closeOnConfirm: false,
            closeOnCancel: false,
        },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "/products/api/" + id,
                    method: "DELETE",
                    data: {
                        id: id,
                        _token: csrfToken,
                    },
                })
                    .done(function (data) {
                        if (data.status === "success") {
                            swal(
                                {
                                    title: protitle + "  deactivated",
                                    text: "If this was a mistake you can re-activate the item in the inactive stocklist screen!",
                                    type: "success",
                                },
                                function (isConfirm) {
                                    if (isConfirm) {
                                        $("#status_show").val("all");
                                        $("#example1")
                                            .DataTable()
                                            .ajax.url("products/api?status=all")
                                            .load();
                                    } else {
                                        $("#status_show").val("all");
                                        $("#example1")
                                            .DataTable()
                                            .ajax.url("products/api?status=all")
                                            .load();
                                    }
                                }
                            );
                        } else {
                            swal(
                                {
                                    title: protitle + " can't be deactivated",
                                    text: "The product you have selected is currently allocated to a keypad. Please remove it from all keypad layouts before deactivating it.",
                                    type: "warning",
                                },
                                function (isConfirm) {
                                    if (isConfirm) {
                                    } else {
                                    }
                                }
                            );
                        }
                    })
                    .fail(function (jqXHR, status, errorThrown) {
                        alert(errorThrown);
                    });
            } else {
                swal("Phew that was close!", "Nothing has changed!", "error");
            }
        }
    );
}

function Realert(id, protitle) {
    swal(
        {
            title: protitle + " will be reactivated",
            text: "Are you sure to proceed?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes Re-Activate!",
            cancelButtonText: "No Take me back!",
            closeOnConfirm: false,
            closeOnCancel: false,
        },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    method: "PUT",
                    url: "/products/api/" + id,
                    data: {
                        id: id,
                        _token: csrfToken,
                    },
                })
                    .done(function (data) {
                        if (data.status === "success") {
                            swal(
                                {
                                    title: protitle + "  reactivated",
                                    text: "If this was a mistake you can re-activate the item in the inactive stocklist screen!",
                                    type: "success",
                                },
                                function (isConfirm) {
                                    $("#status_show").val("all");
                                    $("#example1")
                                        .DataTable()
                                        .ajax.url("products/api?status=all")
                                        .load();
                                }
                            );
                        } else {
                            swal(
                                {
                                    title: protitle + " can't be deactivated",
                                    text: "This stock item is currently active on a keypad, please remove it from all keypads first then reactivate again!",
                                    type: "warning",
                                },
                                function (isConfirm) {
                                    $("#status_show").val("all");
                                    $("#example1")
                                        .DataTable()
                                        .ajax.url("products/api?status=all")
                                        .load();
                                }
                            );
                        }
                    })
                    .fail(function (jqXHR, status, errorThrown) {
                        alert(errorThrown);
                    });
            } else {
                swal("Phew that was close!", "Nothing has changed!", "error");
            }
        }
    );
}

function addNewProduct() {
    //alert('new');
    $("#temptitle").html("New Product");
    saveButton.show();
    updateButton.hide();
    // $('#plu').prop('disabled', false);
    // $('#plu').hide();
    mainCat.val("no");
    subCat.val("no");
    // $('#plu').val($('#newplu').val());
    $("#productname").val("");
    $("#barcode").val("");
    $("#barcode1").val("");
    $("#barcode2").val("");
    $("#gstfreebox").prop("checked", false);
    $("#gstfree").val("0");
    $("#ismodbox").prop("checked", false);
    $("#ismod").val("0");
    $("#iskgbox").prop("checked", false);
    $("#iskg").val("0");

    availableBuilder.attr("value", "off");
    availableBuilder.attr("checked", false);
    availableBuilder.prop("checked", false);
    // availableBuilder.trigger("click");
    stockSelect.select2("val", "0");
    builderTab.hide();
    allocateField.show();

    basePrice.val("0.00");
    tier1.val("0.00");
    tier2.val("0.00");
    tier3.val("0.00");
    tier4.val("0.00");
    tier5.val("0.00");
    baseCost.val("0.00");
    $(".productprice").each(function (index) {
        $(this).val("0.00");
    });

    $(".producttier1").each(function (index) {
        $(this).val("0.00");
    });
    $(".producttier2").each(function (index) {
        $(this).val("0.00");
    });
    $(".producttier3").each(function (index) {
        $(this).val("0.00");
    });
    $(".producttier4").each(function (index) {
        $(this).val("0.00");
    });
    $(".producttier5").each(function (index) {
        $(this).val("0.00");
    });

    $(".productcost").each(function (index) {
        $(this).val("0.00");
    });

    //    initilaize web info panel
    $("#webtitle").val("");
    $("#webdescription").prev().html("");
    posImage.src = "https://avatars0.githubusercontent.com/u/3456749?s=600";
    avatar.src = "https://avatars0.githubusercontent.com/u/3456749?s=600";
    avatar1.src = "https://avatars0.githubusercontent.com/u/3456749?s=600";
    avatar2.src = "https://avatars0.githubusercontent.com/u/3456749?s=600";
    avatar3.src = "https://avatars0.githubusercontent.com/u/3456749?s=600";
    avatar4.src = "https://avatars0.githubusercontent.com/u/3456749?s=600";
    $("#webmapid").val("");
}

$(".checkgst").change(function () {
    if ($(this).is(":checked")) {
        $(".gst").val("1");
    } else if ($(this).not(":checked")) {
        $(".gst").val("0");
    }
});
$(".checkmod").change(function () {
    if ($(this).is(":checked")) {
        $(".mod").val("1");
    } else if ($(this).not(":checked")) {
        $(".mod").val("0");
    }
});

function getBase64Image(imgUrl) {
    return new Promise(function (resolve, reject) {
        let img = new Image();
        img.src = imgUrl;
        img.setAttribute("crossOrigin", "anonymous");

        img.onload = function () {
            let canvas = document.createElement("canvas");
            canvas.width = img.width;
            canvas.height = img.height;
            let ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0);
            let dataURL = canvas.toDataURL("image/png");
            let data = dataURItoBlob(dataURL);
            resolve(data);
        };
        img.onerror = function () {
            reject("The image could not be loaded.");
        };
    });
}

function dataURItoBlob(dataURI) {
    let byteString = atob(dataURI.split(",")[1]);

    let mimeString = dataURI.split(",")[0].split(":")[1].split(";")[0];

    let ab = new ArrayBuffer(byteString.length);
    let ia = new Uint8Array(ab);
    for (let i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }

    return new Blob([ab], { type: mimeString });
}

async function getURLtoBlob(dataURI) {
    let blobData;
    await getBase64Image(dataURI).then(
        function (base64image) {
            blobData = base64image;
        },
        function (reason) {
            console.log(reason); // Error!
        }
    );

    return blobData;
}

async function getPostFormData() {
    let formData = new FormData();
    let webData = {};
    webData["webtitle"] = $("#webtitle").val();
    webData["webdescription"] = $("#webdescription").prev().html();
    webData["web_info"] = convertTableToArrayObject();
    webData["pos_image"] = posImage.src;
    webData["mainImg"] = avatar.src;
    webData["gallery1"] = avatar1.src;
    webData["gallery2"] = avatar2.src;
    webData["gallery3"] = avatar3.src;
    webData["gallery4"] = avatar4.src;
    // let dataUrl = avatar.src;
    // let blob;
    // if (dataUrl.includes("http")) {
    //     blob = await getURLtoBlob(dataUrl);
    // } else {
    //     blob = dataURItoBlob(dataUrl);
    // }
    // formData.append("mainImg", blob);
    //
    // dataUrl = avatar1.src;
    // if (dataUrl.includes("http")) {
    //     blob = await getURLtoBlob(dataUrl);
    // } else {
    //     blob = dataURItoBlob(dataUrl);
    // }
    // formData.append("gallery1", blob);
    //
    // dataUrl = avatar2.src;
    // if (dataUrl.includes("http")) {
    //     blob = await getURLtoBlob(dataUrl);
    // } else {
    //     blob = dataURItoBlob(dataUrl);
    // }
    // formData.append("gallery2", blob);
    //
    // dataUrl = avatar3.src;
    // if (dataUrl.includes("http")) {
    //     blob = await getURLtoBlob(dataUrl);
    // } else {
    //     blob = dataURItoBlob(dataUrl);
    // }
    // formData.append("gallery3", blob);
    //
    // dataUrl = avatar4.src;
    // if (dataUrl.includes("http")) {
    //     blob = await getURLtoBlob(dataUrl);
    // } else {
    //     blob = dataURItoBlob(dataUrl);
    // }
    // formData.append("gallery4", blob);
    formData.append("data", JSON.stringify(webData));
    return formData;
}

async function getFormData() {
    let formData = new FormData();
    let product_data = {};
    // get product data
    $(".product-input").each(function (index) {
        switch (index) {
            case 0:
                product_data["productname"] = $(this).val();
                break;
            case 1:
                product_data["barcode"] = $(this).val();
                break;
            case 2:
                product_data["barcode1"] = $(this).val();
                break;
            case 3:
                product_data["barcode2"] = $(this).val();
                break;
            case 4:
                product_data["maincat"] = $(this).val();
                break;
            case 5:
                product_data["subcat"] = $(this).val();
                break;
            case 6:
                product_data["gstfree"] = $(this).val();
                break;
            case 7:
                product_data["ismod"] = $(this).val();
                break;
            case 8:
                product_data["iskg"] = $(this).val();
                break;
            case 9:
                product_data["baseprice"] = parseFloat($(this).val()).toFixed(
                    2
                );
                break;
            case 10:
                product_data["tier1"] = parseFloat($(this).val()).toFixed(2);
                break;
            case 11:
                product_data["tier2"] = parseFloat($(this).val()).toFixed(2);
                break;
            case 12:
                product_data["tier3"] = parseFloat($(this).val()).toFixed(2);
                break;
            case 13:
                product_data["tier4"] = parseFloat($(this).val()).toFixed(2);
                break;
            case 14:
                product_data["tier5"] = parseFloat($(this).val()).toFixed(2);
                break;
            case 15:
                product_data["basecost"] = parseFloat($(this).val()).toFixed(2);
                break;
            default:
                break;
        }
    });

    if (availableBuilder.val() === "off") {
        product_data["builder_available"] = "off";
        product_data["alloc_stock"] = allocId;
    } else {
        product_data["builder_available"] = "on";
        product_data["alloc_stock"] = "0";
    }

    product_data["allocatedstock"] = stock_data;
    product_data["syncid"] = "";
    // get product web information data
    product_data["webtitle"] = $("#webtitle").val();
    product_data["webdescription"] = $("#webdescription").prev().html();
    product_data["web_info"] = convertTableToArrayObject();

    let dataUrl = posImage.src;
    let blob;

    if (dataUrl.includes("http")) {
        blob = await getURLtoBlob(dataUrl);
    } else {
        blob = dataURItoBlob(dataUrl);
    }
    formData.append("pos-image", blob);

    dataUrl = avatar.src;

    if (dataUrl.includes("http")) {
        blob = await getURLtoBlob(dataUrl);
    } else {
        blob = dataURItoBlob(dataUrl);
    }
    formData.append("mainImg", blob);

    dataUrl = avatar1.src;
    if (dataUrl.includes("http")) {
        blob = await getURLtoBlob(dataUrl);
    } else {
        blob = dataURItoBlob(dataUrl);
    }
    formData.append("gallery1", blob);

    dataUrl = avatar2.src;
    if (dataUrl.includes("http")) {
        blob = await getURLtoBlob(dataUrl);
    } else {
        blob = dataURItoBlob(dataUrl);
    }
    formData.append("gallery2", blob);

    dataUrl = avatar3.src;
    if (dataUrl.includes("http")) {
        blob = await getURLtoBlob(dataUrl);
    } else {
        blob = dataURItoBlob(dataUrl);
    }
    formData.append("gallery3", blob);

    dataUrl = avatar4.src;
    if (dataUrl.includes("http")) {
        blob = await getURLtoBlob(dataUrl);
    } else {
        blob = dataURItoBlob(dataUrl);
    }
    formData.append("gallery4", blob);

    product_data["webmapid"] = $("#webmapid").val();

    let price_data = {};
    let priceArray = [];
    $(".productrow").each(function (index) {
        priceArray.push({
            storeid: "" + $(this).find("input").eq(0).data("id") + "",
            productprice: parseFloat($(this).find("input").eq(0).val()).toFixed(
                2
            ),
            producttier1: parseFloat($(this).find("input").eq(1).val()).toFixed(
                2
            ),
            producttier2: parseFloat($(this).find("input").eq(2).val()).toFixed(
                2
            ),
            producttier3: parseFloat($(this).find("input").eq(3).val()).toFixed(
                2
            ),
            producttier4: parseFloat($(this).find("input").eq(4).val()).toFixed(
                2
            ),
            producttier5: parseFloat($(this).find("input").eq(5).val()).toFixed(
                2
            ),
            syncid: "LOC" + $(this).find("input").eq(0).data("id") + "",
        });
    });
    price_data["prices"] = priceArray;

    let costArray = [];
    $(".productcost").each(function (index) {
        costArray.push({
            storeid: "" + $(this).data("id") + "",
            productcost: parseFloat($(this).val()).toFixed(2),
        });
    });
    price_data["costs"] = costArray;

    let post_data = {};
    post_data["product_data"] = product_data;
    post_data["price_data"] = price_data;
    // if (canvas !== undefined){
    //     let dataUrl = canvas.toDataURL("image/jpeg");
    //
    //     let blob = dataURItoBlob(dataUrl);
    //
    //     formData.append('webimage', blob, 'avatar.jpg');
    // }

    formData.append("data", JSON.stringify(post_data));
    formData.append("_token", csrfToken);
    return formData;
}

async function getPutFormData() {
    let formData = new FormData();
    let product_data = {};
    let data_array = {};
    // get product data
    $(".product-input").each(function (index) {
        switch (index) {
            case 0:
                product_data["productname"] = $(this).val();
                break;
            case 1:
                product_data["barcode"] = $(this).val();
                break;
            case 2:
                product_data["barcode1"] = $(this).val();
                break;
            case 3:
                product_data["barcode2"] = $(this).val();
                break;
            case 4:
                product_data["maincat"] = $(this).val();
                break;
            case 5:
                product_data["subcat"] = $(this).val();
                break;
            case 6:
                product_data["gstfree"] = $(this).val();
                break;
            case 7:
                product_data["ismod"] = $(this).val();
                break;
            case 8:
                product_data["iskg"] = $(this).val();
                break;
            case 9:
                product_data["baseprice"] = parseFloat($(this).val()).toFixed(
                    2
                );
                break;
            case 10:
                product_data["tier1"] = parseFloat($(this).val()).toFixed(2);
                break;
            case 11:
                product_data["tier2"] = parseFloat($(this).val()).toFixed(2);
                break;
            case 12:
                product_data["tier3"] = parseFloat($(this).val()).toFixed(2);
                break;
            case 13:
                product_data["tier4"] = parseFloat($(this).val()).toFixed(2);
                break;
            case 14:
                product_data["tier5"] = parseFloat($(this).val()).toFixed(2);
                break;
            case 15:
                product_data["basecost"] = parseFloat($(this).val()).toFixed(2);
                break;
            default:
                break;
        }
    });

    if (availableBuilder.val() === "off") {
        product_data["builder_available"] = "off";
        product_data["alloc_stock"] = allocId;
    } else {
        product_data["builder_available"] = "on";
        product_data["alloc_stock"] = "0";
    }

    product_data["allocatedstock"] = stock_data;
    product_data["syncid"] = "";
    // get product web information data
    product_data["webtitle"] = $("#webtitle").val();
    product_data["webdescription"] = $("#webdescription").prev().html();
    product_data["web_info"] = convertTableToArrayObject();

    let dataUrl = posImage.src;
    let blob;

    if (dataUrl.includes("http")) {
        blob = await getURLtoBlob(dataUrl);
    } else {
        blob = dataURItoBlob(dataUrl);
    }
    formData.append("pos-image", blob);

    dataUrl = avatar.src;

    if (dataUrl.includes("http")) {
        blob = await getURLtoBlob(dataUrl);
    } else {
        blob = dataURItoBlob(dataUrl);
    }
    formData.append("mainImg", blob);

    dataUrl = avatar1.src;
    if (dataUrl.includes("http")) {
        blob = await getURLtoBlob(dataUrl);
    } else {
        blob = dataURItoBlob(dataUrl);
    }
    formData.append("gallery1", blob);

    dataUrl = avatar2.src;
    if (dataUrl.includes("http")) {
        blob = await getURLtoBlob(dataUrl);
    } else {
        blob = dataURItoBlob(dataUrl);
    }
    formData.append("gallery2", blob);

    dataUrl = avatar3.src;
    if (dataUrl.includes("http")) {
        blob = await getURLtoBlob(dataUrl);
    } else {
        blob = dataURItoBlob(dataUrl);
    }
    formData.append("gallery3", blob);

    dataUrl = avatar4.src;
    if (dataUrl.includes("http")) {
        blob = await getURLtoBlob(dataUrl);
    } else {
        blob = dataURItoBlob(dataUrl);
    }
    formData.append("gallery4", blob);

    product_data["webmapid"] = $("#webmapid").val();

    let price_data = {};
    let priceArray = [];
    $(".productrow").each(function (index) {
        priceArray.push({
            storeid: "" + $(this).find("input").eq(0).data("id") + "",
            productprice: parseFloat($(this).find("input").eq(0).val()).toFixed(
                2
            ),
            producttier1: parseFloat($(this).find("input").eq(1).val()).toFixed(
                2
            ),
            producttier2: parseFloat($(this).find("input").eq(2).val()).toFixed(
                2
            ),
            producttier3: parseFloat($(this).find("input").eq(3).val()).toFixed(
                2
            ),
            producttier4: parseFloat($(this).find("input").eq(4).val()).toFixed(
                2
            ),
            producttier5: parseFloat($(this).find("input").eq(5).val()).toFixed(
                2
            ),
            syncid: "LOC" + $(this).find("input").eq(0).data("id") + "",
        });
    });

    price_data["prices"] = priceArray;

    let costArray = [];
    $(".productcost").each(function (index) {
        costArray.push({
            storeid: "" + $(this).data("id") + "",
            productcost: parseFloat($(this).val()).toFixed(2),
        });
    });
    price_data["costs"] = costArray;

    let post_data = {};
    post_data["product_data"] = product_data;
    post_data["price_data"] = price_data;

    data_array["c_id"] = tempId;
    data_array["c_data"] = post_data;

    formData.append("data", JSON.stringify(data_array));
    formData.append("_token", csrfToken);

    return formData;
}

async function postServer() {
    $.ajax({
        type: "POST",
        url: wpUrl,
        data: await getPostFormData(),
        processData: false,
        contentType: false,
        success: function (data) {
            toastr.success("add post event success", "Success", {
                timeOut: 3000,
            });
        },
        error: function () {
            toastr.error("add event failed", "error", { timeOut: 3000 });
        },
    });
}

// if cost builder is off, post data to stock table
async function saveToStock() {
    let formData = {};
    let stockData = {};
    let allocatedId;

    $(".product-input").each(function (index) {
        switch (index) {
            case 0:
                stockData["stockname"] = $(this).val();
                break;
            case 1:
                stockData["barcode"] = $(this).val();
                break;
            case 2:
                stockData["barcode1"] = $(this).val();
                break;
            case 3:
                stockData["barcode2"] = $(this).val();
                break;
            case 4:
                stockData["maincat"] = $("#maincat option:selected").text();
                break;
            case 5:
                stockData["subcat"] = $("#subcat option:selected").text();
                break;
            case 8:
                if ($(this).val() === "on") {
                    stockData["stockoption"] = "kgs";
                } else {
                    stockData["stockoption"] = "each";
                }
                stockData["unitval"] = 1;
                break;
            case 15:
                stockData["baseprice"] = parseFloat($(this).val()).toFixed(2);
                break;
            case 16:
                stockData["baseqty"] = parseFloat($(this).val()).toFixed(2);
                break;
            default:
                break;
        }
    });

    formData["stock_data"] = stockData;

    let costArray = [];
    $(".productcost").each(function (index) {
        costArray.push({
            storeid: "" + $(this).data("id") + "",
            stockprice: parseFloat($(this).val()).toFixed(2),
        });
    });
    formData["price_data"] = costArray;

    let qtyArray = [];
    $(".productsoh").each(function (index) {
        qtyArray.push({
            storeid: "" + $(this).data("id") + "",
            stockqty: parseFloat($(this).val()).toFixed(2),
        });
    });
    formData["qty_data"] = qtyArray;

    $.ajax({
        type: "POST",
        url: "/products/api/save-stock",
        data: formData,
        async: false,
        success: function (data) {
            if (data.status === "success") {
                toastr.success("save stock success", "Success", {
                    timeOut: 3000,
                });
                allocatedId = data.data;
            } else {
                toastr.error("save stock failed", "error", { timeOut: 3000 });
            }
        },
        error: function () {
            toastr.error("add event failed", "error", { timeOut: 3000 });
        },
    });

    return allocatedId;
}

stockSelect.on("change", function () {
    if (
        $(this).val() === "no" ||
        $(this).val() === "0" ||
        $(this).val() === null
    ) {
        $("#basesoh").val("0");
        $(".productsoh").each(function (index) {
            $(this).val("0");
        });
    } else {
        allocId = $(this).val();
        $.ajax({
            url: "/products/builder-api",
            data: {
                id: allocId,
            },
            method: "GET",
        })
            .done(function (data) {
                if (data.status === "success") {
                    let stockData = data.data;
                    let sohData = stockData["soh"];
                    $("#basesoh").val(stockData["stock"][0]["baseqty"]);
                    $(".productsoh").each(function (index) {
                        let loc_id = $(this).data("id");
                        for (let soh of sohData) {
                            if (loc_id === parseInt(soh["storeid"])) {
                                $(this).val(soh["stockqty"]);
                            }
                        }
                    });
                } else {
                    toastr.error("get event failed", "error", {
                        timeOut: 3000,
                    });
                }
            })
            .fail(function (jqXHR, status, errorThrown) {
                alert(status + "<br>" + errorThrown);
            });
    }
});

async function saveProduct() {
    $('[data-bs-toggle="popover"]').popover("hide");
    saveButton.hide();
    loadButton.show();

    if (mainCat.val() === "no") {
        toastr.error("Please select a Main Catagory!", "error", {
            timeOut: 3000,
        });
        $("#home-tab").trigger("click");
        loadButton.hide();
        saveButton.show();
        return;
    }
    if (subCat.val() === "no") {
        toastr.error("Please select a Sub Catagory!", "error", {
            timeOut: 3000,
        });
        $("#home-tab").trigger("click");
        loadButton.hide();
        saveButton.show();
        return;
    }
    if ($("#productname").val() === "") {
        toastr.error("Please input Product Name!", "error", { timeOut: 3000 });
        $("#home-tab").trigger("click");
        loadButton.hide();
        saveButton.show();
        return;
    }

    // show SOH field and get allocated ID

    if (availableBuilder.val() === "off") {
        if (stockSelect.val() === "no" || stockSelect.val() === "0") {
            allocId = await saveToStock();
            // create the option and append to Select2
            let option = new Option(
                $("#productname").val(),
                allocId,
                true,
                true
            );
            stockSelect.append(option).trigger("change");
        }
    }

    // if ($('#barcode').val() === "") {
    //     toastr.error('Please input barcode!', 'error', {timeOut: 3000});
    //     $('#home-tab').trigger('click');
    //     return;
    // }
    // if ($('#webtitle').val() === "") {
    //     toastr.error('Please input title!', 'error', {timeOut: 3000});
    //     $('#information-tab').trigger('click');
    //     return;
    // }
    // if (editor.val.get() === "") {
    //     toastr.error('Please input description!', 'error', {timeOut: 3000});
    //     $('#information-tab').trigger('click');
    //     return;
    // }
    // if ($('#webmapid').val() === "") {
    //     toastr.error('Please input Mapped ID!', 'error', {timeOut: 3000});
    //     $('#information-tab').trigger('click');
    //     return;
    // }
    $.ajax({
        type: "POST",
        url: "/products/api",
        data: await getFormData(),
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.status === "success") {
                toastr.success("save product success", "Success", {
                    timeOut: 3000,
                });
                $("#status_show").val("all");
                $(".profile-btn-snd").click();
                $("#example1")
                    .DataTable()
                    .ajax.url("products/api?status=all")
                    .load();
                loadButton.hide();
                saveButton.show();
            } else {
                toastr.error("save product failed", "error", { timeOut: 3000 });
                loadButton.hide();
                saveButton.show();
            }
        },
        error: function () {
            toastr.error("add event failed", "error", { timeOut: 3000 });
            loadButton.hide();
            saveButton.show();
        },
    });
}

async function updateProduct() {
    $('[data-bs-toggle="popover"]').popover("hide");
    updateButton.hide();
    // updateButton.attr('disabled', 'disabled');
    loadButton.show();
    if (mainCat.val() === "no") {
        toastr.error("Please select a Main Catagory!", "error", {
            timeOut: 3000,
        });
        $("#home-tab").trigger("click");
        // updateButton.attr('disabled', false);
        loadButton.hide();
        updateButton.show();
        return;
    }
    if (subCat.val() === "no") {
        toastr.error("Please select a Sub Catagory!", "error", {
            timeOut: 3000,
        });
        $("#home-tab").trigger("click");
        // updateButton.attr('disabled', false);
        loadButton.hide();
        updateButton.show();
        return;
    }
    if ($("#productname").val() === "") {
        toastr.error("Please input Product Name!", "error", { timeOut: 3000 });
        $("#home-tab").trigger("click");
        // updateButton.attr('disabled', false);
        loadButton.hide();
        updateButton.show();
        return;
    }

    // show SOH field and get allocated ID

    if (availableBuilder.val() === "off") {
        if (stockSelect.val() === "no" || stockSelect.val() === "0") {
            allocId = await saveToStock();
        }
    }
    // if ($('#barcode').val() === "") {
    //     toastr.error('Please input barcode!', 'error', {timeOut: 3000});
    //     $('#home-tab').trigger('click');
    //     return;
    // }
    //
    // if ($('#webtitle').val() === "") {
    //     toastr.error('Please input title!', 'error', {timeOut: 3000});
    //     $('#information-tab').trigger('click');
    //     return;
    // }
    // if (editor.val.get() === "") {
    //     toastr.error('Please input description!', 'error', {timeOut: 3000});
    //     $('#information-tab').trigger('click');
    //     return;
    // }
    // if ($('#webmapid').val() === "") {
    //     toastr.error('Please input Mapped ID!', 'error', {timeOut: 3000});
    //     $('#information-tab').trigger('click');
    //     return;
    // }
    $.ajax({
        type: "POST",
        url: "/products/api",
        data: await getPutFormData(),
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.status === "success") {
                toastr.success("save product success", "Success", {
                    timeOut: 3000,
                });
                $("#status_show").val("all");

                $(".profile-btn-snd").click();
                $("#example1")
                    .DataTable()
                    .ajax.url("products/api?status=all")
                    .load();
                // updateButton.attr('disabled', false);
                loadButton.hide();
                updateButton.show();
            } else {
                toastr.error("save product failed", "error", { timeOut: 3000 });
                loadButton.hide();
                updateButton.show();
            }
        },
        error: function () {
            toastr.error("add event failed", "error", { timeOut: 3000 });
            loadButton.hide();
            updateButton.show();
        },
    });
    // updateButton.attr('disabled', false);
    // loadButton.hide();
}

function copyTitle() {
    $("#webtitle").val($("#productname").val());
}

function setPercent(elm) {
    let pro_cost = elm.value;
    let pro_price = $(elm).parent().parent().find("input").eq(0).val();
    let price_tier1 = $(elm).parent().parent().find("input").eq(1).val();
    let price_tier2 = $(elm).parent().parent().find("input").eq(2).val();
    let price_tier3 = $(elm).parent().parent().find("input").eq(3).val();
    let price_tier4 = $(elm).parent().parent().find("input").eq(4).val();
    let price_tier5 = $(elm).parent().parent().find("input").eq(5).val();
    if (
        parseFloat(pro_price) !== 0 &&
        parseFloat(pro_cost) !== 0 &&
        !isNaN(parseFloat(pro_price)) &&
        !isNaN(parseFloat(pro_cost))
    ) {
        let percent = 100 - (pro_cost * 100) / pro_price;
        $(elm)
            .parent()
            .parent()
            .find("span")
            .eq(0)
            .text("GP " + percent.toFixed(2) + "%");
    } else {
        $(elm).parent().parent().find("span").eq(0).text("GP % ");
    }

    if (
        parseFloat(price_tier1) !== 0 &&
        parseFloat(pro_cost) !== 0 &&
        !isNaN(parseFloat(price_tier1)) &&
        !isNaN(parseFloat(pro_cost))
    ) {
        let percent = 100 - (pro_cost * 100) / price_tier1;
        $(elm)
            .parent()
            .parent()
            .find("span")
            .eq(1)
            .text("GP " + percent.toFixed(2) + "%");
    } else {
        $(elm).parent().parent().find("span").eq(1).text("GP % ");
    }

    if (
        parseFloat(price_tier2) !== 0 &&
        parseFloat(pro_cost) !== 0 &&
        !isNaN(parseFloat(price_tier2)) &&
        !isNaN(parseFloat(pro_cost))
    ) {
        let percent = 100 - (pro_cost * 100) / price_tier2;
        $(elm)
            .parent()
            .parent()
            .find("span")
            .eq(2)
            .text("GP " + percent.toFixed(2) + "%");
    } else {
        $(elm).parent().parent().find("span").eq(2).text("GP % ");
    }

    if (
        parseFloat(price_tier3) !== 0 &&
        parseFloat(pro_cost) !== 0 &&
        !isNaN(parseFloat(price_tier3)) &&
        !isNaN(parseFloat(pro_cost))
    ) {
        let percent = 100 - (pro_cost * 100) / price_tier3;
        $(elm)
            .parent()
            .parent()
            .find("span")
            .eq(3)
            .text("GP " + percent.toFixed(2) + "%");
    } else {
        $(elm).parent().parent().find("span").eq(3).text("GP % ");
    }

    if (
        parseFloat(price_tier4) !== 0 &&
        parseFloat(pro_cost) !== 0 &&
        !isNaN(parseFloat(price_tier4)) &&
        !isNaN(parseFloat(pro_cost))
    ) {
        let percent = 100 - (pro_cost * 100) / price_tier4;
        $(elm)
            .parent()
            .parent()
            .find("span")
            .eq(4)
            .text("GP " + percent.toFixed(2) + "%");
    } else {
        $(elm).parent().parent().find("span").eq(4).text("GP % ");
    }

    if (
        parseFloat(price_tier5) !== 0 &&
        parseFloat(pro_cost) !== 0 &&
        !isNaN(parseFloat(price_tier5)) &&
        !isNaN(parseFloat(pro_cost))
    ) {
        let percent = 100 - (pro_cost * 100) / price_tier2;
        $(elm)
            .parent()
            .parent()
            .find("span")
            .eq(5)
            .text("GP " + percent.toFixed(2) + "%");
    } else {
        $(elm).parent().parent().find("span").eq(5).text("GP % ");
    }
}
// when click SET Price button
function copyBasePrice() {
    let basePriceVal = $(".popover-body").find("#set_price_option").val();
    if (isNaN(parseFloat(basePriceVal))) {
        toastr.error("please input correct value", "warning", {
            timeOut: 3000,
        });
    } else {
        $(setPriceClass).each(function (index) {
            $(this).val(parseFloat(basePriceVal).toFixed(2));
            if (setPriceClass === ".productcost") {
                setPercent(this);
            } else {
                let pro_price = this.value;
                let pro_cost = $(this)
                    .parent()
                    .parent()
                    .find("input")
                    .eq(6)
                    .val();
                if (
                    parseFloat(pro_price) !== 0 &&
                    parseFloat(pro_cost) !== 0 &&
                    !isNaN(parseFloat(pro_price)) &&
                    !isNaN(parseFloat(pro_cost))
                ) {
                    let percent = 100 - (pro_cost * 100) / pro_price;
                    $(this)
                        .next()
                        .text("GP " + percent.toFixed(2) + "%");
                } else {
                    $(this).next().text("GP % ");
                }
            }
        });
        $('[data-bs-toggle="popover"]').popover("hide");
    }
}

function setIncreasePercent() {
    let IncreasePercent = $(".popover-body").find("#set_price_option").val();
    if (isNaN(parseFloat(IncreasePercent))) {
        toastr.error("please input correct value", "warning", {
            timeOut: 3000,
        });
    } else {
        $(setPriceClass).each(function (index) {
            $(this).val(
                parseFloat(
                    (this.value * (100 + parseFloat(IncreasePercent))) / 100
                ).toFixed(2)
            );
            if (setPriceClass === ".productcost") {
                setPercent(this);
            } else {
                let pro_price = this.value;
                let pro_cost = $(this)
                    .parent()
                    .parent()
                    .find("input")
                    .eq(6)
                    .val();
                if (
                    parseFloat(pro_price) !== 0 &&
                    parseFloat(pro_cost) !== 0 &&
                    !isNaN(parseFloat(pro_price)) &&
                    !isNaN(parseFloat(pro_cost))
                ) {
                    let percent = 100 - (pro_cost * 100) / pro_price;
                    $(this)
                        .next()
                        .text("GP " + percent.toFixed(2) + "%");
                } else {
                    $(this).next().text("GP % ");
                }
            }
        });
        $('[data-bs-toggle="popover"]').popover("hide");
    }
}

function setDecreasePercent() {
    let DecreasePercent = $(".popover-body").find("#set_price_option").val();
    if (isNaN(parseFloat(DecreasePercent))) {
        toastr.error("please input correct value", "warning", {
            timeOut: 3000,
        });
    } else {
        $(setPriceClass).each(function (index) {
            $(this).val(
                parseFloat(
                    (this.value * (100 - parseFloat(DecreasePercent))) / 100
                ).toFixed(2)
            );
            if (setPriceClass === ".productcost") {
                setPercent(this);
            } else {
                let pro_price = this.value;
                let pro_cost = $(this)
                    .parent()
                    .parent()
                    .find("input")
                    .eq(6)
                    .val();
                if (
                    parseFloat(pro_price) !== 0 &&
                    parseFloat(pro_cost) !== 0 &&
                    !isNaN(parseFloat(pro_price)) &&
                    !isNaN(parseFloat(pro_cost))
                ) {
                    let percent = 100 - (pro_cost * 100) / pro_price;
                    $(this)
                        .next()
                        .text("GP " + percent.toFixed(2) + "%");
                } else {
                    $(this).next().text("GP % ");
                }
            }
        });
        $('[data-bs-toggle="popover"]').popover("hide");
    }
}

function setIncreaseVal() {
    let increaseVal = $(".popover-body").find("#set_price_option").val();
    if (isNaN(parseFloat(increaseVal))) {
        toastr.error("please input correct value", "warning", {
            timeOut: 3000,
        });
    } else {
        $(setPriceClass).each(function (index) {
            $(this).val(
                parseFloat(
                    parseFloat(this.value) + parseFloat(increaseVal)
                ).toFixed(2)
            );
            if (setPriceClass === ".productcost") {
                setPercent(this);
            } else {
                let pro_price = this.value;
                let pro_cost = $(this)
                    .parent()
                    .parent()
                    .find("input")
                    .eq(6)
                    .val();
                if (
                    parseFloat(pro_price) !== 0 &&
                    parseFloat(pro_cost) !== 0 &&
                    !isNaN(parseFloat(pro_price)) &&
                    !isNaN(parseFloat(pro_cost))
                ) {
                    let percent = 100 - (pro_cost * 100) / pro_price;
                    $(this)
                        .next()
                        .text("GP " + percent.toFixed(2) + "%");
                } else {
                    $(this).next().text("GP % ");
                }
            }
        });
        $('[data-bs-toggle="popover"]').popover("hide");
    }
}

function setDecreaseVal() {
    let decreaseVal = $(".popover-body").find("#set_price_option").val();
    if (isNaN(parseFloat(decreaseVal))) {
        toastr.error("please input correct value", "warning", {
            timeOut: 3000,
        });
    } else {
        $(setPriceClass).each(function (index) {
            $(this).val(
                parseFloat(
                    parseFloat(this.value) - parseFloat(decreaseVal)
                ).toFixed(2)
            );
            if (setPriceClass === ".productcost") {
                setPercent(this);
            } else {
                let pro_price = this.value;
                let pro_cost = $(this)
                    .parent()
                    .parent()
                    .find("input")
                    .eq(6)
                    .val();
                if (
                    parseFloat(pro_price) !== 0 &&
                    parseFloat(pro_cost) !== 0 &&
                    !isNaN(parseFloat(pro_price)) &&
                    !isNaN(parseFloat(pro_cost))
                ) {
                    let percent = 100 - (pro_cost * 100) / pro_price;
                    $(this)
                        .next()
                        .text("GP " + percent.toFixed(2) + "%");
                } else {
                    $(this).next().text("GP % ");
                }
            }
        });
        $('[data-bs-toggle="popover"]').popover("hide");
    }
}

// get product info and show all info product
function editProduct(id) {
    //alert('edit');
    // $('#plu').prop('disabled', true);
    tempId = id;
    saveButton.hide();
    updateButton.show();
    $("#temptitle").html("Edit Product");
    $.ajax({
        url: "/products/api",
        data: {
            id: id,
        },
        method: "GET",
    })
        .done(function (data) {
            if (data.status === "success") {
                let productData = data.data.product_data;
                // $('#plu').show();
                //alert (JSON.stringify(data[0]));
                $("#productname").val(productData[0]["productname"]);
                // $('#plu').val(productData[0]['sku']);
                $("#barcode").val(productData[0]["barcode"]);
                $("#barcode1").val(productData[0]["barcode1"]);
                $("#barcode2").val(productData[0]["barcode2"]);
                if (productData[0]["gstfree"] === "1") {
                    $("#gstfreebox").prop("checked", true);
                    $("#gstfree").val(productData[0]["gstfree"]);
                } else {
                    $("#gstfreebox").prop("checked", false);
                    $("#gstfree").val(productData[0]["gstfree"]);
                }
                if (productData[0]["ismod"] === "1") {
                    $("#ismodbox").prop("checked", true);
                    $("#ismod").val(productData[0]["ismod"]);
                } else {
                    $("#ismodbox").prop("checked", false);
                    $("#ismod").val(productData[0]["ismod"]);
                }
                if (productData[0]["iskg"] === "1") {
                    $("#iskgbox").prop("checked", true);
                    $("#iskg").val(productData[0]["iskg"]);
                } else {
                    $("#iskgbox").prop("checked", false);
                    $("#iskg").val(productData[0]["iskg"]);
                }
                basePrice.val(productData[0]["baseprice"]);
                tier1.val(productData[0]["tier1"]);
                tier2.val(productData[0]["tier2"]);
                tier3.val(productData[0]["tier3"]);
                tier4.val(productData[0]["tier4"]);
                tier5.val(productData[0]["tier5"]);
                baseCost.val(productData[0]["basecost"]);

                if (productData[0]["builder_available"] === "on") {
                    availableBuilder.attr(
                        "value",
                        productData[0]["builder_available"]
                    );
                    availableBuilder.attr("checked", true);
                    availableBuilder.prop("checked", true);
                    builderTab.show();
                    allocateField.hide();
                    if (productData[0]["allocatedstock"].length !== 0) {
                        $.ajax({
                            type: "POST",
                            url: "/products/cost-api",
                            data: {
                                data: productData[0]["allocatedstock"],
                            },
                            success: function (data) {
                                if (data.status === "success") {
                                    let allData = data.data;
                                    let cost_data = allData["costData"];
                                    let soh_data = allData["sohData"];

                                    $("#basesoh").val(
                                        parseFloat(soh_data[0]).toFixed(2)
                                    );
                                    $(".productsoh").each(function (index) {
                                        let location_id = $(this).data("id");
                                        $(this).val(
                                            parseFloat(
                                                soh_data[location_id]
                                            ).toFixed(2)
                                        );
                                    });
                                } else {
                                    toastr.error(
                                        "save product failed",
                                        "error",
                                        { timeOut: 3000 }
                                    );
                                }
                            },
                            error: function () {
                                toastr.error("add event failed", "error", {
                                    timeOut: 3000,
                                });
                            },
                        });
                    }
                } else {
                    availableBuilder.attr(
                        "value",
                        productData[0]["builder_available"]
                    );
                    availableBuilder.attr("checked", false);
                    availableBuilder.prop("checked", false);
                    stockSelect.select2("val", productData[0]["alloc_stock"]);
                    builderTab.hide();
                    allocateField.show();
                    if (
                        productData[0]["alloc_stock"] === "" ||
                        productData[0]["alloc_stock"] === "0" ||
                        productData[0]["alloc_stock"] === null
                    ) {
                        $("#basesoh").val("0");
                        $(".productsoh").each(function (index) {
                            $(this).val("0");
                        });
                    } else {
                        $.ajax({
                            url: "/products/builder-api",
                            data: {
                                id: productData[0]["alloc_stock"],
                            },
                            method: "GET",
                        })
                            .done(function (data) {
                                if (data.status === "success") {
                                    let stockData = data.data;
                                    let sohData = stockData["soh"];
                                    $("#basesoh").val(
                                        stockData["stock"][0]["baseqty"]
                                    );
                                    $(".productsoh").each(function (index) {
                                        let loc_id = $(this).data("id");
                                        for (let soh of sohData) {
                                            if (
                                                loc_id ===
                                                parseInt(soh["storeid"])
                                            ) {
                                                $(this).val(soh["stockqty"]);
                                            }
                                        }
                                    });
                                } else {
                                    toastr.error("get event failed", "error", {
                                        timeOut: 3000,
                                    });
                                }
                            })
                            .fail(function (jqXHR, status, errorThrown) {
                                alert(status + "<br>" + errorThrown);
                            });
                    }
                }
                // set GP %

                if (
                    parseFloat(productData[0]["basecost"]) === 0 ||
                    isNaN(productData[0]["basecost"]) ||
                    parseFloat(productData[0]["baseprice"]) === 0 ||
                    isNaN(productData[0]["baseprice"])
                ) {
                    basePrice.next().next().text("GP % ");
                } else {
                    let percent =
                        100 -
                        (parseFloat(productData[0]["basecost"]) * 100) /
                            parseFloat(productData[0]["baseprice"]);
                    basePrice
                        .next()
                        .next()
                        .text("GP " + percent.toFixed(2) + "%");
                }

                if (
                    parseFloat(productData[0]["basecost"]) === 0 ||
                    isNaN(productData[0]["basecost"]) ||
                    parseFloat(productData[0]["tier1"]) === 0 ||
                    isNaN(productData[0]["tier1"])
                ) {
                    tier1.next().next().text("GP % ");
                } else {
                    let percent =
                        100 -
                        (parseFloat(productData[0]["basecost"]) * 100) /
                            parseFloat(productData[0]["tier1"]);
                    tier1
                        .next()
                        .next()
                        .text("GP " + percent.toFixed(2) + "%");
                }

                if (
                    parseFloat(productData[0]["basecost"]) === 0 ||
                    isNaN(productData[0]["basecost"]) ||
                    parseFloat(productData[0]["tier2"]) === 0 ||
                    isNaN(productData[0]["tier2"])
                ) {
                    tier2.next().next().text("GP % ");
                } else {
                    let percent =
                        100 -
                        (parseFloat(productData[0]["basecost"]) * 100) /
                            parseFloat(productData[0]["tier2"]);
                    tier2
                        .next()
                        .next()
                        .text("GP " + percent.toFixed(2) + "%");
                }

                if (
                    parseFloat(productData[0]["basecost"]) === 0 ||
                    isNaN(productData[0]["basecost"]) ||
                    parseFloat(productData[0]["tier3"]) === 0 ||
                    isNaN(productData[0]["tier3"])
                ) {
                    tier3.next().next().text("GP % ");
                } else {
                    let percent =
                        100 -
                        (parseFloat(productData[0]["basecost"]) * 100) /
                            parseFloat(productData[0]["tier3"]);
                    tier3
                        .next()
                        .next()
                        .text("GP " + percent.toFixed(2) + "%");
                }

                if (
                    parseFloat(productData[0]["basecost"]) === 0 ||
                    isNaN(productData[0]["basecost"]) ||
                    parseFloat(productData[0]["tier4"]) === 0 ||
                    isNaN(productData[0]["tier4"])
                ) {
                    tier4.next().next().text("GP % ");
                } else {
                    let percent =
                        100 -
                        (parseFloat(productData[0]["basecost"]) * 100) /
                            parseFloat(productData[0]["tier4"]);
                    tier4
                        .next()
                        .next()
                        .text("GP " + percent.toFixed(2) + "%");
                }

                if (
                    parseFloat(productData[0]["basecost"]) === 0 ||
                    isNaN(productData[0]["basecost"]) ||
                    parseFloat(productData[0]["tier5"]) === 0 ||
                    isNaN(productData[0]["tier5"])
                ) {
                    tier5.next().next().text("GP % ");
                } else {
                    let percent =
                        100 -
                        (parseFloat(productData[0]["basecost"]) * 100) /
                            parseFloat(productData[0]["tier5"]);
                    tier5
                        .next()
                        .next()
                        .text("GP " + percent.toFixed(2) + "%");
                }

                $("#webtitle").val(productData[0]["webtitle"]);
                $("#webdescription")
                    .prev()
                    .html(productData[0]["webdescription"]);
                posImage.src = productData[0]["pos_image"];
                avatar.src = productData[0]["main_image"];
                avatar1.src = productData[0]["gallery1"];
                avatar2.src = productData[0]["gallery2"];
                avatar3.src = productData[0]["gallery3"];
                avatar4.src = productData[0]["gallery4"];

                builder.clear().destroy();
                builder = $("#cost-builder").DataTable({
                    data: productData[0]["allocatedstock"],
                    columnDefs: [
                        {
                            targets: 0,
                            data: function (row) {
                                return buildDropdown(stock_list, row[0]);
                            },
                        },
                        {
                            targets: 1,
                            class: "editable",
                        },
                    ],
                });

                // web information table initialize.
                let webInfoData = productData[0]["web_info"];
                if (webInfoData.length !== 0) {
                    webInfo.clear().destroy();
                    webInfo = $("#web-info").DataTable({
                        // stateSave: true,
                        data: webInfoData,
                        columns: [
                            { data: "locationname" },
                            {
                                data: null,
                                targets: -1,
                                createdCell: function (
                                    td,
                                    cellData,
                                    rowData,
                                    row,
                                    col
                                ) {
                                    $(td).css("display", "flex");
                                },
                            },
                            {
                                data: null,
                                targets: -1,
                            },
                        ],
                        columnDefs: [
                            {
                                orderable: false,
                                targets: [1, 2],
                            },
                            {
                                targets: 1,
                                data: null,
                                render: function (data, type, full, meta) {
                                    data =
                                        ' <input class="form-control web-info-price" name="web_price" data-id=' +
                                        full["locationid"] +
                                        " value='" +
                                        full["price"] +
                                        '\'><button type="button" class="btn copy-price-web" data-bs-toggle="modal" data-bs-target="#exampleModal"><i title=\'Copy to price\' class="fa fa-copy"></i></button>';
                                    return data;
                                },
                            },
                            {
                                targets: 2,
                                data: null,
                                render: function (data, type, full, meta) {
                                    let statusCheck = "";
                                    if (full["available"] === "on") {
                                        statusCheck = "checked";
                                    }
                                    data =
                                        '<div class="col-md-6">' +
                                        '<div class="form-check form-switch" style="padding-top: 30px;">' +
                                        '<input class="form-check-input" type="checkbox" name="web_available" value=\'' +
                                        full["available"] +
                                        "' " +
                                        statusCheck +
                                        '><label class="form-check-label" for="web_available"></label></div>';
                                    return data;
                                },
                            },
                        ],
                    });
                }

                $("#webmapid").val(productData[0]["webmapid"]);

                $(
                    '#maincat option[value="' + productData[0]["maincat"] + '"]'
                ).attr("selected", "selected");
                $(
                    '#subcat option[value="' + productData[0]["subcat"] + '"]'
                ).attr("selected", "selected");
                let pricingData = data.data.pricing_data;

                let costingData = data.data.cost_data;
                if (pricingData.length !== 0) {
                    $(".productrow").each(function (index) {
                        let dataId = $(this).find("input").eq(0).data("id");
                        for (let data of pricingData) {
                            if (dataId === parseInt(data.storeid)) {
                                let idx = pricingData.indexOf(data);
                                let pro_cost = costingData[idx].productcost;
                                $(this)
                                    .find("input")
                                    .eq(0)
                                    .val(data.productprice);
                                $(this)
                                    .find("input")
                                    .eq(1)
                                    .val(data.producttier1);
                                $(this)
                                    .find("input")
                                    .eq(2)
                                    .val(data.producttier2);
                                $(this)
                                    .find("input")
                                    .eq(3)
                                    .val(data.producttier3);
                                $(this)
                                    .find("input")
                                    .eq(4)
                                    .val(data.producttier4);
                                $(this)
                                    .find("input")
                                    .eq(5)
                                    .val(data.producttier5);
                                // set GP %
                                let percent;
                                if (
                                    parseFloat(pro_cost) === 0 ||
                                    isNaN(pro_cost) ||
                                    parseFloat(data.productprice) === 0 ||
                                    isNaN(data.productprice)
                                ) {
                                    $(this)
                                        .find("input")
                                        .eq(0)
                                        .next()
                                        .text("GP % ");
                                } else {
                                    percent =
                                        100 -
                                        (parseFloat(pro_cost) * 100) /
                                            parseFloat(data.productprice);
                                    $(this)
                                        .find("input")
                                        .eq(0)
                                        .next()
                                        .text("GP " + percent.toFixed(2) + "%");
                                }

                                if (
                                    parseFloat(pro_cost) === 0 ||
                                    isNaN(pro_cost) ||
                                    parseFloat(data.producttier1) === 0 ||
                                    isNaN(data.producttier1)
                                ) {
                                    $(this)
                                        .find("input")
                                        .eq(1)
                                        .next()
                                        .text("GP % ");
                                } else {
                                    percent =
                                        100 -
                                        (parseFloat(pro_cost) * 100) /
                                            parseFloat(data.producttier1);
                                    $(this)
                                        .find("input")
                                        .eq(1)
                                        .next()
                                        .text("GP " + percent.toFixed(2) + "%");
                                }

                                if (
                                    parseFloat(pro_cost) === 0 ||
                                    isNaN(pro_cost) ||
                                    parseFloat(data.producttier2) === 0 ||
                                    isNaN(data.producttier2)
                                ) {
                                    $(this)
                                        .find("input")
                                        .eq(2)
                                        .next()
                                        .text("GP % ");
                                } else {
                                    percent =
                                        100 -
                                        (parseFloat(pro_cost) * 100) /
                                            parseFloat(data.producttier2);
                                    $(this)
                                        .find("input")
                                        .eq(2)
                                        .next()
                                        .text("GP " + percent.toFixed(2) + "%");
                                }

                                if (
                                    parseFloat(pro_cost) === 0 ||
                                    isNaN(pro_cost) ||
                                    parseFloat(data.producttier3) === 0 ||
                                    isNaN(data.producttier3)
                                ) {
                                    $(this)
                                        .find("input")
                                        .eq(3)
                                        .next()
                                        .text("GP % ");
                                } else {
                                    percent =
                                        100 -
                                        (parseFloat(pro_cost) * 100) /
                                            parseFloat(data.producttier3);
                                    $(this)
                                        .find("input")
                                        .eq(3)
                                        .next()
                                        .text("GP " + percent.toFixed(2) + "%");
                                }

                                if (
                                    parseFloat(pro_cost) === 0 ||
                                    isNaN(pro_cost) ||
                                    parseFloat(data.producttier4) === 0 ||
                                    isNaN(data.producttier4)
                                ) {
                                    $(this)
                                        .find("input")
                                        .eq(4)
                                        .next()
                                        .text("GP % ");
                                } else {
                                    percent =
                                        100 -
                                        (parseFloat(pro_cost) * 100) /
                                            parseFloat(data.producttier4);
                                    $(this)
                                        .find("input")
                                        .eq(4)
                                        .next()
                                        .text("GP " + percent.toFixed(2) + "%");
                                }

                                if (
                                    parseFloat(pro_cost) === 0 ||
                                    isNaN(pro_cost) ||
                                    parseFloat(data.producttier5) === 0 ||
                                    isNaN(data.producttier5)
                                ) {
                                    $(this)
                                        .find("input")
                                        .eq(5)
                                        .next()
                                        .text("GP % ");
                                } else {
                                    percent =
                                        100 -
                                        (parseFloat(pro_cost) * 100) /
                                            parseFloat(data.producttier5);
                                    $(this)
                                        .find("input")
                                        .eq(5)
                                        .next()
                                        .text("GP " + percent.toFixed(2) + "%");
                                }
                            }
                        }
                    });

                    $(".productcost").each(function (index) {
                        let dataId = $(this).data("id");
                        for (let data of costingData) {
                            if (dataId === parseInt(data.storeid)) {
                                $(this).val(data.productcost);
                            }
                        }
                    });
                } else {
                    $(".productrow").each(function (index) {
                        $(this).find("input").eq(0).val("0.00");
                        $(this).find("input").eq(1).val("0.00");
                        $(this).find("input").eq(2).val("0.00");
                        $(this).find("input").eq(3).val("0.00");
                        $(this).find("input").eq(4).val("0.00");
                        $(this).find("input").eq(5).val("0.00");

                        $(this).find("input").eq(0).next().text("GP % ");
                        $(this).find("input").eq(1).next().text("GP % ");
                        $(this).find("input").eq(2).next().text("GP % ");
                        $(this).find("input").eq(3).next().text("GP % ");
                        $(this).find("input").eq(4).next().text("GP % ");
                        $(this).find("input").eq(4).next().text("GP % ");
                    });

                    $(".productcost").each(function (index) {
                        $(this).val("0.00");
                    });
                }
            } else {
                toastr.error("add event failed", "error", { timeOut: 3000 });
            }
        })
        .fail(function (jqXHR, status, errorThrown) {
            alert(status + "<br>" + errorThrown);
        });
}
