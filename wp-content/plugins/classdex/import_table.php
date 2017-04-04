<?php check_admin_referer( 'classdex_import_table','classdex_import_table_nonce' ); ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php
if (isset ( $_FILES ["filename"] ['tmp_name'] ) && isset ( $_POST ["table_name"] )) {
	$file = $_FILES ["filename"] ['tmp_name'];
	$table_name = $_POST ["table_name"];
	
	if ($table_name == "customers") {
		$table = get_table_name ( "customers" );
	} elseif ($table_name == "classes") {
		$table = get_table_name ( "classes" );
	} elseif ($table_name == "registrations") {
		$table = get_table_name ( "registrations" );
	} elseif ($table_name == "payments") {
		$table = get_table_name ( "payments" );
	} else {
		// No proper table was selected, so escape.
		redirect_to ( "admin.php?page=classdex_home" );
	}
	
	if ($_FILES ["filename"] ["error"] > 0 || $_FILES ["filename"] ["type"] != "text/csv") {
		echo "Error uploading or wrong file type. File must be a CSV.";
		$check = false;
	} else {
		csv_file_to_mysql_table ( $_FILES ["filename"] ['tmp_name'], $table );
		$check = true;
	}
}
$redirect_to = admin_url ( "admin.php?page=classdex_import_export&check={$check}" );
wp_safe_redirect ( $redirect_to );
exit ();
?>