<?php getHeader(); ?>
<?php
$posts = new Posts();
$posts2 = new Posts();
$posts->handleSiteViews();
?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div style="text-align: center" class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <?php include_once 'carousel.php'; ?>
            </div>
            <div style="position: relative; display: block; margin-top: 12%; text-align: center;" class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <br>
                <p>
                    ccms is a content management system, suitable to make medium sized websites and web apps.<br>
                    ccms is available in <b>English</b> & <b>Persian</b>. It comes with very useful set of functions and classes built-in.<br>
                    ccms is 100% <span style="color: green; font-weight: bold;">FREE</span> to download and to use.
                </p>
                <a class="btn btn-warning btn-lg" href="http://jmp.sh/tUavOul" target="_blank"><i class="mdi mdi-download"></i>&nbsp;Download</a>
                <span class="help-block">ccms_v<?php echo getVersion(); ?></span>
                <a class="btn btn-default btn-lg" href="<?php echo SITE_DOMAIN; ?>docs"><i class="mdi mdi-library"></i>&nbsp;Learn</a>
                <br><br>
            </div>
        </div>
    </div>
    <br><br>
    <div class="row">
        <div style="text-align: center" class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <img src="<?php echo THEME_BASE; ?>images/duck.png">
            <h3>Easy to Understand and to Use</h3>
            <p class="help-block">Pretty yet simple user interface leads you to creativity</p>
        </div>
        <div style="text-align: center" class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <img src="<?php echo THEME_BASE; ?>images/package-variant.png">
            <h3>A complete package</h3>
            <p class="help-block">Includes starter themes, plugins, analytics, vip section, xml maps and more</p>
        </div>
        <div style="text-align: center" class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <img src="<?php echo THEME_BASE; ?>images/code-tags.png">
            <h3>Access everything</h3>
            <p class="help-block">Built upon PHP and MySQL, ccms lets you access every function and class</p>
        </div>
    </div>
    <br>
    <div class="row">
        <div style="text-align: left" class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-md-offset-1 col-lg-offset-1">
            <h3>Latest News:</h3><br>
            <?php while($row = $posts->getPostsLoop(6)) : ?>
                <a href="<?php echo $posts->getPostPermLink($row['ID'], $row['post_type'], $row['link_title']); ?>"><strong><?php echo strip_tags($row['post_title']); ?></strong></a>&nbsp;&nbsp;<span style="color: #808080"><em><?php echo englishConvertDate(($row['post_date'])); ?></em></span><br>
            <?php endwhile; ?>
            <br><a href="<?php echo SITE_DOMAIN . 'news.php'; ?>" class="btn btn-info"><i class="mdi mdi-archive"></i>&nbsp;Archive</a><br><br>
        </div>
        <div style="text-align: left" class="col-xs-12 col-sm-12 col-md-3 col-lg-3 div_white_left">
            <br>
            <article>
            <?php while($row = $posts2->getPostsLoop(1)) : ?>
                <a href="<?php echo $posts->getPostPermLink($row['ID'], $row['post_type'], $row['link_title']); ?>"><h4 style="font-weight: bold;"><?php echo strip_tags($row['post_title']); ?></h4></a>&nbsp;<span style="color: #808080;"><i style="vertical-align: middle;" class="mdi mdi-calendar"></i>&nbsp;<?php echo englishConvertDate($row['post_date']); ?></span>
                <p><?php echo $row['post_excerpt']; ?></p>
            <?php endwhile; ?>
            </article>
        </div>
    </div>
<?php getFooter(); ?>