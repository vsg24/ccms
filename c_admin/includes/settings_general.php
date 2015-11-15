<?php

$path = DOC_ROOT . '/c_config.php';

if(is_file($path))
{
    $file_array = file($path); // converting file content into an array
    $lang_line = $file_array[20]; // accessing the desired line in array (language constant line)
}
else
{
    die('c_config.php not found in site root');
}

function isAlsoDefault1($lang)
{
    if(lcfirst($lang) == Language)
        return 'selected="selected"';
    return '';
}
function isAlsoDefault2($cat)
{
    if($cat == getCategoryById(getDefaultCategory()))
        return 'selected="selected"';
    return '';
}

function isAlsoSet($value)
{
    $query = "SELECT option_value FROM c_options WHERE option_name = 'can_comment'";
    $conn = MySQL::open_conn();
    $res = $conn->query($query)->fetch_row();
    if($value == $res[0]) return 'selected="selected"';
    return '';
}

$conn = MySQL::open_conn();
?>
<?php
if(isset($_POST['submit']))
{
    //chmod(DOC_ROOT . '/c_config.php', 0644);

    if(isset($_POST['site_language']))
    {
        $language = $_POST['site_language'];
        $c_config = file_get_contents(DOC_ROOT . '/c_config.php');
        $old_lang = getLanguage();
        $new_lang = $language;

        $c_config = preg_replace('/' . $old_lang . '/', $new_lang, $c_config, 1);
        file_put_contents(DOC_ROOT . '/c_config.php', $c_config);
    }

    if(isset($_POST['default_category']))
    {
        $def_cat = getCategoryByName($_POST['default_category']);
        $query = "UPDATE c_options SET option_value = $def_cat WHERE option_name = 'default_category'";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);
    }

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

    if(isset($_POST['site_desc']))
    {
        $desc = $_POST['site_desc'];
        $desc = $conn->real_escape_string($desc);
        $query = "UPDATE c_options SET option_value = '$desc' WHERE option_name = 'site_description'";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);
    }

    {
        if(isset($_POST['register_open']))
        {
            $register_status = 'yes';
        }
        else
        {
            $register_status = 'no';
        }

        $query = "UPDATE c_options SET option_value = '$register_status' WHERE option_name = 'register_open'";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);

        //

        if(isset($_POST['conf_mail']))
        {
            $conf_mail = 'yes';
        }
        else
        {
            $conf_mail = 'no';
        }

        $query = "UPDATE c_options SET option_value = '$conf_mail' WHERE option_name = 'send_conf_mail'";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);

        //

        if(isset($_POST['conf_mail_subject']))
        {
            $conf_mail_subject = $_POST['conf_mail_subject'];
        }

        $query = "UPDATE c_options SET option_value = '$conf_mail_subject' WHERE option_name = 'conf_mail_subject'";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);
    }

    echo "<h3 style=\"color: red;\">" . _e('plswait', '', '', true) . "</h3>";
    header('Refresh: 3;url=index.php?switch=settings');
}
?>

<?php
$query = "SELECT * FROM c_options WHERE option_name = 'default_categroy'";
$res = $conn->query($query);
dbQueryCheck($res, $conn);
$def_cat = $res->fetch_assoc();
$def_cat = $def_cat['option_value'];

$query = "SELECT * FROM c_options WHERE option_name = 'max_post_pp'";
$res = $conn->query($query);
dbQueryCheck($res, $conn);
$max_post_pp = $res->fetch_assoc();
$max_post_pp = $max_post_pp['option_value'];

$query = "SELECT * FROM c_options WHERE option_name = 'site_title'";
$res = $conn->query($query);
dbQueryCheck($res, $conn);
$title = $res->fetch_assoc();
$title = $title['option_value'];

$query = "SELECT * FROM c_options WHERE option_name = 'site_description'";
$res = $conn->query($query);
dbQueryCheck($res, $conn);
$desc = $res->fetch_assoc();
$desc = $desc['option_value'];

$query = "SELECT * FROM c_options WHERE option_name = 'register_open'";
$res = $conn->query($query);
dbQueryCheck($res, $conn);
$register_open = $res->fetch_assoc();
$register_open = $register_open['option_value'];

$query = "SELECT * FROM c_options WHERE option_name = 'send_conf_mail'";
$res = $conn->query($query);
dbQueryCheck($res, $conn);
$send_conf_mail = $res->fetch_assoc();
$send_conf_mail = $send_conf_mail['option_value'];

$query = "SELECT * FROM c_options WHERE option_name = 'conf_mail_subject'";
$res = $conn->query($query);
dbQueryCheck($res, $conn);
$conf_mail_subject = $res->fetch_assoc();
$conf_mail_subject = $conf_mail_subject['option_value'];

$res->free();
?>
<form action="" method="POST" class=form-group">
    <!--<div class="col col-sm-3 col-md-7 col-md-offset-2">-->
        <label for="lang" style="float: <?php echo getLBA(); ?>;" id="lang" data-content="Affects Admin area and all standard themes" rel="popover" data-placement="bottom" data-original-title="Language" data-trigger="hover"><?php _e('site_language', '', ':'); ?></label>&nbsp
        <select name="site_language" id="lang" style="float: <?php echo getLBA_rev(); ?>;">
            <?php
            $handle = scandir(DOC_ROOT . "/c_contents/langs/");
            for($i = 2; $i < count($handle); $i++) // We start the loop at 2 because, . and .. are first two
            {
                $value = basename($handle[$i], ".php");
                echo '<option value="' . $value . '"' . isAlsoDefault1($value) . '>' . ucfirst($value) . '</option>';
            }
            ?>
        </select>
        <br><br>
        <label for="def_cat" style="float: <?php echo getLBA(); ?>;" id="def_cat" data-content="Default category for all new posts and pages" rel="popover" data-placement="bottom" data-original-title="Default Category" data-trigger="hover"><?php _e('default_category', '', ':'); ?></label>&nbsp
        <select name="default_category" id="def_cat" style="float: <?php echo getLBA_rev(); ?>;">
            <?php
            $query = "SELECT name FROM c_categories";
            $res = $conn->query($query);
            dbQueryCheck($res, $conn);
            while ($row = $res->fetch_assoc())
            {
                echo '<option value="' . $row['name'] . '"' . isAlsoDefault2($row['name']) . '>' . $row['name'] . '</option>';
            }
            $res->free();
            ?>
        </select>
        <br><br>
        <label for="can_comment" style="float: <?php echo getLBA(); ?>;"><?php _e('can_comment', '', ':'); ?></label>&nbsp
        <select name="can_comment" id="can_comment" style="float: <?php echo getLBA_rev(); ?>;">
            <option value="anyone" <?php echo isAlsoSet('anyone'); ?>><?php _e('anyone'); ?></option>
            <option value="users" <?php echo isAlsoSet('users'); ?>><?php _e('users'); ?></option>
        </select>
        <br><br>
        <label for="max_post_pp" style="float: <?php echo getLBA(); ?>;"><?php _e('post_pp', '', ':'); ?></label>
        <input type="number" name="max_post_pp" id="max_post_pp" style="float: <?php echo getLBA_rev(); ?>" value="<?php if(isset($max_post_pp)) echo $max_post_pp; ?>" required><br><br>
        <label for="title" style="float: <?php echo getLBA(); ?>;"><?php _e('title', '', ':'); ?></label>
        <input dir="auto" type="text" name="site_title" id="title" class="form_block" value="<?php if(isset($title)) echo $title; ?>" required>
        <label for="desc" style="float: <?php echo getLBA(); ?>;"><?php _e('description', '', ':'); ?></label>
        <input dir="auto" type="text" name="site_desc" id="desc" class="form_block" value="<?php if(isset($desc)) echo $desc; ?>" required>
        <br>
        <label for="register" style="float: <?php echo getLBA(); ?>;"><?php _e('register_open', '', ':'); ?></label>&nbsp<input type="checkbox" name="register_open" <?php if($register_open === 'yes') echo 'checked'; ?> style="float: <?php echo getLBA_rev(); ?>;" id="register"><br><br>
        <label id="conf_mail_label" for="conf_mail" style="float: <?php echo getLBA(); ?>;"><?php _e('send_conf_mail', '', ':'); ?></label>&nbsp<input type="checkbox" name="conf_mail" <?php if($send_conf_mail === 'yes') echo 'checked'; ?> style="float: <?php echo getLBA_rev(); ?>;" id="conf_mail">
        <br><br>
        <label id="conf_mail_subject_label" for="conf_mail_subject" style="float: <?php echo getLBA(); ?>;"><?php _e('conf_mail_subject', '', ':'); ?></label>
        <input dir="auto" type="text" name="conf_mail_subject" id="conf_mail_subject" class="form_block" value="<?php if(isset($conf_mail_subject)) echo $conf_mail_subject; ?>" required>
        <br><br>
        <input type="submit" name="submit" class="btn btn-info" value="<?php _e('submit'); ?>">
    <!--</div>-->
</form>
<script>
    $('#lang').popover();
    $('#def_cat').popover();
    $(document).ready(function () {
        if($('#register').is(':checked'))
        {}
        else
        {
            $('#conf_mail').hide();
            $('#conf_mail_label').hide();
        }
        if($('#conf_mail').is(':checked'))
        {}
        else
        {
            $('#conf_mail_subject').hide();
            $('#conf_mail_subject_label').hide();
        }
    });
    $('input').on('click',function () {
    if($('#register').is(':checked'))
    {
        $('#conf_mail').show();
        $('#conf_mail_label').show();
    }
    else
    {
        $('#conf_mail').hide();
        $('#conf_mail_label').hide();
    }
    if($('#conf_mail').is(':checked'))
    {
        $('#conf_mail_subject').show();
        $('#conf_mail_subject_label').show();
    }
    else
    {
        $('#conf_mail_subject').hide();
        $('#conf_mail_subject_label').hide();
    }
    });
</script>
<?php
$conn->close();
?>