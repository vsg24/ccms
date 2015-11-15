<?php getHeaderCUSTOM(); ?>
<?php
$posts = new Posts();
$posts->handleSiteViews();
?>
    <?php include_once 'sidebar.php'; ?>

    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-md-offset-1 col-lg-offset-1 div_white_left" style="text-align: left; background-color: #ffffff;">
            <article class="post"><br>
                <div style="text-align: center">Oops... Sorry, the page you were looking after doesn't exist.</div>
            </article><br>
    </div>
<?php getFooter(); ?>