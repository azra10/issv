<?php check_admin_referer( 'classdex_full_payment','classdex_full_payment_nonce' ); ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php
if (! isset ( $_POST ['submit'] ) || empty ( $_POST ['cust_id'] ) || empty ( $_POST ['full_payment_reg_ids'] )) {
	// If form was not submitted, escape.
	redirect_to ( "{$_SERVER['HTTP_REFERER']}" );
} else {
	$cust_id = $_POST ['cust_id'];
	$class_credit = $_POST ['class_credit'];
	$pay_type = $_POST ['pay_type'];
	
	update_active_date ( $cust_id );
	
	$timestamp_now = timestamp_now ();
	
	foreach ( $_POST ['full_payment_reg_ids'] as $reg_id => $balance ) {
		// For each payment: (1) Create new payment row -- unique for each registration and payment type,
		// (2) If successful, Mark as paid
		$deposit = get_reg_deposit ( $reg_id );
		$acct_type = get_acct_type_by_reg_id ( $reg_id );
		
		if ($class_credit > 0) { // If there is class credit to be used
			if ($class_credit >= $balance) { // If there is enough class credit to pay for the entire class
				$class_credit -= $balance;
				update_class_credit ( $cust_id, $class_credit );
				create_payment ( $cust_id, $reg_id, $balance, 6, $timestamp_now, $acct_type ); // Payment type is set to 6 -- class credit
			} else { // If there is only enough class credit to partially pay for the class
				create_payment ( $cust_id, $reg_id, $class_credit, 6, $timestamp_now, $acct_type ); // Payment type is set to 6 -- class credit
				create_payment ( $cust_id, $reg_id, $balance - $class_credit, $pay_type, $timestamp_now, $acct_type );
				$class_credit = 0;
				update_class_credit ( $cust_id, $class_credit );
			}
		} else { // If there is NOT any class credit to be used
			create_payment ( $cust_id, $reg_id, $balance, $pay_type, $timestamp_now, $acct_type );
		}
		
		mark_as_paid ( $reg_id );
	}
	
	redirect_to ( "{$_SERVER['HTTP_REFERER']}" . "&m=1" );
}
exit ();
?>