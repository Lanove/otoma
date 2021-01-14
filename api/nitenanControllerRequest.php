<?php
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function arrayDiff($A, $B)
{
    $intersect = array_intersect($A, $B);
    return array_merge(array_diff($A, $intersect), array_diff($B, $intersect));
}

if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_SERVER['HTTP_DEVICE_TOKEN']) &&
    isset($_SERVER['HTTP_ESP32_BUILD_VERSION']) &&
    isset($_SERVER['HTTP_ESP32_SDK_VERSION']) &&
    isset($_SERVER['HTTP_ESP32_CHIP_VERSION']) &&
    isset($_SERVER['HTTP_ESP32_FREE_SKETCH']) &&
    isset($_SERVER['HTTP_ESP32_SKETCH_SIZE']) &&
    isset($_SERVER['HTTP_ESP32_FLASH_SIZE']) &&
    isset($_SERVER['HTTP_ESP32_SKETCH_MD5']) &&
    isset($_SERVER['HTTP_ESP32_CPU_FREQ']) &&
    isset($_SERVER['HTTP_ESP32_MAC']) &&
    isset($_SERVER['HTTP_ESP32_USERNAME']) &&
    isset($_SERVER['HTTP_ESP32_WIFI_SSID']) &&
    isset($_SERVER['HTTP_ESP32_AP_SSID']) &&
    isset($_SERVER['HTTP_ESP32_AP_PASS']) &&
    isset($_SERVER['HTTP_ESP32_AP_IP'])
) {
    header("Connection: keep-alive");
    $deviceData["deviceToken"] = $_SERVER['HTTP_DEVICE_TOKEN'];
    $deviceData["softwareVersion"] = $_SERVER['HTTP_ESP32_BUILD_VERSION'];
    $deviceData["sdkVersion"] = $_SERVER['HTTP_ESP32_SDK_VERSION'];
    $deviceData["chipVersion"] = $_SERVER['HTTP_ESP32_CHIP_VERSION'];
    $deviceData["freeSketch"] = $_SERVER['HTTP_ESP32_FREE_SKETCH'];
    $deviceData["sketchSize"] = $_SERVER['HTTP_ESP32_SKETCH_SIZE'];
    $deviceData["flashSize"] = $_SERVER['HTTP_ESP32_FLASH_SIZE'];
    $deviceData["sketchMD5"] = $_SERVER['HTTP_ESP32_SKETCH_MD5'];
    $deviceData["cpuFreq"] = $_SERVER['HTTP_ESP32_CPU_FREQ'];
    $deviceData["MAC"] = $_SERVER['HTTP_ESP32_MAC'];
    $deviceData["username"] = $_SERVER['HTTP_ESP32_USERNAME'];
    $deviceData["wifiSSID"] = $_SERVER['HTTP_ESP32_WIFI_SSID'];
    $deviceData["softSSID"] = $_SERVER['HTTP_ESP32_AP_SSID'];
    $deviceData["softPass"] = $_SERVER['HTTP_ESP32_AP_PASS'];
    $deviceData["softIP"] = $_SERVER['HTTP_ESP32_AP_IP'];

    require "DatabaseController.php";
    $dbController = new DatabaseController();
    $bond = $dbController->runQuery("SELECT * FROM bond WHERE deviceToken = :deviceToken;", ["deviceToken" => $deviceData["deviceToken"]]);
    if (isset($bond["bondKey"])) {
        $bondKey = $bond["bondKey"];
        $nitenanBond = $dbController->runQuery("SELECT * FROM nitenanbond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
        $dbController->execute("UPDATE nitenanbond SET lastUpdate=? WHERE bondKey = ?;", [gmdate('Y-m-d H:i:s', (time() + 25200)), $bondKey]);
        // First merge the both table fetch array (bond and nitenanBond), to obtain array that had equal keys with deviceData
        // compare the merged array with deviceData from HTTP request, which will return an object that had different value for the same key
        // if arrayDiff return some value, this means that there are some differences between database data and device data
        if (!empty(arrayDiff($deviceData, array_merge($bond, $nitenanBond)))) {
            // If there are some differences, then synchronize changes to database
            $dbController->execute(
                "UPDATE bond SET deviceToken=?, softSSID=?, softPass=?, softIP=?, MAC=?, softwareVersion=? WHERE bondKey = ?;",
                [
                    $deviceData["deviceToken"],
                    $deviceData["softSSID"],
                    $deviceData["softPass"],
                    $deviceData["softIP"],
                    $deviceData["MAC"],
                    $deviceData["softwareVersion"],
                    $bondKey
                ]
            );
            $dbController->execute(
                "UPDATE nitenanbond SET sdkVersion=?, chipVersion=?,freeSketch=?,sketchSize=?,flashSize=?,sketchMD5=?,cpuFreq=?,username=?,wifiSSID=? WHERE bondKey = ?;",
                [
                    $deviceData["sdkVersion"],
                    $deviceData["chipVersion"],
                    $deviceData["freeSketch"],
                    $deviceData["sketchSize"],
                    $deviceData["flashSize"],
                    $deviceData["sketchMD5"],
                    $deviceData["cpuFreq"],
                    $deviceData["username"],
                    $deviceData["wifiSSID"],
                    $bondKey
                ]
            );
        }
        if ($nitenanBond["snapCommand"] == "1" || $nitenanBond["streamCommand"] == "1")
            uploadPhoto($_FILES, $bondKey, $dbController);
        echo json_encode(["servo" => intval($nitenanBond["servoAngle"]), "flash" => intval($nitenanBond["flashBrightness"])]);
    }
} else
    header('HTTP/1.1 403 Forbidden');


function uploadPhoto($uploadedFile, $bondKey, $dbController)
{
    $fileName = $uploadedFile["imageFile"]["name"];
    $filePath = $uploadedFile["imageFile"]["tmp_name"];
    $nitenanBond = $dbController->runQuery("SELECT * FROM nitenanbond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
    $target_dir = "../img/nitenan/" . $bondKey;
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    $target_file = $target_dir .  "/1.jpg";
    if (file_exists($target_dir .  "/2.jpg"))
        unlink($target_dir .  "/2.jpg");
    if (file_exists($target_file))
        rename($target_file, $target_dir .  "/2.jpg");
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($filePath);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }


    // Check if file already exists
    if (file_exists($target_file)) {
        $uploadOk = 0;
    }

    // Check file size
    if ($uploadedFile["imageFile"]["size"] > 500000) {
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        $uploadOk = 0;
    }
    $source = imagecreatefromjpeg($filePath);
    $imageRotate = imagerotate($source, 270, 0);
    imagejpeg($imageRotate, $filePath, 100);
    // Check if $uploadOk is set to 0 by an error
    if (move_uploaded_file($filePath, $target_file) && $uploadOk == 1) {
        // echo "The file " . basename($uploadedFile["imageFile"]["name"]) . " has been uploaded.";
        $dbController->execute("UPDATE nitenanbond SET snapCommand=0, lastPhotoStamp=? WHERE bondKey = ?;", [gmdate('Y-m-d H:i:s', (time() + 25200)), $bondKey]);
    }
    imagedestroy($source);
    imagedestroy($imageRotate);
}
