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
    require "DatabaseController.php";
    $dbController = new DatabaseController();

    session_start();
    $json = json_decode(file_get_contents('php://input'), true); // Get JSON Input from AJAX and decode it to PHP Array
    if (isset($json["token"]) &&  isset($json["requestType"])) {
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
                    $privilegeCheck = $dbController->runQuery("SELECT username FROM nexusbond WHERE bondKey = :bondkey;", ["bondkey" => $json["bondKey"]]);
                    if ($privilegeCheck["username"] === $json["username"]) {
                        if (($requestType === "loadPlot")) {
                            loadPlot($json, $dbController);
                        } else if ($requestType === "toggleSwitch") {
                            toggleSwitch($json, $dbController);
                        } else if ($requestType === "modeSwitch") {
                            modeSwitch($json, $dbController);
                        } else if ($requestType === "submitParameter") {
                            submitParameter($json, $dbController);
                        } else if ($requestType === "changeSetpoint") {
                            changeSetpoint($json, $dbController);
                        } else if ($requestType === "updateProgram") {
                            updateProgram($json, $dbController);
                        } else if ($requestType === "deleteProgram") {
                            deleteProgram($json, $dbController);
                        } else if ($requestType == "reloadStatus") {
                            requestStatus($json, $dbController);
                        }
                    }
                }
            }
        }
    }
    $dbController->close();
    $dbController = null;
}

function requestStatus($arg, $dbC)
{
    $bondKey = $arg["bondKey"];
    $fetchResult["status"] = $dbC->runQuery("SELECT auxStatus1, auxStatus2, thStatus, htStatus, clStatus, tempNow, humidNow, sp, thercoInfo, heaterMode, coolerMode, heaterPar, coolerPar FROM nexusbond WHERE bondKey = :bondkey;", ["bondkey" => $bondKey]);
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
            $dbC->runQuery("INSERT INTO nexusautomation (bondKey,progNumber,deviceType)
            VALUES ${stringBuffer};", []);
            $response["success"] = true;
        } else {
            if ($progNum > 0 && $progNum < 31) {
                $dbC->runQuery("UPDATE nexusautomation SET exist='0', progData1='', progData2='', progData3='', progData4='', progData5='', progData6='', progData7='', progData8='' WHERE bondKey = :bondKey AND progNumber = :progNum;", ["bondKey" => $bondKey, "progNum" => $progNum]);
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
            "Nyalakan Output 1",
            "Nyalakan Output 2",
            "Nyalakan Pemanas",
            "Nyalakan Pendingin",
            "Nyalakan Thermocontrol",
            "Matikan Output 1",
            "Matikan Output 2",
            "Matikan Pemanas",
            "Matikan Pendingin",
            "Matikan Thermocontrol"
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
            $dbC->runQuery("INSERT INTO nexusautomation (bondKey,progNumber,deviceType)
            VALUES ${stringBuffer};", []);
        }
        if ($validFlag) {
            if (
                $trigger == "Nilai Suhu" ||
                $trigger == "Nilai Humiditas"
            ) {
                if (
                    isset($arg["passedData"]["nscmpCd"]) &&
                    isset($arg["passedData"]["nsvalCd"])
                ) {
                    $validComparator = array("<", ">", "<=", ">=", "!=", "==");
                    $nscmp = $arg["passedData"]["nscmpCd"];
                    $nsval = (int)$arg["passedData"]["nsvalCd"];
                    $validFlag = false;
                    foreach ($validComparator as $k) {
                        if ($k == $nscmp) $validFlag = true;
                    }
                    if ($nsval >= 0 && $nsval <= 100 && $validFlag) {
                        $dbC->runQuery("UPDATE nexusautomation SET exist='1',   progData1=:trigger, progData2=:action, progData3=:nscmp, progData4=:nsval, progData5='', progData6='', progData7='', progData8='' WHERE bondKey = :bondKey AND progNumber = :progNum;", ["trigger" => $trigger, "action" => $action, "nscmp" => $nscmp, "nsval" => $nsval, "bondKey" => $bondKey, "progNum" => $progNum]);
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
                            $dbC->runQuery("UPDATE nexusautomation SET exist='1',   progData1=:trigger, progData2=:action, progData3=:timeFrom, progData4=:timeTo, progData5='', progData6='', progData7='', progData8='' WHERE bondKey = :bondKey AND progNumber = :progNum;", ["trigger" => $trigger, "action" => $action, "timeFrom" => $timeFrom, "timeTo" => $timeTo, "bondKey" => $bondKey, "progNum" => $progNum]);

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
                        $dbC->runQuery("UPDATE nexusautomation SET exist='1',   progData1=:trigger, progData2=:action, progData3=:dateFrom, progData4=:dateTo, progData5='', progData6='', progData7='', progData8='' WHERE bondKey = :bondKey AND progNumber = :progNum;", ["trigger" => $trigger, "action" => $action, "dateFrom" => $dateFrom, "dateTo" => $dateTo, "bondKey" => $bondKey, "progNum" => $progNum]);

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
                            $dbC->runQuery("UPDATE nexusautomation SET exist='1',   progData1=:trigger, progData2=:action, progData3=:timerCd, progData4='', progData5='', progData6='', progData7='', progData8='' WHERE bondKey = :bondKey AND progNumber = :progNum;", ["trigger" => $trigger, "action" => $action, "timerCd" => $timerCd, "bondKey" => $bondKey, "progNum" => $progNum]);

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
                    if (($component == "Output 1" || $component == "Output 2" ||
                            $component == "Pemanas" || $component == "Pendingin" ||
                            $component == "Thermocontrol") &&
                        ($condition == "Menyala" || $condition == "Mati")
                    ) {
                        $dbC->runQuery("UPDATE nexusautomation SET exist='1', progData1=:trigger, progData2=:action, progData3=:component, progData4=:condition, progData5='', progData6='', progData7='', progData8='' WHERE bondKey = :bondKey AND progNumber = :progNum;", ["trigger" => $trigger, "action" => $action, "component" => $component, "condition" => $condition, "bondKey" => $bondKey, "progNum" => $progNum]);

                        $doubleCheck = $dbC->runQuery("SELECT * FROM nexusautomation WHERE bondKey = :bondKey AND progNumber = :progNumber;", ["bondKey" => $bondKey, "progNumber" => $progNum]);

                        if ($doubleCheck["progData1"] == $trigger && $doubleCheck["progData2"] == $action && $doubleCheck["progData3"] == $component && $doubleCheck["progData4"] == $condition && $doubleCheck["exist"] == '1')
                            $response["success"] = true;
                    }
                }
            }
        }
    }
    echo json_encode($response);
}

function changeSetpoint($arg, $dbC)
{
    if (isset($arg["data"])) {
        $data = $arg["data"];
        $bondKey = $arg["bondKey"];
        if (is_numeric($data) && $data >= 0 && $data <= 100) {
            $dbC->runQuery("UPDATE nexusbond SET sp='{$data}' WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
        }
    }
}

function submitParameter($arg, $dbC)
{
    if (isset($arg["id"]) && isset($arg["par"])) {
        $id = $arg["id"];
        $parameter = $arg["par"];
        $bondKey = $arg["bondKey"];
        $stringBuffer = "";
        $columnName = "";
        $failed = true;
        $fetchResult = $dbC->runQuery("SELECT heaterPar,coolerPar FROM nexusbond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
        if (($id === "submitcpid" || $id === "submithpid") &&
            isset($parameter[0]) &&
            isset($parameter[1]) &&
            isset($parameter[2]) &&
            isset($parameter[3])
        ) { // Submit PID parameter requires 4 parameter of array to be passed, so check whether 4 array is empty and is numeric before proceeding the task.
            if (
                is_numeric($parameter[0]) &&
                is_numeric($parameter[1]) &&
                is_numeric($parameter[2]) &&
                is_numeric($parameter[3])
            ) {
                for ($k = 0; $k < 3; $k++) {
                    if ($parameter[$k] > 100) $parameter[$k] = 100;
                    else if ($parameter[$k] < -100) $parameter[$k] = -100;
                    $parameter[$k] = round($parameter[$k], 2);
                }
                if ($parameter[3] > 1000000) $parameter[3] = 1000000;
                else if ($parameter[3] < 100) $parameter[3] = 100;
                $parameter[3] = round($parameter[3], 0);
                $split = "";
                if ($id === "submitcpid") {
                    $split = explode("%", $fetchResult["coolerPar"]);
                    $columnName = "coolerPar";
                } else {
                    $split = explode("%", $fetchResult["heaterPar"]);
                    $columnName = "heaterPar";
                }
                $stringBuffer = $parameter[0] . "/" . $parameter[1] . "/" . $parameter[2] . "/" . $parameter[3] . "%" . $split[1];
                $failed = false;
            }
        } else if (($id === "submitchys" || $id === "submithhys") &&
            isset($parameter[0]) &&
            isset($parameter[1])
        ) { // Submit Hysteresis parameter only requires 2 array of value or par, so just check the first 2 index only
            if (
                is_numeric($parameter[0]) &&
                is_numeric($parameter[1])
            ) {

                for ($k = 0; $k < 2; $k++) {
                    if ($parameter[$k] > 30) $parameter[$k] = 30;
                    else if ($parameter[$k] < 0) $parameter[$k] = 0;
                    $parameter[$k] = round($parameter[$k], 2);
                }
                $split = "";
                if ($id === "submitchys") {
                    $split = explode("%", $fetchResult["coolerPar"]);
                    $columnName = "coolerPar";
                } else {
                    $split = explode("%", $fetchResult["heaterPar"]);
                    $columnName = "heaterPar";
                }
                $stringBuffer = $split[0] . "%" . $parameter[0] . "/" . $parameter[1];
                $failed = false;
            }
        }
        if (strlen($stringBuffer) > 50) {
            $stringBuffer = "0/0/0/0%0/0";
        }
        if (
            $stringBuffer !== "" &&
            $columnName !== "" &&
            !$failed
        ) {
            $dbC->runQuery("UPDATE nexusbond SET {$columnName}='{$stringBuffer}' WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
            $callback = $dbC->runQuery("SELECT heaterPar,coolerPar FROM nexusbond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
            $callback["heaterPar"] = explode("%", $callback["heaterPar"]);
            for ($m = 0; $m < 2; $m++) {
                $callback["heaterPar"][$m] = explode("/", $callback["heaterPar"][$m]);
                foreach ($callback["heaterPar"][$m] as $key => $value) {
                    $callback["heaterPar"][$m][$key] = floatval($callback["heaterPar"][$m][$key]);
                }
            }
            $callback["coolerPar"] = explode("%", $callback["coolerPar"]);
            for ($m = 0; $m < 2; $m++) {
                $callback["coolerPar"][$m] = explode("/", $callback["coolerPar"][$m]);
                foreach ($callback["coolerPar"][$m] as $key => $value) {
                    $callback["coolerPar"][$m][$key] = floatval($callback["coolerPar"][$m][$key]);
                }
            }
            echo json_encode(["failed" => $failed, $callback]);
        } else {
            echo json_encode(["failed" => $failed]);
        }
    }
}

function modeSwitch($arg, $dbC)
{
    if (isset($arg["mode"]) &&  isset($arg["docchi"])) {
        $mode = $arg["mode"];
        $docchi = $arg["docchi"];
        $bondKey = $arg["bondKey"];
        $fetchResult = $dbC->runQuery("SELECT thercoInfo FROM nexusbond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
        $stringBuffer = "";
        $columnName = "";
        if (
            $docchi === "operation" &&
            ($mode === "auto" || $mode === "manual")
        ) {
            if ($mode === "auto") {
                $dbC->runQuery("UPDATE nexusbond SET htStatus='0',clStatus='0' WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
            }
            $fetchResult["thercoInfo"] = explode("%", $fetchResult["thercoInfo"]);
            $stringBuffer = $mode . "%" . $fetchResult["thercoInfo"][1];
            $columnName = "thercoInfo";
        } else if (
            $docchi === "thermmode" &&
            ($mode === "cool" || $mode === "heat" || $mode === "dual")
        ) {
            $fetchResult["thercoInfo"] = explode("%", $fetchResult["thercoInfo"]);
            $stringBuffer = $fetchResult["thercoInfo"][0] . "%" . $mode;
            $columnName = "thercoInfo";
        } else if (
            $docchi === "hmode"  &&
            ($mode === "hys" || $mode === "pid")
        ) {
            $stringBuffer = $mode;
            $columnName = "heaterMode";
        } else if (
            $docchi === "cmode"  &&
            ($mode === "hys" || $mode === "pid")
        ) {
            $stringBuffer = $mode;
            $columnName = "coolerMode";
        }
        if (
            $stringBuffer !== "" &&
            $columnName !== ""
        ) {
            $dbC->runQuery("UPDATE nexusbond SET {$columnName}='{$stringBuffer}' WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
        }
    }
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
        else if ($id === "heaterSwitch")
            $columnData = "htStatus";
        else if ($id === "coolerSwitch")
            $columnData = "clStatus";
        else if ($id === "thSwitch")
            $columnData = "thStatus";
        $dbC->runQuery("UPDATE nexusbond SET {$columnData}='{$status}' WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
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
    if (isset($arg["master"])) {
        $master = $arg["master"];
        $username = $arg["username"];
        if ($master === "master") {
            $fetchResult["deviceInfo"] = $dbC->runQuery("SELECT bondKey,masterName FROM bond WHERE username = :name ORDER BY id ASC;", ["name" => $username]);
        } else { // THIS ELSE IF STATEMENT MEAN THAT ONE USER CAN'T NAME DEVICE WITH SAME NAME.
            $fetchResult["deviceInfo"] = $dbC->runQuery("SELECT bondKey,masterName FROM bond WHERE username = :name AND masterName = :master;", ["name" => $username, "master" => $master]);
        }
        $bondKey = $fetchResult["deviceInfo"]["bondKey"];
        // Get every name of masterDevice with fetchAll
        $fetchResult["otherName"] = $dbC->runQuery("SELECT masterName FROM bond WHERE username = :name AND masterName != :exception ORDER BY id ASC;", ["name" => $arg["username"], "exception" => $fetchResult["deviceInfo"]["masterName"]], "ALL");

        $fetchResult["nexusBond"] = $dbC->runQuery("SELECT * FROM nexusbond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
        // Get the oldest record from daily plot data.
        $fetchResult["plot"]["oldest"] = $dbC->runQuery("SELECT MIN(date) AS oldestPlot FROM nexusplot WHERE bondKey = :bondkey;", ["bondkey" => $bondKey]);
        // Get the newest record from daily plot data.
        $fetchResult["plot"]["newest"] = $dbC->runQuery("SELECT MAX(date) AS newestPlot FROM nexusplot WHERE bondKey = :bondkey;", ["bondkey" => $bondKey]);
        // If the newest record from daily plot data is not current date, then create the frame of the current date.
        // Get current date plot data.
        $fetchResult["plot"]["available"] = $dbC->runQuery("SELECT * FROM nexusplot WHERE bondKey = :bondkey AND date = :date LIMIT 1;", ["bondkey" => $bondKey, "date" => date("Y-m-d")]);
        $fetchResult["plot"]["plotDate"] = date("Y-m-d"); // Refer to availability of plot of current date
        if ($fetchResult["plot"]["available"]) {
            $fetchResult["plot"]["available"] = true;
            $fetchResult["plot"]["data"] = $dbC->runQuery("SELECT * FROM nexusplot WHERE bondKey = :bondkey AND date = :date;", ["bondkey" => $bondKey, "date" => date("Y-m-d")], "ALL"); // Fetch all data from date
            if ($fetchResult["plot"]["newest"]["newestPlot"] !== date("Y-m-d")) {
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
