<?php if(isset($_GET['sub']) && $_GET['sub'] == 'new_template') : ?>
<head>
    <script src="<?php echo SITE_DOMAIN . '/c_admin/tools/ckeditor/ckeditor.js'; ?>"></script>
    <script>
        <!--
        function silentErrorHandler() {return true;}
        window.onerror=silentErrorHandler;
        //-->
    </script>
</head>
<?php
if(isset($_POST['new_template_submit']))
{
    $conn = MySQL::open_conn();
    $name = escapeSingleQuotes($_POST['email_template_name']);
    $content_html = $_POST['email_template_html'];
    $content_plain = $_POST['email_template_plain'];
    $query = "INSERT INTO c_emails (name, template_html, template_plain) VALUES ('$name', '$content_html', '$content_plain')";
    $res = $conn->query($query);
    if(!$res) goToError('index.php?switch=utilities&tab=email_templates&sub=new_template', _e('cant_make_or_update_new_template', '', '', true));

    $maxid = 0;
    $row = $conn->query("SELECT MAX(ID) AS max FROM c_emails")->fetch_array();
    if ($row)
    {
        $maxid = $row['max'];
        ob_end_clean();
        redirectTo('index.php?switch=utilities&tab=email_templates&sub=new_template&id=' . $maxid);
    }
    else
    {
        redirectTo('index.php?switch=utilities&tab=email_templates');
    }
}
if(isset($_POST['update_template_submit']))
{
    $name = escapeSingleQuotes($_POST['email_template_name']);
    $content_html = $_POST['email_template_html'];
    $content_plain = $_POST['email_template_plain'];
    $query = "UPDATE c_emails SET name = '$name', template_html = '$content_html', template_plain = '$content_plain'";
    $res = MySQL::open_conn()->query($query);
    if(!$res) goToError('?switch=utilities&tab=email_templates&sub=new_template', _e('cant_make_or_update_new_template', '', '', true));
}
if(isset($_GET['id']))
{
    $id = $_GET['id'];
    $query = "SELECT * FROM c_emails WHERE ID = $id LIMIT 1";
    $row = MySQL::open_conn()->query($query)->fetch_assoc();
    $template_name = $row['name'];
    $template_html = $row['template_html'];
    $template_plain = $row['template_plain'];
}
else
{
    $template_name = null;
    $template_html = null;
    $template_plain = null;
}
?>
<div>
    <br>
    <form action="" method="POST">
        <label for="email_template_name"><?php _e('template_name'); ?>:</label>
        <input name="email_template_name" dir="auto" type="text" id="email_template_name" value="<?php echo $template_name; ?>"><br><br>
        <div><?php _e('available_tags', '', ':'); ?><br>{username}&nbsp;&nbsp;{email}&nbsp;&nbsp;{site_title}&nbsp;&nbsp;{site_url}&nbsp;&nbsp;{activate}</div><br><br>
        <label for="email_template_html"><?php _e('html_email_template', '', ':'); ?></label>
        <textarea name="email_template_html" id="email_template_html" rows="10" cols="80"><?php echo $template_html; ?></textarea><br>
        <label for="email_template_plain"><?php _e('plain_text_email_template', '', ':'); ?></label><br>
        <textarea name="email_template_plain" id="email_template_plain" rows="8" cols="70"><?php echo $template_plain; ?></textarea><br><br>
        <?php if(!isset($_GET['id'])) : ?>
        <input type="submit" class="btn btn-info" name="new_template_submit" value="<?php _e('submit'); ?>">
        <?php else : ?>
        <input type="submit" class="btn btn-default" name="update_template_submit" value="<?php _e('update'); ?>">
        <?php endif; ?>
    </form>
    <script>
        CKEDITOR.replace( 'email_template_html', {
            customConfig: 'ckeditor-config.js',
            extraPlugins: 'image2',
            filebrowserUploadUrl: 'tools/ckeditor/plugins/imgupload/imgupload.php'
        });
    </script>
</div>
<?php else : ?>
<?php
$conn = MySQL::open_conn();

$query = "SELECT * FROM c_emails ORDER BY date_created DESC";
$res = $conn->query($query);
dbQueryCheck($res, $conn);
?>
<?php
if(isset($_GET['sub']) && $_GET['sub'] == 'delete_template')
{
    $id = $_GET['id'];
    $query  = "DELETE FROM c_emails WHERE ID = $id LIMIT 1";
    $res = $conn->query($query);
    dbQueryCheck($res, $conn);
    ob_end_clean();
    redirectTo('index.php?switch=utilities&tab=email_templates');
}
?>
<style>
    #tablediv {
        display:flex;
        justify-content:center;
        align-items:center;
    }

    th {
        text-align: center;
        vertical-align: middle;
    }

</style>
<div class="container-fluid">
    <div class="row" align="center">
        <div class="col-xs-6 col-sm-4 col-md-4 col-xs-offset-3 col-sm-offset-4 col-md-offset-4">
            <a href="index.php?switch=utilities&tab=email_templates&sub=new_template" class="btn btn-default"><?php _e('new_template'); ?></a>
        </div>
    </div>
</div><br>

<div class="container-fluid">
    <div class="row" align="center">
        <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
            <div id="tablediv">
                <table width="auto" class="table table-hover" ">
                <tr>
                    <th style="vertical-align: middle;"><?php _e('', 'ID'); ?></th>
                    <th style="vertical-align: middle;"><?php _e('name'); ?></th>
                    <th style="vertical-align: middle;"><?php _e('operations'); ?></th>
                    <th style="vertical-align: middle;"><?php _e('created_date'); ?></th>
                </tr>
                <?php
                while($row = $res->fetch_assoc())
                {
                    echo "<tr><td style='text-align: center; vertical-align: middle;'>" . $row['ID'] . "</td><td style='text-align: center; vertical-align: middle;'>" . $row['name'] ."</td><td <span style='text-align: center;'><a onclick=\"return confirm('Are you sure?');\" href='?switch=utilities&tab=email_templates&sub=delete_template&id=" . $row['ID'] . "'>" . _e('delete', '', '', true) ."</a></span><br><span style='text-align: center;'><a href='?switch=utilities&tab=email_templates&sub=new_template&id=" . $row['ID'] . "'>" . _e('edit', '', '', true) . "</a></span></td><td style='text-align: center; vertical-align: middle;'>" . addZerosToShow(LBDP($row['date_created'])) . "</td></tr>";
                }
                $res->free();
                ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>