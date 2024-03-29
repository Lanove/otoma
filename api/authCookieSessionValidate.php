<?php
require_once "Auth.php";
require_once "Util.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$auth = new Auth();
$dbHandler = new DatabaseController();
$util = new Util();

// Get Current date, time
$current_time = time();
$current_date = date("Y-m-d H:i:s", $current_time);

// Set Cookie expiration for 1 month
$cookie_expiration_time = $current_time + (30 * 24 * 60 * 60);  // for 1 month

$isLoggedIn = false;

// Check if loggedin session and redirect if session exists
if ((isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)) {
    $isLoggedIn = true;
}
// Check if loggedin session exists
else if (!empty($_COOKIE["member_login"]) && !empty($_COOKIE["random_password"]) && !empty($_COOKIE["random_selector"])) {
    // Initiate auth token verification diirective to false
    $isPasswordVerified = false;
    $isSelectorVerified = false;
    $isExpiryDateVerified = false;

    // Get token for username
    $userToken = $auth->getTokenByUsername($_COOKIE["member_login"], 0);

    // Validate random password cookie with database
    if (password_verify($_COOKIE["random_password"], $userToken["password_hash"])) {
        $isPasswordVerified = true;
    }

    // Validate random selector cookie with database
    if (password_verify($_COOKIE["random_selector"], $userToken["selector_hash"])) {
        $isSelectorVerified = true;
    }

    // check cookie expiration by date
    if ($userToken["expiry_date"] >= $current_date) {
        $isExpiryDateVerified = true;
    }

    // Redirect if all cookie based validation retuens true
    // Else, mark the token as expired and clear cookies
    if (!empty($userToken["id"]) && $isPasswordVerified && $isSelectorVerified && $isExpiryDateVerified) {
        $userCred = $auth->getMemberByUsername($_COOKIE["member_login"]);
        $_SESSION["loggedin"] = true;
        $_SESSION["id"] = $userCred["id"];
        $_SESSION["username"] = $userCred["username"];
        $isLoggedIn = true;
    } else {
        if (!empty($userToken["id"])) {
            $auth->markAsExpired($userToken["id"]);
        }
        // clear cookies
        $util->clearAuthCookie();
    }
}
