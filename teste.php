<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Elemetris IoT</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" />
  <style>
    html,
    body {
      margin: 0;
      padding: 0;
      height: 100%;
    }

    .wrapper {
      min-height: 100%;
      height: 100%;
      position: relative;
    }

    .content {
      width: 100%;
      padding: 20px;
      min-height: 100vh;
      transition: all 0.3s;
      transition: all 0.5s;
      padding-bottom: 100px;
    }

    .footer {
      position: absolute;
      bottom: 0;
      width: 100%;
      min-height: 60px;
      /* Height of the footer */
      max-height: 60px;
      background: var(--teal);
      padding-top: 10px;
      padding-bottom: 10px;
    }

    .footer a {
      color: white;
    }

    .jumbotron {
      background-color: var(--teal);
    }

    .devicebox {
      border: solid 2px var(--green);
      margin-top: 10px;
      border-radius: 10px;
      padding: 3px;
      min-height: 50px;
    }

    /* The switch - the box around the slider */
    .switch {
      position: absolute;
      right: 30px;
      bottom: -13px;
      display: inline-block;
      width: 60px;
      height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    /* The slider */
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: .4s;
      transition: .4s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 26px;
      width: 26px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }

    input:checked+.slider {
      background-color: var(--green);
    }

    input:focus+.slider {
      box-shadow: 0 0 1px var(--green);
    }

    input:checked+.slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }

    .slider.round:before {
      border-radius: 50%;
    }

    .icon {
      font-size: 1.5rem;
      margin-left: 10px;
      margin-right: 10px;
      position: relative;
      bottom: -3px;
    }

    h5 {
      position: relative;
      bottom: -5px;
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <div class="container-fluid">
      <div class="jumbotron">
        <h1>Elemetris</h1>
        <h5>ESP8266 Web Server dengan output LED</h3>
      </div>
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6">
            <div class="devicebox">
              <div class="row">
                <div class="col-12 d-inline-flex align-items-center">
                  <i class="fas fa-lightbulb icon"></i>
                  <h5>LED 1</h5>
                  <label class="switch">
                    <input type="checkbox" onclick='ledUpdate(this.checked)' name="box">
                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="devicebox">
              <div class="row">
                <div class="col-12 d-inline-flex align-items-center">
                  <i class="fas fa-lightbulb icon"></i>
                  <h5>LED 2</h5>
                  <label class="switch">
                    <input type="checkbox">
                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <div class="devicebox">
              <div class="row">
                <div class="col-12 d-inline-flex align-items-center">
                  <i class="fas fa-lightbulb icon"></i>
                  <h5>LED 3</h5>
                  <label class="switch">
                    <input type="checkbox">
                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="devicebox">
              <div class="row">
                <div class="col-12 d-inline-flex align-items-center">
                  <i class="fas fa-lightbulb icon"></i>
                  <h5>LED 4</h5>
                  <label class="switch">
                    <input type="checkbox">
                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid footer justify-content-center align-items-center">
      <div class="row d-flex justify-content-center">
        <div class="col-12 text-center">
          <span>© 2020</span>
          <a href="#0">Eldi4/Lanove/Elemetris</a>
          <span>All rights reserved</span>
        </div>
      </div>
    </div>
  </div>
</body>
<script>
  function ledUpdate(led) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        console.log(this.responseText)
      }
    };
    xhttp.open("GET", "teste.php?LEDstate=" + led, true);
    xhttp.send();
  }

  function getData() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        // document.getElementById("ADCValue").innerHTML =
        //   this.responseText;
      }
    };
    xhttp.open("GET", "getData", true);
    xhttp.send();
  }
  setInterval(function() {
    // Call a function repetatively with 2 Second interval
    getData();
  }, 2000); //2000mSeconds update rate
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js"></script>
<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>

</html>