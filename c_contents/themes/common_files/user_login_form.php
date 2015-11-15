<?php
$i = null;
if(isset($_POST["submit"]))
{
    $username = $_POST["username"];
    $password = $_POST["password"];

    if(verifyUser($username, $password))
    {
        $id = Users::getIdByUsername($username);
        Cookies::setLoginCookies($id, 30); // remember for 30 dayz!
        $session->setSession($id, $username);
        $i = false;
    }
    else
    {
        $i = true;
    }
}
?>
<div class="error">
    <?php getError($i, _e('wrong_username_password', '', '', true)); ?>
</div>
<br>
<form action="" method="POST">
    <label for="username">Username:</label>
    <input id="username" type="text" name="username" required>
    <label for="password">Password:</label>
    <input id="password" type="password" name="password" required>
    <input type="submit" name="submit" value="Login">
</form>