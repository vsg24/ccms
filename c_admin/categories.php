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
    </style>
    <div class="container-fluid">
        <div class="row" align="center">
                <ul class="c_nav">
                    <?php $alert = 'Tabs are disabled while editing a category. to go back, click on Categories in the left menu.'; ?>
                    <li id="first_menu"><a class="btn btn-default" data-toggle="tab" href="#info"><?php _e('info'); ?></a></li>
                    <li><a class="btn btn-default" data-toggle="tab" href="#new_cat"><?php _e('new_category'); ?></a></li>
                    <li><a class="btn btn-default" data-toggle="tab" href="#manage_cats"><?php _e('manage_categories'); ?></a></li>
                    <li><a class="btn btn-default" data-toggle="tab" href="#others"><?php _e('others'); ?></a></li>
                </ul>
        </div><hr>

        <div class="tab-content col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
            <div <?php if(!isset($_GET['sub'])) echo 'style="display: none;"' ?>>
                <?php
                if(isset($_POST['submit_new_category']))
                {
                    $name = $_POST['new_category_name'];
                    $link_name = urlencode($_POST['new_category_link_name']);
                    $name = $conn->real_escape_string($name);
                    $query = "INSERT INTO c_categories (name, link_name) VALUES ('$name', '$link_name')";
                    $res = $conn->query($query);
                    if(!$res) goToError('?switch=categories', _e('cant_make_new_category', '', '', true));
                    ob_end_clean();
                    redirectTo('index.php?switch=categories#manage_cats');
                }
                if(isset($_POST['submit_update_category']))
                {
                    $name = $_POST['update_category_name'];
                    $link_name = urlencode($_POST['update_category_link_name']);
                    $name = $conn->real_escape_string($name);
                    $id = $_POST['update_category_id'];
                    $query = "UPDATE c_categories SET name = '$name', link_name = '$link_name' WHERE ID = $id";
                    $res = $conn->query($query);
                    if(!$res) goToError('?switch=categories', _e('cant_make_new_category', '', '', true));
                    ob_end_clean();
                    redirectTo('index.php?switch=categories#manage_cats');
                }
                if(isset($_POST['submit_delete_category']))
                {
                    $id = $_POST['update_category_id'];
                    $uncategorized_id = getDefaultCategory();

                    if($id == $uncategorized_id)
                    {
                        $helper = 1;
                        goto end; // user should not be able to delete uncategorized category as it's the default category
                    }

                    $query = "DELETE FROM c_categories WHERE ID = $id";
                    $res = $conn->query($query);

                    $query = "SELECT * FROM c_posts_cats WHERE cat_id IS NULL";
                    $res = $conn->query($query);
                    while($row = $res->fetch_assoc())
                    {
                        $post_id = $row['post_id'];
                        $id2 = $row['ID'];
                        $query2 = "SELECT * FROM c_posts_cats WHERE cat_id IS NULL AND post_id = $post_id";
                        $q_res = $conn->query($query2)->num_rows;
                        if($q_res == 1)
                        {
                            $query = "UPDATE c_posts_cats SET cat_id = $uncategorized_id WHERE ID = $id2";
                            $res2 = $conn->query($query);
                        }
                    }

                    end:
                    if(isset($helper) && $helper == 1) goToError('?switch=categories#manage_cats', _e('cant_delete_default_category', '', '', true));
                    //ob_end_clean();
                    redirectTo('index.php?switch=categories#manage_cats');
                }
                ?>
                <table width="auto" class="table table-bordered table-hover">
                    <tr>
                        <th><?php _e('', 'ID'); ?></th>
                        <th><?php _e('name'); ?></th>
                        <th><?php _e('link_name'); ?></th>
                    </tr>
                    <?php
                    if(isset($_GET['sub']) && $_GET['sub'] == 'edit_category')
                    {
                        $id = $_GET['id'];
                        $query = "SELECT * FROM c_categories WHERE ID = $id LIMIT 1";
                        $ress = $conn->query($query);
                        $roww = $ress->fetch_assoc();
                        $name = '<form action="" method="POST"><input type="text" name="update_category_name" value="' . $roww['name'] . '"required>';
                        echo "<tr><td>" . $roww['ID'] ."</td><td>" . $name . "<input type='hidden' name='update_category_id' value='" . $roww['ID'] . "'></td><td><input type=\"text\" name=\"update_category_link_name\" value=\"" . urldecode($roww['link_name']) . "\" required></td></tr>";
                    }
                    ?>
                </table>
                <div align='center'><input type="submit" onclick="return confirm('Are you sure?');" class="btn btn-warning" name="submit_delete_category" value="<?php _e('delete'); ?>">&nbsp;&nbsp;<input class='btn btn-info' type='submit' name='submit_update_category' value='<?php _e('update'); ?>'></div></form>
            </div>
            <div id="info" class="tab-pane fade in active col-md-10 col-md-offset-1" <?php if(isset($_GET['sub'])) echo 'style="display: none;"' ?>>
                <div>
                    <span style="float: <?php echo getLBA() ?>;"><?php _e('categories_count', '', ': '); ?></span>
                    <span style="float: <?php echo getLBA_rev(); ?>;"><?php echo getCategoryCount(); ?></span>
                </div>
            </div>
            <div id="new_cat" class="tab-pane fade" <?php if(isset($_GET['sub'])) echo 'style="display: none;"' ?>>
                <form action="" method="POST" class=form-group">
                    <label for="name"><?php _e('name', '', ':'); ?></label><input dir="auto" id="name" class="form_block" type="text" name="new_category_name" required><br>
                    <label for="link_name"><?php _e('link_name', '', ':'); ?></label><input id="link_name" class="form_block" type="text" name="new_category_link_name" required><br><br>
                    <div align="center"><input type="submit" class="btn btn-info" name="submit_new_category" value="<?php _e('submit'); ?>"></div>
                </form>
            </div>
            <script>

                String.prototype.replaceAll = function(search, replace)
                {
                    //if replace is not sent, return original string otherwise it will
                    //replace search string with 'undefined'.
                    if (replace === undefined) {
                        return this.toString();
                    }

                    return this.replace(new RegExp('[' + search + ']', 'g'), replace);
                };

                $('#name').keyup(function() {
                    var str = $(this).val().toLocaleLowerCase().replaceAll(' ', '-');
                    $('#link_name').val(str);
                });

            </script>
            <div id="manage_cats" class="tab-pane fade" <?php if(isset($_GET['sub'])) echo 'style="display: none;"' ?>>
                <div>
                    <?php include_once 'includes/categories_list.php'; ?>
                </div>
            </div>
            <div id="others" class="tab-pane fade" <?php if(isset($_GET['sub'])) echo 'style="display: none;"' ?>>
                <div align="center"><?php _e('not_yet_ready'); ?></div>
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