<?php global $wpdb; ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php include(CLASSDEX_PATH . "/includes/header_print.php"); ?>

<h2>Students with Pending Payments</h2>

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

if ($result_set == NULL) {
	echo "<em>No students have pending payments!</em><br>";
} else {
	echo "<table border=\"1\" cellpadding=\"7\">
			  <tr><td style=\"width:150px;\">Name</td><td style=\"width:110px;\">Phone</td><td>Class</td><td style=\"width:150px;\">Date</td></tr>";
	foreach ( $result_set as $row ) {
		echo "<tr>";
		echo "<td>{$row['first_name']} {$row['last_name']}</td>";
		echo "<td>{$row['phone']}</td>";
		echo "<td>{$row['title']} - {$row['teacher']}</td><td>";
		if ($row ['start_date'] == $row ['end_date']) {
			echo date ( "D, M j", strtotime ( $row ['start_date'] ) );
		} else {
			echo date ( "D, M j", strtotime ( $row ['start_date'] ) ) . "-" . date ( "M j", strtotime ( $row ['end_date'] ) );
		}
		echo "</td></tr>";
	}
	echo "</table>";
}
?>

</div>
</div>

</div>
<!--/.fluid-container-->
</body>
</html>