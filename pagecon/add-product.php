<div class="container" style="position: relative;bottom: 5px;">
    <style>
        .main-box {
            margin-left: -20px;
            margin-right: -20px;
            background-color: white;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .main-box__label {
            display: block;
            font-size: 20px;
            font-weight: bold;
        }

        .main-box__nama-label {
            font-size: 14px;
            position: relative;
            top: 3px;
            color: #777;
            display: block;
        }

        .main-box__input-text,
        .main-box__deskripsi-box {
            -webkit-transition: all 0.30s ease-in-out;
            -moz-transition: all 0.30s ease-in-out;
            -ms-transition: all 0.30s ease-in-out;
            -o-transition: all 0.30s ease-in-out;
            outline: none;
            border: 1px solid #DDDDDD;
        }

        .main-box__input-text:focus,
        .main-box__deskripsi-box:focus {
            box-shadow: 0 0 5px #38a2ad;
            border: 1px solid #38a2ad;
        }

        .main-box__input-text {
            width: 100%;
            height: 42px;
            border: 2px solid #ddd;
            border-radius: 4px;
            padding: 8px 16px;
        }

        .main-box__deskripsi-box {
            width: 100%;
            height: 200px;
            border: 2px solid #ddd;
            border-radius: 4px;
            padding: 8px 16px;
        }

        .picker__selector {
            position: relative;
        }

        .picker__selector select {
            display: none;
            /*hide original SELECT element: */
        }

        .picker__selector__selected {
            background-color: #eee;
        }

        /* Style the arrow inside the select element: */
        .picker__selector__selected:after {
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
        .picker__selector__selected.picker__selector__arrow--active:after {
            border-color: transparent transparent black transparent;
            top: 7px;
        }

        /* style the items (options), including the selected item: */
        .picker__selector__items div,
        .picker__selector__selected {
            color: black;
            padding: 8px 16px;
            border: 1px solid transparent;
            border-color: transparent transparent rgba(0, 0, 0, 0.1) transparent;
            cursor: pointer;
        }

        /* Style items (options): */
        .picker__selector__items {
            position: absolute;
            background-color: #eee;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 99;
        }

        /* Hide the items when the select box is closed: */
        .picker__selector--hide {
            display: none;
        }

        .picker__selector__items div:hover,
        .picker__selector--same-as-selected {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .input-file {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        .input-file+label {
            height: 100px;
            width: 100%;
            border: 3px dotted #ddd;
            border-radius: 10px;
            padding: 8px;
        }

        .input-file+label {
            cursor: pointer;
            /* "hand" cursor */
        }

        .input-file:focus+label {
            outline: 1px dotted #000;
            outline: -webkit-focus-ring-color auto 5px;
        }

        .input-file__preview {
            width: 78px;
            height: 78px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 2px;
        }

        .input-file__label {
            font-size: 14px;
            padding: 5px;
        }
    </style>
    <div class="row main-box">
        <div class="col-12">
            <span class="main-box__label">Tambahkan Produk</span>
            <span class="main-box__nama-label">Nama Produk</span>
            <input class="main-box__input-text" type="text" name="nama" id="js-nama" placeholder="Masukkan nama barang">
            <span class="main-box__nama-label mt-2">Harga Produk (Rp)</span>
            <input class="main-box__input-text" type="number" name="harga" id="js-harga" placeholder="Masukkan harga barang">
            <span class="main-box__nama-label mt-2">Pilih Kamera</span>
            <div class="picker__selector" id="js-camera-selector" style="width:100%;">
                <select>
                    <option value="0">Pilih Kamera</option>
                </select>
            </div>
            <span class="main-box__nama-label mt-2">Deskripsi Produk</span>
            <textarea placeholder="Masukkan deskripsi produk" name="deskripsi" id="js-deskripsi" class="main-box__deskripsi-box"></textarea>
            <span class="main-box__nama-label mt-2">Gambar Produk</span>
            <input type="file" name="js-file-upload" id="js-file-upload" multiple class="input-file" />
            <label for="js-file-upload" class="d-inline-flex">
                <img id="js-preview-1" class="input-file__preview" src="http://192.168.7.112:8080/otoma/img/upload-image-icon.jpg" alt="">
                <img id="js-preview-2" class="ml-auto mr-auto input-file__preview" src="http://192.168.7.112:8080/otoma/img/upload-image-icon.jpg" alt="">
                <img id="js-preview-3" class="input-file__preview" src="http://192.168.7.112:8080/otoma/img/upload-image-icon.jpg" alt="">
                <button class="ml-auto input-file__label btn btn-info" onclick="this.parentNode.click();">Upload Gambar Disini</button>
            </label>
            <button class="btn btn-info mt-2" style="width:100%;">Tambahkan Produk</button>
        </div>
    </div>
    <script>
        /*when the select box is clicked, close any other select boxes,
                      and open/close the current select box*/
        function selectBoxEvent(e) {
            e.stopPropagation();
            closeAllSelect(this);
            this.nextSibling.classList.toggle("picker__selector--hide");
            this.classList.toggle("picker__selector__arrow--active");
        }

        /*when an item of selector is clicked, update the original select box, and the selected item*/
        function selectItemEvent(e) {
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
                    this.setAttribute(
                        "class",
                        "picker__selector--same-as-selected"
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
            a.setAttribute("class", "picker__selector__selected");
            a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
            element.appendChild(a);
            /*for each element, create a new DIV that will contain the option list:*/
            b = document.createElement("DIV");
            b.setAttribute("class", "picker__selector__items picker__selector--hide");
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
        buildSelector(document.getElementById("js-camera-selector"));
        let filesInput = document.getElementById("js-file-upload");
        filesInput.addEventListener("change", function(event) {
            let files = event.target.files; //FileList object
            if (files.length == 3) {
                for (let i = 0; i < files.length; i++) {
                    let output = document.getElementById(`js-preview-${i+1}`);
                    let file = files[i];
                    //Only pics
                    if (!file.type.match('image'))
                        continue;
                    let picReader = new FileReader();
                    picReader.addEventListener("load", function(event) {
                        let picFile = event.target;
                        let div = document.createElement("div");
                        output.src = picFile.result;
                        output.title = picFile.name;
                    });
                    //Read the image
                    picReader.readAsDataURL(file);
                }
            } else {
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
        });
    </script>
</div>