<?php
/**
 Plugin Name: ISS Parent/Student Management
 Plugin URI: http://learnislam.org/
 Description: Replace word NetBeans with a link to NetBeans site.
 Author: Azra Syed
 Version: 1.0
 Author URI: http://blogs.sun.com/netbeansphp
 */
if (! defined ( 'ISS_PATH' )) {
	$this_plugin_file = __FILE__;
	if (isset ( $plugin )) {
		$this_plugin_file = $plugin;
	} elseif (isset ( $mu_plugin )) {
		$this_plugin_file = $mu_plugin;
	} elseif (isset ( $network_plugin )) {
		$this_plugin_file = $network_plugin;
	}
	define ( 'ISS_PATH', WP_PLUGIN_DIR . '/' . basename ( dirname ( $this_plugin_file ) ) );
	define ( 'ISS_URL', plugin_dir_url ( ISS_PATH ) . basename ( dirname ( $this_plugin_file ) ) );
}

require_once (ISS_PATH . "/includes/functions.php");
require_once (ISS_PATH . "/includes/constants.php");
require_once (ISS_PATH . "/includes/widgets.php");

require_once (ISS_PATH . "/includes/shortcodes.php");
function students_home_page() {
	include (ISS_PATH . "/browsestudent.php");
}
function parents_home_page() {
	include (ISS_PATH . "/browseparent.php");
}
function archived_home_page() {
	include (ISS_PATH . "/browsearchived.php");
}
function view_parent_page() {
	include (ISS_PATH . "/view_parent.php");
}
function print_parent_page() {
	include (ISS_PATH . "/print_parent.php");
}
function payment_parent_page() {
	include (ISS_PATH . "/payment_parent.php");
}
function edit_parent_page() {
	include (ISS_PATH . "/edit_parent.php");
}
function email_home_page() {
	include (ISS_PATH . "/email_home.php");
}
function new_parent_page() {
	include (ISS_PATH . "/new_parent.php");
}
function iss_register_menu_page() {
	// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	$my_pages [] = add_menu_page ( 'Parents', 'Parents', 'iss_board', 'parents_home', 'parents_home_page', 'dashicons-id-alt', 3 );
	$my_pages [] = add_menu_page ( 'Students', 'Students', 'iss_board', 'students_home', 'students_home_page', 'dashicons-groups', 4 );
	// $my_pages[] = add_menu_page( 'Classes', 'Classes', 'iss_admin', 'class_home','class_home_page', 'dashicons-list-view', 5);
	$my_pages [] = add_menu_page ( 'Archived', 'Archived', 'iss_board', 'archived_home', 'archived_home_page', 'dashicons-hidden', 6 );
	// $my_pages[] =add_submenu_page('parents_home', 'Browse', 'Browse', 'iss_admin', 'user_home', 'parents_home_page');
	// $my_pages[] =add_submenu_page('students_home', 'Browse', 'Browse', 'iss_admin', 'students_home', 'students_home_page');
	// $my_pages[] = add_submenu_page(null, 'Search', 'Search', 'iss_admin', 'student_search', 'student_search_page');
	// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	// $my_pages[] =add_submenu_page('parents_home', 'Settings', 'Settings', 'iss_admin', 'issoptions', 'parents_settings_page');
	// $my_pages[] =add_menu_page('Import Parents', 'Import Parents', 'manage_options', 'parent_import', 'parents_import_page', 'dashicons-arrow-up-alt', 5);
	// $my_pages[] =add_submenu_page('parents_home', 'Export', 'Export', 'iss_admin', 'parent_export', 'parents_export_page');
	$my_pages [] = add_submenu_page ( null, 'Payment', 'Payment', 'iss_admin', 'payment_parent', 'payment_parent_page' );
	$my_pages [] = add_submenu_page ( null, 'Print', 'Print', 'iss_board', 'print_parent', 'print_parent_page' );
	$my_pages [] = add_submenu_page ( null, 'View', 'View', 'iss_board', 'view_parent', 'view_parent_page' );
	$my_pages [] = add_submenu_page ( null, 'Edit', 'Edit', 'iss_admin', 'edit_parent', 'edit_parent_page' );
	$my_pages [] = add_submenu_page ( 'parents_home', 'Add', 'Add', 'iss_admin', 'new_parent', 'new_parent_page' );
	$my_pages [] = add_submenu_page ( null, 'Email', 'Email', 'iss_admin', 'email_home', 'email_home_page' );
	
	foreach ( $my_pages as $my_page ) {
		add_action ( 'load-' . $my_page, 'iss_load_admin_custom_css' );
	}
}

add_action ( 'admin_menu', 'iss_register_menu_page' );
add_shortcode ( 'view_parent', 'view_parent_function' );
add_shortcode ( 'edit_parent', 'edit_parent_function' );

// Add custom CSS to plugin pages
function load_custom_iss_style() {
	wp_register_style ( 'custom_iss_admin_css1', ISS_URL . '/css/bootstrap.min.css' ); // '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'); //
	wp_enqueue_style ( 'custom_iss_admin_css1' );
	// wp_register_style( 'custom_iss_admin_css2', '//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
	// wp_enqueue_style( 'custom_iss_admin_css2' );
	// wp_register_style( 'custom_iss_admin_css6', ISS_URL . '/css/bootstrap-theme.min.css' );
	// wp_enqueue_style( 'custom_iss_admin_css6' );
	// wp_register_style( 'custom_iss_admin_css3', ISS_URL . '/css/jquery.dataTables.css' );
	// wp_enqueue_style( 'custom_iss_admin_css3' );
	wp_register_style ( 'custom_iss_admin_css31', ISS_URL . '/css/bootstrap-table.min.css' );
	wp_enqueue_style ( 'custom_iss_admin_css31' );
	// wp_register_style( 'custom_iss_admin_css5', ISS_URL . '/css/jquery-ui.min.css'); //'//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css');
	// wp_enqueue_style( 'custom_iss_admin_css5' );
	wp_register_style ( 'custom_iss_admin_css4', ISS_URL . '/css/iss_form.css' );
	wp_enqueue_style ( 'custom_iss_admin_css4' );
	wp_register_style ( 'custom_iss_admin_css7', ISS_URL . '/css/datepicker.css' );
	wp_enqueue_style ( 'custom_iss_admin_css7' );
	
	wp_register_script ( 'custom_iss_jquery_script', ISS_URL . '/js/jquery-1.12.4.js' ); // '//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js' );
	wp_enqueue_script ( 'custom_iss_jquery_script' );
	wp_register_script ( 'custom_iss_bootstrap_script', ISS_URL . '/js/bootstrap.min.js' ); // '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js');
	wp_enqueue_script ( 'custom_iss_bootstrap_script' );
	wp_register_script ( 'custom_iss_jqueryui_script', ISS_URL . '/js/bootstrap-datepicker.js' ); // '//code.jquery.com/ui/1.12.0/jquery-ui.js');
	wp_enqueue_script ( 'custom_iss_jqueryui_script' );
	wp_register_script ( 'custom_iss_bootstrap_script1', ISS_URL . '/js/bootstrap-table.min.js' ); // '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js');
	wp_enqueue_script ( 'custom_iss_bootstrap_script1' );
	wp_register_script ( 'custom_iss_datatables_script', ISS_URL . '/js/bootstrap-table-en-US.min.js' );
	wp_enqueue_script ( 'custom_iss_datatables_script' );
	wp_register_script ( 'custom_iss_export_script', ISS_URL . '/js/multiselect.min.js' );
	wp_enqueue_script ( 'custom_iss_export_script' );
	
	wp_register_script ( 'custom_iss_form_script', ISS_URL . '/js/iss_form.js' );
	wp_enqueue_script ( 'custom_iss_form_script' );
}
function iss_load_admin_custom_css() {
	add_action ( 'admin_enqueue_scripts', 'load_custom_iss_style' );
}

?>