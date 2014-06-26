<?php
/**
 * Template Name: "Coming Soon" page
 *
 * Also known as "landing page", "splash screen page", " launch page", etc.
 * No menus, no sidebars, no widget, nothing at all, just a title and a content
 *
 * @package fastfood
 * @since fastfood 0.35
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes( 'xhtml' ); ?>>

	<head profile="http://gmpg.org/xfn/11">

		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />

		<meta name="viewport" content="width=100%; initial-scale=1; maximum-scale=1; minimum-scale=1; user-scalable=no;" />

		<title><?php wp_title( '&laquo;', true, 'right' ); ?></title>

		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

		<?php wp_get_archives( 'type=monthly&format=link&limit=10' ); ?>

		<?php wp_head(); ?>

	</head>

	<body <?php body_class( 'coming-soon' ); ?>>

		<div id="ff_background"></div>

		<?php if ( have_posts() ) {

			while ( have_posts() ) {

				the_post(); ?>

				<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

					<div class="storytitle">

						<span><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></span>

						<h1><?php the_title(); ?></h1>

					</div>

					<div class="storycontent">

						<?php echo nl2br( get_the_content() ); ?>

					</div>

					<?php fastfood_hook_entry_bottom(); ?>

				</div>

			<?php } //end while ?>

		<?php } ?>

		<?php wp_footer(); ?>

	</body>

</html>