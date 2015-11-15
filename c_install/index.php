<?php require_once 'header.php'; ?>

<?php

function alreadyInstalled()
{
    if(isset($_SESSION['force_install'])) return 0;

    $query = "SELECT option_value FROM c_options WHERE option_name = 'installation_date'";
    $installation_date = MySQL::open_conn()->query($query)->fetch_assoc()['option_value'];

    if($installation_date != 'None')
    {
        $query = "SELECT option_value FROM c_options WHERE option_name = 'version'";
        $version = MySQL::open_conn()->query($query)->fetch_assoc()['option_value'];
        $_SESSION['date_installed'] = $installation_date;
        $_SESSION['version_installed'] = $version;
        ob_end_clean();
        redirectTo('warning.php');
    }
}

//alreadyInstalled();

function gotoInstallIndex()
{
    ob_end_clean();
    redirectTo('index.php');
}

$site_domain = SITE_DOMAIN;
if(isset($_POST['check_config']))
{
    @$db = new mysqli(DB_LOC, DB_USER, DB_PASS, DB_NAME);
    if($db->connect_errno)
    {
        $html =<<<HTML
<div class="col-xs-12 col-sm-12 col-md-6 co-lg-6 col-md-offset-2 div_white_left">
            <div style="text-align: center; padding: 5%;">
                <i style="vertical-align: middle;" class="mdi mdi-server-network-off"></i>&nbsp;Connection is <span style="color: red; font-weight: bold;">FAULTY</span><br>
                Database connection could not be established.<br>
                <br>
                <span style="color: red;">Error:</span> $db->connect_error
            </div>
            <div>
            Please click 'Back' and review your settings.
                <a style="float: right" href="{$site_domain}c_install/index.php?switch=1" class="btn">Back</a>
            </div>
            <br>
</div>
HTML;
        echo $html;
        goto end;
    }
    else
    {
        require_once 'import_db.php';
        importDatabase();
    }
}

if(isset($_POST['admin_submit']) && isset($_SESSION['hash']))
{
    $hash = $_SESSION['hash'];
    $result = Users::submitNewUser($_POST['admin_username'], $_POST['admin_password'], $_POST['admin_email'], $_POST['admin_password2']);
    if($result == 0)
    {
        $username = $_POST['admin_username'];
        $_SESSION['admin'] = $_POST['admin_username'];
        $query = "UPDATE c_users SET user_role = 4, activate = NULL WHERE user_login = '$username' LIMIT 1";
        $conn = MySQL::open_conn();
        $res = $conn->query($query);
        if($res)
        {
            redirectTo('index.php?switch=3&hash=' . $_SESSION['hash']);
        }
        else
        {
            echo 'FATAL ERROR. CAN NOT CONTINUE. INSTALL ccms MANUALLY';
        }
    }
    else
    {
        if($result == 1)
        {
            $error = 'Username or Password does not meet the requirements.';
        }
        elseif($result == 2)
        {
            $error = 'Username already exist.';
        }
        elseif($result == 3)
        {
            $error = 'Unknown error while creating new user.';
        }
        elseif($result == 4)
        {
            $error = 'Passwords do not match';
        }
        else
        {
            $error = "Known error no $result. This shouldn't have happened! Please make sure you are not using a damaged sql dump.";
        }
        $html =<<<HTML
<div class="col-xs-12 col-sm-12 col-md-6 co-lg-6 col-md-offset-2 div_white_left">
            <div style="text-align: center; padding: 5%;">
                Couldn't create admin account.<br>
                <br>
                <span style="color: red;">Error:</span> $error
            </div>
            <div>
            Please click 'Back' and review your settings.
                <a style="float: right" href="{$site_domain}c_install/index.php?switch=2&hash=$hash" class="btn">Back</a>
            </div>
            <br>
</div>
HTML;
        echo $html;
        goto end;
    }
}

if(isset($_POST['options_submit']) && isset($_SESSION['hash']))
{
    $hash = $_SESSION['hash'];
    $conn = MySQL::open_conn();

    if(isset($_POST['can_comment']))
    {
        $can_comment = $_POST['can_comment'];
        $query = "UPDATE c_options SET option_value = '$can_comment' WHERE option_name = 'can_comment'";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);
    }

    if(isset($_POST['max_post_pp']))
    {
        $max_post_pp = $_POST['max_post_pp'];
        $query = "UPDATE c_options SET option_value = '$max_post_pp' WHERE option_name = 'max_post_pp'";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);
    }

    if(isset($_POST['site_title']))
    {
        $title = $_POST['site_title'];
        $title = $conn->real_escape_string($title);
        $query = "UPDATE c_options SET option_value = '$title' WHERE option_name = 'site_title'";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);
    }

    {
        if (isset($_POST['register_open'])) {
            $register_status = 'yes';
        } else {
            $register_status = 'no';
        }

        $query = "UPDATE c_options SET option_value = '$register_status' WHERE option_name = 'register_open'";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);

        //

        if (isset($_POST['conf_mail'])) {
            $conf_mail = 'yes';
        } else {
            $conf_mail = 'no';
        }

        $query = "UPDATE c_options SET option_value = '$conf_mail' WHERE option_name = 'send_conf_mail'";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);

        $datetime = jDateTime::date('Y-m-d', false, null, false);
        $query = "UPDATE c_options SET option_value = '$datetime' WHERE option_name = 'installation_date'";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);
    }
    ob_end_clean();
    redirectTo('index.php?switch=4&hash=' . $hash);
}
?>
    <?php if(!isset($_GET['switch'])) : ?>
        <div class="col-xs-12 col-sm-12 col-md-6 co-lg-6 col-md-offset-2 div_white_left">
            <div style="text-align: center; padding: 5%;">
                Welcome to ccms installation script.<br>Just a few steps and your website will be online.
                <br><br>
                Please <strong>do not</strong> refresh any page or use back button on your browser during installation.
            </div>
            <div>
                Click 'Next' to continue.
                <a style="float: right" href="<?php echo SITE_DOMAIN; ?>c_install/index.php?switch=1" class="btn">Next</a>
            </div>
            <br>
        </div>
    <?php elseif($_GET['switch'] == '1') : ?>
    <div class="col-xs-12 col-sm-12 col-md-6 co-lg-6 col-md-offset-2 div_white_left">
        <?php $conn = MySQL::open_conn(); $query = "SHOW TABLES FROM " . DB_NAME; $res = $conn->query($query)->num_rows; if($res <= 0) : ?>
        <div style="padding: 5%">
            Please open file 'c_config.php' included in root of your installation directory, with a text editor.<br>
            You need to enter (edit) values for:
            <br><br>
            <ul>
            <li><strong>SUBDIR</strong></li>If your site is located in a subfolder of root, enter the folder(s) name followed by a slash (/). Leave empty if site is not in a sub directory. Sub directory is different than sub domain! For sub domain leave empty.
            <li><strong>date_default_timezone_set</strong></li>Edit the default value based on your time zone. supported time zones in <em>http://php.net/timezones</em>
            <li><strong>DB_NAME</strong></li>Name of the MySQL database you want to use for ccms.
            <li><strong>DB_LOC</strong></li>Address of MySQL server, This should be provided by your host. Don't change if not sure.
            <li><strong>DB_USER</strong></li>Username of database
            <li><strong>DB_PASS</strong></li>Password of database
            </ul>
            <br><br>
            When done editing c_config.php, save and close it, then click 'Check Data' to continue. This can take 10-40 seconds. Refresh page if it took longer that 40 seconds or if any error showed up.
        </div>
        <div>
            <form action="" method="POST">
            <input type="submit" style="float: right" class="btn" id="check_data" name="check_config" value="Check Data">
            <div style="float: right; margin: 20px;" id="spinner"></div>
            </form>
            <script>
                var opts = {
                    lines: 7 // The number of lines to draw
                    , length: 27 // The length of each line
                    , width: 14 // The line thickness
                    , radius: 32 // The radius of the inner circle
                    , scale: 0.20 // Scales overall size of the spinner
                    , corners: 1 // Corner roundness (0..1)
                    , color: '#000' // #rgb or #rrggbb or array of colors
                    , opacity: 0.25 // Opacity of the lines
                    , rotate: 0 // The rotation offset
                    , direction: 1 // 1: clockwise, -1: counterclockwise
                    , speed: 1.50 // Rounds per second
                    , trail: 60 // Afterglow percentage
                    , fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
                    , zIndex: 2e9 // The z-index (defaults to 2000000000)
                    , className: 'spinner' // The CSS class to assign to the spinner
                    , top: '50%' // Top position relative to parent
                    , left: '50%' // Left position relative to parent
                    , shadow: false // Whether to render a shadow
                    , hwaccel: false // Whether to use hardware acceleration
                    , position: 'relative' // Element positioning
                };
                var target = document.getElementById('spinner');
                $('#check_data').click(function() {
                    var spinner = new Spinner(opts).spin(target);
                });
            </script>
            <br>
        </div>
        <br>
    </div>
        <?php else : ?>
        <?php
        if(isset($_SESSION['db_import_started']))
        {
            if($conn = MySQL::open_conn())
            {
                $hash = md5(DB_LOC . DB_NAME);
                $_SESSION['hash'] = $hash;
                $site_domain = SITE_DOMAIN;
                $query = "SHOW TABLES FROM " . DB_NAME;
                $res = $conn->query($query)->num_rows;
                if($res > 0)
                {
                    $html =<<<HTML
                    <br>
ccms Database was successfully imported.<br><br>
            <div>
                Click 'Next' to continue.
                <a style="float: right" href="{$site_domain}c_install/index.php?switch=2&hash=$hash" class="btn">Next</a>
            </div><br>
HTML;
                    echo $html;
                }
                else
                    gotoInstallIndex();
            }
        }
        ?>
        <?php endif; ?>
    <?php elseif($_GET['switch'] == '2' && isset($_GET['hash']) && $_GET['hash'] == $_SESSION['hash']) : ?>
    <div class="col-xs-12 col-sm-12 col-md-6 co-lg-6 col-md-offset-2 div_white_left">
        <div style="padding: 5%">
            <div style="text-align: center"><i style="vertical-align: middle;" class="mdi mdi-server-network"></i>&nbsp;Connection is <span style="color: green; font-weight: bold;">OK</span></div><br>
            Congratulations! Database connection was successfully established.<br>
            Now create an account for admin:
            <br><br>
            <form action="" method="POST">
                <label for="username">Username:</label>
                <input style="float: right" id="username" type="text" name="admin_username" required><br><br><br>
                <label for="password">Password:</label>
                <input style="float: right" id="password" type="password" name="admin_password" required><br><br><br>
                <label for="password2">Repeat Password:</label>
                <input style="float: right" id="password2" type="password" name="admin_password2" required><hr>
                <label for="email">Email:</label>
                <input style="float: right" id="email" type="email" name="admin_email" required><hr>
                <input type="submit" style="float: right" class="btn btn-info" name="admin_submit" value="Create Admin">
            </form>
        </div>
        <br>
    </div>
    <?php elseif($_GET['switch'] == '3' && isset($_GET['hash']) && $_GET['hash'] == $_SESSION['hash'] && isset($_SESSION['admin'])) : ?>
    <div class="col-xs-12 col-sm-12 col-md-6 co-lg-6 col-md-offset-2 div_white_left">
        <div style="padding: 5%">
            That's it <?php echo $_SESSION['admin']; ?>!<br>
            Now let's set some basic options:
            <br><br>
            <form action="" method="POST">
                <label for="can_comment" style="float: <?php echo getLBA(); ?>;"><?php _e('can_comment', '', ':'); ?></label>&nbsp
                <select name="can_comment" id="can_comment" style="float: <?php echo getLBA_rev(); ?>;">
                    <option value="anyone" selected="selected"><?php _e('anyone'); ?></option>
                    <option value="users"><?php _e('users'); ?></option>
                </select>
                <br><br>
                <label for="max_post_pp" style="float: <?php echo getLBA(); ?>;"><?php _e('post_pp', '', ':'); ?></label>
                <input type="number" name="max_post_pp" id="max_post_pp" style="float: <?php echo getLBA_rev(); ?>" value="6" required><br><br>
                <label for="title" style="float: <?php echo getLBA(); ?>;"><?php _e('title', '', ':'); ?></label>
                <input dir="auto" type="text" name="site_title" id="title" class="form_block" value="My CCMS website" required>
                <br>
                <label for="register" style="float: <?php echo getLBA(); ?>;"><?php _e('register_open', '', ':'); ?></label>&nbsp<input type="checkbox" name="register_open" checked style="float: <?php echo getLBA_rev(); ?>;" id="register"><br><br>
                <label id="conf_mail_label" for="conf_mail" style="float: <?php echo getLBA(); ?>;"><?php _e('send_conf_mail', '', ':'); ?></label>&nbsp<input type="checkbox" name="conf_mail" style="float: <?php echo getLBA_rev(); ?>;" id="conf_mail"><br><br>
                <input style="float: right" class="btn btn-pagination" type="submit" name="options_submit" value="Finish">
            </form>
        </div>
        <br>
    </div>
    <?php elseif($_GET['switch'] == '4' && isset($_GET['hash']) && $_GET['hash'] == $_SESSION['hash'] && isset($_SESSION['admin'])) : ?>
    <div class="col-xs-12 col-sm-12 col-md-6 co-lg-6 col-md-offset-2 div_white_left">
        <div style="padding: 5%">
            Congrats! ccms is now set and ready.<br>
            Please <strong>Remove</strong> 'c_install' folder as soon as possible. Leaving it untouched can cause serious security issues.<hr>
            You may access admin area from <a href="<?php echo SITE_DOMAIN . 'c_admin'; ?>">here</a>.<br><br>

            Should you have any question, visit us at <a href="http://ccms.vahida.ir/" target="_blank">ccms</a>
            <br>
        </div>
        <br>
    </div>
    <?php elseif($_GET['switch'] == 'checkdb') : ?>
    <div class="col-xs-12 col-sm-12 col-md-6 co-lg-6 col-md-offset-2 div_white_left">
        <div style="padding: 5%">
            <?php
            if($conn = MySQL::open_conn())
            {
                $query = "SHOW TABLES FROM " . DB_NAME;
                $res = $conn->query($query)->num_rows;
                if($res > 0)
                {
                    $html =<<<HTML
ccms Database was successfully imported.<br><br>
            <div>
                Click 'Next' to continue.
                <a style="float: right" href="<?php echo SITE_DOMAIN; ?>c_install/index.php?switch=2&hash=$hash" class="btn">Next</a>
            </div>
HTML;

                }
                else
                    gotoInstallIndex();
            }
            ?>
        </div>
        <br>
    </div>
    <?php else : gotoInstallIndex(); ?>
    <?php endif; ?>
<?php end: include_once 'footer.php'; ?>