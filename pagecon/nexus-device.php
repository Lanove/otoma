<div class="container-fluid nexusdevice" id="bigdevicebox">
    <div class="row" id="deviceheader">
        <dummy class=""></dummy>
        <div class="col-12">
            <div class="row">
                <div class="col-12 d-inline-flex align-items-center">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown">
                            <p class="text">aefafefae</p>
                            <div class="dropdown-menu"></div>
                        </a>
                    </div>
                    <a href="#0" class="cog">
                        <i class="fas fa-cog ml-auto ibcolor"></i>
                    </a>
                </div>
            </div>
            <div class="row" id="tabs">
                <div class="col-12">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#menu1">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu2">Thermocontrol</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu3">IO Auxiliary</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu4">Kondisional</a>
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
                                    <i class="fas fa-thermometer-half icon ibcolor"></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 centerme">
                                    <span>37.5°C</span>
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
                                    <span>60%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="nexusthspbox">
                        <div class="mini-overlay active" id="setpointoverlay" style="top:10px;">
                            <div class="mino d-flex align-items-center justify-content-center text-center">
                                <p>Aktifkan Thermal Controller untuk mengakses set poin suhu</p>
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
                                <div class="col-12 centerme">
                                    <a href="#0" class="d-inline-flex">
                                        <span>37°C</span>
                                        <div class="btn-group-vertical">
                                            <i class="fas fa-caret-square-up udbutton"></i>
                                            <i class="fas fa-caret-square-down udbutton"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-6">
                    <div class="device-graph-box">
                        <div class="mini-overlay" id="tempgraphoverlay">
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
                                        <p class="text">Grafik Suhu</p>
                                    </div>
                                    <button class="btn btn-info ml-auto" id="dateselector">
                                        <i class="fas fa-calendar-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Device status box content -->
                        <div class="content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="chart">
                                        <canvas id="graph"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="device-graph-box">
                        <div class="mini-overlay" id="humidgraphoverlay">
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
                                        <p class="text">Grafik Humiditas</p>
                                    </div>
                                    <button class="btn btn-info ml-auto" id="dateselector">
                                        <i class="fas fa-calendar-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Device status box content -->
                        <div class="content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="chart">
                                        <canvas id="graph"></canvas>
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
                                            <input type="radio" id="manualop" name="operation" value="yes" checked />
                                            <label for="manualop">Manual</label>
                                            <input type="radio" id="autoop" name="operation" value="no" />
                                            <label for="autoop">Auto</label>
                                        </div>
                                    </div>

                                    <a href="#" data-toggle="popover" title="Informasi" data-content="Some content inside the popover">
                                        <i class="fas fa-info-circle infocon"></i>
                                    </a>
                                </div>
                                <div class="col-md-6 d-inline-flex">
                                    <p>Mode : </p>
                                    <div class="ml-auto">
                                        <div class="switch-field">
                                            <input type="radio" id="heatingmd" name="mode" value="yes" checked />
                                            <label for="heatingmd">Heating</label>
                                            <input type="radio" id="coolingmd" name="mode" value="no" />
                                            <label for="coolingmd">Cooling</label>
                                            <input type="radio" id="dualmd" name="mode" value="no" />
                                            <label for="dualmd">Dual</label>
                                        </div>
                                    </div>
                                    <a href="#" data-toggle="popover" title="Informasi" data-content="Some content inside the popover">
                                        <i class="fas fa-info-circle infocon"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="childbox">
                                        <div class="row header" style="margin-left:0px;">
                                            <div class="col-12 d-inline-flex align-items-center" style="padding:0;">
                                                <i class="fas fa-fire icon ibcolor"></i>
                                                <span class="text">Pemanas</span>
                                                <div class="material-switch pull-right ml-auto switch">
                                                    <input id="heaterSwitch" type="checkbox" />
                                                    <label for="heaterSwitch" class="label-primary"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="content">
                                            <div class="row">
                                                <div class="col-12">
                                                    
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="childbox">
                                        <div class="row header" style="margin-left:0px;">
                                            <div class="col-12 d-inline-flex align-items-center" style="padding:0;">
                                                <i class="fas fa-snowflake icon ibcolor"></i>
                                                <span class="text">Pendingin</span>
                                                <div class="material-switch pull-right ml-auto switch">
                                                    <input id="coolerSwitch" type="checkbox" />
                                                    <label for="coolerSwitch" class="label-primary"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="content">
                                            <div class="row">
                                                <div class="col-12">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
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
            ...</div>
        <div class="tab-pane fade" id="menu4" role="tabpanel" aria-labelledby="contact-tab">
            ...</div>
    </div>
</div>