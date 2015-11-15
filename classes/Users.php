<?php

class Users
{
    static function getUsersCount()
    {
        $conn = MySQL::open_conn();
        $query = "SELECT * FROM c_users";
        $res = $conn->query($query);
        return $res->num_rows;
    }

    static function getAdminsCount()
    {
        $conn = MySQL::open_conn();
        $query = "SELECT * FROM c_users WHERE user_role = 4";
        $res = $conn->query($query);
        $conn->close();
        return $res->num_rows;
    }

    static function getSuperUsersCount()
    {
        $conn = MySQL::open_conn();
        $query = "SELECT * FROM c_users WHERE user_role = 3";
        $res = $conn->query($query);
        $conn->close();
        return $res->num_rows;
    }

    static function getUserListSession()
    {
        if(isset($_SESSION['userlist_pp']))
        {
            return $_SESSION['userlist_pp'];
        }
        else
            return 1;
    }

    static function setUserListSession($userlist_pp)
    {
        $_SESSION['userlist_pp'] = $userlist_pp;
    }

    static function getUsernameBySeassion()
    {
        if(isset($_SESSION['username']))
            return $_SESSION['username'];
        else
            return false;
    }

    static function getIDBySeassion()
    {
        if(isset($_SESSION['user_id']))
            return $_SESSION['user_id'];
        else
            return false;
    }

    static function getUserById($id)
    {
        $conn = MySQL::open_conn();
        $query = "SELECT * FROM c_users WHERE ID = $id LIMIT 1";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);
        $row = $res->fetch_assoc();

        $data['username'] = $row['user_login'];
        $data['password'] =$row['user_pass'];
        $data['user_email'] = $row['user_email'];
        $data['user_role'] = $row['user_role'];
        $data['vip_status'] = $row['vip_status'];
        $data['activate'] = $row['activate'];

        $res->free();
        $conn->close();

        return $data;
    }

    /*static function getNickById($id)
    {
        $conn = MySQL::open_conn();
        $query = "SELECT display_name FROM c_users WHERE ID = $id LIMIT 1";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);
        $row = $res->fetch_assoc();
        $row = $row['display_name'];

        return $row;
    }*/

    static function getIdByUsername($username)
    {
        $conn = MySQL::open_conn();
        $query = "SELECT * FROM c_users WHERE user_login = '$username' LIMIT 1";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);
        $row = $res->fetch_assoc();

        $data = $row['ID'];

        $res->free();
        $conn->close();

        return $data;
    }

    static function userRoleToString($row)
    {
        if($row['user_role'])
        {
            if($row['user_role'] == 4)
            {
                return 'Admin';
            }
            elseif($row['user_role'] == 3)
            {
                return 'Super User';
            }
            elseif($row['user_role'] == 2)
            {
                return 'Subscriber';
            }
            elseif($row['user_role'] == 1)
            {
                return 'Simple';
            }
            else
            {
                return 'Not Defined';
            }
        }
    }

    static function userExists($username)
    {
        $conn = MySQL::open_conn();
        $query = "SELECT * FROM c_users WHERE user_login = '$username' LIMIT 1";
        $res = $conn->query($query);

        dbQueryCheck($res, $conn);
        $row_cnt = $res->num_rows;

        $res->free();
        $conn->close();

        if($row_cnt>0) return true;
        else return false;
    }

    static function userExistsById($id)
    {
        $conn = MySQL::open_conn();
        $query = "SELECT * FROM c_users WHERE ID = $id LIMIT 1";
        $res = $conn->query($query);

        dbQueryCheck($res, $conn);
        $row_cnt = $res->num_rows;

        $res->free();
        $conn->close();

        if($row_cnt>0) return true;
        else return false;
    }

    static function emailExist($email)
    {
        $conn = MySQL::open_conn();
        $query = "SELECT * FROM c_users WHERE user_email = '$email' LIMIT 1";
        $res = $conn->query($query);

        dbQueryCheck($res, $conn);
        $row_cnt = $res->num_rows;

        $res->free();
        $conn->close();

        if($row_cnt>0) return true;
        else return false;
    }

    static function isAdmin($username)
    {
        $username = test_input($username);
        $conn = MySQL::open_conn();
        $query = "SELECT user_role FROM c_users WHERE user_login = '$username' LIMIT 1";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);
        $row = $res->fetch_assoc();
        if((int) $row['user_role'] === 4)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    static function isVIP($username)
    {
        $username = test_input($username);
        $conn = MySQL::open_conn();
        $query = "SELECT vip_status FROM c_users WHERE user_login = '$username' LIMIT 1";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);
        $row = $res->fetch_assoc();
        if((int) $row['vip_status'] != 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    static function isVIPById($id)
    {
        $conn = MySQL::open_conn();
        $query = "SELECT vip_status FROM c_users WHERE ID = $id LIMIT 1";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);
        $row = $res->fetch_assoc();
        if((int) $row['vip_status'] != 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    static function getVIPUsersCount()
    {
        $conn = MySQL::open_conn();
        $query = "SELECT * FROM c_users WHERE vip_status <> 0";
        $res = $conn->query($query);
        return $res->num_rows;
    }

    // submits a new user, suitable for forms in themes. successful only if returns zero (0)
    // error codes: 1, 2, 3, [4], 5
    // success: 0
    // description below
    static function submitNewUser($username, $password, $email, $password2 = null)
    {
        if(!registerUsers())
            return 5; // admin has disabled registering new users

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $username = validateUserName($username) ? $username : false;
        $password = validatePassword($password) ? $password : false;
        if(!$username || !$password)
        {
            return 1; // username or password does not meet the requirements
        }
        if(isset($password2))
        {
            $password2 = validatePassword($password2) ? $password2 : false;
            if($password2 != false)
            {
                //$password2 = passwordHash($password2);
                if($password2 != $password)
                {
                    return 4; // passwords do not match
                }
            }
            else
            {
                return 4;
            }
        }
        $password = passwordHash($password);
        $current_datetime = jDateTime::gdate('Y-m-d H:i:s');

        if(Users::userExists($username))
        {
            return 2; // username already exist
        }

        $activate = md5($email . time());

        $conn = MySQL::open_conn();
        $query  = "INSERT INTO c_users (user_login, user_pass, user_email, user_registered, activate) ";
        $query .= "VALUES ('$username', '$password', '$email', '$current_datetime', '$activate')";

        $res = $conn->query($query);
        if(!$res) return 3; // unknown error while creating new user

        $row = $conn->query("SELECT MAX(ID) AS max FROM c_users")->fetch_array();
        if($row)
        {
            $id = $row['max'];
        }

        if(shouldConfMail())
        {
            $mail_content = replace_template(getDefaultEmailTemplateContent(), getConfEmailTemplateVars($id));

            Email::sendMail($email, $username, getConfMailSubject(), $mail_content);
        }

        return 0;
    }

    // verifies user login, suitable for forms in themes
    // returns true on success and false on failure
    // you should set session after success
    static function verifyUser($username, $password)
    {
        $username = test_input($username);
        $password = test_input($password);

        $conn = MySQL::open_conn();
        $query = "SELECT * FROM c_users WHERE user_login = '$username' LIMIT 1";
        $res = $conn->query($query);

        dbQueryCheck($res, $conn);
        $row = $res->fetch_assoc();

        $hpassword = $row['user_pass'];

        $res->free();
        $conn->close();

        $stat = passwordVerify($password, $hpassword);

        if($stat)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    static function makeSimpleUser($name, $email)
    {
        $conn = MySQL::open_conn();
        $name = htmlspecialchars(stripcslashes($name));
        $email = htmlspecialchars(stripcslashes($email));
        $query = "INSERT INTO c_users (user_login, user_email, user_role) VALUES ('$name', '$email', 1)";
        if($conn->query($query))
        {
            $conn->close();
            return 0;
        }
        else
        {
            $conn->close();
            return false;
        }
    }

    static function getSimpleUserForComments($email)
    {
        $query = "SELECT ID FROM c_users WHERE (user_email = '$email') AND (user_role = 1) LIMIT 1";
        $conn = MySQL::open_conn();
        $res = $conn->query($query);
        if($res->num_rows > 0)
        {
            return $res->fetch_row()[0];
        }
        else
        {
            return false;
        }
    }

    static function submitSimpleUserForComments($name, $email)
    {
        if($id = self::getSimpleUserForComments($email))
        {
            return $id;
        }
        elseif(self::emailExist($email))
        {
            return false;
        }
        elseif(self::makeSimpleUser($name, $email) == 0)
        {
            $query = "SELECT ID FROM c_users WHERE user_email = '$email' LIMIT 1";
            $conn = MySQL::open_conn();
            return $conn->query($query)->fetch_row()[0];
        }
        else
        {
            return false; // set default maybe?
        }
    }
}