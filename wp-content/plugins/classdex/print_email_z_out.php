<?php check_admin_referer( 'classdex_print_email_z_out' );  ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php
if (! isset ( $_GET ['date'] ) || ! isset ( $_GET ['time'] ) || ! isset ( $_GET ['group_type'] )) {
	// If no customer is selected, escape.
	redirect_to ( "z_out_classes.php" );
} else {
	$start_date = $_GET ['date'];
	$start_time = $_GET ['time'];
	$group_type = $_GET ['group_type'];
}
?>
<?php include("includes/header_print.php"); ?>
<?php
if (isset ( $_GET ['date'] ) && isset ( $_GET ['time'] ) && isset ( $_GET ['group_type'] )) {
	$start_date_time = date ( "Y-m-d H:i:s", strtotime ( $start_date . " " . $start_time ) ); // For MySQL format should be: YYYY-MM-DD HH:MM:SS
	$z_out = z_out_classes ( $group_type, $start_date_time );
	$now = timestamp_now ();
	
	$message = "<html><head><title>Z Out for Classes</title></head><body><strong>Z-Out for all " . $group_type . " payments from " . $start_date_time . " until " . $now . ":</strong><br><br>";
	
	$total = 0;
	
	if ($z_out == NULL) {
		$message .= "<em>No payments were found under these conditions.</em><br><br>";
	} else {
		$message .= "<table border=\"1\" cellpadding=\"7\">";
		$message .= "<tr><td><strong>Type</strong></td><td><strong>Total</strong></td></tr>";
		foreach ( $z_out as $row ) {
			$message .= "<tr><td>";
			$message .= get_acct_type ( $row [0] );
			$message .= "</td><td>{$row[1]}</td></tr>";
			$total += $row [1];
		}
		$message .= "<tr><td><strong>TOTAL:</strong></td><td><strong>\${$total}</strong></td></tr>";
		$message .= "</table></body></html>";
		echo $message;
		
		if (get_option ( 'classdex_zout_email' ) != FALSE) { // If there is an email address saved in settings, then send.
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'To: ' . esc_attr ( get_option ( 'classdex_zout_email' ) ) . "\r\n";
			$headers .= 'From: ' . esc_attr ( get_option ( 'classdex_zout_email' ) ) . "\r\n";
			mail ( esc_attr ( get_option ( 'classdex_zout_email' ) ), "Z Out for Classes", $message, $headers );
		}
	}
}
?>
<?php require(CLASSDEX_PATH . "/includes/footer.php"); ?>