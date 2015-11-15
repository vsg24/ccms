<?php
Sessions::checkLogStatusForSite();
if(getCurrentUser() == '')
{
    doCache();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <?php //session_start(); ?>
    <base href="/">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title><?php echo makeSiteTitle(); ?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo THEME_BASE; ?>css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?php echo THEME_BASE; ?>css/main.css" rel="stylesheet">
    <link href="<?php echo THEME_BASE; ?>css/carousel.css" rel="stylesheet">
    <!-- Fonts for this template -->
    <link href="<?php echo THEME_BASE; ?>css/materialdesignicons.min.css" rel="stylesheet">
	<!-- Github Ribbons -->
	<link href="<?php echo THEME_BASE; ?>css/gh-fork-ribbon.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="<?php echo THEME_BASE; ?>js/jquery.min.js"></script>
    <!-- jQuery UI -->
    <script src="<?php echo THEME_BASE; ?>js/jquery-ui.min.js"></script>
    <!-- Bootstrap js -->
    <script src="<?php echo THEME_BASE; ?>js/bootstrap.min.js"></script>
    <style>
        #logo {
            display: inherit;
        }
		.github-fork-ribbon {
            background-color: #ffe100;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div style="text-align: center;" class="col-xs-12 col-sm-12 col-md-4">
    </div>
    <div style="text-align: center;" class="col-xs-12 col-sm-12 col-md-4">
        <a href="<?php echo SITE_DOMAIN; ?>"><img id="logo" src="<?php echo THEME_BASE; ?>images/ccms.png" alt="ccms" class="img-responsive"></a>
    </div>
    <div style="text-align: center;" class="col-xs-12 col-sm-12 col-md-4">
    </div>
    <div style="margin-right: 50px; text-align: right" class="row visible-md visible-lg">
        <ul class="c_nav">
            <li><a class="btn btn-default" href="<?php echo SITE_DOMAIN; ?>"><i class="mdi mdi-home"></i>&nbsp;Home</a></li>
            <li><a class="btn btn-default" href="<?php echo SITE_DOMAIN; ?>docs"><i class="mdi mdi-file"></i>&nbsp;Docs</a></li>
            <li><a class="btn btn-default" href="#new_user"><i class="mdi mdi-puzzle"></i>&nbsp;Contents</a></li>
            <?php if(getCurrentUser() != '') : ?><li><a rel="nofollow" class="btn btn-warning" href="c_admin"><i class="mdi mdi-account"></i>&nbsp;<?php echo getCurrentUser('username'); ?></a></li><?php endif; ?>
        </ul>
    </div>
    <div class="row visible-xs visible-sm" style="text-align: center">
        <ul class="mobile_nav">
            <li id="first_menu"><a class="btn btn-default" href="/"><i class="mdi mdi-home"></i>&nbsp;Home</a></li>
            <li id="first_menu"><a class="btn btn-default" href="#info"><i class="mdi mdi-file"></i>&nbsp;Docs</a></li>
            <li><a class="btn btn-default" href="#new_user"><i class="mdi mdi-puzzle"></i>&nbsp;Contents</a></li>
            <?php if(getCurrentUser() != '') : ?><li><a rel="nofollow" class="btn btn-warning" href="c_admin"><i class="mdi mdi-account"></i>&nbsp;<?php echo getCurrentUser('username'); ?></a></li><?php endif; ?>
        </ul>
    </div>
	    <!-- TOP RIGHT RIBBON: START COPYING HERE -->
    <div class="github-fork-ribbon-wrapper left">
        <div class="github-fork-ribbon">
            <a href="https://github.com/vsg24/ccms" target="_blank">Fork me on GitHub</a>
        </div>
    </div>
    <!-- TOP RIGHT RIBBON: END COPYING HERE -->
    <br>