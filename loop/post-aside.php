<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
	<div class="aside-cont">
		<?php the_content(); ?>
		<div class="fixfloat" style="font-size: 11px; font-style: italic; color: #404040;"><?php the_author(); ?> - <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_time( get_option( 'date_format' ) ); ?></a> - <?php comments_popup_link('(0)', '(1)','(%)'); ?></div>
	</div>
</div>