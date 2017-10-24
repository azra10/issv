<?php
/**
 Plugin Name: 90. ISS Class
 Author: Azra Syed
 Version: 1.0
 */

require_once (plugin_dir_path( __FILE__ ) . "/functions.php");

function class_home_page() {
    include (plugin_dir_path( __FILE__ ) . "/list_class.php");
}
function new_class_page() {
	include (plugin_dir_path( __FILE__ ) . "/new_class.php");
}
function edit_class_page() {
	include (plugin_dir_path( __FILE__ ) . "/edit_class.php");
}
function delete_class_page() {
	include (plugin_dir_path( __FILE__ ) . "/delete_class.php");
}
function iss_class_register_menu_page() {
	$my_pages [] = add_menu_page ( 'Class', 'Class', 'iss_admin', 'class_home', 'class_home_page', 'dashicons-id-alt', 3 );
    $my_pages [] = add_submenu_page ( null, 'Add Teacher', 'Add Teacher', 'iss_admin', 'new_class', 'new_class_page' );
	$my_pages [] = add_submenu_page ( null, 'Edit Teacher', 'Edit Teacher', 'iss_admin', 'edit_class', 'edit_class_page' );
	$my_pages [] = add_submenu_page ( null, 'Delete Teacher', 'Delete Teacher', 'iss_admin', 'delete_class', 'delete_class_page' );
	
    foreach ( $my_pages as $my_page ) {
		add_action ( 'load-' . $my_page, 'iss_class_load_admin_custom_css' );
	}
}
add_action ( 'admin_menu', 'iss_class_register_menu_page' );

function iss_class_load_admin_custom_css() {
	add_action ( 'admin_enqueue_scripts', 'load_custom_issv_style' );
}
?>