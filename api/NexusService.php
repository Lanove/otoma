<?php
require "originandreferer.php";

require "DatabaseController.php";
$GLOBALS["dbController"] = new DatabaseController();
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
    $json = json_decode(file_get_contents('php://input'), true); // Get JSON Input from AJAX and decode it to PHP Array
    if (!empty($json["token"]) && !empty($json["requestType"])) {
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
                loadDeviceInformation($json);
            } else { // bondKey related request is taken with privilege check.
                if (!empty($json["bondKey"])) {
                    // Check if user actually had a bond with bondKey holder
                    $privilegeCheck = $GLOBALS["dbController"]->runQuery("SELECT username FROM nexusbond WHERE bondKey = :bondkey;", ["bondkey" => $json["bondKey"]]);
                    if ($privilegeCheck["username"] === $json["username"]) {
                        if (($requestType === "loadPlot")) {
                            loadPlot($json);
                        } else if ($requestType === "toggleSwitch") {
                            toggleSwitch($json);
                        } else if ($requestType === "modeSwitch") {
                            modeSwitch($json);
                        } else if ($requestType === "submitParameter") {
                            submitParameter($json);
                        }
                    }
                }
            }
        }
    }
}

function submitParameter($arg)
{
    if (!empty($arg["id"] && !empty($arg["par"]))) {
        $id = $arg["id"];
        $parameter = $arg["par"];
        $bondKey = $arg["bondKey"];
        $stringBuffer = "";
        $columnName = "";
        $failed = true;
        $fetchResult = $GLOBALS["dbController"]->runQuery("SELECT heaterPar,coolerPar FROM nexusbond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
        if (($id === "submitcpid" || $id === "submithpid") && !empty($parameter[0]) && !empty($parameter[1]) && !empty($parameter[2]) && !empty($parameter[3]) && is_numeric($parameter[0]) && is_numeric($parameter[1]) && is_numeric($parameter[2]) && is_numeric($parameter[3])) { // Submit PID parameter requires 4 parameter of array to be passed, so check whether 4 array is empty and is numeric before proceeding the task.
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
        } else if (($id === "submitchys" || $id === "submithhys") && !empty($parameter[0]) && !empty($parameter[1]) && is_numeric($parameter[0]) && is_numeric($parameter[1])) { // Submit Hysteresis parameter only requires 2 array of value or par, so just check the first 2 index only
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
        if (strlen($stringBuffer) > 50) {
            $stringBuffer = "0/0/0/0%0/0";
        }
        if ($stringBuffer !== "" && $columnName !== "" && !$failed) {
            $GLOBALS["dbController"]->runQuery("UPDATE nexusbond SET {$columnName}='{$stringBuffer}' WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
            $callback = $GLOBALS["dbController"]->runQuery("SELECT heaterPar,coolerPar FROM nexusbond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
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

function modeSwitch($arg)
{
    if (!empty($arg["mode"]) && !empty($arg["docchi"])) {
        $mode = $arg["mode"];
        $docchi = $arg["docchi"];
        $bondKey = $arg["bondKey"];
        $fetchResult = $GLOBALS["dbController"]->runQuery("SELECT thercoInfo FROM nexusbond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
        $stringBuffer = "";
        $columnName = "";
        if ($docchi === "operation" && ($mode === "auto" || $mode === "manual")) {
            $fetchResult["thercoInfo"] = explode("%", $fetchResult["thercoInfo"]);
            $stringBuffer = $mode . "%" . $fetchResult["thercoInfo"][1];
            $columnName = "thercoInfo";
        } else if ($docchi === "thermmode" && ($mode === "cool" || $mode === "heat" || $mode === "dual")) {
            $fetchResult["thercoInfo"] = explode("%", $fetchResult["thercoInfo"]);
            $stringBuffer = $fetchResult["thercoInfo"][0] . "%" . $mode;
            $columnName = "thercoInfo";
        } else if ($docchi === "hmode"  && ($mode === "hys" || $mode === "pid")) {
            $stringBuffer = $mode;
            $columnName = "heaterMode";
        } else if ($docchi === "cmode"  && ($mode === "hys" || $mode === "pid")) {
            $stringBuffer = $mode;
            $columnName = "coolerMode";
        }
        if ($stringBuffer !== "" && $columnName !== "") {
            $GLOBALS["dbController"]->runQuery("UPDATE nexusbond SET {$columnName}='{$stringBuffer}' WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
        }
    }
}

function toggleSwitch($arg)
{
    if (!empty($arg["id"]) && !empty($arg["status"])) {
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
        $GLOBALS["dbController"]->runQuery("UPDATE nexusbond SET {$columnData}='{$status}' WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
    }
}
function loadPlot($arg)
{
    if (!empty($arg["date"])) {
        $bondKey = $arg["bondKey"];
        $date = $arg["date"];
        $dateCheck = explode("-", $date);
        // A long ass if statement to check if the date is correctly formatted
        if (!empty($dateCheck[0]) && !empty($dateCheck[1]) && !empty($dateCheck[2]) && is_numeric($dateCheck[0]) && is_numeric($dateCheck[1]) && is_numeric($dateCheck[2]) && $dateCheck[0] > 1970 && $dateCheck[0] < 2100 && $dateCheck[1] > 0 && $dateCheck[1] < 13 && $dateCheck[2] > 0 && $dateCheck[2] < 33) {
            $fetchResult["plot"]["available"] = $GLOBALS["dbController"]->runQuery("SELECT * FROM nexusplot WHERE bondKey = :bondkey AND date = :date LIMIT 1;", ["bondkey" => $bondKey, "date" => $date]);
            $fetchResult["plot"]["plotDate"] = $date; // Refer to availability of plot of current date
            if ($fetchResult["plot"]["available"]) {
                $fetchResult["plot"]["available"] = true;
                $fetchResult["plot"]["data"] = $GLOBALS["dbController"]->runQuery("SELECT * FROM nexusplot WHERE bondKey = :bondkey AND date = :date;", ["bondkey" => $bondKey, "date" => $date], "ALL"); // Fetch all data from date
            } else {
                $fetchResult["plot"]["available"] = false;
            }
            echo json_encode($fetchResult);
        }
    }
}

function loadDeviceInformation($arg)
{
    if (!empty($arg["master"])) {
        $master = $arg["master"];
        $username = $arg["username"];
        if ($master === "master") {
            $fetchResult["deviceInfo"] = $GLOBALS["dbController"]->runQuery("SELECT bondKey,masterName FROM bond WHERE username = :name ORDER BY id ASC;", ["name" => $username]);
        } else { // THIS ELSE IF STATEMENT MEAN THAT ONE USER CAN'T NAME DEVICE WITH SAME NAME.
            $fetchResult["deviceInfo"] = $GLOBALS["dbController"]->runQuery("SELECT bondKey,masterName FROM bond WHERE username = :name AND masterName = :master;", ["name" => $username, "master" => $master]);
        }
        $bondKey = $fetchResult["deviceInfo"]["bondKey"];
        // Get every name of masterDevice with fetchAll
        $fetchResult["otherName"] = $GLOBALS["dbController"]->runQuery("SELECT masterName FROM bond WHERE username = :name AND masterName != :exception ORDER BY id ASC;", ["name" => $arg["username"], "exception" => $fetchResult["deviceInfo"]["masterName"]], "ALL");

        $fetchResult["nexusBond"] = $GLOBALS["dbController"]->runQuery("SELECT * FROM nexusbond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
        // Get the oldest record from daily plot data.
        $fetchResult["plot"]["oldest"] = $GLOBALS["dbController"]->runQuery("SELECT MIN(date) AS oldestPlot FROM nexusplot WHERE bondKey = :bondkey;", ["bondkey" => $bondKey]);
        // Get the newest record from daily plot data.
        $fetchResult["plot"]["newest"] = $GLOBALS["dbController"]->runQuery("SELECT MAX(date) AS newestPlot FROM nexusplot WHERE bondKey = :bondkey;", ["bondkey" => $bondKey]);
        // If the newest record from daily plot data is not current date, then create the frame of the current date.
        // Get current date plot data.
        $fetchResult["plot"]["available"] = $GLOBALS["dbController"]->runQuery("SELECT * FROM nexusplot WHERE bondKey = :bondkey AND date = :date LIMIT 1;", ["bondkey" => $bondKey, "date" => date("Y-m-d")]);
        $fetchResult["plot"]["plotDate"] = date("Y-m-d"); // Refer to availability of plot of current date
        if ($fetchResult["plot"]["available"]) {
            $fetchResult["plot"]["available"] = true;
            $fetchResult["plot"]["data"] = $GLOBALS["dbController"]->runQuery("SELECT * FROM nexusplot WHERE bondKey = :bondkey AND date = :date;", ["bondkey" => $bondKey, "date" => date("Y-m-d")], "ALL"); // Fetch all data from date
            if ($fetchResult["plot"]["newest"]["newestPlot"] !== date("Y-m-d")) {
                // Refresh the newest record
                $fetchResult["plot"]["newest"] = $GLOBALS["dbController"]->runQuery("SELECT MAX(date) AS newestPlot FROM nexusplot WHERE bondKey = :bondkey;", ["bondkey" => $bondKey]);
            }
        } else {
            $fetchResult["plot"]["available"] = false;
        }
        echo json_encode($fetchResult);
    }
}
$GLOBALS["dbController"]->close();
$GLOBALS["dbController"] = null;
