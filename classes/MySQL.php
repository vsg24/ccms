<?php

class MySQL
{

    static function open_conn()
    {
        $db = new mysqli(DB_LOC, DB_USER, DB_PASS, DB_NAME); // These constants are defined in site_root/c_config.php

        if($db->connect_errno)
        {
            die("MySQL connection failed: " . $db->connect_error);
        }

        if (!$db->set_charset("utf8")) {
            printf("Error loading character set utf8: %s\n", $db->error);
        }

        return $db;
    }

}