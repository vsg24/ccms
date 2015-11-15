<?php
require_once '../c_config.php';

$conn = MySQL::open_conn();
$current_list_page = Users::getUserListSession();

$per_page = 10; // number of users to show in table in each page
$total_users = Users::getUsersCount();
$total_pages = ceil($total_users/$per_page);

if(isset($_POST['pre']))
{
    $current_list_page -= 1;
}
elseif(isset($_POST['nxt']))
{
    $current_list_page += 1;
}
elseif(isset($_POST['first']))
{
    $current_list_page = 1;
}
elseif(isset($_POST['last']))
{
    $current_list_page = $total_pages;
}

$offset = ($current_list_page - 1) * $per_page;
$current_by_all = (int) $current_list_page/$total_pages;

?>

<!--<div class="col col-sm-3 col-md-8 col-md-offset-2">-->
<span style="float:<?php echo getLBA(); ?>;"><?php _e('registered_users_count', '', ':'); ?></span>
<span style="float:<?php echo getLBA_rev(); ?>;"><?php echo $total_users; ?></span><br>
<span style="float:<?php echo getLBA(); ?>;"><?php _e('current_page', '', ':') ?></span>
<span style="float:<?php echo getLBA_rev(); ?>;"><?php echo $current_list_page . '/' . $total_pages; ?></span><br><br>
<!--</div>-->

<?php
$query  = "SELECT * FROM c_users ";
$query .= "LIMIT {$per_page} ";
$query .= "OFFSET {$offset}";

$res = $conn->query($query);
dbQueryCheck($res, $conn);
?>
    <style>
        #tablediv {
            display:flex;
            justify-content:center;
            align-items:center;
        }

        th, tr {
            text-align: center;
        }
    </style>
    <div id="tablediv">
        <table width="auto" class="table table-hover">
            <tr>
                <th><?php _e('username'); ?></th>
                <th><?php _e('role'); ?></th>
                <th><?php _e('vip'); ?></th>
                <th><?php _e('user_registered_date'); ?></th>
            </tr>
            <?php

            while($row = $res->fetch_assoc())
            {
                if(Users::isVIPById($row['ID']))
                {
                    $vip_status = _e('yes', '', '', true);
                }
                else
                    $vip_status = '';

                $row['user_role'] = Users::userRoleToString($row);
                echo "<tr><td><a id='" . $row['ID'] . "' style='cursor: pointer;' onclick='gotoEdit(this)'>" . $row['user_login'] ."</a></td><td>" . $row['user_role'] . "</td><td style='color: red;'>" . $vip_status . "</td><td>" . addZerosToShow(LBDP($row['user_registered'])) . "</td></tr>";
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

<div align="center">
<form class="btn-group" action="#users_list" method="POST">
<?php if(Users::getUsersCount() > 3) : ?>
    <?php if($total_pages != 1) : ?>
    <input type="submit" class="btn btn-pagination" value="<?php _e('first'); ?>" name="first">
    <?php endif; ?>
        &nbsp;
    <?php if($current_list_page != 1) : ?>
    <input type="submit" class="btn" value="<?php _e('previous'); ?>" name="pre">
        &nbsp;
    <?php endif ?>
<!--for($i=1; $i<=$pagination->totalPages(); $i++)-->
<!--<a href=\"index.php?page={$i}\">{$i}</a>&nbsp;-->
    <?php if($current_by_all < 1) : ?>
    <input type="submit" class="btn" value="<?php _e('next'); ?>" name="nxt">
        &nbsp;
    <?php endif ?>
    <?php if($total_pages != 1) : ?>
    <input type="submit" class="btn btn-pagination" value="<?php _e('last'); ?>" name="last">
    <?php endif; ?>
</form>
</div>

<?php endif ?>


<?php
$conn->close();
Users::setUserListSession($current_list_page);

?>