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
        color: ["#b8002b", "#002bb8", "#00b82b", "#2b00b8"],

        tooltip: {
          trigger: "axis",
          position: function (pt) {
            return [pt[0], "10%"];
          },
          axisPointer: {
            type: "line",
            lineStyle: {
              color: "#fff",
            },
          },
        },
        grid: {
          left: 40,
          top: 50,
          right: 10,
          bottom: 25,
          borderColor: "#ccc",
        },
        title: {
          text: "sampletext",
          left: "center",
          textStyle: {
            color: "#ffffff",
            fontFamily: '"Poppins", sans-serif',
            fontWeight: "normal",
            fontSize: 18,
          },
        },
        textStyle: {
          color: "#ffffff",
          fontFamily: '"Poppins", sans-serif',
          fontSize: 16,
        },

        toolbox: {
          orient: "horizontal",
          right: -5,
          itemSize: 25,
          showTitle: false,
          iconStyle: {
            color: "#132e32",
          },
          textStyle: {
            color: "#fff",
          },
          feature: {
            dataZoom: {
              yAxisIndex: "none",
              icon: {
                zoom:
                  "path://M304 192v32c0 6.6-5.4 12-12 12h-56v56c0 6.6-5.4 12-12 12h-32c-6.6 0-12-5.4-12-12v-56h-56c-6.6 0-12-5.4-12-12v-32c0-6.6 5.4-12 12-12h56v-56c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v56h56c6.6 0 12 5.4 12 12zm201 284.7L476.7 505c-9.4 9.4-24.6 9.4-33.9 0L343 405.3c-4.5-4.5-7-10.6-7-17V372c-35.3 27.6-79.7 44-128 44C93.1 416 0 322.9 0 208S93.1 0 208 0s208 93.1 208 208c0 48.3-16.4 92.7-44 128h16.3c6.4 0 12.5 2.5 17 7l99.7 99.7c9.3 9.4 9.3 24.6 0 34zM344 208c0-75.2-60.8-136-136-136S72 132.8 72 208s60.8 136 136 136 136-60.8 136-136z",
                back:
                  "path://M304 192v32c0 6.6-5.4 12-12 12H124c-6.6 0-12-5.4-12-12v-32c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12zm201 284.7L476.7 505c-9.4 9.4-24.6 9.4-33.9 0L343 405.3c-4.5-4.5-7-10.6-7-17V372c-35.3 27.6-79.7 44-128 44C93.1 416 0 322.9 0 208S93.1 0 208 0s208 93.1 208 208c0 48.3-16.4 92.7-44 128h16.3c6.4 0 12.5 2.5 17 7l99.7 99.7c9.3 9.4 9.3 24.6 0 34zM344 208c0-75.2-60.8-136-136-136S72 132.8 72 208s60.8 136 136 136 136-60.8 136-136z",
              },
            },
            restore: {
              icon:
                "path://M440.935 12.574l3.966 82.766C399.416 41.904 331.674 8 256 8 134.813 8 33.933 94.924 12.296 209.824 10.908 217.193 16.604 224 24.103 224h49.084c5.57 0 10.377-3.842 11.676-9.259C103.407 137.408 172.931 80 256 80c60.893 0 114.512 30.856 146.104 77.801l-101.53-4.865c-6.845-.328-12.574 5.133-12.574 11.986v47.411c0 6.627 5.373 12 12 12h200.333c6.627 0 12-5.373 12-12V12c0-6.627-5.373-12-12-12h-47.411c-6.853 0-12.315 5.729-11.987 12.574zM256 432c-60.895 0-114.517-30.858-146.109-77.805l101.868 4.871c6.845.327 12.573-5.134 12.573-11.986v-47.412c0-6.627-5.373-12-12-12H12c-6.627 0-12 5.373-12 12V500c0 6.627 5.373 12 12 12h47.385c6.863 0 12.328-5.745 11.985-12.599l-4.129-82.575C112.725 470.166 180.405 504 256 504c121.187 0 222.067-86.924 243.704-201.824 1.388-7.369-4.308-14.176-11.807-14.176h-49.084c-5.57 0-10.377 3.842-11.676 9.259C408.593 374.592 339.069 432 256 432z",
            },
            saveAsImage: {
              icon:
                "path://M216 0h80c13.3 0 24 10.7 24 24v168h87.7c17.8 0 26.7 21.5 14.1 34.1L269.7 378.3c-7.5 7.5-19.8 7.5-27.3 0L90.1 226.1c-12.6-12.6-3.7-34.1 14.1-34.1H192V24c0-13.3 10.7-24 24-24zm296 376v112c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V376c0-13.3 10.7-24 24-24h146.7l49 49c20.1 20.1 52.5 20.1 72.6 0l49-49H488c13.3 0 24 10.7 24 24zm-124 88c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20zm64 0c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20z",
            },
          },
        },
        legend: {
          data: ["Suhu (°C)", "Humiditas (%)"],
          top: 25,
          textStyle: {
            color: "#ffffff",
            fontFamily: '"Poppins", sans-serif',
          },
        },
        xAxis: {
          type: "category",
          axisTick: {
            alignWithLabel: true,
          },
          boundaryGap: false,
          axisLabel: {
            fontSize: 14,
            fontFamily: '"Poppins", sans-serif',
          },
          axisLine: {
            lineStyle: {
              color: "#ffffff",
            },
          },
          data: ["sampletext"],
        },
        yAxis: {
          type: "value",
          axisLabel: {
            fontSize: 14,
            fontFamily: '"Poppins", sans-serif',
          },
          axisLine: {
            lineStyle: {
              color: "#e9e9e9",
            },
          },
        },
        dataZoom: [
          {
            type: "inside",
            start: 0,
            end: 100,
          },
        ],
        series: [
          {
            name: "Suhu (°C)",
            type: "line",
            data: [],
            smooth: true,
            symbol: "none",
            sampling: "average",
          },
          {
            name: "Humiditas (%)",
            type: "line",
            data: [],
            smooth: true,
            symbol: "none",
            sampling: "average",
          },
        ],
      };
    $(window).on("resize", function () {
      if (nexusChart != null && nexusChart != undefined) {
        nexusChart.resize();
      }
    });
    // Draw chart function that is specific to nexus device
    function redrawChart(plotData) {
      if (nexusChart) {
        nexusChart.dispose();
      }
      if (plotData.available) {
        $("#goverlay").removeClass("active");
        $("#goverlay p").text("");
        var chartData = { humidData: [], tempData: [], label: [] };
        for (c in plotData.data) {
          chartData.tempData[c] = parseInt(plotData.data[c].data1);
          chartData.humidData[c] = parseInt(plotData.data[c].data2);
          chartData.label[c] = plotData.data[c].timestamp.slice(0, 5);
        }

        config.title.text =
          "Grafik pada " + convertToDateLong(plotData.plotDate);
        config.xAxis.data = chartData.label;
        config.series[0].data = chartData.tempData;
        config.series[1].data = chartData.humidData;

        nexusChart = echarts.init(document.getElementById("graph"));
        nexusChart.setOption(config);
      } else {
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
      if (requestedDate !== $("#datebuffer").val()) {
        // Only load graph when requested graph is different than current showed graph
        $("#graphoverlay").addClass("active");
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
            $("#graphoverlay").removeClass("active");
          }
        );
      }
      $("#datebuffer").val(requestedDate);
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

          const mobileListener = function (e) {
            if (e.matches) {
              nexusChart.setOption({
                toolbox: {
                  feature: {
                    dataZoom: {
                      show: false,
                    },
                    restore: {
                      show: false,
                    },
                  },
                },
              });
            }
          };
          const tabletListener = function (e) {
            if (e.matches) {
              nexusChart.setOption({
                toolbox: {
                  feature: {
                    dataZoom: {
                      show: true,
                    },
                    restore: {
                      show: true,
                    },
                  },
                },
              });
            }
          };
          const checkMobile = window.matchMedia(
            "screen and (max-width: 611px)"
          );
          const checkTablet = window.matchMedia(
            "screen and (min-width: 612px) and (max-width: 991px)"
          );
          mobileListener(checkMobile);
          tabletListener(checkTablet);
          checkMobile.addListener(mobileListener);
          checkTablet.addListener(tabletListener);
        }
      );
    }

    $(document).ready(function () {
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
      $("#dateselector,#datebuffer").val(today); // set today as default value of datepicker

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
