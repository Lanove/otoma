<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $receivedCredential = $_POST["q"];
    require "CryptographyFunction.php";
    // Process incoming data
    $decryptedMsg = decryptAes($aesKey, $receivedCredential);
    $decryptedMsg .= "¶";
    $explodeDecode = explode('¶', $decryptedMsg, -1);
    if (count($explodeDecode) != 3) { // IF THE EXPLODED DATA DOES NOT CONTAIN 3 INFORMATION, HALT THE PROGRAM
        exit();
    }
    $token = $explodeDecode[0]; // extract device token
    $username = $explodeDecode[1]; // extract username
    $password = $explodeDecode[2]; // extract password
    $echoBack = ""; // variable to store returned data

    require "DatabaseController.php";
    $dbHandler = new DatabaseController();

    // Identify if the sender token exist in database.
    $fetchResult = $dbHandler->runQuery("SELECT deviceToken,deviceType FROM device WHERE deviceToken = :deviceToken", ["deviceToken" => $token]);
    $deviceType = $fetchResult["deviceType"];
    if (!$fetchResult) {
        $echoBack = "INVALID";
    } else {
        // Check if User credential is valid
        $fetchResult = $dbHandler->runQuery("SELECT id, username, password FROM users WHERE username = :name", ["name" => $username]);
        if ($fetchResult) { // If username with passed value exist, then
            $hashed_password = $fetchResult["password"];
            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Check for bond relation of user
                // Condition 1 : If device already had a bond with other user than applying user, then refuse the connection.
                // Condition 2 : If device already had a bond with applyting user, then just connect the device.
                // Condition 3 : If device does not have any bond beforehand, then create a new bond with applying user.
                $fetchResult = $dbHandler->runQuery("SELECT id,username,deviceToken,bondKey FROM bond WHERE deviceToken = :deviceToken", ["deviceToken" => $token]);

                if ($dbHandler->getRow() >= 1) { // It seems that applying device already had a bond
                    // Condition 1 : If device already had a bond with other user than applying user, then refuse the connection.
                    if ($fetchResult["username"] != $username) {
                        $echoBack = "DUPLICATE"; // Not bond owner, return duplicate error
                        // Condition 2 : If device already had a bond with applyting user, then just connect the device.
                    } else {
                        $echoBack = "RECON"; // Bond owner, connect device to server.   
                    }
                    // Condition 3 : If device does not have any bond beforehand, then create a new bond with applying user.
                } else {
                    $echoBack = "CONNECTED";
                    $bondKey = bin2hex(random_bytes(5));
                    // Initialize bond with user
                    $dbHandler->runQuery("INSERT INTO bond(username, deviceToken,deviceType, bondKey, masterName, deviceName1, deviceName2, deviceName3, deviceName4) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [$username, $deviceType, $token, $bondKey, "Master Device", "Perangkat 1", "Perangkat 2", "Perangkat 3", "Perangkat 4"]);
                    // Setup status table for device
                    $dbHandler->runQuery("INSERT INTO status(bondKey,deviceType) VALUES (?, ?)", [$bondKey, $deviceType]);
                }
            } else {
                // Display an error message if password is not valid
                $echoBack = "PASS ERROR";
            }
        } else {
            // Display an error message if username doesn't exist
            $echoBack = "USER ERROR";
        }
    }

    $echoBack = encryptAes($aesKey, $echoBack);
    echo $echoBack;
}
