<?php
/**
 Plugin Name: 80. ISS Teacher
 Author: Azra Syed
 Version: 1.0
 */

require_once (plugin_dir_path( __FILE__ ) . "/functions.php");

function teacher_home_page() {
    include (plugin_dir_path( __FILE__ ) . "/list_teacher.php");
}
function new_teacher_page() {
	include (plugin_dir_path( __FILE__ ) . "/new_teacher.php");
}
function edit_teacher_page() {
	include (plugin_dir_path( __FILE__ ) . "/edit_teacher.php");
}
function delete_teacher_page() {
	include (plugin_dir_path( __FILE__ ) . "/delete_teacher.php");
}
function iss_teacher_register_menu_page() {
	$my_pages [] = add_menu_page ( 'Teachers', 'Teachers', 'iss_admin', 'teacher_home', 'teacher_home_page', 'dashicons-id-alt', 3 );
    $my_pages [] = add_submenu_page ( null, 'Add Teacher', 'Add Teacher', 'iss_admin', 'new_teacher', 'new_teacher_page' );
	$my_pages [] = add_submenu_page ( null, 'Edit Teacher', 'Edit Teacher', 'iss_admin', 'edit_teacher', 'edit_teacher_page' );
	$my_pages [] = add_submenu_page ( null, 'Delete Teacher', 'Delete Teacher', 'iss_admin', 'delete_teacher', 'delete_teacher_page' );
	
    foreach ( $my_pages as $my_page ) {
		add_action ( 'load-' . $my_page, 'iss_teacher_load_admin_custom_css' );
	}
}
add_action ( 'admin_menu', 'iss_teacher_register_menu_page' );

function iss_teacher_load_admin_custom_css() {
	add_action ( 'admin_enqueue_scripts', 'load_custom_issv_style' );
}
?>