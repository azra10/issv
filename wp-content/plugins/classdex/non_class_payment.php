<?php check_admin_referer( 'classdex_non_class_payment','classdex_non_class_payment_nonce' ); ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php
if (! isset ( $_POST ['submit'] ) || empty ( $_POST ['cust_id'] )) {
	// If form was not submitted, escape.
	redirect_to ( "{$_SERVER['HTTP_REFERER']}" );
} else {
	$cust_id = $_POST ['cust_id'];
	$pay_type = $_POST ['pay_type'];
	$acct_type = $_POST ['acct_type'];
	$amount = $_POST ['amount'];
	
	update_active_date ( $cust_id );
	
	$timestamp_now = timestamp_now ();
	
	create_payment ( $cust_id, 0, $amount, $pay_type, $timestamp_now, $acct_type );
	
	redirect_to ( "{$_SERVER['HTTP_REFERER']}" );
}
?>