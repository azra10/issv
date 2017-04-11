<?php
if (isset ( $_POST ['_wpnonce-iss-delete-parent-form-page'] )) 
{
		check_admin_referer ( 'iss-delete-parent-form-page', '_wpnonce-iss-delete-parent-form-page' );

		if (! isset ( $_POST ['ParentID'] ) || empty ( $_POST ['ParentID'] ) || (intval ( $_POST ['ParentID'] ) == 0)) {

			echo '<div class="text-danger"><p><strong>Unknown User.</strong></p></div>';
			return;
		}
		$parentid = iss_sanitize_input ( $_POST ['ParentID'] );

		$result = iss_delete_students_by_parentid($parentid);
		$result += iss_delete_parent_by_parentid ( $parentid );
		$result += iss_delete_changelog_by_parentid($parentid);
					

		if ($result>0) {
			echo '<div class="text-primary"><p><strong>Parent Record Deleted.</strong></p></div>';
			return;
		}
}
else if (! isset ( $_GET ['id'] ) || empty ( $_GET ['id'] ) || (intval ( $_GET ['id'] ) == 0)) {
	echo '<div class="text-danger"><p><strong>Unknown Parent.</strong></p></div>';
	return;
}

$id = iss_sanitize_input ( $_GET ['id'] );

// IF PARENT EXIST, PULL PARENT
$issparent = iss_get_parent_and_payment_by_id ( $id );
if ($issparent == NULL) {
	echo '<p>' . 'No users found.' . '</p>';
} else {
	$parentid = $issparent ['ParentID'];
	$regyear = $issparent ['RegistrationYear'];
	// IF PARENT EXISTS, PULL STUDENTS
	$issstudents = iss_get_students_by_parentid ( $issparent ['ParentID'], $regyear );
	$edit = false;

?>
<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
    <?php wp_nonce_field('iss-delete-parent-form-page', '_wpnonce-iss-delete-parent-form-page') ?>
	<input type="hidden" id="ParentID" name="ParentID"
		value="<?php echo $parentid; ?>" /> 
	<input
		type="hidden" id="RegistrationYear" name="RegistrationYear"
		value="<?php echo $regyear; ?>" /> 
	<div>
	<div class="row">
		<label class="col-md-12">Are you sure to delete this record?</label>
	</div>
	<div class="row">
		<div class="col-md-10">
			<ul class="list-inline">
				<li>
					<button type="submit" name="submit" value="delete" class="btn btn-danger">Delete Parent Record</button>
				</li>
			</ul>				
		</div>
	</div>
	</div>
</form>

<?php	
	include (ISS_PATH . "/includes/form_print.php");
}
?>
