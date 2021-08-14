<div class="container">
    <style>
        .delete-icon {
            position: absolute;
            font-size: 1.5rem;
            cursor: pointer;
            -webkit-filter: invert(100%);
            filter: invert(100%);
            color: #f00;
            z-index: 2;
            transition: all 0.3s;
        }

        .delete-icon:hover {
            transform: scale(1.1, 1.1);
        }

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
            border: 1px solid #ddd;
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
            position: relative;
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
            right: 10px;
            width: 120px;
            border-radius: 15px;
            line-height: 30px;
            bottom: 5px;
        }

        .product-box__title-label {
            font-weight: bold;
            font-size: 18px;
            position: absolute;
            top: 5px;
        }

        .product-box__from-label {
            font-size: 12px;
            color: #777;
            position: absolute;
            top: 25px;
        }

        .product-box__oleh-label {
            font-size: 12px;
            color: #777;
            position: absolute;
            top: 40px;
        }

        .product-box__star {
            position: absolute;
            top: 70px;
            left: 140px;
            font-size: 26px;
            color: orange;
        }

        .product-box__rating {
            position: absolute;
            top: 67px;
            left: 170px;
            font-size: 24px;
            font-weight: bold;
        }

        .product-box__out-of {
            font-size: 12px;
            color: #777;
            position: absolute;
            top: 80px;
            left: 205px;
        }

        .product-box__review-label {
            font-size: 12px;
            color: #777;
            position: absolute;
            top: 95px;
            left: 145px;
            width: 70px;
            text-align: center;
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

        .not-found__frown {
            display: block;
            font-size: 100px;
            font-family: "Gill Sans", "Gill Sans MT", "Trebuchet MS", sans-serif;
            transform: rotate(90deg);
            text-align: center;
            position: relative;
            top: 30px;
            left: 12px;
        }

        .not-found__oops {
            font-size: x-large;
            display: block;
            font-weight: bold;
        }

        .not-found__description {
            display: block;
            font-size: 14px;
            color: #555;
            position: relative;
            bottom: 7px;
        }

        .city-picker__reset {
            position: absolute;
            right: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 3px;
            cursor: pointer;
            background-color: #eee;
            z-index: 2;
        }

        .city-picker__reset:hover,
        .city-picker__reset:focus {
            background-color: #ddd;
        }
    </style>
    <div class="row mt-5 user-box">
        <div class="col-12">
            <div class="row">
                <img id="js-userphoto" class="user-box__photo ml-auto mr-auto" src="img/image-not-found.jpg" alt="">

                <a href="#0" id="goAccount">
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
                    <div id="js-reset-filter" class="city-picker__reset"><i class="fas fa-filter"></i>Reset Filter</div>
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
            </div>
            <div class="row mt-3 product-box" id="f33a56cbca">
                <div style="padding:0px;">
                    <img class="product-box__image" src="img/product-photo/download.jpeg" alt="">
                </div>
                <div style="padding-left:15px;">
                    <div class="row product-box__title-label">
                        <span>Ayam bobot 2 kg</span>
                    </div>
                    <div class="row product-box__from-label">
                        <span>Dari Jawa Timur, Kab. Kediri</span>
                    </div>
                    <div class="row product-box__oleh-label">
                        <span>Oleh pak faigo</span>
                    </div>
                    <div class="row">
                        <svg class="svg-inline--fa fa-star fa-w-18 product-box__star" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                        </svg><!-- <span class="fa fa-star product-box__star"></span> -->
                        <span class="product-box__rating">4.3</span>
                        <span class="product-box__out-of">/5</span>
                        <span class="product-box__review-label">67 Ulasan</span>
                        <div class="product-box__price-box btn btn-info">Rp48.000</div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-3 product-box" id="f33a56cbca">
                <div style="padding:0px;">
                    <img class="product-box__image" src="img/product-photo/download 1.jpeg" alt="">
                </div>
                <div style="padding-left:15px;">
                    <div class="row product-box__title-label">
                        <span>Nomor satu</span>
                    </div>
                    <div class="row product-box__from-label">
                        <span>Dari Jawa Timur, Kab. Kediri</span>
                    </div>
                    <div class="row product-box__oleh-label">
                        <span>Oleh Fifi</span>
                    </div>
                    <div class="row">
                        <svg class="svg-inline--fa fa-star fa-w-18 product-box__star" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                        </svg><!-- <span class="fa fa-star product-box__star"></span> -->
                        <span class="product-box__rating">3.7</span>
                        <span class="product-box__out-of">/5</span>
                        <span class="product-box__review-label">82 Ulasan</span>
                        <div class="product-box__price-box btn btn-info">Rp30.000</div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-3 product-box" id="f33a56cbca">
                <div style="padding:0px;">
                    <img class="product-box__image" src="img/product-photo/download 2.jpeg" alt="">
                </div>
                <div style="padding-left:15px;">
                    <div class="row product-box__title-label">
                        <span>Ternak ayam subor</span>
                    </div>
                    <div class="row product-box__from-label">
                        <span>Dari Jawa Tengah, Kab. Semarang</span>
                    </div>
                    <div class="row product-box__oleh-label">
                        <span>Oleh pak Subur</span>
                    </div>
                    <div class="row">
                        <svg class="svg-inline--fa fa-star fa-w-18 product-box__star" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                        </svg><!-- <span class="fa fa-star product-box__star"></span> -->
                        <span class="product-box__rating">5.0</span>
                        <span class="product-box__out-of">/5</span>
                        <span class="product-box__review-label">116 Ulasan</span>
                        <div class="product-box__price-box btn btn-info">Rp45.000</div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-3 product-box" id="f33a56cbca">
                <div style="padding:0px;">
                    <img class="product-box__image" src="img/product-photo/download 4.jpeg" alt="">
                </div>
                <div style="padding-left:15px;">
                    <div class="row product-box__title-label">
                        <span>Ternak makmur</span>
                    </div>
                    <div class="row product-box__from-label">
                        <span>Dari Jawa Timur, Kota Kediri</span>
                    </div>
                    <div class="row product-box__oleh-label">
                        <span>Oleh Bu Fadil</span>
                    </div>
                    <div class="row">
                        <svg class="svg-inline--fa fa-star fa-w-18 product-box__star" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                        </svg><!-- <span class="fa fa-star product-box__star"></span> -->
                        <span class="product-box__rating">4.2</span>
                        <span class="product-box__out-of">/5</span>
                        <span class="product-box__review-label">54 Ulasan</span>
                        <div class="product-box__price-box btn btn-info">Rp24.000</div>
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
                        <div id="js-add-product" class="col-12 mt-2" style="border-top:1px solid #ddffee;padding:10px;">
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