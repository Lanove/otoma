<div class="container-fluid nexusdevice" id="bigdevicebox">
    <div class="row" id="deviceheader">
        <dummy class=""></dummy>

        <div class="col-12">
            <div class="row">
                <div class="col-12 d-inline-flex align-items-center">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown">
                            <p class="text"></p>
                            <div class="dropdown-menu"></div>
                        </a>
                    </div>
                    <a href="#0" class="cog">
                        <i class="fas fa-cog ml-auto ibcolor"></i>
                    </a>
                </div>
            </div>
            <div class="row" id="tabs">
                <div class="col-12" style="position:relative;left:17px;">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#menu1">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu2">
                                Thermocontrol
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu3">
                                Otomasi
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="menu1" role="tabpanel" aria-labelledby="contact-tab">

            <div class="row">
                <div class="col-12">
                    <div class="row auxheader">
                        <div class="col-12 d-inline-flex align-items-center" style="margin-left:-5px;padding-right:5px;">
                            <div class="progBar">
                                <div id="progBar2">
                                    Tidak ada timer
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="nexusthspbox">
                        <div class="header">
                            <div class="row">
                                <div class="col-12">
                                    <span class="text centerme">Suhu</span>
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <div class="row">
                                <div class="col-12 centerme">
                                    <i class="fas fa-thermometer-half icon ibcolor"></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 centerme">
                                    <span id="tempnow"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="nexusthspbox">
                        <div class="header">
                            <div class="row">
                                <div class="col-12">
                                    <span class="text centerme">Humiditas</span>
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <div class="row">
                                <div class="col-12 centerme">
                                    <i class="fas fa-tint icon ibcolor"></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 centerme">
                                    <span id="humidnow"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="nexusthspbox">
                        <div class="mini-overlay" id="setpointoverlay" style="height: calc(100% + 10px);">
                            <div class="mino d-flex align-items-center justify-content-center text-center">
                                <p>Pengaturan set point suhu hanya dapat dilakukan dalam thermal controller mode auto</p>
                            </div>
                        </div>
                        <div class="header">
                            <div class="row">
                                <div class="col-12">
                                    <span class="text centerme">Set Poin Suhu</span>
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <div class="row">
                                <div class="col-12 centerme">
                                    <i class="fas fa-thermometer-half icon ibcolor"></i>
                                    <i class="fas fa-cog ibcolor" style="position:relative;top:-14px;"></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 centerme d-inline-flex">
                                    <span id="spvalue"></span>
                                    <div class="btn-group-vertical">
                                        <i class="fas fa-caret-square-up udbutton"></i>
                                        <i class="fas fa-caret-square-down udbutton"></i>
                                    </div>
                                    <a href="#0" class="d-inline-flex" id="spsetting">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row auxheader">
                        <div class="col-12 d-inline-flex align-items-center" style="margin-left:-5px;padding-right:5px;">
                            <i class=" fas fa-plug icon ibcolor"></i>
                            <span class="text" style="font-size:1.25rem;margin-left:10px;" id="auxname1"></span>
                            <div class="material-switch pull-right ml-auto switch" style="position:relative;top:2px;">
                                <input id="aux1Switch" type="checkbox" onClick="switchToggle(this.id)" />
                                <label for="aux1Switch" class="label-primary"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row auxheader">
                        <div class="col-12 d-inline-flex align-items-center" style="margin-left:-5px;padding-right:5px;">
                            <i class=" fas fa-plug icon ibcolor"></i>
                            <span class="text" style="font-size:1.25rem;margin-left:10px;" id="auxname2"></span>
                            <div class="material-switch pull-right ml-auto switch" style="position:relative;top:2px;">
                                <input id="aux2Switch" type="checkbox" onClick="switchToggle(this.id)" />
                                <label for="aux2Switch" class="label-primary"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="device-graph-box">
                        <div class="mini-overlay" id="graphoverlay">
                            <div class="mino">
                                <div class="myLoader">
                                    <div class="loader quantum-spinner">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Device status box status -->
                        <div class="header">
                            <div class="row">
                                <div class="col-12 d-inline-flex align-items-center" style="min-height: 55px;">
                                    <i class="fas fa-chart-bar icon ibcolor"></i>
                                    <div class="dropdown name">
                                        <p class="text">Grafik</p>
                                    </div>
                                    <input type="hidden" id="datebuffer">
                                    <button class="btn btn-primary ml-auto" id="dateselector">
                                        <i class="fas fa-calendar-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Device status box content -->
                        <div class="content">
                            <div class="mini-overlay" id="goverlay" style="padding:0;">
                                <div class="mino d-flex align-items-center justify-content-center text-center" style="border-radius: 0px 0px 10px 10px;">
                                    <p></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div id="graph">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="menu2" role="tabpanel" aria-labelledby="contact-tab">

            <div class="row">
                <div class="col-12">
                    <div class="nexustcon">
                        <!-- Device status box status -->
                        <div class="header">
                            <div class="row">
                                <div class="col-12 d-inline-flex align-items-center" style="min-height: 55px;">
                                    <i class="fas fa-fire icon ibcolor"></i>
                                    <i class="fas fa-snowflake snowflake ibcolor"></i>
                                    <div class="dropdown name">
                                        <p class="text">Thermal Controller</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <div class="row">
                                <div class="col-md-6 d-inline-flex">
                                    <p>Operasi : </p>
                                    <div class="ml-auto">
                                        <div class="switch-field">
                                            <input type="radio" id="manualop" name="operation" value="manual" />
                                            <label for="manualop">Manual</label>
                                            <input type="radio" id="autoop" name="operation" value="auto" />
                                            <label for="autoop">Auto</label>
                                        </div>
                                    </div>
                                    <a href="#0" data-toggle="popover" title="Informasi" data-trigger="focus" data-placement="left" data-content="Operasi yang digunakan oleh kontroller.<br> Jika <b>Manual</b> maka keadaan nyala dan mati dari pemanas dan pendingin dapat dikontrol dengan saklar dari web.<br> Jika <b>Auto</b> maka kontroller akan menstabilkan suhu secara otomatis sesuai setpoint.">
                                        <i class="fas fa-info-circle infocon"></i>
                                    </a>
                                </div>
                                <div class="col-md-6 d-inline-flex">
                                    <p>Mode : </p>
                                    <div class="ml-auto">
                                        <div class="switch-field">
                                            <input type="radio" id="heatingmd" name="thermmode" value="heat" />
                                            <label for="heatingmd">Heating</label>
                                            <input type="radio" id="coolingmd" name="thermmode" value="cool" />
                                            <label for="coolingmd">Cooling</label>
                                            <input type="radio" id="dualmd" name="thermmode" value="dual" />
                                            <label for="dualmd">Dual</label>
                                        </div>
                                    </div>
                                    <a href="#0" data-toggle="popover" title="Informasi" data-trigger="focus" data-placement="left" data-content="Output yang akan digunakan untuk menyetabilkan suhu secara otomatis. <br> Jika <b>Heating</b> maka output yang akan digunakan hanyalah pemanas.<br>Jika <b>Cooling</b> maka output yang akan digunakan hanyalah pendingin.<br>Jika <b>Dual</b> maka output yang akan digunakan pemanas dan pendingin.">
                                        <i class="fas fa-info-circle infocon"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="childbox" id="heaterbox">
                                        <div class="row header" style="margin-left:0px;">
                                            <div class="col-12 d-inline-flex align-items-center" style="padding:0;">
                                                <i class="fas fa-fire icon ibcolor"></i>
                                                <span class="text">Pemanas</span>
                                                <div class="material-switch pull-right ml-auto switch">
                                                    <input id="heaterSwitch" type="checkbox" onClick="switchToggle(this.id)" />
                                                    <label for="heaterSwitch" class="label-primary"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="content">
                                            <div class="mini-overlay" id="heateroverlay">
                                                <div class="mino d-flex align-items-center justify-content-center text-center">
                                                    <p>Hanya tersedia dalam mode Heating atau Dual</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 d-inline-flex">
                                                    <p>Mode : </p>
                                                    <div class="ml-auto">
                                                        <div class="switch-field">
                                                            <input type="radio" id="hpidmd" name="hmode" value="pid" />
                                                            <label for="hpidmd">PID</label>
                                                            <input type="radio" id="hhysteresismd" name="hmode" value="hys" />
                                                            <label for="hhysteresismd">Hysteresis</label>
                                                        </div>
                                                    </div>
                                                    <a href="#0" data-toggle="popover" title="Informasi" data-trigger="focus" data-placement="left" data-content="Mode algoritma yang digunakan untuk pemanas. Cari tau informasi perbedaan antara mode PID dengan Hysteresis klik <a href='#0'class='infolink'>disini</a>.">
                                                        <i class="fas fa-info-circle infocon"></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="pidmenu active">
                                                <div class="row">
                                                    <div class="col-12 d-inline-flex">
                                                        <p>Kp :</p>
                                                        <input id="hkp" type="text" class="form-control ml-auto" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="Informasi" data-content="Konstanta Proporsional dari algoritma PID, hanya dapat berupa <b>angka positif atau angka negatif</b>, untuk informasi lengkap cara mengatur PID klik <a href='#0'class='infolink'>disini</a>.">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 d-inline-flex">
                                                        <p>Ki :</p>
                                                        <input id="hki" type="text" class="form-control ml-auto" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="Informasi" data-content="Konstanta Derivatif dari algoritma PID, hanya dapat berupa <b>angka positif atau angka negatif</b>, untuk informasi lengkap cara mengatur PID klik <a href='#0'class='infolink'>disini</a>.">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 d-inline-flex">
                                                        <p>Kd :</p>
                                                        <input id="hkd" type="text" class="form-control ml-auto" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="Informasi" data-content="Konstanta Integral dari algoritma PID, hanya dapat berupa <b>angka positif atau angka negatif</b>, untuk informasi lengkap cara mengatur PID klik <a href='#0'class='infolink'>disini</a>.">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 d-inline-flex">
                                                        <p>Durasi Siklus :</p>
                                                        <input id="hds" type="text" class="form-control ml-auto" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="Informasi" data-content="Durasi dari siklus algoritma PID dalam satuan milidetik (1 detik = 1000 milidetik), untuk informasi lengkap cara mengatur PID klik <a href='#0'class='infolink'>disini</a>.">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <span class="" id="spanhpid"></span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 d-inline-flex">
                                                        <button class="btn btn-primary" id="submithpid" onclick='submitPar(this.id)'>Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="hysteresismenu">
                                                <div class="row">
                                                    <div class="col-12 d-inline-flex">
                                                        <p>Batas Atas :</p>
                                                        <input id="hba" type="text" class="form-control ml-auto" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="Informasi" data-content="<u>Batas atas</u> dari setpoint, hanya dapat diisi <b>angka positif</b>. Jika suhu sekarang naik dari <u>setpoint</u> ditambah <u>batas atas</u> maka pemanas akan mati. Untuk informasi lengkap cara mengatur Hysteresis klik <a href='#0'class='infolink'>disini</a>.">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 d-inline-flex">
                                                        <p>Batas Bawah :</p>
                                                        <input id="hbb" type="text" class="form-control ml-auto" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="Informasi" data-content="<u>Batas bawah</u> dari setpoint, hanya dapat diisi <b>angka positif</b>. Jika suhu sekarang turun dari <u>setpoint</u> dikurang <u>batas bawah</u> maka pemanas akan nyala. Untuk informasi lengkap cara mengatur Hysteresis klik <a href='#0'class='infolink'>disini</a>.">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span class="" id="spanhhys"></span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 d-inline-flex">
                                                        <button class="btn btn-primary" id="submithhys" onclick='submitPar(this.id)'>Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="childbox" id="coolerbox">
                                        <div class="row header" style="margin-left:0px;">
                                            <div class="col-12 d-inline-flex align-items-center" style="padding:0;">
                                                <i class="fas fa-snowflake icon ibcolor"></i>
                                                <span class="text">Pendingin</span>
                                                <div class="material-switch pull-right ml-auto switch">
                                                    <input id="coolerSwitch" type="checkbox" onClick="switchToggle(this.id)" />
                                                    <label for="coolerSwitch" class="label-primary"></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="content">
                                            <div class="mini-overlay" id="cooleroverlay">
                                                <div class="mino d-flex align-items-center justify-content-center text-center">
                                                    <p></p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12 d-inline-flex">
                                                    <p>Mode : </p>
                                                    <div class="ml-auto">
                                                        <div class="switch-field">
                                                            <input type="radio" id="cpidmd" name="cmode" value="pid" />
                                                            <label for="cpidmd">PID</label>
                                                            <input type="radio" id="chysteresismd" name="cmode" value="hys" />
                                                            <label for="chysteresismd">Hysteresis</label>
                                                        </div>
                                                    </div>
                                                    <a href="#0" data-toggle="popover" title="Informasi" data-trigger="focus" data-placement="left" data-content="Mode algoritma yang digunakan untuk pendingin. Cari tau informasi perbedaan antara mode PID dengan Hysteresis klik <a href='#0'class='infolink'>disini</a>.">
                                                        <i class="fas fa-info-circle infocon"></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="pidmenu active">
                                                <div class="row">
                                                    <div class="col-12 d-inline-flex">
                                                        <p>Kp :</p>
                                                        <input id="ckp" type="text" class="form-control ml-auto" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="Informasi" data-content="Konstanta Proporsional dari algoritma PID, hanya dapat berupa <b>angka positif atau angka negatif</b>, untuk informasi lengkap cara mengatur PID klik <a href='#0'class='infolink'>disini</a>.">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 d-inline-flex">
                                                        <p>Ki :</p>
                                                        <input id="cki" type="text" class="form-control ml-auto" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="Informasi" data-content="Konstanta Derivatif dari algoritma PID, hanya dapat berupa <b>angka positif atau angka negatif</b>, untuk informasi lengkap cara mengatur PID klik <a href='#0'class='infolink'>disini</a>.">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 d-inline-flex">
                                                        <p>Kd :</p>
                                                        <input id="ckd" type="text" class="form-control ml-auto" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="Informasi" data-content="Konstanta Integral dari algoritma PID, hanya dapat berupa <b>angka positif atau angka negatif</b>, untuk informasi lengkap cara mengatur PID klik <a href='#0'class='infolink'>disini</a>.">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 d-inline-flex">
                                                        <p>Durasi Siklus :</p>
                                                        <input id="cds" type="text" class="form-control ml-auto" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="Informasi" data-content="Durasi dari siklus algoritma PID dalam satuan milidetik (1 detik = 1000 milidetik), untuk informasi lengkap cara mengatur PID klik <a href='#0'class='infolink'>disini</a>.">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span class="" id="spancpid"></span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 d-inline-flex">
                                                        <button class="btn btn-primary" id="submitcpid" onclick='submitPar(this.id)'>Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="hysteresismenu">
                                                <div class="row">
                                                    <div class="col-12 d-inline-flex">
                                                        <p>Batas Atas :</p>
                                                        <input id="cba" type="text" class="form-control ml-auto" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="Informasi" data-content="<u>Batas atas</u> dari setpoint, hanya dapat diisi <b>angka positif</b>. Jika suhu sekarang naik dari <u>setpoint</u> ditambah <u>batas atas</u> maka pendingin akan menyala. Untuk informasi lengkap cara mengatur Hysteresis klik <a href='#0'class='infolink'>disini</a>.">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 d-inline-flex">
                                                        <p>Batas Bawah :</p>
                                                        <input id="cbb" type="text" class="form-control ml-auto" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" title="Informasi" data-content="<u>Batas bawah</u> dari setpoint, hanya dapat diisi <b>angka positif</b>. Jika suhu sekarang turun dari <u>setpoint</u> dikurang <u>batas bawah</u> maka pendingin akan mati. Untuk informasi lengkap cara mengatur Hysteresis klik <a href='#0'class='infolink'>disini</a>.">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <span class="" id="spanchys"></span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 d-inline-flex">
                                                        <button class="btn btn-primary" id="submitchys" onclick='submitPar(this.id)'>Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="menu3" role="tabpanel" aria-labelledby="contact-tab">
            <div id="conditional"></div>
            <div class="row" style="margin-top:10px;">
                <div class="col-12 d-inline-flex">
                    <button class="btn btn-primary" id="addCond"><i class="fas fa-plus"></i></button>
                    <a href="#0" class="ml-auto btn btn-primary helpBtn">Bantuan<i style="position:relative;top:3px;" class="fas fa-info-circle infocon"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>