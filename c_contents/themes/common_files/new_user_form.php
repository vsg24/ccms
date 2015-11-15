<?php
if(isset($_POST['submit']))
{
    $recaptcha_secret_key = "";
    $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$recaptcha_secret."&response=".$_POST['g-recaptcha-response']), true);
    if($response["success"] === true)
    {
        $res = Users::submitNewUser($_POST['username'], $_POST['password'], $_POST['email'], $_POST['password2']);
        if ($res == 0)
        {
            // successful
        }
        else
        {
            // failed
        }
    }
    else
    {
        // failed (because of recaptcha)
    }
}
?>
<script src='https://www.google.com/recaptcha/api.js?hl=en'></script> <!-- en for English, fa for Persian and others... -->
<form action="" method="POST">
    <label for="username">Username:</label>
    <input id="username" type="text" name="username" required>
    <label for="password">Password:</label>
    <input id="password" type="password" name="password" required>
    <label for="password2">Password again:</label>
    <input id="password2" type="password" name="password2" required>
    <label for="email">Email:</label>
    <input id="email" type="email" name="email" required>
    <div class="g-recaptcha" data-sitekey="your-site-key-here"></div>
    <input type="submit" name="submit" value="Register">
</form>