<?php check_admin_referer( 'classdex_delete_table_item'); ?>
<?php global $wpdb; ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php
if (! isset ( $_GET ['t'] ) && ! isset ( $_GET ['i'] ) || intval ( $_GET ['i'] ) == 0) {
	redirect_to ( "admin.php?page=classdex_home" );
} else {
	$id_num = $_GET ['i'];
	$table_name = $_GET ['t'];
	
	if ($table_name == "customers") {
		$index_name = "cust_id";
		$table = get_table_name ( "customers" );
		$redirect_link = "admin.php?page=classdex_home";
		
		// Delete corresponding Payments when deleting a Customer
		$payment_table = get_table_name ( "payments" );
		$payment_query = $wpdb->prepare ( "DELETE FROM {$payment_table} WHERE {$index_name} = %d", $id_num );
		$wpdb->query ( $payment_query );
		
		// Delete corresponding Registrations when deleting a Registration
		$reg_table = get_table_name ( "registrations" );
		$reg_query = $wpdb->prepare ( "DELETE FROM {$reg_table} WHERE {$index_name} = %d", $id_num );
		$wpdb->query ( $reg_query );
	} elseif ($table_name == "classes") {
		$index_name = "class_id";
		$table = get_table_name ( "classes" );
		$redirect_link = "admin.php?page=classdex_home";
	} elseif ($table_name == "registrations") {
		$index_name = "reg_id";
		$table = get_table_name ( "registrations" );
		$redirect_link = "{$_SERVER['HTTP_REFERER']}";
		
		// Delete corresponding Payments when deleting a Registration
		$payment_table = get_table_name ( "payments" );
		$payment_query = $wpdb->prepare ( "DELETE FROM {$payment_table} WHERE {$index_name} = %d", $id_num );
		
		$wpdb->query ( $payment_query );
	} elseif ($table_name == "payments") {
		$index_name = "pay_id";
		$table = get_table_name ( "payments" );
		$redirect_link = "{$_SERVER['HTTP_REFERER']}";
		
		// If corresponding registration exists, mark as not paid
		$reg_id = get_payment_reg_id ( $id_num );
		if ($reg_id != 0) {
			mark_as_not_paid ( $reg_id );
		}
	} else {
		// No proper table was selected, so escape.
		redirect_to ( "admin.php?page=classdex_home" );
	}
}

$query = $wpdb->prepare ( "DELETE FROM {$table} WHERE {$index_name} = %d", $id_num );

$result = $wpdb->query ( $query );

if ($result != 0) {
	redirect_to ( $redirect_link );
} else {
	// Deletion Failed
	echo "<p>Item deletion failed.</p>";
	echo "<p>" . $wpdb->print_error () . "</p>";
	echo "<a href=\"admin.php?page=classdex_home\">Return to Main Page</a>";
}

?>