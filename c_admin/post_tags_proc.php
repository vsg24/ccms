<?php
include '../c_config.php';

$errors         = array();      // array to hold validation errors
$data           = array();      // array to pass back data

$tag = escapeSingleQuotes($_POST['tags']);
$id = escapeSingleQuotes($_POST['id']);

if (empty($tag))
    $errors['tag'] = 'Tag is required.';

/*if (empty($_POST['name']))
$errors['name'] = 'Name is required.';*/

// return a response ===========================================================

// if there are any errors in our errors array, return a success boolean of false
    if ( ! empty($errors))
    {

    // if there are items in our errors array, return those errors
    $data['success'] = false;
    $data['errors']  = $errors;
    } else
    {

// if there are no errors process our form, then return a message

// DO ALL YOUR FORM PROCESSING HERE
// THIS CAN BE WHATEVER YOU WANT TO DO (LOGIN, SAVE, UPDATE, WHATEVER)

    $conn = MySQL::open_conn();
//Fetching Values from URL

//Insert query

    $query = "UPDATE c_posts SET tags = CONCAT(tags, '.$tag') WHERE ID = $id";

    $res = $conn->query($query);
    //dbQueryCheck($res, $conn);
    if(!$res)
    {
        $data['success'] = false;
        $data['message'] = 'Success!';
    }
    $conn->close(); // Connection Closed

// show a message of success and provide a true success variable
    $data['success'] = true;
    $data['message'] = $tag; // for most cases you would want this to be something like 'Success!'
}

// return all our data to an AJAX call
echo json_encode($data);