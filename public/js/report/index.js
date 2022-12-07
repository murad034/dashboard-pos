function filter() {
    function formatDate(date) {
        let d = new Date(date),
            month = "" + (d.getMonth() + 1),
            day = "" + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = "0" + month;
        if (day.length < 2) day = "0" + day;

        return [year, month, day].join("-");
    }

    let reportselection = $(".report").val();
    let store = $(".store").val();
    let date = $(".js-daterangepicker").val();
    let myArray = date.split("-");
    let datefrom = new Date(myArray[0]);
    let dateto = new Date(myArray[1]);
    datefrom = formatDate(datefrom);
    dateto = formatDate(dateto);

    if (reportselection === "1") {
        return;
    }
    //    if (store == "1"){
    //      return;
    //    }

    jsreport.serverUrl = "https://reports.imreke.com.au:5488";
    jsreport.headers["Authorization"] =
        "Basic " + btoa("ausittechdirect:#Au5T3chGR0up#");

    async function beforeRender(req, res) {
        const report = await jsreport.render({
            template: { name: "/imreke/" + reportselection + "/main" },
            data: {
                database: databaseName,
                dateFrom: datefrom,
                dateTo: dateto,
                store: store,
            },
            options: { reports: { save: true } },
        });

        let result = report.toBlob();

        let promise = Promise.resolve(result);

        promise.then(function (val) {
            console.log(val);
            let url = window.URL.createObjectURL(val, {
                type: "application/pdf",
            });
            $("#pdf").attr("data", url);
        });
        // download the output to the file
        //report.download('myreport.pdf')
    }

    beforeRender();
}
