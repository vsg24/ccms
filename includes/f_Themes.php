<?php

    function refreshPlease($loc = 'href')
    {
        echo '  <div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Success!</strong> Please <span style="cursor: pointer;" onclick="redirect();"><strong>Refresh</strong></span> the page to see new changes.
                </div>';

        if($loc == 'users_list')
            echo '<script>function redirect() { window.location.replace("index.php?switch=users#users_list"); }</script>';
        elseif($loc == 'post_list')
            echo '<script>function redirect() { window.location.replace("index.php?switch=manage_posts"); }</script>';
        elseif($loc == 'cat_list')
            echo '<script>function redirect() { window.location.replace("index.php?switch=categories#manage_cats"); }</script>';
        else
            echo '<script>function redirect() { window.location = window.location.href; }</script>';
    }

    function getHeader()
    {
        include_once INCLUDE_FIX . 'header.php';
    }

    function getFooter()
    {
        include_once INCLUDE_FIX . 'footer.php';
    }