<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login.php");
  exit;
}
require "api/CryptographyFunction.php";
if (empty($_SESSION['ajaxToken'])) {
  $_SESSION['ajaxToken'] = bin2hex(random_bytes(16));
}
$token = encryptAes($aesKey, base64_encode($_SESSION['ajaxToken']) . "%" . base64_encode($_SESSION["username"]) . "%" . base64_encode($_SESSION["loggedin"]));


require "api/DatabaseController.php";
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

  <meta name="token" content="<?php echo $token ?>" />
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
  <link href="style/style.css" rel="stylesheet" type="text/css" />
  <!-- Scrollbar Custom CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css" />

  <!-- Font Awesome JS -->
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
</head>

<body id="dashboard">
  <div class="wrapper">
    <!-- Sidebar  -->
    <!-- Sidebar  -->
    <nav id="sidebar">
      <ul class="list-unstyled components">
        <li>
          <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <i class="fas fa-home"></i>
            Home</a>
          <ul class="collapse list-unstyled" id="homeSubmenu">
            <li>
              <a href="#">Home 1</a>
            </li>
            <li>
              <a href="#">Home 2</a>
            </li>
            <li>
              <a href="#">Home 3</a>
            </li>
          </ul>
        </li>
        <li>
          <a href="#"><i class="fas fa-briefcase"></i> About</a>
        </li>
        <li>
          <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fas fa-copy"></i> Pages</a>
          <ul class="collapse list-unstyled" id="pageSubmenu">
            <li>
              <a href="#">Page 1</a>
            </li>
            <li>
              <a href="#">Page 2</a>
            </li>
            <li>
              <a href="#">Page 3</a>
            </li>
          </ul>
        </li>
        <li>
          <a href="#"><i class="fas fa-image"></i> Portfolio</a>
        </li>
        <li>
          <a href="#"><i class="fas fa-paper-plane"></i> Contact</a>
        </li>
      </ul>
    </nav>
    <nav class="navbar nav-fill w-100 navbar-dark fixed-top">
      <div class="container-fluid d-flex justify-content-between">
        <button type="button" id="topnavButton" class="sidebarCollapse btn btn-info">
          <i class="fas fa-align-justify icon active open"></i>
          <i class="fas fa-times icon close"></i>
        </button>
        <a href="#">
          <img alt="Otoma" src="img/logo/otoma_withtext_small.png">
        </a>
        <a href="logout.php">
          <button type="button" id="topnavButton" class="btn btn-info">
            <i class="fas fa-sign-out-alt"></i>
          </button>
        </a>

      </div>
    </nav>
    <!-- Page Content  -->
    <div id="content">
      <input type="text" id="mydatepicker" />
      <?php
      if ($deviceBelonging) {
        if ($deviceBelongingType == "main") {
          //require "pagecon/main-device.php";
        }
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
      <div class="row d-flex justify-content-center">
        <div class="col-12 text-center">
          <div class="text-muted">Icons made by <a href="https://www.flaticon.com/authors/srip" title="srip">srip</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
        </div>
      </div>
    </div>
  </div>
  <div class="overlay"></div>
  <!--Bottom Footer-->
  <!--Bottom Footer-->

  <!-- jQuery CDN - Slim version (=without AJAX) -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <!-- Popper.JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  <!-- jQuery Custom Scroller CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
  <!-- Chart.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js" integrity="sha512-G8JE1Xbr0egZE5gNGyUm1fF764iHVfRXshIoUWCTPAbKkkItp/6qal5YAHXrxEu4HNfPTQs6HOu3D5vCGS1j3w==" crossorigin="anonymous"></script>

  <script src="js/dashboard.js"></script>
</body>

</html>