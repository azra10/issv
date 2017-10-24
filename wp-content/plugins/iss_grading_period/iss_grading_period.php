<?php
/**
 Plugin Name: 5. ISS Grading Period
 Author: Azra Syed
 Version: 1.0
 */

require_once (plugin_dir_path( __FILE__ ) . "/functions.php");

function gp_home_page() {
    include (plugin_dir_path( __FILE__ ) . "/list_gp.php");
}
function new_gp_page() {
	include (plugin_dir_path( __FILE__ ) . "/new_gp.php");
}
function edit_gp_page() {
	include (plugin_dir_path( __FILE__ ) . "/edit_gp.php");
}
function delete_gp_page() {
	include (plugin_dir_path( __FILE__ ) . "/delete_gp.php");
}
function iss_gp_register_menu_page() {
	$my_pages [] = add_menu_page ( 'Grading Peeriods', 'Grading Periods', 'iss_admin', 'gp_home', 'gp_home_page', 'dashicons-id-alt', 3 );
    $my_pages [] = add_submenu_page ( null, 'Add Grading Period', 'Add Grading Period', 'iss_admin', 'new_gp', 'new_gp_page' );
	$my_pages [] = add_submenu_page ( null, 'Edit Grading Period', 'Edit Grading Period', 'iss_admin', 'edit_gp', 'edit_gp_page' );
	$my_pages [] = add_submenu_page ( null, 'Delete Grading Period', 'Delete Grading Period', 'iss_admin', 'delete_gp', 'delete_gp_page' );
	
    foreach ( $my_pages as $my_page ) {
		add_action ( 'load-' . $my_page, 'iss_gp_load_admin_custom_css' );
	}
}
add_action ( 'admin_menu', 'iss_gp_register_menu_page' );

function iss_gp_load_admin_custom_css() {
	add_action ( 'admin_enqueue_scripts', 'load_custom_issv_style' );
}
?>