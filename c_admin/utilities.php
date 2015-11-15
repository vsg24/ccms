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
<?php
$query = "SELECT option_value FROM c_options WHERE option_name = 'xml_maps_auto_update' LIMIT 1";
$conn = MySQL::open_conn();
$xml_auto_update = $conn->query($query)->fetch_row()[0];


if(isset($_POST['submit_sitemaps']))
{
    $value = isset($_POST['xml_maps_auto_update']) ? 'yes' : 'no';
    $query = "UPDATE c_options SET option_value = '$value' WHERE option_name = 'xml_maps_auto_update'";
    $res = $conn->query($query);
    ob_end_clean();
    redirectTo('index.php?switch=utilities#xml_maps');
}

if(isset($_GET['sub']) && $_GET['sub'] == 'generate_sitemaps')
{
    generatePostsSitemap();
    generatePagesSitemap();
}
?>
<div class="container-fluid">
    <div class="row" align="center">
        <?php
        if(isset($_GET['tab']))
        {
            include_once 'includes/utilities_' . $_GET['tab'] . '.php';
        }
        else
        {
        ?>
        <ul class="c_nav">
            <li id="first_menu"><a class="btn btn-default" data-toggle="tab" href="#general"><?php _e('general'); ?></a></li>
            <li><a class="btn btn-default" data-toggle="tab" href="#cache"><?php _e('cache'); ?></a></li>
            <li><a class="btn btn-default" href="index.php?switch=utilities&tab=email_templates"><?php _e('email_templates'); ?></a></li>
            <li><a class="btn btn-default" data-toggle="tab" href="#xml_maps"><?php _e('xml_maps'); ?></a></li>
        </ul><hr><br>

        <div class="tab-content col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
            <div id="general" class="tab-pane fade in active col-md-10 col-md-offset-1">
                <span><?php _e('use_buttons_above'); ?></span>
            </div>
            <div id="cache" class="tab-pane fade col-md-10 col-md-offset-1">
                <?php include_once 'includes/utilities_cache.php'; ?>
            </div>
            <div id="xml_maps" class="tab-pane fade col-md-10 col-md-offset-1">
                <form action="" method="POST">
                    <label for="xml_auto_update" style="float: <?php echo getLBA(); ?>;"><?php _e('auto_update', '', ':'); ?></label>&nbsp<input type="checkbox" name="xml_maps_auto_update" <?php if($xml_auto_update === 'yes') echo 'checked'; ?> style="float: <?php echo getLBA_rev(); ?>;" id="xml_auto_update"><br>
                    <p align="<?php echo getLBA(); ?>" class="help-block"><?php _e('sitemaps_help', '', '.'); ?></p>
                    <input style="float: <?php echo getLBA_rev(); ?>;" class="btn btn-info" type="submit" value="<?php _e('submit'); ?>" name="submit_sitemaps">
                </form>
                <br><hr>
                <a href="?switch=utilities&sub=generate_sitemaps#xml_maps"><?php _e('generate_site_maps'); ?></a>
            </div>
        </div>
        <?php } ?>
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