<?php global $wpdb; ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php include(CLASSDEX_PATH . "/includes/header.php"); ?>

<!--Admin Navigation-->
<?php	display_admin_navigation("expired"); ?>

<?php
$customers = get_table_name ( "customers" );
$today = today ();

$query = "SELECT * ";
$query .= "FROM {$customers} ";
$query .= "WHERE {$customers}.member_expiration < '{$today}' ";
$query .= "AND {$customers}.member_expiration != '0000-00-00' ";
$query .= "ORDER BY {$customers}.last_name, {$customers}.first_name";

$result_set = $wpdb->get_results ( $query, ARRAY_A );

if (count ( $result_set ) == 0) {
	echo "<br><em>No results were found.</em>";
} else {
	echo "<table class=\"table table-striped\">
			  <tr><td></td><td>Name</td><td>Phone</td><td>Email</td><td>Member Expiration Date</td><td>Actions</td></tr>";
	foreach ( $result_set as $row ) {
		echo "<tr><td><a title=\"Browse students by last name.\" href=\"admin.php?page=classdex_browse&amp;initial=" . urlencode ( strtolower ( $row ['last_name'] [0] ) ) . "#" . urlencode ( strtolower ( $row ['last_name'] ) ) . "\"><i class=\"icon-list\"></i></a></td>";
		echo "<td>{$row['first_name']} {$row['last_name']}</td>";
		echo "<td>{$row['phone']}</td>";
		echo "<td>{$row['email']}</td><td>";
		echo date ( "m/d/Y", strtotime ( $row ['member_expiration'] ) );
		echo "</td>";
		echo "<td><a href=\"admin.php?page=classdex_edit_customer&amp;cust_id={$row['cust_id']}\"><i class=\"icon-pencil\"></i> Edit</a></td>";
		echo "</tr>";
	}
	echo "</table>";
}
?>
<?php include(CLASSDEX_PATH . "/includes/footer.php"); ?>