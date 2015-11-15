<?php

    function getMoneyAPI($api_name)
    {
        if($api_name == 'payline.ir' || $api_name == 'payline' || $api_name == 'payline_ir' || $api_name == 'payline_ir.php')
        {
            require_once '../c_contents/plugins/payline/payline_ir.php';
        }
    }