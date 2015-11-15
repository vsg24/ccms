<?php
if(isset($_POST['submit_exception']))
{
    $conn = MySQL::open_conn();
    $hash = md5($_POST['exception_rest_of_url']);
    $desc = $conn->real_escape_string($_POST['exception_description']);
    $query = "INSERT INTO c_ignored_cache (hash, description) VALUES ('$hash', '$desc')";
    $res = $conn->query($query);
    ob_end_clean();
    redirectTo('index.php?switch=utilities#cache');
}
if(isset($_GET['sub']) && $_GET['sub'] == 'delete_all_cache')
{
    clearAllCache();
}
if(isset($_GET['sub']) && $_GET['sub'] == 'new_exception')
{
    $sitedomain = SITE_DOMAIN;
    $rest_url = _e('rest_url', '', ':', true);
    $desc = _e('description', '', ':', true);
    $submit = _e('submit', '', '', true);
    $html =<<<HTML
                <form action="" method="POST" class=form-group">
                {$sitedomain}<br>
                    <label for="url">{$rest_url}</label>
                    <input id="url" class="form_block" placeholder="/45/post/this-is-an-example" type="text" name="exception_rest_of_url" required><br>
                    <label for="description">{$desc}</label>
                    <input dir="auto" id="description" class="form_block" type="text" name="exception_description"><br><br>
                    <div style="text-align: center"><input type="submit" class="btn btn-info" name="submit_exception" value="{$submit}"></div>
                </form>
                <br>
                <div style="text-align: left!important;">
                e.g.<br>
                Full URL: example.com/45/post/this-is-an-example<br>
                Rest of URL: <strong>/45/post/this-is-an-example</strong>
                </div>
HTML;
    echo $html;
    goto end;
}
if(isset($_GET['sub']) && $_GET['sub'] == 'change_stat')
{
    if(shouldCache())
    {
        $stat = 'no';
    }
    else
        $stat = 'yes';
    $query = "UPDATE c_options SET option_value = '$stat' WHERE option_name = 'caching'";
    $conn = MySQL::open_conn();
    $res = $conn->query($query);
    dbQueryCheck($res, $conn);
    ob_end_clean();
    redirectTo('index.php?switch=utilities#cache');
}
{
    $cache_stat = shouldCache();
    if($cache_stat == true)
    {
        $caching = _e('enabled', '', '', true);
    }
    else
    {
        $caching = _e('disabled', '', '', true);
    }
}
?>
<p><?php _e('click_to_change_status'); ?></p>
<span style="text-align: center"><?php _e('caching_status', '', ':'); ?><br><a href="index.php?switch=utilities&sub=change_stat"><?php echo $caching; ?></a></span><hr>
<a class="btn" href="index.php?switch=utilities&sub=new_exception#cache"><?php _e('new_exception'); ?></a>&nbsp;
<a class="btn btn-warning" href="index.php?switch=utilities&sub=delete_all_cache#cache"><?php _e('delete_all_cache'); ?></a>
<br>

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
<br>
<div class="container-fluid">
    <div class="row" style="text-align: center">
        <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
            <label for="exceptions_table"><?php _e('exceptions_list', '', ':'); ?></label>
            <div id="tablediv">
                <table id="exceptions_table" width="auto" class="table table-hover" ">
                <tr>
                    <th style="vertical-align: middle;"><?php _e('', 'Hash'); ?></th>
                    <th style="vertical-align: middle;"><?php _e('description'); ?></th>
                </tr>
                <?php
                $conn = MySQL::open_conn();
                $res = $conn->query("SELECT * FROM c_ignored_cache");
                while($row = $res->fetch_assoc())
                {
                    echo "<tr><td style='text-align: center; vertical-align: middle;'>" . $row['hash'] . "</td><td style='text-align: center; vertical-align: middle;'>" . $row['description'] . "</td></tr>";
                }
                $res->free();
                ?>
                </table>
                <?php $conn->close(); ?>
            </div>
        </div>
    </div>
</div>
<?php end: ?>