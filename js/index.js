var cityPicker = 1,
  cameraPicker = 1;
let provinsi;
let userInfo;
var deviceBelonging = $("#bigdevicebox");
let nexusReloadTimer, nitenanReloadTimer, nitenanPhotoTimer;
let nitenan;
if (cityPicker) {
  document.addEventListener("click", closeAllSelect);
  fetch("js/json/pulau-jawa.json")
    .then((response) => response.json())
    .then((json) => {
      provinsi = json;
    });
  /*when the select box is clicked, close any other select boxes,
                      and open/close the current select box*/
  function selectBoxEvent(e) {
    e.stopPropagation();
    closeAllSelect(this);
    this.nextSibling.classList.toggle("city-picker__selector--hide");
    this.classList.toggle("city-picker__selector__arrow--active");
  }

  /*when an item of selector is clicked, update the original select box, and the selected item*/
  function selectItemEvent(e) {
    let y, i, k, s, h, sl, yl, selectorElmt, selectorId;
    selectorElmt = this.parentNode.parentNode;
    selectorId = selectorElmt.id;
    s = this.parentNode.parentNode.getElementsByTagName("select")[0];
    sl = s.length;
    h = this.parentNode.previousSibling;
    // If user changes the provinsi selector item, then...
    if (selectorId == "js-provinsi-selector" && h.innerHTML != this.innerHTML) {
      let kotaSelector = document.getElementById("js-kota-selector"),
        adakahKotaSelector =
          typeof kotaSelector != "undefined" && kotaSelector != null;
      // Jika selector kota udah ada kita delete dulu sebelum recreate
      if (adakahKotaSelector) {
        document.getElementById("js-kota-label").remove();
        kotaSelector.remove();
      }
      // Buat htmlnya city-picker untuk kota
      selectorElmt.parentNode.insertAdjacentHTML(
        "beforeend",
        `<span class="city-picker__label" id="js-kota-label">Kota/Kabupaten</span>
                          <div class="city-picker__selector" id="js-kota-selector" style="width:100%;">
                              <select>
                              <option value="0">Pilih Kota</option>
                              </select>
                          </div>`
      );
      // cari elemen dengan id js-kota-selector buat dijadiin kotaSelector
      kotaSelector = document.getElementById("js-kota-selector");
      // Kita edit items dari kotaSelector sesuai provinsi yang terpilih
      provinsi[this.innerHTML].forEach((element, index) => {
        kotaSelector.childNodes[1].innerHTML += `<option value="${
          index + 1
        }">${element}</option>`;
      });
      // Setelah itu kita buat selectornya
      buildSelector(kotaSelector);
    }
    if (selectorId == "js-kota-selector" && h.innerHTML != this.innerHTML) {
      $("#pembeli").children(".product-box, .product-not-found").remove();
      requestAJAX(
        "GlobalService",
        {
          requestType: "getProducts",
          token: getMeta("token"),
          provinsi: $("#js-provinsi-selector")
            .find(".city-picker__selector__selected")
            .text(),
          kota: this.innerHTML,
        },
        function (response) {
          let formatRupiah = function (angka) {
            var number_string = angka.replace(/[^,\d]/g, "").toString(),
              split = number_string.split(","),
              sisa = split[0].length % 3,
              rupiah = split[0].substr(0, sisa),
              ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
              separator = sisa ? "." : "";
              rupiah += separator + ribuan.join(".");
            }
            rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
            return "Rp" + rupiah;
          };
          response = JSON.parse(response);
          if (response.status) {
            //Marketplace public products
            if (response.products) {
              response.products.forEach((element, index) => {
                $("#pembeli").append(`
                <div class="row mt-3 product-box" id="${element.bondKey}">
                    <div style="padding:0px;">
                        <img class="product-box__image" src="${element.gambar1.replace(
                          "../",
                          ""
                        )}" alt="">
                    </div>
                    <div style="padding-left:15px;">
                        <div class="row product-box__title-label">
                            <span>${element.namaBarang}</span>
                        </div>
                        <div class="row product-box__from-label">
                            <span>${
                              "Dari " + element.provinsi + ", " + element.kota
                            }</span>
                        </div>
                        <div class="row product-box__oleh-label">
                            <span>Oleh ${element.nama}</span>
                        </div>
                        <div class="row">
                            <span class="fa fa-star product-box__star"></span>
                            <span class="product-box__rating">${parseFloat(
                              element.rating
                            ).toFixed(1)}</span>
                            <span class="product-box__out-of">/5</span>
                            <span class="product-box__review-label">${
                              element.review
                            } Ulasan</span>
                            <div class="product-box__price-box btn btn-info">${formatRupiah(
                              element.hargaBarang
                            )}</div>
                        </div>
                    </div>
                </div>`);
              });
            } else
              $("#pembeli").append(`
              <div class="row mt-3 product-not-found">
                  <div class="col-12 text-center">
                      <h3 class="not-found__frown">:(</h3>
                      <span class="not-found__oops">Oops!</span>
                      <span class="not-found__description">Sepertinya kami tidak dapat menemukan produk yang anda cari!</span>
                  </div>
              </div>`);
          } else {
            bootbox.alert({
              size: "large",
              title: "Gagal memuat produk-produk marketplace",
              message: `Terjadi kesalahan saat memuat produk-produk marketplace, mohon coba lagi sesaat kemudian`,
              closeButton: false,
              buttons: {
                ok: {
                  label: "Tutup",
                },
              },
            });
          }
        },
        8000,
        function (status) {
          bootbox.alert({
            size: "large",
            title: "Gagal memuat produk-produk marketplace",
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
    for (i = 0; i < sl; i++) {
      if (s.options[i].innerHTML == this.innerHTML) {
        s.selectedIndex = i;
        h.innerHTML = this.innerHTML;
        y = this.parentNode.getElementsByClassName(
          "city-picker__selector--same-as-selected"
        );
        yl = y.length;
        for (k = 0; k < yl; k++) {
          y[k].removeAttribute("class");
        }
        this.setAttribute("class", "city-picker__selector--same-as-selected");
        break;
      }
    }
    h.click();
  }

  function closeAllSelect(elmnt) {
    /*a function that will close all select boxes in the document,
                                      except the current select box:*/
    var x,
      y,
      i,
      xl,
      yl,
      arrNo = [];
    x = document.getElementsByClassName("city-picker__selector__items");
    y = document.getElementsByClassName("city-picker__selector__selected");
    xl = x.length;
    yl = y.length;
    for (i = 0; i < yl; i++) {
      if (elmnt == y[i]) {
        arrNo.push(i);
      } else {
        y[i].classList.remove("city-picker__selector__arrow--active");
      }
    }
    for (i = 0; i < xl; i++) {
      if (arrNo.indexOf(i)) {
        x[i].classList.add("city-picker__selector--hide");
      }
    }
  }

  function buildSelector(element) {
    let x,
      i,
      j,
      l,
      ll,
      selElmnt = element.getElementsByTagName("select")[0],
      a,
      b,
      c;
    ll = selElmnt.length;
    /*for each element, create a new DIV that will act as the selected item:*/
    a = document.createElement("DIV");
    a.setAttribute("class", "city-picker__selector__selected");
    a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
    element.appendChild(a);
    /*for each element, create a new DIV that will contain the option list:*/
    b = document.createElement("DIV");
    b.setAttribute(
      "class",
      "city-picker__selector__items city-picker__selector--hide"
    );
    for (j = 1; j < ll; j++) {
      /*for each option in the original select element,create a new DIV that will act as an option item:*/
      c = document.createElement("DIV");
      c.innerHTML = selElmnt.options[j].innerHTML;
      c.addEventListener("click", selectItemEvent);
      b.appendChild(c);
    }
    element.appendChild(b);
    a.addEventListener("click", selectBoxEvent);
  }
}

if (cameraPicker) {
  /*when the select box is clicked, close any other select boxes,
                and open/close the current select box*/
  function selectCameraBoxEvent(e) {
    e.stopPropagation();
    closeAllCameraSelect(this);
    this.nextSibling.classList.toggle("picker__selector--hide");
    this.classList.toggle("picker__selector__arrow--active");
  }

  /*when an item of selector is clicked, update the original select box, and the selected item*/
  function selectCameraItemEvent(e) {
    let y, i, k, s, h, sl, yl, selectorElmt, selectorId;
    selectorElmt = this.parentNode.parentNode;
    selectorId = selectorElmt.id;
    s = this.parentNode.parentNode.getElementsByTagName("select")[0];
    sl = s.length;
    h = this.parentNode.previousSibling;
    for (i = 0; i < sl; i++) {
      if (s.options[i].innerHTML == this.innerHTML) {
        s.selectedIndex = i;
        h.innerHTML = this.innerHTML;
        y = this.parentNode.getElementsByClassName(
          "picker__selector--same-as-selected"
        );
        yl = y.length;
        for (k = 0; k < yl; k++) {
          y[k].removeAttribute("class");
        }
        this.setAttribute("class", "picker__selector--same-as-selected");
        break;
      }
    }
    h.click();
  }

  function closeAllCameraSelect(elmnt) {
    /*a function that will close all select boxes in the document,
                    except the current select box:*/
    var x,
      y,
      i,
      xl,
      yl,
      arrNo = [];
    x = document.getElementsByClassName("picker__selector__items");
    y = document.getElementsByClassName("picker__selector__selected");
    xl = x.length;
    yl = y.length;
    for (i = 0; i < yl; i++) {
      if (elmnt == y[i]) {
        arrNo.push(i);
      } else {
        y[i].classList.remove("picker__selector__arrow--active");
      }
    }
    for (i = 0; i < xl; i++) {
      if (arrNo.indexOf(i)) {
        x[i].classList.add("picker__selector--hide");
      }
    }
  }
  document.addEventListener("click", closeAllCameraSelect);
}

function submitAddProduct() {
  let productData = {
    namaProduk: $("#js-namaBarang").val(),
    hargaProduk:
      isNaN(parseInt($("#js-hargaBarang").val())) == false
        ? parseInt($("#js-hargaBarang").val())
        : 0,
    kamera: $("#js-camera-selector").find(".picker__selector__selected").text(),
    deskripsiProduk: $("#js-deskripsiBarang").val(),
    gambar1: $("#js-preview-1").data("b64"),
    gambar2: $("#js-preview-2").data("b64"),
    gambar3: $("#js-preview-3").data("b64"),
    nama: userInfo.nama,
    kota: userInfo.kota,
    provinsi: userInfo.provinsi,
  };

  requestAJAX(
    "GlobalService",
    {
      requestType: "submitAddProduct",
      token: getMeta("token"),
      productData: productData,
    },
    function (response) {
      response = JSON.parse(response);
      if (response.status == true) {
        bootbox.alert({
          size: "large",
          title: "Sukses menambahkan produk",
          message:
            "Produk anda telah ditambahkan, anda akan dialihkan ke marketplace setelah 3 detik...",
          closeButton: false,
          buttons: {
            ok: {
              label: "Tutup",
            },
          },
        });
        setTimeout(() => {
          bootbox.hideAll();
          $("#js-marketplace").trigger("click");
        }, 3000);
      } else {
        bootbox.alert({
          size: "large",
          title: "Gagal menambahkan produk",
          message: response.message,
          closeButton: false,
          buttons: {
            ok: {
              label: "Tutup",
            },
          },
        });
      }
    },
    5000,
    function (status) {
      bootbox.alert({
        size: "large",
        title: "Gagal menambahkan produk",
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
  reloadUserInfo();
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
  } else if (id == "js-marketplace") {
    pageCon = "pagecon/marketplace.php";
  } else if (id == "js-add-product") {
    pageCon = "pagecon/add-product.php";
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
        let ii = 0;
        Object.keys(provinsi).forEach(function (key) {
          document.getElementById(
            "js-provinsi-selector"
          ).childNodes[1].innerHTML += `<option value=${++ii}>${key}</option>`;
        });
        buildSelector(document.getElementById("js-provinsi-selector"));
        reloadUserInfo();
        $("#ACemail").val(userInfo.email);
        $("#js-phoneNumber").val(userInfo.phoneNumber);
        $("#js-nama").val(userInfo.nama);
        $("#js-provinsi-selector")
          .find(
            `.city-picker__selector__items div:contains("${userInfo.provinsi}")`
          )
          .trigger("click");
        $("#js-kota-selector")
          .find(
            `.city-picker__selector__items div:contains("${userInfo.kota}")`
          )
          .trigger("click");
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
        $("#js-pp").on("change", function () {
          if (this.files && this.files[0]) {
            var FR = new FileReader();
            FR.addEventListener("load", function (e) {
              $("#js-pp").data("b64", e.target.result);
            });
            FR.readAsDataURL(this.files[0]);
          }
        });
        $("#ACuserSubmit").click(function () {
          $("#ACuserNotif").removeClass();
          $("#ACuserNotif").text("Memproses permintaan anda...");
          requestAJAX(
            "GlobalService",
            {
              requestType: "userInfoChange",
              token: getMeta("token"),
              email: $("#ACemail").val(),
              phoneNumber: $("#js-phoneNumber").val(),
              b64Image: $("#js-pp").data("b64"),
              provinsi: $("#js-provinsi-selector")
                .find(".city-picker__selector__selected")
                .text(),
              kota: $("#js-kota-selector")
                .find(".city-picker__selector__selected")
                .text(),
              nama: $("#js-nama").val(),
            },
            function (response) {
              const parseJson = JSON.parse(response);
              if (parseJson.status == "failure")
                $("#ACuserNotif").addClass("tfailed");
              else if (parseJson.status == "success")
                $("#ACuserNotif").addClass("tsucceed2");
              $("#ACuserNotif").html(parseJson.message);
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
            $("#ACuserNotif").text("");
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
      } else if (id == "js-marketplace") {
        let ii = 0;
        Object.keys(provinsi).forEach(function (key) {
          document.getElementById(
            "js-provinsi-selector"
          ).childNodes[1].innerHTML += `<option value=${++ii}>${key}</option>`;
        });
        buildSelector(document.getElementById("js-provinsi-selector"));
        $(".absolute-overlay").addClass("loaded");
        reloadUserInfo();
        $("#js-userphoto").attr("src", userInfo.photoPath.replace("../", ""));
        $("#js-username").text(userInfo.nama);

        requestAJAX(
          "GlobalService",
          {
            requestType: "getProducts",
            token: getMeta("token"),
            provinsi: "",
            kota: "",
          },
          function (response) {
            let formatRupiah = function (angka) {
              var number_string = angka.replace(/[^,\d]/g, "").toString(),
                split = number_string.split(","),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);
              // tambahkan titik jika yang di input sudah menjadi angka ribuan
              if (ribuan) {
                separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
              }
              rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
              return "Rp" + rupiah;
            };
            response = JSON.parse(response);
            if (response.status) {
              //Marketplace public products
              if (response.products) {
                response.products.forEach((element, index) => {
                  $("#pembeli").append(`
                  <div class="row mt-3 product-box" id="${element.bondKey}">
                      <div style="padding:0px;">
                          <img class="product-box__image" src="${element.gambar1.replace(
                            "../",
                            ""
                          )}" alt="">
                      </div>
                      <div style="padding-left:15px;">
                          <div class="row product-box__title-label">
                              <span>${element.namaBarang}</span>
                          </div>
                          <div class="row product-box__from-label">
                              <span>${
                                "Dari " + element.provinsi + ", " + element.kota
                              }</span>
                          </div>
                          <div class="row product-box__oleh-label">
                              <span>Oleh ${element.nama}</span>
                          </div>
                          <div class="row">
                              <span class="fa fa-star product-box__star"></span>
                              <span class="product-box__rating">${parseFloat(
                                element.rating
                              ).toFixed(1)}</span>
                              <span class="product-box__out-of">/5</span>
                              <span class="product-box__review-label">${
                                element.review
                              } Ulasan</span>
                              <div class="product-box__price-box btn btn-info">${formatRupiah(
                                element.hargaBarang
                              )}</div>
                          </div>
                      </div>
                  </div>`);
                });
              } else
                $("#pembeli").append(`
                <div class="row mt-3 product-not-found">
                    <div class="col-12 text-center">
                        <h3 class="not-found__frown">:(</h3>
                        <span class="not-found__oops">Oops!</span>
                        <span class="not-found__description">Sepertinya kami tidak dapat menemukan produk yang anda cari!</span>
                    </div>
                </div>`);
              // User specific owned products

              if (response.myProducts) {
                response.myProducts.forEach((element, index) => {
                  $("#penjual").append(`
                  <div class="row mt-3 product-box" id="${element.bondKey}">
                      <div style="padding:0px;">
                          <img class="product-box__image" src="${element.gambar1.replace(
                            "../",
                            ""
                          )}" alt="">
                      </div>
                      <div style="padding-left:15px;">
                          <div class="row product-box__title-label">
                              <span>${element.namaBarang}</span>
                          </div>
                          <div class="row product-box__from-label">
                              <span>${
                                "Dari " + element.provinsi + ", " + element.kota
                              }</span>
                          </div>
                          <div class="row product-box__oleh-label">
                              <span>Oleh ${element.nama}</span>
                          </div>
                          <div class="row">
                              <span class="fa fa-star product-box__star"></span>
                              <span class="product-box__rating">${parseFloat(
                                element.rating
                              ).toFixed(1)}</span>
                              <span class="product-box__out-of">/5</span>
                              <span class="product-box__review-label">${
                                element.review
                              } Ulasan</span>
                              <div class="product-box__price-box btn btn-info">${formatRupiah(
                                element.hargaBarang
                              )}</div>
                          </div>
                      </div>
                  </div>`);
                });
              } else
                $("#penjual").append(`
                <div class="row mt-3">
                    <div class="col-12 text-center product-not-found">
                        <h3 class="not-found__frown">:(</h3>
                        <span class="not-found__oops">Oops!</span>
                        <span class="not-found__description">Sepertinya anda tidak mempunyai produk yang anda jual!, tambahkan produk sekarang!</span>
                    </div>
                </div>`);
            } else {
              bootbox.alert({
                size: "large",
                title: "Gagal memuat produk-produk marketplace",
                message: `Terjadi kesalahan saat memuat produk-produk marketplace, mohon coba lagi sesaat kemudian`,
                closeButton: false,
                buttons: {
                  ok: {
                    label: "Tutup",
                  },
                },
              });
            }
          },
          8000,
          function (status) {
            bootbox.alert({
              size: "large",
              title: "Gagal memuat produk-produk marketplace",
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
      } else if (id == "js-add-product") {
        let buildCameraSelector = function (element) {
          let x,
            i,
            j,
            l,
            ll,
            selElmnt = element.getElementsByTagName("select")[0],
            a,
            b,
            c;
          ll = selElmnt.length;
          /*for each element, create a new DIV that will act as the selected item:*/
          a = document.createElement("DIV");
          a.setAttribute("class", "picker__selector__selected");
          a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
          element.appendChild(a);
          /*for each element, create a new DIV that will contain the option list:*/
          b = document.createElement("DIV");
          b.setAttribute(
            "class",
            "picker__selector__items picker__selector--hide"
          );
          for (j = 1; j < ll; j++) {
            /*for each option in the original select element,create a new DIV that will act as an option item:*/
            c = document.createElement("DIV");
            c.innerHTML = selElmnt.options[j].innerHTML;
            c.addEventListener("click", selectCameraItemEvent);
            b.appendChild(c);
          }
          element.appendChild(b);
          a.addEventListener("click", selectCameraBoxEvent);
        };
        requestAJAX(
          "GlobalService",
          {
            requestType: "getOwnedNitenan",
            token: getMeta("token"),
          },
          function (response) {
            response = JSON.parse(response);
            if (response) {
              response.forEach(function (element, index) {
                document.getElementById(
                  "js-camera-selector"
                ).childNodes[1].innerHTML += `<option value=${index + 1}>${
                  element.masterName
                }</option>`;
              });
              buildCameraSelector(
                document.getElementById("js-camera-selector")
              );
            } else {
              // If user had no nitenan device then diplay the alert
              bootbox.alert({
                size: "large",
                title: "Informasi",
                message: `Sepertinya anda tidak mempunyai kontroller Nitenan yang digunakan sebagai kamera produk, tanpa kamera anda tidak bisa menambahkan produk`,
                closeButton: false,
                buttons: {
                  ok: {
                    label: "Tutup",
                  },
                },
              });
            }
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
        $(".absolute-overlay").addClass("loaded");
      }
      if (helppick != null) {
        $().accordiom.openItem("#main-accordion", 2);
      }
      retractSidebar();
    }
    if (statusTxt == "error") {
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

let humidRadial, tempRadial;
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
  $(document).on("change", "#js-file-upload", function (event) {
    let files = event.target.files; //FileList object
    if (files.length == 3) {
      for (let i = 0; i < files.length; i++) {
        let output = document.getElementById(`js-preview-${i + 1}`);
        let file = files[i];
        //Only pics
        if (!file.type.match("image")) continue;
        let picReader = new FileReader();
        picReader.addEventListener("load", function (event) {
          let picFile = event.target;
          let div = document.createElement("div");
          output.src = picFile.result;
          output.title = picFile.name;
          $(output).data("b64", event.target.result);
        });
        //Read the image
        picReader.readAsDataURL(file);
      }
    } else {
      bootbox.alert({
        size: "large",
        title: "Peringatan",
        message: `Tolong upload 3 gambar`,
        closeButton: false,
        buttons: {
          ok: {
            label: "Tutup",
          },
        },
      });
    }
  });
  $(document).on(
    "click",
    "#contactUs, #goHelp, #goAccount, #progHelp, #js-marketplace, #js-add-product",
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
      } else if (
        deviceBelonging.hasClass("nitenan") &&
        !$("#nitenan-dashboard").length
      ) {
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
  reloadUserInfo();
});

function reloadUserInfo() {
  requestAJAX(
    "GlobalService",
    {
      requestType: "requestUserInfo",
      token: getMeta("token"),
    },
    function (response) {
      userInfo = JSON.parse(response);
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

let outputNames = [];
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

    config.title.text = "Grafik pada " + convertToDateLong(plotData.plotDate);
    config.xAxis.data = chartData.label;
    config.series[0].data = chartData.tempData;
    config.series[1].data = chartData.humidData;

    nexusChart = echarts.init(document.getElementById("graph"));
    nexusChart.setOption(config);
    // Add event listener for device size, if it's mobile just show the download image icon.
    const checkUltraMobile = window.matchMedia("screen and (max-width: 380px)");
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

function decimalSetOut(oSelectedValues) {
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
    formatOutput: decimalSetOut,
  });
}

function buildConditionalPicker(spaceNum) {
  $(`#condition${spaceNum} .content`).append(`
  <div class="item ctCd">
    <span>Jika</span>
    <input style="max-width:150px;text-align:center;" type="text" class="form-control" id="cndCd${spaceNum}" value="${outputNames[0]}" readonly>
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
        data: createTextDataSource([...outputNames]),
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

function buildHysteresisController(spaceNum, val) {
  $(`#condition${spaceNum} .content`).append(`
  <div class="item ctCd">
    <span>Atur Ke</span>
    <input style="max-width:70px;text-align:center;" type="text" class="form-control" id="js-aturKe${spaceNum}" value="0" readonly>
  </div>    
  <div class="item ctCd">
    <span>Toleransi</span>
    <input style="max-width:70px;text-align:center;" type="text" class="form-control" id="js-toleransi${spaceNum}" value="0" readonly>
  </div>
  <div class="item ctCd">
      <span>Pada Output</span>
      <input style="max-width:220px;text-align:center;" type="text" class="form-control" id="acCd${spaceNum}" readonly>
  </div>`);
  var oArrData = [];
  createDataSource(oArrData, [100, 9]);
  $("#js-aturKe" + spaceNum)
    .unbind()
    .removeData();
  $(`#js-aturKe${spaceNum}`).AnyPicker({
    // Add anypicker to every timer
    showComponentLabel: true,
    mode: "select",
    lang: "id",
    components: [
      {
        component: 0,
        name: "c",
        label:
          val == "Pemanas" || val == "Pendingin"
            ? "Suhu (°C)"
            : "Humiditas (%)",
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
    formatOutput: decimalSetOut,
  });
  $("#js-toleransi" + spaceNum)
    .unbind()
    .removeData();
  $(`#js-toleransi${spaceNum}`).AnyPicker({
    // Add anypicker to every timer
    showComponentLabel: true,
    mode: "select",
    lang: "id",
    components: [
      {
        component: 0,
        name: "c",
        label: "Toleransi",
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
    formatOutput: decimalSetOut,
  });
}

function buildActionPicker(spaceNum, acContent, hysteresis = false) {
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
        name: hysteresis == false ? "aksi" : "output",
        label: hysteresis == false ? "Aksi" : "Output",
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
                    <span>Pilih<span>
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
        name: "program",
        label: "Program",
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
          comparator.forEach((selement, mindex) => {
            comparator[mindex].forEach((element, index) => {
              comparator[mindex][index] = parseInt(element);
            });
          });
          let totalSecond = [
            comparator[0][0] * 3600 + comparator[0][1] * 60 + comparator[0][2],
            comparator[1][0] * 3600 + comparator[1][1] * 60 + comparator[1][2],
          ];
          if (totalSecond[0] > totalSecond[1]) {
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
        outputNames.forEach((element, index) => {
          passedData.cndCd = passedData.cndCd.replace(element, `${index}`);
        });
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
      } else if (
        ifVal == "Pemanas" ||
        ifVal == "Pendingin" ||
        ifVal == "Humidifier"
      ) {
        if (
          $(`#acCd${spaceNum}`).val() == "" ||
          $(`#js-toleransi${spaceNum}`).val() == "" ||
          $(`#js-aturKe${spaceNum}`).val() == ""
        ) {
          span.append(
            `<span class="tfailed" style="display:block;">Tidak boleh ada input/kotak yang kosong<span>`
          );
          success = false;
        } else {
          passedData.toleransi = $(`#js-toleransi${spaceNum}`).val();
          passedData.aturKe = $(`#js-aturKe${spaceNum}`).val();
          passedData.acCd = $(`#acCd${spaceNum}`).val();
          outputNames.forEach((element, index) => {
            passedData.acCd = passedData.acCd.replace(element, `${index}`);
          });
        }
      } else {
        success = false;
        span.append(
          `<span class="tfailed" style="display:block;">Program tidak boleh kosong<span>`
        );
      }

      if (success) {
        span.append(
          `<span style="display:block;">Mengupdate program ke database...<span>`
        );
        passedData.trCd = ifVal;
        passedData.progNum = spaceNum;
        passedData.acCd = passedData.acCd.replace("Nyalakan", "1");
        passedData.acCd = passedData.acCd.replace("Matikan", "0");
        outputNames.forEach((element, index) => {
          passedData.acCd = passedData.acCd.replace(element, `${index}`);
        });
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
          if (JSON.parse(response).success) $(`#condition${spaceNum}`).remove();
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
    if (val == "Nilai Suhu" || val == "Nilai Humiditas")
      buildTempOrHum(spaceNum, val);
    else if (val == "Jadwal Harian") buildDailyPicker(spaceNum);
    else if (val == "Tanggal Waktu") buildDateTimePicker(spaceNum);
    else if (val == "Keadaan") buildConditionalPicker(spaceNum);
    else if (val == "Pemanas" || val == "Pendingin" || val == "Humidifier")
      buildHysteresisController(spaceNum, val);
    // else if (val == "")

    let hysFlag = false;
    if (
      val == "Nilai Suhu" ||
      val == "Nilai Humiditas" ||
      val == "Jadwal Harian" ||
      val == "Tanggal Waktu" ||
      val == "Keadaan"
    )
      acContent = [
        `Nyalakan ${outputNames[0]}`,
        `Nyalakan ${outputNames[1]}`,
        `Nyalakan ${outputNames[2]}`,
        `Nyalakan ${outputNames[3]}`,
        `Matikan ${outputNames[0]}`,
        `Matikan ${outputNames[1]}`,
        `Matikan ${outputNames[2]}`,
        `Matikan ${outputNames[3]}`,
      ];
    else {
      acContent = [...outputNames];
      hysFlag = true;
    }

    buildActionPicker(spaceNum, acContent, hysFlag);
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
  $("#aux3Switch").prop("checked", Boolean(Number(arg.auxStatus3)));
  $("#aux4Switch").prop("checked", Boolean(Number(arg.auxStatus4)));
}

function parameterReload(arg, long = false) {
  if (arg.espStatusUpdateAvailable == 1) {
    binarySwitch(arg);
  }
  tempRadial.animate(arg.tempNow / 100);
  humidRadial.animate(arg.humidNow / 100);

  if (long) binarySwitch(arg);
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
        $("#deviceheader .text").text(parseJson["deviceInfo"]["masterName"]);
        $("#deviceheader .text").append("<i class = 'fas fa-caret-down'></i>");
        //Add submasters name that user had to dropdown
        $("#deviceheader .dropdown-menu").html("");
        for (x in parseJson.otherName) {
          $("#deviceheader .dropdown-menu").append(
            `<a href="#" data-device-type="${parseJson.otherName[x]["deviceType"]}"class="dropdown-item ` +
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
            let clickedDeviceName = $(
              "." + $(this).attr("class").split(/\s+/)[1]
            ).text();
            if ($(this).attr("data-device-type") == "nexus") {
              loadDeviceInformation({
                requestType: "loadDeviceInformation",
                master: clickedDeviceName,
                token: getMeta("token"),
              });
            } else if ($(this).attr("data-device-type") == "nitenan") {
              $("#content").html("");
              $("#content").load(
                "pagecon/nitenan-device.php",
                function (responseTxt, statusTxt, xhr) {
                  if (statusTxt == "success") {
                    loadNitenanInfo({
                      requestType: "loadNitenanInfo",
                      master: clickedDeviceName,
                      token: getMeta("token"),
                    });
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
            }
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
          "<p class='text'>" + parseJson["deviceInfo"]["masterName"] + "</p>"
        ); // Add name but without caret or dropdown
      }
      $("#js_humid-radial, #js_temp-radial").unbind().removeData(); // Remove any anypicker instance to make sure it's not duplicating.
      if (tempRadial != null) tempRadial.destroy();
      if (humidRadial != null) humidRadial.destroy();
      humidRadial = new ProgressBar.SemiCircle("#js_humid-radial", {
        strokeWidth: 12,
        color: "#1f81ff",
        trailColor: "#50a0a0",
        trailWidth: 12,
        easing: "easeInOut",
        duration: 1400,
        svgStyle: null,
        text: {
          value: "0%",
          alignToBottom: true,
        },
        from: {
          color: "#1f81ff",
        },
        to: {
          color: "#f5bd1f",
        },
        // Set default step function for all animate calls
        step: (state, bar) => {
          bar.path.setAttribute("stroke", state.color);
          var value = Math.round(bar.value() * 100);
          if (value === 0) {
            bar.setText("0%");
          } else {
            bar.setText(value + "%");
          }

          bar.text.style.color = state.color;
          $("#js_humIcon").css("color", state.color);
        },
      });
      tempRadial = new ProgressBar.SemiCircle("#js_temp-radial", {
        strokeWidth: 12,
        color: "#1f81ff",
        trailColor: "#50a0a0",
        trailWidth: 12,
        easing: "easeInOut",
        duration: 1400,
        svgStyle: null,
        text: {
          value: "0°C",
          alignToBottom: true,
        },
        from: {
          color: "#1f81ff",
        },
        to: {
          color: "#f54029",
        },
        // Set default step function for all animate calls
        step: (state, bar) => {
          bar.path.setAttribute("stroke", state.color);
          value = Math.round(bar.value() * 10000) / 100;
          // var value = Math.round(bar.value() * 100);
          if (value === 0) {
            bar.setText("0°C");
          } else {
            bar.setText(value + "°C");
          }

          bar.text.style.color = state.color;
          $("#js_tempIcon").css("color", state.color);
        },
      });
      humidRadial.text.style.fontFamily = "Poppins, sans-serif;";
      humidRadial.text.style.fontSize = "2rem";
      humidRadial.text.style.top = "60%";
      tempRadial.text.style.fontFamily = "Poppins, sans-serif;";
      tempRadial.text.style.fontSize = "2rem";
      tempRadial.text.style.top = "60%";
      $("#auxname1").text(parseJson.nexusBond.auxName1);
      $("#auxname2").text(parseJson.nexusBond.auxName2);
      $("#auxname3").text(parseJson.nexusBond.auxName3);
      $("#auxname4").text(parseJson.nexusBond.auxName4);
      outputNames[0] = parseJson.nexusBond.auxName1;
      outputNames[1] = parseJson.nexusBond.auxName2;
      outputNames[2] = parseJson.nexusBond.auxName3;
      outputNames[3] = parseJson.nexusBond.auxName4;

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
        minValue: new Date(oldest[0] - 1, oldest[1], 01),
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
        let progNum = parseJson.programs[index].progNumber;
        let trigger = parseJson.programs[index].progData1;
        let action = parseJson.programs[index].progData2;
        appendConditionalChild(progNum, [
          "Nilai Suhu",
          "Nilai Humiditas",
          "Jadwal Harian",
          "Tanggal Waktu",
          //"Timer",
          "Keadaan",
          "Pemanas",
          "Pendingin",
          "Humidifier",
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
          let stringBuffer = parseJson.programs[index].progData3;
          outputNames.forEach((element, index) => {
            stringBuffer = stringBuffer.replace(`${index}`, element);
          });
          $(`#cndCd${progNum}`).val(stringBuffer);
          $(`#cnddCd${progNum}`).val(parseJson.programs[index].progData4);
        } else if (
          trigger == "Pemanas" ||
          trigger == "Pendingin" ||
          trigger == "Humidifier"
        ) {
          buildHysteresisController(progNum, trigger);
          $(`#js-aturKe${progNum}`).val(parseJson.programs[index].progData3);
          $(`#js-toleransi${progNum}`).val(parseJson.programs[index].progData4);
        }
        var acContent = [];
        if (
          trigger != "Pemanas" &&
          trigger != "Pendingin" &&
          trigger != "Humidifier"
        ) {
          acContent = [
            `Nyalakan ${outputNames[0]}`,
            `Nyalakan ${outputNames[1]}`,
            `Nyalakan ${outputNames[2]}`,
            `Nyalakan ${outputNames[3]}`,
            `Matikan ${outputNames[0]}`,
            `Matikan ${outputNames[1]}`,
            `Matikan ${outputNames[2]}`,
            `Matikan ${outputNames[3]}`,
          ];
          action = action.split(" ");
          action[0] = action[0].replace("1", "Nyalakan ");
          action[0] = action[0].replace("0", "Matikan ");
          outputNames.forEach((element, index) => {
            action[1] = action[1].replace(`${index}`, element);
          });
          buildActionPicker(progNum, acContent);
          $(`#acCd${progNum}`).val(action[0] + action[1]);
        } else {
          acContent = [...outputNames];
          outputNames.forEach((element, index) => {
            action = action.replace(`${index}`, element);
          });
          buildActionPicker(progNum, acContent);
          $(`#acCd${progNum}`).val(action);
        }
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
        name: [
          $("#aux1Name").val(),
          $("#aux2Name").val(),
          $("#aux3Name").val(),
          $("#aux4Name").val(),
        ],
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
      $("#aux3Name").val(json.auxName3);
      $("#aux4Name").val(json.auxName4);
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

function nexusFirstLoad(bondKey = null, devName = null) {
  // Awal loading page load device yang paling atas.
  if (bondKey == null)
    loadDeviceInformation({
      requestType: "loadDeviceInformation",
      master: devName == null ? "master" : devName,
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
  $("#aux1Switch, #aux2Switch,#aux3Switch, #aux4Switch").unbind().removeData();
  // Onclick switches
  $("#aux1Switch, #aux2Switch,#aux3Switch, #aux4Switch").click(function () {
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
              (arg.id == arg.id) == "aux1Switch"
                ? $("#auxname1").text()
                : arg.id == "aux2Switch"
                ? $("#auxname2").text()
                : arg.id == "aux3Switch"
                ? $("#auxname3").text()
                : arg.id == "aux4Switch"
                ? $("#auxname4").text()
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
        "Keadaan",
        "Pemanas",
        "Pendingin",
        "Humidifier",
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
  clearTimeout(nexusReloadTimer);
  nexusReloadTimer = setTimeout(function reload() {
    if (getBondKey() != "" && $("#nexus-dashboard").length) {
      reloadStatus();
      // humidRadial.animate(Math.random());
    }
    nexusReloadTimer = setTimeout(reload, 3000);
  }, 3000);
}

function loadNitenanInfo(ajaxBuffer) {
  requestAJAX(
    "NitenanService",
    ajaxBuffer,
    function callback(response) {
      var parseJson = JSON.parse(response);
      nitenan = parseJson;
      // Add device bondKey information
      setBondKey(parseJson["deviceInfo"]["bondKey"]);
      if (parseJson.otherName) {
        // If user had more than one device
        // Add text and icon to the title of the main header
        $("#deviceheader .text").text(parseJson["deviceInfo"]["masterName"]);
        $("#deviceheader .text").append("<i class = 'fas fa-caret-down'></i>");
        //Add submasters name that user had to dropdown
        $("#deviceheader .dropdown-menu").html("");
        for (x in parseJson.otherName) {
          $("#deviceheader .dropdown-menu").append(
            `<a href="#" data-device-type="${parseJson.otherName[x]["deviceType"]}"class="dropdown-item ` +
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
            let clickedDeviceName = $(
              "." + $(this).attr("class").split(/\s+/)[1]
            ).text();
            if ($(this).attr("data-device-type") == "nexus") {
              $("#content").html("");
              $("#content").load(
                "pagecon/nexus-device.php",
                function (responseTxt, statusTxt, xhr) {
                  if (statusTxt == "success") {
                    nexusFirstLoad(null, clickedDeviceName);
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
            } else if ($(this).attr("data-device-type") == "nitenan") {
              loadNitenanInfo({
                requestType: "loadNitenanInfo",
                master: clickedDeviceName,
                token: getMeta("token"),
              });
            }
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
          "<p class='text'>" + parseJson["deviceInfo"]["masterName"] + "</p>"
        ); // Add name but without caret or dropdown
      }

      $("#js-brightness")[0].value = parseInt(
        parseJson.nitenanBond.flashBrightness
      );
      $("#js-servo")[0].value = parseInt(parseJson.nitenanBond.flashBrightness);

      $("#js-potret").unbind().removeData();
      $("#js-potret").click(function () {
        requestAJAX("NitenanService", {
          requestType: "takeSnapshot",
          bondKey: getBondKey(),
          token: getMeta("token"),
        });
        clearTimeout(nitenanPhotoTimer);
        nitenanPhotoTimer = setTimeout(function () {
          requestAJAX(
            "NitenanService",
            {
              requestType: "checkSnapshot",
              bondKey: getBondKey(),
              token: getMeta("token"),
            },
            function callback(response) {
              if (response == "1") {
                bootbox.alert({
                  size: "large",
                  title: "Pemberitahuan",
                  message: `Kontroller anda dengan nama <b>${nitenan.deviceInfo.masterName}</b> tidak memberikan respon pada perintah mengambil gambar, <b>pastikan kontroller anda menyambung ke internet</b> agar anda dapat mengambil gambar.`,
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
        }, 8000);
      });
      $("#js-stream").unbind().removeData();
      $("#js-stream").click(function () {
        requestAJAX("NitenanService", {
          requestType: "stream",
          type: $(this).children("span").text().split(" ")[0],
          bondKey: getBondKey(),
          token: getMeta("token"),
        });
      });
      clearTimeout(nitenanReloadTimer);
      nitenanReloadTimer = setTimeout(function reload() {
        if (getBondKey() != "" && $("#nitenan-dashboard").length) {
          requestAJAX(
            "NitenanService",
            {
              requestType: "updateStatus",
              bondKey: getBondKey(),
              token: getMeta("token"),
              data: {
                flash: $("#js-brightness")[0].value,
                servo: $("#js-servo")[0].value,
              },
            },
            function callback(xhrResponse) {
              let response = JSON.parse(xhrResponse);
              if (
                $("#js-nitenan-image").attr("data-timestamp") !=
                response["lastPhotoStamp"]
              ) {
                $("#js-nitenan-image").attr(
                  "data-timestamp",
                  response["lastPhotoStamp"]
                );
                $("#js-nitenan-image").attr(
                  "src",
                  `./img/nitenan/${getBondKey()}/1.jpg?${new Date().getTime()}`
                );
              }
              $("#js-stream")
                .children("span")
                .text(
                  response["streamCommand"] == 0
                    ? "Mulai Stream"
                    : "Stop Stream"
                );
            }
          );
        }
        nitenanReloadTimer = setTimeout(reload, 1000);
      }, 500);

      if (
        new Date().getTime() / 1000 -
          new Date(parseJson.nitenanBond.lastUpdate).getTime() / 1000 >=
        300
      ) {
        parseJson.nitenanBond.lastUpdate = parseJson.nitenanBond.lastUpdate.split(
          " "
        );
        setTimeout(function () {
          bootbox.alert({
            size: "large",
            title: "Pemberitahuan",
            message: `Kontroller anda dengan nama <b>${
              parseJson.deviceInfo.masterName
            }</b> sudah tidak mengirim respon ke server selama lebih dari 5 menit, <b>pastikan kontroller anda menyambung ke internet</b> agar anda dapat mengkontrol atau mengupdate kontroller.<br>Kontroller anda terakhir online pada<b> ${convertToDateLong(
              parseJson.nitenanBond.lastUpdate[0]
            )} ${parseJson.nitenanBond.lastUpdate[1]}</b>`,
            closeButton: false,
            buttons: {
              ok: {
                label: "Tutup",
              },
            },
          });
        }, 2000);
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

if (deviceBelonging.hasClass("maindevice")) {
} else if (deviceBelonging.hasClass("nexusdevice")) {
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
  });

  const check = setInterval(function () {
    // Function to check every 0.1s if bondKey is available, then execute reloadStatus() and destroy itself.
    if (getBondKey() != "") {
      $(".absolute-overlay").addClass("loaded");
      clearInterval(check); // kill after executed
    }
  }, 100);
} else if (deviceBelonging.hasClass("nitenan")) {
  $(document).ready(function () {
    loadNitenanInfo({
      requestType: "loadNitenanInfo",
      master: "master",
      token: getMeta("token"),
    });
  });
  const check = setInterval(function () {
    // Function to check every 0.1s if bondKey is available, then execute reloadStatus() and destroy itself.
    if (getBondKey() != "") {
      $(".absolute-overlay").addClass("loaded");
      clearInterval(check); // kill after executed
    }
  }, 100);
}
