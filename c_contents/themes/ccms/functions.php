<?php

function makeSiteTitle()
{
    if(URI::checkPage() == 'Home')
    {
        return strip_tags(getSiteTitle());
    }
    // title for 404 page
    elseif(URI::checkPage() == '404')
    {
        return strip_tags('404 - Not Found' . ' - ' . getSiteTitle());
    }
    // title for pages and posts
    elseif(URI::checkPage() == 'Single' || URI::checkPage() == 'Page')
    {
        return strip_tags(Posts::getPostTitleById(URI::getParams_single()['id'])) . ' - ' . getSiteTitle();
    }
    elseif(URI::checkPage() == 'news.php')
    {
        return strip_tags('News Archive' . ' - ' . getSiteTitle());
    }
    elseif(URI::checkPage() == 'search.php')
    {
        return strip_tags('Search' . ' - ' . getSiteTitle());
    }
    elseif(URI::checkPage() == 'docs.php')
    {
        return strip_tags('Documentation' . ' - ' . getSiteTitle());
    }
    // title for every other case
    else
    {
        return getSiteTitle();
    }
}

function getHeaderCUSTOM()
{
    include_once INCLUDE_FIX . 'header2.php';
}