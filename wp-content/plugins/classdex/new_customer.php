<?php include(CLASSDEX_PATH . "/includes/header.php"); ?>

<h2>Add Student</h2>

<form action="admin.php?page=classdex_create_customer&amp;noheader=true"
	method="post">
	<?php wp_nonce_field( 'classdex_create_customer','classdex_create_customer_nonce' );  ?>

<?php
$edit = false;
include (CLASSDEX_PATH . "/includes/customer_form.php");
?>	
						
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Add Student</button>
		<a href="admin.php?page=classdex_new_customer" class="btn">Cancel</a>
	</div>
</form>


<?php require(CLASSDEX_PATH . "/includes/footer.php"); ?>