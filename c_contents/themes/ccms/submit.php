<?php getHeader(); ?>
<?php
Posts::handleSiteViewsStatic();
?>
    <div align="center" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-md-offset-1 col-lg-offset-1">
        <a class="btn btn-warning btn-lg"><i class="mdi mdi-download"></i>&nbsp;Download</a>
        <span class="help-block">v.<?php echo getVersion(); ?> Alpha</span>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-md-offset-1 col-lg-offset-1 div_white_left">
        <article class="post">
            <a href="submit.php"><h3 align="center" style="font-weight: bold;">Submit Page</h3></a>
            <p>You have been redirected here from a submit form in our website.</p>
            <span>Please confirm that you want to complete the action.</span><br><br>
            <span>Action: <?php echo 'action'; ?></span>
            <br><br>
        </article>
    </div>
<?php getFooter(); ?>