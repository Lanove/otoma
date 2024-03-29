<div class="container" style="position: relative;bottom: 5px;">
    <style>
        .product {
            background-color: white;
            margin-right: -20px;
            margin-left: -20px;
            border-bottom: 1px solid #ddffee;
            border-top: 1px solid #ddffee;
            padding: 15px;
            padding-left: 5px;
            padding-right: 5px;
            padding-bottom: 5px;
        }


        .back-icon {
            position: absolute;
            font-size: 1.5rem;
            top: -5px;
            left: 10px;
            cursor: pointer;
            -webkit-filter: invert(100%);
            filter: invert(100%);
            color: #f00;
            z-index: 2;
        }

        .product__image {
            width: calc(100% + 40px);
            height: 300px;
            position: relative;
            right: 20px;
            bottom: 20px;
        }

        .product__price {
            display: block;
            font-weight: bold;
            position: absolute;
            top: 265px;
            font-size: 26px;
        }

        .product__title {
            font-size: 18px;
        }

        .product__star {
            position: absolute;
            top: 265px;
            right: 60px;
            font-size: 26px;
            color: orange;
        }

        .product__rating {
            position: absolute;
            top: 265px;
            right: 24px;
            font-size: 24px;
            font-weight: bold;
        }

        .product__out-of {
            font-size: 12px;
            color: #777;
            position: absolute;
            top: 278px;
            right: 13px;
        }

        .product__review-label {
            font-size: 12px;
            color: #777;
            position: absolute;
            top: 290px;
            right: 13px;
            width: 70px;
            text-align: center;
        }

        .product__review-box {
            position: absolute;
        }

        .product__image .carousel-indicators {
            z-index: 1;
        }

        .penjual-box {
            background-color: white;
            margin-right: -20px;
            margin-left: -20px;
            border-bottom: 1px solid #ddffee;
            border-top: 1px solid #ddffee;
            padding: 15px;
            position: relative;
            height: 142px;
        }

        .penjual-box__photo {
            width: 80px;
            height: 80px;
            border-radius: 100%;
            position: relative;
            left: 10px;
            border: 1px solid #ddd;
        }

        .penjual-box__label {
            display: block;
            position: relative;
            bottom: 8px;
            font-size: 20px;
            font-weight: bold;
        }

        .penjual-box__dari {
            position: absolute;
            left: 120px;
            top: 70px;
        }

        .penjual-box__contact {
            position: absolute;
            left: 120px;
            top: 90px;
        }

        .penjual-box__username {
            position: absolute;
            top: 50px;
            font-weight: bold;
            left: 120px;
        }

        .description-box,
        .live-image-box {
            background-color: white;
            margin-right: -20px;
            margin-left: -20px;
            border-bottom: 1px solid #ddffee;
            border-top: 1px solid #ddffee;
            padding: 15px;
            position: relative;
        }

        .description-box__label,
        .live-image-box__label {
            display: block;
            position: relative;
            bottom: 8px;
            font-size: 20px;
            font-weight: bold;
        }

        .description-box__content {
            display: block;
            font-size: 15px;
            line-height: 20px;
        }

        .live-image-box__timeStamp {
            font-size: 14px;
            color: #777;
            position: absolute;
            top: 35px;
        }

        .live-image-box__status {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 5px;
            position: absolute;
            right: 15px;
            top: 10px;
            background-color: lawngreen;
        }
    </style>
    <div class="row product">
        <div class="col-12">
            <div style="background-color:#f00">
                <i class="fas fa-arrow-left back-icon" id="js-marketplace"></i>
            </div>
            <div id="productCarousel" class="carousel slide product__image" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#productCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#productCarousel" data-slide-to="1"></li>
                    <li data-target="#productCarousel" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="product__image" src="img/image-not-found.jpg" alt="" id="js-gambar-1">
                    </div>
                    <div class="carousel-item">
                        <img class="product__image" src="img/image-not-found.jpg" alt="" id="js-gambar-2">
                    </div>
                    <div class="carousel-item">
                        <img class="product__image" src="img/image-not-found.jpg" alt="" id="js-gambar-3">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#productCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#productCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <span class="product__price" id="js-price"></span>
            <span class="product__title overflow-auto" id="js-title"></span>
            <span class="fa fa-star product__star"></span>
            <span class="product__rating" id="js-rating"></span>
            <span class="product__out-of">/5</span>
            <span class="product__review-label" id="js-review"></span>
        </div>
    </div>
    <div class="penjual-box mt-3">
        <span class="penjual-box__label">Penjual</span>
        <img class="penjual-box__photo" src="img/image-not-found.jpg" alt="" id="js-photo">
        <span class="penjual-box__username" id="js-nama"></span>
        <span class="penjual-box__dari" id="js-from"></span>
        <span class="penjual-box__contact" id="js-contact"></span>
    </div>
    <div class="description-box mt-3">
        <span class="description-box__label">Deskripsi Produk</span>
        <span class="description-box__content" id="js-description"></span>
    </div>
    <div class="live-image-box mt-3" id="">
        <div class="live-image-box__status">ON</div>
        <span class="live-image-box__label">Live Camera</span>
        <span class="live-image-box__timeStamp"></span>
        <div class="d-inline-flex" style="width:100%;">
            <img id="js-live-image" class="ml-auto mr-auto nitenan-image" src="img/image-not-found.jpg" alt="img/image-not-found.jpg" data-timestamp="">
        </div>
    </div>
</div>