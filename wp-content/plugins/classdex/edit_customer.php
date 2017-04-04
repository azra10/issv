<?php global $wpdb; ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php
if (! isset ( $_GET ['cust_id'] ) || intval ( $_GET ['cust_id'] ) == 0) {
	// If no customer is selected, escape.
	redirect_to ( "admin.php?page=classdex_home" );
} else {
	$cust_id = $_GET ['cust_id'];
}
if (get_customer_by_id ( $cust_id ) == NULL) {
	// If customer selected does not exist, escape.
	redirect_to ( "admin.php?page=classdex_home" );
}
if (isset ( $_POST ['submit'] )) {
	check_admin_referer ( 'classdex_edit_customer', 'classdex_edit_customer_nonce' );
	
	$errors = array ();
	
	// Form Validation (this should be a function - duplicate on create customer)
	$required_fields = array (
			'first_name',
			'last_name' 
	);
	foreach ( $required_fields as $fieldname ) {
		if (! isset ( $_POST [$fieldname] ) || empty ( $_POST [$fieldname] )) {
			$errors [] = $fieldname;
		}
	}
	if (empty ( $errors )) {
		$fields_with_lengths = array (
				'first_name' => 35,
				'last_name' => 35 
		);
		foreach ( $fields_with_lengths as $fieldname => $maxlength ) {
			if (strlen ( trim ( $_POST [$fieldname] ) ) > $maxlength) {
				$errors [] = $fieldname;
			}
		}
	}
	if (! empty ( $errors )) {
		redirect_to ( "admin.php?page=classdex_new_customer" );
	}
	// End Form Validation
	
	if (empty ( $errors )) {
		// Perform Update
		$cust_id = $_GET ['cust_id'];
		$first_name = stripslashes_deep ( $_POST ['first_name'] );
		$last_name = stripslashes_deep ( $_POST ['last_name'] );
		$address = stripslashes_deep ( $_POST ['address'] );
		$city = stripslashes_deep ( $_POST ['city'] );
		$state = $_POST ['state'];
		$zip = $_POST ['zip'];
		$phone = $_POST ['phone'];
		$email = strtolower ( $_POST ['email'] );
		$active_date = $_POST ['active_date'];
		$signed_waiver = $_POST ['signed_waiver'];
		$class_credit = $_POST ['class_credit'];
		if (isset ( $_POST ['green'] )) {
			$green = 1;
		} else {
			$green = 0;
		}
		if (isset ( $_POST ['senior'] )) {
			$senior = 1;
		} else {
			$senior = 0;
		}
		if (isset ( $_POST ['student'] )) {
			$student = 1;
		} else {
			$student = 0;
		}
		if (isset ( $_POST ['member'] )) {
			$member = 1;
		} else {
			$member = 0;
		}
		$member_expiration = $_POST ['member_expiration'];
		$notes = $_POST ['notes'];
		
		// Add email to Mailchimp
		$customer = get_customer_by_id ( $cust_id );
		if ($customer ['email'] != $email) {
			if ($email != NULL) {
				add_to_mailchimp ( $email, $first_name, $last_name );
			}
		}
		
		$customers = get_table_name ( "customers" );
		
		$result = $wpdb->update ( $customers, array (
				'first_name' => $first_name,
				'last_name' => $last_name,
				'address' => $address,
				'city' => $city,
				'state' => $state,
				'zip' => $zip,
				'phone' => $phone,
				'email' => $email,
				'active_date' => $active_date,
				'signed_waiver' => $signed_waiver,
				'class_credit' => $class_credit,
				'green' => $green,
				'senior' => $senior,
				'student' => $student,
				'member' => $member,
				'member_expiration' => $member_expiration,
				'notes' => $notes 
		), array (
				'cust_id' => $cust_id 
		), array (
				'%s', // first_name
				'%s', // last_name
				'%s', // address
				'%s', // city
				'%s', // state
				'%d', // zip
				'%s', // phone
				'%s', // email
				'%s', // active_date
				'%s', // signed_waiver
				'%d', // class_credit
				'%d', // green
				'%d', // senior
				'%d', // student
				'%d', // member
				'%s', // member_expiration
				'%s' 
		) // notes
, array (
				'%d' 
		) ) // cust_id
;
		
		if ($result == 1) {
			// Success
			$message = "<div class=\"alert alert-success\">The customer was successfully updated.</div>";
		} else {
			// Failed
			$message = "<div class=\"alert alert-error\">The customer update failed, or no changes were made.</div>";
			$message .= "<br />" . $wpdb->print_error ();
		}
	} else {
		// Errors occurred
		$message = "There were " . count ( $errors ) . " errors in the form.";
	}
} // end: if (isset($_POST['submit']))
?>

<?php $customer = get_customer_by_id($cust_id); ?>
<?php include("includes/header.php"); ?>

<h2>Edit Student: <?php echo $customer['first_name'] . " " . $customer['last_name']; ?>&nbsp;
		<a class="btn"
		href="admin.php?page=classdex_registration&amp;cust_id=<?php echo $customer['cust_id']; ?>"><i
		class="icon-ok"></i> Registration</a>&nbsp; <a class="btn"
		href="admin.php?page=classdex_payment&amp;cust_id=<?php echo $customer['cust_id']; ?>"><i
		class="icon-shopping-cart"></i> Payment</a>
</h2>
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
	action="admin.php?page=classdex_edit_customer&amp;cust_id=<?php echo urlencode($customer['cust_id']); ?>"
	method="post">
	<?php wp_nonce_field( 'classdex_edit_customer','classdex_edit_customer_nonce' );  ?>
	<?php
	$edit = true;
	include ("includes/customer_form.php");
	?>	

	<div class="form-actions">
		<input type="submit" name="submit" id="submit" value="Save Changes"
			class="btn btn-primary"> <a
			href="admin.php?page=classdex_edit_customer&amp;cust_id=<?php echo urlencode($customer['cust_id']); ?>"
			class="btn">Cancel</a>
	  <?php if ( current_user_can('manage_options') ) { ?>
	  	<a href="#deleteCust" role="button"
			class="btn btn-small btn-danger pull-right" data-toggle="modal">Delete
			Customer</a>
	  <?php }  ?> 
	</div>

</form>

<!-- Modal -->
<div id="deleteCust" class="modal hide fade" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
		<h3 id="myModalLabel">Delete Customer: <?php echo $customer['first_name'] . " " . $customer['last_name']; ?></h3>
	</div>
	<div class="modal-body">
		<div class="alert alert-block">
			<h4>Warning!</h4>
			This action cannot be undone.
		</div>
	</div>
	<div class="modal-footer">
		<form
			action="admin.php?page=classdex_delete_table_item&amp;noheader=true&amp;t=customers&i=<?php echo urlencode($customer['cust_id']); ?>"
			method="post">
		<?php wp_nonce_field('classdex_delete_table_item'); ?>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
			<input type="submit" name="submit" id="submit"
				value="Delete Customer" class="btn btn-danger">
		</form>
	</div>
</div>
<legend>Registration and Payment Information</legend>
<ul class="nav nav-tabs">
	<li class="active"><a href="#current_reg" data-toggle="tab">Current
			Registrations</a></li>
	<li><a href="#class_hist" data-toggle="tab">Class History</a></li>
	<li><a href="#pay_hist" data-toggle="tab">Payment History</a></li>
	<li><a href="#non_class_pay" data-toggle="tab">Non-Class Payment</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="current_reg">	
		<?php
		$current_classes = get_current_classes ( $cust_id );
		
		if (count ( $current_classes ) == 0) {
			echo "<em>No current registrations.</em><br><br>";
			echo "<a class=\"btn\" href=\"admin.php?page=classdex_registration&amp;cust_id={$customer['cust_id']}\"><i class=\"icon-ok\"></i> Register for Classes</a>";
		} else {
			echo "<ul>";
			foreach ( $current_classes as $row ) {
				echo "<li>";
				if (current_user_can ( 'manage_options' ))
					echo "<a href=\"admin.php?page=classdex_edit_class&amp;class_id=" . urlencode ( $row ['class_id'] ) . "\">";
				echo "{$row['title']} - {$row['teacher']}";
				if (current_user_can ( 'manage_options' ))
					echo "</a>";
				echo " (" . date ( "M j, Y", strtotime ( $row ['start_date'] ) ) . ")";
				$payment = get_reg_deposit ( $row ['reg_id'] );
				$delete_registration_url = "admin.php?page=classdex_delete_table_item&amp;noheader=true&amp;t=registrations&amp;i=" . urlencode ( $row ['reg_id'] );
				echo " 	<a href=\"" . wp_nonce_url ( $delete_registration_url, 'classdex_delete_table_item' ) . "\" onclick=\"return confirm('Are you sure? Unregistering from {$row['title']} cannot be undone. Give \${$payment} as class credit or refund.')\" ><i class=\"icon-remove\"></i></a>";
				echo "</li>";
			}
			echo "</ul>";
			echo "<a class=\"btn\" href=\"admin.php?page=classdex_registration&amp;cust_id={$customer['cust_id']}\"><i class=\"icon-ok\"></i> Register for more classes</a>";
		}
		?>	
  </div>
	<div class="tab-pane" id="class_hist">
		<ul>
		<?php
		$class_history = get_class_history ( $cust_id );
		
		if (count ( $class_history ) == 0) {
			echo "<em>No class history.</em><br>";
		} else {
			foreach ( $class_history as $row ) {
				echo "<li>";
				if (current_user_can ( 'manage_options' ))
					echo "<a href=\"admin.php?page=classdex_edit_class&amp;class_id=" . urlencode ( $row ['class_id'] ) . "\">";
				echo "{$row['title']} - {$row['teacher']}";
				if (current_user_can ( 'manage_options' ))
					echo "</a>";
				echo " (" . date ( "M j, Y", strtotime ( $row ['start_date'] ) ) . ")";
				if (is_paid ( $row ['reg_id'] )) {
					echo " - PAID";
					if (get_reg_balance ( $row ['reg_id'] ) > 0) {
						echo " 	<a href=\"admin.php?page=classdex_mark_not_paid&amp;noheader=true&amp;reg_id=" . urlencode ( $row ['reg_id'] ) . "\" onclick=\"return confirm('Are you sure? Marking as not paid cannot be undone.')\" ><i class=\"icon-remove\"></i></a>";
					}
				}
				echo "</li>";
			}
		}
		?>	
	</ul>
	</div>
	<div class="tab-pane" id="pay_hist">
  	<?php display_payment_history($cust_id); ?>
  </div>
	<div class="tab-pane" id="non_class_pay">
		<div class="alert alert-info">
			<strong>Note:</strong> <em>This function is primarially for non-class
				payments such as rent and donations. All classes should be paid
				through the standard payment method.</em>
		</div>

		<form class="form-inline"
			action="admin.php?page=classdex_non_class_payment&amp;noheader=true"
			method="post">
		<?php wp_nonce_field( 'classdex_non_class_payment','classdex_non_class_payment_nonce' ); ?>
		<div class="form-actions">
				Amount: <input class="input-small" type="number" name="amount"
					value="" id="amount" style="height: 30px;" required>
				&nbsp;&nbsp;&nbsp; <select class="input-large" name="pay_type"
					required>
					<option value="">(Select Payment Type)</option>
					<option value="1">Paypal</option>
					<option value="2">Check</option>
					<option value="3">Cash</option>
					<option value="4">Credit Card</option>
					<option value="5">Gift Certificate</option>
					<option value="6">Class Credit</option>
				</select> &nbsp;&nbsp;&nbsp; <select id="acct-type"
					class="input-large" name="acct_type" required>
			  <?php echo get_acct_type_option_list(); ?>
			</select> &nbsp;&nbsp;&nbsp; <input type="hidden" name="cust_id"
					value="<?php echo $cust_id; ?>">
				<button type="submit" name="submit" class="btn btn-primary">Make
					Payment</button>
			</div>
		</form>
	</div>
</div>

<?php require(CLASSDEX_PATH . "/includes/footer.php"); ?>