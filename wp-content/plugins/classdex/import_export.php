<?php include(CLASSDEX_PATH . "/includes/header.php"); ?>

<!--Admin Navigation-->
<?php	display_admin_navigation("import"); ?>

<legend>Export Database Table to CSV</legend>
<form action="admin.php?page=classdex_export_table&amp;noheader=true"
	method="POST">
	<?php wp_nonce_field( 'classdex_export_table','classdex_export_table_nonce' ); ?>
	<select name="table_name" required="required">
		<option value="customers">Customers</option>
		<option value="classes">Classes</option>
		<option value="registrations">Registrations</option>
		<option value="payments">Payments</option>
	</select>

	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Export</button>
	</div>
</form>

<br>


<legend>Import Database Table from CSV</legend>

<?php

if (isset ( $_GET ['check'] )) {
	if ($_GET ['check'] == true) {
		// Success
		$message = "<div class=\"alert alert-success\">The import was successful.</div>";
	} else {
		// Failed
		$message = "<div class=\"alert alert-error\">The import failed.</div>";
	}
	echo $message;
}
?>

<form enctype="multipart/form-data"
	action="admin.php?page=classdex_import_table&amp;noheader=true"
	method="POST">
	<?php wp_nonce_field( 'classdex_import_table','classdex_import_table_nonce' ); ?>
	<select name="table_name" required="required">
		<option value="customers">Customers</option>
		<option value="classes">Classes</option>
		<option value="registrations">Registrations</option>
		<option value="payments">Payments</option>
	</select> <br> <input type="hidden" name="MAX_FILE_SIZE"
		value="10000000" /> <input id="filename" name="filename" type="file"
		accept="text/csv" required="required">

	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Import</button>
		<a class="btn" href="admin.php?page=classdex_import_export">Cancel</a>
	</div>
</form>

<div class="alert alert-info">
	<strong>Note:</strong> To replace customer database and preserve
	customer IDs, CSV must include a 'cust_id' column. To batch add new
	customers (they each will each get a new cust_id), do not include a
	'cust_id' column. In excel, format all date columns to be "yyyy-mm-dd".
</div>

<?php require(CLASSDEX_PATH . "/includes/footer.php"); ?>