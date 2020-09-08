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

<?php
/*
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>
 
#include "index.h" //Our HTML webpage contents with javascripts
 
#define LED 2  //On board LED
 
//SSID and Password of your WiFi router
const char* ssid = "circuits4you.com";
const char* password = "123456789";
 
ESP8266WebServer server(80); //Server on port 80
 
//===============================================================
// This routine is executed when you open its IP in browser
//===============================================================
void handleRoot() {
 String s = MAIN_page; //Read HTML contents
 server.send(200, "text/html", s); //Send web page
}
 
void handleADC() {
 int a = analogRead(A0);
 String adcValue = String(a);
 
 server.send(200, "text/plane", adcValue); //Send ADC value only to client ajax request
}
 
void handleLED() {
 String ledState = "OFF";
 String t_state = server.arg("LEDstate"); //Refer  xhttp.open("GET", "setLED?LEDstate="+led, true);
 Serial.println(t_state);
 if(t_state == "1")
 {
  digitalWrite(LED,LOW); //LED ON
  ledState = "ON"; //Feedback parameter
 }
 else
 {
  digitalWrite(LED,HIGH); //LED OFF
  ledState = "OFF"; //Feedback parameter  
 }
 
 server.send(200, "text/plane", ledState); //Send web page
}
//==============================================================
//                  SETUP
//==============================================================
void setup(void){
  Serial.begin(115200);
  
  WiFi.begin(ssid, password);     //Connect to your WiFi router
  Serial.println("");
 
  //Onboard LED port Direction output
  pinMode(LED,OUTPUT); 
  
  // Wait for connection
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
 
  //If connection successful show IP address in serial monitor
  Serial.println("");
  Serial.print("Connected to ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());  //IP address assigned to your ESP
 
  server.on("/", handleRoot);      //Which routine to handle at root location. This is display page
  server.on("/setLED", handleLED);
  server.on("/readADC", handleADC);
 
  server.begin();                  //Start server
  Serial.println("HTTP server started");
}
//==============================================================
//                     LOOP
//==============================================================
void loop(void){
  server.handleClient();          //Handle client requests
}*/
?>