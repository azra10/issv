<?php global $wpdb; ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php include(CLASSDEX_PATH . "/includes/header.php"); ?>

<!--Admin Navigation-->
<?php	display_admin_navigation("unpaid"); ?>

<?php
$customers = get_table_name ( "customers" );
$classes = get_table_name ( "classes" );
$registrations = get_table_name ( "registrations" );
$today = today ();

$query = "SELECT * ";
$query .= "FROM {$customers}, {$registrations}, {$classes} ";
$query .= "WHERE ({$registrations}.cust_id = {$customers}.cust_id ";
$query .= "AND {$registrations}.class_id = {$classes}.class_id ";
$query .= "AND {$classes}.start_date < '{$today}' ";
$query .= "AND {$registrations}.paid = 0) ";
$query .= "ORDER BY {$customers}.last_name, {$customers}.first_name";

$result_set = $wpdb->get_results ( $query, ARRAY_A );

if (count ( $result_set ) == 0) {
	echo "<br><em>No results were found.</em>";
} else {
	echo "<table class=\"table table-striped\">
			  <tr><td></td><td>Name</td><td>Phone</td><td>Class</td><td>Date</td><td>Action</td></tr>";
	foreach ( $result_set as $row ) {
		echo "<tr><td><a title=\"Browse students by last name.\" href=\"admin.php?page=classdex_browse&amp;initial=" . urlencode ( strtolower ( $row ['last_name'] [0] ) ) . "#" . urlencode ( strtolower ( $row ['last_name'] ) ) . "\"><i class=\"icon-list\"></i></a></td>";
		echo "<td>{$row['first_name']} {$row['last_name']}</td>";
		echo "<td>{$row['phone']}</td>";
		echo "<td>{$row['title']} - {$row['teacher']}</td><td>";
		if ($row ['start_date'] == $row ['end_date']) {
			echo date ( "D, M j", strtotime ( $row ['start_date'] ) );
		} else {
			echo date ( "D, M j", strtotime ( $row ['start_date'] ) ) . "-" . date ( "M j", strtotime ( $row ['end_date'] ) );
		}
		echo "</td>";
		
		$payment = get_reg_deposit ( $row ['reg_id'] );
		$delete_registration_url = "admin.php?page=classdex_delete_table_item&amp;noheader=true&amp;t=registrations&amp;i=" . urlencode ( $row ['reg_id'] );
		echo "<td><a href=\"admin.php?page=classdex_edit_customer&amp;cust_id={$row['cust_id']}\"><i class=\"icon-pencil\"></i> Edit</a>&nbsp;&nbsp; 
			<a href=\"admin.php?page=classdex_payment&amp;cust_id={$row['cust_id']}\"><i class=\"icon-shopping-cart\"></i> Pay</a>&nbsp;&nbsp;
			<a href=\"" . wp_nonce_url ( $delete_registration_url, 'classdex_delete_table_item' ) . "\" onclick=\"return confirm('Are you sure? Unregistering from this class cannot be undone. Give \${$payment} as class credit or refund.')\" ><i class=\"icon-remove\"></i> Remove</a>
			</td></tr>";
	}
	echo "</table>";
	echo "<a class=\"btn\" target=\"_blank\" href=\"admin.php?page=classdex_print_unpaid&noheader=true\"><i class=\"icon-print\"></i> Print</a>";
}
?>
<?php include(CLASSDEX_PATH . "/includes/footer.php"); ?>