<?php
$echoBack = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $json = json_decode(file_get_contents('php://input'), true); // Get JSON Input from AJAX and decode it to PHP Array
    if (isset($json["username"]) && isset($json["password"]) && isset($_SERVER['HTTP_DEVICE_TOKEN']) && isset($json["softssid"]) && isset($json["softpswd"])) {
        $username = $json["username"];
        $password = $json["password"];
        $deviceToken = $_SERVER['HTTP_DEVICE_TOKEN'];
        $softSSID = $json["softssid"];
        $softPW = $json["softpswd"];
        $macAddr = $_SERVER['HTTP_ESP8266_MAC'];
        $buildVersion = $_SERVER['HTTP_ESP8266_BUILD_VERSION'];
        require "DatabaseController.php";
        $dbHandler = new DatabaseController();

        // Identify if the sender token exist in database.
        $fetchResult = $dbHandler->runQuery("SELECT deviceToken,deviceType FROM device WHERE deviceToken = :deviceToken", ["deviceToken" => $deviceToken]);

        if (!$fetchResult) {
            $echoBack = "illegal"; // Return as unregistered device
        } else {
            $deviceType = $fetchResult["deviceType"];
            // Check if User credential is valid
            $fetchResult = $dbHandler->runQuery("SELECT id, username, password FROM users WHERE username = :name", ["name" => $username]);
            if ($fetchResult) { // If username with passed value exist, then
                $hashedPassword = $fetchResult["password"];
                // Verify password entered by user from ESP8266 or device
                if (password_verify($password, $hashedPassword)) {
                    // Check for bond relation of user
                    // Condition 1 : If device already had a bond with other user than applying user, then refuse the connection.
                    // Condition 2 : If device already had a bond with applyting user, then just connect the device.
                    // Condition 3 : If device does not have any bond beforehand, then create a new bond with applying user.
                    $fetchResult = $dbHandler->runQuery("SELECT * FROM bond WHERE deviceToken = :deviceToken", ["deviceToken" => $deviceToken]);

                    if ($fetchResult) { // It seems that applying device already had a bond
                        if ($fetchResult["username"] != $username) { // Condition 1 : If device already had a bond with other user than applying user, then refuse the connection.
                            $echoBack = "used"; // Not bond owner, return duplicate error
                        } else { // Condition 2 : If device already had a bond with applyting user, then just connect the device.
                            $echoBack = "recon"; // Bond owner, connect device to server.   
                        }
                    } else { // Condition 3 : If device does not have any bond beforehand, then create a new bond with applying user.
                        $echoBack = "success";
                        $bondKey = bin2hex(random_bytes(5)); // Create a 10 length random string for bondKey
                        // Initialize bond with user
                        $dbHandler->execute("INSERT INTO bond(username, deviceToken,deviceType, bondKey, masterName,softSSID,softPass,MAC,softwareVersion) VALUES (:username, :token, :type, :bondKey, :masterName, :softSSID, :softPass, :MAC, :buildVersion)", ["username" => $username, "token" => $deviceToken, "type" => $deviceType, "bondKey" => $bondKey, "masterName" => $bondKey, "softSSID" => $softSSID, "softPass" => $softPW, "MAC" => $macAddr, "buildVersion" => $buildVersion]);
                        // Device type specific table row insert
                        if ($deviceType == "nexus") {
                            $dbHandler->execute("INSERT INTO nexusbond(bondKey) VALUES (:bondKey)", ["bondKey" => $bondKey]);
                        }
                    }
                } else { // Wrong password is inserted, oops...
                    $echoBack = "wrongpw";
                }
            } else { // It was unregistered username, oops...
                $echoBack = "wrongid";
            }
        }
    } else
        $echoBack = "smwrong";
    echo $echoBack;
} else
    header('HTTP/1.1 403 Forbidden');
