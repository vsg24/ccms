<?php
$conn = MySQL::open_conn();
?>
    <div>
        <?php formErrorDisplay(); formErrorReset(); ?>
    </div>

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

        .row-centered {
            text-align:center;
        }
    </style>

    <div class="container-fluid">
        <div class="row" align="center">
            <h2 align="center"><?php _e('vip_section'); ?></h2>
            <ul class="c_nav">
                <?php $alert = 'Tabs are disabled while editing a user. to go back, click on Users in the left menu.'; ?>
                <li id="first_menu"><a class="btn btn-default" data-toggle="tab" href="#info"><?php _e('info'); ?></a></li>
                <li><a class="btn btn-default" data-toggle="tab" href="#vip_users_list"><?php _e('manage_vip_users'); ?></a></li>
                <li><a class="btn btn-default" data-toggle="tab" href="#gateways"><?php _e('gateways'); ?></a></li>
            </ul>
        </div><hr>

        <div class="tab-content col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
            <div>
                <?php
                if(isset($_GET['sub']) && $_GET['sub'] == 'edit_user')
                {
                    if(isset($_GET['id']))
                    {
                        $data = Users::getUserById($_GET['id']);
                    }
                    require_once 'new_user.php';
                }
                ?>
                <br>
            </div>
            <div id="info" class="tab-pane fade col-md-10 col-md-offset-1" <?php if(isset($_GET['sub'])) echo 'style="display: none;"' ?>>
                <div>
                    <span style="float: <?php echo getLBA() ?>;"><?php _e('current_admin', '', ': '); ?></span>
                    <span style="float: <?php echo getLBA_rev(); ?>;"><?php echo Users::getAdminsCount(); ?></span>
                </div>
                <br>
                <br>
                <div>
                    <span style="float: <?php echo getLBA() ?>;"><?php _e('vip_users_count', '', ': '); ?></span>
                    <span style="float: <?php echo getLBA_rev(); ?>;"><?php echo Users::getVIPUsersCount(); ?></span>
                </div>
            </div>
            <div id="new_user" class="tab-pane fade" <?php if(isset($_GET['sub'])) echo 'style="display: none;"' ?>>
                <?php include_once 'new_user.php'; ?>
            </div>
            <div id="vip_users_list" class="tab-pane fade" <?php if(isset($_GET['sub'])) echo 'style="display: none;"' ?>>
                <div>
                    <?php include_once 'includes/vip_users_list.php'; ?>
                </div>
            </div>
            <div id="gateways" class="tab-pane fade in active" <?php if(isset($_GET['sub'])) echo 'style="display: none;"' ?>>
                <?php include_once 'includes/vip_gateways.php' ?>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            if (location.hash !== '') $('a[href="' + location.hash + '"]').tab('show');
            return $('a[data-toggle="tab"]').on('shown', function(e) {
                return location.hash = $(e.target).attr('href').substr(1);
            });
        });
    </script>