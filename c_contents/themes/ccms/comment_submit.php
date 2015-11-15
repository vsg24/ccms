<?php

$errors = [];      // array to hold validation errors
$data = [];      // array to pass back data
$text = $_POST['text'];
$id = $_POST['id'];

if($_POST['mode'] == '1')
{
    $type = 1; // user is not logged in so we either user their email to get existing simple account or we create one for them
    $name = $_POST['name'];
    $email = $_POST['email'];
    $user = Users::submitSimpleUserForComments($name, $email);
    if(!$user)
    {
        $errors = ['Can not register you. Because a user with the same email already exist. If it is you, please enter the correct name.'];
    }
}
else
{
    $type = 2; // user is indeed logged in, just get their ID from HTML hidden div or any other way
    $user = $_POST['user']; // user_id
}

if (!empty($errors))
{
    // if there are items in our errors array, return those errors
    $data['success'] = false;
    $data['errors']  = $errors;
}
else
{
    if(Comments::makeComment($id, $user, $text))
    {
        //echo 'succeed';
        $data['success'] = true;
        // exclusive data for comments to return
        $data['author_id'] = $user;
        $data['author'] = Users::getUserById($user)['username'];
        $data['text'] = $text;
        $data['date'] = englishConvertDate(jDateTime::gdate('Y-m-d H:i:s'));
    }
    else
    {
        $errors = ['Your comment is probably too short.'];
        $data['success'] = false;
        $data['errors']  = $errors;
    }
}

// return all our data to an AJAX call
echo json_encode($data);