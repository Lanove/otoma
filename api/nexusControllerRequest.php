<?php
if (isset($_SERVER['HTTP_DEVICE_TOKEN']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $json = json_decode(file_get_contents('php://input'), true); // Get JSON Input from AJAX and decode it to PHP Array
    if (isset($json["type"])) {
        $macAddr = $_SERVER['HTTP_ESP8266_MAC'];
        $buildVersion = $_SERVER['HTTP_ESP8266_BUILD_VERSION'];
        $deviceToken = $_SERVER['HTTP_DEVICE_TOKEN'];
        $requestType = $json["type"];
        require "DatabaseController.php";
        $dbController = new DatabaseController();
        $privilegeCheck = $dbController->runQuery("SELECT bondKey FROM bond WHERE deviceToken = :deviceToken;", ["deviceToken" => $deviceToken]);
        if (isset($privilegeCheck["bondKey"])) {
            $bondKey = $privilegeCheck["bondKey"];
            if ($requestType == "tnhns")
                updateTnHnS($bondKey, $json, $dbController);
        } else {
            echo json_encode(["fallback" => true]);
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
        $dbC->runQuery("INSERT INTO nexusplot (bondKey,date,timestamp,deviceType,data1,data2) VALUES (?,?,?,?,?,?);", [$bondKey, gmdate("Y-m-d", $json["unix"]), gmdate("H:i:s", $json["unix"]), 'nexus', round($json["data"][0], 2), round($json["data"][1], 2)]);
    }
    // If ESP are not updating the output status then, just update the passed temperature and humidity
    if (
        $json["a1"] != $fetchData["auxStatus1"] ||
        $json["a2"] != $fetchData["auxStatus2"] ||
        $json["tc"] != $fetchData["thStatus"] ||
        $json["ht"] != $fetchData["htStatus"] ||
        $json["cl"] != $fetchData["clStatus"]
    ) {
        $dbC->runQuery("UPDATE nexusbond SET espStatusUpdateAvailable='1', auxStatus1=?, auxStatus2=?, thStatus=?, htStatus=?, clStatus=?, tempNow=?, humidNow=?, lastUpdate=?{$stringBuffer} WHERE bondKey = ?;", [
            (!in_array("auxStatus1", $updateBuffer)) ? $json["a1"] : $fetchData["auxStatus1"],
            (!in_array("auxStatus2", $updateBuffer)) ? $json["a2"] : $fetchData["auxStatus2"],
            (!in_array("thStatus", $updateBuffer)) ? $json["tc"] : $fetchData["thStatus"],
            (!in_array("htStatus", $updateBuffer)) ? $json["ht"] : $fetchData["htStatus"],
            (!in_array("clStatus", $updateBuffer)) ? $json["cl"] : $fetchData["clStatus"],
            round($json["data"][0], 2),
            round($json["data"][1], 2),
            gmdate("Y-m-d H:i:s", $json["unix"]),
            $bondKey
        ]);
    } else
        $dbC->runQuery("UPDATE nexusbond SET tempNow=?, humidNow=?, lastUpdate=?{$stringBuffer} WHERE bondKey = ?;", [round($json["data"][0], 2), round($json["data"][1], 2), gmdate("Y-m-d H:i:s", $json["unix"]), $bondKey]);
    // Send update response if there are updateAvailable from server
    if ($fetchData["updateAvailable"] == "1") {
        $someKey = true;
        $buffer = array();
        $buffer["order"] = "setParam";
        $prog = $dbC->runQuery("SELECT * FROM nexusautomation WHERE bondKey = :bondKey;", ["bondKey" => $bondKey], "ALL");
        foreach ($updateBuffer as $key) {
            if ($key == "sp") {
                $buffer["setpoint"] = floatval($fetchData["sp"]);
                $fetchData["updateBuffer"] = str_replace($key . ",", "", $fetchData["updateBuffer"]);
                $someKey = false;
            } else if ($key == "coolerMode") {
                $buffer["cmd"] = ($fetchData["coolerMode"] == "pid") ? 0 : 1;
                $fetchData["updateBuffer"] = str_replace($key . ",", "", $fetchData["updateBuffer"]);
                $someKey = false;
            } else if ($key == "coolerPar") {
                $cpar = explode("%", $fetchData["coolerPar"]);
                $cpar[0] = explode("/", $cpar[0]);
                $cpar[1] = explode("/", $cpar[1]);
                $buffer["cpam"][0] = floatval($cpar[0][0]);
                $buffer["cpam"][1] = floatval($cpar[0][1]);
                $buffer["cpam"][2] = floatval($cpar[0][2]);
                $buffer["cpam"][3] = floatval($cpar[0][3]);
                $buffer["cpam"][4] = floatval($cpar[1][0]);
                $buffer["cpam"][5] = floatval($cpar[1][1]);
                $fetchData["updateBuffer"] = str_replace($key . ",", "", $fetchData["updateBuffer"]);
                $someKey = false;
            } else if ($key == "heaterPar") {
                $hpar = explode("%", $fetchData["heaterPar"]);
                $hpar[0] = explode("/", $hpar[0]);
                $hpar[1] = explode("/", $hpar[1]);
                $buffer["hpam"][0] = floatval($hpar[0][0]);
                $buffer["hpam"][1] = floatval($hpar[0][1]);
                $buffer["hpam"][2] = floatval($hpar[0][2]);
                $buffer["hpam"][3] = floatval($hpar[0][3]);
                $buffer["hpam"][4] = floatval($hpar[1][0]);
                $buffer["hpam"][5] = floatval($hpar[1][1]);
                $fetchData["updateBuffer"] = str_replace($key . ",", "", $fetchData["updateBuffer"]);
                $someKey = false;
            } else if ($key == "heaterMode") {
                $buffer["hmd"] = ($fetchData["heaterMode"] == "pid") ? 0 : 1;
                $fetchData["updateBuffer"] = str_replace($key . ",", "", $fetchData["updateBuffer"]);
                $someKey = false;
            } else if ($key == "thercoInfo") {
                $tcInfo = explode("%", $fetchData["thercoInfo"]);
                $buffer["tcm"][0] = ($tcInfo[0] == "manual") ? 0 : 1;
                if ($tcInfo[1] == "heat") {
                    $buffer["tcm"][1] = 0;
                    $buffer["tcm"][2] = 0;
                } else if ($tcInfo[1] == "cool") {
                    $buffer["tcm"][1] = 1;
                    $buffer["tcm"][2] = 0;
                } else if ($tcInfo[1] == "dual") {
                    $buffer["tcm"][1] = 1;
                    $buffer["tcm"][2] = 1;
                }
                $fetchData["updateBuffer"] = str_replace($key . ",", "", $fetchData["updateBuffer"]);
                $someKey = false;
            } else if ($key == "auxStatus1" || $key == "auxStatus2" || $key == "thStatus" || $key == "clStatus" || $key == "htStatus") {
                $buffer["st"] = "st";
                $buffer[$key] = ($fetchData[$key] == "1") ? 1 : 0;
                $fetchData["updateBuffer"] = str_replace($key . ",", "", $fetchData["updateBuffer"]);
                $someKey = false;
            } else if (substr($key, 0, 1) == "p") {
                if (!isset($buffer["prog"])) $buffer["prog"] = new stdClass();
                $i = (int)filter_var($key, FILTER_SANITIZE_NUMBER_INT) - 1;
                $data = rephraseProgram($prog[$i]);
                $buffer["prog"]->{$i}[0] = $data[0];
                $buffer["prog"]->{$i}[1] = $data[1];
                $buffer["prog"]->{$i}[2] = $data[2];
                $buffer["prog"]->{$i}[3] = $data[3];
                $fetchData["updateBuffer"] = str_replace($key . ",", "", $fetchData["updateBuffer"]);
                $someKey = false;
            }
        }
        $dbC->runQuery("UPDATE nexusbond SET updateAvailable=:someKey,updateBuffer=:updateBuffer WHERE bondKey = :bondKey;", ["bondKey" => $bondKey, "someKey" => $someKey, "updateBuffer" => $fetchData["updateBuffer"]]);
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
        if ($prog["progData3"] == "Output 1")
            $data[1] = 1;
        else if ($prog["progData3"] == "Output 2")
            $data[1] = 2;
        else if ($prog["progData3"] == "Pemanas")
            $data[1] = 3;
        else if ($prog["progData3"] == "Pendingin")
            $data[1] = 4;
        else if ($prog["progData3"] == "Thermocontrol")
            $data[1] = 5;
        if ($prog["progData4"] == "Menyala")
            $data[2] = 1;
        else if ($prog["progData4"] == "Mati")
            $data[2] = 2;
    }
    if ($prog["progData2"] == "Nyalakan Output 1")
        $data[3] = 1;
    else if ($prog["progData2"] == "Nyalakan Output 2")
        $data[3] = 2;
    else if ($prog["progData2"] == "Nyalakan Pemanas")
        $data[3] = 3;
    else if ($prog["progData2"] == "Nyalakan Pendingin")
        $data[3] = 4;
    else if ($prog["progData2"] == "Nyalakan Thermocontrol")
        $data[3] = 5;
    else if ($prog["progData2"] == "Matikan Output 1")
        $data[3] = 6;
    else if ($prog["progData2"] == "Matikan Output 2")
        $data[3] = 7;
    else if ($prog["progData2"] == "Matikan Pemanas")
        $data[3] = 8;
    else if ($prog["progData2"] == "Matikan Pendingin")
        $data[3] = 9;
    else if ($prog["progData2"] == "Matikan Thermocontrol")
        $data[3] = 10;
    return $data;
}

// date_default_timezone_set('Asia/Jakarta');
// $nDateFrom = DateTime::createFromFormat('Y-m-d H:i', $dateFrom)->getTimestamp();
