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
    chartApo[chartObj].destroy();
    createChart(chartApo, chartElement, chartConfig, chartObj);
    updateChart(chartApo[chartObj], apoPlot.label, apoPlot.data);
  }
  ////////////////////////////////////////////////////////////////////

  function updateBar(id, percentage, label) {
    var elem = document.getElementById(id);
    elem.style.width = percentage + "%";
    elem.innerHTML = label;
  }
  function requestAJAX(fileName, jsonobject, callback = function () {}) {
    var xhr = new XMLHttpRequest();
    var json = JSON.stringify(jsonobject);
    xhr.open("POST", "api/" + fileName + ".php");
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
      options = {
        maintainAspectRatio: false,
        responsive: true,
        title: {
          display: true,
          text: "Grafik Suhu pada tanggal 16 Agustus 2020",
          fontFamily: "'Poppins',sans-serif",
          fontStyle: "normal",
        },
        tooltips: {
          mode: "index",
          intersect: false,
        },
        hover: {
          mode: "nearest",
          intersect: true,
        },
      },
      tempConfig = {
        type: "line",
        data: {
          datasets: [
            {
              fill: false,
              label: "Suhu " /*+ date*/,
              backgroundColor: "#002bb8",
              borderColor: "#002bb8",
              borderWidth: 2,
              pointRadius: 2,
            },
          ],
        },
        options: options,
      },
      humidConfig = {
        type: "line",
        data: {
          datasets: [
            {
              fill: false,
              label: "Humiditas pada " /*+ date*/,
              backgroundColor: "rgba(33, 145, 251, 1)",
              borderColor: "rgba(33, 145, 251, 1)",
              borderWidth: 1,
            },
          ],
        },
        options: options,
      };
    function getLabel() {
      labeltable = [];
      for (var x = 0; x < 24; x++) {
        for (var i = 0; i < 2; i++) {
          labeltable[x * 4 + i] = String(x) + ":" + String(i * 30);
        }
      }
      return labeltable;
    }

    // Anypicker custom trigger onSet
    function spsetOut(oSelectedValues) {
      $("#spvalue").text(String(oSelectedValues.values[0].label) + "°C");
      return oSelectedValues.values[0].label;
    }
    function setTrigger(sOutput) {
      return (
        sOutput.values[0].label +
        "-" +
        sOutput.values[1].label +
        "-" +
        sOutput.values[2].label
      );
    }
    /////////////////////////////////////////////////

    // Function that is triggered when operation

    function modeCallback(mode, docchi) {
      if (docchi === "cooler") {
        $("#coolerbox .hysteresismenu").removeClass("active"); // Remove both active class
        $("#coolerbox .pidmenu").removeClass("active"); // Remove both active class
        if (mode === "pid") {
          // Display the pidmenu or hysteresismenu accordingly
          $("#coolerbox .pidmenu").addClass("active");
        } else {
          $("#coolerbox .hysteresismenu").addClass("active");
        }
      } else if (docchi === "heater") {
        $("#heaterbox .hysteresismenu").removeClass("active"); // Remove both active class
        $("#heaterbox .pidmenu").removeClass("active"); // Remove both active class
        if (mode === "pid") {
          // Display the pidmenu or hysteresismenu accordingly
          $("#heaterbox .pidmenu").addClass("active");
        } else {
          $("#heaterbox .hysteresismenu").addClass("active");
        }
      } else if (docchi === "thermo") {
        if ($("input[name='operation']:checked").val() === "auto") {
          // Only do shit when we are on auto mode
          $("#heateroverlay").removeClass("active"); // Remove both active overlay class
          $("#cooleroverlay").removeClass("active"); // Remove both active overlay class
          $("#heateroverlay p").text(""); // Remove text from overlay
          $("#cooleroverlay p").text(""); // Remove text from overlay
          if (mode === "heat") {
            $("#cooleroverlay").addClass("active"); // Overlay the cooler
            $("#cooleroverlay p").text(
              "Hanya tersedia dalam mode Cooling atau Dual"
            ); // Add text to overlay
          } else if (mode === "cool") {
            $("#heateroverlay").addClass("active"); // Overlay the heater
            $("#heateroverlay p").text(
              "Hanya tersedia dalam mode Heating atau Dual"
            ); // Add text to overlay
          }
        }
      } else if (docchi === "operation") {
        $("#heateroverlay").removeClass("active"); // Remove both active overlay class
        $("#cooleroverlay").removeClass("active"); // Remove both active overlay class
        $("#setpointoverlay").removeClass("active"); // Remove both active overlay class
        $("#heateroverlay p").text(""); // Remove text from overlay
        $("#cooleroverlay p").text(""); // Remove text from overlay
        if (mode === "manual") {
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
      }
    }

    function loadDeviceInformation(master) {
      requestAJAX(
        "NexusService",
        {
          requestType: "loadDeviceInformation",
          master: master,
          token: getMeta("token"),
        },
        function callback(response) {
          var parseJson = JSON.parse(response);

          // Add device bondKey information to dummy class within main header
          $("#deviceheader dummy").removeClass();
          $("#deviceheader dummy").addClass(parseJson["deviceInfo"]["bondKey"]);

          if (parseJson.otherName) {
            // If user had more than one device
            // Add text and icon to the title of the main header
            $("#deviceheader .text").text(
              parseJson["deviceInfo"]["masterName"]
            );
            $("#deviceheader .text").append(
              "<i class = 'fas fa-caret-down'></i>"
            );
            //Add submasters name that user had to dropdown
            $("#deviceheader .dropdown-menu").html("");
            for (x in parseJson.otherName) {
              $("#deviceheader .dropdown-menu").append(
                '<a href="#" class="dropdown-item ' +
                  "subMasterName" +
                  String(x) +
                  '">' +
                  parseJson.otherName[x]["masterName"] +
                  "</a>"
              );
            }
          } else {
            // If user had just only one device then
            $("#deviceheader .dropdown").html(""); // remove dropdown menu
            $("#deviceheader .dropdown").prepend(
              "<p class='text'>" +
                parseJson["deviceInfo"]["masterName"] +
                "</p>"
            ); // Add name but without caret or dropdown
          }

          // Self explanation
          $("#tempnow").text(parseJson.nexusBond.tempNow + "°C");
          $("#humidnow").text(parseJson.nexusBond.humidNow + "%");
          $("#spvalue").text(parseJson.nexusBond.sp + "°C");
          $("#spsetting").val(parseJson.nexusBond.sp);
          $("#auxname1").text(parseJson.nexusBond.auxName1);
          $("#auxname2").text(parseJson.nexusBond.auxName2);

          // Adjust the position of switch based on database value
          $("#aux1Switch").prop(
            "checked",
            Boolean(Number(parseJson.nexusBond.auxStatus1))
          );
          $("#aux2Switch").prop(
            "checked",
            Boolean(Number(parseJson.nexusBond.auxStatus2))
          );

          // Get each data by splitting/exploding the string
          parseJson.nexusBond.thercoInfo = parseJson.nexusBond.thercoInfo.split(
            "%"
          );
          parseJson.nexusBond.heaterInfo = parseJson.nexusBond.heaterInfo.split(
            "%"
          );
          parseJson.nexusBond.coolerInfo = parseJson.nexusBond.coolerInfo.split(
            "%"
          );

          // Adjust thermocontrols radio checked position based on database value
          $(
            "input[name=operation][value='" +
              parseJson.nexusBond.thercoInfo[0] +
              "']"
          ).prop("checked", true);
          $(
            "input[name=thermmode][value='" +
              parseJson.nexusBond.thercoInfo[1] +
              "']"
          ).prop("checked", true);
          $(
            "input[name=hmode][value='" +
              parseJson.nexusBond.heaterInfo[0] +
              "']"
          ).prop("checked", true);
          $(
            "input[name=cmode][value='" +
              parseJson.nexusBond.coolerInfo[0] +
              "']"
          ).prop("checked", true);

          // Adjust overlay and display of thermocontrol based on database value
          modeCallback($("input[name='operation']:checked").val(), "operation");
          modeCallback($("input[name='thermmode']:checked").val(), "thermo");
          modeCallback($("input[name='hmode']:checked").val(), "heater");
          modeCallback($("input[name='cmode']:checked").val(), "cooler");
        }
      );
    }

    $(document).ready(function () {
      // Awal loading page load device yang paling atas.
      loadDeviceInformation("master");
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
      refreshChart(
        nexuschart,
        "temp",
        document.getElementById("tempgraph").getContext("2d"),
        tempConfig,
        {
          label: getLabel(),
          data: [],
        }
      );
      // Enable popover
      $('[data-toggle="popover"]').popover({ html: true });

      // Enable anypicker on humid and temp dateselector
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, "0");
      var mm = String(today.getMonth() + 1).padStart(2, "0"); //January is 0!
      var yyyy = today.getFullYear();

      today = yyyy + "-" + mm + "-" + dd;
      $("#humidds").val(today); // set today as default value of datepicker
      $("#tempds").val(today); // set today as default value of datepicker
      $("#humidds").unbind().removeData(); // Remove any anypicker instance to make sure it's not duplicating.
      $("#tempds").unbind().removeData(); // Remove any anypicker instance to make sure it's not duplicating.
      $("#tempds").AnyPicker({
        mode: "datetime",
        dateTimeFormat: "yyyy-MM-dd",
        lang: "id",
        formatOutput: setTrigger,
        // minValue: new Date(),
      });
      $("#humidds").AnyPicker({
        mode: "datetime",
        dateTimeFormat: "yyyy-MM-dd",
        lang: "id",
        formatOutput: setTrigger,
        // minValue: new Date(),
      });
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
        modeCallback(radioValue, "operation");
      });

      // Onclick thermocontroller mode select
      $("input[name='thermmode']").click(function () {
        var radioValue = $("input[name='thermmode']:checked").val(); // Get the value of checked radio
        modeCallback(radioValue, "thermo");
      });

      // Onclick cooler mode select
      $("input[name='cmode']").click(function () {
        var radioValue = $("input[name='cmode']:checked").val(); // Get the value of checked radio
        modeCallback(radioValue, "cooler");
      });

      // Onclick heater mode select
      $("input[name='hmode']").click(function () {
        var radioValue = $("input[name='hmode']:checked").val(); // Get the value of checked radio
        modeCallback(radioValue, "heater");
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
