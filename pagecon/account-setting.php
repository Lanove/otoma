<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <div class="setting-box">
                <div class="header">
                    <div class="row">
                        <div class="col-12">
                            <span>Ganti Password</span>
                        </div>
                    </div>
                    <div class="line"></div>
                </div>
                <div class="content">
                    <div class="row">
                        <div class="col-12">
                            <span>Password Lama :</span>
                            <input type="password" class="form-control" id="ACpasswordLama">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <span>Password Baru :</span>
                            <input type="password" class="form-control" id="ACpasswordBaru">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <span>Konfirmasi Password Baru :</span>
                            <input type="password" class="form-control" id="ACconfirmBaru">
                            <span style="display:block;" id="ACpasswordNotif"></span>
                            <button type="button" class="btn btn-primary  mt-3" id="ACpasswordSubmit">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="row">
                <div class="col-12">
                    <div class="setting-box">
                        <div class="header">
                            <div class="row">
                                <div class="col-12">
                                    <span>Informasi Akun</span>
                                </div>
                            </div>
                            <div class="line"></div>
                        </div>
                        <div class="content">
                            <div class="row">
                                <div class="col-12">
                                    <span>Email :</span>
                                    <input type="email" class="form-control" id="ACemail">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <span>Kontak :</span>
                                    <input type="number" class="form-control" id="js-phoneNumber">
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
                            <div class="row">
                                <div class="col-12">
                                    <span>Foto Profil :</span>
                                    <input type="file" name="js-pp" id="js-pp" data-b64="">
                                    <span style="display:block;" id="ACuserNotif"></span>
                                    <button type="button" class="btn btn-primary  mt-3" id="ACuserSubmit">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
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
                                    <button type="button" class="btn btn-danger mr-2 mt-2" id="ACdelete">Hapus Akun</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>