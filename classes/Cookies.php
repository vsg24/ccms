<?php

class Cookies
{
    static function setLoginCookies($user_id, $time) // $time is in days
    {
        $user_id_cookie = 'user_id';
        $nonce_cookie = 'token';
        $user_id = test_input($user_id);
        $token = md5(mt_rand());
        $user_ip = $_SERVER['REMOTE_ADDR'];
        $expire_date = strtotime('+' . $time . ' days', time());

        setcookie($user_id_cookie, $user_id, time() + (86400 * $time), "/"); // 86400 = 1 day
        setcookie($nonce_cookie, $token, time() + (86400 * $time), "/"); // 86400 = 1 day

        $query = "INSERT INTO c_nonce (user_id, token, user_ip, expire_date) VALUES ($user_id, '$token', '$user_ip', '$expire_date')";
        $conn = MySQL::open_conn();
        $conn->query($query);

        $cookie_names = [$user_id_cookie, $nonce_cookie];
        return $cookie_names;
    }

    static function verifyLoginCookies()
    {
        $user_id = $_COOKIE['user_id'];
        $token = $_COOKIE['token'];

        if(verifyLoginNonce($user_id, $token))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    static function isSetLoginCookies()
    {
        if(isset($_COOKIE['user_id']) && isset($_COOKIE['token']))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    static function getLoginIdFromCookie()
    {
        if(isset($_COOKIE['user_id']))
        {
            return $_COOKIE['user_id'];
        }
        return '';
    }

    static function getLoginPasswordFromCookie()
    {
        if(isset($_COOKIE['password']))
        {
            return $_COOKIE['password'];
        }
        return '';
    }

    static function unsetCookie()
    {
        $user_id_cookie = 'user_id';
        $nonce_cookie = 'token';
        $cookie_path = "/";
        setcookie($user_id_cookie, "", time()-3600, $cookie_path);
        setcookie($nonce_cookie, "", time()-3600, $cookie_path);
        unset($_COOKIE[$user_id_cookie]);
        unset($_COOKIE[$nonce_cookie]);
    }
}