<?php
// Initialize the session
session_start();

require "api/Util.php";
$util = new Util();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();
// clear cookies
$util->clearAuthCookie();

// Redirect to login page
header("location: login.php");
exit;
