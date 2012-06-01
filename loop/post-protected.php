<?php global $fastfood_opt; ?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
	<?php fastfood_featured_title( array( 'featured' => false ) ); ?>
	<?php fastfood_extrainfo( array( 'comms' => 0, 'tags' => 0, 'cats' => 0 ) ); ?>
	<div class="storycontent">
		<?php the_content(); ?>
	</div>
	<div class="fixfloat"> </div>
</div>
