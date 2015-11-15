<?php
if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}

// if $_SESSION['update_check'] != false then there is an update available, if $_SESSION['update_check'] == 1 it's a normal one, if $_SESSION['update_check'] == 2 it's a critical update if it's -1 then there is no update available

$version = getVersion();

if(isset($_GET['refresh']))
{
    $_SESSION['update_check'] = null;
}

if(!isset($_SESSION['update_check']))
{
    if(isset($_SESSION['update_server_down']))
    {
        $_SESSION['update_server_down'] = null;
        getError(true, _e('update_server_down', '', '', true), true);
        goto down;
    }
    elseif($stat = checkForUpdate())
    {
        if(($stat[0] - $version) > 0 && $stat[2] == 'c') // c for critical
        {
            $_SESSION['update_check'] = 2;
        }
        elseif(($stat[0] - $version) > 0 && $stat[2] == 'r') // r for regular
        {
            $_SESSION['update_check'] = 1;
        }
        else
        {
            down:
            $_SESSION['update_check'] = -1;
        }
    }
}
    if($_SESSION['update_check'] == 2)
    {
        $status = _e('new_crit_update', '', '', true);
    }
    elseif($_SESSION['update_check'] == 1)
    {
        $status = _e('new_regu_update', '', '', true);
        $status_icon = '<i></i>';
    }
    else
    {
        $status = _e('no_new_update', '', '', true);
        $status_icon = '<i></i>';
    }
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
            <div align="<?php echo getLBA(); ?>">
                <?php _e('status') ?>:&nbsp;<span style="text-align: left"><a href="?switch=updates&refresh"><?php echo _e('refresh'); ?></a></span>&nbsp;<span style="float: <?php echo getLBA_rev(); ?>; <?php if($_SESSION['update_check'] == 2) echo 'color: red;'; elseif($_SESSION['update_check'] == 1) echo 'color: green;'; ?>"><?php echo $status; ?></span><br>
                <?php _e('installed_version', '', ':') ?><span style="float: <?php echo getLBA_rev(); ?>"><?php echo getVersion(); ?></span><br>
                <?php _e('installed_on', '', ':') ?><span style="float: <?php echo getLBA_rev(); ?>"><?php echo tr_num(LBDP(getInstallationDate())); ?></span><br>
                <?php _e('release_date', '', ':') ?><span style="float: <?php echo getLBA_rev(); ?>"><?php echo tr_num(LBDP(getReleaseDate())); ?></span><br>
                <?php if($_SESSION['update_check'] != -1 && $_SESSION['update_check'] != false) : ?>
                    <?php _e('new_version', '', ':') ?><span style="float: <?php echo getLBA_rev(); ?>;<?php if($_SESSION['update_check'] == 2) echo 'color: red;'; elseif($_SESSION['update_check'] == 1) echo 'color: green;'; ?>"><?php echo $_SESSION['stat_version']; ?></span><br>
                    <?php _e('new_version_release_date', '', ':') ?><span style="float: <?php echo getLBA_rev(); ?>"><?php echo tr_num(LBDP($_SESSION['stat_date'])); ?></span><br>
                    <?php _e('description', '', ':') ?><span style="float: <?php echo getLBA_rev(); ?>"><?php echo $_SESSION['stat_desc']; ?></span><br>
                <?php endif; ?>
            </div>
            <br>
            <?php if($_SESSION['update_check'] != -1 && $_SESSION['update_check'] != false) : ?>
            <div align="center">
                <a class="btn" href="<?php echo $_SESSION['stat_link']; ?>"><?php _e('download') ?></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>