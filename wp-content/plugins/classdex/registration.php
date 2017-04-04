<?php global $wpdb; ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php include(CLASSDEX_PATH . "/includes/header.php"); ?>
<?php
if (! isset ( $_GET ['cust_id'] ) || intval ( $_GET ['cust_id'] ) == 0) {
	// If no customer is selected, escape.
	redirect_to ( "admin.php?page=classdex_home" );
} else {
	$cust_id = $_GET ['cust_id'];
	$customer = get_customer_by_id ( $cust_id );
}

if (isset ( $_POST ['submit'] ) && ! empty ( $_POST ['class_ids'] )) {
	update_active_date ( $cust_id );
	
	// Get array of classes that customer is already registered for
	$existing_registrations = get_existing_registrations_by_cust ( $cust_id );
	$existing_classes_array = array ();
	
	if ($existing_registrations != NULL) {
		foreach ( $existing_registrations as $row ) {
			$existing_classes_array [] = $row ['class_id'];
		}
	}
	
	$registrations = get_table_name ( "registrations" );
	
	// Create new registrations for all of the classes that have been submited
	// avoiding classes that the customer is already registered for
	foreach ( $_POST ['class_ids'] as $class_id ) {
		if (! in_array ( $class_id, $existing_classes_array )) { // Check if cust is already registered before creating registration
			$class = get_class_by_id ( $class_id );
			$class_fee = $class ['fee'];
			if ($class_fee == 0) {
				$paid = 1; // Registering for a free class, so mark paid.
			} else {
				$paid = 0; // New registration with fee -- not yet paid.
			}
			
			$result = $wpdb->insert ( $registrations, array (
					'class_id' => $class_id,
					'cust_id' => $cust_id,
					'paid' => $paid 
			), array (
					'%d', // class_id
					'%d', // cust_id
					'%d' 
			) // paid
 );
			
			if ($result != false) {
				// Success
				$message = "<br>
								<div class=\"alert alert-success\">The registration was successful.&nbsp;&nbsp;&nbsp;   
								<a class=\"btn\" href=\"admin.php?page=classdex_payment&amp;cust_id={$customer['cust_id']}\"><i class=\"icon-shopping-cart\"></i> Pay for Classes</a>
								</div>";
			} else {
				// Failed
				$message = "<div class=\"alert alert-error\">The registration failed, or no changes were made.</div>";
				$message .= "<br />" . $wpdb->print_error ();
			}
		}
	} // end: foreach($_POST['class_ids'] as $class_id)
} // end: if (isset($_POST['submit']) && !empty($_POST['class_ids']))
?>
<h2>Registration: <?php echo"{$customer['first_name']} {$customer['last_name']}"; ?>&nbsp;
	<a class="btn"
		href="admin.php?page=classdex_edit_customer&amp;cust_id=<?php echo $customer['cust_id']; ?>"><i
		class="icon-pencil"></i> Edit</a>&nbsp; <a class="btn"
		href="admin.php?page=classdex_payment&amp;cust_id=<?php echo $customer['cust_id']; ?>"><i
		class="icon-shopping-cart"></i> Payment</a>
</h2>
<ul class="nav nav-tabs">
	<li class="active"><a href="#info" data-toggle="tab">Student
			Information</a></li>
	<li><a href="#current" data-toggle="tab">Current Registrations</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="info">
<?php
echo "<strong>Email:</strong> ";
if (empty ( $customer ['email'] )) {
	echo "(none) ";
} else {
	echo $customer ['email'] . " ";
}
echo "&nbsp;&nbsp;&nbsp; <strong>Phone:</strong> ";
if (empty ( $customer ['phone'] )) {
	echo "(none) ";
} else {
	echo $customer ['phone'] . " ";
}
echo "&nbsp;&nbsp;&nbsp; <strong>Address:</strong> ";
if (empty ( $customer ['address'] )) {
	echo "(none) ";
} else {
	echo $customer ['address'] . ", " . $customer ['city'] . ", " . $customer ['state'] . "  " . $customer ['zip'];
}
echo "<br>";
echo "<strong>Customer Type:</strong> ";
if ($customer ['member'] == 1) {
	echo "Member ";
}
if ($customer ['senior'] == 1) {
	echo "Senior ";
}
if ($customer ['student'] == 1) {
	echo "Student ";
}
if ($customer ['member'] == 0 && $customer ['senior'] == 0 && $customer ['student'] == 0) {
	echo "Regular ";
}
?>
  </div>
	<div class="tab-pane" id="current">
<?php
$current_classes = get_current_classes ( $cust_id );

if (count ( $current_classes ) != 0) {
	echo "<ul>";
	foreach ( $current_classes as $row ) {
		echo "<li>";
		if (current_user_can ( 'manage_options' )) {
			echo "<a href=\"admin.php?page=classdex_edit_class&amp;class_id=" . urlencode ( $row ['class_id'] ) . "\">{$row['title']} - {$row['teacher']}</a>" . " (" . date ( "M j, Y", strtotime ( $row ['start_date'] ) ) . ")";
		} else {
			echo "{$row['title']} - {$row['teacher']}" . " (" . date ( "M j, Y", strtotime ( $row ['start_date'] ) ) . ")";
		}
		$payment = get_reg_deposit ( $row ['reg_id'] );
		$delete_registration_url = "admin.php?page=classdex_delete_table_item&amp;noheader=true&amp;t=registrations&amp;i=" . urlencode ( $row ['reg_id'] );
		echo " 	<a href=\"" . wp_nonce_url ( $delete_registration_url, 'classdex_delete_table_item' ) . "\" onclick=\"return confirm('Are you sure? Unregistering from {$row['title']} cannot be undone. Give \${$payment} as class credit or refund.')\" ><i class=\"icon-remove\"></i></a>";
		echo "</li>";
	}
	echo "</ul>";
	echo "<a class=\"btn\" href=\"admin.php?page=classdex_payment&amp;cust_id={$customer['cust_id']}\"><i class=\"icon-shopping-cart\"></i> Pay for Classes</a>";
	echo "<hr>";
} else {
	echo "<i>No current registrations.</i>";
	echo "<hr>";
}

?>
  	</div>
</div>

<?php
if (! empty ( $message )) {
	echo $message;
}
echo "<form action=\"admin.php?page=classdex_registration&amp;cust_id=" . urlencode ( $customer ['cust_id'] ) . "\" method=\"post\">";
?>
<?php include("includes/class_pagination.php"); ?>
<?php display_classes($query,true); ?>

<?php require(CLASSDEX_PATH . "/includes/footer.php"); ?>