<?php get_header(); ?>

<?php if ( have_posts() ) {
	global $ff_is_printpreview;
	while ( have_posts() ) {
		the_post(); ?>

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

				<h2 class="storytitle">
					<a href="<?php the_permalink() ?>" rel="bookmark">
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

				<div class="meta top_meta">

					<div class="metafield">
						<div class="metafield_trigger mft_comm no-mobile" style="right: 10px; width:16px"> </div>
						<div class="metafield_content">
							<?php _e( 'Comments','fastfood' ); ?>:
							<?php comments_popup_link( __( 'No Comments','fastfood' ), __( '1 Comment','fastfood' ), __( '% Comments','fastfood' ) ); // number of comments?>
						</div>
					</div>

					<?php fastfood_multipages(); ?>

					<div id="edit_link" class="metafield_trigger" style="right: 70px;"><?php edit_post_link( __( 'Edit','fastfood' ),'' ); ?></div>

				</div>

				<div class="storycontent">
					<?php the_content();	?>
				</div>

				<div class="fixfloat">
					<?php wp_link_pages( 'before=<div class="comment_tools">' . __( 'Pages','fastfood' ) . ':&after=</div><div class="fixfloat"></div>' ); ?>
				</div>

				<div class="fixfloat"> </div>
			</div>

			<?php comments_template(); // Get wp-comments.php template ?>

		<?php	} //end while
	} else {?>

<p><?php _e( 'Sorry, no posts matched your criteria.','fastfood' );?></p>

<?php } //endif ?>

<?php get_footer(); ?>
