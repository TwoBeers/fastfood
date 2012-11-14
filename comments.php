<!-- begin comments -->
<?php
	if ( post_password_required() ) {
		echo '<div class="comment_tools" id="comments">' . __( 'Enter your password to view comments.','fastfood' ) . '</div>';
		return;
	}
		global $fastfood_is_printpreview;
?>

	<?php if ( comments_open() ) { ?>
		<div class="comment_tools" id="comments">
			<?php comments_number( __( 'No Comments','fastfood' ), __( '1 Comment','fastfood' ), __( '% Comments','fastfood' ) ); ?> - <a class="show_comment_form" href="#respond" title="<?php _e( "Leave a comment",'fastfood' ); ?>" ><?php _e( "Leave a comment",'fastfood' ); ?></a>
		</div>
		<?php
	} elseif ( have_comments() ) { ?>
		<div class="comment_tools" id="comments">
			<?php comments_number( __( 'No Comments','fastfood' ), __( '1 Comment','fastfood' ), __( '% Comments','fastfood' ) ); ?>
		</div>
		<?php
	} ?>

	<?php if ( have_comments() ) { ?>

		<?php fastfood_hook_before_comments(); ?>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>

			<div class="navigate_comments">
				<?php if(function_exists('wp_paginate_comments')) {
					wp_paginate_comments();
				} else {
					paginate_comments_links( array('prev_text' => '&laquo;', 'next_text' => '&raquo;') );
				} ?>
				<div class="fixfloat"> </div>
			</div>

		<?php } ?>

		<ol id="commentlist">
			<?php //wp_list_comments(array('avatar_size' => 96)); ?>
			<?php wp_list_comments(); ?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>

			<div class="navigate_comments">
				<?php if(function_exists('wp_paginate_comments')) {
					wp_paginate_comments();
				} else {
					paginate_comments_links( array('prev_text' => '&laquo;', 'next_text' => '&raquo;') );
				} ?>
				<div class="fixfloat"> </div>
			</div>

		<?php } ?>

		<?php fastfood_hook_after_comments(); ?>
	<?php }

	if ( comments_open() && !$fastfood_is_printpreview ) { // if comments are open and not in print preview
		//define custom argoments for comment form
		$fastfood_custom_args = array(
			'comment_field'        => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="7" style="width: 95%;max-width: 95%;" aria-required="true"></textarea></p>',
			'comment_notes_after'  => '<p class="form-allowed-tags"><small>' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s','fastfood' ), allowed_tags() ) . '</small></p><input type="hidden" value="' . __('Reply to Comment','fastfood' ) . '" id="replytocomment" name="replytocomment" /><input type="hidden" value="' . __( 'Leave a Reply','fastfood' ) . '" id="replytopost" name="replytopost" />',
			'label_submit'         => __( 'Say It!','fastfood' ),
			'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>.','fastfood' ), admin_url( 'profile.php' ), $user_identity ) . '</p>',
			'cancel_reply_link'    => '<br>' . __( 'Cancel reply','fastfood' ),
			'title_reply'          => __( 'Leave a comment','fastfood' ),
		);

		//output comment form
		comment_form($fastfood_custom_args); 
	}
	?> <div class="fixfloat"></div> <?php
?>
<!-- end comments -->
