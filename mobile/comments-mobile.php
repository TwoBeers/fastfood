<?php
/**
 * The mobile theme - Comments template
 *
 * @package fastfood
 * @subpackage mobile
 * @since 0.31
 */
?>

<!-- begin comments -->
<?php
	if ( post_password_required() ) {
		echo '<p>' . __( 'Enter your password to view comments.', 'fastfood' ) . '</p>';
		return;
	} 
?>

<?php if ( have_comments() ) { ?>

	<?php echo apply_filters( 'fastfood_mobile_filter_seztitle', __('Comments','fastfood') . ' (' . get_comments_number() . ')' ); ?>

	<?php do_action( 'fastfood_mobile_hook_comments_before' ); ?>

	<ol class="commentlist">
		<?php wp_list_comments(); ?>
	</ol>

	<?php do_action( 'fastfood_mobile_hook_comments_after' ); ?>

<?php } ?>

<?php
	if ( comments_open() )
		comment_form();
?>
<!-- end comments -->
