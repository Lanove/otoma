var deviceBelonging = $("#bigdevicebox");

$.fn.animateRotate = function (start, end, duration, easing, complete) {
  var args = $.speed(duration, easing, complete);
  var step = args.step;
  return this.each(function (i, e) {
    args.complete = $.proxy(args.complete, e);
    args.step = function (now) {
      $.style(e, "transform", "rotate(" + now + "deg)");
      if (step) return step.apply(e, arguments);
    };

    $({ deg: start }).animate({ deg: end }, args);
  });
};
function retractSidebar() {
  $(".sidebarCollapse .close").removeClass("active");
  $(".sidebarCollapse .open").addClass("active");
  $("#sidebar").removeClass("active");
  $(".overlay").removeClass("active");
}
function requestAJAX(
  fileName,
  jsonobject,
  callback = function () {},
  timeOut = 0,
  timeOutHandler = function () {}
) {
  var json = JSON.stringify(jsonobject);
  $.ajax({
    type: "POST",
    url: "api/" + fileName + ".php",
    contentType: "application/json; charset=utf-8",
    data: json,
    timeout: timeOut,
    success: function (msg) {
      callback(msg);
    },
    error: function (xhr, textStatus, errorThrown) {
      if (timeOut != 0) {
        if (textStatus == "timeout") timeOutHandler(textStatus);
        else
          bootbox.alert({
            size: "large",
            title: "Gagal terhubung ke server!",
            message: `Sepertinya anda tidak mempunyai koneksi ke internet<br>${errorThrown}`,
            closeButton: false,
            buttons: {
              ok: {
                label: "Tutup",
              },
            },
          });
      }
    },
  });
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

function tabletListener(e) {
  if (e.matches) {
    $("#dashboard .navbar").css("padding", "0.5rem 1rem");
    $("#dashboard .nexustcon .switch-field label").css("padding", "8px 16px");
    $("#dashboard #ultraM label").css("padding", "8px 16px");
    $("#dashboard .numin").css("max-width", "180px");
    if (nexusChart != null && nexusChart != undefined) {
      nexusChart.setOption({
        toolbox: {
          itemGap: 10,
          itemSize: 25,
          orient: "horizontal",
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
  if (e.matches) {
    $("#dashboard .navbar").css("padding", "0.5rem 1rem");
    $("#dashboard .nexustcon .switch-field label").css("padding", "8px 16px");
    $("#dashboard #ultraM label").css("padding", "8px 16px");
    $("#dashboard .numin").css("max-width", "180px");
    if (nexusChart != null && nexusChart != undefined) {
      nexusChart.setOption({
        title: {
          textStyle: {
            fontSize: 14,
          },
        },
        toolbox: {
          itemGap: 6,
          itemSize: 16,
          orient: "horizontal",
        },
      });
    }
  }
}
function ultraMobileListener(e) {
  if (e.matches) {
    $("#dashboard .navbar").css("padding", "0");
    $("#dashboard .nexustcon .switch-field label").css("padding", "8px 10px");
    $("#dashboard #ultraM label").css("padding", "8px 6px");
    $("#dashboard .numin").css("max-width", "140px");

    if (nexusChart != null && nexusChart != undefined) {
      nexusChart.setOption({
        title: {
          textStyle: {
            fontSize: 14,
          },
        },
        toolbox: {
          itemGap: 4,
          itemSize: 16,
          orient: "vertical",
        },
      });
    }
  }
}

$(window).on("resize", function () {
  if (nexusChart != null && nexusChart != undefined) {
    // Add event listener for device size, if it's mobile just show the download image icon.
    const checkUltraMobile = window.matchMedia("screen and (max-width: 380px)");
    const checkMobile = window.matchMedia(
      "screen and (min-width: 381px) and (max-width: 611px)"
    );
    const checkTablet = window.matchMedia("screen and (min-width: 612px)");
    ultraMobileListener(checkUltraMobile);
    mobileListener(checkMobile);
    tabletListener(checkTablet);
    nexusChart.resize();
  }
});

function pageChanger() {
  var id = this.id;
  var helppick = null;
  $(".absolute-overlay").removeClass("loaded");
  $("#content").html("");
  var pageCon = "";
  if (id == "contactUs") pageCon = "pagecon/contact-us.php";
  else if (id == "goHelp") pageCon = "pagecon/help-page.php";
  else if (id == "goAccount") pageCon = "pagecon/account-setting.php";
  else if (id == "progHelp") {
    helppick = "progHelp";
    id = "goHelp";
    pageCon = "pagecon/help-page.php";
  }
  $("#content").load(pageCon, function (responseTxt, statusTxt, xhr) {
    if (statusTxt == "success") {
      if (id == "contactUs") {
        $("#submitContact").click(function () {
          if (
            $("#nameContact").val() != "" &&
            $("#phoneContact").val() != "" &&
            $("#emailContact").val() != "" &&
            $("#messageContact").val() != ""
          ) {
            $("#notifContact").text("Mengirimkan pesan anda...");
            requestAJAX(
              "GlobalService",
              {
                requestType: "submitMessage",
                token: getMeta("token"),
                name: $("#nameContact").val(),
                phone: $("#phoneContact").val(),
                email: $("#emailContact").val(),
                message: $("#messageContact").val(),
              },
              function (response) {
                $("#nameContact").val("");
                $("#phoneContact").val("");
                $("#emailContact").val("");
                $("#messageContact").val("");
                $("#notifContact").text("Pesan terkirim!");
              },
              5000,
              function (status) {
                bootbox.alert({
                  size: "large",
                  title: "Gagal mengirim pesan",
                  message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian<br>Status Error : ${status}`,
                  closeButton: false,
                  buttons: {
                    ok: {
                      label: "Tutup",
                    },
                  },
                });
              }
            );
          } else {
            $("#notifContact").text(
              "Gagal mengirim pesan, terdapat form isian yang belum di isi"
            );
          }
          setTimeout(function () {
            $("#notifContact").text("");
          }, 15000);
        });
        $("#xEr").popover();
        $("#xeR").popover();
        $(".absolute-overlay").addClass("loaded");
      } else if (id == "goAccount") {
        requestAJAX(
          "GlobalService",
          {
            requestType: "requestEmail",
            token: getMeta("token"),
          },
          function (response) {
            $("#ACemail").val(response);
          },
          8000,
          function (status) {
            bootbox.alert({
              size: "large",
              title: "Gagal memuat laman",
              message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian<br>Status Error : ${status}`,
              closeButton: false,
              buttons: {
                ok: {
                  label: "Tutup",
                },
              },
            });
          }
        );
        $("#ACpasswordSubmit").click(function () {
          $("#ACpasswordNotif").removeClass();
          $("#ACpasswordNotif").text("Memproses permintaan anda...");
          requestAJAX(
            "GlobalService",
            {
              requestType: "passwordChange",
              token: getMeta("token"),
              oldPw: $("#ACpasswordLama").val(),
              newPw: $("#ACpasswordBaru").val(),
              confPw: $("#ACconfirmBaru").val(),
            },
            function (response) {
              const parseJson = JSON.parse(response);
              if (parseJson.status == "failure")
                $("#ACpasswordNotif").addClass("tfailed");
              else if (parseJson.status == "success")
                $("#ACpasswordNotif").addClass("tsucceed2");
              $("#ACpasswordNotif").text(parseJson.message);
            },
            4000,
            function (status) {
              bootbox.alert({
                size: "large",
                title: "Gagal mengganti password",
                message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian<br>Status Error : ${status}`,
                closeButton: false,
                buttons: {
                  ok: {
                    label: "Tutup",
                  },
                },
              });
            }
          );

          setTimeout(function () {
            $("#ACpasswordNotif").text("");
          }, 15000);
        });

        $("#ACdelete").click(function () {
          bootbox.prompt({
            message: `Aksi ini tidak dapat dipulihkan. Seluruh informasi yang terhubung dengan akun ini akan terhapus seluruhnya. Mohon masukkan password anda dengan benar apabila anda yakin.`,
            title: "Apakah anda yakin ingin menghapus akun ini?",
            inputType: "password",
            buttons: {
              cancel: {
                label: '<i class="fa fa-times"></i> Tidak',
                className: "btn-secondary",
              },
              confirm: {
                label: '<i class="fa fa-check"></i> Ya',
                className: "btn-danger",
              },
            },
            callback: function (result) {
              if (result != null) {
                requestAJAX(
                  "GlobalService",
                  {
                    requestType: "deleteAccount",
                    token: getMeta("token"),
                    password: result,
                  },
                  function (response) {
                    const parseJson = JSON.parse(response);
                    bootbox.alert({
                      size: "large",
                      title: parseJson.title,
                      message: parseJson.message,
                      closeButton: false,
                      buttons: {
                        ok: {
                          label: "Tutup",
                        },
                      },
                    });
                    if (parseJson.status == "success")
                      setTimeout(function () {
                        window.location.replace("logout.php");
                      }, 3000);
                  },
                  4000,
                  function (status) {
                    bootbox.alert({
                      size: "large",
                      title: "Terjadi kesalahan",
                      message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian<br>Status Error : ${status}`,
                      closeButton: false,
                      buttons: {
                        ok: {
                          label: "Tutup",
                        },
                      },
                    });
                  }
                );
              }
            },
          });
        });
        $("#ACemailSubmit").click(function () {
          $("#ACemailNotif").removeClass();
          $("#ACemailNotif").text("Memproses permintaan anda...");
          requestAJAX(
            "GlobalService",
            {
              requestType: "emailChange",
              token: getMeta("token"),
              email: $("#ACemail").val(),
            },
            function (response) {
              const parseJson = JSON.parse(response);
              if (parseJson.status == "failure")
                $("#ACemailNotif").addClass("tfailed");
              else if (parseJson.status == "success")
                $("#ACemailNotif").addClass("tsucceed2");
              $("#ACemailNotif").text(parseJson.message);
            },
            4000,
            function (status) {
              bootbox.alert({
                size: "large",
                title: "Gagal mengganti email",
                message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian<br>Status Error : ${status}`,
                closeButton: false,
                buttons: {
                  ok: {
                    label: "Tutup",
                  },
                },
              });
            }
          );

          setTimeout(function () {
            $("#ACemailNotif").text("");
          }, 15000);
        });

        $(".absolute-overlay").addClass("loaded");
      } else if (id == "goHelp") {
        $("#main-accordion").accordiom({
          autoClosing: false,
          showFirstItem: false,
          onLoad: function (accordionButton) {
            $(".absolute-overlay").addClass("loaded");
          },
          beforeChange: function () {
            const status = $(this).is(".on");
            $(this)
              .find(".caret")
              .animateRotate(status * 180, status * 180 + 180, 500);
          },
          afterchange: function () {},
        });

        $("#sub-accordion1, #sub-accordion2, #sub-accordion3").accordiom({
          showFirstItem: false,
          beforeChange: function () {
            const status = $(this).is(".on");
            $(this)
              .find(".caret")
              .animateRotate(status * 180, status * 180 + 180, 500);
          },

          onLoad: function (accordionButton) {},
        });
      }
      if (helppick != null) {
          $().accordiom.openItem("#main-accordion", 2);
      }
      retractSidebar();
    }
    if (status == "error") {
      bootbox.alert({
        size: "large",
        title: "Gagal memuat laman",
        message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian.`,
        closeButton: false,
        buttons: {
          ok: {
            label: "Tutup",
          },
        },
      });
    }
  });
}

$(document).ready(function () {
  // Add event listener for device size
  const checkUltraMobile = window.matchMedia("screen and (max-width: 380px)");
  const checkMobile = window.matchMedia(
    "screen and (min-width: 381px) and (max-width: 611px)"
  );
  const checkTablet = window.matchMedia("screen and (min-width: 612px)");
  ultraMobileListener(checkUltraMobile);
  mobileListener(checkMobile);
  tabletListener(checkTablet);
  checkUltraMobile.addListener(ultraMobileListener);
  checkMobile.addListener(mobileListener);
  checkTablet.addListener(tabletListener);

  if (!deviceBelonging.length) {
    $(".absolute-overlay").addClass("loaded");
  }
  $(document).on(
    "click",
    "#contactUs, #goHelp, #goAccount, #progHelp",
    pageChanger
  );
  $("#otomaIcon, #goHome").on("click", function () {
    if (deviceBelonging.length) {
      if (
        deviceBelonging.hasClass("nexusdevice") &&
        !$("#nexus-dashboard").length
      ) {
        retractSidebar();
        $(".absolute-overlay").removeClass("loaded");
        $("#content").html("");
        $("#content").load(
          "pagecon/nexus-device.php",
          function (responseTxt, statusTxt, xhr) {
            if (statusTxt == "success") {
              nexusFirstLoad(getBondKey());
              $(".absolute-overlay").addClass("loaded");
            }
            if (status == "error") {
              bootbox.alert({
                size: "large",
                title: "Gagal memuat laman",
                message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian`,
                closeButton: false,
                buttons: {
                  ok: {
                    label: "Tutup",
                  },
                },
              });
            }
          }
        );
      } else {
        retractSidebar();
      }
    } else {
      location.reload();
    }
  });

  $("#sidebar").mCustomScrollbar({ theme: "minimal" });
  $("#dismiss, .overlay").on("click", function () {
    retractSidebar();
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
      retractSidebar();
    }
  });
});

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

  function getBondKey() {
    return $("#bondContainer").val();
  }

  function setBondKey(value) {
    $("#bondContainer").val(value);
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
    const newLocal = / /g;
    var tableHTML = tableSelect.outerHTML.replace(newLocal, "%20");

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
            fontSize: 12,
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
          scale: true,
          type: "value",
          axisLabel: {
            fontSize: 12,
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
    jQuery.fn.sortDivs = function sortDivs() {
      $("> div", this[0]).sort(dec_sort).appendTo(this[0]);
      function dec_sort(a, b) {
        return $(b).data("sort") < $(a).data("sort") ? 1 : -1;
      }
    };
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
          chartData.tempData[c] = parseFloat(plotData.data[c].data1);
          chartData.humidData[c] = parseFloat(plotData.data[c].data2);
          chartData.label[c] = plotData.data[c].timestamp.slice(0, 5);
        }

        config.title.text =
          "Grafik pada " + convertToDateLong(plotData.plotDate);
        config.xAxis.data = chartData.label;
        config.series[0].data = chartData.tempData;
        config.series[1].data = chartData.humidData;

        nexusChart = echarts.init(document.getElementById("graph"));
        nexusChart.setOption(config);
        // Add event listener for device size, if it's mobile just show the download image icon.
        const checkUltraMobile = window.matchMedia(
          "screen and (max-width: 380px)"
        );
        const checkMobile = window.matchMedia(
          "screen and (min-width: 381px) and (max-width: 611px)"
        );
        const checkTablet = window.matchMedia("screen and (min-width: 612px)");
        ultraMobileListener(checkUltraMobile);
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

    function formatSHInput(sElemValue) {
      matches = sElemValue.match(/\d+/g);
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

    function formatJadwalInput(sElemValue) {
      var matches = [];
      if (
        sElemValue === null ||
        sElemValue === undefined ||
        sElemValue === "" ||
        sElemValue === "0"
      ) {
        matches[0] = "0";
        matches[1] = "0";
        matches[2] = "0";
      } else {
        matches = sElemValue.match(/\d+/g);
        for (i in matches) {
          if (
            matches[i] === null ||
            matches[i] === undefined ||
            matches[i] === "" ||
            matches[i] === "0"
          )
            matches[i] = "0";
        }
      }
      return matches;
    }

    function formatJadwalOutput(oSelectedValues) {
      var stringBuffer = "",
        numberPal = [":", ":", ""];
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

    function spsetOut(oSelectedValues) {
      var stringBuffer = "",
        numberPal = [".", ""];
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
      if (this.elem.id == "spsetting") {
        $("#spvalue").text(String(stringBuffer) + "°C");
        requestAJAX(
          "NexusService",
          {
            token: getMeta("token"),
            bondKey: getBondKey(),
            requestType: "changeSetpoint",
            data: stringBuffer,
          },
          function (response) {},
          3000,
          function (status) {
            bootbox.alert({
              size: "large",
              title: "Gagal mengganti setpoin suhu",
              message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian<br>Status Error : ${status}`,
              closeButton: false,
              buttons: {
                ok: {
                  label: "Tutup",
                },
              },
            });
          }
        );
      }
      return stringBuffer;
    }

    function createTextDataSource(content) {
      var data = [];
      for (var iTempIndex = 0; iTempIndex < content.length; iTempIndex++) {
        data.push({
          val: content[iTempIndex],
          label: content[iTempIndex],
        });
      }
      return data;
    }

    function buildTempOrHum(spaceNum, val) {
      $(`#condition${spaceNum} .content`).append(`
      <div class="item ctCd">
        <input style="max-width:50px;text-align:center;" type="text" class="form-control" id="nscmpCd${spaceNum}" value=">" readonly>
      </div>    
      <div class="item ctCd">
        <input style="max-width:70px;text-align:center;" type="text" class="form-control" id="nsvalCd${spaceNum}" value="0" readonly>
      </div>
      <div class="item ctCd">
          <span>Aksi</span>
          <input style="max-width:220px;text-align:center;" type="text" class="form-control" id="acCd${spaceNum}" readonly>
      </div>`);

      $("#nscmpCd" + spaceNum)
        .unbind()
        .removeData();
      $("#nscmpCd" + spaceNum).AnyPicker({
        // Create anypicker instance
        showComponentLabel: true,
        mode: "select",
        lang: "id",
        components: [
          {
            component: 0,
            name: "operator",
            label: "Operator",
            width: "50%",
            textAlign: "center",
          },
        ],
        dataSource: [
          {
            component: 0,
            data: createTextDataSource(["<", ">", "<=", ">="]),
          },
        ],
      });
      var oArrData = [];
      createDataSource(oArrData, [100, 9]);

      $("#nsvalCd" + spaceNum)
        .unbind()
        .removeData();
      $(`#nsvalCd${spaceNum}`).AnyPicker({
        // Add anypicker to every timer
        showComponentLabel: true,
        mode: "select",
        lang: "id",
        components: [
          {
            component: 0,
            name: "c",
            label: val == "Nilai Suhu" ? "Suhu (°C)" : "Humiditas (%)",
            width: "30%",
            textAlign: "center",
          },
          {
            component: 1,
            name: "d",
            label: "Koma",
            width: "30%",
            textAlign: "center",
          },
        ],
        dataSource: [
          {
            component: 0,
            data: oArrData[0],
          },
          {
            component: 1,
            data: oArrData[1],
          },
        ],
        parseInput: formatSHInput,
        formatOutput: spsetOut,
      });
    }

    function buildConditionalPicker(spaceNum) {
      $(`#condition${spaceNum} .content`).append(`
      <div class="item ctCd">
        <span>Jika</span>
        <input style="max-width:150px;text-align:center;" type="text" class="form-control" id="cndCd${spaceNum}" value="Output 1" readonly>
      </div>
      <div class="item ctCd">
        <span>Sedang</span>
        <input style="max-width:150px;text-align:center;" type="text" class="form-control" id="cnddCd${spaceNum}" value="Menyala" readonly>
      </div>
      <div class="item ctCd">
          <span>Aksi</span>
          <input style="max-width:220px;text-align:center;" type="text" class="form-control" id="acCd${spaceNum}" readonly>
      </div>`);

      $("#cndCd" + spaceNum)
        .unbind()
        .removeData();
      $("#cndCd" + spaceNum).AnyPicker({
        // Create anypicker instance
        showComponentLabel: true,
        mode: "select",
        lang: "id",
        components: [
          {
            component: 0,
            name: "komponen",
            label: "Komponen",
            width: "50%",
            textAlign: "center",
          },
        ],
        dataSource: [
          {
            component: 0,
            data: createTextDataSource([
              "Output 1",
              "Output 2",
              "Pemanas",
              "Pendingin"
            ]),
          },
        ],
      });

      $("#cnddCd" + spaceNum)
        .unbind()
        .removeData();
      $("#cnddCd" + spaceNum).AnyPicker({
        // Create anypicker instance
        showComponentLabel: true,
        mode: "select",
        lang: "id",
        components: [
          {
            component: 0,
            name: "kondisi",
            label: "Kondisi",
            width: "50%",
            textAlign: "center",
          },
        ],
        dataSource: [
          {
            component: 0,
            data: createTextDataSource(["Menyala", "Mati"]),
          },
        ],
      });
    }

    function buildTimer(spaceNum) {
      $(`#condition${spaceNum} .content`).append(`
      <div class="item ctCd">
        <span>Durasi</span>
        <input style="max-width:125px;text-align:center;" type="text" class="form-control" id="timerCd${spaceNum}" value="0h 0j 0m" readonly>
      </div>          
      <div class="item ctCd">
        <button class="btn btn-primary" style="margin-right:7.5px;" id="tstartCd${spaceNum}">Start</button>
        <button class="btn btn-primary" class="tstopCd${spaceNum}">Reset</button>
      </div>
      <div class="item ctCd">
          <span>Aksi</span>
          <input style="max-width:220px;text-align:center;" type="text" class="form-control" id="acCd${spaceNum}" readonly>
      </div>`);
      // Create anypicker object for timer selector
      var oArrData = [];
      createDataSource(oArrData, [30, 23, 59]); // Maximum duration is 30 day 23 hour 59 minute

      $("#timerCd" + spaceNum)
        .unbind()
        .removeData();
      $(`#timerCd${spaceNum}`).AnyPicker({
        // Add anypicker to every timer
        mode: "select",
        lang: "id",
        showComponentLabel: true,
        components: [
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
        dataSource: [
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
        ],
        parseInput: formatTimerInput,
        formatOutput: formatTimerOutput,
      });
    }
    var oAP1 = [],
      oAP2 = [];

    function buildDailyPicker(spaceNum) {
      $(`#condition${spaceNum} .content`).append(`
      <div class="item ctCd">
        <span>Dari</span>
        <input style="max-width:100px;text-align:center;" type="text" class="form-control" id="faCd${spaceNum}" readonly>
      </div>    
      <div class="item ctCd">
        <span>Hingga</span>
        <input style="max-width:100px;text-align:center;" type="text" class="form-control" id="feCd${spaceNum}" readonly>
      </div>
      <div class="item ctCd">
          <span>Aksi</span>
          <input style="max-width:220px;text-align:center;" type="text" class="form-control" id="acCd${spaceNum}" readonly>
      </div>`);
      var jadwalDataSource = [];
      createDataSource(jadwalDataSource, [23, 59, 59]);
      $("#faCd" + spaceNum)
        .unbind()
        .removeData();
      $(`#faCd${spaceNum}`).AnyPicker({
        mode: "select",
        lang: "id",
        showComponentLabel: true,
        components: [
          {
            component: 0,
            name: "j",
            label: "Jam",
            width: "33%",
            textAlign: "center",
          },
          {
            component: 1,
            name: "m",
            label: "Menit",
            width: "33%",
            textAlign: "center",
          },
          {
            component: 2,
            name: "d",
            label: "Detik",
            width: "33%",
            textAlign: "center",
          },
        ],
        dataSource: [
          {
            component: 0,
            data: jadwalDataSource[0],
          },
          {
            component: 1,
            data: jadwalDataSource[1],
          },
          {
            component: 2,
            data: jadwalDataSource[2],
          },
        ],
        parseInput: formatJadwalInput,
        formatOutput: formatJadwalOutput,
      });

      $("#feCd" + spaceNum)
        .unbind()
        .removeData();
      $(`#feCd${spaceNum}`).AnyPicker({
        mode: "select",
        lang: "id",
        showComponentLabel: true,
        components: [
          {
            component: 0,
            name: "j",
            label: "Jam",
            width: "33%",
            textAlign: "center",
          },
          {
            component: 1,
            name: "m",
            label: "Menit",
            width: "33%",
            textAlign: "center",
          },
          {
            component: 2,
            name: "d",
            label: "Detik",
            width: "33%",
            textAlign: "center",
          },
        ],
        dataSource: [
          {
            component: 0,
            data: jadwalDataSource[0],
          },
          {
            component: 1,
            data: jadwalDataSource[1],
          },
          {
            component: 2,
            data: jadwalDataSource[2],
          },
        ],
        parseInput: formatJadwalInput,
        formatOutput: formatJadwalOutput,
      });
    }

    function buildDateTimePicker(spaceNum) {
      $(`#condition${spaceNum} .content`).append(`
      <div class="item ctCd">
        <span>Dari</span>
        <input style="max-width:200px;text-align:center;" type="text" class="form-control" id="dfaCd${spaceNum}" readonly>
      </div>    
      <div class="item ctCd">
        <span>Hingga</span>
        <input style="max-width:200px;text-align:center;" type="text" class="form-control" id="dfeCd${spaceNum}" readonly>
      </div>
      <div class="item ctCd">
          <span>Aksi</span>
          <input style="max-width:220px;text-align:center;" type="text" class="form-control" id="acCd${spaceNum}" readonly>
      </div>`);
      $("#dfaCd" + spaceNum)
        .unbind()
        .removeData();

      $(`#dfaCd${spaceNum}`).AnyPicker({
        mode: "datetime",
        lang: "id",
        dateTimeFormat: "yyyy-MM-dd HH:mm",
        showComponentLabel: true,
        minValue: new Date(2020, 00, 01),
        maxValue: new Date(2025, 12, 31),
      });

      $("#dfeCd" + spaceNum)
        .unbind()
        .removeData();
      $(`#dfeCd${spaceNum}`).AnyPicker({
        mode: "datetime",
        lang: "id",
        dateTimeFormat: "yyyy-MM-dd HH:mm",
        showComponentLabel: true,
        minValue: new Date(2020, 00, 01),
        maxValue: new Date(2025, 12, 31),
      });
    }

    function buildActionPicker(spaceNum, acContent) {
      $("#acCd" + spaceNum)
        .unbind()
        .removeData();
      $("#acCd" + spaceNum).AnyPicker({
        // Create anypicker instance
        showComponentLabel: true,
        mode: "select",
        lang: "id",
        components: [
          {
            component: 0,
            name: "aksi",
            label: "Aksi",
            width: "50%",
            textAlign: "center",
          },
        ],
        dataSource: [
          {
            component: 0,
            data: createTextDataSource(acContent),
          },
        ],
      });
    }

    function appendConditionalChild(spaceNum, ifContent) {
      $("#conditional").append(`
      <div class="row" data-sort="${spaceNum}">
        <div class="col-12">
            <div class="nexuscond" id="condition${spaceNum}">
                <div class="numbox d-flex align-items-center justify-content-center">
                    <span>Program ${spaceNum}</span>
                </div>
                <div class="d-flex content justify-content-center">
                    <div class="item pgCd">
                        <span>Pemicu</span>
                        <input type="text" class="form-control" id="ifCd${spaceNum}" style="max-width:150px;text-align:center;" readonly>
                    </div>
                </div>
                <span id="nSpan${spaceNum}" style="text-align:center;"></span>
                <div class="d-flex align-items-center justify-content-center" style="padding-bottom:15px;">
                    <button class="btn btn-primary" style="margin-right:15px;" id="submitCd${spaceNum}">Update</button>
                    <button class="btn btn-primary" id="deleteCd${spaceNum}">Hapus</button>
                </div>
              </div>
          </div>
      </div>`);
      $("#conditional").sortDivs();

      $("#ifCd" + spaceNum)
        .unbind()
        .removeData();
      $(`#ifCd${spaceNum}`).AnyPicker({
        // Create anypicker instance
        showComponentLabel: true,
        mode: "select",
        lang: "id",
        formatOutput: pgacCdCallback,
        components: [
          {
            component: 0,
            name: "pemicu",
            label: "Pemicu",
            width: "50%",
            textAlign: "center",
          },
        ],
        dataSource: [
          {
            component: 0,
            data: createTextDataSource(ifContent),
          },
        ],
      });

      $(`#submitCd${spaceNum}, #deleteCd${spaceNum}`).unbind().removeData();
      $(`#submitCd${spaceNum}, #deleteCd${spaceNum}`).click(function () {
        var passedData = {};
        var span = $(`#nSpan${spaceNum}`);
        span.html("");
        if (this.id.match(/\D+/g) == "submitCd") {
          const ifVal = $(`#ifCd${spaceNum}`).val();
          var success = true;
          if (ifVal == "Nilai Suhu" || ifVal == "Nilai Humiditas") {
            passedData.nscmpCd = $(`#nscmpCd${spaceNum}`).val();
            passedData.nsvalCd = $(`#nsvalCd${spaceNum}`).val();
            passedData.acCd = $(`#acCd${spaceNum}`).val();
            if ($(`#nscmpCd${spaceNum}`).val() == "") {
              span.append(
                `<span class="tfailed" style="display:block;">Komparator tidak boleh kosong<span>`
              );
              success = false;
            }
            if ($(`#nsvalCd${spaceNum}`).val() == "") {
              span.append(
                ifVal == "Nilai Suhu"
                  ? `<span class="tfailed" style="display:block;">Nilai Suhu tidak boleh kosong<span>`
                  : `<span class="tfailed" style="display:block;">Nilai Humiditas tidak boleh kosong<span>`
              );
              success = false;
            }
            if ($(`#acCd${spaceNum}`).val() == "") {
              span.append(
                `<span class="tfailed" style="display:block;">Aksi tidak boleh kosong<span>`
              );
              success = false;
            }
          } else if (ifVal == "Jadwal Harian") {
            passedData.faCd = $(`#faCd${spaceNum}`).val();
            passedData.feCd = $(`#feCd${spaceNum}`).val();
            passedData.acCd = $(`#acCd${spaceNum}`).val();
            if ($(`#faCd${spaceNum}`).val() == "") {
              span.append(
                `<span class="tfailed" style="display:block;">Input Jam (Dari) tidak boleh kosong<span>`
              );
              success = false;
            }
            if ($(`#feCd${spaceNum}`).val() == "") {
              span.append(
                `<span class="tfailed" style="display:block;">Input Jam (Hingga) tidak boleh kosong<span>`
              );
              success = false;
            }
            if (passedData.feCd != "" && passedData.faCd != "") {
              const comparator = [
                passedData.faCd.split(":"),
                passedData.feCd.split(":"),
              ];
              if (
                comparator[0][0] * 3600 +
                  comparator[0][1] * 60 +
                  comparator[0][2] >
                comparator[1][0] * 3600 +
                  comparator[1][1] * 60 +
                  comparator[1][2]
              ) {
                span.append(
                  `<span class="tfailed" style="display:block;">Input Jam (Dari) tidak boleh melebihi dari Input Jam (Hingga)<span>`
                );
                success = false;
              }
            }
            if ($(`#acCd${spaceNum}`).val() == "") {
              span.append(
                `<span class="tfailed" style="display:block;">Aksi tidak boleh kosong<span>`
              );
              success = false;
            }
          } else if (ifVal == "Tanggal Waktu") {
            passedData.dfaCd = $(`#dfaCd${spaceNum}`).val();
            passedData.dfeCd = $(`#dfeCd${spaceNum}`).val();
            passedData.acCd = $(`#acCd${spaceNum}`).val();

            if ($(`#dfaCd${spaceNum}`).val() == "") {
              span.append(
                `<span class="tfailed" style="display:block;">Input Tanggal Waktu (Dari) tidak boleh kosong<span>`
              );
              success = false;
            }
            if ($(`#dfeCd${spaceNum}`).val() == "") {
              span.append(
                `<span class="tfailed" style="display:block;">Input Tanggal Waktu (Hingga) tidak boleh kosong<span>`
              );
              success = false;
            }
            if (
              $(`#dfeCd${spaceNum}`).val() != "" &&
              $(`#dfaCd${spaceNum}`).val() != ""
            ) {
              var dateFrom = $(`#dfaCd${spaceNum}`).val().split(" ");
              var dateTo = $(`#dfeCd${spaceNum}`).val().split(" ");
              const unixFrom = Date.parse(dateFrom);
              const unixTo = Date.parse(dateTo);
              const dateNow = new Date();
              // 0 is tanggal, 1 is month str, 2 year, 3 jam:menit
              if (unixFrom > unixTo) {
                span.append(
                  `<span class="tfailed" style="display:block;">Tanggal Waktu(Dari) tidak boleh melebihi Tanggal Waktu(Hingga)<span>`
                );
                success = false;
              }
              if (unixTo < dateNow.getTime()) {
                span.append(
                  `<span class="tfailed" style="display:block;">Tidak dapat memilih Tanggal Waktu(Hingga) yang telah berlalu dari waktu sekarang<span>`
                );
                success = false;
              }
            }
            if ($(`#acCd${spaceNum}`).val() == "") {
              span.append(
                `<span class="tfailed" style="display:block;">Aksi tidak boleh kosong<span>`
              );
              success = false;
            }
          } else if (ifVal == "Timer") {
            passedData.timerCd = $(`#timerCd${spaceNum}`).val();
            passedData.acCd = $(`#acCd${spaceNum}`).val();
            if ($(`#timerCd${spaceNum}`).val() == "") {
              span.append(
                `<span class="tfailed" style="display:block;">Durasi timer tidak boleh kosong<span>`
              );
              success = false;
            }
            if ($(`#acCd${spaceNum}`).val() == "") {
              span.append(
                `<span class="tfailed" style="display:block;">Aksi tidak boleh kosong<span>`
              );
              success = false;
            }
          } else if (ifVal == "Keadaan") {
            passedData.cndCd = $(`#cndCd${spaceNum}`).val();
            passedData.cnddCd = $(`#cnddCd${spaceNum}`).val();
            passedData.acCd = $(`#acCd${spaceNum}`).val();
            if ($(`#cndCd${spaceNum}`).val() == "") {
              span.append(
                `<span class="tfailed" style="display:block;">Input komponen tidak boleh kosong<span>`
              );
              success = false;
            }
            if ($(`#cnddCd${spaceNum}`).val() == "") {
              span.append(
                `<span class="tfailed" style="display:block;">Input kondisi tidak boleh kosong<span>`
              );
              success = false;
            }
            if ($(`#acCd${spaceNum}`).val() == "") {
              span.append(
                `<span class="tfailed" style="display:block;">Aksi tidak boleh kosong<span>`
              );
              success = false;
            }
          } else {
            success = false;
            span.append(
              `<span class="tfailed" style="display:block;">Pemicu tidak boleh kosong<span>`
            );
          }

          if (success) {
            span.append(
              `<span style="display:block;">Mengupdate program ke database...<span>`
            );
            passedData.trCd = ifVal;
            passedData.progNum = spaceNum;
            requestAJAX(
              "NexusService",
              {
                token: getMeta("token"),
                bondKey: getBondKey(),
                requestType: "updateProgram",
                passedData,
              },
              function (response) {
                span.html("");
                if (!JSON.parse(response).success)
                  span.append(
                    `<span style="display:block;" class="tfailed">Gagal mengupdate program ke database, silahkan coba lagi setelah beberapa saat<span>`
                  );
                else
                  span.append(
                    `<span style="display:block;" class="tsucceed2">Program berhasil terupdate ke database<span>`
                  );
                setTimeout(function () {
                  span.html("");
                }, 30000);
              },
              4000,
              function (status) {
                bootbox.alert({
                  size: "large",
                  title: `Gagal mengupdate program ${spaceNum}`,
                  message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian<br>Status Error : ${status}`,
                  closeButton: false,
                  buttons: {
                    ok: {
                      label: "Tutup",
                    },
                  },
                });
              }
            );
          }
        } else {
          span.append(
            `<span style="display:block;">Menghapus program dari database...<span>`
          );
          passedData.progNum = spaceNum;
          requestAJAX(
            "NexusService",
            {
              token: getMeta("token"),
              bondKey: getBondKey(),
              requestType: "deleteProgram",
              passedData,
            },
            function (response) {
              span.html("");
              if (JSON.parse(response).success)
                $(`#condition${spaceNum}`).remove();
              else
                span.append(
                  `<span style="display:block;" class="tfailed">Gagal menghapus, silahkan coba lagi setelah beberapa saat<span>`
                );

              setTimeout(function () {
                span.html("");
              }, 30000);
            },
            5000,
            function (status) {
              bootbox.alert({
                size: "large",
                title: `Gagal menghapus program ${spaceNum}`,
                message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian<br>Status Error : ${status}`,
                closeButton: false,
                buttons: {
                  ok: {
                    label: "Tutup",
                  },
                },
              });
            }
          );
        }
      });
    }

    function pgacCdCallback(sOutput) {
      var val = sOutput.values[0].val;
      var id = this.elem.id;
      var spaceNum = id.match(/\d+/g);
      if (id.substring(0, 4) === "ifCd") {
        var acContent = [];
        $(`#condition${spaceNum} .ctCd`).remove();
        if (val == "Timer") {
          buildTimer(spaceNum);
          acContent = [
            "Nyalakan Output 1",
            "Nyalakan Output 2",
            "Nyalakan Pemanas",
            "Nyalakan Pendingin",
            "Matikan Pemanas",
            "Matikan Pendingin",
          ];
        } else if (val == "Nilai Suhu" || val == "Nilai Humiditas") {
          buildTempOrHum(spaceNum, val);
          acContent = [
            "Nyalakan Output 1",
            "Nyalakan Output 2",
            "Nyalakan Pemanas",
            "Nyalakan Pendingin",
            "Matikan Output 1",
            "Matikan Output 2",
            "Matikan Pemanas",
            "Matikan Pendingin",
          ];
        } else if (val == "Jadwal Harian") {
          buildDailyPicker(spaceNum);
          acContent = [
            "Nyalakan Output 1",
            "Nyalakan Output 2",
            "Nyalakan Pemanas",
            "Nyalakan Pendingin",
            "Matikan Output 1",
            "Matikan Output 2",
            "Matikan Pemanas",
            "Matikan Pendingin",
          ];
        } else if (val == "Tanggal Waktu") {
          buildDateTimePicker(spaceNum);
          acContent = [
            "Nyalakan Output 1",
            "Nyalakan Output 2",
            "Nyalakan Pemanas",
            "Nyalakan Pendingin",
            "Matikan Output 1",
            "Matikan Output 2",
            "Matikan Pemanas",
            "Matikan Pendingin",
          ];
        } else if (val == "Keadaan") {
          buildConditionalPicker(spaceNum);
          acContent = [
            "Nyalakan Output 1",
            "Nyalakan Output 2",
            "Nyalakan Pemanas",
            "Nyalakan Pendingin",
            "Matikan Output 1",
            "Matikan Output 2",
            "Matikan Pemanas",
            "Matikan Pendingin",
          ];
        }
        buildActionPicker(spaceNum, acContent);
      }

      return val;
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
            bondKey: getBondKey(),
            requestType: "loadPlot",
            date: requestedDate,
          },
          function (response) {
            redrawChart(JSON.parse(response).plot);
            $("#graphoverlay").removeClass("active");
          },
          8000,
          function (status) {
            $("#graphoverlay").removeClass("active");
            bootbox.alert({
              size: "large",
              title: "Gagal memuat grafik",
              message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian<br>Status Error : ${status}`,
              closeButton: false,
              buttons: {
                ok: {
                  label: "Tutup",
                },
              },
            });
          }
        );
      }
      $("#datebuffer").val(requestedDate);
      return requestedDate;
    }
    /////////////////////////////////////////////////
    function binarySwitch(arg) {
      // Adjust the position of switch based on database value
      $("#aux1Switch").prop("checked", Boolean(Number(arg.auxStatus1)));
      $("#aux2Switch").prop("checked", Boolean(Number(arg.auxStatus2)));
    }

    function parameterReload(arg, long = false) {
      if (arg.espStatusUpdateAvailable == 1) {
        binarySwitch(arg);
      }
      $("#tempnow").text(arg.tempNow + "°C");
      $("#humidnow").text(arg.humidNow + "%");
      $("#spvalue").text(arg.sp + "°C");
      $("#spsetting").val(arg.sp);

      if (long) 
        binarySwitch(arg);
    }

    function reloadStatus() {
      requestAJAX(
        "NexusService",
        {
          requestType: "reloadStatus",
          bondKey: getBondKey(),
          token: getMeta("token"),
        },
        function (response) {
          parameterReload(JSON.parse(response).status);
        }
      );
    }

    ///////////////////////////////
    function loadDeviceInformation(master) {
      requestAJAX(
        "NexusService",
        master,
        function callback(response) {
          var parseJson = JSON.parse(response);

          // Add device bondKey information
          setBondKey(parseJson["deviceInfo"]["bondKey"]);

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

              $(".subMasterName" + String(x))
                .unbind()
                .removeData();
              $(".subMasterName" + String(x)).on("click", function () {
                setBondKey("");
                $(".absolute-overlay").removeClass("loaded");
                loadDeviceInformation({
                  requestType: "loadDeviceInformation",
                  master: $("." + $(this).attr("class").split(/\s+/)[1]).text(),
                  token: getMeta("token"),
                });
                const check = setInterval(function () {
                  // Function to check every 0.1s if bondKey is available, then execute reloadStatus() and destroy itself.
                  if (getBondKey() != "") {
                    $(".absolute-overlay").addClass("loaded");
                    clearInterval(check); // kill after executed
                  }
                }, 100);
              });
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

          $("#auxname1").text(parseJson.nexusBond.auxName1 + " (1)");
          $("#auxname2").text(parseJson.nexusBond.auxName2 + " (2)");

          parameterReload(parseJson.nexusBond, true);

          // Initialize anypicker with correct date limit based on database

          var today = new Date();
          var dd = String(today.getDate()).padStart(2, "0");
          var mm = String(today.getMonth() + 1).padStart(2, "0"); //January is 0!
          var yyyy = today.getFullYear();
          today = yyyy + "-" + mm + "-" + dd;
          if (parseJson.plot.oldest.oldestPlot == null)
            parseJson.plot.oldest.oldestPlot = today;
          if (parseJson.plot.oldest.newestPlot == null)
            parseJson.plot.newest.newestPlot = today;
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
            showComponentLabel: true,
            mode: "datetime",
            dateTimeFormat: "yyyy-MM-dd",
            lang: "id",
            formatOutput: setTrigger,
            minValue: new Date(oldest[0], oldest[1], 01),
            maxValue: new Date(newest[0], newest[1], 00),
          });

          // Draw a chart
          redrawChart(parseJson.plot);
          if (
            new Date().getTime() / 1000 -
              new Date(parseJson.nexusBond.lastUpdate).getTime() / 1000 >=
            300
          ) {
            parseJson.nexusBond.lastUpdate = parseJson.nexusBond.lastUpdate.split(
              " "
            );
            setTimeout(function () {
              bootbox.alert({
                size: "large",
                title: "Pemberitahuan",
                message: `Kontroller anda dengan nama <b>${
                  parseJson.deviceInfo.masterName
                }</b> sudah tidak mengirim respon ke server selama lebih dari 5 menit, <b>pastikan kontroller anda menyambung ke internet</b> agar anda dapat mengkontrol atau mengupdate kontroller.<br>Kontroller anda terakhir online pada<b> ${convertToDateLong(
                  parseJson.nexusBond.lastUpdate[0]
                )} ${parseJson.nexusBond.lastUpdate[1]}</b>`,
                closeButton: false,
                buttons: {
                  ok: {
                    label: "Tutup",
                  },
                },
              });
            }, 2000);
          }
          $("#conditional").html("");
          for (index in parseJson.programs) {
            const progNum = parseJson.programs[index].progNumber;
            const trigger = parseJson.programs[index].progData1;
            const action = parseJson.programs[index].progData2;
            appendConditionalChild(progNum, [
              "Nilai Suhu",
              "Nilai Humiditas",
              "Jadwal Harian",
              "Tanggal Waktu",
              //"Timer",
              "Keadaan",
            ]);
            // buildTempOrHum(spaceNum), buildTimer(spaceNum), buildDailyPicker(spaceNum), buildDateTimePicker(spaceNum), buildActionPicker(spaceNum, acContent)
            $(`#ifCd${progNum}`).val(trigger);
            if (trigger == "Nilai Suhu" || trigger == "Nilai Humiditas") {
              buildTempOrHum(progNum, trigger);
              $(`#nscmpCd${progNum}`).val(parseJson.programs[index].progData3);
              $(`#nsvalCd${progNum}`).val(parseJson.programs[index].progData4);
            } else if (trigger == "Jadwal Harian") {
              buildDailyPicker(progNum);
              $(`#faCd${progNum}`).val(parseJson.programs[index].progData3);
              $(`#feCd${progNum}`).val(parseJson.programs[index].progData4);
            } else if (trigger == "Tanggal Waktu") {
              buildDateTimePicker(progNum);
              $(`#dfaCd${progNum}`).val(parseJson.programs[index].progData3);
              $(`#dfeCd${progNum}`).val(parseJson.programs[index].progData4);
            } else if (trigger == "Timer") {
              buildTimer(progNum);
              $(`#timerCd${progNum}`).val(parseJson.programs[index].progData3);
            } else if (trigger == "Keadaan") {
              buildConditionalPicker(progNum);
              $(`#cndCd${progNum}`).val(parseJson.programs[index].progData3);
              $(`#cnddCd${progNum}`).val(parseJson.programs[index].progData4);
            }
            var acContent = [];
            if (trigger != "timer") {
              acContent = [
                "Nyalakan Output 1",
                "Nyalakan Output 2",
                "Nyalakan Pemanas",
                "Nyalakan Pendingin",
                "Matikan Output 1",
                "Matikan Output 2",
                "Matikan Pemanas",
                "Matikan Pendingin",
              ];
            } else {
              acContent = [
                "Nyalakan Output 1",
                "Nyalakan Output 2",
                "Nyalakan Pemanas",
                "Nyalakan Pendingin",
                "Matikan Pemanas",
                "Matikan Pendingin",
              ];
            }
            buildActionPicker(progNum, acContent);
            $(`#acCd${progNum}`).val(action);
          }
        },
        10000,
        function (status) {
          bootbox.alert({
            size: "large",
            title: "Gagal memuat laman",
            message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian<br>Status Error : ${status}`,
            closeButton: false,
            buttons: {
              ok: {
                label: "Tutup",
              },
            },
          });
        }
      );
    }

    function nexusSettingLoad() {
      $("#delDevice").unbind().removeData();
      $("#delDevice").click(function () {
        bootbox.prompt({
          message: `Aksi ini tidak dapat dipulihkan. Seluruh informasi kontroller yang terhubung dengan akun ini akan terhapus seluruhnya.<br> Tolong ketikkkan nama kontroller anda (<b>${$(
            "#deviceName"
          ).text()}</b>) apabila anda yakin.`,
          title: "Apakah anda yakin ingin menghapus kontroller ini?",
          inputType: "text",
          buttons: {
            cancel: {
              label: '<i class="fa fa-times"></i> Tidak',
              className: "btn-secondary",
            },
            confirm: {
              label: '<i class="fa fa-check"></i> Ya',
              className: "btn-danger",
            },
          },
          callback: function (result) {
            if (result != null) {
              if (result == $("#deviceName").text()) {
                requestAJAX(
                  "NexusService",
                  {
                    requestType: "deleteController",
                    bondKey: getBondKey(),
                    token: getMeta("token"),
                  },
                  function (response) {
                    if (response) {
                      bootbox.alert({
                        size: "large",
                        title: "Berhasil menghapus",
                        message: `Kontroller sudah terhapus dari akun ini, merefresh halaman dalam 3 detik...`,
                        closeButton: false,
                        buttons: {
                          ok: {
                            label: "Tutup",
                          },
                        },
                      });
                      setTimeout(function () {
                        location.reload();
                      }, 3000);
                    }
                  },
                  5000,
                  function (status) {
                    bootbox.alert({
                      size: "large",
                      title: "Terjadi kesalahan",
                      message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian<br>Status Error : ${status}`,
                      closeButton: false,
                      buttons: {
                        ok: {
                          label: "Tutup",
                        },
                      },
                    });
                  }
                );
              } else {
                bootbox.alert({
                  size: "large",
                  title: "Gagal menghapus kontroller",
                  message: `Isian yang anda isi tidak sama dengan nama kontroller anda (<b>${$(
                    "#deviceName"
                  ).text()}</b>).`,
                  closeButton: false,
                  buttons: {
                    ok: {
                      label: "Tutup",
                    },
                  },
                });
              }
            }
          },
        });
      });

      $("#restartDev, #conCheck").unbind().removeData();
      $("#restartDev, #conCheck").click(function () {
        var message, title, command;
        if (this.id == "restartDev") {
          title = "Restart kontroller?";
          message =
            'Apabila anda yakin ingin merestart kontroller maka klik tombol "Ya"';
          command = "restart";
        } else if (this.id == "conCheck") {
          title = "Putuskan koneksi internet kontroller?";
          message =
            'Apabila anda yakin ingin memutus koneksi internet kontroller maka klik tombol "Ya"';
          command = "fallback";
        }

        bootbox.confirm({
          title: title,
          className: "bootBoxPop",
          message: message,
          buttons: {
            cancel: {
              label: '<i class="fa fa-times"></i> Tidak',
              className: "btn-secondary",
            },
            confirm: {
              label: '<i class="fa fa-check"></i> Ya',
              className: "btn-danger",
            },
          },
          callback: function (result) {
            if (result) {
              requestAJAX(
                "NexusService",
                {
                  requestType: "controllerCommand",
                  command: command,
                  bondKey: getBondKey(),
                  token: getMeta("token"),
                },
                function (response) {},
                5000,
                function (status) {
                  bootbox.alert({
                    size: "large",
                    title: "Terjadi kesalahan",
                    message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian<br>Status Error : ${status}`,
                    closeButton: false,
                    buttons: {
                      ok: {
                        label: "Tutup",
                      },
                    },
                  });
                }
              );
            }
          },
        });
      });

      $("#auxNameSubmit").unbind().removeData();
      $("#auxNameSubmit").click(function () {
        $("#infoAuxName").removeClass();
        $("#infoAuxName").text("Mengupdate ke database...");
        requestAJAX(
          "NexusService",
          {
            requestType: "updateName",
            docchi: "aux",
            name: [$("#aux1Name").val(), $("#aux2Name").val()],
            bondKey: getBondKey(),
            token: getMeta("token"),
          },
          function (response) {
            $("#infoAuxName").addClass("tsucceed2");
            $("#infoAuxName").text("Sukses mengupdate");
            setTimeout(function () {
              $("#infoAuxName").removeClass();
              $("#infoAuxName").text("");
            }, 15000);
          },
          4000,
          function (status) {
            bootbox.alert({
              size: "large",
              title: "Terjadi kesalahan",
              message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian<br>Status Error : ${status}`,
              closeButton: false,
              buttons: {
                ok: {
                  label: "Tutup",
                },
              },
            });
          }
        );
      });

      $("#submitEdit").unbind().removeData();
      $("#submitEdit").click(function () {
        $("#infoName").removeClass();
        if ($("#deviceNameEdit").val() != $("#deviceName").text()) {
          $("#infoName").text("Mengupdate ke database...");
          requestAJAX(
            "NexusService",
            {
              requestType: "updateName",
              docchi: "master",
              name: $("#deviceNameEdit").val(),
              bondKey: getBondKey(),
              token: getMeta("token"),
            },
            function (response) {
              if (JSON.parse(response).duplicate == false) {
                $("#deviceName").text($("#deviceNameEdit").val());
                $("#infoName").addClass("tsucceed2");
                $("#infoName").text("Sukses mengupdate");
              } else {
                $("#infoName").addClass("tfailed");
                $("#infoName").text(
                  "Gagal mengubah nama, nama ini sudah terambil oleh kontroller lain yang anda miliki"
                );
              }
            },
            4000,
            function (status) {
              bootbox.alert({
                size: "large",
                title: "Terjadi kesalahan",
                message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian<br>Status Error : ${status}`,
                closeButton: false,
                buttons: {
                  ok: {
                    label: "Tutup",
                  },
                },
              });
            }
          );
        } else {
          $("#infoName").addClass("tfailed");
          $("#infoName").text(
            "Gagal mengubah nama, nama yang diupdate sama dengan nama lama"
          );
        }

        setTimeout(function () {
          $("#infoName").removeClass();
          $("#infoName").text("");
        }, 15000);
      });
      requestAJAX(
        "NexusService",
        {
          requestType: "loadSetting",
          bondKey: getBondKey(),
          token: getMeta("token"),
        },
        function (response) {
          const json = JSON.parse(response);
          $("#deviceName").text(json.masterName);
          $("#firmware").val(json.softwareVersion);
          $("#macaddr").val(json.MAC);
          $("#softSSID").val(json.softSSID);
          $("#softPW").val(json.softPass);
          $("#softIP").val(json.softIP);
          $("#aux1Name").val(json.auxName1);
          $("#aux2Name").val(json.auxName2);
          $("#deviceNameEdit").val(json.masterName);
        },
        8000,
        function (status) {
          bootbox.alert({
            size: "large",
            title: "Terjadi kesalahan",
            message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian<br>Status Error : ${status}`,
            closeButton: false,
            buttons: {
              ok: {
                label: "Tutup",
              },
            },
          });
        }
      );
    }

    function nexusFirstLoad(bondKey = null) {
      // Awal loading page load device yang paling atas.
      if (bondKey == null)
        loadDeviceInformation({
          requestType: "loadDeviceInformation",
          master: "master",
          token: getMeta("token"),
        });
      else
        loadDeviceInformation({
          requestType: "loadDeviceInformation",
          bondKey: bondKey,
          token: getMeta("token"),
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
      createDataSource(oArrData, [100, 9]);
      $("#spsetting").unbind().removeData();
      $("#spsetting").AnyPicker({
        mode: "select",
        lang: "id",
        showComponentLabel: true,
        components: [
          {
            component: 0,
            name: "c",
            label: "Suhu (°C)",
            width: "30%",
            textAlign: "center",
          },
          {
            component: 1,
            name: "d",
            label: "Koma",
            width: "30%",
            textAlign: "center",
          },
        ],
        dataSource: [
          {
            component: 0,
            data: oArrData[0],
          },
          {
            component: 1,
            data: oArrData[1],
          },
        ],
        parseInput: formatSHInput,
        formatOutput: spsetOut,
      });

      $("#aux1Switch, #aux2Switch")
        .unbind()
        .removeData();
      // Onclick switches
      $(
        "#aux1Switch, #aux2Switch"
      ).click(function () {
        const elem = $(this);
        var check = elem.prop("checked");
        const switchToggle = function (arg) {
          requestAJAX(
            "NexusService",
            {
              requestType: "toggleSwitch",
              bondKey: getBondKey(),
              id: arg.id,
              status: String(arg.checked),
              token: getMeta("token"),
            },
            function (response) {
              // DO SOMETHING IF THERE ARE SOME EXCEPTION SUCH AS CONDITIONAL
            },
            3000,
            function (status) {
              elem.prop("checked", !check);
              bootbox.alert({
                size: "large",
                title: `Gagal mengubah kondisi saklar ${
                  arg.id == arg.id == "aux1Switch"
                    ? $("#auxname1").text()
                    : arg.id == "aux2Switch"
                    ? $("#auxname2").text()
                    : ""
                }`,
                message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian<br>Status Error : ${status}`,
                closeButton: false,
                buttons: {
                  ok: {
                    label: "Tutup",
                  },
                },
              });
            }
          );
        };
        switchToggle(this);
      });

      $("#addCond").unbind().removeData();
      // Onclick add condition button
      $("#addCond").click(function () {
        var space = true;
        var spaceNum;
        for (var i = 1; i <= 30; i++) {
          if ($("#condition" + i).length == 0) {
            spaceNum = i;
            break;
          }
          if (i == 30) {
            space = false;
          }
        }
        if (space) {
          appendConditionalChild(spaceNum, [
            "Nilai Suhu",
            "Nilai Humiditas",
            "Jadwal Harian",
            "Tanggal Waktu",
            // "Timer",
            "Keadaan",
          ]);
          $("html, body").animate(
            {
              scrollTop:
                $(`#condition${spaceNum}`).offset().top -
                $(`#conditional`).offset().top +
                $(`#conditional`).scrollTop() -
                60,
            },
            500
          );
        } else {
          bootbox.alert({
            size: "large",
            title: "Tidak dapat menambah program",
            message: "Jumlah maksimum program yang dapat ditambahkan adalah 30",
            closeButton: false,
            buttons: {
              ok: {
                label: "Tutup",
              },
            },
          });
        }
      });

      $('[data-toggle="popover"]').popover({ html: true });
    }

    var prevInput = {};
    $(document).ready(function () {
      nexusFirstLoad();

      $("#content").on("click", "#deviceSetting", function () {
        $(".absolute-overlay").removeClass("loaded");
        $("#content").html("");
        $("#content").load(
          "pagecon/nexus-settings.php",
          function (responseTxt, statusTxt, xhr) {
            if (statusTxt == "success") {
              $(".absolute-overlay").addClass("loaded");
              nexusSettingLoad();
            }
            if (status == "error") {
              bootbox.alert({
                size: "large",
                title: "Gagal memuat laman",
                message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian`,
                closeButton: false,
                buttons: {
                  ok: {
                    label: "Tutup",
                  },
                },
              });
            }
          }
        );
      });

      $("#content").on("click", "#deviceHome", function () {
        $(".absolute-overlay").removeClass("loaded");
        $("#content").html("");
        $("#content").load(
          "pagecon/nexus-device.php",
          function (responseTxt, statusTxt, xhr) {
            if (statusTxt == "success") {
              nexusFirstLoad(getBondKey());
              $(".absolute-overlay").addClass("loaded");
            }

            if (status == "error") {
              bootbox.alert({
                size: "large",
                title: "Gagal memuat laman",
                message: `Sepertinya server terlalu lama merespons, ini dapat disebabkan oleh koneksi yang buruk atau error pada server kami. Mohon coba lagi sesaat kemudian`,
                closeButton: false,
                buttons: {
                  ok: {
                    label: "Tutup",
                  },
                },
              });
            }
          }
        );
      });

      setTimeout(function reload() {
        if (getBondKey() != "" && $("#nexus-dashboard").length) {
          reloadStatus();
        }
        setTimeout(reload, 3000);
      }, 3000);
    });

    const check = setInterval(function () {
      // Function to check every 0.1s if bondKey is available, then execute reloadStatus() and destroy itself.
      if (getBondKey() != "") {
        $(".absolute-overlay").addClass("loaded");
        clearInterval(check); // kill after executed
      }
    }, 100);
  }
}
