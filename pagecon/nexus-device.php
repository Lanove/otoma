<div class="container-fluid nexusdevice" id="bigdevicebox">
    <div id="nexus-dashboard"></div>
    <div class="row" id="deviceheader">
        <div class="col-12">
            <div class="row">
                <div class="col-12 d-inline-flex align-items-center" style="overflow-wrap:anywhere;">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown">
                            <p class="text"></p>
                            <div class="dropdown-menu"></div>
                        </a>
                    </div>
                    <a href="#0" class="cog" id="deviceSetting">
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
                                    <i class="fas fa-thermometer-half icon ibcolor" id="js_tempIcon"></i>
                                    <div id="js_temp-radial" class="radial-gauge"></div>
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
                                    <i class="fas fa-tint icon ibcolor" id="js_humIcon"></i>
                                    <div id="js_humid-radial" class="radial-gauge"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row auxheader">
                        <div class="col-12 d-inline-flex align-items-center" style="margin-left:-5px;padding-right:5px;">
                            <i class=" fas fa-plug icon ibcolor"></i>
                            <span class="text" style="font-size:1.25rem;margin-left:10px;" id="auxname1"></span>
                            <div class="material-switch pull-right ml-auto switch" style="position:relative;top:2px;">
                                <input id="aux1Switch" type="checkbox" />
                                <label for="aux1Switch" class="label-primary"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row auxheader">
                        <div class="col-12 d-inline-flex align-items-center" style="margin-left:-5px;padding-right:5px;">
                            <i class=" fas fa-plug icon ibcolor"></i>
                            <span class="text" style="font-size:1.25rem;margin-left:10px;" id="auxname2"></span>
                            <div class="material-switch pull-right ml-auto switch" style="position:relative;top:2px;">
                                <input id="aux2Switch" type="checkbox" />
                                <label for="aux2Switch" class="label-primary"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row auxheader">
                        <div class="col-12 d-inline-flex align-items-center" style="margin-left:-5px;padding-right:5px;">
                            <i class=" fas fa-plug icon ibcolor"></i>
                            <span class="text" style="font-size:1.25rem;margin-left:10px;" id="auxname3"></span>
                            <div class="material-switch pull-right ml-auto switch" style="position:relative;top:2px;">
                                <input id="aux3Switch" type="checkbox" />
                                <label for="aux3Switch" class="label-primary"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row auxheader">
                        <div class="col-12 d-inline-flex align-items-center" style="margin-left:-5px;padding-right:5px;">
                            <i class=" fas fa-plug icon ibcolor"></i>
                            <span class="text" style="font-size:1.25rem;margin-left:10px;" id="auxname4"></span>
                            <div class="material-switch pull-right ml-auto switch" style="position:relative;top:2px;">
                                <input id="aux4Switch" type="checkbox" />
                                <label for="aux4Switch" class="label-primary"></label>
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
                                <div class="col-12 d-inline-flex align-items-center" style="min-height: 55px;max-height:55px;">
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
            <div id="conditional"></div>
            <div class="row" style="margin-top:10px;">
                <div class="col-12 d-inline-flex">
                    <button class="btn btn-primary" id="addCond"><i class="fas fa-plus"></i></button>
                    <a href="#0" class="ml-auto btn btn-primary helpBtn" id="progHelp">Bantuan<i style="position:relative;top:3px;" class="fas fa-info-circle infocon"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>