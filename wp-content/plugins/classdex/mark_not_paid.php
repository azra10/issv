<?php global $wpdb; ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php
if (! isset ( $_GET ['reg_id'] )) {
	redirect_to ( "admin.php?page=classdex_home" );
} else {
	$result = mark_as_not_paid ( $_GET ['reg_id'] ); // Returns true or false.
}

if ($result) {
	redirect_to ( "{$_SERVER['HTTP_REFERER']}" );
} else {
	// Deletion Failed
	echo "<p>Mark not paid failed.</p>";
	echo "<p>" . $wpdb->print_error () . "</p>";
	echo "<a href=\"admin.php?page=classdex_home\">Return to Main Page</a>";
}

?>