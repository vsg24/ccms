<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">
    <h3><i class="mdi mdi-forum"></i>&nbsp;Comments:</h3><br>
    <div id="comments">
        <?php $comment_status = $row['comment_status']; ?>
        <?php while($row = $comments->getCommentsLoop()) : ?>
            <p><em><?php echo Users::getUserById($row['user_id'])['username']; ?></em> on <span class="help-block" style="display: inline;"><?php echo englishConvertDate($row['date']); ?></span> said:</p>
            <div><?php echo $row['content']; ?></div><br>
        <?php endwhile; ?>
    </div>
    <script src="<?php echo THEME_BASE; ?>js/spin.min.js"></script>
    <script src="<?php echo THEME_BASE; ?>comment_submit.js"></script>
    <div id="comment_form">
        <?php $a = commentFormCheck($comment_status); if($a == 2 && getCurrentUser() == '') : ?>
            Log in to comment.
        <?php elseif($a == 1 || ($a == 2 && getCurrentUser() != '')) : ?>
            <form id="comment_form" action="comment_submit.php" method="POST">
                <input name="post_id" type="hidden" value="<?php echo URI::getPageId(); ?>">
                <input name="user_id" type="hidden" value="<?php echo getCurrentUser('id'); ?>">
                <?php if(getCurrentUser() != '') : ?>
                    <p>Comment as <strong><?php echo getCurrentUser('username'); ?></strong>:</p>
                <?php endif; ?>
                <?php if(getCurrentUser() == '') : ?>
                <label>Name:</label><input name="new_user_name" type="text" placeholder="Required" required><br>
                <label>Email:</label><input name="new_user_email" type="text" placeholder="Required" required><br>
                    <p class="help-block">Enter your name and email, we will register an account immediately for you. Use your name and email to submit comments in the future with this account.</p>
                <?php endif; ?>
                <textarea id="comment_text" style="border-width: 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; width: 100%;" rows="6" class="div_white_left" required></textarea>
                <br>
                <input id="comment_submit" name="new_comment_submit" class="btn btn-info" value="Submit" type="submit"><div style="margin-top: -15px;" id="spinner"></div>
            </form>
        <?php else : ?>
            <p>Comments are closed for this post.</p>
        <?php endif; ?>
    </div>
</div>