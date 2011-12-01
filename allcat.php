<?php
	//shows "all categories" page.
?>
<?php get_header(); ?>

<?php fastfood_hook_before_posts(); ?>
<div id="posts_content" class="posts_wide">
	<div class="hentry">
		<h2 class="storytitle"><?php _e( 'Categories','fastfood' ); ?></h2>
		<div class="comment_tools">
			<?php _e( 'All Categories','fastfood' ); ?>
		</div>
		<div class="storycontent">
			<ul>
				<?php wp_list_categories( 'title_li=' ); ?>
			</ul>
		</div>
	</div>
</div>
<?php fastfood_hook_after_posts(); ?>

<?php get_footer(); ?>
