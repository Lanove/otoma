<?php
// https://phpsecurity.readthedocs.io/en/latest/Cross-Site-Scripting-(XSS).html
require "nocache.php";

// Initialize the session
require_once "api/authCookieSessionValidate.php";

if (!$isLoggedIn) {
  $util->redirect("login.php");
}

require "api/CryptographyFunction.php";
if (empty($_SESSION['ajaxToken'])) {
  $_SESSION['ajaxToken'] = bin2hex(random_bytes(16));
}
$token = encryptAes($aesKey, base64_encode($_SESSION['ajaxToken']) . "%" . base64_encode($_SESSION["username"]) . "%" . base64_encode($_SESSION["loggedin"]));

$dbHandler = new DatabaseController(); // Open database

$fetchResult = $dbHandler->runQuery("SELECT deviceType,bondKey FROM bond WHERE username = :name;", ["name" => $_SESSION["username"]]);
if ($fetchResult) {
  $deviceBelonging = true;
  $deviceBelongingType = $fetchResult["deviceType"];
} else {
  $deviceBelonging = false;
}
?>

<!DOCTYPE html>
<html>

<head>

  <meta name="token" content="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>" />
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

  <title>Otoma</title>

  <!-- Bootstrap CSS CDN -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous" />

  <!-- Our Custom CSS -->
  <link rel="stylesheet" type="text/css" href="style/style.css?<?php echo date('l jS \of F Y h:i:s A'); ?>" />
  <!-- Scrollbar Custom CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css" />
</head>

<body id="dashboard">

  <div class="absolute-overlay">
    <div class="myLoader">
      <div class="loader quantum-spinner">

      </div>
    </div>
    <div class="right section"></div>
    <div class="left section"></div>
  </div>
  <div class="wrapper">
    <!-- Sidebar  -->
    <!-- Sidebar  -->
    <nav id="sidebar">
      <ul class="list-unstyled components">
        <li>
          <a href="#" id="goHome">
            <i class="fas fa-home"></i>
            Home</a>
        </li>
        <li>
          <a href="#"><i class="fas fa-user-circle"></i> Akun</a>
        </li>
        <li>
          <a href="#"><i class="fas fa-question-circle"></i> Bantuan</a>
        </li>
        <li>
          <a href="#" id="contactUs"><i class="fas fa-paper-plane"></i> Kontak Kami</a>
        </li>
      </ul>
    </nav>
    <nav class="navbar nav-fill w-100 navbar-dark fixed-top">
      <div class="container-fluid d-flex justify-content-between">
        <button type="button" id="topnavButton" class="sidebarCollapse btn btn-info">
          <i class="fas fa-align-justify icone active open"></i>
          <i class="fas fa-times icone close"></i>
        </button>
        <a href="#" id="otomaIcon">
          <img alt="Otoma" src="img/logo/otoma_withtext.png" height="42">
        </a>
        <a href="logout.php">
          <button type="button" id="topnavButton" class="btn btn-info">
            <i class="fas fa-sign-out-alt"></i>
          </button>
        </a>

      </div>
    </nav>
    <!-- Page Content  -->

    <div id="bondContainer" value=""></div>
    <div id="content">
      <?php
      // require "pagecon/nexus-settings.php";
      if ($deviceBelonging) {
        if ($deviceBelongingType == "main")
          require "pagecon/main-device.php";
        else if ($deviceBelongingType == "nexus")
          require "pagecon/nexus-device.php";
      } else {
        require "pagecon/no-device-found.php";
      }
      ?>
    </div>
    <div class="container-fluid footer justify-content-center align-items-center">
      <div class="row d-flex justify-content-center">
        <div class="col-12 text-center">
          <span class="text-muted">© 2020</span>
          <a href="#0">Eldi4</a>
          <span class="text-muted">All rights reserved</span>
        </div>
      </div>
    </div>
  </div>
  <div class="overlay">
  </div>
  <!--Bottom Footer-->
  <!--Bottom Footer-->

  <!-- jQuery CDN -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <!-- Popper.JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  <!-- jQuery Custom Scroller CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
  <!-- Font Awesome JS -->
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
  <!-- echarts.js -->
  <script type="text/javascript" src="js/echarts.min.js"></script>
  <!-- Anypicker -->
  <link rel="stylesheet" type="text/css" href="style/anypicker-font.css?<?php echo date('l jS \of F Y h:i:s A'); ?>" />
  <link rel="stylesheet" type="text/css" href="style/anypicker.css?<?php echo date('l jS \of F Y h:i:s A'); ?>" />
  <script type="text/javascript" src="js/anypicker.js"></script>
  <script type="text/javascript" src="js/i18n/anypicker-i18n-id.js"></script>
  <!-- Bootbox -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js" integrity="sha512-8vfyGnaOX2EeMypNMptU+MwwK206Jk1I/tMQV4NkhOz+W8glENoMhGyU6n/6VgQUhQcJH8NqQgHhMtZjJJBv3A==" crossorigin="anonymous"></script>

  <script src="js/dashboard.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
</body>

</html>