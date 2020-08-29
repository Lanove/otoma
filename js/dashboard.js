$(document).ready(function () {
  $("#sidebar").mCustomScrollbar({theme: "minimal"});
  $("#dismiss, .overlay").on("click", function () {
    $(".sidebarCollapse .close").removeClass("active");
    $(".sidebarCollapse .open").addClass("active");
    $("#sidebar").removeClass("active");
    $(".overlay").removeClass("active");
  });

  $(".sidebarCollapse").on("click", function () {
    if ($(".sidebarCollapse .open").hasClass("active") == true) {
      $(".sidebarCollapse .open").removeClass("active");
      $(".sidebarCollapse .close").addClass("active");
      $("#sidebar").addClass("active");
      $(".overlay").addClass("active");
      $(".collapse.in").toggleClass("in");
      $("a[aria-expanded=true]").attr("aria-expanded", "false");
    } else {
      $(".sidebarCollapse .close").removeClass("active");
      $(".sidebarCollapse .open").addClass("active");
      $("#sidebar").removeClass("active");
      $(".overlay").removeClass("active");
    }
  });
});

var deviceBelonging = document.getElementById("bigdevicebox");
if (deviceBelonging) {
  // Check whether user had some device or not, if not then don't include the extra function that is specific for device.
  var myChart;
  $(document).ready(function () {
    loadDeviceInformation();
    createChart();

    $(".settingDevice1").on("click", function () {
      // $("#content").html("");
    });
  });

  function reloadStatus() {
    var xhr = new XMLHttpRequest();
    var json = JSON.stringify({requestType: "reloadStatus", masterDevice: $("#dashboard #deviceheader dummy").attr("class"), token: getMeta("token")});
    xhr.open("POST", "api/AjaxService.php");
    xhr.setRequestHeader("Content-type", "application/json; charset=utf-8");
    xhr.send(json);
    xhr.onload = function () {
      if (xhr.status == 200 && xhr.readyState == 4) {
        var parseJson = JSON.parse(xhr.responseText);
        if (parseJson["status"]["deviceType"] == "main") {
          $("#statusBoxSwitch1").prop("checked", Boolean(Number(parseJson["status"]["data1"])));
          $("#statusBoxSwitch2").prop("checked", Boolean(Number(parseJson["status"]["data2"])));
          $("#statusBoxSwitch3").prop("checked", Boolean(Number(parseJson["status"]["data3"])));
          $("#statusBoxSwitch4").prop("checked", Boolean(Number(parseJson["status"]["data4"])));
        }
      }
    };
  }

  const interval = setInterval(function () {
    if ($("#dashboard #deviceheader dummy").length) {
      reloadStatus();
    }
  }, 5000);

  function loadDeviceInformation() {
    var xhr = new XMLHttpRequest();
    var json = JSON.stringify({requestType: "loadDeviceInformation", token: getMeta("token")});
    xhr.open("POST", "api/AjaxService.php");
    xhr.setRequestHeader("Content-type", "application/json; charset=utf-8");
    xhr.send(json);
    xhr.onload = function () {
      if (xhr.status == 200 && xhr.readyState == 4) {
        var parseJson = JSON.parse(xhr.responseText);
        $("#dashboard #deviceheader dummy").addClass(parseJson["main"]["bondKey"]);
        $("#dashboard #deviceheader .text").text(parseJson["main"]["masterName"]);
        $("#dashboard #deviceheader .text").append("<i class = 'fas fa-caret-down'></i>");
        $("#dashboard .device-graph-box .name .text").text(parseJson["main"]["masterName"]);
        $("#dashboard .device-graph-box .name .text").append("<i class = 'fas fa-caret-down'></i>");
        var i = 1;
        for (c in parseJson) {
          if (c != "main" && c != "plot") {
            $("#dashboard #deviceheader .dropdown-menu").append('<a href="#" class="dropdown-item ' + "subMasterName" + String(i) + '">' + parseJson[c]["masterName"] + "</a>");
            i++;
          }
        }
        i = 1;
        for (c in parseJson["main"]) {
          if (c.includes("deviceName")) {
            $("#dashboard .device-graph-box .name .dropdown-menu").append('<a href="#" class="dropdown-item ' + "deviceName" + String(i) + '">' + parseJson["main"][c] + "</a>");
            $("#dashboard .device-status-box .header ." + c).text(parseJson["main"][c]);
            i++;
          }
        }
        $("#dashboard .device-graph-box .name .dropdown-menu").append("<a href='#' class='dropdown-item totalAmount'>" + "Total" + "</a>");

        var dataPlot = [];
        var totalEnergy = 0;
        for (c in parseJson["plot"]) {
          dataPlot[c] = parseInt(parseJson["plot"][c]["data1"]);
          totalEnergy += dataPlot[c];
        }
        $("#dashboard .device-graph-box .totalenergy span").text(String(totalEnergy) + "Wh");
        updateChart(myChart, [
          "1:00",
          "2:00",
          "3:00",
          "4:00",
          "5:00",
          "6:00",
          "7:00",
          "8:00",
          "9:00",
          "10:00",
          "11:00",
          "12:00",
          "13:00",
          "14:00",
          "15:00",
          "16:00",
          "17:00",
          "18:00",
          "19:00",
          "20:00",
          "21:00",
          "22:00",
          "23:00"
        ], dataPlot);
      }
    };
    setTimeout(function () {
      if ($("#dashboard #deviceheader dummy").length) {
        reloadStatus();
      }
    }, 500);
  }

  function getMeta(metaName) {
    const metas = document.getElementsByTagName("meta");
    for (let i = 0; i < metas.length; i++) {
      if (metas[i].getAttribute("name") === metaName) {
        return metas[i].getAttribute("content");
      }
    }
    return "";
  }

  function switchToggle(id) {
    var checkBox = document.getElementById(id);
    var xhr = new XMLHttpRequest();
    var json = JSON.stringify({
      requestType: "changeStatus",
      masterDevice: $("#dashboard #deviceheader dummy").attr("class"),
      id: id,
      status: String(checkBox.checked),
      token: getMeta("token")
    });
    xhr.open("POST", "api/AjaxService.php");
    xhr.setRequestHeader("Content-type", "application/json; charset=utf-8");
    xhr.send(json);
  }

  function createChart() {
    var ctx = document.getElementById("graph").getContext("2d");
    Chart.defaults.global.defaultFontColor = "white";
    Chart.defaults.global.elements.rectangle.borderWidth = 2;
    myChart = new Chart(ctx, {
      type: "bar",
      data: {
        datasets: [
          {
            label: "Penggunaan Energi (Wh)",
            backgroundColor: "rgba(2, 117, 216, 1)",
            borderColor: "rgba(2, 117, 216, 1)",
            borderWidth: 1
          }
        ]
      },
      options: {
        maintainAspectRatio: false,
        responsive: true,
        scales: {
          yAxes: [
            {
              ticks: {
                beginAtZero: true
              }
            }
          ]
        }
      }
    });
  }

  function addData(chart, label, data) {
    chart.data.labels.push(label);
    chart.data.datasets.forEach(dataset => {
      dataset.data.push(data);
    });
    chart.update();
  }

  function removeData(chart) {
    chart.data.labels.pop();
    chart.data.datasets.forEach(dataset => {
      dataset.data.pop();
    });
    chart.update();
  }

  function updateChart(chart, label, data) {
    for (c in label) {
      addData(chart, label[c], data[c]);
    }
  }
}