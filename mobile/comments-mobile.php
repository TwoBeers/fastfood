<?php
/**
 * mobile subtheme -> comments
 *
 * @package fastfood
 * @since fastfood 0.31
 */


?>

<!-- begin comments -->
<?php
	if ( post_password_required() ) { ?>
		<div class="meta" id="comments" style="text-align: right;"><?php _e( 'Enter your password to view comments.', 'fastfood' ); ?></div>
		<?php return;
	} 
?>

<?php if ( have_comments() ) { ?>
	<?php echo fastfood_mobile_seztitle( 'before' ); comments_number( __( 'No Comments', 'fastfood' ), __( '1 Comment', 'fastfood' ), __( '% Comments', 'fastfood' ) ); echo fastfood_mobile_seztitle( 'after' ); ?>
	<ol class="commentlist">
		<?php wp_list_comments(); ?>
	</ol>
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>
		<div class="tbm-pc-navi">
			<?php paginate_comments_links(); ?>
		</div>
	<?php } ?>
<?php } ?>
	
<?php if ( comments_open() ) { ?>

	<?php
	$tbm_fields =  array(
		'author' => '<p class="comment-form-author">' . '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" />' .
					'<label for="author">' . __( 'Name', 'fastfood' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .'</p>',
		'email'  => '<p class="comment-form-email">' . '<input id="email" name="email" type="text" value="' . sanitize_email(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" />' .
					'<label for="email">' . __( 'Email', 'fastfood' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .'</p>',
		'url'    => '<p class="comment-form-url">' . '<input id="url" name="url" type="text" value="' . esc_url( $commenter['comment_author_url'] ) . '" size="30" />' .
					'<label for="url">' . __( 'Website', 'fastfood' ) . '</label>' .'</p>',
	); 
	?>

	<?php $tbm_custom_args = array(
		'fields'               => apply_filters( 'comment_form_default_fields', $tbm_fields ),
		'comment_field'        => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="7" style="width: 98%;" aria-required="true"></textarea></p>',
		'comment_notes_after'  => '',
		'label_submit'         => __( 'Say It!', 'fastfood' ),
		'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>.', 'fastfood' ), admin_url( 'profile.php' ), $user_identity ) . '</p>',
		'title_reply'          => fastfood_mobile_seztitle( 'before' ) . __( 'Leave a comment', 'fastfood' ) . fastfood_mobile_seztitle( 'after' ),
		'title_reply_to'       => fastfood_mobile_seztitle( 'before' ) . __( 'Leave a Reply to %s', 'fastfood' ) . fastfood_mobile_seztitle( 'after' ),
	);
	comment_form( $tbm_custom_args ); ?>
<?php } ?>
<!-- end comments -->
