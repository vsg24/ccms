<?php

class Page {

    private $title, $stylesheets=array(), $javascripts=array(), $body;

    function __construct()
    {
        //$this->addCSS( THEME_PATH . 'css/main.css' );
    }

    function setTitle($title)
    {
        $this->title = $title;
    }

    function loadCustomFunctions()
    {
        $filename = THEME_BASE . "functions.php";

        if(file_exists($filename))
        {
            require_once $filename;
        }
    }

    function checkCSS()
    {
        $filename = getThemeRoot() . "/style.css";

        if(!file_exists($filename))
        {
            die("style.css Should be used as main stylesheet for your theme. Please correct this.");
        }
    }

    function get_header( $name = null )
    {
        $filename = getThemeRoot() . '/';
        $filename .= ($name === null) ? "header.php" : $name;

        include $filename;
    }

    function get_footer( $name = null )
    {
        $filename = getThemeRoot() . '/';
        $filename .= ($name === null) ? "footer.php" : $name;

        include $filename;
    }

    function startBody()
    {
        ob_start();
    }

    function endBody()
    {
        $this->body = ob_get_clean();
    }

    function render($path)
    {
        ob_start();
        include($path);
        return ob_get_clean();
    }

}