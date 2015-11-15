<?php

// url to send information to
//$url = 'http://payline.ir/payment/gateway-send';
// unique api key, get this from payline.ir
//$api = 'your-key-here';
// amount of money to transfer
//$amount = 1000;
// url to redirect to after transaction, must be a page where you execute get function to verify success in transaction
//$redirect = urlencode('your_website');

function send($url, $api, $amount, $redirect) // used to send data to payline server
{
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POSTFIELDS,"api=$api&amount=$amount&redirect=$redirect");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

function get($url, $api, $trans_id, $id_get) // gets returned data from payline
{
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POSTFIELDS,"api=$api&id_get=$id_get&trans_id=$trans_id");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

function doInitialCheck($result) // print back errors if any exist otherwise return false
{
    switch($result)
    {
        case '-1':
            return 'Sent API is incorrect Or it\'s not compatible with Payline.';
            break;
        case '-2':
            return 'Money amount is incorrect or lower than minimum. (Minimum valid is 1000)';
            break;
        case '-3':
            return 'Redirect variable is null. (Doesn\'t exist)';
            break;
        case '-4':
            return 'Gateway not found';
            break;
    }

    return true;
}

function doTransaction($url, $api, $trans_id_post, $id_get_post)
{
    $trans_id = $trans_id_post;
    $id_get = $id_get_post;
    $result = get($url,$api,$trans_id,$id_get);
    return $result;
}

function getPaylineAPI()
{
    return json_decode(file_get_contents(DOC_ROOT . '/c_contents/plugins/payline/payline.json'), true)['api'];
}