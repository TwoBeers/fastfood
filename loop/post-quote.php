<?php
/**
 * post-quote.php
 *
 * post with 'quote' format (list view)
 *
 * @package fastfood
 * @since fastfood 0.34
 */
?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

	<?php fastfood_hook_entry_top(); ?>

	<div class="quote-cont">

		<div class="format-icon-32"></div>

		<?php the_content(); ?>

		<div class="fixfloat links"><small><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a> - <?php comments_popup_link('(0)', '(1)','(%)'); ?><?php edit_post_link( __( 'Edit', 'fastfood' ),' - ' ); ?></small></div>

	</div>

	<?php fastfood_hook_entry_bottom(); ?>

</div>
