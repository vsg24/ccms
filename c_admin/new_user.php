<div id="new_user_form">
    <form action="" method="POST" class=form-group">
        <label for="username"><?php _e('username', '', ':'); ?></label><input type="text" name="new_user_username" value="<?php if(isset($data['username'])) echo $data['username']; ?>" id="username" class="form_block" <?php if(isset($_GET['id'])) echo 'disabled'; else echo 'required'; ?>>
        <label for="password"><?php _e('password', '', ':'); ?></label><input type="password" name="new_user_password" id="password" placeholder="<?php if(isset($_GET['id'])) echo htmlspecialchars('Type in a new password to change the existing'); ?>" class="form_block" <?php if(!isset($_GET['id'])) echo 'required'; ?>>
        <label for="email"><?php _e('email', '', ':'); ?></label><input type="email" name="new_user_email" value="<?php if(isset($data['user_email'])) echo $data['user_email']; ?>" id="email" class="form_block" required>
        <hr>
        <label for="role" style="float: <?php echo getLBA(); ?>;"><?php _e('role', '', ':'); ?></label>&nbsp;
        <select name="new_user_role" id="role">
            <option value="Subscriber" <?php if(isset($data['user_role']) && $data['user_role'] == 2) echo 'selected'; ?>><?php _e('subscriber'); ?></option>
            <option value="Super User" <?php if(isset($data['user_role']) && $data['user_role'] == 3) echo 'selected'; ?>><?php _e('super_user'); ?></option>
            <option value="Admin" <?php if(isset($data['user_role']) && $data['user_role'] == 4) echo 'selected'; ?>>Admin</option>
        </select>
        <br><br>
        <label style="float: <?php echo getLBA(); ?>;" for="vip"><?php _e('vip', '', ':'); ?></label>&nbsp;
        <select id="vip" name="new_user_vip">
            <option value="0" <?php if(isset($data['vip_status']) && $data['vip_status'] == 0) echo 'selected'; ?>><?php _e('not_vip'); ?></option>
            <option value="1" <?php if(isset($data['vip_status']) && $data['vip_status'] == 1) echo 'selected'; ?>>1 Day</option>
            <option value="7" <?php if(isset($data['vip_status']) && $data['vip_status'] == 7) echo 'selected'; ?>>7 Days</option>
            <option value="15" <?php if(isset($data['vip_status']) && $data['vip_status'] == 15) echo 'selected'; ?>>15 Days</option>
            <option value="30" <?php if(isset($data['vip_status']) && $data['vip_status'] == 30) echo 'selected'; ?>>30 Days</option>
            <option value="45" <?php if(isset($data['vip_status']) && $data['vip_status'] == 45) echo 'selected'; ?>>45 Days</option>
            <option value="60" <?php if(isset($data['vip_status']) && $data['vip_status'] == 60) echo 'selected'; ?>>60 Days</option>
            <option value="-1" <?php if(isset($data['vip_status']) && $data['vip_status'] == -1) echo 'selected'; ?>><?php _e('indefinitely'); ?></option>
        </select>
        <?php if(isset($_GET['sub']) && $_GET['sub'] == 'edit_user') : ?>
            <div class="btn-group" style="float: <?php echo getLBA_rev(); ?>;">
            <input type="submit" name="submit_update_user" class="btn btn-info" value="<?php _e('update'); ?>">
            <input type="submit" name="submit_delete_user" class="btn btn-warning" onclick="return confirm('Are you sure?');" value="<?php _e('delete_user'); ?>">
            </div><br>
        <?php else : ?>
            <input type="submit" name="submit_new_user" class="btn btn-info" value="<?php _e('submit'); ?>" style="float: <?php echo getLBA_rev(); ?>;"><br>
        <?php endif; ?>
    </form>
</div>