<?php
/**
 Plugin Name: 85. ISS User
 Author: Azra Syed
 Version: 1.0
 */

require_once (plugin_dir_path( __FILE__ ) . "/functions.php");

function account_home_page() {
    include (plugin_dir_path( __FILE__ ) . "/new_user.php");
}

function iss_user_register_menu_page() {
	//$my_pages [] = add_menu_page ( 'Account', 'Account', 'iss_admin', 'account_home', 'account_home_page', 'dashicons-id-alt', 3 );
     $my_pages [] = add_submenu_page ( null, 'Account', 'Account', 'iss_admin', 'account_home', 'account_home_page' );
	// $my_pages [] = add_submenu_page ( null, 'Edit User', 'Edit User', 'iss_admin', 'edit_user', 'edit_user_page' );
	// $my_pages [] = add_submenu_page ( null, 'Delete User', 'Delete User', 'iss_admin', 'delete_user', 'delete_user_page' );
	
    foreach ( $my_pages as $my_page ) {
		add_action ( 'load-' . $my_page, 'iss_user_load_admin_custom_css' );
	}
}
add_action ( 'admin_menu', 'iss_user_register_menu_page' );

function iss_user_load_admin_custom_css() {
	add_action ( 'admin_enqueue_scripts', 'load_custom_issv_style' );
}
?>