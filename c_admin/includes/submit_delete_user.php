<?php

if(isset($_POST["submit_delete_user"]))
{
    $id = $_GET['id'];

    if($id == Users::getIDBySeassion())
    {
        goToError('index.php?switch=users#users_list', _e('cant_remove_logged_in_user', '', '', true));
        goto end;
    }

    $query  = "DELETE FROM c_users WHERE ID = $id LIMIT 1";

    $res = $conn->query($query);
    dbQueryCheck($res, $conn);

    ob_end_clean();
    redirectTo('index.php?switch=users#users_list');
    end:
}
