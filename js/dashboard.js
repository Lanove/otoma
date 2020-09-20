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

var deviceBelonging = $("#bigdevicebox");
if (deviceBelonging) {
  // Utility function for anypicker
  function createDataSource(outputData, dataNum) {
    var iTempIndex1, iTempIndex2;
    for (iTempIndex1 = 0; iTempIndex1 < dataNum.length; iTempIndex1++) {
      var iNum = dataNum[iTempIndex1],
        oArrDataComp = [];
      for (iTempIndex2 = 0; iTempIndex2 < iNum + 1; iTempIndex2++) {
        oArrDataComp.push({
          val: iTempIndex2.toString(),
          label: iTempIndex2.toString(),
        });
      }
      outputData.push(oArrDataComp);
    }
  }
  ///////////////////////////////////////////

  // Utility function for chart.js
  function createChart(chartApo, element, config, obj) {
    Chart.defaults.global.defaultFontColor = "white";
    Chart.defaults.global.elements.rectangle.borderWidth = 2;
    chartApo[obj] = new Chart(element, config);
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

  function refreshChart(
    chartApo,
    chartObj,
    chartElement,
    chartConfig,
    apoPlot
  ) {
    chartApo.destroy();
    createChart(chartApo, chartElement, chartConfig, chartObj);
    updateChart(chartApo[chartObj], apoPlot.label, apoPlot.data);
  }
  ////////////////////////////////////////////////////////////////////

  function updateBar(id, percentage, label) {
    var elem = document.getElementById(id);
    elem.style.width = percentage + "%";
    elem.innerHTML = label;
  }
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

  if (deviceBelonging.hasClass("maindevice")) {
  } else if (deviceBelonging.hasClass("nexusdevice")) {
    var nexuschart = { temp: null, humid: null },
      tempConfig = {
        type: "line",
        data: {
          datasets: [
            {
              label: "Suhu pada " /*+ date*/,
              backgroundColor: "rgba(33, 145, 251, 1)",
              borderColor: "rgba(33, 145, 251, 1)",
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
      },
      humidConfig = {
        type: "line",
        data: {
          datasets: [
            {
              label: "Humiditas pada " /*+ date*/,
              backgroundColor: "rgba(33, 145, 251, 1)",
              borderColor: "rgba(33, 145, 251, 1)",
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
      };

    function spsetOut(oSelectedValues) {
      $("#spvalue").text(String(oSelectedValues.values[0].label) + "°C");
      return oSelectedValues.values[0].label;
    }
    $(document).ready(function () {
      createChart(
        nexuschart,
        document.getElementById("tempgraph").getContext("2d"),
        tempConfig,
        "temp"
      );
      createChart(
        nexuschart,
        document.getElementById("humidgraph").getContext("2d"),
        humidConfig,
        "humid"
      );
      // Enable popover
      $('[data-toggle="popover"]').popover({ html: true });

      // Enable anypicker on setpoint setting
      var oArrData = [];
      createDataSource(oArrData, [100]);
      var oArrComponents = [
          {
            component: 0,
            name: "c",
            label: "Suhu",
            width: "50%",
            textAlign: "center",
          },
        ],
        oArrDataSource = [
          {
            component: 0,
            data: oArrData[0],
          },
        ];
      $("#spsetting").text("37"); // test
      $("#spsetting").AnyPicker({
        mode: "select",
        lang: "id",
        showComponentLabel: true,
        components: oArrComponents,
        dataSource: oArrDataSource,
        formatOutput: spsetOut,
      });

      // Onclick thermocontroller operation select
      $("input[name='operation']").click(function () {
        var radioValue = $("input[name='operation']:checked").val(); // Get the value of checked radio
        $("#heateroverlay").removeClass("active"); // Remove both active overlay class
        $("#cooleroverlay").removeClass("active"); // Remove both active overlay class
        $("#setpointoverlay").removeClass("active"); // Remove both active overlay class
        $("#heateroverlay p").text(""); // Remove text from overlay
        $("#cooleroverlay p").text(""); // Remove text from overlay

        if (radioValue === "manual") {
          $("#setpointoverlay").addClass("active"); // Overlay the setpoint setting
          $("#cooleroverlay").addClass("active"); // Overlay the cooler
          $("#cooleroverlay p").text("Hanya tersedia dalam mode Auto"); // Add text to overlay
          $("#heateroverlay").addClass("active"); // Overlay the heater
          $("#heateroverlay p").text("Hanya tersedia dalam mode Auto"); // Add text to overlay
        } else {
          var thermmode = $("input[name='thermmode']:checked").val();
          // Apply overlay according to thermmode radio
          if (thermmode === "heat") {
            $("#cooleroverlay").addClass("active"); // Overlay the cooler
            $("#cooleroverlay p").text(
              "Hanya tersedia dalam mode Cooling atau Dual"
            ); // Add text to overlay
          } else if (thermmode === "cool") {
            $("#heateroverlay").addClass("active"); // Overlay the heater
            $("#heateroverlay p").text(
              "Hanya tersedia dalam mode Heating atau Dual"
            ); // Add text to overlay
          }
        }
        // Do AJAX things here and such
      });

      // Onclick thermocontroller mode select
      $("input[name='thermmode']").click(function () {
        var radioValue = $("input[name='thermmode']:checked").val(); // Get the value of checked radio
        if ($("input[name='operation']:checked").val() == "auto") {
          // Only do shit when we are on auto mode
          $("#heateroverlay").removeClass("active"); // Remove both active overlay class
          $("#cooleroverlay").removeClass("active"); // Remove both active overlay class
          $("#heateroverlay p").text(""); // Remove text from overlay
          $("#cooleroverlay p").text(""); // Remove text from overlay

          if (radioValue === "heat") {
            $("#cooleroverlay").addClass("active"); // Overlay the cooler
            $("#cooleroverlay p").text(
              "Hanya tersedia dalam mode Cooling atau Dual"
            ); // Add text to overlay
          } else if (radioValue === "cool") {
            $("#heateroverlay").addClass("active"); // Overlay the heater
            $("#heateroverlay p").text(
              "Hanya tersedia dalam mode Heating atau Dual"
            ); // Add text to overlay
          }
          // Do AJAX things here and such
        }
      });

      // Onclick cooler mode select
      $("input[name='cmode']").click(function () {
        var radioValue = $("input[name='cmode']:checked").val(); // Get the value of checked radio
        $("#coolerbox .hysteresismenu").removeClass("active"); // Remove both active class
        $("#coolerbox .pidmenu").removeClass("active"); // Remove both active class
        if (radioValue === "pid") {
          // Display the pidmenu or hysteresismenu accordingly
          $("#coolerbox .pidmenu").addClass("active");
        } else {
          $("#coolerbox .hysteresismenu").addClass("active");
        }
      });

      // Onclick cooler mode select
      $("input[name='cmode']").click(function () {
        var radioValue = $("input[name='cmode']:checked").val(); // Get the value of checked radio
        $("#coolerbox .hysteresismenu").removeClass("active"); // Remove both active class
        $("#coolerbox .pidmenu").removeClass("active"); // Remove both active class
        if (radioValue === "pid") {
          // Display the pidmenu or hysteresismenu accordingly
          $("#coolerbox .pidmenu").addClass("active");
        } else {
          $("#coolerbox .hysteresismenu").addClass("active");
        }
      });

      // Onclick heater mode select
      $("input[name='hmode']").click(function () {
        var radioValue = $("input[name='hmode']:checked").val(); // Get the value of checked radio
        $("#heaterbox .hysteresismenu").removeClass("active"); // Remove both active class
        $("#heaterbox .pidmenu").removeClass("active"); // Remove both active class
        if (radioValue === "pid") {
          // Display the pidmenu or hysteresismenu accordingly
          $("#heaterbox .pidmenu").addClass("active");
        } else {
          $("#heaterbox .hysteresismenu").addClass("active");
        }
      });

      $(".absolute-overlay").addClass("loaded");
    });
    const check = setInterval(function () {
      // Function to check every 0.1s if bondKey is available, then execute reloadStatus() and destroy itself.
      // if ($("#dashboard #deviceheader dummy").attr("class") != "") {
      //   reloadStatus();
      //   clearInterval(check); // kill after executed
      // }
    }, 100);
  }
}
