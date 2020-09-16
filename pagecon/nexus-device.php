<div class="container-fluid nexusdevice" id="bigdevicebox">
    <div class="row" id="deviceheader">
        <dummy class=""></dummy>
        <div class="col-12 d-inline-flex align-items-center">
            <div class="dropdown">
                <a href="#" data-toggle="dropdown">
                    <p class="text">aefafefae</p>
                    <div class="dropdown-menu"></div>
                </a>
            </div>
            <a href="#0" class="cog">
                <i class="fas fa-cog ml-auto"></i>
            </a>
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
                            <i class="fas fa-thermometer-half icon"></i>
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
                            <i class="fas fa-tint icon"></i>
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
                            <i class="fas fa-thermometer-half icon"></i>
                            <i class="fas fa-cog" style="position:relative;top:-14px;"></i>
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
                            <i class="fas fa-chart-bar icon"></i>
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
                            <i class="fas fa-chart-bar icon"></i>
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