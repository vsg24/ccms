<?php

if(isset($_POST["submit_update_user"]))
{
    $id = $_GET['id'];
    $changepass = false;
    //$username = $_POST['new_user_username'];
    //$username = validateUserName($username) ? $_POST['new_user_username'] : false;
    if(!empty($_POST['new_user_password']))
    {
        $changepass = true;
        $bh_password = $_POST['new_user_password'];
        $bh_password = validatePassword($bh_password) ? $_POST['new_user_password'] : false;
        $password = passwordHash($bh_password);
    }
    $email = $_POST['new_user_email'];
    //$vip = isset($_POST['new_user_vip']) ? 1 : 0;
    $bp_role = $_POST['new_user_role'];

    $bp_vip = $_POST['new_user_vip'];

    if($bp_vip == 0)
    {
        $vip = 0;
        $vip_start = null;
        $vip_expire = null;
    }
    elseif($bp_vip == -1)
    {
        $vip = -1;
        $vip_start = $current_datetime;
        $vip_expire = null;
    }
    else
    {
        $vip = $bp_vip;
        $vip_start = strtotime($current_datetime);
        $vip_expire = strtotime('+' . $vip . ' day', $vip_start);
        $vip_start = $current_datetime;
        $vip_expire = date('Y-m-d H:i:s', $vip_expire);
    }

    if($changepass)
    {
        if(!$bh_password)
        {
            formErrorSet();
            goto end2;
        }
    }

    if($bp_role == 'Admin')
    {
        $role = 4;
    }
    elseif($bp_role == 'Super User')
    {
        $role = 3;
    }
    elseif($bp_role == 'Subscriber')
    {
        $role = 2;
    }

    $query  = "UPDATE c_users SET ";
    $query .= ($changepass) ? "user_pass = '$password', " : '';
    $query .= "user_email = '$email', user_role = $role, vip_status = $vip, vip_start_date = '$vip_start', vip_expire_date = '$vip_expire' WHERE ID = $id";

    $res = $conn->query($query);
    dbQueryCheck($res, $conn);

    ob_end_clean();
    redirectTo('index.php?switch=users#users_list');
    end2:
}