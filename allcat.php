<?php
get_header(); //shows "all categories" page.
?>
<div class="post">

	<h2 class="storytitle"><?php _e( 'Categories' ); ?></h2>

	<div class="comment_tools top_meta">
		<?php _e( 'All Categories' ); ?>
	</div>

	<div class="storycontent">
		<ul>
			<?php wp_list_categories( 'title_li=' ); ?>
		</ul>
	</div>

</div>
<?php get_footer(); ?>
