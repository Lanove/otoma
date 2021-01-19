<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if Request Method used is POST
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
            } else if ($requestType == "userInfoChange") {
                userInfoChange($json, $dbController);
            } else if ($requestType == "requestUserInfo") {
                echo json_encode($dbController->runQuery("SELECT email,phoneNumber,provinsi,kota,nama,photoPath FROM users WHERE username = :username;", ["username" => $json["username"]]));
            } else if ($requestType == "deleteAccount") {
                deleteAccount($json, $dbController);
            } else if ($requestType == "getOwnedNitenan") {
                echo json_encode($dbController->runQuery("SELECT masterName FROM bond WHERE username = :username AND deviceType='nitenan';", ["username" => $json["username"]], "ALL"));
            } else if ($requestType == "submitAddProduct") {
                submitAddProduct($json, $dbController);
            }
        }
    }
    $dbController->close();
    $dbController = null;
} else header('HTTP/1.1 403 Forbidden');

function submitAddProduct($arg, $dbC)
{
    $responseBuffer = array();
    $responseBuffer["message"] = "";
    $responseBuffer["status"] = true;
    if (
        isset($arg["productData"]["namaProduk"]) &&
        isset($arg["productData"]["hargaProduk"]) &&
        isset($arg["productData"]["deskripsiProduk"]) &&
        isset($arg["productData"]["kamera"]) &&
        isset($arg["productData"]["gambar1"]) &&
        isset($arg["productData"]["gambar2"]) &&
        isset($arg["productData"]["gambar3"])
    ) {
        $namaProduk = filter_var($arg["productData"]["namaProduk"], FILTER_SANITIZE_STRING);
        $hargaProduk = intVal(filter_var($arg["productData"]["hargaProduk"], FILTER_SANITIZE_NUMBER_INT));
        $deskripsiProduk = filter_var($arg["productData"]["deskripsiProduk"], FILTER_SANITIZE_STRING);
        $kamera = $arg["productData"]["kamera"];
        if ($kamera == "Pilih Kamera" || empty($kamera)) {
            $responseBuffer["message"] .= "Tolong pilih kamera untuk menambahkan produk\n";
            $responseBuffer["status"] = false;
            echo json_encode($responseBuffer);
            exit;
        }
        if (empty($namaProduk)) {
            $responseBuffer["message"] .= "Tolong masukkan nama produk\n";
            $responseBuffer["status"] = false;
            echo json_encode($responseBuffer);
            exit;
        }
        if (empty($hargaProduk)) {
            $responseBuffer["message"] .= "Tolong masukkan harga produk\n";
            $responseBuffer["status"] = false;
            echo json_encode($responseBuffer);
            exit;
        }
        if (empty($deskripsiProduk)) {
            $responseBuffer["message"] .= "Tolong masukkan deskripsi produk\n";
            $responseBuffer["status"] = false;
            echo json_encode($responseBuffer);
            exit;
        }
        if (empty($arg["productData"]["gambar1"]) || strlen($arg["productData"]["gambar1"])<800) {
            $responseBuffer["message"] .= "Tidak dapat membaca gambar 1, pastikan gambar sudah terupload dengan benar\n";
            $responseBuffer["status"] = false;
            echo json_encode($responseBuffer);
            exit;
        }
        if (empty($arg["productData"]["gambar2"]) || strlen($arg["productData"]["gambar2"])<800) {
            $responseBuffer["message"] .= "Tidak dapat membaca gambar 2, pastikan gambar sudah terupload dengan benar\n";
            $responseBuffer["status"] = false;
            echo json_encode($responseBuffer);
            exit;
        }
        if (empty($arg["productData"]["gambar3"]) || strlen($arg["productData"]["gambar3"])<800) {
            $responseBuffer["message"] .= "Tidak dapat membaca gambar 3, pastikan gambar sudah terupload dengan benar\n";
            $responseBuffer["status"] = false;
            echo json_encode($responseBuffer);
            exit;
        }
        $gambar = array($arg["productData"]["gambar1"],$arg["productData"]["gambar2"],$arg["productData"]["gambar3"]);
        $fetchResult = $dbC->runQuery("SELECT * FROM bond WHERE username = :username AND masterName=:masterName;", ["masterName" => $kamera, "username" => $arg["username"]]);
        if ($fetchResult) {
            $bondKey = $fetchResult["bondKey"];
            $duplicateCheck = $dbC->runQuery("SELECT * FROM lapak WHERE bondKey=?;", [$bondKey]);
            if ($duplicateCheck) {
                $responseBuffer["message"] .= "Anda tidak boleh menambahkan lebih dari 1 produk pada 1 kamera, mohon gunakan kamera lain untuk menambahkan produk baru\n";
                $responseBuffer["status"] = false;
                echo json_encode($responseBuffer);
                exit;
            }
            if (strlen($namaProduk) > 100) {
                $responseBuffer["message"] .= "Nama Produk tidak boleh lebih dari 100 huruf\n";
                $responseBuffer["status"] = false;
            }
            if (strlen($namaProduk) < 5) {
                $responseBuffer["message"] .= "Nama Produk harus lebih panjang dari 5 huruf\n";
                $responseBuffer["status"] = false;
            }
            if ($hargaProduk < 100) {
                $responseBuffer["message"] .= "Harga produk tidak boleh kurang dari 100 rupiah\n";
                $responseBuffer["status"] = false;
            }
            if ($hargaProduk > 100000000) {
                $responseBuffer["message"] .= "Harga produk tidak boleh lebih dari 100 juta rupiah\n";
                $responseBuffer["status"] = false;
            }
            if (strlen($deskripsiProduk) < 30) {
                $responseBuffer["message"] .= "Deskripsi produk harus lebih panjang dari 30 huruf\n";
                $responseBuffer["status"] = false;
            }
            if (strlen($namaProduk) > 1000) {
                $responseBuffer["message"] .= "Deskripsi Produk tidak boleh lebih dari 1000 huruf\n";
                $responseBuffer["status"] = false;
            }
            $imagePath = array();
            if ($responseBuffer["status"] == true) {
                for ($i = 0; $i < 3; $i++) {
                    list($type, $gambar[$i]) = explode(';', $gambar[$i]);
                    list(, $gambar[$i])      = explode(',', $gambar[$i]);
                    $type = explode("/", $type)[1];
                    $gambar[$i] = base64_decode($gambar[$i]);
                    $randomString = bin2hex(random_bytes(5));
                    $imagePath[$i] = "../img/product-photo/{$randomString}.{$type}";
                    if (file_put_contents($imagePath[$i], $gambar[$i]) === FALSE) {
                        $responseBuffer["message"] .= "Terjadi kesalahan saat mengupload gambar {$i}\n";
                        $responseBuffer["status"] = false;
                    };
                }
            }
            if ($responseBuffer["status"] === true && $responseBuffer["message"] === "") {
                $dbC->execute("INSERT INTO lapak(bondkey,namaBarang,hargaBarang,deskripsiBarang,gambar1,gambar2,gambar3) VALUES (?,?,?,?,?,?,?);", [$bondKey, $namaProduk, $hargaProduk, $deskripsiProduk, $imagePath[0], $imagePath[1], $imagePath[2]]);
            }
        } else {
            $responseBuffer["message"] .= "Terjadi kesalahan, kontroller dengan nama {$kamera} tidak ditemukan\n";
            $responseBuffer["status"] = false;
        }
    } else {
        $responseBuffer["message"] .= "Terjadi kesalahan saat permintaan, mohon coba lagi sesaat kemudian\n";
        $responseBuffer["status"] = false;
    }
    echo json_encode($responseBuffer);
}

function deleteAccount($arg, $dbC)
{
    if (isset($arg["password"])) {
        $fetchResult = $dbC->runQuery("SELECT * FROM users WHERE username = :username;", ["username" => $arg["username"]]);
        if (password_verify($arg["password"], $fetchResult["password"])) {
            $dbC->execute("DELETE FROM users WHERE username = :username;", ["username" => $arg["username"]]);
            echo json_encode(["status" => "success", "title" => "Berhasil", "message" => "Akun anda sudah terhapus, akan keluar dalam 3 detik..."]);
        } else
            echo json_encode(["status" => "failure", "title" => "Terjadi kesalahan", "message" => "Password yang anda masukkan salah."]);
    } else
        echo json_encode(["status" => "failure", "title" => "Error", "message" => "Terjadi kesalahan saat permintaan, mohon coba lagi sesaat kemudian"]);
}

function userInfoChange($arg, $dbC)
{
    if (isset($arg["email"]) && isset($arg["phoneNumber"]) && isset($arg["b64Image"]) && isset($arg["provinsi"]) && isset($arg["kota"]) && isset($arg["nama"])) {
        $original_email = $arg["email"];
        $original_number = $arg["phoneNumber"];
        $provinsi = $arg["provinsi"];
        $kota = $arg["kota"];
        $clean_email = filter_var($original_email, FILTER_SANITIZE_EMAIL);
        $clean_number = filter_var($original_number, FILTER_SANITIZE_NUMBER_INT);
        $clean_name = filter_var($arg["nama"], FILTER_SANITIZE_STRING);

        $fetchResult = $dbC->runQuery("SELECT * FROM users WHERE username = :username;", ["username" => $arg["username"]]);

        if ($arg["b64Image"] != "") {
            $b64Image = $arg["b64Image"];
            list($type, $b64Image) = explode(';', $b64Image);
            list(, $b64Image)      = explode(',', $b64Image);
            $type = explode("/", $type)[1];
            $b64Image = base64_decode($b64Image);
            $randomString = bin2hex(random_bytes(5));
            if (file_exists($fetchResult["photoPath"]))
                unlink($fetchResult["photoPath"]);
            $imagePath = "../img/photo-profile/{$randomString}.{$type}";
            file_put_contents("../img/photo-profile/{$randomString}.{$type}", $b64Image);
        } else
            $imagePath = $fetchResult["photoPath"];

        if (($provinsi != "" || $provinsi != "Pilih Provinsi") && ($kota != "" || $kota != "Pilih Kota") &&
            ($original_email == $clean_email && filter_var($original_email, FILTER_VALIDATE_EMAIL)) ||
            ($original_number == $clean_number && filter_var($original_number, FILTER_VALIDATE_INT))
        ) {
            if ($clean_email != $fetchResult["email"] || $clean_email != $fetchResult["phoneNumber"]) {
                $dbC->execute("UPDATE users SET nama=:nama, email=:email, phoneNumber=:phoneNumber, photoPath=:imagePath, provinsi=:provinsi, kota=:kota WHERE username = :username;", ["nama" => $clean_name, "kota" => $kota, "provinsi" => $provinsi, "imagePath" => $imagePath, "phoneNumber" => $clean_number, "email" => $clean_email, "username" => $arg["username"]]);
                echo json_encode(["status" => "success", "message" => "Berhasil mengupdate informasi akun!"]);
            } else
                echo json_encode(["status" => "failure", "message" => "Email/nomor lama dengan email/nomor baru tidak boleh sama"]);
        } else
            echo json_encode(["status" => "failure", "message" => "Tolong masukkan email/nomor dengan benar"]);
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
                        $dbC->execute("UPDATE users SET password=:hpw WHERE username = :username;", ["hpw" => $hashedPassword, "username" => $arg["username"]]);
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
            $dbC->execute("INSERT INTO contact_us (username,nama,nomor,email,pesan) VALUES (:username,:name,:phone,:email,:message);", ["username" => $arg["username"], "name" => $arg["name"], "phone" => $arg["phone"], "email" => $arg["email"], "message" => $arg["message"]]);
    }
}
