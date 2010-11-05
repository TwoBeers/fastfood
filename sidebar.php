<!-- begin sidebar -->
<div id="sidebardx">

	<div id="search" style="margin-bottom: 5px;">
		<div class="search-form">
			<form action="<?php echo home_url(); ?>" id="searchform" method="get">
				<input type="text" onfocus="if (this.value == '<?php _e( "Search" ) ?>...')
				{this.value = '';}" onblur="if (this.value == '')
				{this.value = '<?php _e( "Search" ) ?>...';}" id="s" name="s" value="<?php _e( 'Search' ) ?>..." style="width: 210px;" />
				<input type="hidden" id="searchsubmit" />
			</form>
		</div>
	</div>

	<?php 	/* Widgetized sidebar, if you have the plugin installed. */
	if ( !dynamic_sidebar( 'primary-widget-area' ) ) { ?>

		<div id="w_meta" class="widget"><div class="w_title"><?php _e( 'Meta' ); ?></div>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<?php wp_meta(); ?>
			</ul>
		</div>

		<div id="w_pages" class="widget"><div class="w_title"><?php _e( 'Pages' ); ?></div><ul><?php wp_list_pages( 'title_li=' ); ?></ul></div>
		<div id="w_bookmarks" class="widget"><div class="w_title"><?php _e( 'Blogroll' ); ?></div><ul><?php wp_list_bookmarks( 'title_li=0&categorize=0' ); ?></ul></div>
		<div id="w_categories" class="widget"><div class="w_title"><?php _e( 'Categories' ); ?></div><ul><?php wp_list_categories( 'title_li=' ); ?></ul></div>

		<div id="w_archives" class="widget"><div class="w_title"><?php _e( 'Archives' ); ?></div>
			<ul>
			<?php wp_get_archives( 'type=monthly' ); ?>
			</ul>
		</div>

	<?php } ?>

	<div class="fixfloat"> </div>

</div>
<!-- end sidebar -->