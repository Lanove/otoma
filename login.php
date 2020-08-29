<?php
// Initialize the session
session_start();
// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: dashboard.php");
    exit;
}
if (empty($_SESSION['logtoken'])) {
    $_SESSION['logtoken'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['logtoken'];

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

require "api/DatabaseController.php";
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
    <link href="style/style.css" rel="stylesheet" type="text/css" />
</head>

<body id="login">
    <div class="container">
        <div id="login-row" class="row justify-content-center">
            <div id="login-column" class="col-md-6">
                <div class="col-md-12 box">
                    <h2>Login Akun</h2>
                    <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="hidden" name="token" value="<?php echo $token ?>">
                        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                            <label id="p">Username :</label>
                            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                            <span class="help-block"><?php echo $username_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <label>Password :</label>
                            <input type="password" name="password" class="form-control">
                            <span class="help-block"><?php echo $password_err; ?></span>
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