<?php
require_once '../c_config.php';

$conn = MySQL::open_conn();

?>
<?php
$query  = "SELECT * FROM c_plugins";
$res = $conn->query($query);
$num_rows = $res->num_rows;
dbQueryCheck($res, $conn);
?>
    <!--<div class="col col-sm-3 col-md-8 col-md-offset-2">-->
    <span style="float:<?php echo getLBA(); ?>;"><?php _e('installed_plugins_count', '', ':'); ?></span>
    <span style="float:<?php echo getLBA_rev(); ?>;"><?php echo $num_rows; ?></span><br><br>
    <!--</div>-->
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
    <div id="tablediv">
        <table width="auto" class="table table-bordered table-hover">
            <tr>
                <th><?php _e('plugin_name'); ?></th>
                <th><?php _e('plugin_version'); ?></th>
                <th><?php _e('plugin_type'); ?></th>
                <th><?php _e('plugin_status'); ?></th>
                <th><?php _e('plugin_last_update_date'); ?></th>
            </tr>
            <?php

            while($row = $res->fetch_assoc())
            {
                if($row['plugin_version'] == '1')
                {
                    $row['plugin_version'] = '1.0';
                }
                if($row['included'] == '1')
                {
                    $row['included'] = 'Included';
                }
                else
                {
                    $row['included'] = 'User made';
                }
                if($row['plugin_status'] == '1')
                {
                    $row['plugin_status'] = 'Active';
                }
                else
                {
                    $row['plugin_status'] = 'Not Active or Needs Repair';
                }

                echo "<tr><td><a href='?switch=settings&plugin=" . $row['ID'] . "'>" . ucfirst($row['plugin_name']) ."</a></td><td>" . $row['plugin_version'] . "</td><td>" . $row['included'] . "</td><td style='color: red;'>" . $row['plugin_status'] . "</td><td>" . addZerosToShow(LBDP($row['latest_update_date'])) . "</td></tr>";
            }
            $res->free();
            ?>
        </table>
    </div>
<?php
$conn->close();
?>