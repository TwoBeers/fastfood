<?php
/**
 * comments-list.php
 *
 * This template file includes the comments list
 *
 * @package fastfood
 * @since 0.37
 */
?>

<?php if ( !post_password_required() && have_comments() ) { ?>

<div id="commentlist-wrap">

	<?php fastfood_hook_comments_list_before(); ?>

	<ol id="commentlist">
		<?php wp_list_comments(); ?>
	</ol>

	<?php fastfood_hook_comments_list_after(); ?>

</div>

<?php } ?>
