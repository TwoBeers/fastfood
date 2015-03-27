<?php
/**
 * hooks.php
 *
 * Defines every wrapping function for the theme hooks
 * Includes The Hook Alliance support file (https://github.com/zamoose/themehookalliance)
 *
 * @package fastfood
 * @since 0.27
 */


/** Grab the THA theme hooks file */
require_once( get_template_directory() . '/tha/tha-theme-hooks.php' );

function fastfood_hook_head_top() {
	tha_head_top();
	do_action( 'fastfood_hook_head_top' );
}

function fastfood_hook_head_bottom() {
	do_action( 'fastfood_hook_head_bottom' );
	tha_head_bottom();
}

function fastfood_hook_header_before() {
	tha_header_before();
	do_action( 'fastfood_hook_header_before' );
}

function fastfood_hook_header_after() {
	do_action( 'fastfood_hook_header_after' );
	tha_header_after();
}

function fastfood_hook_header_top() {
	tha_header_top();
	do_action( 'fastfood_hook_header_top' );
}

function fastfood_hook_header_bottom() {
	do_action( 'fastfood_hook_header_bottom' );
	tha_header_bottom();
}

function fastfood_hook_header() {
	do_action( 'fastfood_hook_header' );
}

function fastfood_hook_content_before() {
	tha_content_before();
	do_action( 'fastfood_hook_content_before' );
}

function fastfood_hook_content_after() {
	do_action( 'fastfood_hook_content_after' );
	tha_content_after();
}

function fastfood_hook_content_top() {
	tha_content_top();
	do_action( 'fastfood_hook_content_top' );
}

function fastfood_hook_content_bottom() {
	do_action( 'fastfood_hook_content_bottom' );
	tha_content_bottom();
}

function fastfood_hook_entry_before() {
	tha_entry_before();
	do_action( 'fastfood_hook_entry_before' );
}

function fastfood_hook_entry_after() {
	do_action( 'fastfood_hook_entry_after' );
	tha_entry_after();
}

function fastfood_hook_entry_top() {
	tha_entry_top();
	do_action( 'fastfood_hook_entry_top' );
}

function fastfood_hook_entry_bottom() {
	do_action( 'fastfood_hook_entry_bottom' );
	tha_entry_bottom();
}

function fastfood_hook_comments_before() {
	tha_comments_before();
	do_action( 'fastfood_hook_comments_before' );
}

function fastfood_hook_comments_after() {
	do_action( 'fastfood_hook_comments_after' );
	tha_comments_after();
}

function fastfood_hook_comments_top() {
	do_action( 'fastfood_hook_comments_top' );
}

function fastfood_hook_comments_bottom() {
	do_action( 'fastfood_hook_comments_bottom' );
}

function fastfood_hook_sidebars_before( $location = 'every' ) {
	tha_sidebars_before();
	do_action( 'fastfood_hook_sidebars_before' );
	do_action( 'fastfood_hook_' . $location . '_sidebar_before' );
}

function fastfood_hook_sidebars_after( $location = 'every' ) {
	do_action( 'fastfood_hook_' . $location . '_sidebar_after' );
	do_action( 'fastfood_hook_sidebars_after' );
	tha_sidebars_after();
}

function fastfood_hook_sidebar_top( $location = 'every' ) {
	tha_sidebar_top();
	do_action( 'fastfood_hook_sidebar_top' );
	do_action( 'fastfood_hook_' . $location . '_sidebar_top' );
}

function fastfood_hook_sidebar_bottom( $location = 'every' ) {
	do_action( 'fastfood_hook_' . $location . '_sidebar_bottom' );
	do_action( 'fastfood_hook_sidebar_bottom' );
	tha_sidebar_bottom();
}

function fastfood_hook_footer_before() {
	tha_footer_before();
	do_action( 'fastfood_hook_footer_before' );
}

function fastfood_hook_footer_after() {
	do_action( 'fastfood_hook_footer_after' );
	tha_footer_after();
}

function fastfood_hook_footer_top() {
	tha_footer_top();
	do_action( 'fastfood_hook_footer_top' );
}

function fastfood_hook_footer_bottom() {
	do_action( 'fastfood_hook_footer_bottom' );
	tha_footer_bottom();
}

function fastfood_hook_footer() {
	do_action( 'fastfood_hook_footer' );
}

function fastfood_hook_quickbar_top() {
	do_action( 'fastfood_hook_quickbar_top' );
}

function fastfood_hook_quickbar_bottom() {
	do_action( 'fastfood_hook_quickbar_bottom' );
}

function fastfood_hook_post_content_before () {
	do_action( 'fastfood_hook_post_content_before' );
}

function fastfood_hook_post_content_after () {
	do_action( 'fastfood_hook_post_content_after' );
}

function fastfood_hook_comments_list_before() {
	do_action( 'fastfood_hook_comments_list_before' );
}

function fastfood_hook_comments_list_after() {
	do_action( 'fastfood_hook_comments_list_after' );
}

function fastfood_hook_body_top() {
	do_action( 'tha_body_top' );
	do_action( 'fastfood_hook_body_top' );
}

function fastfood_hook_body_bottom() {
	do_action( 'fastfood_hook_body_bottom' );
	do_action( 'tha_body_bottom' );
}

function fastfood_hook_menu_primary_before() {
	do_action( 'fastfood_hook_menu_primary_before' );
}

function fastfood_hook_menu_primary_after() {
	do_action( 'fastfood_hook_menu_primary_after' );
}

function fastfood_hook_menu_primary_top() {
	do_action( 'fastfood_hook_menu_primary_top' );
}

function fastfood_hook_menu_primary_bottom() {
	do_action( 'fastfood_hook_menu_primary_bottom' );
}

function fastfood_hook_menu_secondary_first_before() {
	do_action( 'fastfood_hook_menu_secondary_first_before' );
}

function fastfood_hook_menu_secondary_first_after() {
	do_action( 'fastfood_hook_menu_secondary_first_after' );
}

function fastfood_hook_menu_secondary_first_top() {
	do_action( 'fastfood_hook_menu_secondary_first_top' );
}

function fastfood_hook_menu_secondary_first_bottom() {
	do_action( 'fastfood_hook_menu_secondary_first_bottom' );
}

function fastfood_hook_menu_secondary_second_before() {
	do_action( 'fastfood_hook_menu_secondary_second_before' );
}

function fastfood_hook_menu_secondary_second_after() {
	do_action( 'fastfood_hook_menu_secondary_second_after' );
}

function fastfood_hook_menu_secondary_second_top() {
	do_action( 'fastfood_hook_menu_secondary_second_top' );
}

function fastfood_hook_menu_secondary_second_bottom() {
	do_action( 'fastfood_hook_menu_secondary_second_bottom' );
}

function fastfood_hook_change_view() {
	do_action( 'fastfood_hook_change_view' );
}

function fastfood_hook_attachment_before() {
	do_action( 'fastfood_hook_attachment_before' );
}

function fastfood_hook_attachment_after() {
	do_action( 'fastfood_hook_attachment_after' );
}

function fastfood_hook_loop_before() {
	do_action( 'fastfood_hook_loop_before' );
}

function fastfood_hook_loop_after() {
	do_action( 'fastfood_hook_loop_after' );
}
