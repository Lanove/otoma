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
  function drawChart(chartObj, element, config) {
    if (chartObj != null) {
      chartObj.destroy();
      chartObj.config = config;
      //redraw the chart
      chartObj.update();
    } else {
      // Get the context of the canvas element we want to select
      chartObj = new Chart(element, config);
    }
  }
  ////////////////////////////////////////////////////////////////////

  function updateBar(id, percentage, label) {
    var elem = document.getElementById(id);
    elem.style.width = percentage + "%";
    elem.innerHTML = label;
  }

  function convertToDateLong(arg) {
    var buffer = arg.split("-");
    let month = [
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
    ][parseInt(buffer[1]) - 1];
    return buffer[2] + " " + month + " " + buffer[0];
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
    var nexusChart,
      config = {
        type: "line",
        data: {
          datasets: [
            {
              fill: false,
              label: "Suhu",
              backgroundColor: "#002bb8",
              borderColor: "#002bb8",
              borderWidth: 2,
              pointRadius: 2,
            },
            {
              fill: false,
              label: "Humiditas",
              backgroundColor: "#b8002b",
              borderColor: "#b8002b",
              borderWidth: 2,
              pointRadius: 2,
            },
          ],
        },
        options: {
          maintainAspectRatio: false,
          responsive: true,
          title: {
            display: true,
            text: "sample text",
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
      };

    // Draw chart function that is specific to nexus device
    function redrawChart(plotData) {
      if (plotData.available) {
        $("#goverlay").removeClass("active");
        $("#goverlay p").text("");
        var chartData = { humidData: [], tempData: [], label: [] };
        for (c in plotData.data) {
          chartData.tempData[c] = parseInt(plotData.data[c].data1);
          chartData.humidData[c] = parseInt(plotData.data[c].data2);
          chartData.label[c] = plotData.data[c].timestamp.slice(0, 5);
        }
        config.options.title.text =
          "Grafik pada " + convertToDateLong(plotData.plotDate);
        config.data.labels = chartData.label;
        config.data.datasets[0].data = chartData.tempData;
        config.data.datasets[1].data = chartData.humidData;
        drawChart(nexusChart, $("#graph"), config);
      } else {
        drawChart(nexusChart, $("#graph"), {});
        $("#goverlay").addClass("active");
        $("#goverlay p").text(
          "Grafik pada " +
            convertToDateLong(plotData.plotDate) +
            " tidak tersedia. Hal ini disebabkan karena kontroller anda tidak online pada tanggal tersebut, pastikan kontroller anda tetap tersambung ke internet agar kontroller dapat mengirim data grafik ke database"
        );
      }
    }
    // Anypicker custom trigger onSet
    function spsetOut(oSelectedValues) {
      $("#spvalue").text(String(oSelectedValues.values[0].label) + "°C");
      return oSelectedValues.values[0].label;
    }
    function setTrigger(sOutput) {
      const requestedDate =
        sOutput.values[0].label +
        "-" +
        sOutput.values[1].label +
        "-" +
        sOutput.values[2].label;
      requestAJAX(
        "NexusService",
        {
          token: getMeta("token"),
          bondKey: $("#dashboard #deviceheader dummy").attr("class"),
          requestType: "loadPlot",
          date: requestedDate,
        },
        function (response) {
          redrawChart(JSON.parse(response).plot);
        }
      );
      return requestedDate;
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
          // Adjust Thermocontrol Input text value based on database
          if (parseJson.nexusBond.coolerInfo[0] === "pid") {
            $("#ckp").val(parseJson.nexusBond.coolerInfo[1]);
            $("#cki").val(parseJson.nexusBond.coolerInfo[2]);
            $("#ckd").val(parseJson.nexusBond.coolerInfo[3]);
            $("#cds").val(parseJson.nexusBond.coolerInfo[4]);
          } else {
            $("#cba").val(parseJson.nexusBond.coolerInfo[1]);
            $("#cbb").val(parseJson.nexusBond.coolerInfo[2]);
          }
          if (parseJson.nexusBond.heaterInfo[0] === "pid") {
            $("#hkp").val(parseJson.nexusBond.heaterInfo[1]);
            $("#hki").val(parseJson.nexusBond.heaterInfo[2]);
            $("#hkd").val(parseJson.nexusBond.heaterInfo[3]);
            $("#hds").val(parseJson.nexusBond.heaterInfo[4]);
          } else {
            $("#hba").val(parseJson.nexusBond.heaterInfo[1]);
            $("#hbb").val(parseJson.nexusBond.heaterInfo[2]);
          }
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

          // Initialize anypicker with correct date limit based on database
          var oldest = parseJson.plot.oldest.oldestPlot.split("-");
          var newest = parseJson.plot.newest.newestPlot.split("-");
          for (index in newest) {
            // Convert every string array that is splitted into integer array
            newest[index] = parseInt(newest[index]);
            oldest[index] = parseInt(oldest[index]);
          }
          oldest[1]--; // Dk what is this, but keep it.
          $("#dateselector").unbind().removeData(); // Remove any anypicker instance to make sure it's not duplicating.
          $("#dateselector").AnyPicker({
            // Create anypicker instance
            mode: "datetime",
            dateTimeFormat: "yyyy-MM-dd",
            lang: "id",
            formatOutput: setTrigger,
            minValue: new Date(oldest[0], oldest[1], 01),
            maxValue: new Date(newest[0], newest[1], 00),
          });

          redrawChart(parseJson.plot);
        }
      );
    }

    $(document).ready(function () {
      Chart.defaults.global.defaultFontColor = "white";
      Chart.defaults.global.elements.rectangle.borderWidth = 2;
      // Awal loading page load device yang paling atas.
      loadDeviceInformation("master");
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
