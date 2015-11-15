<?php

if(isset($_POST["submit_new_user"]))
{
    $username = $_POST['new_user_username'];
    $username = validateUserName($username) ? $_POST['new_user_username'] : false;
    $bh_password = $_POST['new_user_password'];
    $bh_password = validatePassword($bh_password) ? $_POST['new_user_password'] : false;
    $password = passwordHash($bh_password);
    $email = $_POST['new_user_email'];
    //$vip = isset($_POST['new_user_vip']) ? 1 : 0;
    $bp_role = $_POST['new_user_role'];
    $current_datetime = jDateTime::gdate('Y-m-d H:i:s');

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

    if(Users::userExists($username))
    {
        goToError('?switch=users#new_user', _e('username_already_exists', '', '', true));
    }

    if(!$username || !$password)
    {
        goToError('?switch=users#new_user', _e('username_password_wrong', '', '', true));
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

    $query  = "INSERT INTO c_users (user_login, user_pass, user_email, user_role, vip_status, vip_start_date, vip_expire_date, user_registered) ";
    $query .= "VALUES ('$username', '$password', '$email', $role, $vip, '$vip_start', '$vip_expire', '$current_datetime')";

    $res = $conn->query($query);
    if(!$res) goToError('?switch=users#new_user', _e('cant_create_new_user', '', '', true));

    ob_end_clean();
    redirectTo('?switch=users#users_list');
    end:
}