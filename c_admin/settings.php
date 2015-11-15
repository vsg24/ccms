<style>
    .c_nav {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }
    .c_nav li {
        display: inline;
        margin-left: 10px;
        margin-right: 10px;
    }

    a:link {
        text-decoration: none;
    }

    a:visited {
        text-decoration: none;
    }

    a:hover {
        text-decoration: none;
    }

    a:active {
        text-decoration: none;
    }
</style>

<div class="container-fluid">
    <div class="row" align="center">
        <ul class="c_nav">
            <li id="first_menu"><a class="btn btn-default" data-toggle="tab" href="#general"><?php _e('general'); ?></a></li>
            <li><a class="btn btn-default" data-toggle="tab" href="#email"><?php _e('email'); ?></a></li>
            <li><a class="btn btn-default" data-toggle="tab" href="#plugins"><?php _e('plugins'); ?></a></li>
        </ul><hr><br>

        <div class="tab-content col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
            <?php
            if(isset($_POST['submit_payline_api']))
            {
                $api_key = $_POST['payline_api_key'];
                $payline_json = ['api'=>$api_key];
                $payline_json = json_encode($payline_json);
                file_put_contents(DOC_ROOT . '/c_contents/plugins/payline/payline.json', $payline_json);
            }
            ?>
            <?php if(isset($_GET['plugin'])) : ?>
                <?php if(file_get_contents(DOC_ROOT . '/c_contents/plugins/payline/payline.json') !== false) : ?>
                    <?php $apikey = json_decode(file_get_contents(DOC_ROOT . '/c_contents/plugins/payline/payline.json'), true); ?>
                <?php else : $apikey = null; ?>
                <?php endif; ?>
            <div id="plugin_settings_payline">
                <form action="" method="POST">
                <label for="payline_api_key">Payline API Key:</label>
                <input id="payline_api_key" type="text" value="<?php echo $apikey['api']; ?>" name="payline_api_key">
                <input type="submit" class="btn btn-info btn_txt_inline" name="submit_payline_api" value="<?php _e('submit'); ?>">
                </form>
            </div>
            <?php else : ?>
            <div id="general" class="tab-pane fade in active col-md-10 col-md-offset-1">
                <?php include_once 'includes/settings_general.php'; ?>
            </div>
            <div id="email" class="tab-pane fade">
                <?php include_once 'includes/settings_email.php'; ?>
            </div>
            <div id="plugins" class="tab-pane fade">
                <?php include_once 'includes/settings_plugins.php'; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>