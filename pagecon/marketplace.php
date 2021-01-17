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
    </style>
    <div class="row mt-5 user-box">
        <div class="col-12">
            <div class="row">
                <img class="user-box__photo ml-auto mr-auto" src="https://i.redd.it/4nj0lojm5no51.jpg" alt="">

                <a href="#0" id="js-marketplace-usersetting">
                    <i class="fas fa-cog user-box__setting-cog"></i>
                </a>
            </div>
            <div class="row">
                <span class="user-box__username ml-auto mr-auto">Lanove</span>
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
            function isElement(o) {
                return typeof HTMLElement === "object" ?
                    o instanceof HTMLElement //DOM2
                    :
                    o &&
                    typeof o === "object" &&
                    o !== null &&
                    o.nodeType === 1 &&
                    typeof o.nodeName === "string";
            }
            let provinsi;

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
                // If user changes the selector item, and it was a provinsi selector then..
                if (selectorId == "js-provinsi-selector" && h.innerHTML != this.innerHTML) {
                    let kotaSelector = document.getElementById("js-kota-selector"),
                        adakahKotaSelector = (typeof(kotaSelector) != 'undefined' && kotaSelector != null);
                    // Jika selector kota udah ada kita delete dulu sebelum recreate
                    if (adakahKotaSelector){
                        document.getElementById("js-kota-label").remove();
                        kotaSelector.remove();
                    }
                    selectorElmt.parentNode.insertAdjacentHTML(
                        "beforeend",
                        `<span class="city-picker__label" id="js-kota-label">Kota/Kabupaten</span>
                            <div class="city-picker__selector" id="js-kota-selector" style="width:100%;">
                                <select>
                                <option value="0">Pilih Kota</option>
                                </select>
                            </div>`
                    );
                    selectorElmt.parentNode.childNodes.forEach(element => {
                        if (element.id == "js-kota-selector") kotaSelector = element;
                    });
                    provinsi[this.innerHTML].forEach((element, index) => {
                        kotaSelector.childNodes[1].innerHTML += `<option value="${index+1}">${element}</option>`;
                    });
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
                        this.setAttribute(
                            "class",
                            "city-picker__selector--same-as-selected"
                        );
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
            document.addEventListener("click", closeAllSelect);

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
                b.setAttribute("class", "city-picker__selector__items city-picker__selector--hide");
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
</div>