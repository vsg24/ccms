<?php

$conn = MySQL::open_conn();

if(isset($_POST['email_submit']))
{
    $server = $_POST['smtp_server'];
    $port = $_POST['smtp_port'];
    $username = $_POST['smtp_username'];
    $password = $_POST['smtp_password'];
    $from = $_POST['smtp_mail_from'];
    $from_name = $_POST['smtp_mail_from_name'];
    $def_tem = $_POST['def_tem'];

    $query = "UPDATE c_options SET option_value = '$server' WHERE option_name = 'smtp_server'";
    $res = $conn->query($query);

    $query = "UPDATE c_options SET option_value = '$port' WHERE option_name = 'smtp_port'";
    $res = $conn->query($query);

    $query = "UPDATE c_options SET option_value = '$username' WHERE option_name = 'smtp_username'";
    $res = $conn->query($query);

    $query = "UPDATE c_options SET option_value = '$password' WHERE option_name = 'smtp_password'";
    $res = $conn->query($query);

    $query = "UPDATE c_options SET option_value = '$from' WHERE option_name = 'smtp_mail_from'";
    $res = $conn->query($query);

    $query = "UPDATE c_options SET option_value = '$from_name' WHERE option_name = 'smtp_mail_from_name'";
    $res = $conn->query($query);

    $query = "UPDATE c_options SET option_value = '$def_tem' WHERE option_name = 'default_email_template'";
    $res = $conn->query($query);
}

$query = "SELECT * FROM c_options WHERE option_name = 'smtp_server'";
$res = $conn->query($query);
dbQueryCheck($res, $conn);
$smtp_server = $res->fetch_assoc();
$smtp_server = $smtp_server['option_value'];

$query = "SELECT * FROM c_options WHERE option_name = 'smtp_port'";
$res = $conn->query($query);
dbQueryCheck($res, $conn);
$smtp_port = $res->fetch_assoc();
$smtp_port = $smtp_port['option_value'];

$query = "SELECT * FROM c_options WHERE option_name = 'smtp_username'";
$res = $conn->query($query);
dbQueryCheck($res, $conn);
$smtp_username = $res->fetch_assoc();
$smtp_username = $smtp_username['option_value'];

$query = "SELECT * FROM c_options WHERE option_name = 'smtp_password'";
$res = $conn->query($query);
dbQueryCheck($res, $conn);
$smtp_password = $res->fetch_assoc();
$smtp_password = $smtp_password['option_value'];

$query = "SELECT * FROM c_options WHERE option_name = 'smtp_mail_from'";
$res = $conn->query($query);
dbQueryCheck($res, $conn);
$smtp_mail_from = $res->fetch_assoc();
$smtp_mail_from = $smtp_mail_from['option_value'];

$query = "SELECT * FROM c_options WHERE option_name = 'smtp_mail_from_name'";
$res = $conn->query($query);
dbQueryCheck($res, $conn);
$smtp_mail_from_name = $res->fetch_assoc();
$smtp_mail_from_name = $smtp_mail_from_name['option_value'];

function isAlsoDefault($tem)
{
    if($tem == getDefaultEmailTemplate())
        return 'selected="selected"';
    return '';
}

?>

<form action="" method="POST">
    <label for="smtp_server" style="float: <?php echo getLBA(); ?>;"><?php _e('smtp_server', '', ':'); ?></label>
    <?php if(Language == 'fa') : ?>
    <input type="text" name="smtp_server" id="smtp_server" style="float: <?php echo getLBA_rev(); ?>" value="<?php if(isset($smtp_server)) echo $smtp_server; ?>" required>
    <input type="number" name="smtp_port" placeholder="Port" id="smtp_port" style="float: <?php echo getLBA_rev(); ?>" value="<?php if(isset($smtp_port)) echo $smtp_port; ?>" required><br><br><br>
    <?php else : ?>
    <input type="number" name="smtp_port" placeholder="Port" id="smtp_port" style="float: <?php echo getLBA_rev(); ?>" value="<?php if(isset($smtp_port)) echo $smtp_port; ?>" required>
    <input type="text" name="smtp_server" id="smtp_server" style="float: <?php echo getLBA_rev(); ?>" value="<?php if(isset($smtp_server)) echo $smtp_server; ?>" required><br><br><br>
    <?php endif; ?>
    <label for="smtp_username" style="float: <?php echo getLBA(); ?>;"><?php _e('smtp_username', '', ':'); ?></label>
    <input type="text" name="smtp_username" id="smtp_username" style="float: <?php echo getLBA_rev(); ?>" value="<?php if(isset($smtp_username)) echo $smtp_username; ?>" required><br><br><br>
    <label for="smtp_password" style="float: <?php echo getLBA(); ?>;"><?php _e('smtp_password', '', ':'); ?></label>
    <input type="text" name="smtp_password" id="smtp_password" style="float: <?php echo getLBA_rev(); ?>" value="<?php if(isset($smtp_password)) echo $smtp_password; ?>" required><br><br><br>
    <hr>
    <label for="smtp_mail_from" style="float: <?php echo getLBA(); ?>;"><?php _e('mail_from', '', ':'); ?></label>
    <input type="text" name="smtp_mail_from" class="form_block" id="smtp_mail_from" style="float: <?php echo getLBA_rev(); ?>" value="<?php if(isset($smtp_mail_from)) echo $smtp_mail_from; ?>" required><br><br><br>
    <label for="smtp_mail_from_name" style="float: <?php echo getLBA(); ?>;"><?php _e('mail_from_name', '', ':'); ?></label>
    <input type="text" dir="auto" name="smtp_mail_from_name" class="form_block" id="smtp_mail_from_name" style="float: <?php echo getLBA_rev(); ?>" value="<?php if(isset($smtp_mail_from_name)) echo $smtp_mail_from_name; ?>" required><br><br><br>
    <label for="def_tem" style="float: <?php echo getLBA(); ?>;" id="def_tem" data-content="Default Template for confirmation emails" rel="popover" data-placement="bottom" data-original-title="Default Template" data-trigger="hover"><?php _e('default_template', '', ':'); ?></label>&nbsp
    <select name="def_tem" id="def_tem" style="float: <?php echo getLBA_rev(); ?>;">
        <?php
        $query = "SELECT ID, name FROM c_emails";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);
        while ($row = $res->fetch_assoc())
        {
            echo '<option value="' . $row['ID'] . '"' . isAlsoDefault($row['ID']) . '>' . $row['name'] . '</option>';
        }
        $res->free();
        ?>
    </select><br><br>
    <div align="center"><input type="submit" class="btn btn-info" name="email_submit" value="<?php _e('submit'); ?>"></div>
</form>
<script>
    $('#def_tem').popover();
</script>