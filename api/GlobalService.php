<?php require "originandreferer.php";
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
    if (isset($json["token"]) && isset($json["requestType"])) {
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
            if ($requestType == "submitMessage") {
                submitMessage($json, $dbController);
            }
        }
    }
}

function submitMessage($arg, $dbC)
{
    if (isset($arg["name"]) && isset($arg["email"]) && isset($arg["message"]) && isset($arg["phone"])) {
        if ($arg["name"] != "" && $arg["email"] != "" && $arg["message"] != "" && $arg["phone"] != "")
            $dbC->runQuery("INSERT INTO contact_us (username,nama,nomor,email,pesan) VALUES (:username,:name,:phone,:email,:message);", ["username" => $arg["username"], "name" => $arg["name"], "phone" => $arg["phone"], "email" => $arg["email"], "message" => $arg["message"]]);
    }
}
