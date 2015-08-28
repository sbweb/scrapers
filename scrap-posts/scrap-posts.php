<?php
/*
Plugin Name: Scrap Post
Description: Get all posts from nadeemtron.com/policy using this plugin.
Author: Jay Thakur
Version: 1.0
Plugin URI: http://www.webcrazy.in
Author URI: http://www.webcrazy.in
Donate link: http://www.webcrazy.in
License: GPLv2 or later
License URI: http://www.webcrazy.in
*/
global $wpdb, $wp_version;
//define("WP_scontact_TABLE_APP", $wpdb->prefix . "scontact_newsletter_app");



function sP_install() 
{
	// create table if need
}

function sP_deactivation() 
{
	// No action required
}

function sP_admin()
{
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	$sP_table = get_option('sP_table');
	switch($current_page)
	{
		case 'set':
			include('pages/content-setting.php');
			break;
		default:
			include('pages/content-management-show.php');
			break;
	}
}

function main_page(){
	global $wpdb;
	include('pages/scrap-posts.php');

}


function sP_add_to_menu() 
{

	
	global $sP_menu_slug;
	
	add_menu_page( __( 'Scrap Posts', 'scrap-posts' ), __( 'Scrap Posts', 'scrap-posts' ), 'administrator', 'scrap-posts', 'main_page' );
	
	
}
   

function sP_textdomain() 
{
	  load_plugin_textdomain( 'scrap-posts', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}


add_action('plugins_loaded', 'sP_textdomain');
add_action('admin_menu', 'sP_add_to_menu');
register_activation_hook(__FILE__, 'sP_install');
register_deactivation_hook(__FILE__, 'sP_deactivation');


?>