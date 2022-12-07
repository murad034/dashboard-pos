function save_changes() {
    let old_json_data = JSON.stringify({
        data: $("#droppable-layout-section").html(),
    });
    let rem_quo = old_json_data.replace(/\\"/gi, "`");
    let json_data = rem_quo.replace(/\\n/gi, "");
    let data_ref = $(".menu-selected").data("ref");
    let data_id = data_ref.replace(/\D/g, "");
    $.ajax({
        url: "/order-keypad-designer/builder/push_json",
        method: "POST",
        data:
            {
                id: data_id,
                data: json_data
            },
        success: function (data) {
            console.log("data uploaded");
            launch_toast();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $(".dest").html("");
            alert("Error Loading");
        },
    });
}
