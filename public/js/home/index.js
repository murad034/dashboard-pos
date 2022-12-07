let chart_data;

function formatDate(date) {
    let d = new Date(date),
        month = "" + (d.getMonth() + 1),
        day = "" + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = "0" + month;
    if (day.length < 2) day = "0" + day;

    return [year, month, day].join("-");
}

$(document).ready(function () {
    let date = new Date();
    let firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
    let lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    $.ajax({
        url: "/dashboard/api",
        method: "GET",
        data: {
            id: 0,
            dateFrom: formatDate(firstDay),
            dateTo: formatDate(lastDay),
        },
        async: false,
        success: function (data) {
            if (data.status === "success") {
                chart_data = data.data;
                if (chart_data["totalsale"].length === 0) {
                    $("#total-sale").html("$0.00");
                    toastr.error("no data available", "error", {
                        timeOut: 3000,
                    });
                } else {
                    let totalVal = "$" + chart_data["totalsale"][0].toFixed(2);
                    $("#total-sale").html(totalVal);
                }
                if (chart_data["atv"].length === 0) {
                    $("#atv").html("$0.00");
                } else {
                    let atv = "$" + chart_data["atv"][0].toFixed(2);
                    $("#atv").html(atv);
                }
                if (chart_data["totaltrans"].length === 0) {
                    $("#total-tran").html("0");
                } else {
                    $("#total-tran").html(chart_data["totaltrans"][0]);
                }
            } else {
                toastr.error("get chart data error", "error", {
                    timeOut: 3000,
                });
            }
        },
    });
    // draw sals monthly chart
    let ctx_1 = document.getElementById("monthlySales").getContext("2d");
    let myChart_1 = new Chart(ctx_1, {
        type: "line",
        data: {
            labels: chart_data["monthlySales"]["label"],
            datasets: [
                {
                    label: "This Year Total Sales",
                    fill: true,
                    borderColor: "rgba(75,192,192,1)",
                    pointBackgroundColor: "#fff",
                    data: chart_data["monthlySales"]["c_totalsale"],
                },
                {
                    label: "Last Year Total Sales",
                    fill: true,
                    borderColor: "rgba(35,92,19,1)",
                    pointBackgroundColor: "#fff",
                    data: chart_data["monthlySales"]["o_totalsale"],
                },
                {
                    label: "This Year Total Trans",
                    fill: true,
                    borderColor: "rgba(5,19,192,1)",
                    pointBackgroundColor: "#fff",
                    data: chart_data["monthlySales"]["c_totaltrans"],
                },
                {
                    label: "Last Year Total Trans",
                    fill: true,
                    borderColor: "rgba(5,192,19,1)",
                    pointBackgroundColor: "#fff",
                    data: chart_data["monthlySales"]["o_totaltrans"],
                },
                {
                    label: "This Year Total ATV",
                    fill: true,
                    borderColor: "rgba(75,19,112,1)",
                    pointBackgroundColor: "#fff",
                    data: chart_data["monthlySales"]["c_atv"],
                },
                {
                    label: "Last Year Total ATV",
                    fill: true,
                    borderColor: "rgba(75,92,19,1)",
                    pointBackgroundColor: "#fff",
                    data: chart_data["monthlySales"]["o_atv"],
                },
            ],
        },
        options: {
            plugins: {
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: function (tooltipItem) {
                            let value = tooltipItem.formattedValue;

                            let label = tooltipItem.dataset.label || "";
                            if (label.includes("Trans")) {
                                return [label + " : " + value];
                            } else {
                                value = value.toLocaleString("en-US", {
                                    style: "currency",
                                    currency: "AUD",
                                });
                                return [label + " : " + value];
                            }
                        },
                    },
                },
            },
            // scales: {
            //     y: {
            //         ticks: {
            //             padding: 40,
            //             callback: function (value, index, ticks) {
            //                 value = (value).toLocaleString('en-US', {
            //                     style: 'currency',
            //                     currency: 'AUD',
            //                 });
            //                 return value;
            //             },
            //         }
            //     },
            //
            // },
        },
    });

    let ctx = document.getElementById("salesWeather").getContext("2d");

    let cloudy = new Image();
    cloudy.src = base_url + "img/cloudy.png";

    let clearDay = new Image();
    clearDay.src = base_url + "img/clearday.png";

    let clearNight = new Image();
    clearNight.src = base_url + "img/clear-night.png";

    let rain = new Image();
    rain.src = base_url + "img/rain.png";

    let snow = new Image();
    snow.src = base_url + "img/snow.png";

    let sleet = new Image();
    sleet.src = base_url + "img/sleet.png";

    let wind = new Image();
    wind.src = base_url + "img/wind.png";

    let fog = new Image();
    fog.src = base_url + "img/fog.png";

    let partlyDay = new Image();
    partlyDay.src = base_url + "img/partly-cloudy-day.png";

    let partlyNight = new Image();
    partlyNight.src = base_url + "img/partly-cloudy-night.png";

    let pointStyle = [];
    if (chart_data["salesWeather"]["label"].length > 0) {
        let tData = chart_data["salesWeather"]["label"];
        tData.forEach((val) => {
            switch (val) {
                case "clear-day":
                    pointStyle.push(clearDay);
                    break;
                case "clear-night":
                    pointStyle.push(clearNight);
                    break;
                case "rain":
                    pointStyle.push(rain);
                    break;
                case "snow":
                    pointStyle.push(snow);
                    break;
                case "sleet":
                    pointStyle.push(sleet);
                    break;
                case "wind":
                    pointStyle.push(wind);
                    break;
                case "fog":
                    pointStyle.push(fog);
                    break;
                case "cloudy":
                    pointStyle.push(cloudy);
                    break;
                case "partly-cloudy-day":
                    pointStyle.push(partlyDay);
                    break;
                case "partly-cloudy-night":
                    pointStyle.push(partlyNight);
                    break;
                case "sunny":
                    pointStyle.push(clearDay);
                    break;
                default:
                    pointStyle.push(clearDay);
                    break;
            }
        });
    }
    let myChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: chart_data["salesWeather"]["date"],
            weather: chart_data["salesWeather"]["label"],
            maxTemp: chart_data["salesWeather"]["maxTemp"],
            minTemp: chart_data["salesWeather"]["minTemp"],
            avgTemp: chart_data["salesWeather"]["avgTemp"],
            datasets: [
                {
                    fill: true,
                    borderColor: "rgba(75,192,192,1)",
                    pointBackgroundColor: "#fff",
                    data: chart_data["salesWeather"]["data"],
                    pointStyle: pointStyle,
                    pointBorderColor: "transparent",
                    pointRadius: 10,
                    pointHitRadius: 25,
                    hoverWidth: 2,
                    pointPaddingLeft: 150,
                },
            ],
        },
        options: {
            plugins: {
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: function (tooltipItem, data) {
                            let dataIndex = tooltipItem.dataIndex;
                            let weather =
                                chart_data["salesWeather"]["label"][dataIndex];
                            let sales =
                                chart_data["salesWeather"]["data"][dataIndex];
                            let highTemp =
                                chart_data["salesWeather"]["maxTemp"][
                                    dataIndex
                                ];
                            let lowTemp =
                                chart_data["salesWeather"]["minTemp"][
                                    dataIndex
                                ];
                            let avgTemp =
                                chart_data["salesWeather"]["avgTemp"][
                                    dataIndex
                                ];
                            sales = sales.toLocaleString("en-US", {
                                style: "currency",
                                currency: "AUD",
                            });
                            // let val = data.temp[tooltipItem.datasetIndex];
                            return [
                                "Sales : " + sales + "",
                                "Weather : " + weather + "",
                                "High Temp : " + highTemp,
                                "Low Temp : " + lowTemp,
                                "Avg Temp : " + avgTemp,
                            ];
                        },
                    },
                },
                legend: {
                    display: false,
                },
            },
            scales: {
                y: {
                    ticks: {
                        padding: 40,
                        callback: function (value, index, ticks) {
                            value = value.toLocaleString("en-US", {
                                style: "currency",
                                currency: "AUD",
                            });
                            return value;
                        },
                    },
                },
            },
        },
    });

    let ctx_2 = document.getElementById("itemsales").getContext("2d");
    let data_2 = {
        datasets: [
            {
                data: chart_data["itemsales"]["val"],
                backgroundColor: ["green", "red", "yellow", "purple", "pink"],
            },
        ],
        labels: chart_data["itemsales"]["name"],
    };
    let myDoughnutChart_2 = new Chart(ctx_2, {
        type: "pie",
        data: data_2,
        options: {
            responsive: false,
            maintainAspectRatio: false,
            legend: {
                position: "bottom",
                labels: {
                    boxWidth: 12,
                },
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return (
                                tooltipItem.label +
                                ": $" +
                                tooltipItem.formattedValue +
                                ""
                            );
                        },
                    },
                },
            },
        },
    });

    let ctx_3 = document.getElementById("itemcost").getContext("2d");
    let data_3 = {
        datasets: [
            {
                data: chart_data["itemcost"]["val"],
                backgroundColor: [
                    "green",
                    "lightgreen",
                    "yellow",
                    "blue",
                    "orange",
                ],
            },
        ],
        labels: chart_data["itemcost"]["name"],
    };
    let myDoughnutChart_3 = new Chart(ctx_3, {
        type: "pie",
        data: data_3,
        options: {
            responsive: false,
            maintainAspectRatio: false,
            legend: {
                position: "bottom",
                labels: {
                    boxWidth: 12,
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return (
                                tooltipItem.label +
                                ": $" +
                                tooltipItem.formattedValue +
                                ""
                            );
                        },
                    },
                },
            },
        },
    });

    let ctx_4 = document.getElementById("staffsales").getContext("2d");
    let myDoughnutChart_4 = new Chart(ctx_4, {
        type: "bar",
        data: {
            labels: chart_data["staffsales"]["name"],
            datasets: [
                {
                    // label: '# of Tomatoes',
                    data: chart_data["staffsales"]["val"],
                    backgroundColor: [
                        "rgba(255, 99, 132)",
                        "rgba(54, 162, 235)",
                        "rgba(255, 206, 86)",
                        "rgba(75, 192, 192)",
                        "rgba(153, 102, 255)",
                        "rgba(255, 159, 64)",
                    ],
                },
            ],
        },
        options: {
            responsive: false,
            indexAxis: "y",
            // scales: {
            //     xAxes: [{
            //         ticks: {
            //             maxRotation: 90,
            //             minRotation: 80
            //         },
            //         gridLines: {
            //             offsetGridLines: true // à rajouter
            //         },
            //     }],
            //     yAxes: [{
            //         ticks: {
            //             beginAtZero: true
            //         },
            //     }]
            // },
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return (
                                tooltipItem.label +
                                ": $" +
                                tooltipItem.formattedValue +
                                ""
                            );
                        },
                    },
                },
            },
        },
    });

    let ctx_5 = document.getElementById("customersales").getContext("2d");

    let myDoughnutChart_5 = new Chart(ctx_5, {
        type: "bar",
        data: {
            labels: chart_data["customersales"]["name"],
            datasets: [
                {
                    // label: '# of Tomatoes',
                    data: chart_data["customersales"]["val"],
                    backgroundColor: [
                        "rgba(255, 99, 132)",
                        "rgba(54, 162, 235)",
                        "rgba(255, 206, 86)",
                        "rgba(75, 192, 192)",
                        "rgba(153, 102, 255)",
                        "rgba(255, 159, 64)",
                    ],
                },
            ],
        },
        options: {
            responsive: false,
            indexAxis: "y",
            // scales: {
            //     xAxes: [{
            //         ticks: {
            //             maxRotation: 90,
            //             minRotation: 80
            //         },
            //         gridLines: {
            //             offsetGridLines: true // à rajouter
            //         }
            //     }],
            //     yAxes: [{
            //         ticks: {
            //             beginAtZero: true
            //         }
            //     }]
            // },
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return (
                                tooltipItem.label +
                                ": $" +
                                tooltipItem.formattedValue +
                                ""
                            );
                        },
                    },
                },
            },
        },
    });

    function filter() {
        let store = $(".store").val();
        let date = $(".js-daterangepicker").val();
        let myArray = date.split("-");
        let datefrom = new Date(myArray[0]);
        let dateto = new Date(myArray[1]);
        datefrom = formatDate(datefrom);
        dateto = formatDate(dateto);
        $.ajax({
            url: "/dashboard/api",
            method: "GET",
            async: false,
            data: {
                id: store,
                dateFrom: datefrom,
                dateTo: dateto,
            },
            success: function (data) {
                if (data.status === "success") {
                    chart_data = data.data;
                    if (chart_data["totalsale"].length === 0) {
                        $("#total-sale").html("$0.00");
                        toastr.error("no data available", "error", {
                            timeOut: 3000,
                        });
                    } else {
                        let totalVal =
                            "$" + chart_data["totalsale"][0].toFixed(2);
                        $("#total-sale").html(totalVal);
                    }
                    if (chart_data["atv"].length === 0) {
                        $("#atv").html("$0.00");
                    } else {
                        let atv = "$" + chart_data["atv"][0].toFixed(2);
                        $("#atv").html(atv);
                    }
                    if (chart_data["totaltrans"].length === 0) {
                        $("#total-tran").html("0");
                    } else {
                        $("#total-tran").html(chart_data["totaltrans"][0]);
                    }

                    let pointStyle = [];
                    if (chart_data["salesWeather"]["label"].length > 0) {
                        let tData = chart_data["salesWeather"]["label"];
                        tData.forEach((val) => {
                            switch (val) {
                                case "clear-day":
                                    pointStyle.push(clearDay);
                                    break;
                                case "clear-night":
                                    pointStyle.push(clearNight);
                                    break;
                                case "rain":
                                    pointStyle.push(rain);
                                    break;
                                case "snow":
                                    pointStyle.push(snow);
                                    break;
                                case "sleet":
                                    pointStyle.push(sleet);
                                    break;
                                case "wind":
                                    pointStyle.push(wind);
                                    break;
                                case "fog":
                                    pointStyle.push(fog);
                                    break;
                                case "cloudy":
                                    pointStyle.push(cloudy);
                                    break;
                                case "partly-cloudy-day":
                                    pointStyle.push(partlyDay);
                                    break;
                                case "partly-cloudy-night":
                                    pointStyle.push(partlyNight);
                                    break;
                                default:
                                    pointStyle.push(clearDay);
                                    break;
                            }
                        });
                    }
                    myChart_1.data.datasets[0].data =
                        chart_data["monthlySales"]["c_totalsale"];
                    myChart_1.data.datasets[1].data =
                        chart_data["monthlySales"]["o_totalsale"];
                    myChart_1.data.datasets[2].data =
                        chart_data["monthlySales"]["c_totaltrans"];
                    myChart_1.data.datasets[3].data =
                        chart_data["monthlySales"]["o_totaltrans"];
                    myChart_1.data.datasets[4].data =
                        chart_data["monthlySales"]["c_atv"];
                    myChart_1.data.datasets[5].data =
                        chart_data["monthlySales"]["o_atv"];
                    myChart_1.data.labels = chart_data["monthlySales"]["label"];
                    myChart_1.update();

                    myChart.data.datasets[0].data =
                        chart_data["salesWeather"]["data"];
                    myChart.data.labels = chart_data["salesWeather"]["date"];
                    myChart.data.maxTemp =
                        chart_data["salesWeather"]["maxTemp"];
                    myChart.data.minTemp =
                        chart_data["salesWeather"]["minTemp"];
                    myChart.data.avgTemp =
                        chart_data["salesWeather"]["avgTemp"];
                    myChart.data.weather = chart_data["salesWeather"]["label"];
                    myChart.data.datasets[0].pointStyle = pointStyle;
                    myChart.update();

                    myDoughnutChart_2.data.datasets[0].data =
                        chart_data["itemsales"]["val"];
                    myDoughnutChart_2.data.labels =
                        chart_data["itemsales"]["name"];
                    myDoughnutChart_2.update();
                    myDoughnutChart_3.data.datasets[0].data =
                        chart_data["itemcost"]["val"];
                    myDoughnutChart_3.data.labels =
                        chart_data["itemcost"]["name"];
                    myDoughnutChart_3.update();
                    myDoughnutChart_4.data.datasets[0].data =
                        chart_data["staffsales"]["val"];
                    myDoughnutChart_4.data.labels =
                        chart_data["staffsales"]["name"];
                    myDoughnutChart_4.update();
                    myDoughnutChart_5.data.datasets[0].data =
                        chart_data["customersales"]["val"];
                    myDoughnutChart_5.data.labels =
                        chart_data["customersales"]["name"];
                    myDoughnutChart_5.update();
                } else {
                    toastr.error(data.message, "error", { timeOut: 3000 });
                }
            },
        });
    }

    $("#filter-data").on("click", filter);
    // filter();
});
