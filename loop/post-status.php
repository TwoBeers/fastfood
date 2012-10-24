<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
	<div class="status-cont">
		<div class="status-avatar">
			<?php echo get_avatar( $post->post_author, 50, $default=get_option('avatar_default'), get_the_author() ); ?>
		</div>
		<div class="status-subcont">
			<div><small><strong><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php printf( __( 'View all posts by %s', 'fastfood' ), esc_attr( get_the_author() ) ); ?>"><?php echo get_the_author(); ?></a><?php edit_post_link( __( 'Edit', 'fastfood' ),' - ' ); ?></strong></small></div>
			<?php the_content(); ?>
			<div class="fixfloat status-date"><small><?php echo fastfood_friendly_date(); ?></small> <a href="<?php the_permalink() ?>" rel="bookmark">&rarr;</a></div>
		</div>
	</div>
	<div class="fixfloat"> </div>
</div>