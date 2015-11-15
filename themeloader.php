<?php

    session_start();

    if(file_exists(INCLUDE_FIX . 'functions.php'))
    {
        require_once INCLUDE_FIX . 'functions.php';
    }

    if(URI::checkPage() == 'Home')
    {
        include_once INCLUDE_FIX . 'index.php';
    }
    elseif(URI::checkPage() == 'Single')
    {
        include_once INCLUDE_FIX . 'single.php';
    }
    elseif(URI::checkPage() == 'Page')
    {
        include_once INCLUDE_FIX . 'page.php';
    }
    elseif(URI::checkPage() == '404')
    {
        include_once INCLUDE_FIX . '404.php';
    }
    else
    {
        include_once INCLUDE_FIX . URI::checkPage();
    }