$(document).ready(function () {
  $("#sidebar").mCustomScrollbar({ theme: "minimal" });
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
  var oArrData = [];
  var timerData = {
    t1: {},
    t2: {},
    t3: {},
    t4: {},
  };

  function createDataSource() {
    var oArrDataNum = [7, 23, 59],
      iTempIndex1,
      iTempIndex2;
    for (iTempIndex1 = 0; iTempIndex1 < oArrDataNum.length; iTempIndex1++) {
      var iNum = oArrDataNum[iTempIndex1],
        oArrDataComp = [];
      for (iTempIndex2 = 0; iTempIndex2 < iNum + 1; iTempIndex2++) {
        oArrDataComp.push({
          val: iTempIndex2.toString(),
          label: iTempIndex2.toString(),
        });
      }
      oArrData.push(oArrDataComp);
    }
  }
  createDataSource();
  var oArrComponents = [
      {
        component: 0,
        name: "h",
        label: "Hari",
        width: "40%",
        textAlign: "center",
      },
      {
        component: 1,
        name: "j",
        label: "Jam",
        width: "30%",
        textAlign: "center",
      },
      {
        component: 2,
        name: "m",
        label: "Menit",
        width: "30%",
        textAlign: "center",
      },
    ],
    oArrDataSource = [
      {
        component: 0,
        data: oArrData[0],
      },
      {
        component: 1,
        data: oArrData[1],
      },
      {
        component: 2,
        data: oArrData[2],
      },
    ];

  function formatTimerInput(sElemValue) {
    var matches = sElemValue.match(/\d+/g);
    for (i in matches) {
      if (
        matches[i] === null ||
        matches[i] === undefined ||
        matches[i] === "" ||
        matches[i] === "0"
      )
        matches[i] = "0";
    }
    return matches;
  }

  function formatTimerOutput(oSelectedValues) {
    var stringBuffer = "",
      numberPal = ["h ", "j ", "m"];
    for (i in oSelectedValues.values) {
      if (
        oSelectedValues.values[i].val === null ||
        oSelectedValues.values[i].val === undefined ||
        oSelectedValues.values[i].val === "" ||
        oSelectedValues.values[i].val === "0"
      )
        stringBuffer += "0" + numberPal[i];
      else stringBuffer += oSelectedValues.values[i].val + numberPal[i];
    }
    return stringBuffer;
  }

  $(document).ready(function () {
    loadDeviceInformation();
    createChart();
    for (var a = 1; a < 5; a++) {
      $("#t" + String(a)).AnyPicker({
        // Add anypicker to every timer
        mode: "select",
        showComponentLabel: true,
        components: oArrComponents,
        dataSource: oArrDataSource,
        parseInput: formatTimerInput,
        formatOutput: formatTimerOutput,
      });

      $("#statusBoxSwitch" + a).on("click", function () {
        // Add event listener to status toggle
        switchToggle(this.id);
      });

      $("#tbtns" + a).on("click", function () {
        // Add event listener to every timer start/pause button
        var ajaxBuffer = {};
        ajaxBuffer["val"] = $("#" + this.id).text();
        ajaxBuffer["requestType"] = "timerButton";
        ajaxBuffer["id"] = this.id;
        ajaxBuffer["duration"] = $("#t" + this.id.substring(5, 6)).val();
        ajaxBuffer["token"] = getMeta("token");
        ajaxBuffer["masterDevice"] = $("#dashboard #deviceheader dummy").attr(
          "class"
        );
        requestAJAX(ajaxBuffer, function () {
          reloadStatus();
        });
      });
      $("#tbtnr" + a).on("click", function () {
        // Add event listener to every timer stop button
        var ajaxBuffer = {};
        ajaxBuffer["requestType"] = "timerButton";
        ajaxBuffer["id"] = this.id;
        ajaxBuffer["token"] = getMeta("token");
        ajaxBuffer["masterDevice"] = $("#dashboard #deviceheader dummy").attr(
          "class"
        );
        requestAJAX(ajaxBuffer, function () {
          reloadStatus();
        });
      });
    }

    $(".settingDevice1").on("click", function () {
      // $("#content").html("");
    });

    const check = setInterval(function () {
      // Function to check every 0.1s if bondKey is available, then execute reloadStatus() and destroy itself.
      if ($("#dashboard #deviceheader dummy").attr("class") != "") {
        reloadStatus();
        clearInterval(check); // kill after executed
      }
    }, 100);

    const interval = setInterval(function () {
      // If the page had bondKey, reload the status of the device every 5s
      if ($("#dashboard #deviceheader dummy").attr("class") != "") {
        reloadStatus();
      }
    }, 5000);

    const timerBarUpdate = setInterval(function () {
      const now = Math.floor(Date.now() / 1000); // Get time now in unixtime second
      for (var i = 1; i < 5; i++) {
        // Loop through every proggress bar 1~4
        if (timerData["t" + i]["status"] == "started") {
          // If the timer is started

          var elem = document.getElementById("progBar" + i),
            deltaNow = timerData["t" + i]["endAt"] - now, // Get timer remaining second
            width = Math.round(
              ((now - timerData["t" + i]["startedAt"]) /
                (timerData["t" + i]["endAt"] -
                  timerData["t" + i]["startedAt"])) *
                100
            );
          if (timerData["t" + i]["endAt"] - Math.floor(Date.now() / 1000) < 0) {
            // If the result of time now and started time of timer is negative, the timer is expired, update the database
            reloadStatus();
          }

          if (width > 100) {
            // Limit the width to 100 when go pass 100
            width = 100;
          }
          if (width < 0) {
            // Limit the width to 0 when it goes lower than 0
            width = 0;
          }
          var stringBuffer = "";
          if (deltaNow >= 86400) {
            // Calculate day remained when timer remaining second is higher than 86400s or 1 day
            stringBuffer += Math.floor(deltaNow / 86400) + "h ";
          }
          if (deltaNow >= 3600) {
            // Calculate hour remained when timer remaining second is higher than 3600s or one hour
            stringBuffer += Math.floor((deltaNow % 86400) / 3600) + "j ";
          }
          if (deltaNow >= 60) {
            // Calculate minutes remained when timer remaining second is higher than 60s or one minute
            stringBuffer += Math.floor((deltaNow % 3600) / 60) + "m ";
          }

          if (deltaNow >= 0) {
            stringBuffer += (deltaNow % 60) + "s ";
          }
          elem.style.width = width + "%"; // Adjust proggress bar width
          elem.innerHTML = stringBuffer + "(" + width + "%" + ")"; // Print remaining time in xh xj xm format and it's percentage to complete
        } else if (timerData["t" + i]["status"] == "paused") {
          var elem = document.getElementById("progBar" + i),
            width = Math.round(
              ((timerData["t" + i]["pausedAt"] -
                timerData["t" + i]["startedAt"]) /
                (timerData["t" + i]["endAt"] -
                  timerData["t" + i]["startedAt"])) *
                100
            ),
            deltaNow =
              timerData["t" + i]["endAt"] - timerData["t" + i]["pausedAt"]; // Get timer remained after paused;

          if (width > 100) {
            // Limit the width to 100 when go pass 100
            width = 100;
          }
          if (width < 0) {
            // Limit the width to 0 when it goes lower than 0
            width = 0;
          }
          var stringBuffer = "";
          if (deltaNow >= 86400) {
            // Calculate day remained when timer remaining second is higher than 86400s or 1 day
            stringBuffer += Math.floor(deltaNow / 86400) + "h ";
          }
          if (deltaNow >= 3600) {
            // Calculate hour remained when timer remaining second is higher than 3600s or one hour
            stringBuffer += Math.floor((deltaNow % 86400) / 3600) + "j ";
          }
          if (deltaNow >= 60) {
            // Calculate minutes remained when timer remaining second is higher than 60s or one minute
            stringBuffer += Math.floor((deltaNow % 3600) / 60) + "m ";
          }
          if (deltaNow >= 0) {
            stringBuffer += (deltaNow % 60) + "s ";
          }
          elem.style.width = width + "%"; // Adjust progress bar width
          elem.innerHTML = stringBuffer + "(Terpause)"; // Print remaining time before paused
        } else {
          // No timer here.
          var elem = document.getElementById("progBar" + i);
          elem.innerHTML = "Tidak ada timer";
          elem.style.width = 0 + "%";
        }
      }
    }, 1000);
  });

  function requestAJAX(jsonobject, callback = function () {}) {
    var xhr = new XMLHttpRequest();
    var json = JSON.stringify(jsonobject);
    xhr.open("POST", "api/AjaxService.php");
    xhr.setRequestHeader("Content-type", "application/json; charset=utf-8");
    xhr.send(json);
    xhr.onload = function () {
      if (xhr.status == 200 && xhr.readyState == 4) {
        callback(xhr.responseText);
      }
    };
  }

  function reloadStatus() {
    requestAJAX(
      {
        requestType: "reloadStatus",
        masterDevice: $("#dashboard #deviceheader dummy").attr("class"),
        token: getMeta("token"),
      },
      function (response) {
        var parseJson = JSON.parse(response);
        if (parseJson["status"]["deviceType"] == "main") {
          $("#statusBoxSwitch1").prop(
            "checked",
            Boolean(Number(parseJson["status"]["data1"]))
          );
          $("#statusBoxSwitch2").prop(
            "checked",
            Boolean(Number(parseJson["status"]["data2"]))
          );
          $("#statusBoxSwitch3").prop(
            "checked",
            Boolean(Number(parseJson["status"]["data3"]))
          );
          $("#statusBoxSwitch4").prop(
            "checked",
            Boolean(Number(parseJson["status"]["data4"]))
          );
          for (var i = 1; i < 5; i++) {
            var tData = parseJson["t" + i];
            timerData["t" + i]["status"] = tData[0];
            timerData["t" + i]["startedAt"] = tData[1];
            timerData["t" + i]["endAt"] = tData[2];
            timerData["t" + i]["pausedAt"] = tData[3];
            timerData["t" + i]["duration"] = tData[4];
            $("#t" + i).val(timerData["t" + i]["duration"]);
            if (timerData["t" + i]["status"] == "started") {
              if (
                timerData["t" + i]["endAt"] - Math.floor(Date.now() / 1000) <
                0
              ) {
                // If the result of time now and started time of timer is negative, the timer is expired, update the database
                var ajaxBuffer = {};
                ajaxBuffer["t" + i + "Data"] = "idle";
                ajaxBuffer["requestType"] = "changeTimerStatus";
                ajaxBuffer["token"] = getMeta("token");
                ajaxBuffer["masterDevice"] = $(
                  "#dashboard #deviceheader dummy"
                ).attr("class");
                requestAJAX(ajaxBuffer);
              }
              $("#tbtns" + i).text("Pause");
              $("#progBar" + i).css(
                "background-color",
                "var(--progbar-bar-color)"
              );
            } else if (timerData["t" + i]["status"] == "paused") {
              $("#tbtns" + i).text("Start");
              $("#progBar" + i).css(
                "background-color",
                "var(--progbar-bar-paused-color)"
              );
            } else if (timerData["t" + i]["status"] == "idle") {
              $("#tbtns" + i).text("Start");
              $("#progBar" + i).css(
                "background-color"
                // "var(--progbar-bg-color)"
              );
            }
          }
        }
      }
    );
  }
  function loadDeviceInformation() {
    requestAJAX(
      {
        requestType: "loadDeviceInformation",
        token: getMeta("token"),
      },
      function (response) {
        var parseJson = JSON.parse(response);
        $("#dashboard #deviceheader dummy").addClass(
          parseJson["main"]["bondKey"]
        );
        $("#dashboard #deviceheader .text").text(
          parseJson["main"]["masterName"]
        );
        $("#dashboard #deviceheader .text").append(
          "<i class = 'fas fa-caret-down'></i>"
        );
        $("#dashboard .device-graph-box .name .text").text(
          parseJson["main"]["masterName"]
        );
        $("#dashboard .device-graph-box .name .text").append(
          "<i class = 'fas fa-caret-down'></i>"
        );
        var i = 1;
        for (c in parseJson) {
          if (c != "main" && c != "plot") {
            $("#dashboard #deviceheader .dropdown-menu").append(
              '<a href="#" class="dropdown-item ' +
                "subMasterName" +
                String(i) +
                '">' +
                parseJson[c]["masterName"] +
                "</a>"
            );
            i++;
          }
        }
        i = 1;
        for (c in parseJson["main"]) {
          if (c.includes("deviceName")) {
            $("#dashboard .device-graph-box .name .dropdown-menu").append(
              '<a href="#" class="dropdown-item ' +
                "deviceName" +
                String(i) +
                '">' +
                parseJson["main"][c] +
                "</a>"
            );
            $("#dashboard .device-status-box .header ." + c).text(
              parseJson["main"][c]
            );
            i++;
          }
        }
        $("#dashboard .device-graph-box .name .dropdown-menu").append(
          "<a href='#' class='dropdown-item totalAmount'>" + "Total" + "</a>"
        );
        var dataPlot = [];
        var totalEnergy = 0;
        for (c in parseJson["plot"]) {
          dataPlot[c] = parseInt(parseJson["plot"][c]["data1"]);
          totalEnergy += dataPlot[c];
        }
        $("#dashboard .device-graph-box .totalenergy span").text(
          String(totalEnergy) + "Wh"
        );
        updateChart(
          myChart,
          [
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
            "23:00",
          ],
          dataPlot
        );
      }
    );
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
    requestAJAX({
      requestType: "changeStatus",
      masterDevice: $("#dashboard #deviceheader dummy").attr("class"),
      id: id,
      status: String(checkBox.checked),
      token: getMeta("token"),
    });
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
            borderWidth: 1,
          },
        ],
      },
      options: {
        maintainAspectRatio: false,
        responsive: true,
        scales: {
          yAxes: [
            {
              ticks: {
                beginAtZero: true,
              },
            },
          ],
        },
      },
    });
  }

  function addData(chart, label, data) {
    chart.data.labels.push(label);
    chart.data.datasets.forEach((dataset) => {
      dataset.data.push(data);
    });
    chart.update();
  }

  function removeData(chart) {
    chart.data.labels.pop();
    chart.data.datasets.forEach((dataset) => {
      dataset.data.pop();
    });
    chart.update();
  }

  function updateChart(chart, label, data) {
    for (c in label) {
      addData(chart, label[c], data[c]);
    }
  }

  function updateBar(id, percentage, label) {
    var elem = document.getElementById(id);
    elem.style.width = percentage + "%";
    elem.innerHTML = label;
  }
}
