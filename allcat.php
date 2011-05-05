<?php
	//shows "all categories" page.
?>
<?php get_header(); ?>

<div id="posts_content" class="posts_narrow">
	<div class="post">
		<h2 class="storytitle"><?php _e( 'Categories','fastfood' ); ?></h2>
		<div class="comment_tools top_meta">
			<?php _e( 'All Categories','fastfood' ); ?>
		</div>
		<div class="storycontent">
			<ul>
				<?php wp_list_categories( 'title_li=' ); ?>
			</ul>
		</div>
	</div>
</div>

<?php get_footer(); ?>
