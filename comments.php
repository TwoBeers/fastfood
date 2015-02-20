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

	<?php fastfood_hook_comments_top(); ?>

	<div id="commentlist-wrap">

		<?php get_template_part( 'comments-list' ); ?>

	</div>

	<?php comment_form();  ?>

	<?php fastfood_hook_comments_bottom(); ?>

</div>

<?php fastfood_hook_comments_after(); ?>

<!-- end comments -->
