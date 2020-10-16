<?php
if (isset($_SERVER['HTTP_DEVICE_TOKEN'])) {
    $json = json_decode(file_get_contents('php://input'), true); // Get JSON Input from AJAX and decode it to PHP Array
    if (isset($json["type"])) {
        $deviceToken = $_SERVER['HTTP_DEVICE_TOKEN'];
        $requestType = $json["type"];
        require "DatabaseController.php";
        $dbController = new DatabaseController();
        $privilegeCheck = $dbController->runQuery("SELECT bondKey FROM bond WHERE deviceToken = :deviceToken;", ["deviceToken" => $deviceToken]);
        if (isset($privilegeCheck["bondKey"])) {
            $bondKey = $privilegeCheck["bondKey"];
            if ($requestType == "tnhns")
                updateTnHnS($bondKey, $json, $dbController);
        }
    }
}

function updateTnHnS($bondKey, $json, $dbC)
{
    $fetchData =  $dbC->runQuery("SELECT * FROM nexusbond WHERE bondKey = ?;", [$bondKey]);
    $stringBuffer = "";
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $fetchData["lastGraphUpdate"], new DateTimeZone('GMT'))->getTimeStamp();
    echo $json["data"][0];
    if ((time() + 25200) - $date >= 60) {
        $stringBuffer = ", lastGraphUpdate='" . gmdate('Y-m-d H:i:s', (time() + 25200)) . "'";
        $dbC->runQuery("INSERT INTO nexusplot (bondKey,date,timestamp,deviceType,data1,data2) VALUES (?,?,?,?,?,?);", [$bondKey, gmdate("Y-m-d", $json["unix"]), gmdate("H:i:s", $json["unix"]), 'nexus', round($json["data"][0], 2), round($json["data"][1], 2)]);
    }
    if (
        $json["data"][2] != $fetchData["auxStatus1"] &&
        $json["data"][3] != $fetchData["auxStatus2"] &&
        $json["data"][4] != $fetchData["thStatus"] &&
        $json["data"][5] != $fetchData["htStatus"] &&
        $json["data"][6] != $fetchData["clStatus"]
    )
        $dbC->runQuery("UPDATE nexusbond SET auxStatus1=?, auxStatus2=?, thStatus=?, htStatus=?, clStatus=?, tempNow=?, humidNow=?, lastUpdate=?{$stringBuffer} WHERE bondKey = ?;", [$json["data"][2], $json["data"][3], $json["data"][4], $json["data"][5], $json["data"][6], round($json["data"][0], 2), round($json["data"][1], 2), gmdate("Y-m-d H:i:s", $json["unix"]), $bondKey]);
    else
        $dbC->runQuery("UPDATE nexusbond SET tempNow=?, humidNow=?, lastUpdate=?{$stringBuffer} WHERE bondKey = ?;", [round($json["data"][0], 2), round($json["data"][1], 2), gmdate("Y-m-d H:i:s", $json["unix"]), $bondKey]);
}

// date_default_timezone_set('Asia/Jakarta');
// $nDateFrom = DateTime::createFromFormat('Y-m-d H:i', $dateFrom)->getTimestamp();
