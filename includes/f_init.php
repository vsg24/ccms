<?php

    function load_main_classes($className)
    {
        $path = CLASS_PATH . '/';
        require_once $path.$className.'.php';
    }

    require_once CLASS_PATH . '/PHPMailer/PHPMailerAutoload.php';

    spl_autoload_register('load_main_classes');

    foreach (glob(DOC_ROOT . "/includes/*.php") as $filename)
    {
        if(!strpos($filename, 'cache.php'))
        {
            require_once $filename;
        }
    }

    if(true)
    {
        require_once DOC_ROOT . "/c_contents/langs/" . Language . ".php";
    }

    function getLBA() // Language Based Align
    {
        if(Language == "fa" || Language == "ar")
            return "right";
        else
            return "left";
    }

    function getLBA_rev() // Language Based Align - Reverse
    {
        if(Language == "fa" || Language == "ar")
            return "left";
        else
            return "right";
    }