<?php include(CLASSDEX_PATH . "/includes/header.php"); ?>


<!--Admin Navigation-->
<?php	display_admin_navigation("add"); ?>


<legend>Add a Class</legend>

<form action="admin.php?page=classdex_create_class&amp;noheader=true"
	method="post">		
	<?php wp_nonce_field( 'classdex_create_class','classdex_create_class_nonce' );  ?>

	<?php
	$edit = false;
	include (CLASSDEX_PATH . "/includes/class_form.php");
	?>				
					
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Add Class</button>
		<a href="admin.php?page=classdex_new_class" class="btn">Cancel</a>
	</div>
</form>


<?php require(CLASSDEX_PATH . "/includes/footer.php"); ?>