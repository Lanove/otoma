<?php
if (isset($_SERVER['HTTP_DEVICE_TOKEN']) && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_SERVER['HTTP_ESP8266_BUILD_VERSION']) && isset($_SERVER['HTTP_ESP8266_SDK_VERSION']) && isset($_SERVER['HTTP_ESP8266_CORE_VERSION']) && isset($_SERVER['HTTP_ESP8266_MAC']) && isset($_SERVER['HTTP_ESP8266_SKETCH_MD5']) && isset($_SERVER['HTTP_ESP8266_SKETCH_FREE_SPACE']) && isset($_SERVER['HTTP_ESP8266_SKETCH_SIZE']) && isset($_SERVER['HTTP_ESP8266_CHIP_SIZE'])) {
    $json = json_decode(file_get_contents('php://input'), true); // Get JSON Input from AJAX and decode it to PHP Array
    /*
    [CONTENT_TYPE] => application/json
    [HTTP_DEVICE_TOKEN] => te9Dz1MfFK
    [HTTP_ESP8266_BUILD_VERSION] => 1.0.0
    [HTTP_ESP8266_SDK_VERSION] => 2.2.2-dev(38a443e)
    [HTTP_ESP8266_CORE_VERSION] => 2_7_4
    [HTTP_ESP8266_MAC] => 40:F5:20:25:49:D6
    [HTTP_ESP8266_SKETCH_MD5] => 5aba035a3d003f533221c573d06565fc
    [HTTP_ESP8266_SKETCH_FREE_SPACE] => 2797568
    [HTTP_ESP8266_SKETCH_SIZE] => 344368
    [HTTP_ESP8266_CHIP_SIZE] => 4194304
    */
    // print_r($_SERVER);
    if (isset($json["type"])) {
        header("Connection: keep-alive");
        $macAddr = $_SERVER['HTTP_ESP8266_MAC'];
        $buildVersion = $_SERVER['HTTP_ESP8266_BUILD_VERSION'];
        $deviceToken = $_SERVER['HTTP_DEVICE_TOKEN'];
        $requestType = $json["type"];
        require "DatabaseController.php";
        $dbController = new DatabaseController();
        $privilegeCheck = $dbController->runQuery("SELECT bondKey,softwareVersion FROM bond WHERE deviceToken = :deviceToken;", ["deviceToken" => $deviceToken]);
        if (isset($privilegeCheck["bondKey"])) {
            $bondKey = $privilegeCheck["bondKey"];
            // If the ESP Version is different than on database, we change the database to be matched on the ESP one
            if ($privilegeCheck["softwareVersion"] != $_SERVER['HTTP_ESP8266_BUILD_VERSION']) {
                $dbController->execute("UPDATE bond SET softwareVersion=:vers WHERE bondKey = :bondKey;", ["vers" => $_SERVER['HTTP_ESP8266_BUILD_VERSION'], "bondKey" => $bondKey]);
            }
            if ($requestType == "tnhns")
                updateTnHnS($bondKey, $json, $dbController);
        } else {
            echo json_encode(["order" => "fallback"]);
        }
    }
} else
    header('HTTP/1.1 403 Forbidden');

function updateTnHnS($bondKey, $json, $dbC)
{
    $fetchData =  $dbC->runQuery("SELECT * FROM nexusbond WHERE bondKey = ?;", [$bondKey]);
    $stringBuffer = "";
    $updateBuffer = explode(',', rtrim($fetchData["updateBuffer"], ", "));
    //For some reason stored DS1307 and NTP time use GMT Time Zone
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $fetchData["lastGraphUpdate"], new DateTimeZone('GMT'))->getTimeStamp();
    if ((time() + 25200) - $date >= 60) { // Update graph if 60 second has passed since last graph update
        $stringBuffer = ", lastGraphUpdate='" . gmdate('Y-m-d H:i:s', (time() + 25200)) . "'";
        $dbC->execute("INSERT INTO nexusplot (bondKey,date,timestamp,deviceType,data1,data2) VALUES (?,?,?,?,?,?);", [$bondKey, gmdate("Y-m-d", $json["unix"]), gmdate("H:i:s", $json["unix"]), 'nexus', round($json["data"][0], 2), round($json["data"][1], 2)]);
    }
    // If ESP are not updating the output status then, just update the passed temperature and humidity
    if (
        $json["a1"] != $fetchData["auxStatus1"] ||
        $json["a2"] != $fetchData["auxStatus2"] ||
        $json["a3"] != $fetchData["auxStatus3"] ||
        $json["a4"] != $fetchData["auxStatus4"]
    ) {
        $dbC->execute("UPDATE nexusbond SET espStatusUpdateAvailable='1', auxStatus1=?, auxStatus2=?, auxStatus3=?, auxStatus4=?, tempNow=?, humidNow=?, lastUpdate=?{$stringBuffer} WHERE bondKey = ?;", [
            (!in_array("auxStatus1", $updateBuffer)) ? $json["a1"] : $fetchData["auxStatus1"],
            (!in_array("auxStatus2", $updateBuffer)) ? $json["a2"] : $fetchData["auxStatus2"],
            (!in_array("auxStatus3", $updateBuffer)) ? $json["a3"] : $fetchData["auxStatus3"],
            (!in_array("auxStatus4", $updateBuffer)) ? $json["a4"] : $fetchData["auxStatus4"],
            round($json["data"][0], 2),
            round($json["data"][1], 2),
            gmdate("Y-m-d H:i:s", $json["unix"]),
            $bondKey
        ]);
    } else
        $dbC->execute("UPDATE nexusbond SET tempNow=?, humidNow=?, lastUpdate=?{$stringBuffer} WHERE bondKey = ?;", [round($json["data"][0], 2), round($json["data"][1], 2), gmdate("Y-m-d H:i:s", $json["unix"]), $bondKey]);
    // Send update response if there are updateAvailable from server

    $restartOrFallbackFlag = false;
    $clearUpdateAvailable = false;
    $someKey = true;
    foreach ($updateBuffer as $ckey) { // check if restart or fallback command exist
        if ($ckey == "restart" || $ckey == "fallback") {
            $restartOrFallbackFlag = true;
            $buffer["order"] = ($ckey == "restart") ? "restart" : "fallback";
            $fetchData["updateBuffer"] = str_replace($ckey . ",", "", $fetchData["updateBuffer"]);
            if ($fetchData["updateBuffer"] == "")
                $someKey = false;
            break;
        }
    }

    if ($fetchData["updateAvailable"] == "1" && $restartOrFallbackFlag == false) {
        $buffer = array();
        $buffer["order"] = "setParam";
        $prog = $dbC->runQuery("SELECT * FROM nexusautomation WHERE bondKey = :bondKey;", ["bondKey" => $bondKey], "ALL");
        foreach ($updateBuffer as $key) {
            if ($key == "auxStatus1" || $key == "auxStatus2" || $key == "auxStatus3" || $key == "auxStatus4") {
                $buffer["st"] = "st";
                $buffer[$key] = ($fetchData[$key] == "1") ? 1 : 0;
                $fetchData["updateBuffer"] = str_replace($key . ",", "", $fetchData["updateBuffer"]);
                $someKey = false;
            } else if (substr($key, 0, 1) == "p") {
                if (!isset($buffer["prog"])) $buffer["prog"] = new stdClass();
                $i = (int)filter_var($key, FILTER_SANITIZE_NUMBER_INT) - 1;
                $data = rephraseProgram($prog[$i]);
                for ($w = 0; $w < 4; $w++)
                    $buffer["prog"]->{$i}[$w] = $data[$w];
                $fetchData["updateBuffer"] = str_replace($key . ",", "", $fetchData["updateBuffer"]);
                $someKey = false;
            }
        }
    }
    if ($fetchData["updateAvailable"] == "1" || $restartOrFallbackFlag == true) {
        $dbC->execute("UPDATE nexusbond SET updateAvailable=:someKey,updateBuffer=:updateBuffer WHERE bondKey = :bondKey;", ["bondKey" => $bondKey, "someKey" => $someKey, "updateBuffer" => $fetchData["updateBuffer"]]);
        echo json_encode($buffer);
    }
}

function rephraseProgram($prog)
{
    $data = array(0, 0, 0, 0);
    if ($prog["exist"] == "0")
        return $data;

    if ($prog["progData1"] == "Nilai Suhu")
        $data[0] = 1;
    else if ($prog["progData1"] == "Nilai Humiditas")
        $data[0] = 2;
    else if ($prog["progData1"] == "Jadwal Harian")
        $data[0] = 3;
    else if ($prog["progData1"] == "Tanggal Waktu")
        $data[0] = 4;
    else if ($prog["progData1"] == "Keadaan")
        $data[0] = 5;
    else if ($prog["progData1"] == "Pemanas")
        $data[0] = 6;
    else if ($prog["progData1"] == "Pendingin")
        $data[0] = 7;
    else if ($prog["progData1"] == "Humidifier")
        $data[0] = 8;

    if ($data[0] == 1 || $data[0] == 2) {
        if ($prog["progData3"] == "<")
            $data[1] = 0;
        else if ($prog["progData3"] == ">")
            $data[1] = 1;
        else if ($prog["progData3"] == "<=")
            $data[1] = 2;
        else if ($prog["progData3"] == ">=")
            $data[1] = 3;
        $ar = unpack("L*", pack("f", (float)$prog["progData4"]));
        $data[2] =  $ar[1]; // Get binary of float and convert the binary into int
    } else if ($data[0] == 3) {
        $from = explode(":", $prog["progData3"]);
        $to = explode(":", $prog["progData4"]);
        $data[1] = (($from[0] + 0) * 3600) + (($from[1] + 0) * 60) + $from[2];
        $data[2] = (($to[0] + 0) * 3600) + (($to[1] + 0) * 60) + $to[2];
    } else if ($data[0] == 4) {
        $data[1] = DateTime::createFromFormat('Y-m-d H:i', $prog["progData3"], new DateTimeZone('GMT'))->getTimeStamp();
        $data[2] = DateTime::createFromFormat('Y-m-d H:i', $prog["progData4"], new DateTimeZone('GMT'))->getTimeStamp();
    } else if ($data[0] == 5) {
        $data[1] = intval($prog["progData3"]);
        if ($prog["progData4"] == "Menyala")
            $data[2] = 1;
        else if ($prog["progData4"] == "Mati")
            $data[2] = 2;
    }
    if ($data[0] == 6 || $data[0] == 7 || $data[0] == 8) {
        $convertedHex = unpack("L*", pack("f", (float)$prog["progData3"])); // Atur Ke
        $data[1] =  $convertedHex[1]; // Get binary of float and convert the binary into int
        $convertedHex = unpack("L*", pack("f", (float)$prog["progData4"])); // Toleransi
        $data[2] =  $convertedHex[1]; // Get binary of float and convert the binary into int
        $data[3] = intval($prog["progData2"]);
    } else {
        $prog["progData2"] = explode(" ", $prog["progData2"]);
        if ($prog["progData2"][0] == "1")
            $data[3] = intval($prog["progData2"][1]) + 1;
        else
            $data[3] = intval($prog["progData2"][1]) + 5;
    }
    return $data;
}

// date_default_timezone_set('Asia/Jakarta');
// $nDateFrom = DateTime::createFromFormat('Y-m-d H:i', $dateFrom)->getTimestamp();
