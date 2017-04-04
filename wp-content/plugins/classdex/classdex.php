<?php
/*
 * Plugin Name: ClassDex
 * Plugin URI: http://howtononprofit.wordpress.com/classdex/
 * Description: A class and customer management system for Wordpress.
 * Author: John Daskovsky
 * Version: 1.2.4
 * Author URI: http://howtononprofit.wordpress.com/about/
 *
 * Copyright 2013 John Daskovsky
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

// Discover plugin path even if symlinked
if (! defined ( 'CLASSDEX_PATH' )) {
	$this_plugin_file = __FILE__;
	if (isset ( $plugin )) {
		$this_plugin_file = $plugin;
	} elseif (isset ( $mu_plugin )) {
		$this_plugin_file = $mu_plugin;
	} elseif (isset ( $network_plugin )) {
		$this_plugin_file = $network_plugin;
	}
	define ( 'CLASSDEX_PATH', WP_PLUGIN_DIR . '/' . basename ( dirname ( $this_plugin_file ) ) );
	define ( 'CLASSDEX_URL', plugin_dir_url ( CLASSDEX_PATH ) . basename ( dirname ( $this_plugin_file ) ) );
}

require_once (CLASSDEX_PATH . "/includes/functions.php");
require_once (CLASSDEX_PATH . "/includes/shortcodes.php");

// Install
function classdex_install() {
	global $wpdb;
	$tablename = get_table_name ( 'classes' );
	$query = "CREATE TABLE IF NOT EXISTS `{$tablename}` (
		`class_id` int(11) NOT NULL AUTO_INCREMENT,
		`public_id` varchar(10) NOT NULL,
		`title` varchar(255) NOT NULL,
		`teacher` varchar(255) NOT NULL,
		`start_date` date NOT NULL,
		`end_date` date NOT NULL,
		`time` varchar(255) NOT NULL,
		`description` text NOT NULL,
		`fee` int(5) NOT NULL,
		`discounts` tinyint(1) NOT NULL,
		`max_size` int(3) NOT NULL,
		`yoga_class` tinyint(1) NOT NULL,
		`seminar` tinyint(1) NOT NULL,
		`meditation` tinyint(1) NOT NULL,
		`wellness` tinyint(1) NOT NULL,
		`philosophy` tinyint(1) NOT NULL,
		`canceled` tinyint(1) NOT NULL,
		`custom_html` text NOT NULL,
		`image` varchar(255) NOT NULL,
		`image_justified` tinyint(1) NOT NULL,
		`acct_type` int(2) NOT NULL,
		PRIMARY KEY (`class_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
	$wpdb->query ( $query );
	$tablename = get_table_name ( 'customers' );
	$query = "CREATE TABLE IF NOT EXISTS `{$tablename}` (
		`cust_id` int(11) NOT NULL AUTO_INCREMENT,
		`first_name` varchar(35) NOT NULL,
		`last_name` varchar(35) NOT NULL,
		`address` varchar(255) NOT NULL,
		`city` varchar(255) NOT NULL,
		`state` varchar(3) NOT NULL,
		`zip` int(5) NOT NULL,
		`phone` varchar(20) NOT NULL,
		`email` varchar(100) NOT NULL,
		`active_date` date NOT NULL,
		`signed_waiver` date NOT NULL,
		`class_credit` int(5) NOT NULL,
		`green` tinyint(1) NOT NULL,
		`senior` tinyint(1) NOT NULL,
		`student` tinyint(1) NOT NULL,
		`member` tinyint(1) NOT NULL,
		`member_expiration` date NOT NULL,
		PRIMARY KEY (`cust_id`),
		FULLTEXT KEY `full_name_index` (`first_name`,`last_name`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
	$wpdb->query ( $query );
	$tablename = get_table_name ( 'payments' );
	$query = "CREATE TABLE IF NOT EXISTS `{$tablename}` (
		`pay_id` int(11) NOT NULL AUTO_INCREMENT,
		`cust_id` int(11) NOT NULL,
		`reg_id` int(11) NOT NULL,
		`amount` decimal(6,2) NOT NULL,
		`pay_type` int(2) NOT NULL,
		`acct_type` int(2) NOT NULL,
		`date_time` datetime NOT NULL,
		PRIMARY KEY (`pay_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
	$wpdb->query ( $query );
	$tablename = get_table_name ( 'registrations' );
	$query = "CREATE TABLE IF NOT EXISTS `{$tablename}` (
		`reg_id` int(11) NOT NULL AUTO_INCREMENT,
		`class_id` int(11) NOT NULL,
		`cust_id` int(11) NOT NULL,
		`paid` tinyint(1) NOT NULL,
		PRIMARY KEY (`reg_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
	$wpdb->query ( $query );
	
	// Added notes to customers table in version 1.1.8
	$tablename = get_table_name ( 'customers' );
	$query = "alter table `{$tablename}` add column `notes` text not null";
	$wpdb->query ( $query );
}
register_activation_hook ( __FILE__, 'classdex_install' );

if (function_exists ( 'register_update_hook' )) {
	register_update_hook ( __FILE__, 'classdex_install' );
}

// Create plugin Options page
function classdex_init() {
	register_setting ( 'classdex_options_hidden', 'classdex_zout_register_date' );
	register_setting ( 'classdex_options_hidden', 'classdex_zout_register_time' );
	register_setting ( 'classdex_options_hidden', 'classdex_zout_paypal_date' );
	register_setting ( 'classdex_options_hidden', 'classdex_zout_paypal_time' );
	register_setting ( 'classdex_options', 'classdex_zout_email' );
	register_setting ( 'classdex_options', 'classdex_mailchimp_apikey' );
	register_setting ( 'classdex_options', 'classdex_mailchimp_listid' );
	register_setting ( 'classdex_options', 'classdex_zip_start' );
	register_setting ( 'classdex_options', 'classdex_zip_stop' );
	register_setting ( 'classdex_options', 'classdex_month_01' );
	register_setting ( 'classdex_options', 'classdex_month_02' );
	register_setting ( 'classdex_options', 'classdex_month_03' );
	register_setting ( 'classdex_options', 'classdex_month_04' );
	register_setting ( 'classdex_options', 'classdex_month_05' );
	register_setting ( 'classdex_options', 'classdex_month_06' );
	register_setting ( 'classdex_options', 'classdex_month_07' );
	register_setting ( 'classdex_options', 'classdex_month_08' );
	register_setting ( 'classdex_options', 'classdex_month_09' );
	register_setting ( 'classdex_options', 'classdex_month_10' );
	register_setting ( 'classdex_options', 'classdex_month_11' );
	register_setting ( 'classdex_options', 'classdex_month_12' );
	register_setting ( 'classdex_options', 'classdex_waiver' );
	register_setting ( 'classdex_options', 'classdex_list_shortcode' );
	register_setting ( 'classdex_options', 'classdex_detail_shortcode' );
	register_setting ( 'classdex_options', 'classdex_detail1_shortcode' );
	register_setting ( 'classdex_options', 'classdex_detail2_shortcode' );
	register_setting ( 'classdex_options', 'classdex_detail3_shortcode' );
	register_setting ( 'classdex_options', 'classdex_account_types' );
	register_setting ( 'classdex_options', 'classdex_discount_member' );
	register_setting ( 'classdex_options', 'classdex_discount_senior' );
	register_setting ( 'classdex_options', 'classdex_discount_student' );
}
add_action ( 'admin_init', 'classdex_init' );

// Create Admin Dashboard Widgets
function classdex_today_dashboard_widget_function() {
	$today = today ();
	$classes = get_table_name ( "classes" );
	
	$query = "SELECT * FROM {$classes} ";
	$query .= "WHERE end_date >= '{$today}' AND start_date <= '{$today}' ";
	$query .= "AND DAYOFWEEK(start_date) = DAYOFWEEK('{$today}') ";
	$query .= "ORDER BY title ASC";
	
	display_classes_for_widget ( $query );
}
function classdex_today_add_dashboard_widgets() {
	wp_add_dashboard_widget ( 'classdex_today_dashboard_widget', "ClassDex: Today's Classes", 'classdex_today_dashboard_widget_function' );
}
add_action ( 'wp_dashboard_setup', 'classdex_today_add_dashboard_widgets' );
function classdex_search_dashboard_widget_function() {
	?>
<form action="admin.php?page=classdex_search" method="post">
			<?php wp_nonce_field( 'classdex_search','classdex_search_nonce' ); ?>
			<input type="text" name="keyword" value="" id="keyword"
		placeholder="Search Students"> <input type="submit" name="submit"
		id="submit" value="Search" class="btn">
</form>
<?php
}
function classdex_search_add_dashboard_widgets() {
	wp_add_dashboard_widget ( 'classdex_search_dashboard_widget', "ClassDex: Search Students", 'classdex_search_dashboard_widget_function' );
}
add_action ( 'wp_dashboard_setup', 'classdex_search_add_dashboard_widgets' );

// Create plugin pages
function classdex_home_page() {
	require_once (CLASSDEX_PATH . "/includes/functions.php");
	include (CLASSDEX_PATH . "/includes/header.php");
	?>
<legend>Today's Classes</legend>
<?php
	$today = today ();
	$classes = get_table_name ( "classes" );
	
	$query = "SELECT * FROM {$classes} ";
	$query .= "WHERE end_date >= '{$today}' AND start_date <= '{$today}' ";
	$query .= "AND DAYOFWEEK(start_date) = DAYOFWEEK('{$today}') ";
	$query .= "ORDER BY title ASC";
	
	display_classes ( $query );
	
	include (CLASSDEX_PATH . "/includes/footer.php");
}
function classdex_browse_page() {
	include (CLASSDEX_PATH . "/browse.php");
}
function classdex_classes_page() {
	include (CLASSDEX_PATH . "/classes.php");
}
function classdex_clear_database_page() {
	include (CLASSDEX_PATH . "/clear_database.php");
}
function classdex_clear_table_page() {
	include (CLASSDEX_PATH . "/clear_table.php");
}
function classdex_clone_class_page() {
	include (CLASSDEX_PATH . "/clone_class.php");
}
function classdex_create_cart66_product_page() {
	include (CLASSDEX_PATH . "/create_cart66_product.php");
}
function classdex_create_class_page() {
	include (CLASSDEX_PATH . "/create_class.php");
}
function classdex_create_customer_page() {
	include (CLASSDEX_PATH . "/create_customer.php");
}
function classdex_delete_table_item_page() {
	include (CLASSDEX_PATH . "/delete_table_item.php");
}
function classdex_edit_class_page() {
	include (CLASSDEX_PATH . "/edit_class.php");
}
function classdex_edit_customer_page() {
	include (CLASSDEX_PATH . "/edit_customer.php");
}
function classdex_expired_page() {
	include (CLASSDEX_PATH . "/expired.php");
}
function classdex_export_mailing_list_page() {
	include (CLASSDEX_PATH . "/export_mailing_list.php");
}
function classdex_export_table_page() {
	include (CLASSDEX_PATH . "/export_table.php");
}
function classdex_full_payment_page() {
	include (CLASSDEX_PATH . "/full_payment.php");
}
function classdex_import_export_page() {
	include (CLASSDEX_PATH . "/import_export.php");
}
function classdex_import_table_page() {
	include (CLASSDEX_PATH . "/import_table.php");
}
function classdex_mailing_list_page() {
	include (CLASSDEX_PATH . "/mailing_list.php");
}
function classdex_mark_not_paid_page() {
	include (CLASSDEX_PATH . "/mark_not_paid.php");
}
function classdex_new_class_page() {
	include (CLASSDEX_PATH . "/new_class.php");
}
function classdex_new_customer_page() {
	include (CLASSDEX_PATH . "/new_customer.php");
}
function classdex_non_class_payment_page() {
	include (CLASSDEX_PATH . "/non_class_payment.php");
}
function classdex_partial_payment_page() {
	include (CLASSDEX_PATH . "/partial_payment.php");
}
function classdex_payment_page() {
	include (CLASSDEX_PATH . "/payment.php");
}
function classdex_print_email_z_out_page() {
	include (CLASSDEX_PATH . "/print_email_z_out.php");
}
function classdex_print_sign_up_list_page() {
	include (CLASSDEX_PATH . "/print_sign_up_list.php");
}
function classdex_print_student_list_page() {
	include (CLASSDEX_PATH . "/print_student_list.php");
}
function classdex_print_unpaid_page() {
	include (CLASSDEX_PATH . "/print_unpaid.php");
}
function classdex_print_waiver_page() {
	include (CLASSDEX_PATH . "/print_waiver.php");
}
function classdex_registration_page() {
	include (CLASSDEX_PATH . "/registration.php");
}
function classdex_search_page() {
	include (CLASSDEX_PATH . "/search.php");
}
function classdex_settings_page() {
	include (CLASSDEX_PATH . "/settings.php");
}
function classdex_student_list_page() {
	include (CLASSDEX_PATH . "/student_list.php");
}
function classdex_unpaid_page() {
	include (CLASSDEX_PATH . "/unpaid.php");
}
function classdex_zout_page() {
	include (CLASSDEX_PATH . "/z_out_classes.php");
}
function classdex_plugin_menu() {
	$my_pages [] = add_menu_page ( 'ClassDex', 'ClassDex', 'read', 'classdex_home' );
	$my_pages [] = add_submenu_page ( 'classdex_home', 'ClassDex', 'ClassDex', 'read', 'classdex_home', 'classdex_home_page' );
	$my_pages [] = add_submenu_page ( null, 'Browse', 'Browse', 'read', 'classdex_browse', 'classdex_browse_page' );
	$my_pages [] = add_submenu_page ( null, 'Classes', 'Classes', 'read', 'classdex_classes', 'classdex_classes_page' );
	$my_pages [] = add_submenu_page ( null, 'Clear Database', 'Clear Database', 'manage_options', 'classdex_clear_database', 'classdex_clear_database_page' );
	$my_pages [] = add_submenu_page ( null, 'Clear Table', 'Clear Table', 'manage_options', 'classdex_clear_table', 'classdex_clear_table_page' );
	$my_pages [] = add_submenu_page ( null, 'Clone Class', 'Clone Class', 'manage_options', 'classdex_clone_class', 'classdex_clone_class_page' );
	$my_pages [] = add_submenu_page ( null, 'Create Cart66 Product', 'Create Cart66 Product', 'manage_options', 'classdex_create_cart66_product', 'classdex_create_cart66_product_page' );
	$my_pages [] = add_submenu_page ( null, 'Create Class', 'Create Class', 'manage_options', 'classdex_create_class', 'classdex_create_class_page' );
	$my_pages [] = add_submenu_page ( null, 'Create Customer', 'Create Customer', 'read', 'classdex_create_customer', 'classdex_create_customer_page' );
	$my_pages [] = add_submenu_page ( null, 'Delete Table Item', 'Delete Table Item', 'read', 'classdex_delete_table_item', 'classdex_delete_table_item_page' );
	$my_pages [] = add_submenu_page ( null, 'Edit Class', 'Edit Class', 'manage_options', 'classdex_edit_class', 'classdex_edit_class_page' );
	$my_pages [] = add_submenu_page ( null, 'Edit Customer', 'Edit Customer', 'read', 'classdex_edit_customer', 'classdex_edit_customer_page' );
	$my_pages [] = add_submenu_page ( null, 'Expired Members', 'Expired Members', 'manage_options', 'classdex_expired', 'classdex_expired_page' );
	$my_pages [] = add_submenu_page ( null, 'Export Mailing List', 'Export Mailing List', 'manage_options', 'classdex_export_mailing_list', 'classdex_export_mailing_list_page' );
	$my_pages [] = add_submenu_page ( null, 'Export Table', 'Export Table', 'manage_options', 'classdex_export_table', 'classdex_export_table_page' );
	$my_pages [] = add_submenu_page ( null, 'Full Payment', 'Full Payment', 'read', 'classdex_full_payment', 'classdex_full_payment_page' );
	$my_pages [] = add_submenu_page ( null, 'Import/Export', 'Import/Export', 'manage_options', 'classdex_import_export', 'classdex_import_export_page' );
	$my_pages [] = add_submenu_page ( null, 'Import Table', 'Import Table', 'manage_options', 'classdex_import_table', 'classdex_import_table_page' );
	$my_pages [] = add_submenu_page ( null, 'Mailing List', 'Mailing List', 'manage_options', 'classdex_mailing_list', 'classdex_mailing_list_page' );
	$my_pages [] = add_submenu_page ( null, 'Mark Not Paid', 'Mark Not Paid', 'read', 'classdex_mark_not_paid', 'classdex_mark_not_paid_page' );
	$my_pages [] = add_submenu_page ( 'classdex_home', 'Add Classes', 'Admin', 'manage_options', 'classdex_new_class', 'classdex_new_class_page' );
	$my_pages [] = add_submenu_page ( null, 'New Customer', 'New Customer', 'read', 'classdex_new_customer', 'classdex_new_customer_page' );
	$my_pages [] = add_submenu_page ( null, 'Non Class Payment', 'Non Class Payment', 'read', 'classdex_non_class_payment', 'classdex_non_class_payment_page' );
	$my_pages [] = add_submenu_page ( null, 'Partial Payment', 'Partial Payment', 'read', 'classdex_partial_payment', 'classdex_partial_payment_page' );
	$my_pages [] = add_submenu_page ( null, 'Payment', 'Payment', 'read', 'classdex_payment', 'classdex_payment_page' );
	$my_pages [] = add_submenu_page ( null, 'Print/Email Z-Out', 'Print/Email Z-Out', 'manage_options', 'classdex_print_email_z_out', 'classdex_print_email_z_out_page' );
	$my_pages [] = add_submenu_page ( null, 'Print Sign Up List', 'Print Sign Up List', 'read', 'classdex_print_sign_up_list', 'classdex_print_sign_up_list_page' );
	$my_pages [] = add_submenu_page ( null, 'Print Student List', 'Print Student List', 'read', 'classdex_print_student_list', 'classdex_print_student_list_page' );
	$my_pages [] = add_submenu_page ( null, 'Print Unpaid', 'Print Unpaid', 'manage_options', 'classdex_print_unpaid', 'classdex_print_unpaid_page' );
	$my_pages [] = add_submenu_page ( null, 'Print Waiver', 'Print Waiver', 'read', 'classdex_print_waiver', 'classdex_print_waiver_page' );
	$my_pages [] = add_submenu_page ( null, 'Registration', 'Registration', 'read', 'classdex_registration', 'classdex_registration_page' );
	$my_pages [] = add_submenu_page ( null, 'Search', 'Search', 'read', 'classdex_search', 'classdex_search_page' );
	$my_pages [] = add_submenu_page ( 'classdex_home', 'Settings', 'Settings', 'manage_options', 'classdex_settings', 'classdex_settings_page' );
	$my_pages [] = add_submenu_page ( null, 'Student List', 'Student List', 'read', 'classdex_student_list', 'classdex_student_list_page' );
	$my_pages [] = add_submenu_page ( null, 'Unpaid', 'Unpaid', 'manage_options', 'classdex_unpaid', 'classdex_unpaid_page' );
	$my_pages [] = add_submenu_page ( null, 'Z-Out Classes', 'Z-Out Classes', 'manage_options', 'classdex_zout', 'classdex_zout_page' );
	
	foreach ( $my_pages as $my_page ) {
		add_action ( 'load-' . $my_page, 'load_admin_custom_css' );
	}
}
add_action ( 'admin_menu', 'classdex_plugin_menu' );

// Add custom CSS to plugin pages
function load_custom_classdex_style() {
	wp_register_style ( 'custom_wp_admin_css1', CLASSDEX_URL . '/css/bootstrap.css', false, '2.2.2' );
	wp_enqueue_style ( 'custom_wp_admin_css1' );
	wp_register_style ( 'custom_wp_admin_css2', CLASSDEX_URL . '/css/bootstrap-responsive.css', false, '2.2.2' );
	wp_enqueue_style ( 'custom_wp_admin_css2' );
	wp_register_style ( 'custom_wp_admin_css3', CLASSDEX_URL . '/css/jquery.dataTables.css', false, '1.0.0' );
	wp_enqueue_style ( 'custom_wp_admin_css3' );
	wp_register_script ( 'datatables_script', CLASSDEX_URL . '/js/jquery.dataTables.min.js' );
	wp_enqueue_script ( 'datatables_script' );
	wp_register_script ( 'bootstrap_script', CLASSDEX_URL . '/js/bootstrap.min.js' );
	wp_enqueue_script ( 'bootstrap_script' );
	wp_register_script ( 'jquery_script', '//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js' );
	wp_enqueue_script ( 'jquery_script' );
}
function load_admin_custom_css() {
	add_action ( 'admin_enqueue_scripts', 'load_custom_classdex_style' );
}
function classdex_enqueue_style() {
	wp_register_style ( 'classdex_public_css', CLASSDEX_URL . '/css/classdex.css' );
	wp_enqueue_style ( 'classdex_public_css' );
}
add_action ( 'wp_enqueue_scripts', 'classdex_enqueue_style' );
?>