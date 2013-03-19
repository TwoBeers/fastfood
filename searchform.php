<?php
/**
 * The template for displaying search forms
 *
 * @package fastfood
 * @since 0.15
 */
?>

<div class="search-form">
	<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="text" class="field" name="s" id="s" value="<?php _e( 'Search','fastfood' ) ?>..." onfocus="if (this.value == '<?php _e( 'Search','fastfood' ) ?>...') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'Search','fastfood' ) ?>...';}" />
		<input type="submit" class="submit searchsubmit hide-if-js" name="submit" value="<?php esc_attr_e( 'Search', 'fastfood' ); ?>" />
	</form>
</div>
