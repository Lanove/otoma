<div class="container-fluid nitenan" id="bigdevicebox">
    <div id="nitenan-dashboard"></div>
    <div class="row" id="deviceheader">
        <div class="col-12">
            <div class="row">
                <div class="col-12 d-inline-flex align-items-center" style="overflow-wrap:anywhere;">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown">
                            <p class="text">aa</p>
                            <div class="dropdown-menu"></div>
                        </a>
                    </div>
                    <a href="#0" class="cog" id="js-nitenanSetting">
                        <i class="fas fa-cog ml-auto ibcolor"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="nitenan-content">
        <div class="row">
            <div class="col-12">
                <img class="nitenan-image" src="img/image-not-found.jpg" alt="">
            </div>
        </div>
        <div class="row" style="margin-top:10px;">
            <div class="col-6">
                <span class="centerme slider-label">Cahaya Flash</span>
                <div class="slidecontainer">
                    <input type="range" min="1" max="100" value="50" class="slider" id="js-brightness">
                </div>
            </div>
            <div class="col-6">
                <span class="centerme slider-label">Posisi Servo</span>
                <div class="slidecontainer">
                    <input type="range" min="1" max="180" value="90" class="slider" id="js-servo">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 d-inline-flex">
                <button class="btn btn-primary ml-auto mr-auto" style="margin-top:10px;" id="js-potret">
                    Ambil Foto
                    <i style="position:relative;top:0.5px;margin-left:0px;" class="fas fa-camera infocon"></i>
                </button>
            </div>
        </div>
    </div>
</div>