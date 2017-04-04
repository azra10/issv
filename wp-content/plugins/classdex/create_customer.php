<?php check_admin_referer( 'classdex_create_customer','classdex_create_customer_nonce' );  ?>
<?php global $wpdb; ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php
$errors = array ();

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

$first_name = stripslashes_deep ( $_POST ['first_name'] );
$last_name = stripslashes_deep ( $_POST ['last_name'] );
$address = stripslashes_deep ( $_POST ['address'] );
$city = stripslashes_deep ( $_POST ['city'] );
$state = $_POST ['state'];
$zip = $_POST ['zip'];
$phone = $_POST ['phone'];
$email = $_POST ['email'];
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

$customers = get_table_name ( "customers" );

$result = $wpdb->insert ( $customers, array (
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
 );

// Add email to Mailchimp
if ($email != NULL) {
	add_to_mailchimp ( $email, $first_name, $last_name );
}

$new_id = $wpdb->insert_id;

if ($result != false) {
	// Success!
	redirect_to ( "admin.php?page=classdex_edit_customer&cust_id={$new_id}" );
} else {
	// Display error message.
	echo "<p>Customer creation failed.</p>";
	echo "<p>" . $wpdb->print_error () . "</p>";
}
?>