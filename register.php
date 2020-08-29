<?php
// Include config file
session_start();

require "api/DatabaseController.php";
$dbHandler = new DatabaseController();

$message = array(
    "usernotfound" => "Username tidak terdaftar",
    "userempty" => "Tolong masukkan username",
    "passwordempty" => "Tolong masukkan password",
    "wrongpassword" => "Password salah",
    "passwordtarinai" => "Panjang password minimal 8 huruf",
    "usernametaken" => "Username telah terpakai oleh orang lain",
    "confirmpasswordempty" => "Tolong masukkan konfirmasi password",
    "passwordnotmatch" => "Password dengan konfirmasi password tidak sama",
    "passwordtoolong" => "Password terlalu panjang",
    "usernametooshort" => "Jumlah karakter username harus lebih dari 3",
    "usernametoolong" => "Jumlah karakter username harus kurang dari 33",
    "illegalusername" => "Username anda mengandung karakter ilegal",
);

if (empty($_SESSION['regtoken'])) {
    $_SESSION['regtoken'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['regtoken'];
// Define variables and initialize with empty values
$username = $rawusername = $password = $confirmPassword = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['token'])) {
        if (hash_equals($_SESSION['regtoken'], $_POST['token'])) {
            $_SESSION['regtoken'] = bin2hex(random_bytes(32));
            $token = $_SESSION['regtoken'];
            //Sanitize input to prevent XSS Attack
            $rawusername = $_POST['username'];
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);

            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];
            //$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
            //$confirmPassword = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);
            // Validate username
            if ($username !== $rawusername) {
                $username_err = $message["illegalusername"];
            } else if (empty(trim($username))) {
                $username_err = $message["userempty"];
            } else if (strlen($username) >= 4 && strlen($username) <= 32) {
                $fetchResult = $dbHandler->runQuery("SELECT id FROM users WHERE username = :name", ["name"   => $username]);
                if (!empty($dbHandler->getErrorCode())) {
                    $dbHandler->errorHandler();
                    exit();
                }
                $param_username = trim($username);
                if ($fetchResult) {
                    $username_err = $message["usernametaken"];
                } else {
                    $username = trim($username);
                }
            } else if (strlen($username) < 4) {
                $username_err = $message["usernametooshort"];
            } else if (strlen($username) > 32) {
                $username_err = $message["usernametoolong"];
            }

            // Validate password
            if (empty($password)) {
                $password_err = $message["passwordempty"];
            } elseif (strlen($password) < 8) {
                $password_err = $message["passwordtarinai"];
            } elseif (strlen($password) > 200) {
                $password_err = $message["passwordtoolong"];
            }

            // Validate confirm password
            if (empty($confirmPassword)) {
                $confirm_password_err = $message["confirmpasswordempty"];
            } else {
                if (empty($password_err) && ($password != $confirmPassword)) {
                    $confirm_password_err = $message["passwordnotmatch"];
                }
            }

            // Check input errors before inserting in database
            if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {

                $param_username = $username;
                $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

                // Insert into database!
                $dbHandler->runQuery("INSERT INTO users(username, password) VALUES (?, ?)", [$param_username, $param_password]);
                if (!empty($dbHandler->getErrorCode())) {
                    $dbHandler->errorHandler();
                    exit();
                }
                // Redirect user to login page
                header("location: login.php");
            }
        }
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

    <title>Daftar akun Otoma</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link href="style/style.css" rel="stylesheet" type="text/css" />
</head>

<body id="register">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="col-md-12 box">
                    <img src="" alt="">
                    <br>
                    <h2>Daftar Akun</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="hidden" name="token" value="<?php echo $token ?>">
                        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo   $rawusername; ?>">
                            <span class="help-block"><?php echo $username_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                            <span class="help-block"><?php echo $password_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                            <label>Konfirmasi Password</label>
                            <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirmPassword; ?>">
                            <span class="help-block"><?php echo $confirm_password_err; ?></span>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Daftar">
                            <input type="reset" class="btn btn-default" value="Reset">
                        </div>
                        <p>Sudah mempunyai akun? <a href="login.php">Login disini</a>.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>