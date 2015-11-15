<?php
$conn = MySQL::open_conn();
$query  = "SELECT * FROM c_categories";
$res = $conn->query($query);
dbQueryCheck($res, $conn);
?>
<style>
    #tablediv {
        display:flex;
        justify-content:center;
        align-items:center;
        /*margin: 2%;*/
    }

    th, tr {
        text-align: center;
    }
</style>
<br>
<div id="tablediv">
    <table width="auto" class="table table-hover">
        <tr>
            <th><?php _e('', 'ID'); ?></th>
            <th><?php _e('name'); ?></th>
            <th><?php _e('link_name'); ?></th>
            <th><?php _e('posts_count'); ?></th>
        </tr>
        <?php
            while($row = $res->fetch_assoc())
            {
                echo "<tr><td>" . $row['ID'] ."</td><td><a href='?switch=categories&sub=edit_category&id=" . $row['ID'] . "'>" . $row['name'] . "</a></td><td>" . $row['link_name'] . "</td><td>" . getCategoryPostCountById($row['ID']) . "</td></tr>";
            }
        ?>
    </table>
</div>