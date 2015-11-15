<?php

require_once '../c_config.php';
$session = new Sessions();
$i = null; // just a helper for error checking

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
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <script src="js/jquery.min.js"></script>
    <link href="css/login.css" rel='stylesheet' type='text/css' />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <title>ccms Admin Login</title>
</head>

<body>

<div class="main">
    <div style="text-align: center;">
        <img src="images/ccms.png" height="250" class="img-responsive" alt="ccms">
    </div>
    <div class="login">
        <div class="error">
            <?php getError($i, _e('wrong_username_password', '', '', true)); ?>
        </div>
        <div class="inset">
            <!-----start-main---->
            <form action="" method="POST">
                <div>
                    <span><label>Username</label></span>
                    <span><input type="text" class="textbox" placeholder="<?php _e('username'); ?>" name="username" required></span><!--id="active"-->
                </div>
                <div>
                    <span><label>Password</label></span>
                    <span><input type="password" class="password" placeholder="<?php _e('password'); ?>" name="password" required></span>
                </div>
                <div class="sign">
                    <div class="submit">
                        <input id='login' type="submit" name="submit" value="LOGIN">
                    </div>
						<span class="forget-pass">
							<a style="color: #333" href="#">Forgot Password?</a>
						</span>
                    <div class="clear"> </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('#login').click(function() {
        //Now just reference this button and change CSS
        $(this).css('background','#A88211');
    });
</script>
    <!-----//end-main---->
<!-----start-copyright---->
<div class="copy-right">
    <p>Copyright <?php echo date('Y'); ?> by <a href="http://ccms.vahida.ir">ccms</a></p>
    <br>
</div>
<!-----//end-copyright---->

</body>
</html>