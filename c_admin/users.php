<?php
$conn = MySQL::open_conn();

require_once "includes/submit_new_user.php";
require_once "includes/submit_update_user.php";
require_once "includes/submit_delete_user.php";
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
        <h2 align="center"><?php _e('users'); ?></h2>
        <!--<div align="center">-->
        <ul class="c_nav">
            <?php $alert = 'Tabs are disabled while editing a user. to go back, click on Users in the left menu.'; ?>
            <li id="first_menu"><a class="btn btn-default" data-toggle="tab" href="#info"><?php _e('info'); ?></a></li>
            <li><a class="btn btn-default" data-toggle="tab" href="#new_user"><?php _e('new_user'); ?></a></li>
            <li><a class="btn btn-default" data-toggle="tab" href="#users_list"><?php _e('manage_users'); ?></a></li>
        </ul>
        <!--</div>-->
        </div><hr>

        <div class="tab-content col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3" style="/*margin-right: 30%; margin-left: 30%;*/">
            <div>
                <!--<h3 align="center">Edit User:</h3>-->
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
            <div id="info" class="tab-pane fade in active col-md-10 col-md-offset-1" <?php if(isset($_GET['sub'])) echo 'style="display: none;"' ?>>
                <div>
                    <span style="float: <?php echo getLBA() ?>;"><?php _e('current_admin', '', ': '); ?></span>
                    <span style="float: <?php echo getLBA_rev(); ?>;"><?php echo Users::getAdminsCount(); ?></span>
                </div>
                <br>
                <br>
                <div>
                    <span style="float: <?php echo getLBA() ?>;"><?php _e('registered_users_count', '', ': '); ?></span>
                    <span style="float: <?php echo getLBA_rev(); ?>;"><?php echo Users::getUsersCount(); ?></span>
                </div>
            </div>
            <div id="new_user" class="tab-pane fade" <?php if(isset($_GET['sub'])) echo 'style="display: none;"' ?>>
                <?php require_once 'new_user.php'; ?>
            </div>
            <div id="users_list" class="tab-pane fade" <?php if(isset($_GET['sub'])) echo 'style="display: none;"' ?>>
                <div>
                 <?php include_once 'users_list.php'; ?>
                </div>
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

<?php //$conn->close(); ?>