<?php

require "nocache.php";

// Initialize the session
require_once "api/authCookieSessionValidate.php";

require_once "api/Auth.php";
require_once "api/Util.php";

$auth = new Auth();
$util = new Util();

if ($isLoggedIn) {
    $util->redirect("dashboard.php");
}

if (empty($_SESSION['logtoken'])) {
    $_SESSION['logtoken'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['logtoken'];

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

$dbHandler = new DatabaseController();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['token'])) {
        if (hash_equals($_SESSION['logtoken'], $_POST['token'])) {
            $_SESSION['logtoken'] = bin2hex(random_bytes(32));
            $token = $_SESSION['logtoken'];
            $message = array(
                "usernotfound" => "Username tidak terdaftar",
                "userempty" => "Tolong masukkan username",
                "passwordempty" => "Tolong masukkan password",
                "wrongpassword" => "Password salah"
            );
            // Include database controller
            // Processing form data when form is submitted
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            //$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
            $password = $_POST['password'];

            // Check if username is empty
            if (empty(trim($username))) {
                $username_err = $message["userempty"];
            } else {
                $username = trim($username);
            }

            // Check if password is empty
            if (empty($password)) {
                $password_err = $message["passwordempty"];
            }

            // Check if no errors present
            if (empty($username_err) && empty($password_err)) {
                $param_username = trim($username);
                // Run database query
                $fetchResult = $dbHandler->runQuery("SELECT id, username, password FROM users WHERE username = :name", ["name" => $param_username]);
                if (!empty($dbHandler->getErrorCode())) {
                    $dbHandler->errorHandler();
                    exit();
                }

                //Check if user exist
                if ($fetchResult) {
                    $id = $fetchResult["id"];
                    $username = $fetchResult["username"];
                    $hashed_password = $fetchResult["password"];
                    // Verify password
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, so start a new session
                        session_start();

                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;

                        // If the user check the remember me checkbox
                        if (!empty($_POST["remme"])) {
                            setcookie("member_login", $username, $cookie_expiration_time);

                            $random_password = $util->getToken(16);
                            setcookie("random_password", $random_password, $cookie_expiration_time);

                            $random_selector = $util->getToken(32);
                            setcookie("random_selector", $random_selector, $cookie_expiration_time);

                            $random_password_hash = password_hash($random_password, PASSWORD_DEFAULT);
                            $random_selector_hash = password_hash($random_selector, PASSWORD_DEFAULT);

                            $expiry_date = date("Y-m-d H:i:s", $cookie_expiration_time);

                            // mark existing token as expired
                            $userToken = $auth->getTokenByUsername($username, 0);
                            if (!empty($userToken["id"])) {
                                $auth->markAsExpired($userToken["id"]);
                            }
                            // Insert new token
                            $auth->insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date);
                        } else {
                            $util->clearAuthCookie();
                        }
                        // Redirect user to welcome page
                        header("location: dashboard.php");
                    } else {
                        // Display an error message if password is not valid
                        $password_err = $message["wrongpassword"];
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = $message["usernotfound"];
                }
            }
        }
    } else {
        // Log this as a warning and keep an eye on these attempts
    }
}
$dbHandler->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png">
    <link rel="manifest" href="img/favicon/site.webmanifest">
    <link rel="mask-icon" href="img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#00aba9">
    <meta name="theme-color" content="#ffffff">

    <title>Masuk ke Otoma</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style/style.css?<?php echo date('l jS \of F Y h:i:s A'); ?>" />
</head>

<body id="login">
    <div class="container">
        <div id="login-row" class="row justify-content-center">
            <div id="login-column" class="col-md-6">
                <div class="col-md-12 box">
                    <h2>Login Akun</h2>
                    <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, 'UTF-8'); ?>" method="post">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="form-group <?php echo htmlspecialchars((!empty($username_err)) ? 'has-error' : '', ENT_QUOTES, 'UTF-8'); ?>">
                            <label id="p">Username :</label>
                            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>">
                            <span class="help-block"><?php echo htmlspecialchars($username_err, ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                        <div class="form-group <?php echo
                                                    htmlspecialchars((!empty($password_err)) ? 'has-error' : '', ENT_QUOTES, 'UTF-8');
                                                ?>">
                            <label>Password :</label>
                            <input type="password" name="password" class="form-control">
                            <span class="help-block"><?php echo
                                                            htmlspecialchars($password_err, ENT_QUOTES, 'UTF-8'); ?></span>

                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="customCheck" name="remme">
                                <label class="custom-control-label" for="customCheck">Ingat saya</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="submit" class="btn">Masuk</button>
                        </div>
                    </form>
                    <div id="register-link">
                        <button class="btn"><a href="register.php">Daftar</a></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>