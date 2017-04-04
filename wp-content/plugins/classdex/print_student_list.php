<?php check_admin_referer( 'classdex_print_student_list' );  ?>
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
<h2>Class List: <?php echo "{$class['title']} - {$class['teacher']} (" . date("M j, Y", strtotime($class['start_date'])) . ")"; ?></h2>

<?php
if ($class ['end_date'] == $class ['start_date']) {
	$recurring = false;
} else {
	$recurring = true;
}
echo date ( "l", strtotime ( $class ['start_date'] ) );
if ($recurring) {
	echo "s";
}
echo ", ";
if (date ( "m", strtotime ( $class ['start_date'] ) ) == date ( "m", strtotime ( $class ['end_date'] ) )) {
	if ($recurring) {
		echo date ( "F j", strtotime ( $class ['start_date'] ) ) . "-" . date ( "j", strtotime ( $class ['end_date'] ) ) . ", ";
	} else {
		echo date ( "F j", strtotime ( $class ['start_date'] ) ) . ", ";
	}
} else {
	echo date ( "F j", strtotime ( $class ['start_date'] ) ) . "-" . date ( "F j", strtotime ( $class ['end_date'] ) ) . ", ";
}
echo $class ['time'] . "</em><br><br>";

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
			  <tr><td>Name</td><td>Phone</td></tr>";
	foreach ( $result_set as $row ) {
		echo "<td>{$row['first_name']} {$row['last_name']}</td>";
		echo "<td>{$row['phone']}</td>";
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