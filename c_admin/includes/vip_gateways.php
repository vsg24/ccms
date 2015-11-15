<head>
    <script src="./js/image-picker.min.js"></script>
    <link href="./css/image-picker.css" rel="stylesheet">
</head>

    <?php if(getLBA() == 'left') : ?>
    <div class="col-xs-6 col-sm-6 col-md-6" align="<?php echo getLBA(); ?>">
        <span><?php _e('available_gateways', '', ':'); ?></span>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6" align="<?php echo getLBA_rev(); ?>">
        <div style="float: <?php echo getLBA_rev(); ?>">
            <select id="gateways" class="image-picker">
                <option value=""></option>
                <option data-img-src="./images/payline.png" value="payline">Payline - پی لاین</option>
            </select>
        </div>
    </div>
    <script>var lang = 'en';</script>
    <?php else : ?>
    <div class="col-xs-6 col-sm-6 col-md-6" align="<?php echo getLBA_rev(); ?>">
        <div style="float: <?php echo getLBA_rev(); ?>">
            <select id="gateways" class="image-picker">
                <option value=""></option>
                <option data-img-src="./images/payline.png" value="payline">Payline - پی لاین</option>
            </select>
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6" align="<?php echo getLBA(); ?>">
        <span><?php _e('available_gateways', '', ':'); ?></span>
    </div>
    <script>var lang = 'fa';</script>
    <?php endif ?>

    <br><br><br><br>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12" align="<?php echo getLBA(); ?>">
            <div style="float: <?php echo getLBA(); ?>">
            <form id="gateway_form" action="" method="POST">
                <p><?php _e('gateway_test_text', '', ':'); ?></p>
                <label for="test_money"><?php _e('amount'); ?>:</label>
                <input id="test_money" type="number" name="test_money_amount" required>
                <input style="text-align: left; display: inline;" type="submit" class="btn btn-info btn_txt_inline" value="<?php _e('pay'); ?>" name="test_money_submit">
            </form>
            </div>
        </div>
    </div>
    <div>
        <br>
        <label style="float: <?php echo getLBA(); ?>"><?php _e('result', '', ':'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
        <span id="res" style="color: purple"><strong><?php _e('nothing_done'); ?></strong></span><br><hr>
        <span id="res_reason"></span><br>
    </div>

    <script>
        $("select").imagepicker({
            hide_select : true,
            show_label  : true
        });

        $('#gateway_form').submit(function ()
        {
            if($('#gateways :selected').text() == 'Payline - پی لاین')
            {
                return true;
            } else
            {
                if(lang == 'en')
                {
                    $("#res_reason").text("Please select a gateway by clicking on its picture");
                }
                else if(lang == 'fa')
                {
                    $("#res_reason").text("لطفا یک درگاه را با کلیک بر روی تصویر آن انتخاب نمایید");
                }
                return false;
            }
        });
    </script>
<?php
getMoneyAPI('payline');

$url = 'http://payline.ir/payment/gateway-send';
$api = getPaylineAPI();
$redirect = urlencode(SITE_DOMAIN . '/c_admin/index.php?switch=vip'); // payline will send error no.4 if SITE_DOMAIN is localhost

if(isset($_POST['test_money_submit']))
{
    $amount = $_POST['test_money_amount'];
    $result = send($url,$api,$amount,$redirect);
    $i = doInitialCheck($result);

    if($i === true && is_numeric($result))
    {
        if(Language === 'en')
        {
            echo '<script>$("#res").text("Redirecting to Gateway"); $("#res").css("color", "blue").css("font-weight", "bold");</script>';
        }
        else
        {
            echo '<script>$("#res").text("در حال انتقال به درگاه"); $("#res").css("color", "blue").css("font-weight", "bold");</script>';
        }
        $go = "http://payline.ir/payment/gateway-$result";
        header("Refresh: 4; URL=$go");
    }
    else
    {
        if(Language === 'en')
        {
            echo '<script>
        $("#res").text("Failed!");
        $("#res_reason").text("' . $i . '");
        $("#res").css("color", "red");
        </script>';
            exit;
        }
        elseif(Language === 'fa')
        {
            echo '<script>
        $("#res").text("!ناموفق");
        $("#res_reason").text("' . $i . '");
        $("#res").css("color", "red");
        </script>';
            exit;
        }
    }
}

if(isset($_POST['trans_id']) && isset($_POST['id_get']))
{
    $url = 'http://payline.ir/payment/gateway-result-second';
    ##
    $conn = MySQL::open_conn();
    $amount = $_POST['test_money_submit'];
    $current_user = Users::getIDBySeassion();
    $type = 'vip_credit';
    $trans_id = $_POST['trans_id'];
    ##
    if($res = doTransaction($url, $api, $_POST['trans_id'], $_POST['id_get']))
    {
        if($res === 1)
        {
            if(Language === 'en')
            {
                echo '<script>$("#res").text("Successful"); $("#res").css("color", "green");</script>';
            }
            elseif(Language === 'fa')
            {
                echo '<script>$("#res").text("موفق"); $("#res").css("color", "green");</script>';
            }
            $trans_status = 1;
            $query = "INSERT INTO c_payments (ID, type, payment_by, payment_for, amount, trans_status) VALUES ($trans_id, '$type', '$current_user', '$current_user', '$amount', $trans_status)";
            $res = $conn->query($query);
            dbQueryCheck($res, $conn);
        }
        else
        {
            if(Language === 'en')
            {
                echo '<script>
        $("#res").text("Failed!");
        $("#res_reason").text("Error code:' . $res . '");
        $("#res").css("color", "red");
        </script>';
            }
            elseif(Language === 'fa')
            {
                echo '<script>
        $("#res").text("!ناموفق");
        $("#res_reason").text("Error code:' . $res . '");
        $("#res").css("color", "red");
        </script>';
            }
            $trans_status = 0;
            $query = "INSERT INTO c_payments (ID, type, payment_by, payment_for, amount, trans_status) VALUES ($trans_id, '$type', '$current_user', '$current_user', '$amount', $trans_status)";
            $res = $conn->query($query);
            dbQueryCheck($res, $conn);
        }
    }
}
?>