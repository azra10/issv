<?php check_admin_referer( 'classdex_print_waiver' );  ?>
<?php global $wpdb; ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php include(CLASSDEX_PATH . "/includes/header_print.php"); ?>
<?php
if (! isset ( $_GET ['class_id'] ) || intval ( $_GET ['class_id'] ) == 0) {
	// If no class is selected, escape.
	redirect_to ( "admin.php?page=classdex_home" );
} else {
	$class_id = $_GET ['class_id'];
}

$class = get_class_by_id ( $class_id );
?>
<h2>Class Waiver -- Please Read Carefully Before Signing</h2>
<p><?php echo esc_attr( get_option('classdex_waiver') ); ?></p>

<?php
$customers = get_table_name ( "customers" );
$registrations = get_table_name ( "registrations" );
$last_year = date ( 'Y-m-d', strtotime ( '-1 year', strtotime ( "now" ) ) );

$query = "SELECT * ";
$query .= "FROM {$customers}, {$registrations} ";
$query .= "WHERE ({$registrations}.class_id = %d 
		AND {$registrations}.cust_id = {$customers}.cust_id 
		AND {$customers}.signed_waiver < '{$last_year}') ";
$query .= "ORDER BY {$customers}.last_name ASC";

$query_prep = $wpdb->prepare ( $query, $class_id );

$result_set = $wpdb->get_results ( $query_prep, ARRAY_A );

if ($result_set == NULL) {
	echo "<em>No waiver needed for this class. All students have already signed.</em><br>";
} else {
	echo "<table border=\"1\" cellpadding=\"7\">
			  <tr><td>Name</td><td style=\"width:350px;\">Signature</td><td style=\"width:100px;\">Date</td></tr>";
	foreach ( $result_set as $row ) {
		echo "<td>{$row['first_name']} {$row['last_name']}</td>";
		echo "<td></td><td></td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "<p><em>If you have already signed this waiver, your name will not appear on the list and you do not need to sign.</em></p>";
}
?>

</div>
</div>

</div>
<!--/.fluid-container-->
</body>
</html>