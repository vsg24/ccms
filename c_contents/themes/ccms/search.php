<?php getHeaderCUSTOM(); ?>
<?php
$posts = new Posts();
$posts->handleSiteViews();

if(isset($_GET['search_submit']) && isset($_GET['search_query']))
{
    $s = $_GET['search_query'];
    $i = 'set';
}
?>
    <?php include_once 'sidebar.php'; ?>

    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-md-offset-1 col-lg-offset-1 div_white_left">
        <br>
        <div align="center" class="search">
            <form action="" method="GET">
                <input dir="auto" class="form-block" type="search" name="search_query">&nbsp;<input rel="search" type="submit" name="search_submit" class="btn btn-search" style="margin-bottom: 3px;" value="Search">
            </form>
        </div>
        <?php if(isset($i)) : ?>
            <div>Search result for <span style="color: darkred;"><?php echo urldecode($s); ?></span>:</div>
        <?php while($row = $posts->getSearchResultLoop($s)) : ?>
            <article class="post">
                <a href="<?php echo $posts->getPostPermLink($row['ID'], $row['post_type'], $row['link_title']); ?>"><h3 style="font-weight: bold;"><?php echo strip_tags($row['post_title']); ?></h3></a>&nbsp;&nbsp;<span style="color: #808080;"><i style="vertical-align: middle;" class="mdi mdi-calendar"></i>&nbsp;<?php echo englishConvertDate($row['post_date']); ?>&nbsp;<i style="vertical-align: middle;" class="mdi mdi-file"></i>&nbsp;<?php echo getCategoryById(getPostCategories($row['ID'])[0]); ?></span>
                <p><?php echo $row['post_excerpt']; ?></p>
                <?php if(Users::getUsernameBySeassion() !== false && Users::isAdmin(Users::getUsernameBySeassion())) : ?>
                    <div align="right"><a href="<?php echo $posts->getPostEditLink($row['ID']); ?>"><i style="vertical-align: middle;" class="mdi mdi-pencil-box-outline"></i>Edit</a></div><br>
                <?php endif; ?>
            </article><br>
        <?php endwhile; ?>
            <div align="right"><strong><?php echo $posts->search_res_count; ?> Result</strong></div><br>
            <?php if($posts->total_pages > 1) : ?>
        <div style="background-color: whitesmoke; padding: 10px;" align="center">
            <?php if($posts->hasPreviousPage) : ?>
                <a href="<?php echo $_SERVER['REQUEST_URI'] . '&page=' . $posts->previousPage; ?>" class="btn">Back</a>
            <?php endif; if($posts->hasNextPage) : ?>
                <a href="<?php echo $_SERVER['REQUEST_URI'] . '&page=' . $posts->nextPage; ?>" class="btn">Next</a>
            <?php endif; ?>
            <span style="float: left !important; margin-top: 7px;">Page <?php echo $posts->getCurrentPageNumber(); ?> of <?php echo $posts->total_pages; ?></span>
        </div><br>
            <?php endif; ?>
        <?php else : ?>
        <br>
        <?php endif; ?>
    </div>
<?php getFooter(); ?>