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
            bottom: 24px;
            font-size: 26px;
        }

        .product__title {
            font-size: 18px;
        }

        .product__star {
            position: absolute;
            bottom: 34px;
            right: 60px;
            font-size: 26px;
            color: orange;
        }

        .product__rating {
            position: absolute;
            bottom: 26px;
            right: 24px;
            font-size: 24px;
            font-weight: bold;
        }

        .product__out-of {
            font-size: 12px;
            color: #777;
            position: absolute;
            bottom: 31px;
            right: 13px;
        }

        .product__review-label {
            font-size: 12px;
            color: #777;
            position: absolute;
            bottom: 18px;
            right: 20px;
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
        }

        .penjual-box__label {
            display: block;
            position: relative;
            bottom: 8px;
            font-size: 20px;
            font-weight: bold;
        }

        .penjual-box__dari {
            position: relative;
            right: 41px;
            top: 1px;
        }

        .penjual-box__contact {
            position: relative;
            right: -104px;
            bottom: 30px;
        }

        .penjual-box__username {
            position: relative;
            top: -20px;
            font-weight: bold;
            left: 20px;
        }

        .description-box {
            background-color: white;
            margin-right: -20px;
            margin-left: -20px;
            border-bottom: 1px solid #ddffee;
            border-top: 1px solid #ddffee;
            padding: 15px;
        }

        .description-box__label {
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
    </style>
    <div class="row product">
        <div class="col-12">
            <div id="productCarousel" class="carousel slide product__image" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#productCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#productCarousel" data-slide-to="1"></li>
                    <li data-target="#productCarousel" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="product__image" src="https://kanaanglobal.net/wp-content/uploads/2019/04/cara-ternak-ayam-potong.jpg" alt="">
                    </div>
                    <div class="carousel-item">
                        <img class="product__image" src="https://www.nusabali.com/article_images/71513/peternakan-ayam-di-bali-terkapar-2020-04-06-141737_0.jpg" alt="">
                    </div>
                    <div class="carousel-item">
                        <img class="product__image" src="https://www.batamnews.co.id/foto_berita/78chicken-2692019_960_720.jpg" alt="">
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
            <span class="product__price">Rp1.000</span>
            <span class="product__title">Ayam Sehat</span>
            <span class="fa fa-star product__star"></span>
            <span class="product__rating">4.6</span>
            <span class="product__out-of">/5</span>
            <span class="product__review-label">27 Ulasan</span>
        </div>
    </div>
    <div class="penjual-box mt-3">
        <span class="penjual-box__label">Penjual</span>
        <img class="penjual-box__photo" src="https://i.redd.it/4nj0lojm5no51.jpg" alt="">
        <span class="penjual-box__username">Lanove</span>
        <span class="penjual-box__dari">Kab. Kediri, Kec. Gurah</span>
        <span class="penjual-box__contact">085843503354</span>
    </div>
    <div class="description-box mt-3">
        <span class="description-box__label">Deskripsi Produk</span>
        <span class="description-box__content">Lorem ipsum,<br>
            dolor sit amet consectetur adipisicing elit.
            Odit fugiat at perferendis saepe,<br>
            id voluptatum ut nihil mollitia autem consectetur,
            fugit, esse aliquid porro totam iste atque dolorem
            repudiandae ipsa architecto a adipisci illum.
            Voluptatem, asperiores hic voluptatibus tempora voluptates assumenda
            laudantium laboriosam reiciendis sapiente veritatis,
            sequi nesciunt cumque excepturi fuga, dicta aliquam.
            Aliquam pariatur, sequi aspernatur saepe animi,
            nihil in ratione laudantium molestiae ullam tempora maiores nisi,
            doloremque quos quisquam totam perferendis est eum itaque consequatur ad.
            Magnam maxime velit quas cumque consequuntur provident alias eaque,
            ullam porro, quos facere mollitia amet ex? Quo tenetur sunt earum impedit qui.</span>
    </div>
</div>