<?php getHeaderCUSTOM(); ?>

    <?php include_once 'sidebar.php'; ?>

<?php
$conn = MySQL::open_conn();
if(isset($_GET['activate']))
{
    $activate = $_GET['activate'];
    $query = "SELECT ID FROM c_users WHERE activate = '$activate'";
    $res = $conn->query($query);
    if(!$res)
    {
        ob_end_clean();
        goTo404();
    }
    $num_rows = $res->num_rows;
    if($num_rows > 0)
    {
        $id = $res->fetch_assoc()['ID'];
        $query = "UPDATE c_users SET activate = NULL WHERE ID = $id";
        $res = $conn->query($query);
        if(!$res)
        {
            ob_end_clean();
            goTo404();
        }
    }
    else
    {
        ob_end_clean();
        goTo404();
    }
}
else
{
    ob_end_clean();
    goTo404();
}
?>

    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-md-offset-1 col-lg-offset-1 div_white_left">
        <article class="post">
            <div align="center">Your account has been activated. You can now log in.</div>
        </article>
    </div>
<?php getFooter(); ?>