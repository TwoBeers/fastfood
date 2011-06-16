<?php global $fastfood_opt; ?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
	<?php fastfood_hook_before_post_title(); ?>
	<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php 
		$ff_post_title = the_title( '','',false );
		if ( !$ff_post_title ) {
			_e( '(no title)','fastfood' );
		} else {
			echo $ff_post_title;
		}
		?></a>
	</h2>
	<?php fastfood_hook_after_post_title(); ?>
	
	<?php fastfood_extrainfo( true, true, true, true, true ); ?>

	<?php fastfood_hook_before_post_content(); ?>
	<div class="storycontent">
		<?php if ( $fastfood_opt['fastfood_postexcerpt'] == 1 ) {
			the_excerpt();
		} else {
			the_content();
		} ?>
	</div>
	<?php fastfood_hook_after_post_content(); ?>
	<div class="fixfloat"> </div>
	<?php wp_link_pages( 'before=<div class="comment_tools">' . __( 'Pages','fastfood' ) . ':&after=</div><div class="fixfloat"></div>' ); ?>
</div>
