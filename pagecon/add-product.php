<div class="container" style="position: relative;bottom: 5px;">
    <style>
        .main-box {
            margin-left: -20px;
            margin-right: -20px;
            background-color: white;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .back-icon {
            position: absolute;
            font-size: 1.5rem;
            top: 5px;
            cursor: pointer;
        }

        .main-box__label {
            display: block;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
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
            <i class="fas fa-arrow-left back-icon" id="js-marketplace"></i>
            <span class="main-box__label">Tambahkan Produk</span>
            <span class="main-box__nama-label">Nama Produk</span>
            <input class="main-box__input-text" type="text" name="nama" id="js-namaBarang" placeholder="Masukkan nama barang">
            <span class="main-box__nama-label mt-2">Harga Produk (Rp)</span>
            <input class="main-box__input-text" type="number" name="harga" id="js-hargaBarang" min="100" max="100000000" placeholder="Masukkan harga barang">
            <span class="main-box__nama-label mt-2">Pilih Kamera</span>
            <div class="picker__selector" id="js-camera-selector" style="width:100%;">
                <select>
                    <option value="0">Pilih Kamera</option>
                </select>
            </div>
            <span class="main-box__nama-label mt-2">Deskripsi Produk</span>
            <textarea placeholder="Masukkan deskripsi produk" name="deskripsi" id="js-deskripsiBarang" class="main-box__deskripsi-box"></textarea>
            <span class="main-box__nama-label mt-2">Gambar Produk</span>
            <input type="file" name="js-file-upload" id="js-file-upload" multiple class="input-file" />
            <label for="js-file-upload" class="d-inline-flex">
                <img data-b64="" id="js-preview-1" class="input-file__preview" src="http://192.168.7.112:8080/otoma/img/upload-image-icon.jpg" alt="">
                <img data-b64="" id="js-preview-2" class="ml-auto mr-auto input-file__preview" src="http://192.168.7.112:8080/otoma/img/upload-image-icon.jpg" alt="">
                <img data-b64="" id="js-preview-3" class="input-file__preview" src="http://192.168.7.112:8080/otoma/img/upload-image-icon.jpg" alt="">
                <button class="ml-auto input-file__label btn btn-info" onclick="this.parentNode.click();">Upload Gambar Disini</button>
            </label>
            <button class="btn btn-info mt-2" style="width:100%;" onclick="submitAddProduct()">Tambahkan Produk</button>
        </div>
    </div>
</div>