<?php

function _e($key, $def = '', $append = '', $mode = false)
{
    global $lang;
    if(isset($lang[$key]))
    {
        if(Language==='en' && $mode != true)
        {
            echo $lang[$key] . $append;
        }
        elseif(Language==='en' && $mode == true)
        {
            return $lang[$key] . $append;
        }
        elseif(Language==='fa' && $mode != true)
        {
            echo $append . $lang[$key];
        }
        elseif(Language==='fa' && $mode == true)
        {
            return $append . $lang[$key];
        }
    }
    else
    {
        echo $def . $append;
    }
}