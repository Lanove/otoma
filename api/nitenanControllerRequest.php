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
    isset($_SERVER['HTTP_NITENAN_BUILD_VERSION']) &&
    isset($_SERVER['HTTP_NITENAN_SDK_VERSION']) &&
    isset($_SERVER['HTTP_NITENAN_CHIP_VERSION']) &&
    isset($_SERVER['HTTP_NITENAN_FREE_SKETCH']) &&
    isset($_SERVER['HTTP_NITENAN_SKETCH_SIZE']) &&
    isset($_SERVER['HTTP_NITENAN_FLASH_SIZE']) &&
    isset($_SERVER['HTTP_NITENAN_SKETCH_MD5']) &&
    isset($_SERVER['HTTP_NITENAN_CPU_FREQ']) &&
    isset($_SERVER['HTTP_NITENAN_MAC']) &&
    isset($_SERVER['HTTP_NITENAN_USERNAME']) &&
    isset($_SERVER['HTTP_NITENAN_WIFI_SSID']) &&
    isset($_SERVER['HTTP_NITENAN_AP_SSID']) &&
    isset($_SERVER['HTTP_NITENAN_AP_PASS']) &&
    isset($_SERVER['HTTP_NITENAN_AP_IP'])
) {
    $deviceData["deviceToken"] = $_SERVER['HTTP_DEVICE_TOKEN'];
    $deviceData["softwareVersion"] = $_SERVER['HTTP_NITENAN_BUILD_VERSION'];
    $deviceData["sdkVersion"] = $_SERVER['HTTP_NITENAN_SDK_VERSION'];
    $deviceData["chipVersion"] = $_SERVER['HTTP_NITENAN_CHIP_VERSION'];
    $deviceData["freeSketch"] = $_SERVER['HTTP_NITENAN_FREE_SKETCH'];
    $deviceData["sketchSize"] = $_SERVER['HTTP_NITENAN_SKETCH_SIZE'];
    $deviceData["flashSize"] = $_SERVER['HTTP_NITENAN_FLASH_SIZE'];
    $deviceData["sketchMD5"] = $_SERVER['HTTP_NITENAN_SKETCH_MD5'];
    $deviceData["cpuFreq"] = $_SERVER['HTTP_NITENAN_CPU_FREQ'];
    $deviceData["MAC"] = $_SERVER['HTTP_NITENAN_MAC'];
    $deviceData["username"] = $_SERVER['HTTP_NITENAN_USERNAME'];
    $deviceData["wifiSSID"] = $_SERVER['HTTP_NITENAN_WIFI_SSID'];
    $deviceData["softSSID"] = $_SERVER['HTTP_NITENAN_AP_SSID'];
    $deviceData["softPass"] = $_SERVER['HTTP_NITENAN_AP_PASS'];
    $deviceData["softIP"] = $_SERVER['HTTP_NITENAN_AP_IP'];

    require "DatabaseController.php";
    $dbController = new DatabaseController();
    $bond = $dbController->runQuery("SELECT * FROM bond WHERE deviceToken = :deviceToken;", ["deviceToken" => $deviceToken]);
    $nitenanBond = $dbController->runQuery("SELECT * FROM nitenanbond WHERE bondKey = :bondKey;", ["bondKey" => $bondKey]);
    $bondKey = $bond["bondKey"];
    // First merge the both table fetch array (bond and nitenanBond), to obtain array that had equal keys with deviceData
    // compare the merged array with deviceData from HTTP request, which will return an object that had different value for the same key
    // if arrayDiff return some value, this means that there are some differences between database data and device data
    if (!empty(arrayDiff($deviceData, array_merge($bond, $nitenanBond)))) {
        // If there are some differences, then synchronize changes to database
        $dbC->execute(
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
        $dbC->execute(
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
    $bondKey = "";
    uploadPhoto($_FILES, $bondKey, $dbController);
} else
    header('HTTP/1.1 403 Forbidden');


function uploadPhoto($uploadedFile, $bondKey, $dbController)
{
    $target_dir = "uploads/";
    $datum = mktime(date('H') + 0, date('i'), date('s'), date('m'), date('d'), date('y'));
    $target_file = $target_dir .  generateRandomString() . "_" . date('Y-m-d_H_i_s', $datum) . basename($uploadedFile["imageFile"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($uploadedFile["imageFile"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }


    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($uploadedFile["imageFile"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($uploadedFile["imageFile"]["tmp_name"], $target_file)) {
            echo "The file " . basename($uploadedFile["imageFile"]["name"]) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
