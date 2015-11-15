<?php

class URI
{
    static function checkPage()
    {
        $url = parse_url($_SERVER['REQUEST_URI']);
        $params = explode('/', $url['path']);
        $add = getSUBDIRPiecesCount();

        if(count($params) && strlen($params[1+$add]) == 0) // works?
        {
            return 'Home';
        }
        elseif(isset($params[2+$add]) && $params[2+$add] == 'post' && is_numeric($params[1+$add]))
        {
            return 'Single';
        }
        elseif(isset($params[2+$add]) && $params[2+$add] == 'page' && is_numeric($params[1+$add]))
        {
            return 'Page';
        }
        elseif(isset($params[1+$add]))
        {
            if(file_exists(INCLUDE_FIX . $params[1+$add]))
            {
                return $params[1+$add];
            }
            elseif(file_exists(INCLUDE_FIX . $params[1+$add] . '.php'))
            {
                return $params[1+$add] . '.php';
            }
            elseif(file_exists(INCLUDE_FIX . $params[1+$add] . '.html'))
            {
                return $params[1+$add] . '.html';
            }
            else
            {
                return '404';
            }
        }
        return '404';
    }

    static function getParams_single()
    {
        $add = getSUBDIRPiecesCount();
        $url = parse_url($_SERVER['REQUEST_URI']);
        $params = explode('/', $url['path']);
        $assoc_params = ['id'=>$params[1+$add], 'type'=>$params[2+$add], 'link_title'=>$params[3+$add]];
        return $assoc_params;
    }

    static function getPageId()
    {
        return self::getParams_single()['id'];
    }
}