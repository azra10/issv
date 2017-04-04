<?php check_admin_referer( 'classdex_print_sign_up_list' );  ?>
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
<h2>Sign Up for the Next Session: <?php echo "{$class['title']} - {$class['teacher']}"; ?></h2>
<p>Initial next to your name if you'd like to register for the next
	session.</p>

<?php
$customers = get_table_name ( "customers" );
$registrations = get_table_name ( "registrations" );

$query = "SELECT * ";
$query .= "FROM {$customers}, {$registrations} ";
$query .= "WHERE ({$registrations}.class_id = %d AND {$registrations}.cust_id = {$customers}.cust_id) ";
$query .= "ORDER BY {$customers}.last_name ASC";

$query_prep = $wpdb->prepare ( $query, $class_id );

$result_set = $wpdb->get_results ( $query_prep, ARRAY_A );

if ($result_set == NULL) {
	echo "<em>No students are registered for this class.</em><br>";
} else {
	echo "<table border=\"1\" cellpadding=\"7\">
			  <tr><td>Name</td><td style=\"width:100px;\">Initials</td></tr>";
	foreach ( $result_set as $row ) {
		echo "<td>{$row['first_name']} {$row['last_name']}</td>";
		echo "<td></td>";
		echo "</tr>";
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