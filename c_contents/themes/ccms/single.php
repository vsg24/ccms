<?php getHeaderCUSTOM(); ?>
<?php
$posts = new Posts();
$comments = new Comments(URI::getPageId());
$row = $posts->getPost(URI::getParams_single()['id']);
$posts->handlePostViewsById(URI::getPageId());
?>
        <?php include_once 'sidebar.php'; ?>

        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-md-offset-1 col-lg-offset-1 div_white_left">
            <article class="post">
                <a href="<?php echo $posts->getPostPermLink($row['ID'], $row['post_type'], $row['link_title']); ?>"><h3 style="font-weight: bold;"><?php echo strip_tags($row['post_title']); ?></h3></a>&nbsp;&nbsp;<span style="color: #808080;"><i style="vertical-align: middle;" class="mdi mdi-calendar"></i>&nbsp;<?php echo englishConvertDate($row['post_date']); ?>&nbsp;<i style="vertical-align: middle;" class="mdi mdi-file"></i>&nbsp;<?php echo getCategoryById(getPostCategories($row['ID'])[0]); ?></span>
                <p><?php echo $row['post_excerpt']; ?></p>
                <p><?php echo $row['post_content']; ?></p>
                <?php if(Users::getUsernameBySeassion() !== false && Users::isAdmin(Users::getUsernameBySeassion())) : ?>
                <div align="right"><a href="<?php echo $posts->getPostEditLink($row['ID']); ?>"><i style="vertical-align: middle;" class="mdi mdi-pencil-box-outline"></i>Edit</a></div><br>
                <?php endif; ?>
            </article>
        </div>
        <?php include_once 'comments.php'; ?>
<?php getFooter(); ?>