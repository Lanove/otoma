<div class="container">
    <style>
        .user-box {
            background-color: white;
            height: 130px;
            margin-right: -20px;
            margin-left: -20px;
            border-radius: 25px 25px 0 0;
        }

        .user-box__photo {
            width: 80px;
            height: 80px;
            border-radius: 100%;
            position: relative;
            top: -40px;
        }

        .user-box__username {
            font-size: 22px;
            position: relative;
            top: -35px;
        }

        .user-box__nav {
            position: relative;
            top: -28px;
        }

        .user-box__setting-cog {
            font-size: 2rem;
            position: absolute;
            top: 10px;
            right: 15px;
        }

        .user-box__nav .nav-link {
            border-bottom: 5px solid transparent;
            border-top: 0px solid transparent;
            border-right: 0px solid transparent;
            border-left: 0px solid transparent;
            color: #999 !important;
        }

        .user-box__nav .nav-link.active {
            color: black !important;
            border-radius: 5px 5px 0 0;
            border-bottom: 5px solid #38a2ad;
        }

        .city-picker {
            background-color: white;
            margin-right: -20px;
            margin-left: -20px;
            border-bottom: 1px solid #ddffee;
            border-top: 1px solid #ddffee;
            padding: 15px;
            padding-left: 5px;
            padding-right: 5px;
        }

        .city-picker__selector {
            position: relative;
        }

        .city-picker__selector select {
            display: none;
            /*hide original SELECT element: */
        }

        .city-picker__selector__selected {
            background-color: #eee;
        }

        /* Style the arrow inside the select element: */
        .city-picker__selector__selected:after {
            position: absolute;
            content: "";
            top: 14px;
            right: 10px;
            width: 0;
            height: 0;
            border: 6px solid transparent;
            border-color: black transparent transparent transparent;
        }

        /* Point the arrow upwards when the select box is open (active): */
        .city-picker__selector__selected.city-picker__selector__arrow--active:after {
            border-color: transparent transparent black transparent;
            top: 7px;
        }

        /* style the items (options), including the selected item: */
        .city-picker__selector__items div,
        .city-picker__selector__selected {
            color: black;
            padding: 8px 16px;
            border: 1px solid transparent;
            border-color: transparent transparent rgba(0, 0, 0, 0.1) transparent;
            cursor: pointer;
        }

        /* Style items (options): */
        .city-picker__selector__items {
            position: absolute;
            background-color: #eee;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 99;
        }

        /* Hide the items when the select box is closed: */
        .city-picker__selector--hide {
            display: none;
        }

        .city-picker__selector__items div:hover,
        .city-picker__selector--same-as-selected {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .city-picker__label {
            font-size: 14px;
            position: relative;
            top: 3px;
            color: #777;
        }

        .product-box {
            background-color: white;
            border: 1px solid #ddffee;
            border-radius: 10px;
            padding: 10px;
            height: 122px;
            cursor: pointer;
        }

        .product-box__image {
            width: 120px;
            height: 120px;
            border-radius: 10px 0px 0px 10px;
            position: relative;
            bottom: 10px;
            right: 10px;
        }

        .product-box__price-box {
            font-size: 16px;
            font-weight: bold;
            position: absolute;
            right: 15px;
            width: 150px;
            border-radius: 15px;
            line-height: 30px;
        }

        .product-box__title-label {
            font-weight: bold;
            font-size: 18px;
            position: relative;
            bottom: 7px;
        }

        .product-box__from-label {
            font-size: 12px;
            color: #777;
            position: relative;
            bottom: 12px;
        }

        .product-box__oleh-label {
            font-size: 12px;
            color: #777;
            position: relative;
            bottom: 15px;
        }

        .product-box__star {
            position: relative;
            bottom: 4px;
            right: 5px;
            font-size: 26px;
            color: orange;
        }

        .product-box__rating {
            position: relative;
            bottom: 6px;
            right: 2px;
            font-size: 24px;
            font-weight: bold;
        }

        .product-box__out-of {
            font-size: 12px;
            color: #777;
            position: relative;
            top: 9px;
            right: 2px;
        }

        .product-box__review-label {
            font-size: 12px;
            color: #777;
            position: relative;
            top: 24px;
            right: 65px;
        }

        .add-box {
            background-color: white;
            margin-right: -20px;
            margin-left: -20px;
            border-bottom: 1px solid #ddffee;
            border-top: 1px solid #ddffee;
            padding: 15px;
            padding-left: 5px;
            padding-right: 5px;
            padding-bottom: 0px;
            cursor: pointer;
        }

        .add-box__plus-icon {
            position: relative;
            left: 27px;
            bottom: 19px;
            font-size: 12px;
        }

        .add-box__box-icon {
            font-size: 1.7rem;
            position: relative;
            top: 5px;
            margin-right: 10px;
        }
    </style>
    <div class="row mt-5 user-box">
        <div class="col-12">
            <div class="row">
                <img id="js-userphoto" class="user-box__photo ml-auto mr-auto" src="img/image-not-found.jpg" alt="">

                <a href="#0" id="js-marketplace-usersetting">
                    <i class="fas fa-cog user-box__setting-cog"></i>
                </a>
            </div>
            <div class="row">
                <span class="user-box__username ml-auto mr-auto" id="js-username"></span>
            </div>
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-justified nav-tabs user-box__nav">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#pembeli">
                                Pembeli
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#penjual">
                                Penjual
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="pembeli" role="tabpanel" aria-labelledby="contact-tab">
            <div class="row mt-3 city-picker">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <span style="font-weight: 550;font-size: 18px;">Dikirim Dari</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <span class="city-picker__label">Provinsi</span>
                            <div class="city-picker__selector mb-2" id="js-provinsi-selector" style="width:100%;">
                                <select>
                                    <option value="0">Pilih Provinsi</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
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
                                kotaSelector.childNodes[1].innerHTML += `<option value="${index + 1
                }">${element}</option>`;
                            });
                            // Setelah itu kita buat selectornya
                            buildSelector(kotaSelector);
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
                    fetch("js/json/pulau-jawa.json")
                        .then((response) => response.json())
                        .then((json) => {
                            provinsi = json;
                            let ii, x, l;

                            /*look for any elements with the class "city-picker__selector":*/
                            x = document.getElementsByClassName("city-picker__selector");
                            Object.keys(provinsi).forEach(function(key) {
                                x[0].childNodes[1].innerHTML += `<option value=${++ii}>${key}</option>`;
                            });
                            l = x.length;
                            // We build the provinsi selector from provided json
                            for (i = 0; i < l; i++) {
                                buildSelector(x[i]);
                            }
                        });
                </script>
            </div>
            <div class="row mt-3 product-box" data-product-id="">
                <div style="padding:0px;">
                    <img class="product-box__image" src="https://kanaanglobal.net/wp-content/uploads/2019/04/cara-ternak-ayam-potong.jpg" alt="">
                </div>
                <div style="padding-left:15px;">
                    <div class="row product-box__title-label">
                        <span>Ayam Sehat</span>
                    </div>
                    <div class="row product-box__from-label">
                        <span>Dari Kab. Kediri, Kec. Gurah</span>
                    </div>
                    <div class="row product-box__oleh-label">
                        <span>Oleh Lanove</span>
                    </div>
                    <div class="row">
                        <span class="fa fa-star product-box__star"></span>
                        <span class="product-box__rating">4.9</span>
                        <span class="product-box__out-of">/5</span>
                        <span class="product-box__review-label">89 Ulasan</span>
                        <div class="product-box__price-box btn btn-info">Rp1.000,00.</div>
                    </div>
                </div>
            </div>
            <div class="row mt-3 product-box" data-product-id="">
                <div style="padding:0px;">
                    <img class="product-box__image" src="https://static.republika.co.id/uploads/images/inpicture_slide/doc-atau-bibit-anak-ayam-ilustrasi-_160126165434-203.JPG" alt="">
                </div>
                <div style="padding-left:15px;">
                    <div class="row product-box__title-label">
                        <span>Ayam Murah</span>
                    </div>
                    <div class="row product-box__from-label">
                        <span>Dari Kab. Kediri, Kec. Grogol</span>
                    </div>
                    <div class="row product-box__oleh-label">
                        <span>Oleh mbah kung</span>
                    </div>
                    <div class="row">
                        <span class="fa fa-star product-box__star"></span>
                        <span class="product-box__rating">4.6</span>
                        <span class="product-box__out-of">/5</span>
                        <span class="product-box__review-label">27 Ulasan</span>
                        <div class="product-box__price-box btn btn-info">Rp5.000,00.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="penjual" role="tabpanel" aria-labelledby="contact-tab">
            <div class="row mt-3 add-box">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <span style="font-weight: 550;font-size: 18px;">Barang Saya</span>
                        </div>
                    </div>
                    <div class="row">
                        <div id="js-add-product"class="col-12 mt-2" style="border-top:1px solid #ddffee;padding:10px;">
                            <i class="fas fa-plus add-box__plus-icon"></i>
                            <i class="fas fa-box-open add-box__box-icon"></i>
                            <span>Tambahkan Produk</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>