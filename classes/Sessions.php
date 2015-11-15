<?php

class Sessions
{
    private $logged_in = false;

    function __construct()
    {
        if(!isset($_SESSION))
        {
            session_start();
        }
    }

    function setSession($id, $username)
    {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        if(isset($_SESSION['user_id']))
        {
            $this->logged_in = true;
            $this->log_in();
        }
    }

    static function staticSetSession($id, $username)
    {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
    }

    static function checkLogStatus()
    {
        if(!isset($_SESSION))
        {
            session_start();
        }
        if(!isset($_SESSION['user_id']) && !isset($_SESSION['usernname']))
        {
            redirectTo("login.php");
        }
    }

    function checkLogStatusPlusCookies()
    {
        if(!isset($_SESSION))
        {
            session_start();
        }
        if(!isset($_SESSION['user_id']))
        {
            if(Cookies::isSetLoginCookies())
            {
                if(Cookies::verifyLoginCookies())
                {
                    $id = Cookies::getLoginIdFromCookie();
                    $username = Users::getUserById($id)['username'];
                    $this->setSession($id, $username);
                }
                else
                {
                    redirectTo('login.php');
                }
            }
            else
            {
                redirectTo('login.php');
            }
        }
    }

    static function checkLogStatusForSite()
    {
        if(!isset($_SESSION))
        {
            session_start();
        }
        if(!isset($_SESSION['user_id']))
        {
            if(Cookies::isSetLoginCookies())
            {
                if(Cookies::verifyLoginCookies())
                {
                    $id = Cookies::getLoginIdFromCookie();
                    $username = Users::getUserById($id)['username'];
                    self::staticSetSession($id, $username);
                }
            }
        }
    }

    static function unsetSession()
    {
        if(!isset($_SESSION))
        {
            session_start();
        }
        if(isset($_SESSION['user_id']) && (isset($_SESSION['username'])))
        {
            unset($_SESSION['user_id']);
            unset($_SESSION['username']);
        }
    }

    function log_in()
    {
        if($this->logged_in)
        {
            redirectTo("index.php");
        }
        else
        {
            redirectTo("login.php");
        }
    }

}