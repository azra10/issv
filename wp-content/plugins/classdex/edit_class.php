<?php global $wpdb; ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php
if (! isset ( $_GET ['class_id'] ) || intval ( $_GET ['class_id'] ) == 0) {
	// If no class is selected, escape.
	redirect_to ( "admin.php?page=classdex_home" );
} else {
	$class_id = $_GET ['class_id'];
}
if (get_class_by_id ( $class_id ) == NULL) {
	// If class selected does not exist, escape.
	redirect_to ( "admin.php?page=classdex_home" );
}
if (isset ( $_POST ['submit'] )) {
	check_admin_referer ( 'classdex_edit_class', 'classdex_edit_class_nonce' );
	
	$errors = array ();
	
	// Form Validation (this should be a function - duplicate on create class)
	$required_fields = array (
			'public_id',
			'title',
			'teacher' 
	);
	foreach ( $required_fields as $fieldname ) {
		if (! isset ( $_POST [$fieldname] ) || empty ( $_POST [$fieldname] )) {
			$errors [] = $fieldname;
		}
	}
	if (! empty ( $errors )) {
		redirect_to ( "admin.php?page=classdex_home" );
	} else {
		// Perform Update
		$public_id = $_POST ['public_id'];
		$title = stripslashes_deep ( $_POST ['title'] );
		$teacher = $_POST ['teacher'];
		$start_date = $_POST ['start_date'];
		$end_date = $_POST ['end_date'];
		$time = $_POST ['time'];
		$description = stripslashes_deep ( $_POST ['description'] );
		$fee = $_POST ['fee'];
		$max_size = $_POST ['max_size'];
		$custom_html = stripslashes_deep ( $_POST ['custom_html'] );
		$image = $_POST ['image'];
		$acct_type = $_POST ['acct_type'];
		if (isset ( $_POST ['discounts'] )) {
			$discounts = 1;
		} else {
			$discounts = 0;
		}
		if (isset ( $_POST ['yoga_class'] )) {
			$yoga_class = 1;
		} else {
			$yoga_class = 0;
		}
		if (isset ( $_POST ['seminar'] )) {
			$seminar = 1;
		} else {
			$seminar = 0;
		}
		if (isset ( $_POST ['meditation'] )) {
			$meditation = 1;
		} else {
			$meditation = 0;
		}
		if (isset ( $_POST ['wellness'] )) {
			$wellness = 1;
		} else {
			$wellness = 0;
		}
		if (isset ( $_POST ['philosophy'] )) {
			$philosophy = 1;
		} else {
			$philosophy = 0;
		}
		if (isset ( $_POST ['canceled'] )) {
			$canceled = 1;
		} else {
			$canceled = 0;
		}
		if (isset ( $_POST ['image_justified'] )) {
			$image_justified = 1;
		} else {
			$image_justified = 0;
		}
		
		$classes = get_table_name ( "classes" );
		
		$result = $wpdb->update ( $classes, array (
				'public_id' => $public_id,
				'title' => $title,
				'teacher' => $teacher,
				'start_date' => $start_date,
				'end_date' => $end_date,
				'time' => $time,
				'description' => $description,
				'fee' => $fee,
				'max_size' => $max_size,
				'image' => $image,
				'custom_html' => $custom_html,
				'discounts' => $discounts,
				'yoga_class' => $yoga_class,
				'seminar' => $seminar,
				'meditation' => $meditation,
				'wellness' => $wellness,
				'philosophy' => $philosophy,
				'canceled' => $canceled,
				'image_justified' => $image_justified,
				'acct_type' => $acct_type 
		), array (
				'class_id' => $class_id 
		), array (
				'%s', // public_id
				'%s', // title
				'%s', // teacher
				'%s', // start_date
				'%s', // end_date
				'%s', // time
				'%s', // description
				'%d', // fee
				'%d', // max_size
				'%s', // image
				'%s', // custom_html
				'%d', // discounts
				'%d', // yoga_class
				'%d', // seminar
				'%d', // meditation
				'%d', // wellness
				'%d', // philosophy
				'%d', // canceled
				'%d', // image_justified
				'%s' 
		) // acct_type
, array (
				'%d' 
		) ) // class_id
;
		
		// Edit Cart66 Product
		if ($discounts) {
			$options = get_discount_options ( $fee );
		}
		
		if (class_exists ( 'Cart66Product' )) {
			$product = new Cart66Product ();
			$product->loadByItemNumber ( $class_id );
			$_POST ['cart66_product_nonce'] = wp_create_nonce ( 'cart66_product_nonce' );
			if ($discounts) {
				$product->setData ( array (
						'name' => $title . " - " . $teacher . " (" . $public_id . ")",
						'item_number' => $class_id,
						'price' => 0,
						'shipped' => 0,
						'options_1' => $options,
						'custom' => 'single',
						'custom_desc' => 'Registering multiple students? List names' 
				) );
			} else {
				$product->setData ( array (
						'name' => $title . " - " . $teacher . " (" . $public_id . ")",
						'item_number' => $class_id,
						'price' => $fee,
						'shipped' => 0,
						'options_1' => '',
						'custom' => 'single',
						'custom_desc' => 'Registering multiple students? List names' 
				) );
			}
			$product->save ();
			$product->clear ();
		}
		
		if ($result == 1) {
			// Success
			$message = "<div class=\"alert alert-success\">The class was successfully updated.</div>";
		} else {
			// Failed
			$message = "<div class=\"alert alert-error\">The class update failed, or no changes were made.</div>";
			$message .= "<br />" . $wpdb->print_error ();
		}
	}
} // end: if (isset($_POST['submit']))
?>

<?php $class = get_class_by_id($class_id); ?>
<?php include("includes/header.php"); ?>

<h2>Edit Class: <?php echo $class['title'] . " - " . $class['teacher']; ?></h2>
<?php

if (! empty ( $message )) {
	echo "<p>" . $message . "</p>";
}
?>
<?php
// output a list of the fields that had errors
if (! empty ( $errors )) {
	echo "<p class=\"errors\">";
	echo "Please review the following fields:<br />";
	foreach ( $errors as $error ) {
		echo " - " . $error . "<br />";
	}
	echo "</p>";
}
?>
<form
	action="admin.php?page=classdex_edit_class&amp;class_id=<?php echo urlencode($class['class_id']); ?>"
	method="post">
	<?php wp_nonce_field( 'classdex_edit_class','classdex_edit_class_nonce' );  ?>
	<?php
	$edit = true;
	include ("includes/class_form.php");
	?>	
				
	<div class="form-actions">
		<input type="submit" name="submit" id="submit" value="Save Changes"
			class="btn btn-primary"> <a
			href="admin.php?page=classdex_edit_class&amp;class_id=<?php echo urlencode($class['class_id']); ?>"
			class="btn">Cancel</a>
	  <?php if ( current_user_can('manage_options') ) { ?>
	  	<a href="#deleteClass" role="button"
			class="btn btn-small btn-danger pull-right" data-toggle="modal">Delete
			Class</a>
      <?php }  ?> 
	</div>

</form>


<!-- Modal -->
<div id="deleteClass" class="modal hide fade" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
		<h3 id="myModalLabel">Delete Class: <?php echo $class['title'] . " with " . $class['teacher']; ?></h3>
	</div>
	<div class="modal-body">
		<div class="alert alert-block">
			<h4>Warning!</h4>
			This action cannot be undone.
		</div>
	</div>
	<div class="modal-footer">
  	<?php $form_action_url = "admin.php?page=classdex_delete_table_item&amp;noheader=true&amp;t=classes&amp;i=" . urlencode($class['class_id']); ?>	
	<form action="<?php echo $form_action_url; ?>" method="post">
		<?php wp_nonce_field('classdex_delete_table_item'); ?>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
			<input type="submit" name="submit" id="submit" value="Delete Class"
				class="btn btn-danger">
		</form>
	</div>
</div>

<legend>Class List</legend>

<?php
display_student_list ( $class_id );
?>


<?php require(CLASSDEX_PATH . "/includes/footer.php"); ?>