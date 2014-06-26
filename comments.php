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

<div id="comments">

<?php
	if ( post_password_required() ) {

		echo '<div class="comment_tools">' . __( 'Enter your password to view comments.','fastfood' ) . '</div>';

	} elseif ( comments_open() ) {

		echo '<div class="comment_tools">';
		comments_number( __( 'No Comments','fastfood' ), __( '1 Comment','fastfood' ), __( '% Comments','fastfood' ) );
		echo sprintf( '<span class="hide_if_print"> - <a class="show_comment_form" href="#respond" title="%s">%s</a></span>',
			esc_attr__( 'Leave a comment', 'fastfood' ),
			esc_html__( 'Leave a comment', 'fastfood' )
		);
		echo '</div>';

	} elseif ( have_comments() ) {

		echo '<div class="comment_tools">';
		comments_number( __( 'No Comments','fastfood' ), __( '1 Comment','fastfood' ), __( '% Comments','fastfood' ) );
		echo '</div>';

	}
?>

<?php get_template_part( 'comments-list' ); ?>

<?php comment_form();  ?>

</div>

<?php fastfood_hook_comments_after(); ?>

<!-- end comments -->
