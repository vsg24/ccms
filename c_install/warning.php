<?php require_once 'header.php'; ?>
<?php
if(isset($_GET['force_install']))
{
    $_SESSION['force_install'] = true;
    redirectTo('index.php');
}
?>
<div class="col-xs-12 col-sm-12 col-md-6 co-lg-6 col-md-offset-2 div_white_left">
    <div style="text-align: center; padding: 5%;">
        <div style="text-align: center"><i style="vertical-align: middle;" class="mdi mdi-alert"></i>&nbsp;Status <span style="color: orange; font-weight: bold;">WARNING</span></div><br>
        It seems that a version of ccms is already installed.<br><br>

        Version: <span style="color: purple; font-weight: bold;"><?php echo $_SESSION['version_installed']; ?></span><br>
        Date Installed: <?php echo $_SESSION['date_installed']; ?>
        <br><br>
        If you decide to install ccms again, your previous database tables will be <strong>wiped out.</strong><br>
    </div>
    <div>
        <span style="color: red;">Are you sure you want to continue?</span>
        <form action="" METHOD="GET">
            <div style="text-align: right">
        <input type="submit" class="btn" name="force_install" value="Yes, Install Anyway">
            </div>
        </form>
    </div>
    <br>
</div>
<?php include_once 'footer.php'; ?>
