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
            if ($requestType === "loadNitenanInfo") { // Request is first page load
                loadNitenanInfo($json, $dbController);
            } else { // bondKey related request is taken with privilege check.
                if (isset($json["bondKey"])) {
                    // Check if user actually had a bond with bondKey holder
                    $privilegeCheck = $dbController->runQuery("SELECT username FROM bond WHERE bondKey = :bondkey;", ["bondkey" => $json["bondKey"]]);
                    if ($privilegeCheck["username"] === $json["username"]) {
                        if (($requestType === "updateStatus")) {
                            updateStatus($json, $dbController);
                        }
                        if (($requestType === "checkSnapshot")) {
                            snapshotCommand($json, $dbController, "check");
                        }
                        if (($requestType === "takeSnapshot")) {
                            snapshotCommand($json, $dbController, "take");
                        }
                    }
                }
            }
        }
    }
    $dbController->close();
    $dbController = null;
} else header('HTTP/1.1 403 Forbidden');

function snapshotCommand($arg, $dbC, $cmd){
    $bondKey = $arg["bondKey"];
    if($cmd == "take"){
        $dbC->execute("UPDATE nitenanbond SET snapCommand='1' WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
        echo "OK";
    }else{
        print_r($dbC->runQuery("SELECT snapCommand FROM nitenanbond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey])["snapCommand"]);
    }
}

function updateStatus($arg, $dbC)
{
    if (isset($arg["data"]["flash"]) && isset($arg["data"]["servo"])) {
        $flashValue = intval($arg["data"]["flash"]);
        $servoValue = intval($arg["data"]["servo"]);
        $bondKey = $arg["bondKey"];
        if ($flashValue >= 0 && $flashValue <= 100 && $servoValue >= 0 && $servoValue <= 180) {
            $dbC->execute("UPDATE nitenanbond SET flashBrightness=:flashValue, servoAngle=:servoValue WHERE bondKey = :bondKey;", ["bondKey" => $bondKey, "servoValue" => $servoValue, "flashValue" => $flashValue]);
        }
    }
}
function loadNitenanInfo($arg, $dbC)
{
    if (isset($arg["master"]) || isset($arg["bondKey"])) {
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
        $fetchResult["otherName"] = $dbC->runQuery("SELECT masterName,deviceType FROM bond WHERE username = :name AND masterName != :exception ORDER BY id ASC;", ["name" => $arg["username"], "exception" => $fetchResult["deviceInfo"]["masterName"]], "ALL");
        $fetchResult["nitenanBond"] = $dbC->runQuery("SELECT * FROM nitenanbond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
        echo json_encode($fetchResult);
    }
}
