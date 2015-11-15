<?php
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

class Posts
{
    private $conn;
    private $i, $p, $j; // just a dummy helper
    public $res;
    public $search_res_count;
    public $hasPreviousPage;
    public $hasNextPage;
    public $previousPage;
    public $nextPage;
    public $total_pages;

    function __construct()
    {
        if(!isset($this->conn))
            $this->conn = MySQL::open_conn();
    }

    static function getPostsCount()
    {
        $conn = MySQL::open_conn();
        $query = "SELECT COUNT(*) AS count FROM c_posts";
        $res = $conn->query($query);
        $row = $res->fetch_assoc();
        $count = (int) $row['count'];
        $conn->close();
        return $count;
    }

    function getMaxPostPerPage()
    {
        $query = "SELECT * FROM c_options WHERE option_name = 'max_post_pp' LIMIT 1";
        $res = $this->conn->query($query);
        $row = $res->fetch_assoc();
        $post_pp = (int) $row['option_value'];
        return $post_pp;
    }

    function getCurrentPageNumber()
    {
        $page = !empty($_GET['page']) ? (int) $_GET['page'] : 1;
        return $page;
    }

    // if used inside a while loop, will return all posts and pages, you may then echo (print) the desired parts of them
    // gets one optional parameter, max_pp sets the maximum number of posts per page
    function getPostsLoop($max_pp = null, $type = 'post')
    {
        if(!isset($loop_post_query))
        {
            if($type == 'post') $type = 'post'; elseif($type == 'page') $type = 'page';
            $pagination = new Pagination($max_pp);
            $loop_post_query = "SELECT * FROM c_posts WHERE (post_type = '$type') AND (post_status <> 'Initialized') ORDER BY ID DESC ";
            $loop_post_query .= "LIMIT $pagination->per_page ";
            $loop_post_query .= "OFFSET {$pagination->offset()}";
        }
        if(!isset($this->i))
        {
            $this->res = $this->conn->query($loop_post_query);
            dbQueryCheck($this->res, $this->conn);
            $this->i = 'set';
        }
        $this->hasPreviousPage = $pagination->hasPreviousPage();
        $this->hasNextPage = $pagination->hasNextPage();
        $this->previousPage = $pagination->previousPage();
        $this->nextPage = $pagination->nextPage();
        $this->total_pages = $pagination->totalPages();
        return $this->res->fetch_assoc();
    }

    // returns a single post or page set by id
    function getPost($id)
    {
        /*$query  = "SELECT p.post_author, p.post_date, p.post_content, p.post_title, p.post_excerpt, p.comment_status, p.cat1, p.cat2, p.cat3, p.link_title, u.ID, u.display_name ";
        $query .= "FROM c_posts p ";
        $query .= "INNER JOIN c_users u ";
        $query .= "ON p.post_author = u.ID WHERE p.ID = $id";*/
        $query = "SELECT * FROM c_posts WHERE ID = $id AND post_status <> 'Initialized'";
        $this->res = $this->conn->query($query);
        dbQueryCheck($this->res, $this->conn);
        return $this->res->fetch_assoc();
    }

    // very similar to getPostsLoop but returns only the search result
    function getSearchResultLoop($s, $max_pp = null)
    {
        if(!isset($this->p))
        {
            $s = $this->conn->real_escape_string(urldecode($s));
            $loop_first_search_query = "SELECT * FROM c_posts WHERE (post_content LIKE '%$s%' OR post_excerpt LIKE '%$s%' OR post_title LIKE '%$s%' OR post_description LIKE '%$s%' OR link_title LIKE '%$s%' OR tags LIKE '%$s%') AND post_status <> 'Initialized'";
            $this->res = $this->conn->query($loop_first_search_query);
            dbQueryCheck($this->res, $this->conn);
            $this->search_res_count = $this->res->num_rows; // set search result count, should be accessed only after function call
            $this->p = 'set';
        }
        if(!isset($loop_search_query))
        {
            $s = $this->conn->real_escape_string(urldecode($s));
            $pagination = new Pagination($max_pp, $this->search_res_count);
            $loop_search_query = "SELECT * FROM c_posts WHERE (post_content LIKE '%$s%' OR post_excerpt LIKE '%$s%' OR post_title LIKE '%$s%' OR post_description LIKE '%$s%' OR link_title LIKE '%$s%' OR tags LIKE '%$s%') AND post_status <> 'Initialized' ";
            $loop_search_query .= "LIMIT $pagination->per_page ";
            $loop_search_query .= "OFFSET {$pagination->offset()}";
        }
        if(!isset($this->j))
        {
            $this->res = $this->conn->query($loop_search_query);
            dbQueryCheck($this->res, $this->conn);
            $this->j = 'set';
        }
        $this->total_pages = $pagination->totalPages();
        $this->hasPreviousPage = $pagination->hasPreviousPage();
        $this->hasNextPage = $pagination->hasNextPage();
        $this->previousPage = $pagination->previousPage();
        $this->nextPage = $pagination->nextPage();
        return $this->res->fetch_assoc();
    }

    function makePost($author_id, $date, $title, $link_title, $content, $excerpt, $desc, $type, $status, $comment_status, $type)
    {
        $query  = "INSERT INTO c_posts (post_author, post_date, post_title, link_title, post_content, post_excerpt, post_description, post_type, post_status, comment_status, tags) VALUES ";
        $query .= "($author_id, '$date', '$title', '$link_title', '$content', '$excerpt', '$desc', '$type', '$status', $comment_status, '$type')";

        $this->res = $this->conn->query($query);
        return $this->res;
    }

    function updatePost($id, $date, $title, $link_title, $content, $excerpt, $desc, $status, $comment_status)
    {
        $query = "UPDATE c_posts SET post_content = '$content', post_title = '$title', link_title = '$link_title', post_description = '$desc', post_excerpt = '$excerpt', post_status = '$status', comment_status = $comment_status, post_date = '$date' WHERE ID = $id";

        $this->res = $this->conn->query($query);
        return $this->res;
    }

    function getMaxPostId()
    {
        $query = "SELECT MAX(ID) AS max FROM c_posts";
        return $this->conn->query($query)->fetch_array()['max'];
    }

    function getPostPermLink($id, $type, $title)
    {
        return SUBDIR . "{$id}/{$type}/{$title}";
    }

    function getPostEditLink($id)
    {
        return SUBDIR . "c_admin/?switch=new_post&sub=edit_post&id=" . $id;
    }

    static function getPostTitleById($id)
    {
        $conn = MySQL::open_conn();
        $query = "SELECT post_title FROM c_posts WHERE ID = $id";
        return $conn->query($query)->fetch_array()[0];
    }

    function handlePostViewsById($page_id) //URI::getPageId();
    {
        $user_session_for_page = 'user_ip_for_' . $page_id;
        if(!isset($_SESSION[$user_session_for_page]))
        {
            $_SESSION[$user_session_for_page] = $_SERVER['REMOTE_ADDR'];
        }
        else
            return 0;
        $query = "UPDATE c_posts SET post_views = post_views + 1 WHERE ID = $page_id";
        $this->res = $this->conn->query($query);
    }

    // saves user information into database, should be used in non single posts and pages only
    function handleSiteViews()
    {
        if(!isset($_SESSION['user_ip_main']))
        {
            $_SESSION['user_ip_main'] = $_SERVER['REMOTE_ADDR'];
        }
        else
            return 0;
        $user_ip = getVisitorIP();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $query = "INSERT INTO c_user_stats (user_ip, user_agent) VALUES ('$user_ip', '$user_agent')";
        $this->conn->query($query);
    }

    // static version of handleSiteViews
    static function handleSiteViewsStatic()
    {
        $conn = MySQL::open_conn();
        if(!isset($_SESSION['user_ip_main']))
        {
            $_SESSION['user_ip_main'] = $_SERVER['REMOTE_ADDR'];
        }
        else
            return 0;
        $user_ip = getVisitorIP();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $query = "INSERT INTO c_user_stats (user_ip, user_agent) VALUES ('$user_ip', '$user_agent')";
        $conn->query($query);
        $conn->close();
    }
}