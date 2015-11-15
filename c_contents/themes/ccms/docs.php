<?php getHeaderCUSTOM(); ?>
<?php

/*if(isset($_GET['switch']) && $_GET['switch'] == 'installation')
{
}*/

$posts = new Posts();
$posts->handleSiteViews();
?>
<?php include_once 'sidebar.php'; ?>
<style>
    .help-content {
        text-align: center; margin: 5%;
    }
    .help-link {
        color: inherit;
    }
    .help-link:hover {
        color: red !important;
    }
</style>

    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-md-offset-1 col-lg-offset-1 div_white_left">
        <br>
        <div style="text-align: center" class="search">
            <form action="<?php echo SITE_DOMAIN; ?>search.php" method="GET">
                <input class="form-block" type="search" name="search_query">&nbsp;<input type="submit" name="search_submit" class="btn btn-search" style="margin-bottom: 3px;" value="Search">
            </form>
        </div>
        <hr>
            <article class="post">
                <div class="help-content">
                    <a class="help-link" href="http://ccms.vahida.ir/111/page/ccms-install-howto">
                    <img src="<?php echo THEME_BASE; ?>images/transfer.png"><br>
                    Installation
                    </a>
                </div>
                <div class="help-content">
                    <a class="help-link" href="<?php echo SITE_DOMAIN; ?>docs.php?switch=settings">
                    <img src="<?php echo THEME_BASE; ?>images/settings.png"><br>
                    Settings
                    </a>
                </div>
                <div class="help-content">
                    <a class="help-link" href="<?php echo SITE_DOMAIN; ?>docs.php?switch=themes">
                    <img src="<?php echo THEME_BASE; ?>images/palette.png"><br>
                    Themes
                    </a>
                </div>
            </article><br>
    </div>
<?php getFooter(); ?>