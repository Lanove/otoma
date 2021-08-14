<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if Request Method used is POST
    require "DatabaseController.php";
    $dbController = new DatabaseController();
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $json = json_decode(file_get_contents('php://input'), true); // Get JSON Input from AJAX and decode it to PHP Array
    if (isset($json["token"]) &&  isset($json["requestType"]) && isset($_SESSION['ajaxToken'])) {
        require "CryptographyFunction.php";
        // Decrypt token passed by AJAX
        $decryptedMsg = decryptAes($aesKey, $json['token']);
        $decryptedMsg .= "%";
        $explodeDecode = explode('%', $decryptedMsg, -1); // Explode decrypted data to extract it's content
        $requestType = $json["requestType"]; // Get requestType from POST'ed JSON by AJAX
        $ajaxToken = base64_decode($explodeDecode[0]); // Get session token
        $username = base64_decode($explodeDecode[1]); // Get username
        $isLoggedIn = base64_decode($explodeDecode[2]); // Get isLoggedIn value
        $json["username"] = $username;
        ///////////////////////////////////////////////////////////////////////
        if (hash_equals($_SESSION['ajaxToken'], $ajaxToken) && $username === $_SESSION["username"]) {
            if ($requestType === "loadDeviceInformation") { // Request is first page load
                loadDeviceInformation($json, $dbController);
            } else { // bondKey related request is taken with privilege check.
                if (isset($json["bondKey"])) {
                    // Check if user actually had a bond with bondKey holder
                    $privilegeCheck = $dbController->runQuery("SELECT username FROM bond WHERE bondKey = :bondkey;", ["bondkey" => $json["bondKey"]]);
                    if ($privilegeCheck["username"] === $json["username"]) {
                        if (($requestType === "loadPlot")) {
                            loadPlot($json, $dbController);
                        } else if ($requestType === "toggleSwitch") {
                            toggleSwitch($json, $dbController);
                        } else if ($requestType === "updateProgram") {
                            updateProgram($json, $dbController);
                        } else if ($requestType === "deleteProgram") {
                            deleteProgram($json, $dbController);
                        } else if ($requestType == "reloadStatus") {
                            requestStatus($json, $dbController);
                        } else if ($requestType == "loadSetting") {
                            loadSetting($json, $dbController);
                        } else if ($requestType == "updateName") {
                            updateName($json, $dbController);
                        } else if ($requestType == "deleteController") {
                            deleteController($json, $dbController);
                        } else if ($requestType == "controllerCommand") {
                            controllerCommand($json, $dbController);
                        }
                    }
                }
            }
        }
    }
    $dbController->close();
    $dbController = null;
} else header('HTTP/1.1 403 Forbidden');

function controllerCommand($arg, $dbC)
{
    $bondKey =  $arg["bondKey"];
    $cmd = $arg["command"];
    $fetchResult = $dbC->runQuery("SELECT updateBuffer FROM nexusbond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
    $fetchResult["updateBuffer"] .= $cmd . ",";
    $dbC->execute("UPDATE nexusbond SET updateAvailable='1',updateBuffer=:updateBuffer WHERE bondKey = :bondKey;", ["bondKey" => $bondKey, "updateBuffer" => $fetchResult["updateBuffer"]]);
}

function deleteController($arg, $dbC)
{
    $bondKey =  $arg["bondKey"];
    $dbC->execute("DELETE FROM bond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);

    $doubleCheck = $dbC->runQuery("SELECT * FROM bond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
    if ($doubleCheck)
        echo false;
    else
        echo true;
}

function updateName($arg, $dbC)
{
    $bondKey = $arg["bondKey"];
    if (isset($arg["docchi"]) && isset($arg["name"])) {
        if ($arg["docchi"] == "master") {
            $arg["name"] = filter_var($arg["name"], FILTER_SANITIZE_STRING);
            $validFlag = true;
            $fetchResult = $dbC->runQuery("SELECT masterName FROM bond WHERE username = :username;", ["username" => $arg["username"]], "ALL");
            foreach ($fetchResult as $k) {
                if (strtolower($k["masterName"]) === strtolower($arg["name"])) $validFlag = false;
            }
            if ($validFlag)
                $dbC->execute("UPDATE bond SET masterName=:masterName WHERE bondKey = :bondKey;", ["bondKey" => $bondKey, "masterName" => $arg["name"]]);
            echo json_encode(["duplicate" => !$validFlag]);
        } else if ($arg["docchi"] == "aux" && isset($arg["name"][0]) && isset($arg["name"][1]) && isset($arg["name"][2]) && isset($arg["name"][3])) {
            $arg["name"][0] = filter_var($arg["name"][0], FILTER_SANITIZE_STRING);
            $arg["name"][1] = filter_var($arg["name"][1], FILTER_SANITIZE_STRING);
            $arg["name"][2] = filter_var($arg["name"][2], FILTER_SANITIZE_STRING);
            $arg["name"][3] = filter_var($arg["name"][3], FILTER_SANITIZE_STRING);
            $dbC->execute("UPDATE nexusbond SET auxName1=:auxName1, auxName2=:auxName2, auxName3=:auxName3, auxName4=:auxName4 WHERE bondKey = :bondKey;", ["bondKey" => $bondKey, "auxName1" => $arg["name"][0], "auxName2" => $arg["name"][1], "auxName3" => $arg["name"][2], "auxName4" => $arg["name"][3]]);
        }
    }
}

function loadSetting($arg, $dbC)
{
    $bondKey = $arg["bondKey"];
    echo json_encode(array_merge($dbC->runQuery("SELECT masterName,softSSID,softPass,softIP,MAC,softwareVersion FROM bond WHERE bondKey = :bondkey;", ["bondkey" => $bondKey]), $dbC->runQuery("SELECT auxName1, auxName2, auxName3, auxName4 FROM nexusbond WHERE bondKey = :bondkey;", ["bondkey" => $bondKey])));
}

function requestStatus($arg, $dbC)
{
    $bondKey = $arg["bondKey"];
    $fetchResult["status"] = $dbC->runQuery("SELECT auxStatus1, auxStatus2, auxStatus3,auxStatus4, tempNow, humidNow, espStatusUpdateAvailable FROM nexusbond WHERE bondKey = :bondkey;", ["bondkey" => $bondKey]);
    if ($fetchResult["status"]["espStatusUpdateAvailable"] == 1) {
        $dbC->execute("UPDATE nexusbond SET espStatusUpdateAvailable='0' WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
    }
    echo json_encode($fetchResult);
}

function deleteProgram($arg, $dbC)
{
    $response = array("success" => false);
    if (isset($arg["passedData"]["progNum"])) {
        $progNum = $arg["passedData"]["progNum"];
        $bondKey = $arg["bondKey"];
        $isCreated = $dbC->runQuery("SELECT * FROM nexusautomation WHERE bondKey = :bondkey LIMIT 1;", ["bondkey" => $bondKey]);
        if (!$isCreated) {
            $stringBuffer = "";
            for ($i = 1; $i < 31; $i++) {
                $stringBuffer .= "('" . $bondKey . "','" . $i . "','nexus')";
                if ($i != 30) $stringBuffer .= ",";
            }
            $dbC->execute("INSERT INTO nexusautomation (bondKey,progNumber,deviceType)
            VALUES ${stringBuffer};", []);
            $response["success"] = true;
        } else {
            if ($progNum > 0 && $progNum < 31) {
                $fetchResult = $dbC->runQuery("SELECT updateBuffer FROM nexusbond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
                if (!(strpos($fetchResult["updateBuffer"], ("p" . $progNum)) !== false)) {
                    $fetchResult["updateBuffer"] .= "p" . $progNum . ",";
                }
                $dbC->execute("UPDATE nexusbond SET updateAvailable='1',updateBuffer=:updateBuffer WHERE bondKey = :bondKey;", ["bondKey" => $bondKey, "updateBuffer" => $fetchResult["updateBuffer"]]);
                $dbC->execute("UPDATE nexusautomation SET exist='0', progData1='', progData2='', progData3='', progData4='', progData5='', progData6='', progData7='', progData8='' WHERE bondKey = :bondKey AND progNumber = :progNum;", ["bondKey" => $bondKey, "progNum" => $progNum]);
                $doubleCheck = $dbC->runQuery("SELECT * FROM nexusautomation WHERE bondKey = :bondKey AND progNumber = :progNumber;", ["bondKey" => $bondKey, "progNumber" => $progNum]);
                if (
                    $doubleCheck["progData1"] == '' &&
                    $doubleCheck["progData2"] == '' &&
                    $doubleCheck["progData3"] == '' &&
                    $doubleCheck["progData4"] == '' &&
                    $doubleCheck["progData5"] == '' &&
                    $doubleCheck["progData6"] == '' &&
                    $doubleCheck["progData7"] == '' &&
                    $doubleCheck["progData8"] == '' &&
                    $doubleCheck["progData9"] == '' &&
                    $doubleCheck["progData10"] == '' &&
                    $doubleCheck["exist"] == '0'
                )
                    $response["success"] = true;
            }
        }
    }
    echo json_encode($response);
}

function updateProgram($arg, $dbC) // Be careful, within this function there are eye-burning-ifs-conditions
{
    $response = array("success" => false);
    if (
        isset($arg["passedData"]["trCd"]) &&
        isset($arg["passedData"]["acCd"]) &&
        isset($arg["passedData"]["progNum"])
    ) {
        $bondKey = $arg["bondKey"];
        $trigger = $arg["passedData"]["trCd"];
        $action = $arg["passedData"]["acCd"];
        $progNum = (int)$arg["passedData"]["progNum"];
        $validAction = array(
            "0 0",
            "0 1",
            "0 2",
            "0 3",
            "1 0",
            "1 1",
            "1 2",
            "1 3",
            "0",
            "1",
            "2",
            "3"
        );
        $validFlag = false;
        foreach ($validAction as $k) {
            if (
                $k == $action && $progNum >= 1 &&
                $progNum <= 30
            ) $validFlag = true;
        }
        $isCreated = $dbC->runQuery("SELECT * FROM nexusautomation WHERE bondKey = :bondkey LIMIT 1;", ["bondkey" => $bondKey]);
        if (!$isCreated) {
            $stringBuffer = "";
            for ($i = 1; $i < 31; $i++) {
                $stringBuffer .= "('" . $bondKey . "','" . $i . "','nexus')";
                if ($i != 30) $stringBuffer .= ",";
            }
            $dbC->execute("INSERT INTO nexusautomation (bondKey,progNumber,deviceType)
            VALUES ${stringBuffer};", []);
        }
        if ($validFlag) {
            $fetchResult = $dbC->runQuery("SELECT updateBuffer FROM nexusbond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
            if (!(strpos($fetchResult["updateBuffer"], ("p" . $progNum)) !== false)) {
                $fetchResult["updateBuffer"] .= "p" . $progNum . ",";
            }
            if (
                $trigger == "Nilai Suhu" ||
                $trigger == "Nilai Humiditas"
            ) {
                if (
                    isset($arg["passedData"]["nscmpCd"]) &&
                    isset($arg["passedData"]["nsvalCd"])
                ) {
                    $validComparator = array("<", ">", "<=", ">=");
                    $nscmp = $arg["passedData"]["nscmpCd"];
                    $nsval = (float)$arg["passedData"]["nsvalCd"];
                    $validFlag = false;
                    foreach ($validComparator as $k) {
                        if ($k == $nscmp) $validFlag = true;
                    }
                    if ($nsval >= 0 && $nsval <= 100 && $validFlag) {
                        $dbC->execute("UPDATE nexusautomation SET exist='1',   progData1=:trigger, progData2=:action, progData3=:nscmp, progData4=:nsval, progData5='', progData6='', progData7='', progData8='' WHERE bondKey = :bondKey AND progNumber = :progNum;", ["trigger" => $trigger, "action" => $action, "nscmp" => $nscmp, "nsval" => $nsval, "bondKey" => $bondKey, "progNum" => $progNum]);
                        $doubleCheck = $dbC->runQuery("SELECT * FROM nexusautomation WHERE bondKey = :bondKey AND progNumber = :progNumber;", ["bondKey" => $bondKey, "progNumber" => $progNum]);
                        if (
                            $doubleCheck["progData1"] == $trigger &&
                            $doubleCheck["progData2"] == $action &&
                            $doubleCheck["progData3"] == $nscmp &&
                            $doubleCheck["progData4"] == $nsval &&
                            $doubleCheck["exist"] == '1'
                        )
                            $response["success"] = true;
                    }
                }
            } else if ($trigger == "Jadwal Harian") {
                if (
                    isset($arg["passedData"]["faCd"]) &&
                    isset($arg["passedData"]["feCd"])
                ) {
                    $timeFrom = $arg["passedData"]["faCd"];
                    $timeTo = $arg["passedData"]["feCd"];
                    $sTimeFrom = explode(":", $timeFrom);
                    $sTimeTo = explode(":", $timeTo);
                    if (
                        isset($sTimeFrom[0]) &&
                        isset($sTimeFrom[1]) &&
                        isset($sTimeFrom[2]) &&
                        isset($sTimeTo[0]) &&
                        isset($sTimeTo[1]) &&
                        isset($sTimeTo[2])
                    ) {
                        $tTimeFrom = ($sTimeFrom[0] * 3600) + ($sTimeFrom[1] * 60) + $sTimeFrom[2];
                        $tTimeTo = ($sTimeTo[0] * 3600) + ($sTimeTo[1] * 60) + $sTimeTo[2];
                        if (
                            $sTimeFrom[0] >= 0 && $sTimeFrom[0] <= 23 &&
                            $sTimeFrom[1] >= 0 && $sTimeFrom[1] <= 59 &&
                            $sTimeFrom[2] >= 0 && $sTimeFrom[2] <= 59 &&
                            $sTimeTo[0] >= 0 && $sTimeTo[0] <= 23 &&
                            $sTimeTo[1] >= 0 && $sTimeTo[1] <= 59 &&
                            $sTimeTo[2] >= 0 && $sTimeTo[2] <= 59 &&
                            $tTimeFrom < $tTimeTo
                        ) {
                            $dbC->execute("UPDATE nexusautomation SET exist='1',   progData1=:trigger, progData2=:action, progData3=:timeFrom, progData4=:timeTo, progData5='', progData6='', progData7='', progData8='' WHERE bondKey = :bondKey AND progNumber = :progNum;", ["trigger" => $trigger, "action" => $action, "timeFrom" => $timeFrom, "timeTo" => $timeTo, "bondKey" => $bondKey, "progNum" => $progNum]);

                            $doubleCheck = $dbC->runQuery("SELECT * FROM nexusautomation WHERE bondKey = :bondKey AND progNumber = :progNumber;", ["bondKey" => $bondKey, "progNumber" => $progNum]);

                            if (
                                $doubleCheck["progData1"] == $trigger &&
                                $doubleCheck["progData2"] == $action &&
                                $doubleCheck["progData3"] == $timeFrom &&
                                $doubleCheck["progData4"] == $timeTo &&
                                $doubleCheck["exist"] == '1'
                            )
                                $response["success"] = true;
                        }
                    }
                }
            } else if ($trigger == "Tanggal Waktu") {
                if (
                    isset($arg["passedData"]["dfaCd"]) &&
                    isset($arg["passedData"]["dfeCd"])
                ) {
                    $dateFrom = $arg["passedData"]["dfaCd"];
                    $dateTo = $arg["passedData"]["dfeCd"];
                    date_default_timezone_set('Asia/Jakarta');
                    $nDateFrom = DateTime::createFromFormat('Y-m-d H:i', $dateFrom)->getTimestamp();
                    $nDateTo = DateTime::createFromFormat('Y-m-d H:i', $dateTo)->getTimestamp();
                    $now = new DateTime();
                    if (
                        $nDateFrom < $nDateTo &&
                        $nDateTo > $now->getTimestamp() &&
                        $nDateFrom >= 1577811600 && $nDateTo >= 1577811600 &&
                        $nDateFrom <= 1767200340 && $nDateTo <= 1767200340
                    ) {
                        $dbC->execute("UPDATE nexusautomation SET exist='1',   progData1=:trigger, progData2=:action, progData3=:dateFrom, progData4=:dateTo, progData5='', progData6='', progData7='', progData8='' WHERE bondKey = :bondKey AND progNumber = :progNum;", ["trigger" => $trigger, "action" => $action, "dateFrom" => $dateFrom, "dateTo" => $dateTo, "bondKey" => $bondKey, "progNum" => $progNum]);

                        $doubleCheck = $dbC->runQuery("SELECT * FROM nexusautomation WHERE bondKey = :bondKey AND progNumber = :progNumber;", ["bondKey" => $bondKey, "progNumber" => $progNum]);

                        if ($doubleCheck["progData1"] == $trigger && $doubleCheck["progData2"] == $action && $doubleCheck["progData3"] == $dateFrom && $doubleCheck["progData4"] == $dateTo && $doubleCheck["exist"] == '1')
                            $response["success"] = true;
                    }
                }
            } else if ($trigger == "Timer") {
                if (isset($arg["passedData"]["timerCd"])) {
                    $timerCd = $arg["passedData"]["timerCd"];
                    $sTimerCd = null;
                    preg_match_all("/\d+/", $timerCd, $sTimerCd);
                    if (
                        isset($sTimerCd[0][0]) &&
                        isset($sTimerCd[0][1]) &&
                        isset($sTimerCd[0][2])
                    ) {
                        if (
                            $sTimerCd[0][0] <= 30 && $sTimerCd[0][0] >= 0 &&
                            $sTimerCd[0][1] <= 23 && $sTimerCd[0][1] >= 0 &&
                            $sTimerCd[0][2] <= 59 && $sTimerCd[0][2] >= 0
                        ) {

                            $dbC->execute("UPDATE nexusautomation SET exist='1',   progData1=:trigger, progData2=:action, progData3=:timerCd, progData4='', progData5='', progData6='', progData7='', progData8='', WHERE bondKey = :bondKey AND progNumber = :progNum;", ["trigger" => $trigger, "action" => $action, "timerCd" => $timerCd, "bondKey" => $bondKey, "progNum" => $progNum]);

                            $doubleCheck = $dbC->runQuery("SELECT * FROM nexusautomation WHERE bondKey = :bondKey AND progNumber = :progNumber;", ["bondKey" => $bondKey, "progNumber" => $progNum]);

                            if ($doubleCheck["progData1"] == $trigger && $doubleCheck["progData2"] == $action && $doubleCheck["progData3"] == $timerCd && $doubleCheck["exist"] == '1')
                                $response["success"] = true;
                        }
                    }
                }
            } else if ($trigger == "Keadaan") {
                if (isset($arg["passedData"]["cndCd"]) && isset($arg["passedData"]["cnddCd"])) {
                    $component = $arg["passedData"]["cndCd"];
                    $condition = $arg["passedData"]["cnddCd"];
                    $bondKey = $arg["bondKey"];
                    if (intval($component) >= 0 && intval($component) <= 3 &&($condition == "Menyala" || $condition == "Mati")) {
                        $dbC->execute("UPDATE nexusautomation SET exist='1', progData1=:trigger, progData2=:action, progData3=:component, progData4=:condition, progData5='', progData6='', progData7='', progData8='' WHERE bondKey = :bondKey AND progNumber = :progNum;", ["trigger" => $trigger, "action" => $action, "component" => $component, "condition" => $condition, "bondKey" => $bondKey, "progNum" => $progNum]);
                        $doubleCheck = $dbC->runQuery("SELECT * FROM nexusautomation WHERE bondKey = :bondKey AND progNumber = :progNumber;", ["bondKey" => $bondKey, "progNumber" => $progNum]);
                        if ($doubleCheck["progData1"] == $trigger && $doubleCheck["progData2"] == $action && $doubleCheck["progData3"] == $component && $doubleCheck["progData4"] == $condition && $doubleCheck["exist"] == '1')
                            $response["success"] = true;
                    }
                }
            } else if ($trigger == "Pemanas" || $trigger == "Pendingin" || $trigger == "Humidifier"){
                if(isset($arg["passedData"]["aturKe"]) && isset($arg["passedData"]["toleransi"])){
                    $aturKe = (float)$arg["passedData"]["aturKe"];
                    $toleransi = (float)$arg["passedData"]["toleransi"];
                    if(!is_nan($aturKe) && !is_nan($toleransi) && $toleransi >= 0 && $toleransi <= 100.9 && $aturKe >= 0 && $aturKe <= 100.9){
                        if($toleransi == 0.0) $toleransi = 0.1;
                        if($aturKe == 0.0) $aturKe = 0.1;
                        $dbC->execute("UPDATE nexusautomation SET exist='1', progData1=:trigger, progData2=:action, progData3=:aturKe, progData4=:toleransi, progData5='', progData6='', progData7='', progData8='' WHERE bondKey = :bondKey AND progNumber = :progNum;", ["trigger" => $trigger, "action" => $action, "toleransi" => $toleransi, "aturKe" => $aturKe, "bondKey" => $bondKey, "progNum" => $progNum]);
                        $doubleCheck = $dbC->runQuery("SELECT * FROM nexusautomation WHERE bondKey = :bondKey AND progNumber = :progNumber;", ["bondKey" => $bondKey, "progNumber" => $progNum]);
                        if (
                            $doubleCheck["progData1"] == $trigger &&
                            $doubleCheck["progData2"] == $action &&
                            $doubleCheck["progData3"] == $aturKe &&
                            $doubleCheck["progData4"] == $toleransi &&
                            $doubleCheck["exist"] == '1'
                        )
                            $response["success"] = true;
                    }
                }
            }
        }
    }
    if (isset($response["success"])) {
        if ($response["success"] == true)
            $dbC->execute("UPDATE nexusbond SET updateAvailable='1',updateBuffer=:updateBuffer WHERE bondKey = :bondKey;", ["bondKey" => $bondKey, "updateBuffer" => $fetchResult["updateBuffer"]]);
    }
    echo json_encode($response);
}

function toggleSwitch($arg, $dbC)
{
    if (isset($arg["id"]) &&  isset($arg["status"])) {
        $bondKey = $arg["bondKey"];
        $id = $arg["id"];
        $status = $arg["status"];
        $status = ($status === 'true') ? $status = "1" : $status = "0";
        $columnData = "auxStatus1";
        // THERE SHOULD BE SOME SERVER FILTER IF SOME CONDITIONAL WERE REFERRED TO SOME SWITCH
        if ($id === "aux2Switch")
            $columnData = "auxStatus2";
        else if ($id === "aux3Switch")
            $columnData = "auxStatus3";
        else if ($id === "aux4Switch")
            $columnData = "auxStatus4";
        $fetchResult = $dbC->runQuery("SELECT updateBuffer FROM nexusbond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
        if (!(strpos($fetchResult["updateBuffer"], $columnData) !== false)) {
            $fetchResult["updateBuffer"] .= $columnData . ",";
        }
        $dbC->execute("UPDATE nexusbond SET {$columnData}='{$status}', updateAvailable='1', updateBuffer=:updateBuffer WHERE bondKey = :bondKey;", ["bondKey" => $bondKey, "updateBuffer" => $fetchResult["updateBuffer"]]);
    }
}
function loadPlot($arg, $dbC)
{
    if (isset($arg["date"])) {
        $bondKey = $arg["bondKey"];
        $date = $arg["date"];
        $dateCheck = explode("-", $date);
        // A long ass if statement to check if the date is correctly formatted
        if (
            isset($dateCheck[0]) &&
            isset($dateCheck[1]) &&
            isset($dateCheck[2])
        ) {
            if (
                is_numeric($dateCheck[0]) &&
                is_numeric($dateCheck[1]) &&
                is_numeric($dateCheck[2]) &&
                $dateCheck[0] > 1970 && $dateCheck[0] < 2100 &&
                $dateCheck[1] > 0 && $dateCheck[1] < 13 &&
                $dateCheck[2] > 0 && $dateCheck[2] < 33
            ) {

                $fetchResult["plot"]["available"] = $dbC->runQuery("SELECT * FROM nexusplot WHERE bondKey = :bondkey AND date = :date LIMIT 1;", ["bondkey" => $bondKey, "date" => $date]);
                $fetchResult["plot"]["plotDate"] = $date; // Refer to availability of plot of current date
                if ($fetchResult["plot"]["available"]) {
                    $fetchResult["plot"]["available"] = true;
                    $fetchResult["plot"]["data"] = $dbC->runQuery("SELECT * FROM nexusplot WHERE bondKey = :bondkey AND date = :date;", ["bondkey" => $bondKey, "date" => $date], "ALL"); // Fetch all data from date
                } else {
                    $fetchResult["plot"]["available"] = false;
                }
                echo json_encode($fetchResult);
            }
        }
    }
}

function loadDeviceInformation($arg, $dbC)
{
    if (isset($arg["master"]) || isset($arg["bondKey"])) {
        $todayDate = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $username = $arg["username"];
        if (isset($arg["master"])) {
            $master = $arg["master"];
            if ($master === "master") {
                $fetchResult["deviceInfo"] = $dbC->runQuery("SELECT bondKey,masterName FROM bond WHERE username = :name ORDER BY id ASC;", ["name" => $username]);
            } else { // THIS ELSE IF STATEMENT MEAN THAT ONE USER CAN'T NAME DEVICE WITH SAME NAME.
                $fetchResult["deviceInfo"] = $dbC->runQuery("SELECT bondKey,masterName FROM bond WHERE username = :name AND masterName = :master;", ["name" => $username, "master" => $master]);
            }
            $bondKey = $fetchResult["deviceInfo"]["bondKey"];
        } else if (isset($arg["bondKey"])) {
            $bondKey = $arg["bondKey"];
            $fetchResult["deviceInfo"] = $dbC->runQuery("SELECT bondKey,masterName FROM bond WHERE bondKey = :bondKey ORDER BY id ASC;", ["bondKey" => $bondKey]);
        }
        // Get every name of masterDevice with fetchAll
        $fetchResult["otherName"] = $dbC->runQuery("SELECT masterName,deviceType FROM bond WHERE username = :name AND masterName != :exception ORDER BY id ASC;", ["name" => $arg["username"], "exception" => $fetchResult["deviceInfo"]["masterName"]], "ALL");

        $fetchResult["nexusBond"] = $dbC->runQuery("SELECT bondKey,deviceType,auxName1,auxName2,auxName3,auxName4,auxStatus1,auxStatus2,auxStatus3,auxStatus4,tempNow,humidNow, lastUpdate FROM nexusbond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
        // Get the oldest record from daily plot data.
        $fetchResult["plot"]["oldest"] = $dbC->runQuery("SELECT MIN(date) AS oldestPlot FROM nexusplot WHERE bondKey = :bondkey;", ["bondkey" => $bondKey]);
        // Get the newest record from daily plot data.
        $fetchResult["plot"]["newest"] = $dbC->runQuery("SELECT MAX(date) AS newestPlot FROM nexusplot WHERE bondKey = :bondkey;", ["bondkey" => $bondKey]);
        // If the newest record from daily plot data is not current date, then create the frame of the current date.
        // Get current date plot data.
        $fetchResult["plot"]["available"] = $dbC->runQuery("SELECT * FROM nexusplot WHERE bondKey = :bondkey AND date = :date LIMIT 1;", ["bondkey" => $bondKey, "date" => $todayDate->format("Y-m-d")]);
        $fetchResult["plot"]["plotDate"] = $todayDate->format("Y-m-d"); // Refer to availability of plot of current date
        if ($fetchResult["plot"]["available"]) {
            $fetchResult["plot"]["available"] = true;
            $fetchResult["plot"]["data"] = $dbC->runQuery("SELECT * FROM nexusplot WHERE bondKey = :bondkey AND date = :date;", ["bondkey" => $bondKey, "date" => $todayDate->format("Y-m-d")], "ALL"); // Fetch all data from date
            if ($fetchResult["plot"]["newest"]["newestPlot"] !== $todayDate->format("Y-m-d")) {
                // Refresh the newest record
                $fetchResult["plot"]["newest"] = $dbC->runQuery("SELECT MAX(date) AS newestPlot FROM nexusplot WHERE bondKey = :bondkey;", ["bondkey" => $bondKey]);
            }
        } else {
            $fetchResult["plot"]["available"] = false;
        }
        // Fetch automations program that exist
        $fetchResult["programs"] = $dbC->runQuery("SELECT * FROM nexusautomation WHERE bondKey = :bondkey AND exist = '1';", ["bondkey" => $bondKey], "ALL");
        echo json_encode($fetchResult);
    }
}
