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
            <div class="col-12 d-inline-flex">
                <img id="js-nitenan-image" class="ml-auto mr-auto nitenan-image" src="img/image-not-found.jpg" alt="img/image-not-found.jpg" data-timestamp="">
            </div>
        </div>
        <div class="row" style="margin-top:10px;">
            <div class="col-6">
                <span class="centerme slider-label">Kecerahan</span>
                <div class="slidecontainer">
                    <input type="range" min="0" max="100" value="50" class="slider" id="js-brightness">
                </div>
            </div>
            <div class="col-6">
                <span class="centerme slider-label">Sudut Servo</span>
                <div class="slidecontainer">
                    <input type="range" min="0" max="180" value="90" class="slider" id="js-servo">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 d-inline-flex">
                <button class="btn btn-primary ml-auto" style="margin-top:10px;" id="js-potret">
                    Ambil Foto
                    <i style="position:relative;top:0.5px;margin-left:0px;" class="fas fa-camera infocon"></i>
                </button>
                <button class="btn btn-primary mr-auto" style="margin-left:5px;margin-top:10px;" id="js-stream">
                    <span>Mulai Stream</span>
                    <i style="position:relative;top:2.5px;margin-left:0px;" class="fas fa-video infocon"></i>
                </button>
            </div>
        </div>
    </div>
</div>