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

  function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob([csv], { type: "text/csv" });

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();
  }
  function exportTableToCSV(tableID, filename) {
    var csv = [];
    var rows = $("#" + tableID).find("tr");
    for (var i = 0; i < rows.length; i++) {
      var row = [],
        cols = rows[i].querySelectorAll("td, th");

      for (var j = 0; j < cols.length; j++) row.push(cols[j].innerText);

      csv.push(row.join(","));
    }

    // Download CSV file
    downloadCSV(csv.join("\n"), filename + ".csv");
  }

  function exportTableToExcel(tableID, filename = "") {
    var downloadLink;
    var dataType = "application/vnd.ms-excel";
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, "%20");

    // Specify file name
    filename = filename ? filename + ".xls" : "excel_data.xls";

    // Create download link element
    downloadLink = document.createElement("a");

    document.body.appendChild(downloadLink);

    if (navigator.msSaveOrOpenBlob) {
      var blob = new Blob(["\ufeff", tableHTML], {
        type: dataType,
      });
      navigator.msSaveOrOpenBlob(blob, filename);
    } else {
      // Create a link to the file
      downloadLink.href = "data:" + dataType + ", " + tableHTML;

      // Setting the file name
      downloadLink.download = filename;

      //triggering the function
      downloadLink.click();
    }
  }

  if (deviceBelonging.hasClass("maindevice")) {
  } else if (deviceBelonging.hasClass("nexusdevice")) {
    var nexusChart,
      config = {
        color: ["#b8002b", "#002bb8", "#00b82b", "#2b00b8"],
        tooltip: {
          trigger: "axis",
          position: function (pos, params, dom, rect, size) {
            if (pos[0] < size.viewSize[0] / 2) {
              return [pos[0], "-10%"];
            }
            return [pos[0] - 130, "-10%"];
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
              show: false,
            },
            dataView: {
              lang: ["Data View", "Close", "Refresh"],
              optionToContent: function (opt) {
                var axisData = opt.xAxis[0].data;
                var series = opt.series;
                const title = opt.title[0].text;
                var table =
                  `
                    <div style="position: absolute;top: 5px;right: 20px;" class="dropdown">
                      <button  data-toggle="dropdown" type="button" class="btn btn-primary">
                        Download <i class = 'fas fa-caret-down'></i>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="#0" onclick="exportTableToCSV('dataview','${title}');">CSV</a>
                        <a class="dropdown-item" href="#0" onclick="exportTableToExcel('dataview','${title}');">Excel</a>
                      </div>
                    </div>
                  ` +
                  '<table id="dataview" style="width:100%;text-align:center"><tbody><tr>' +
                  "<td></td>" +
                  "<td>Waktu:</td>" +
                  "<td>" +
                  series[0].name +
                  "</td>" +
                  "<td>" +
                  series[1].name +
                  "</td>" +
                  "</tr>";
                for (var i = 0, l = axisData.length; i < l; i++) {
                  table +=
                    "<tr>" +
                    "<td>" +
                    (i + 1) +
                    "</td>" +
                    "<td>" +
                    axisData[i] +
                    "</td>" +
                    "<td>" +
                    series[0].data[i] +
                    "</td>" +
                    "<td>" +
                    series[1].data[i] +
                    "</td>" +
                    "</tr>";
                }
                table += "</tbody></table>";
                return table;
              },
              icon:
                "path://M464 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V80c0-26.51-21.49-48-48-48zM224 416H64v-96h160v96zm0-160H64v-96h160v96zm224 160H288v-96h160v96zm0-160H288v-96h160v96z",
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
        const checkMobile = window.matchMedia("screen and (max-width: 611px)");
        const checkTablet = window.matchMedia(
          "screen and (min-width: 612px) and (max-width: 991px)"
        );
        mobileListener(checkMobile);
        tabletListener(checkTablet);
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
        const checkMobile = window.matchMedia("screen and (max-width: 611px)");
        const checkTablet = window.matchMedia(
          "screen and (min-width: 612px) and (max-width: 991px)"
        );
        mobileListener(checkMobile);
        tabletListener(checkTablet);
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

    // UI API
    function submitPar(id) {
      var par = [];
      if (id === "submitcpid") {
        par = [
          $("#ckp").val(),
          $("#cki").val(),
          $("#ckd").val(),
          $("#cds").val(),
        ];
      } else if (id === "submitchys") {
        par = [$("#cba").val(), $("#cbb").val()];
      } else if (id === "submithpid") {
        par = [
          $("#hkp").val(),
          $("#hki").val(),
          $("#hkd").val(),
          $("#hds").val(),
        ];
      } else if (id === "submithhys") {
        par = [$("#hba").val(), $("#hbb").val()];
      }
      requestAJAX(
        "NexusService",
        {
          requestType: "submitParameter",
          bondKey: $("#dashboard #deviceheader dummy").attr("class"),
          id: id,
          par: par,
          token: getMeta("token"),
        },
        function (response) {
          var parseJson = JSON.parse(response);
          var spanId = "";
          if (id === "submitcpid") spanId = "#spancpid";
          else if (id === "submithpid") spanId = "#spanhpid";
          else if (id === "submitchys") spanId = "#spanchys";
          else if (id === "submithhys") spanId = "#spanhhys";
          if (parseJson[0].failed && spanId !== "") {
            $(spanId).removeClass();
            $(spanId).addClass("tfailed");
            $(spanId).text(
              "Gagal mengupdate ke database, silahkan coba lagi sesaat kemudian"
            );
          } else {
            $(spanId).removeClass();
            $(spanId).addClass("tsucceed");
            $(spanId).text("Sukses mengupdate ke database");
            $("#hkp").val(parseJson[0].heaterPar[0][0].toFixed(2));
            $("#hki").val(parseJson[0].heaterPar[0][1].toFixed(2));
            $("#hkd").val(parseJson[0].heaterPar[0][2].toFixed(2));
            $("#hds").val(parseJson[0].heaterPar[0][3]);
            $("#hbb").val(parseJson[0].heaterPar[1][0].toFixed(2));
            $("#hba").val(parseJson[0].heaterPar[1][1].toFixed(2));
            $("#ckp").val(parseJson[0].coolerPar[0][0].toFixed(2));
            $("#cki").val(parseJson[0].coolerPar[0][1].toFixed(2));
            $("#ckd").val(parseJson[0].coolerPar[0][2].toFixed(2));
            $("#cds").val(parseJson[0].coolerPar[0][3]);
            $("#cbb").val(parseJson[0].coolerPar[1][0].toFixed(2));
            $("#cba").val(parseJson[0].coolerPar[1][1].toFixed(2));
          }
        }
      );
    }
    function switchToggle(id) {
      var checkBox = document.getElementById(id);
      requestAJAX(
        "NexusService",
        {
          requestType: "toggleSwitch",
          bondKey: $("#dashboard #deviceheader dummy").attr("class"),
          id: id,
          status: String(checkBox.checked),
          token: getMeta("token"),
        },
        function (response) {
          // DO SOMETHING IF THERE ARE SOME EXCEPTION SUCH AS CONDITIONAL
        }
      );
    }
    function modeCallback(mode, docchi) {
      if (docchi === "cmode") {
        $("#coolerbox .hysteresismenu").removeClass("active"); // Remove both active class
        $("#coolerbox .pidmenu").removeClass("active"); // Remove both active class
        if (mode === "pid") {
          // Display the pidmenu or hysteresismenu accordingly
          $("#coolerbox .pidmenu").addClass("active");
        } else {
          $("#coolerbox .hysteresismenu").addClass("active");
        }
      } else if (docchi === "hmode") {
        $("#heaterbox .hysteresismenu").removeClass("active"); // Remove both active class
        $("#heaterbox .pidmenu").removeClass("active"); // Remove both active class
        if (mode === "pid") {
          // Display the pidmenu or hysteresismenu accordingly
          $("#heaterbox .pidmenu").addClass("active");
        } else {
          $("#heaterbox .hysteresismenu").addClass("active");
        }
      } else if (docchi === "thermmode") {
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
      requestAJAX(
        "NexusService",
        {
          requestType: "modeSwitch",
          bondKey: $("#dashboard #deviceheader dummy").attr("class"),
          mode: mode,
          docchi: docchi,
          token: getMeta("token"),
        },
        function (response) {}
      );
    }

    ///////////////////////////////
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
          $("#heaterSwitch").prop(
            "checked",
            Boolean(Number(parseJson.nexusBond.htStatus))
          );
          $("#coolerSwitch").prop(
            "checked",
            Boolean(Number(parseJson.nexusBond.clStatus))
          );

          // Get each data by splitting/exploding the string
          parseJson.nexusBond.thercoInfo = parseJson.nexusBond.thercoInfo.split(
            "%"
          );
          parseJson.nexusBond.heaterPar = parseJson.nexusBond.heaterPar.split(
            "%"
          );
          parseJson.nexusBond.coolerPar = parseJson.nexusBond.coolerPar.split(
            "%"
          );
          for (var i = 0; i < 2; i++) {
            parseJson.nexusBond.heaterPar[i] = parseJson.nexusBond.heaterPar[
              i
            ].split("/");
            parseJson.nexusBond.coolerPar[i] = parseJson.nexusBond.coolerPar[
              i
            ].split("/");
          }
          // // Adjust Thermocontrol Input text value based on database
          $("#ckp").val(
            parseFloat(parseJson.nexusBond.coolerPar[0][0]).toFixed(2)
          );
          $("#cki").val(
            parseFloat(parseJson.nexusBond.coolerPar[0][1]).toFixed(2)
          );
          $("#ckd").val(
            parseFloat(parseJson.nexusBond.coolerPar[0][2]).toFixed(2)
          );
          $("#cds").val(parseJson.nexusBond.coolerPar[0][3]);
          $("#cba").val(
            parseFloat(parseJson.nexusBond.coolerPar[1][0]).toFixed(2)
          );
          $("#cbb").val(
            parseFloat(parseJson.nexusBond.coolerPar[1][1]).toFixed(2)
          );
          $("#hkp").val(
            parseFloat(parseJson.nexusBond.heaterPar[0][0]).toFixed(2)
          );
          $("#hki").val(
            parseFloat(parseJson.nexusBond.heaterPar[0][1]).toFixed(2)
          );
          $("#hkd").val(
            parseFloat(parseJson.nexusBond.heaterPar[0][2]).toFixed(2)
          );
          $("#hds").val(parseJson.nexusBond.heaterPar[0][3]);
          $("#hba").val(
            parseFloat(parseJson.nexusBond.heaterPar[1][0]).toFixed(2)
          );
          $("#hbb").val(
            parseFloat(parseJson.nexusBond.heaterPar[1][1]).toFixed(2)
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
            "input[name=hmode][value='" + parseJson.nexusBond.heaterMode + "']"
          ).prop("checked", true);
          $(
            "input[name=cmode][value='" + parseJson.nexusBond.coolerMode + "']"
          ).prop("checked", true);

          // Adjust overlay and display of thermocontrol based on database value
          modeCallback($("input[name='operation']:checked").val(), "operation");
          modeCallback($("input[name='thermmode']:checked").val(), "thermmode");
          modeCallback($("input[name='hmode']:checked").val(), "hmode");
          modeCallback($("input[name='cmode']:checked").val(), "cmode");

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

          // Draw a chart
          redrawChart(parseJson.plot);

          // Add event listener for device size, if it's mobile just show the download image icon.
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
    function tabletListener(e) {
      if (nexusChart != null && nexusChart != undefined) {
        if (e.matches) {
          nexusChart.setOption({
            toolbox: {
              itemGap: 10,
              itemSize: 25,
            },
            title: {
              textStyle: {
                fontSize: 18,
              },
            },
          });
        }
      }
    }
    function mobileListener(e) {
      if (nexusChart != null && nexusChart != undefined) {
        if (e.matches) {
          nexusChart.setOption({
            title: {
              textStyle: {
                fontSize: 14,
              },
            },
            toolbox: {
              itemGap: 6,
              itemSize: 16,
            },
          });
        }
      }
    }
    var prevInput = {};
    $(document).ready(function () {
      // Awal loading page load device yang paling atas.
      loadDeviceInformation("master");
      // Enable popover
      $('[data-toggle="popover"]').popover({ html: true });
      // Client side filter (that is kinda suck) for parameter of therco
      $("#ckp, #cki, #ckd, #hkp, #hki, #hkd").on("input", function () {
        var v = parseFloat(this.value);
        if (isNaN(v)) {
          if (this.value.charAt(0) != "-") this.value = "";
          else this.value = "-";
        } else {
          if (v > 100) v = 100;
          else if (v < -100) v = -100;
          if (this.value.split(".").length > 1) this.value = v.toFixed(2);
          else this.value = v.toFixed(0);
        }
      });
      // Client side filter (that is kinda suck) for duration of pid
      $("#hds, #cds, #cba, #cbb, #hba, #hbb").on("input", function () {
        var v = parseFloat(this.value);
        if (isNaN(v)) {
          this.value = "";
        } else {
          if (this.id === "hds" || this.id === "cds") {
            if (v > 1000000) v = 1000000;
            else if (v < 0) v = 0;
          } else {
            if (v > 30) v = 30;
            else if (v < 0) v = 0;
          }
          this.value = v.toFixed(0);
        }
      });
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

      // Onclick mode select
      $(
        "input[name='cmode'],input[name='hmode'],input[name='thermmode'],input[name='operation']"
      ).click(function () {
        modeCallback(this.value, this.name);
      });
    });
    const check = setInterval(function () {
      // Function to check every 0.1s if bondKey is available, then execute reloadStatus() and destroy itself.
      if ($("#dashboard #deviceheader dummy").attr("class") != "") {
        $(".absolute-overlay").addClass("loaded");
        //   reloadStatus();
        clearInterval(check); // kill after executed
      }
    }, 100);
  }
}
