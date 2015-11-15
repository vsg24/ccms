<head>
    <script src="tools/ckeditor/ckeditor.js"></script>
    <link rel="stylesheet" type="text/css" href="js/calendars/redmond.calendars.picker.css">
    <script type="text/javascript" src="js/calendars/jquery.calendars.min.js"></script>
    <script type="text/javascript" src="js/calendars/jquery.calendars.plus.min.js"></script>
    <script type="text/javascript" src="js/calendars/jquery.plugin.js"></script>
    <script type="text/javascript" src="js/calendars/jquery.calendars.picker.js"></script>
    <?php if(Language == 'fa') : ?>
        <script type="text/javascript" src="js/calendars/jquery.calendars.persian.js"></script>
        <script type="text/javascript" src="js/calendars/jquery.calendars.persian-fa.js"></script>
        <script type="text/javascript" src="js/calendars/jquery.calendars.picker-fa.js"></script>
    <?php endif; ?>
    <script type="text/javascript" src="js/DateFormat.js"></script>
</head>
<?php
$post = new Posts();
if(isset($_POST['submit_new_post']))
{
    $content = $_POST['new_post_textarea'];
    $excerpt = $_POST['new_post_excerpt'];
    $title = $_POST['new_post_title'];
    $status = $_POST['new_post_status'];
    $author_id = Users::getIDBySeassion();
    if(Language == 'fa')
    {
        $date = $_POST['new_post_date'];

        // separate date from time
        $time = explode(" ", $date);
        $get_time = $time[1];
        // joining date and time
        $date = dateToGregDB($date) . ' ' . $get_time;
    }
    else
    {
        $date = $_POST['new_post_date'];
    }

    $desc = $_POST['new_post_desc'];
    $link_title = escapeSingleQuotes($_POST['new_post_link_title']);
    $type = $_POST['new_post_type'];
    $comment_status = $_POST['new_post_comment_status'];

    if(autoXMLMapsUpdate())
    {
        if($type == 'post')
        {
            generatePostsSitemap();
        }
        elseif($type == 'page')
        {
            generatePagesSitemap();
        }
    }

    clearAllCache(); // clearing all cache files so that they will rebuilt

    $link_title = urlencode(str_replace(' ', '-', $link_title));

    if(!isset($_POST['category'])) goToError('?switch=new_post', _e('category_cant_be_empty', '', '', true));

    $res = $post->makePost($author_id, $date, $title, $link_title, $content, $excerpt, $desc, $type, $status, $comment_status, $type);

    if(!$res) goToError('?switch=new_post', _e('cant_make_new_post_or_page', '', '', true));

    $maxid = 0;
    $row = $post->getMaxPostId();

    $conn = MySQL::open_conn();
    // if it all goes well, we submit categories
    if ($row && isset($_POST['category']))
    {
        $post_id = $row;
        foreach ($_POST['category'] as $cat_id)
        {
            if (is_numeric($cat_id))
            {
                $values[] = "($post_id, " . (int) $cat_id . ')';
            }
        }
        if ($values)
        {
            $query = 'INSERT INTO c_posts_cats (post_id, cat_id) VALUES ' . implode(',', $values);
            // execute the query and get error message if it fails
            if (!$conn->query($query))
            {
                $helper = 1;
                goto end2;
            }
        }
    }

    if ($row)
    {
        $maxid = $row;
        ob_end_clean();
        redirectTo('index.php?switch=new_post&sub=edit_post&id=' . $maxid);
    }
    else
    {
        redirectTo('index.php?switch=manage_posts');
    }

    end2:
    if(isset($helper)) goToError('?switch=new_post', _e('post_submitted_but_not_category', '', '', true));
}
?>

<?php

if(isset($_POST['submit_update_post']))
{
    $id = $_GET['id'];
    $content = $_POST['new_post_textarea'];
    $excerpt = $_POST['new_post_excerpt'];
    $title = $_POST['new_post_title'];
    $author_id = Users::getIDBySeassion();

    if(Language == 'fa')
    {
        $date = $_POST['new_post_date'];

        // separate date from time
        $time = explode(" ", $date);
        $get_time = $time[1];
        // joining date and time
        $date = dateToGregDB($date) . ' ' . $get_time;
    }
    else
    {
        $date = $_POST['new_post_date'];
    }

    $desc = $_POST['new_post_desc'];
    $status = $_POST['new_post_status'];
    $comment_status = $_POST['new_post_comment_status'];
    $link_title = escapeSingleQuotes($_POST['new_post_link_title']);

    $link_title = urlencode(str_replace(' ', '-', $link_title));
    $type = $_POST['post_type'];

    // clearing all cache files so that they will rebuilt
    clearAllCache();

    $res = $post->updatePost($id, $date, $title, $link_title, $content,  $excerpt, $desc, $status, $comment_status);
    if(!$res) goToError('?switch=new_post', _e('cant_make_new_post_or_page', '', '', true));

    $conn = MySQL::open_conn();
    if (isset($_POST['category']))
    {
        $post_id = $id;
        foreach ($_POST['category'] as $cat_id)
        {
            if (is_numeric($cat_id))
            {
                $values[] = "($post_id, " . (int) $cat_id . ')';
            }
        }

        $query = "DELETE FROM c_posts_cats WHERE post_id = $post_id"; // remove all rows where post_id = this post
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);

        if ($values)
        {
            $query = "INSERT INTO c_posts_cats (post_id, cat_id) VALUES " . implode(',', $values); // IT ALLOWS DUPLICATION, ALSO DOESN'T UPDATE (DELETE)
            // execute the query and get error message if it fails
            if (!$conn->query($query))
            {
                $helper = 1;
                goto end3;
            }
        }
    }
    end3:
    if(isset($helper)) goToError('?switch=new_post', _e('post_submitted_but_not_category', '', '', true));
}

?>

<?php
if(isset($_GET['id']) && $_GET['id'] > 0)
{
    $id = $_GET['id'];
    $conn = MySQL::open_conn();
    $query = "SELECT * FROM c_posts WHERE ID = $id LIMIT 1";
    $res = $conn->query($query);

    dbQueryCheck($res, $conn);
    $row = $res->fetch_assoc();

    $title_text = htmlspecialchars($row['post_title']);
    $excerpt = $row['post_excerpt'];
    $content_text = htmlspecialchars($row['post_content']);
    $desc_text = htmlspecialchars($row['post_description']);
    $tags = $row['tags'];

    if(Language == 'fa')
    {
        $date = $row['post_date'];

        // separate date from time
        $time = explode(" ", $date);
        $get_time = $time[1];
        // joining date and time
        $date = addZerosToShow_dash(dateToJalali($date, '-'), true) . ' ' . $get_time;
    }
    else
    {
        $date = $row['post_date'];
    }

    $post_status = $row['post_status'];
    $comment_status = $row['comment_status'];
    $link_title = urldecode($row['link_title']);
    $type = $row['post_type'];

    if($type == 'post')
        $type = 'Post';
    elseif($type == 'page')
        $type = 'Page';

    $query = "SELECT cat_id FROM c_posts_cats WHERE post_id = $id";
    $res = $conn->query($query);

    $categories = [];
    while($row = $res->fetch_assoc())
    {
        array_push($categories, $row['cat_id']);
    }

}
else
{
    $title_text = null;
    $content_text = null;
    $desc_text = null;
    $date = null;
    $post_status = null;
    $link_title = null;
    $tags = null;
    $type = null;
    $excerpt = null;
    $comment_status = null;
}
?>

<form action="" method="POST" class="form-group">
    <!--<label for="title"><?php _e('title', '', ':'); ?></label><input type="text" style="width: 100%" name="new_post_title" id="title" value="<?php echo $title_text ?>" required>-->
    <label for="title"><?php _e('title', '', ':'); ?></label><textarea name="new_post_title" id="title" required><?php echo $title_text ?></textarea>
    <br>
    <div class="form-inline" align="center">
    <label for="desc"><?php _e('desc') ?>:</label>&nbsp;<input dir="auto" type="text" name="new_post_desc" value="<?php echo $desc_text ?>" id="desc" required>
    &nbsp;&nbsp;
    <label for="link"><?php _e('link_name'); ?>:</label>&nbsp;<input type="text" name="new_post_link_title" id="link" value="<?php echo $link_title ?>" required>
        &nbsp;&nbsp;
    <label for="date"><?php _e('date'); ?>:</label>&nbsp;<input type="text" name="new_post_date" id="date" value="<?php echo $date ?>" required>
    </div>
    <br>
    <style>
        .calendars-nav, .calendars-ctrl {
            font-size: 100%;
        }
    </style>
    <script>
        $('#date').calendarsPicker({
            dateFormat: 'yyyy-mm-dd',
            //pickerClass: 'my-picker',
            <?php if(Language == 'fa') echo "calendar: $.calendars.instance('persian', 'fa'),"; ?>
            onSelect: function (dates) {
                var today = new Date();
                var time = today.format("H:M:s");
                $('#date').val(dates + ' ' + time);
            }
        });
    </script>
    <div align="center">
        <label for="type"><?php _e('post_type'); ?>:</label>
        <?php if(isset($_GET['id'])) : ?>
        <span><em style="color: red;"><?php echo $type; ?></em></span>
        <?php else : ?>
        <select id="type" name="new_post_type">
            <option value="post">Post</option>
            <option value="page">Page</option>
        </select>
        <?php endif ?>
        &nbsp;<label for="cat1"><?php _e('categories'); ?>:</label>
        <a style="cursor: pointer;" id="show_cats"><?php _e('show'); ?></a>
        <select style="display: none;" id="cats" multiple="multiple" size="<?php echo getCategoryCount(); ?>" name="category[]">
            <?php
            $conn = MySQL::open_conn();
            $query = "SELECT ID, name FROM c_categories";
            $res = $conn->query($query);

            while ($row = $res->fetch_assoc())
            {
                if(@in_array($row['ID'], $categories))
                {
                    $h = 'selected';
                }
                elseif(!isset($_GET['id']) && !isset($u))
                {
                    if($row['ID'] === getDefaultCategory())
                    {
                        $h = 'selected';
                        $u = 1;
                    }
                }
                else
                    $h = '';
                echo '<option value="' . $row['ID'] . '"' . $h . '>' . $row['name'] . '</option>';
            }
            ?>
        </select>&nbsp;
    </div>
    <script>
        $(document).ready(function(){
            $("#show_cats").click(function(){
                {
                    $("#cats").toggle();
                    //$("#show_cats").val.html = "hide";
                }
            });
        });
    </script>
    <br>
    <input type="hidden" name="post_type" value="<?php echo $type; ?>">
    <div align="center"><a style="cursor: pointer; <?php if(isset($excerpt)) echo 'display: none;'?>" id="add_excerpt"><?php _e('add_excerpt'); ?></a></div><br>
    <textarea style="<?php if(!isset($excerpt)) echo 'display: none;'?>" name="new_post_excerpt" id="excerpt" rows="10" cols="80"><?php echo $excerpt; ?></textarea>
    <textarea name="new_post_textarea" id="textarea" rows="10" cols="80"><?php echo $content_text ?></textarea>
    <br>
    <label for="status" style="float: <?php echo getLBA(); ?>;"><?php _e('status', '', ':'); ?></label>&nbsp;
    <select id="status" name="new_post_status">
        <option value="Initialized" <?php if($post_status == 'Initialized') echo 'selected'; ?>><?php _e('initialized'); ?></option>
        <option value="Published" <?php if($post_status == 'Published') echo 'selected'; ?>><?php _e('published'); ?></option>
    </select><br><br>
    <script>
        <?php if(!isset($_GET['id'])) : ?>
        $('#status option[value="Published"]').attr("selected", "selected");
        <?php endif; ?>
    </script>
    <!--<label for="status" style="float: <?php echo getLBA(); ?>;"><?php _e('status', '', ':'); ?></label>&nbsp;<input id="status" type="radio" name="new_post_status" value="Initialized" <?php if(!isset($_GET['id'])) echo 'checked'; elseif($post_status == 'Initialized') echo 'checked'; ?>>Initialized &nbsp;<input type="radio" name="new_post_status" value="Published" <?php if(!isset($_GET['id'])) echo 'disabled'; elseif($post_status == 'Published') echo 'checked'; ?>>Published &nbsp;<br>-->
    <label for="comment_status" style="float: <?php echo getLBA(); ?>;"><?php _e('comment_status', '', ':'); ?></label>&nbsp;<input id="comment_status" type="radio" name="new_post_comment_status" value="0" <?php if($comment_status == 0) echo 'checked'; ?>><?php if(Language == 'en') _e('close'); else _e('open'); ?> &nbsp;<input type="radio" name="new_post_comment_status" value="1" <?php if(!isset($_GET['id'])) echo 'checked'; elseif($comment_status == 1) echo 'checked'; ?>><?php if(Language == 'en') _e('open'); else _e('close'); ?> &nbsp;
    <?php if(isset($_GET['id']) && $_GET['id'] > 0) : ?>
    <input class="btn btn-default" type="submit" name="submit_update_post" value="<?php _e('update'); ?>" style="float: <?php echo getLBA_rev(); ?>;">
        <input type="hidden" id="post_id" value="<?php echo $_GET['id']; ?>">
    <?php else : ?>
    <input class="btn btn-info" type="submit" name="submit_new_post" value="<?php _e('submit'); ?>" style="float: <?php echo getLBA_rev(); ?>;">
    <?php endif; ?>
    <script>
        CKEDITOR.replace( 'title', {
            extraPlugins: 'divarea',
            customConfig: 'ckeditor-config-title.js'
        });

        CKEDITOR.replace( 'textarea', {
            customConfig: 'ckeditor_config.js',
            //height: 400,
            extraPlugins: 'image2',
            filebrowserUploadUrl: 'tools/ckeditor/plugins/imgupload/imgupload.php'
        });
    </script>
    <script>
        $(document).ready(function(){
            var i = 0;
            $("#add_excerpt").click(function(){
                if(i<1)
                {
                    $("#excerpt").toggle();
                    CKEDITOR.replace( 'excerpt', {
                        customConfig: 'ckeditor_config.js',
                        //height: 400,
                        extraPlugins: 'image2',
                        filebrowserUploadUrl: 'tools/ckeditor/plugins/imgupload/imgupload.php'
                    });
                    $("#add_excerpt").css('display', 'none');
                    i++;
                }
            });
        });
    </script>
    <?php if(isset($excerpt)) : ?>
        <script>
        CKEDITOR.replace( 'excerpt', {
        customConfig: 'ckeditor_config.js',
        //height: 400,
        extraPlugins: 'image2',
        filebrowserUploadUrl: 'tools/ckeditor/plugins/imgupload/imgupload.php'
        });
        </script>
    <?php endif; ?>
</form>

<script src="post_tags_proc.js"></script>
<form style="text-align: center" id="tags_form" action="post_tags_proc.php" method="POST">
<div class="form-inline">
    <label for="tag_to_add"><?php _e('tags'); ?>:</label>&nbsp;<input type="text" name="new_post_tags" id="tag_to_add" <?php if(!isset($_GET['id'])) echo 'placeholder="Submit the post first" disabled' ?>>
    <button class="btn btn-default btn_txt_inline" id="add_tag"><?php _e('add'); ?></button>
    <hr>
    <div><span id="tags"><?php if(isset($_GET['id'])) echo mb_ereg_replace('\.', '&nbsp;&nbsp;', $tags); ?>&nbsp;&nbsp;</span></div>
</div>
</form>