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
            } else if ($requestType == "passwordChange") {
                passwordChange($json, $dbController);
            } else if ($requestType == "emailChange") {
                emailChange($json, $dbController);
            } else if ($requestType == "requestEmail") {
                echo $dbController->runQuery("SELECT email FROM users WHERE username = :username;", ["username" => $json["username"]])["email"];
            } else if ($requestType == "deleteAccount") {
                deleteAccount($json, $dbController);
            }
        }
    }
    $dbController->close();
    $dbController = null;
}

function deleteAccount($arg, $dbC)
{
    if (isset($arg["password"])) {
        $fetchResult = $dbC->runQuery("SELECT * FROM users WHERE username = :username;", ["username" => $arg["username"]]);
        if (password_verify($arg["password"], $fetchResult["password"])) {
            $dbC->runQuery("DELETE FROM users WHERE username = :username;", ["username" => $arg["username"]]);
            echo json_encode(["status" => "success", "title" => "Berhasil", "message" => "Akun anda sudah terhapus, akan keluar dalam 3 detik..."]);
        } else
            echo json_encode(["status" => "failure", "title" => "Terjadi kesalahan", "message" => "Password yang anda masukkan salah."]);
    } else
        echo json_encode(["status" => "failure", "title" => "Error", "message" => "Terjadi kesalahan saat permintaan, mohon coba lagi sesaat kemudian"]);
}

function emailChange($arg, $dbC)
{
    if (isset($arg["email"])) {
        $original_email = $arg["email"];
        $clean_email = filter_var($original_email, FILTER_SANITIZE_EMAIL);
        $fetchResult = $dbC->runQuery("SELECT * FROM users WHERE username = :username;", ["username" => $arg["username"]]);
        if ($original_email == $clean_email && filter_var($original_email, FILTER_VALIDATE_EMAIL)) {
            if ($clean_email != $fetchResult["email"]) {
                $dbC->runQuery("UPDATE users SET email=:email WHERE username = :username;", ["email" => $clean_email, "username" => $arg["username"]]);
                echo json_encode(["status" => "success", "message" => "Berhasil mengganti email!"]);
            } else
                echo json_encode(["status" => "failure", "message" => "Email lama dengan email baru tidak boleh sama"]);
        } else
            echo json_encode(["status" => "failure", "message" => "Tolong masukkan email dengan benar"]);
    } else
        echo json_encode(["status" => "failure", "message" => "Terjadi kesalahan saat permintaan, mohon coba lagi sesaat kemudian"]);
}

function passwordChange($arg, $dbC)
{
    if (isset($arg["confPw"]) && isset($arg["newPw"]) && isset($arg["oldPw"])) {
        $confPw = $arg["confPw"];
        $newPw = $arg["newPw"];
        $oldPw = $arg["oldPw"];
        $fetchResult = $dbC->runQuery("SELECT * FROM users WHERE username = :username;", ["username" => $arg["username"]]);
        if (password_verify($oldPw, $fetchResult["password"])) {
            if ($newPw != $oldPw) {
                if (strlen($newPw) < 8) {
                    echo json_encode(["status" => "failure", "message" => "Panjang minimal password baru adalah 8 huruf"]);
                } else {
                    if ($newPw != $confPw) {
                        echo json_encode(["status" => "failure", "message" => "Password baru dengan konfirmasi password baru tidak sama"]);
                    } else {
                        $hashedPassword = password_hash($newPw, PASSWORD_DEFAULT);
                        // Insert into database!
                        $dbC->runQuery("UPDATE users SET password=:hpw WHERE username = :username;", ["hpw" => $hashedPassword, "username" => $arg["username"]]);
                        echo json_encode(["status" => "success", "message" => "Password berhasil diganti"]);
                    }
                }
            } else
                echo json_encode(["status" => "failure", "message" => "Password lama dengan password baru tidak boleh sama"]);
        } else
            echo json_encode(["status" => "failure", "message" => "Password lama salah"]);
    } else
        echo json_encode(["status" => "failure", "message" => "Terjadi kesalahan saat permintaan, mohon coba lagi sesaat kemudian"]);
}

function submitMessage($arg, $dbC)
{
    if (isset($arg["name"]) && isset($arg["email"]) && isset($arg["message"]) && isset($arg["phone"])) {
        if ($arg["name"] != "" && $arg["email"] != "" && $arg["message"] != "" && $arg["phone"] != "")
            $dbC->runQuery("INSERT INTO contact_us (username,nama,nomor,email,pesan) VALUES (:username,:name,:phone,:email,:message);", ["username" => $arg["username"], "name" => $arg["name"], "phone" => $arg["phone"], "email" => $arg["email"], "message" => $arg["message"]]);
    }
}
