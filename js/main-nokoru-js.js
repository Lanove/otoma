// Check whether user had some device or not, if not then don't include the extra function that is specific for device.
var myChart;
var oArrData = [];
var timerData = {
  t1: {},
  t2: {},
  t3: {},
  t4: {},
};
var plotData = {
  date: {},
  data: {},
  total: null,
  label: {},
};

createDataSource(oArrData, [7, 23, 59]);
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
  requestAJAX({
    requestType: "updateTimerVal",
    bondKey: $("#dashboard #deviceheader dummy").attr("class"),
    token: getMeta("token"),
    value: stringBuffer,
    id: this.elem.id,
  });
  return stringBuffer;
}

$(document).ready(function () {
  createChart();
  var today = new Date();
  var dd = String(today.getDate()).padStart(2, "0");
  var mm = String(today.getMonth() + 1).padStart(2, "0"); //January is 0!
  var yyyy = today.getFullYear();

  today = yyyy + "-" + mm + "-" + dd;
  $("#dateselector").val(today);
  loadDeviceInformation("main");
  for (var a = 1; a < 5; a++) {
    $("#t" + String(a)).AnyPicker({
      // Add anypicker to every timer
      mode: "select",
      lang: "id",
      showComponentLabel: true,
      components: oArrComponents,
      dataSource: oArrDataSource,
      parseInput: formatTimerInput,
      formatOutput: formatTimerOutput,
    });

    $("#statusBoxSwitch" + a).on("click", function () {
      // Add event listener to status toggle
      var matches = this.id.match(/\d+/g);
      var elem = this.id;
      var check = $("#" + this.id).prop("checked");
      $("#" + elem).prop("checked", !check);
      if (timerData["t" + matches]["status"] === "started") {
        if (check == false) {
          bootbox.confirm({
            title: "Nonaktifkan status perangkat?",
            className: "bootBoxPop",
            message: `Menonaktifkan status perangkat saat timer sedang dimulai dapat menjadikan fungsi timer terganggu, timer menonaktifkan perangkat saat timer kadaluarsa, jika perangkat sudah nonaktif, perangkat akan tetap nonaktif walaupun timer sudah kadaluarsa`,
            buttons: {
              cancel: {
                label: '<i class="fa fa-times"></i> Tidak',
                className: "bootBoxCancelButton",
              },
              confirm: {
                label: '<i class="fa fa-check"></i> Ya',
                className: "bootBoxConfirmButton",
              },
            },
            callback: function (result) {
              if (result) {
                $("#" + elem).prop("checked", check);
                switchToggle(elem);
              }
            },
          });
        } else {
          $("#" + elem).prop("checked", check);
          switchToggle(elem);
        }
      } else if (timerData["t" + matches]["status"] === "paused") {
        if (check == true) {
          bootbox.confirm({
            title: "Aktifkan status perangkat?",
            className: "bootBoxPop",
            message: `Mengaktifkan status perangkat saat timer sedang terpause dapat menjadikan fungsi timer terganggu, timer mengaktifkan perangkat kembali saat timer dimulai kembali dari keadaan pause, jika perangkat sudah aktif, perangkat akan tetap aktif walaupun timer dimulai kembali dari keadaan pause, dan nonaktif setelah timer kadaluarsa`,
            buttons: {
              cancel: {
                label: '<i class="fa fa-times"></i> Tidak',
                className: "bootBoxCancelButton",
              },
              confirm: {
                label: '<i class="fa fa-check"></i> Ya',
                className: "bootBoxConfirmButton",
              },
            },
            callback: function (result) {
              if (result) {
                $("#" + elem).prop("checked", check);
                switchToggle(elem);
              }
            },
          });
        } else {
          $("#" + elem).prop("checked", check);
          switchToggle(elem);
        }
      } else if (timerData["t" + matches]["status"] === "idle") {
        $("#" + elem).prop("checked", check);
        switchToggle(elem);
      }
    });

    $("#tbtns" + a).on("click", function () {
      // Add event listener to every timer start/pause button
      var ajaxBuffer = {};
      var apo = this;
      ajaxBuffer["val"] = $("#" + apo.id).text();
      ajaxBuffer["requestType"] = "timerButton";
      ajaxBuffer["id"] = apo.id;
      ajaxBuffer["duration"] = $("#t" + apo.id.substring(5, 6)).val();
      ajaxBuffer["token"] = getMeta("token");
      ajaxBuffer["masterDevice"] = $("#dashboard #deviceheader dummy").attr(
        "class"
      );
      $("#statusBoxOverlay" + apo.id.substring(5, 6)).addClass("active");
      requestAJAX(ajaxBuffer, function () {
        reloadStatus();
        $("#statusBoxOverlay" + apo.id.substring(5, 6)).removeClass("active");
      });
    });
    $("#tbtnr" + a).on("click", function () {
      // Add event listener to every timer stop button
      var ajaxBuffer = {};
      var apo = this;
      ajaxBuffer["requestType"] = "timerButton";
      ajaxBuffer["id"] = apo.id;
      ajaxBuffer["token"] = getMeta("token");
      ajaxBuffer["masterDevice"] = $("#dashboard #deviceheader dummy").attr(
        "class"
      );
      $("#statusBoxOverlay" + apo.id.substring(5, 6)).addClass("active");
      requestAJAX(ajaxBuffer, function () {
        reloadStatus();
        $("#statusBoxOverlay" + apo.id.substring(5, 6)).removeClass("active");
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

      $(".absolute-overlay").addClass("loaded");
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
              (timerData["t" + i]["endAt"] - timerData["t" + i]["startedAt"])) *
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
              (timerData["t" + i]["endAt"] - timerData["t" + i]["startedAt"])) *
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
const monthNames = [
  "Januari",
  "Februari",
  "Maret",
  "April",
  "Mei",
  "Juni",
  "Juli",
  "Agustus",
  "September",
  "Oktober",
  "November",
  "Desember",
];
function getDateNowLong() {
  let dateObj = new Date();
  let month = monthNames[dateObj.getMonth()];
  let day = String(dateObj.getDate()).padStart(2, "0");
  let year = dateObj.getFullYear();
  let output = day + " " + month + " " + year;
  return output;
}
function convertToDateLong(arg) {
  var buffer = arg.split("-");
  let month = monthNames[parseInt(buffer[1]) - 1];
  return buffer[2] + " " + month + " " + buffer[0];
}
function setTrigger(sOutput) {
  var type = $("#dashboard .sub4 .ctn .txt").text();
  if (type.search(/harian/i)) type = "Harian";
  else if (type.search(/bulanan/i)) type = "Bulanan";
  else type = "Tahunan;";
  $("#dateselector").val(sOutput);
  $("#graphoverlay").addClass("active");
  var success = false;
  requestAJAX(
    {
      requestType: "getChart",
      bondKey: $("#dashboard #deviceheader dummy").attr("class"),
      token: getMeta("token"),
      type: type,
      value: sOutput,
    },
    function (response) {
      var parseJson = JSON.parse(response);
      success = true;
      $("#graphoverlay").removeClass("active");
      plotData.total = 0;
      for (c in parseJson) {
        plotData.data[c] = parseInt(parseJson[c]["data1"]);
        plotData.total += plotData.data[c];
        plotData.label[c] = parseJson[c]["timestamp"].slice(0, 5);
      }
      plotData.date = convertToDateLong(sOutput);
      $("#dashboard .device-graph-box .totalenergy span").text(
        String(plotData.total) + "Wh"
      );
      refreshChart(plotData);
    }
  );
}

function loadDeviceInformation(controller) {
  requestAJAX(
    {
      requestType: "loadDeviceInformation",
      master: controller,
      token: getMeta("token"),
    },
    function (response) {
      var parseJson = JSON.parse(response);
      $("#dashboard #deviceheader dummy").removeClass();
      $("#dashboard #deviceheader dummy").addClass(
        parseJson["main"]["bondKey"]
      );
      $("#dashboard #deviceheader .text").text(parseJson["main"]["masterName"]);
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
      $("#dashboard #deviceheader .dropdown-menu").html("");
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
          $(".subMasterName" + String(i)).on("click", function () {
            $(".absolute-overlay").removeClass("loaded");
            loadDeviceInformation(
              $("." + $(this).attr("class").split(/\s+/)[1]).text()
            );
            const check = setInterval(function () {
              // Function to check every 0.1s if bondKey is available, then execute reloadStatus() and destroy itself.
              if ($("#dashboard #deviceheader dummy").attr("class") != "") {
                reloadStatus();
                $(".absolute-overlay").addClass("loaded");
                clearInterval(check); // kill after executed
              }
            }, 100);
          });
          i++;
        }
      }
      i = 1;
      $("#dashboard .device-graph-box .name .dropdown-menu").html("");
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
      var oldest = parseJson["plot"]["oldest"]["oldestPlot"].split("-");
      var newest = parseJson["plot"]["newest"]["newestPlot"].split("-");
      for (index in newest) {
        newest[index] = parseInt(newest[index]);
        oldest[index] = parseInt(oldest[index]);
      }
      oldest[1]--;
      $("#dateselector").unbind().removeData();
      $("#dateselector").AnyPicker({
        mode: "datetime",
        dateTimeFormat: "yyyy-MM-dd",
        lang: "id",
        minValue: new Date(oldest[0], oldest[1], 01),
        maxValue: new Date(newest[0], newest[1], 00),
        setOutput: setTrigger,
      });

      plotData.total = 0;
      for (c in parseJson["plot"]["data"]) {
        plotData.data[c] = parseInt(parseJson["plot"]["data"][c]["data1"]);
        plotData.total += plotData.data[c];
        plotData.label[c] = parseJson["plot"]["data"][c]["timestamp"].slice(
          0,
          5
        );
      }
      plotData.date = getDateNowLong();
      $("#dashboard .device-graph-box .totalenergy span").text(
        String(plotData.total) + "Wh"
      );
      refreshChart(plotData);
    }
  );
}

