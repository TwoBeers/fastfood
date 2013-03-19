<?php
/**
 * comments.php
 *
 * This template file includes both the comments list and
 * the comment form
 *
 * @package fastfood
 * @since 0.15
 */
?>

<!-- begin comments -->

<?php fastfood_hook_comments_before(); ?>

<?php
	if ( post_password_required() ) {
		echo '<div class="comment_tools" id="comments">' . __( 'Enter your password to view comments.','fastfood' ) . '</div>';
		return;
	}
?>


<?php if ( comments_open() ) { ?>

	<div id="comments" class="comment_tools">
		<?php comments_number( __( 'No Comments','fastfood' ), __( '1 Comment','fastfood' ), __( '% Comments','fastfood' ) ); ?><span class="hide_if_print"> - <a class="show_comment_form" href="#respond" title="<?php esc_attr_e( "Leave a comment",'fastfood' ); ?>"><?php _e( "Leave a comment",'fastfood' ); ?></a></span>
	</div>

<?php } elseif ( have_comments() ) { ?>

	<div id="comments" class="comment_tools">
		<?php comments_number( __( 'No Comments','fastfood' ), __( '1 Comment','fastfood' ), __( '% Comments','fastfood' ) ); ?>
	</div>

<?php } ?>

<?php if ( have_comments() ) { ?>

	<?php fastfood_hook_comments_list_before(); ?>

	<ol id="commentlist">
		<?php wp_list_comments(); ?>
	</ol>

	<?php fastfood_hook_comments_list_after(); ?>

<?php } ?>

<?php if ( comments_open() && !fastfood_is_printpreview() ) { // if comments are open and not in print preview ?>

	<?php comment_form();  ?>

<?php } ?>

<br class="fixfloat">

<?php fastfood_hook_comments_after(); ?>

<!-- end comments -->
