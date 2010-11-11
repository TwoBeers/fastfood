<?php get_header(); ?>

<?php if ( have_posts() ) {
	global $is_ff_printpreview;
	while ( have_posts() ) {
		the_post(); ?>
		
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			
			<?php if ( $is_ff_printpreview ) { ?>
				<div id="close_preview">
					<a href="<?php the_permalink() ?>" rel="bookmark"><?php _e( 'Close' ); ?></a>
					<a href="javascript:window.print()" id="print_button"><?php _e( 'Print' ); ?></a>
					<script type="text/javascript" defer="defer">
						/* <![CDATA[ */
						document.getElementById("print_button").style.display = "block"; // print button (available only with js active)
						/* ]]> */
					</script>
				</div>
			<?php } ?>
		
			<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark">
				<?php 
				$post_title = the_title_attribute( 'echo=0' );
				if ( !$post_title ) {
					_e( '(no title)' );
				} else {
					echo $post_title;
				}
				?>
				</a>
			</h2>
			
			<div class="meta top_meta">
				<div class="metafield_trigger" style="left: 10px;"><?php _e( 'by', 'fastfood' ); ?> <?php the_author() ?></div>
				<div class="metafield">
					<div class="metafield_trigger mft_date" style="right: 100px; width:16px"> </div>
					<div class="metafield_content">
						<?php
						printf( __( 'Published on: <b>%1$s</b>' ), '' );
						the_time( get_option( 'date_format' ) );
						?>
					</div>
				</div>
				<div class="metafield">
					<div class="metafield_trigger mft_cat" style="right: 10px; width:16px"> </div>
					<div class="metafield_content">
						<?php echo __( 'Categories' ) . ':'; ?>
						<?php the_category( ', ' ) ?>
					</div>
				</div>
				<div class="metafield">
					<div class="metafield_trigger mft_tag" style="right: 40px; width:16px"> </div>
					<div class="metafield_content">
						<?php _e( 'Tags:' ); ?>
						<?php if ( !get_the_tags() ) { _e('No Tags'); } else { the_tags('', ', ', ''); } ?>
					</div>
				</div>
				<div class="metafield">
					<div class="metafield_trigger mft_comm" style="right: 70px; width:16px"> </div>
					<div class="metafield_content">
						<?php _e( 'Comments' ); ?>:
						<?php comments_popup_link( __( 'No Comments' ), __( '1 Comment' ), __( '% Comments' ) ); // number of comments?>
					</div>
				</div>
				<div class="metafield_trigger edit_link" style="right: 130px;"><?php edit_post_link( __( 'Edit' ),'' ); ?></div>
			</div>
			
			<div class="storycontent">
				<?php fastfood_content_replace();	?>
			</div>
			
			<div>
					<?php wp_link_pages( 'before=<div class="comment_tools">' . __( 'Pages:' ) . '&after=</div><div class="fixfloat"></div>' ); ?>
			</div>
		
			<div class="fixfloat"> </div>
			
			<?php $tmptrackback = get_trackback_url(); ?>
			
		</div>	
		
		<?php comments_template(); // Get wp-comments.php template ?>
		
		<?php	} //end while
	} else {?>
		
		<p><?php _e( 'Sorry, no posts matched your criteria.' );?></p>
		
	<?php } ?>

<?php get_footer(); ?>
