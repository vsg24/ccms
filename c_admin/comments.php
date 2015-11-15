<?php
$conn = MySQL::open_conn();

$query = "SELECT * FROM c_comments ORDER BY date DESC";
$res = $conn->query($query);
dbQueryCheck($res, $conn);
?>
<?php
if(isset($_GET['sub']) && $_GET['sub'] == 'delete_comment')
{
    $id = $_GET['id'];
    $query  = "DELETE FROM c_comments WHERE ID = $id LIMIT 1";
    $res = $conn->query($query);
    dbQueryCheck($res, $conn);
    ob_end_clean();
    redirectTo('index.php?switch=comments');
}
if(isset($_GET['sub']) && $_GET['sub'] == 'trash_comment')
{
    $id = $_GET['id'];
    $query  = "UPDATE c_comments SET status = 'Trashed' WHERE ID = $id LIMIT 1";
    $res = $conn->query($query);
    dbQueryCheck($res, $conn);
    ob_end_clean();
    redirectTo('index.php?switch=comments');
}
if(isset($_GET['sub']) && $_GET['sub'] == 'clean_comment')
{
    $id = $_GET['id'];
    $query  = "UPDATE c_comments SET status = 'Published' WHERE ID = $id LIMIT 1";
    $res = $conn->query($query);
    dbQueryCheck($res, $conn);
    ob_end_clean();
    redirectTo('index.php?switch=comments');
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
    <div class="row" style="text-align: center;">
        <div class="col-xs-6 col-sm-4 col-md-4 col-xs-offset-3 col-sm-offset-4 col-md-offset-4">
            <span style="float:<?php echo getLBA(); ?>;"><?php _e('comments_count', '', ':'); ?></span>
            <span style="float:<?php echo getLBA_rev(); ?>;"><?php echo getCommentsCount(); ?></span><br>
        </div>
    </div>
</div><br>

<div class="container-fluid">
    <div class="row" style="text-align: center;">
        <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
            <div id="tablediv">
                <table width="auto" class="table table-hover" ">
                <tr>
                    <th style="vertical-align: middle;"><?php _e('', 'ID'); ?></th>
                    <th style="vertical-align: middle;"><?php _e('author'); ?></th>
                    <th style="vertical-align: middle;"><?php _e('comment'); ?></th>
                    <th style="vertical-align: middle;"><?php _e('post'); ?></th>
                    <th style="vertical-align: middle;"><?php _e('status'); ?></th>
                    <th style="vertical-align: middle;"><?php _e('submitted_date'); ?></th>
                </tr>
                <?php
                while($row = $res->fetch_assoc())
                {
                    if(isset($row['user_id']))
                    {
                        $user = Users::getUserById($row['user_id']);
                    }
                    if($row['status'] == 'Trashed')
                    {
                        $status = _e('trashed', '', '', true);
                        $statcolor = "style='text-align: center; color: red;'";
                        $t_button = "<a href='?switch=comments&sub=clean_comment&id=" . $row['ID'] . "'>" . _e('clean', '', '', true) ."</a>";
                    }
                    else
                    {
                        $status = _e('published', '', '', true);
                        $statcolor = "style='text-align: center;'";
                        $t_button = "<a href='?switch=comments&sub=trash_comment&id=" . $row['ID'] . "'>" . _e('trash', '', '', true) ."</a>";
                    }
                    echo "<tr><td style='text-align: center; vertical-align: middle;'>" . $row['ID'] . "</td><td style='text-align: center; vertical-align: middle;'><a href='?switch=users&sub=edit_user&id=" . $row['user_id'] . "'>" . $user['username'] ."</a></td><td>" . $row['content'] . "</td><td>" . Posts::getPostTitleById($row['post_id']) . "</td><td " . $statcolor . ">" . $status . "<br>" . "<span style='text-align: center;'><a onclick=\"return confirm('Are you sure?');\" href='?switch=comments&sub=delete_comment&id=" . $row['ID'] . "'>" . _e('delete', '', '', true) ."</a></span>"  . "<br>" . "<span style='text-align: center;'>" . $t_button . "</span></td><td style='text-align: center; vertical-align: middle;'>" . addZerosToShow(LBDP($row['date'])) . "</td></tr>";
                }
                $res->free();
                ?>
                </table>
                <?php $home = "?switch=users&sub=edit_user"; ?>
                <script>
                    function gotoEdit(ref)
                    {
                        var id = ref.id;
                        window.location.assign("<?php echo $home; ?>&id=" + id);
                    }
                </script>
            </div>
        </div>
    </div>
</div>

<?php $conn->close(); ?>