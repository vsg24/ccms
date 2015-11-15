<?php
$conn = MySQL::open_conn();

$query = "SELECT * FROM c_posts ORDER BY ID DESC";
$res = $conn->query($query);
dbQueryCheck($res, $conn);
?>
<?php
if(isset($_GET['sub']) && $_GET['sub'] == 'delete_post')
{
    $id = $_GET['id'];

    /*$query = "DELETE FROM c_posts_cats WHERE post_id = $id"; // remove all rows where post_id = this post
    $res = $conn->query($query);
    dbQueryCheck($res, $conn);

    $query = "DELETE FROM c_comments WHERE post_id = $id"; // remove all comments associated with this post
    $res = $conn->query($query);
    dbQueryCheck($res, $conn);*/

    $query  = "DELETE FROM c_posts WHERE ID = $id LIMIT 1"; // remove this post
    $res = $conn->query($query);
    dbQueryCheck($res, $conn);

    ob_end_clean();
    redirectTo('index.php?switch=manage_posts');
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
            <span style="float:<?php echo getLBA(); ?>;"><?php _e('posts_count', '', ':'); ?></span>
            <span style="float:<?php echo getLBA_rev(); ?>;"><?php echo Posts::getPostsCount(); ?></span><br>
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
                    <th style="vertical-align: middle;"><?php _e('author'); ?></th>
                    <th style="vertical-align: middle;"><?php _e('title'); ?></th>
                    <th style="vertical-align: middle;"><?php _e('category'); ?></th>
                    <th style="vertical-align: middle;"><?php _e('status'); ?></th>
                    <th style="vertical-align: middle;"><?php _e('submitted_date'); ?></th>
                </tr>
                <?php
                while($row = $res->fetch_assoc())
                {
                    if(isset($row['post_author']))
                    {
                        $username = Users::getUserById($row['post_author']);
                    }
                    if($row['post_status'] == 'Initialized')
                    {
                        $status = _e('initialized', '', '', true);
                        $statcolor = "style='text-align: center; color: red;'";
                    }
                    else
                    {
                        $status = _e('published', '', '', true);
                        $statcolor = "style='text-align: center;'";
                    }
                    $first_cat = getPostCategories($row['ID'])[0];
                    echo "<tr><td style='text-align: center; vertical-align: middle;'>" . $row['ID'] . "</td><td style='text-align: center; vertical-align: middle;'><a href='?switch=users&sub=edit_user&id=" . $row['post_author'] . "'>" . $username['username'] ."</a></td><td>" . $row['post_title'] . "</td><td style='text-align: center; vertical-align: middle;'><a href='?switch=categories&sub=edit_category&id=" . $first_cat . "'>" . getCategoryById($first_cat) . "</a></td><td " . $statcolor . ">" . $status . "<br>" . "<span style='text-align: center;'><a onclick=\"return confirm('Are you sure?');\" href='?switch=manage_posts&sub=delete_post&id=" . $row['ID'] . "'>" . _e('delete', '', '', true) ."</a></span>"  . "<br>" . "<span style='text-align: center;'><a href='?switch=new_post&sub=edit_post&id=" . $row['ID'] . "'>" . _e('edit', '', '', true) ."</a></span></td><td style='text-align: center; vertical-align: middle;'>" . addZerosToShow(LBDP($row['post_date'])) . "</td></tr>";
                }
                $res->free();
                ?>
                </table>
                <?php $home = "?switch=users&sub=edit_user"; ?>
                <!--<script>
                    function gotoEdit(ref)
                    {
                        var id = ref.id;
                        window.location.assign("<?php echo $home; ?>&id=" + id);
                    }
                </script>-->
            </div>
        </div>
    </div>
</div>

<?php $conn->close(); ?>