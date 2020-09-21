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
    $json = json_decode(file_get_contents('php://input'), true); // Get JSON Input from AJAX and Encode it to PHP Array
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
            }
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
        } else {
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
        $fetchResult["plot"]["available"] = $GLOBALS["dbController"]->runQuery("SELECT * FROM nexusplot WHERE bondKey = :bondkey AND date = :date LIMIT 1;", ["bondkey" => $bondKey, "date" => date("y-m-d")]);

        if ($fetchResult["plot"]["available"]) {
            $fetchResult["plot"]["available"] = true;
            $fetchResult["plot"]["data"] = $GLOBALS["dbController"]->runQuery("SELECT * FROM nexusplot WHERE bondKey = :bondkey AND date = :date;", ["bondkey" => $bondKey, "date" => date("y-m-d")], "ALL");
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
