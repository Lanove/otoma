<?php
require "originandreferer.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if Request Method used is POST
    if (isset($_SERVER['HTTP_ORIGIN'])) { // Check if HTTP Origin is set.
        $address = 'http://' . $origin;
        if (strpos($address, $_SERVER['HTTP_ORIGIN']) !== 0) { // Check if HTTP Origin is valid
            exit();
        }
    } else {
        if ($_SERVER['HTTP_REFERER'] !== $dashboardReferer) { // If Origin is not set, then check the referer
            exit();
        }
    }
    session_start();
    $json = json_decode(file_get_contents('php://input'), true); // Get JSON Input from AJAX and Encode it to PHP Array
    if (!empty($json["token"])) {
        require "CryptographyFunction.php";
        // Decrypt token passed by AJAX
        $decryptedMsg = decryptAes($aesKey, $json['token']);
        $decryptedMsg .= "%";
        $explodeDecode = explode('%', $decryptedMsg, -1); // Explode decrypted data to extract it's content
        $requestType = $json["requestType"]; // Get requestType from POST'ed JSON by AJAX
        $ajaxToken = base64_decode($explodeDecode[0]); // Get session token
        $username = base64_decode($explodeDecode[1]); // Get username
        $isLoggedIn = base64_decode($explodeDecode[2]); // Get isLoggedIn value
        ///////////////////////////////////////////////////////////////////////
        if (hash_equals($_SESSION['ajaxToken'], $ajaxToken)) {

            require "DatabaseController.php";
            $dbHandler = new DatabaseController(); // Open database
            if ($requestType === "loadDeviceInformation") { // Request is first page load
                // Get every name of device including masterName and deviceName
                $fetchResult["main"] = $dbHandler->runQuery("SELECT deviceType,bondKey,masterName,deviceName1,deviceName2,deviceName3,deviceName4 FROM bond WHERE username = :name ORDER BY id ASC;", ["name" => $username]);
                // Get every name of masterDevice with fetchAll
                $masterNameList = $dbHandler->runQuery("SELECT masterName FROM bond WHERE username = :name AND masterName != :exception ORDER BY id ASC;", ["name" => $username, "exception" => $fetchResult["main"]["masterName"]], "ALL");
                // Get daily plot data if it exist.
                $fetchResult["plot"] = $dbHandler->runQuery("SELECT plotStamp,timeStamp,deviceType,data1 FROM dailyplot WHERE bondKey = :bondkey;", ["bondkey" => $fetchResult["main"]["bondKey"]], "ALL");
                // Merge array
                $mergeResult = array_merge($masterNameList, $fetchResult);
                // Return JSON array
                echo json_encode($mergeResult);
            } else if ($requestType === "changeStatus") {
                $bondKey = $json["masterDevice"];
                $status = $json["status"];
                $id = $json["id"];
                $fetchResult = $dbHandler->runQuery("SELECT bondKey FROM status WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
                // Check whether status column of the device and user has been created
                if ($fetchResult) { // If exist
                    $columnData = "";
                    // Convert button id into column data of database
                    switch ($id) {
                        case "statusBoxSwitch1":
                            $columnData = "data1";
                            break;
                        case "statusBoxSwitch2":
                            $columnData = "data2";
                            break;
                        case "statusBoxSwitch3":
                            $columnData = "data3";
                            break;
                        case "statusBoxSwitch4":
                            $columnData = "data4";
                            break;
                    }
                    // Convert 4 byte boolean value to 1 byte boolean value
                    if ($status == "true")
                        $status = "1";
                    else
                        $status = "0";
                    // Update the status of database
                    $dbHandler->runQuery("UPDATE status SET {$columnData}='{$status}' WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
                } else { // If not exist
                    // Setup status table for bond
                    $fetchResult = $dbHandler->runQuery("SELECT deviceType FROM bond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]); // Get deviceType from bond table
                    $deviceType = $fetchResult["deviceType"];
                    $dbHandler->runQuery("INSERT INTO status(bondKey,deviceType) VALUES (?, ?)", [$bondKey, $deviceType]);
                    /////////////////////////
                }
            } else if ($requestType === "reloadStatus") {
                $bondKey = $json["masterDevice"];
                // Fetch row from status table
                $fetchResult["status"] = $dbHandler->runQuery("SELECT deviceType,data1,data2,data3,data4,data5,data6,data7,data8 FROM status WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
                // Merge array
                $mergeResult = array_merge($fetchResult);
                echo json_encode($mergeResult);
            }
            $dbHandler->close(); // Close database
        }
    }
}
