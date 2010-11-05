<?php get_header();

$website = get_bloginfo( 'url' );

?>

<div class="post" id="post-404-not-found">

	<div class="wp-caption aligncenter"><h2 class="storytitle">Error 404 - <?php _e( 'Page not found' ); ?></h2></div>

	<div class="storycontent">

		<p><?php _e( "Sorry, you're looking for something that isn't here" ,'fastfood' ); ?>: <u><?php echo $website . esc_html( $_SERVER['REQUEST_URI'] ); ?></u></p>
		<p><?php _e( 'You can try the following:','fastfood' ); ?></p>

		<ul>
			<li><?php _e( 'search the site using the searchbox in the upper-right','fastfood' ); ?></li>
			<li><?php _e( 'see the suggested pages in the above menu','fastfood' ); ?></li>
			<li><?php _e( 'browse the site throught the popup menu on bottom left','fastfood' ); ?></li>
		</ul>

	</div>

	<div class="fixfloat"> </div>

</div>


<?php get_footer(); ?>
