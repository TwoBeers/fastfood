<!-- begin comments -->
<?php
	if ( isset( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
		die ( 'Please do not load this page directly. Thanks!' );
	}

	if ( post_password_required() ) {
		echo '<div class="comment_tools" id="comments" style="text-align: right;">' . __( 'Enter your password to view comments.','fastfood' ) . '</div>';
		return;
	}
		global $fastfood_opt, $ff_is_printpreview, $ff_is_mobile_browser;
?>

	<?php if ( comments_open() ) { ?>
		<div class="comment_tools" id="comments" style="text-align: right;">
			<?php comments_number( __( 'No Comments','fastfood' ), __( '1 Comment','fastfood' ), __( '% Comments','fastfood' ) ); ?> - <a href="#respond" title="<?php _e( "Leave a comment",'fastfood' ); ?>" <?php if ( !$ff_is_printpreview && ( $fastfood_opt['fastfood_cust_comrep'] == 1 ) && !$ff_is_mobile_browser ) echo 'onclick="return addComment.viewForm()"'; ?> ><?php _e( "Leave a comment",'fastfood' ); ?></a>
		</div>
		<?php
	} elseif ( have_comments() ) { ?>
		<div class="comment_tools" id="comments" style="text-align: right;">
			<?php comments_number( __( 'No Comments','fastfood' ), __( '1 Comment','fastfood' ), __( '% Comments','fastfood' ) ); ?>
		</div>
		<?php
	} ?>

	<?php if ( have_comments() ) { ?>
		<ol id="commentlist">
			<?php //wp_list_comments(array('avatar_size' => 96)); ?>
			<?php wp_list_comments(); ?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>

			<div class="navigate_comments">
				<?php paginate_comments_links(); ?>
				<div class="fixfloat"> </div>
			</div>

		<?php
		}
	}
	//if comments are open
	if ( comments_open() ) { 
		if ( !$ff_is_printpreview ) { //script not to be loaded in print preview
			//define custom argoments for comment form
			$ff_custom_args = array(
				'comment_field'        => '<p class="comment-form-comment" style="text-align: center;"><textarea id="comment" name="comment" cols="45" rows="7" style="width: 95%;" aria-required="true"></textarea></p>',
				'comment_notes_after'  => '<p class="form-allowed-tags"><small style="float: right; min-width: 200px; color: #999999;">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s','fastfood' ), allowed_tags() ) . '</small></p><input type="hidden" value="' . __('Reply to Comment','fastfood' ) . '" id="replytocomment" name="replytocomment" /><input type="hidden" value="' . __( 'Leave a Reply','fastfood' ) . '" id="replytopost" name="replytopost" />',
				'label_submit'         => __( 'Say It!','fastfood' ),
				'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>.','fastfood' ), admin_url( 'profile.php' ), $user_identity ) . '</p>',
				'cancel_reply_link'    => '<br />' . __( 'Cancel reply','fastfood' ),
				'title_reply'          => __( 'Leave a comment','fastfood' ),
			);

			//output comment form
			comment_form($ff_custom_args); 
			if ( ( $fastfood_opt['fastfood_cust_comrep'] == 1 ) && !$ff_is_mobile_browser ) { // disable custom script if default comment-reply is in use ?>
				<script type="text/javascript">
					/* <![CDATA[ */
					addComment.resetForm();
					addComment.addCloseButton();
					/* ]]> */
				</script>
			<?php } ?>
		<?php } ?>
		<div class="fixfloat"></div> <?php 
	}
?>
<!-- end comments -->
