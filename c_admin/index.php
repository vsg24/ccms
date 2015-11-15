<?php ob_start(); session_start(); require_once "../c_config.php"; require_once "tools/RandomColor.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>ccms Admin</title>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">

    <!-- Custom styles for userbar -->
    <link href="css/cuserbar.css" rel="stylesheet">

    <!-- Material Icons -->
    <link href="css/materialdesignicons.min.css" rel="stylesheet">
    <style>
        .navbar-toggle {
            padding: 5px;
            padding-bottom: inherit;
            padding-top: inherit;
        }
    </style>
</head>
<body>

<?php
$sessions = new Sessions();
$sessions->checkLogStatusPlusCookies();
?>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" onclick="window.location.assign('<?php echo SITE_DOMAIN ?>');" aria-controls="navbar">
                <i class="mdi mdi-home"></i>
            </button>
            <button type="button" class="navbar-toggle collapsed" onclick="window.location.assign('index.php');" aria-controls="navbar">
                <i class="mdi mdi-view-dashboard"></i>
            </button>
            <button type="button" class="navbar-toggle collapsed" onclick="window.location.assign('?switch=settings');" aria-controls="navbar">
                <i class="mdi mdi-settings"></i>
            </button>
            <button type="button" class="navbar-toggle collapsed" onclick="window.location.assign('?switch=updates');" aria-controls="navbar">
                <i class="mdi mdi-alert-circle"></i>
            </button>
            <a class="navbar-brand" href="#" style="color: yellow;">ccms <span style="color: white;">Admin</span></a>
        </div>
        <div id="cssmenu" class="navbar-collapse collapse" style="float: right;">
            <ul class="dropdown nav navbar-nav navbar-<?php echo getLBA() ?>">

                <li><a href="#"><?php _e('help'); ?></a></li>
                <li <?php if(isset($_GET['switch']) && $_GET['switch'] == 'settings') echo 'class="active"'; ?>><a href='index.php?switch=settings'><?php _e('settings'); ?></a></li>
                <li <?php if(!isset($_GET['switch'])) echo 'class="active"'; ?>><a href='index.php'><?php _e('dashboard'); ?></a></li>
                <li class='last'><a href='#'><?php if(getLBA() == 'right') echo Users::getUsernameBySeassion(); _e('profile', '', ' : '); if(getLBA() == 'left') echo Users::getUsernameBySeassion(); ?>&nbsp;<span style="color: #FFC107;" class="caret"></span></a>
                    <ul>
                        <li><a style="text-align: center;" href="?switch=logout"><span><?php _e('logout'); ?></span></a></li>
                        <li><a style="text-align: center;" href="?switch=users&sub=edit_user&id=<?php echo $_SESSION['user_id']; ?>"><span><?php _e('edit'); ?></span></a></li>
                    </ul>
                </li>

            </ul>
        </div>
</nav>
<style>
    body > div > div > div.col-sm-3.col-md-2.sidebar > ul > li.active > a > i, body > div > div > div.col-sm-3.col-md-2.sidebar > ul > li.active > a  {
        color: red !important;
    }
</style>

<?php
if(!isset($_GET['switch'])) {$i = 1; $curr_loc = _e('dashboard', '', '', true);}
if(isset($_GET['switch']) && ($_GET['switch'])=='new_post') {$i = 2; $curr_loc = _e('new_post', '', '', true);}
if(isset($_GET['switch']) && ($_GET['switch'])=='manage_posts') {$i = 3; $curr_loc = _e('post_management', '', '', true);}
if(isset($_GET['switch']) && ($_GET['switch'])=='categories') {$i = 4; $curr_loc = _e('categories', '', '', true);}
if(isset($_GET['switch']) && ($_GET['switch'])=='comments') {$i = 5; $curr_loc = _e('comments', '', '', true);}
if(isset($_GET['switch']) && ($_GET['switch'])=='settings') {$i = 6; $curr_loc = _e('settings', '', '', true);}
if(isset($_GET['switch']) && ($_GET['switch'])=='appearance') {$i = 7; $curr_loc = _e('appearance', '', '', true);}
if(isset($_GET['switch']) && ($_GET['switch'])=='users') {$i = 8; $curr_loc = _e('users', '', '', true);}
if(isset($_GET['switch']) && ($_GET['switch'])=='vip') {$i = 9; $curr_loc = _e('vip_section', '', '', true);}
if(isset($_GET['switch']) && ($_GET['switch'])=='utilities') {$i = 10; $curr_loc = _e('utilities', '', '', true);}
if(isset($_GET['switch']) && ($_GET['switch'])=='updates') {$i = 11; $curr_loc = _e('updates', '', '', true);}
if(isset($_GET['switch']) && ($_GET['switch'])=='error') {$i = 0; $curr_loc = _e('error', '', '', true);}
?>

<div class="container-fluid" id="nav-left" align="<?php echo getLBA(); ?>">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <?php $ic_style = 'style="float: ' . getLBA() . '; line-height: inherit; margin-' . getLBA_rev() . ': 8px; color: yellow;"' ?>
                <li <?php if($i == 1) echo 'class="active"'; ?>><a href="index.php"><i <?php echo $ic_style ?> class="mdi mdi-view-dashboard"></i><?php _e('dashboard'); ?></a></li>
                <li><a href="../"><i <?php echo $ic_style ?> class="mdi mdi-home"></i><?php _e('site_home'); ?></a></li>
                <li <?php if($i == 2) echo 'class="active"'; ?>><a href="?switch=new_post"><i <?php echo $ic_style ?> class="mdi mdi-note-text"></i><?php _e('new_post'); ?></a></li>
                <li <?php if($i == 3) echo 'class="active"'; ?>><a href="?switch=manage_posts"><i <?php echo $ic_style ?> class="mdi mdi-file-multiple"></i><?php _e('post_management'); ?></a></li>
                <li <?php if($i == 4) echo 'class="active"'; ?>><a href="?switch=categories"><i <?php echo $ic_style ?> class="mdi mdi-database"></i><?php _e('categories'); ?></a></li>
                <li <?php if($i == 5) echo 'class="active"'; ?>><a href="?switch=comments"><i <?php echo $ic_style ?> class="mdi mdi-forum"></i><?php _e('comments'); ?></a></li>
            </ul><br>
            <ul class="nav nav-sidebar">
                <li <?php if($i == 6) echo 'class="active"'; ?>><a href="?switch=settings"><i <?php echo $ic_style ?> class="mdi mdi-settings"></i><?php _e('settings'); ?></a></li>
                <li <?php if($i == 7) echo 'class="active"'; ?>><a href="?switch=appearance"><i <?php echo $ic_style ?> class="mdi mdi-palette"></i><?php _e('appearance'); ?></a></li>
                <li <?php if($i == 8) echo 'class="active"'; ?>><a href="?switch=users"><i <?php echo $ic_style ?> class="mdi mdi-account-multiple"></i><?php _e('users'); ?></a></li>
                <li <?php if($i == 9) echo 'class="active"'; ?>><a href="?switch=vip"><i <?php echo $ic_style ?> class="mdi mdi-account-star-variant"></i><?php _e('vip_section'); ?></a></li>
                <li <?php if($i == 10) echo 'class="active"'; ?>><a href="?switch=utilities"><i <?php echo $ic_style ?> class="mdi mdi-cube"></i><?php _e('utilities'); ?></a></li>
            </ul>
            <div align="left" style="margin-top: 10%;">
                <span style="/*position: fixed; */color: #fff; text-align: left !important;"><strong>ccms v<?php echo getVersion(); ?></strong></span><br><span><a style="color: yellow;" href="?switch=updates"><?php if(isUpdateAvailable() == 1) _e('regu_update_here', '', '!'); elseif(isUpdateAvailable() == 2) _e('crit_update_here', '', '!'); else _e('updates'); ?></a></span>
                <?php if(shouldCache()) : ?><br><span><a style="color: yellow;" href="?switch=clear_cache"><?php _e('clear_cache'); ?></a></span><?php endif; ?>
            </div>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h2 class="page-header"><?php echo $curr_loc; ?></h2>

            <!--<div class="row placeholders">
                <div class="col-xs-6 col-sm-3 placeholder">
                    <img data-src="holder.js/200x200/auto/sky" class="img-responsive" alt="Generic placeholder thumbnail">
                    <h4>Label</h4>
                    <span class="text-muted">Something else</span>
                </div>
                <div class="col-xs-6 col-sm-3 placeholder">
                    <img data-src="holder.js/200x200/auto/vine" class="img-responsive" alt="Generic placeholder thumbnail">
                    <h4>Label</h4>
                    <span class="text-muted">Something else</span>
                </div>
                <div class="col-xs-6 col-sm-3 placeholder">
                    <img data-src="holder.js/200x200/auto/sky" class="img-responsive" alt="Generic placeholder thumbnail">
                    <h4>Label</h4>
                    <span class="text-muted">Something else</span>
                </div>
                <div class="col-xs-6 col-sm-3 placeholder">
                    <img data-src="holder.js/200x200/auto/vine" class="img-responsive" alt="Generic placeholder thumbnail">
                    <h4>Label</h4>
                    <span class="text-muted">Something else</span>
                </div>
            </div>-->

            <div class="row visible-xs" style="text-align: center;">
                <div class="col-xs-3 col-sm-3">
                    <a href="?switch=new_post"><img src="images/note-text.png"></a>
                    <h5><?php _e('new_post'); ?></h5>
                    <!--<span class="text-muted">Something else</span>-->
                </div>
                <div class="col-xs-3 col-sm-3">
                    <a href="?switch=manage_posts"><img src="images/file-multiple.png"></a>
                    <h5><?php _e('post_management'); ?></h5>
                </div>
                <div class="col-xs-3 col-sm-3">
                    <a href="?switch=users"><img src="images/account-multiple.png"></a>
                    <h5><?php _e('users'); ?></h5>
                </div>
                <div class="col-xs-3 col-sm-3">
                    <a href="?switch=vip"><img src="images/account-star-variant.png"></a>
                    <h5><?php _e('vip_section'); ?></h5>
                </div>
            </div>
            <br>

            <?php if($i == 1) : ?>
            <div class="row visible-xs visible-sm" style="text-align: center;">
                <div class="col-xs-3 col-sm-3">
                    <a href="?switch=categories"><img src="images/database.png"></a>
                    <h5><?php _e('categories'); ?></h5>
                    <!--<span class="text-muted">Something else</span>-->
                </div>
                <div class="col-xs-3 col-sm-3">
                    <a href="?switch=comments"><img src="images/forum.png"></a>
                    <h5><?php _e('comments'); ?></h5>
                </div>
                <div class="col-xs-3 col-sm-3">
                    <a href="?switch=appearance"><img src="images/palette.png"></a>
                    <h5><?php _e('appearance'); ?></h5>
                </div>
                <div class="col-xs-3 col-sm-3">
                    <a href="?switch=utilities"><img src="images/cube.png"></a>
                    <h5><?php _e('utilities'); ?></h5>
                </div>
                <span style="float: left; padding-left: 20px; padding-top: 20px;">cCMS v<?php echo getVersion(); ?></span>
            </div>
            <?php endif; ?>

<?php

if(isset($_GET['switch']))
{
    switch($_GET['switch'])
    {
        case 'logout':
            Cookies::unsetCookie();
            Sessions::unsetSession(); // doesn't work?!
            ob_end_clean();
            redirectTo("../");
            break;
        case 'clear_cache':
            clearAllCache();
            redirectTo('.');
            break;
        case 'error':
            require_once 'error.php';
            break;
        case 'updates':
            require_once 'updates.php';
            break;
        case 'new_post':
            require_once 'new_post.php';
            break;
        case 'manage_posts':
            require_once 'manage_posts.php';
            break;
        case 'categories':
            require_once 'categories.php';
            break;
        case 'comments':
            require_once 'comments.php';
            break;
        case 'settings':
            require_once 'settings.php';
            break;
        case 'appearance':
            require_once 'appearance.php';
            break;
        case 'users':
            require_once 'users.php';
            break;
        case 'vip':
            require_once 'vip.php';
            break;
        case 'utilities':
            require_once 'utilities.php';
            break;
        default:
            break;
    }
}
else
    require_once 'main.php';

if(isset($_GET['sub']))
{
    switch($_GET['sub'])
    {
        case 'users_list':
            require_once 'users_list.php';
            break;
        default:
            break;
    }
}

?>
            <br><br>
        </div>
    </div>
</div>
<footer>
</footer>

</body>
</html>
