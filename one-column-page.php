<?php
/**
 * Template Name: One column, no sidebar
 *
 * A custom page template without sidebar.
 */
?>
<?php get_header(); ?>

<?php fastfood_hook_before_posts(); ?>
<div id="posts_content" class="posts_wide">

<?php if ( have_posts() ) {
	global $ff_is_printpreview;
	while ( have_posts() ) {
		the_post(); ?>
		<?php fastfood_hook_before_post(); ?>
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<?php if ( $ff_is_printpreview ) { ?>
				<div id="close_preview">
					<a href="<?php the_permalink() ?>" rel="bookmark"><?php _e( 'Close','fastfood' ); ?></a>
					<a href="javascript:window.print()" id="print_button"><?php _e( 'Print','fastfood' ); ?></a>
					<script type="text/javascript" defer="defer">
						/* <![CDATA[ */
						document.getElementById("print_button").style.display = "block"; // print button (available only with js active)
						/* ]]> */
					</script>
				</div>
			<?php } ?>
			<?php fastfood_hook_before_post_title(); ?>
			<h2 class="storytitle">
				<a href="<?php the_permalink(); ?>" rel="bookmark">
					<?php
					$ff_post_title = the_title_attribute( 'echo=0' );
					if ( !$ff_post_title ) {
						_e( '(no title)','fastfood' );
					} else {
						echo $ff_post_title;
					}
					?>
				</a>
			</h2>
			<?php fastfood_hook_after_post_title(); ?>
			<?php fastfood_extrainfo( false, false, true, false, false, true ); ?>
			<?php fastfood_hook_before_post_content(); ?>
			<div class="storycontent">
				<?php the_content(); ?>
			</div>
			<?php fastfood_hook_after_post_content(); ?>
			<div class="fixfloat">
				<?php wp_link_pages( 'before=<div class="comment_tools">' . __( 'Pages','fastfood' ) . ':&after=</div><div class="fixfloat"></div>' ); ?>
			</div>
		</div>
		<?php fastfood_hook_after_post(); ?>

		<?php comments_template(); // Get wp-comments.php template ?>

	<?php } //end while
} else {?>
	<p><?php _e( 'Sorry, no posts matched your criteria.','fastfood' );?></p>
<?php } //endif ?>

</div>
<?php fastfood_hook_after_posts(); ?>

<?php get_footer(); ?>
