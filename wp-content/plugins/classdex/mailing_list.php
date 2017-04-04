<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php include(CLASSDEX_PATH . "/includes/header.php"); ?>

<!--Admin Navigation-->
<?php	display_admin_navigation("mailing"); ?>

<?php
date_default_timezone_set ( get_option ( 'timezone_string' ) );
$date = date ( "Y-m-d", strtotime ( "-3 years" ) );
?>

<legend>Export Mailing List CSV</legend>
Select earliest included active date:
<br>
(Automatically selects 3 years ago)
<br>
<br>
<form
	action="admin.php?page=classdex_export_mailing_list&amp;noheader=true"
	method="POST">
    <?php wp_nonce_field( 'classdex_export_mailing_list','classdex_export_mailing_list_nonce' ); ?>
    <input id="date" name="date" type="date"
		value="<?php echo $date; ?>" required="required">

	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Export Mailing List</button>
	</div>
</form>

<div class="alert alert-info">
	<strong>Note:</strong> All text to uppercase. Ordered by zip. 
	Excluding "Green" customers. Most recent activity date is set to 3 years ago by default, 
	but another starting date can be selected.
<?php
$zip_start = get_option ( 'classdex_zip_start' );
$zip_stop = get_option ( 'classdex_zip_stop' );

if ($zip_start == $zip_stop) {
	echo "</div><div class=\"alert alert-error\">Zip code range is not set. All zips will be included. 
			To edit this setting visit the <a href=\"admin.php?page=classdex_settings\">ClassDex settings</a> page.</div>";
} else {
	echo "Zip code range is " . $zip_start . "-" . $zip_stop . ". To edit this setting visit the ClassDex settings page.</div>";
}
?>

	
<?php require(CLASSDEX_PATH . "/includes/footer.php"); ?>