<?php ob_start(); require_once '../c_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <?php session_start(); ?>
    <base href="/">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>CCMS - New Install</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo SITE_DOMAIN; ?>c_install/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?php echo SITE_DOMAIN; ?>c_install/css/main.css" rel="stylesheet">
    <!-- Fonts for this template -->
    <link href="<?php echo SITE_DOMAIN; ?>c_install/css/materialdesignicons.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="<?php echo SITE_DOMAIN; ?>c_install/js/jquery.min.js"></script>
    <!-- Bootstrap js -->
    <script src="<?php echo SITE_DOMAIN; ?>c_install/js/bootstrap.min.js"></script>
    <!-- Spin js -->
    <script src="<?php echo SITE_DOMAIN; ?>c_install/js/spin.min.js"></script>
    <style>
        li {
            list-style-type: none;
            padding: 5px;
        }
        a { color: inherit; }
    </style>
</head>
<body>
<header>
    <br>
    <div style="text-align: center">
        <img src="<?php echo THEME_BASE; ?>images/ccms.png" height="180">
    </div>
    <hr><br>
</header>
<?php
function getHash()
{
    if(isset($_SESSION['hash']))
    {
        return '&hash=' . $_SESSION['hash'];
    }
    return null;
}
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-2 co-lg-2">
            <ul>
                <li><?php if(!isset($_GET['switch'])) echo '<i style="color: blueviolet; vertical-align: middle;" class="mdi mdi-arrow-right"></i>'; elseif($_GET['switch'] == '1' || $_GET['switch'] == '2' || $_GET['switch'] == '3' || $_GET['switch'] == '4') echo '<i style="color: green; vertical-align: middle;" class="mdi mdi-check"></i>'; ?><a href="<?php echo SITE_DOMAIN . 'c_install/' ?>">Welcome</a></li>
                <li><?php if(isset($_GET['switch']) && $_GET['switch'] == '1') echo '<i style="color: blueviolet; vertical-align: middle;" class="mdi mdi-arrow-right"></i>'; elseif(isset($_GET['switch']) && $_GET['switch'] == '2' || isset($_GET['switch']) && $_GET['switch'] == '3' || isset($_GET['switch']) && $_GET['switch'] == '4') echo '<i style="color: green; vertical-align: middle;" class="mdi mdi-check"></i>'; ?><a href="<?php if(getHash()!=null) echo SITE_DOMAIN . 'c_install/index.php?switch=1' ?>">Database Info</a></li>
                <li><?php if(isset($_GET['switch']) && $_GET['switch'] == '2') echo '<i style="color: blueviolet; vertical-align: middle;" class="mdi mdi-arrow-right"></i>'; elseif(isset($_GET['switch']) && $_GET['switch'] == '3' || isset($_GET['switch']) && $_GET['switch'] == '4') echo '<i style="color: green; vertical-align: middle;" class="mdi mdi-check"></i>'; ?><a href="<?php if(getHash()!=null) echo SITE_DOMAIN . 'c_install/index.php?switch=2' . getHash() ?>">Admin Info</a></li>
                <li><?php if(isset($_GET['switch']) && $_GET['switch'] == '3') echo '<i style="color: blueviolet; vertical-align: middle;" class="mdi mdi-arrow-right"></i>'; elseif(isset($_GET['switch']) && $_GET['switch'] == '4') echo '<i style="color: green; vertical-align: middle;" class="mdi mdi-check"></i>'; ?><a href="<?php if(getHash()!=null) echo SITE_DOMAIN . 'c_install/index.php?switch=3' . getHash() ?>">Settings</a></li>
                <li><?php if(isset($_GET['switch']) && $_GET['switch'] == '4') echo '<i style="color: green; vertical-align: middle;" class="mdi mdi-check"></i>'; ?><a href="<?php echo SITE_DOMAIN . 'c_install/index.php?switch=4' . getHash() ?>">Finish</a></li>
            </ul>
        </div>