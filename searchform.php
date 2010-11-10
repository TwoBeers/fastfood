<div class="search-form">
	<form action="<?php echo home_url(); ?>" class="ff-searchform" method="get">
		<input type="text" onfocus="if (this.value == '<?php _e( 'Search' ) ?>...')
		{this.value = '';}" onblur="if (this.value == '')
		{this.value = '<?php _e( 'Search' ) ?>...';}" class="ff-searchinput" name="s" value="<?php _e( 'Search' ) ?>..."  style="width: 210px;" />
		<input type="hidden" class="ff-searchsubmit" />
	</form>
</div>
