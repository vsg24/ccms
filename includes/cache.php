<?php

// check if page is in ignored cache list
{
    $hash = md5($_SERVER['REQUEST_URI']);
    $conn = MySQL::open_conn();
    $res = $conn->query("SELECT hash FROM c_ignored_cache");

    while($row = $res->fetch_row())
    {
        if($hash === $row[0])
            return;
    }

    if(Users::getIDBySeassion()) // we don't want to cache for logged in users
    {
        return;
    }
}

// return location and name for cache file
function cache_file()
{
    return CACHE_PATH . md5($_SERVER['REQUEST_URI']);
}
// display cached file if present and not expired
function cache_display()
{
    $file = cache_file();

    // check that cache file exists and is not too old
    if(!file_exists($file)) return;
    if(filemtime($file) < time() - CACHE_TIME * 3600)
    {
        @unlink($file);
        return;
    }
    // if so, display cache file and stop processing
    @gzdecode(readfile($file));
    exit;
}
// write to cache file
function cache_page($content)
{
    if(false !== ($f = @fopen(cache_file(), 'w')))
    {
        gzencode(fwrite($f, $content), 9);
        fclose($f);
    }
    return $content;
}
// execution stops here if valid cache file found
cache_display();
// enable output buffering and create cache file
ob_start('cache_page');