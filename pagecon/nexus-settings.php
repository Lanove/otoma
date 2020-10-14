<div class="container-fluid" id="bigdevicebox">
    <div class="row" id="deviceheader">
        <div class="col-12">
            <div class="row">
                <div class="col-12 d-inline-flex align-items-center" style="overflow-wrap:anywhere;">
                    <p class="text" id="deviceName"></p>
                    <a href="#0" class="cog" id="deviceHome">
                        <i class="fas fa-home ml-auto ibcolor"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="setting-box">
                <div class="header">
                    <div class="row">
                        <div class="col-12">
                            <span>Nama Output</span>
                        </div>
                    </div>
                    <div class="line"></div>
                </div>
                <div class="content">
                    <div class="row">
                        <div class="col-12">
                            <span>Output 1 :</span>
                            <input type="text" class="form-control" id="aux1Name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <span>Output 2 :</span>
                            <input type="text" class="form-control" id="aux2Name">
                            <span style="display:block;" id="infoAuxName"></span>
                            <button type="button" class="btn btn-primary  mt-3" id="auxNameSubmit">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="setting-box">
                <div class="header">
                    <div class="row">
                        <div class="col-12">
                            <span>Nama Kontroler</span>
                        </div>
                    </div>
                    <div class="line"></div>
                </div>
                <div class="content">
                    <div class="row">
                        <div class="col-12">
                            <span>Nama :</span>
                            <input type="text" class="form-control" id="deviceNameEdit">
                            <span style="display:block;" id="infoName"></span>
                            <button type="button" class="btn btn-primary  mt-3" id="submitEdit">Update</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="setting-box">
                <div class="header">
                    <div class="row">
                        <div class="col-12">
                            <span>Lainnya</span>
                        </div>
                    </div>
                    <div class="line"></div>
                </div>
                <div class="content">
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-danger mr-2 mt-2" id="delDevice">Hapus Kontroler</button>
                            <button type="button" class="btn   btn-info mt-2" id="conCheck">Cek Koneksi</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>