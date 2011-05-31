<?php global $fastfood_opt; ?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
	<h2 class="storytitle"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php 
		$ff_post_title = the_title( '','',false );
		if ( !$ff_post_title ) {
			_e( '(no title)','fastfood' );
		} else {
			echo $ff_post_title;
		}
		?></a>
	</h2>
	<?php fastfood_extrainfo( true, true, false, false, false ); ?>
	<div class="storycontent">
		<?php the_content(); ?>
	</div>
	<div class="fixfloat"> </div>
</div>
