<?php

class Comments
{
    private $conn;
    private $i, $j; // just a dummy helper
    public $res;
    private $post_id;

    function __construct($post_id)
    {
        if(!isset($this->conn))
            $this->conn = MySQL::open_conn();
        $this->post_id = $post_id;
    }

    /*static function makeComment($post_id, $user_id, $content)
    {
        $conn = MySQL::open_conn();
        $conn->begin_transaction();
        $content = escapeSingleQuotes(strip_tags($content, '<b><i><strong><em><p>'));
        if(mb_strlen($content) < 8)
            return false;
        $res1 = $conn->query("INSERT INTO c_comments (post_id, user_id, content, status) VALUES ($post_id, $user_id, '$content', 'Published')");
        $res2 = $conn->query("UPDATE c_posts SET comment_count = comment_count + 1 WHERE ID = $post_id");
        if($res1 && $res2)
        {
            $conn->commit();
            return true;
        }
        else
        {
            $conn->rollback();
            return false;
        }
    }*/

    static function makeComment($post_id, $user_id, $content)
    {
        $conn = MySQL::open_conn();
        $conn->begin_transaction();
        $content = strip_tags($content, '<b><i><strong><em><p>');
        if(mb_strlen($content) < 8)
            return false;

        if(!Users::userExistsById($user_id))
        {
            return false;
        }

        $res1 = $conn->prepare("INSERT INTO c_comments (post_id, user_id, content, status) VALUES (?, ?, ?, ?)");
        $stat = 'Published';
        $res1->bind_param('iiss', $post_id, $user_id, $content, $stat);
        $res1->execute();

        $res2 = $conn->query("UPDATE c_posts SET comment_count = comment_count + 1 WHERE ID = $post_id");

        if($res1 && $res2)
        {
            $conn->commit();
            return true;
        }
        else
        {
            $conn->rollback();
            return false;
        }
    }

    function getCommentsLoop()
    {
        if(!isset($loop_comment_query))
        {
            $loop_comment_query = "SELECT * FROM c_comments WHERE post_id = $this->post_id AND status <> 'Trashed' ORDER BY date";
        }
        if(!isset($this->i))
        {
            $this->res = $this->conn->query($loop_comment_query);
            dbQueryCheck($this->res, $this->conn);
            $this->i = 'set';
        }
        return $this->res->fetch_assoc();
    }
}