<!-- begin comments -->
<?php
	if ( isset( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
		die ( 'Please do not load this page directly. Thanks!' );
	}

	if ( post_password_required() ) {
		echo '<div class="comment_tools" id="comments" style="text-align: right;">' . _e( 'Enter your password to view comments.' ) . '</div>';
		return;
	}
	//if comments are open
	if ( comments_open() ) { 
		global $fastfood_opt, $is_ff_printpreview;
?>

	<div class="comment_tools" id="comments" style="text-align: right;">
		<?php comments_number( __( 'No Comments' ), __( '1 Comment' ), __( '% Comments' ) ); ?> - <a href="#respond" title="<?php _e( "Leave a comment" ); ?>" <?php if ( !$is_ff_printpreview ) echo 'onclick="return addComment.viewForm()"'; ?> ><?php _e( "Leave a comment" ); ?></a>
	</div>

	<?php if ( have_comments() ) { ?>

		<ol id="commentlist">
			<?php
			wp_list_comments( 'type=comment' );
			?><li class="trackback" style="margin-top: 20px; margin-bottom: 10px;">trackbacks:</li><?php
			wp_list_comments( 'type=pings' );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>

			<div class="navigate_comments">
				<div class="nav-previous-comm"><?php previous_comments_link(); ?></div>
				<div class="nav-next-comm"><?php next_comments_link(); ?></div>
				<div class="fixfloat"> </div>
			</div>

		<?php
		}
	}
	
	if ( !$is_ff_printpreview ) { //script not to be loaded in print preview
		//define custom argoments for comment form
		$custom_args = array(
			'comment_field'        => '<p class="comment-form-comment" style="text-align: center;"><textarea id="comment" name="comment" cols="45" rows="7" style="width: 95%;" aria-required="true"></textarea></p>',
			'comment_notes_after'  => '<p class="form-allowed-tags"><small style="float: right; min-width: 200px; color: #999999;">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), allowed_tags() ) . '</small></p><input type="hidden" value="' . __('Reply to Comment') . '" id="replytocomment" name="replytocomment" /><input type="hidden" value="' . __( 'Leave a Reply' ) . '" id="replytopost" name="replytopost" />',
			'label_submit'         => __( 'Say It!' ),
			'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>.' ), admin_url( 'profile.php' ), $user_identity ) . '</p>',
			'cancel_reply_link'    => '<br />' . __( 'Cancel reply' ),
			'title_reply'          => __( 'Leave a comment' ),
		);

		//output comment form
		comment_form($custom_args); 
		if ( $fastfood_opt['fastfood_cust_comrep'] == 1 ) { // disable custom script if default comment-reply is in use ?>
			<script type="text/javascript">
				/* <![CDATA[ */
				addComment.resetForm();
				addComment.addCloseButton();
				/* ]]> */
			</script>
		<?php } ?>
	<?php } ?>
	
	<div class="fixfloat"></div>

<?php
	}
?>
<!-- end comments -->
