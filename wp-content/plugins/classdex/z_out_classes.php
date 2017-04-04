<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php
if (isset ( $_POST ['submit'] )) {
	if (! isset ( $_POST ['date'] ) || ! isset ( $_POST ['time'] ) || ! isset ( $_POST ['group_type'] )) {
		redirect_to ( "z_out_classes.php" );
	} else {
		$start_date = $_POST ['date'];
		$start_time = $_POST ['time'];
		$group_type = $_POST ['group_type'];
	}
} // end: if (isset($_POST['submit']))
?>
<?php include(CLASSDEX_PATH . "/includes/header.php"); ?>

<!--Admin Navigation-->
<?php	display_admin_navigation("zout"); ?>

<legend>Z-Out Class Income</legend>
<?php
if (isset ( $_POST ['submit'] ) && isset ( $_POST ['date'] ) && isset ( $_POST ['time'] ) && isset ( $_POST ['group_type'] )) {
	check_admin_referer ( 'classdex_zout', 'classdex_zout_nonce' );
	
	$start_date_time = date ( "Y-m-d H:i:s", strtotime ( $start_date . " " . $start_time ) ); // For MySQL format should be: YYYY-MM-DD HH:MM:SS
	$z_out = z_out_classes ( $group_type, $start_date_time );
	$now = timestamp_now ();
	
	echo "<strong>Z-Out for all " . $group_type . " payments from " . $start_date_time . " until " . $now . ".</strong><br><br>";
	
	// Update wordpress options
	if ($group_type == "register") {
		update_option ( 'classdex_zout_register_date', today () );
		update_option ( 'classdex_zout_register_time', time_now () );
	} elseif ($group_type == "paypal") {
		update_option ( 'classdex_zout_paypal_date', today () );
		update_option ( 'classdex_zout_paypal_time', time_now () );
	}
	
	$total = 0;
	
	if ($z_out == NULL) {
		echo "<em>No payments were found under these conditions.</em><br><br>";
	} else {
		echo "<table class=\"table table-striped\">";
		echo "<tr><td><strong>Type</strong></td><td><strong>Total</strong></td></tr>";
		foreach ( $z_out as $row ) {
			echo "<tr><td>";
			echo get_acct_type ( $row [0] );
			echo "</td><td>{$row[1]}</td></tr>";
			$total += $row [1];
		}
		echo "<tr><td><strong>TOTAL:</strong></td><td><strong>\${$total}</strong></td></tr>";
		echo "</table>";
		$print_zout_url = "admin.php?page=classdex_print_email_z_out&amp;noheader=true&amp;date=" . urlencode ( $start_date ) . "&amp;time=" . urlencode ( $start_time ) . "&amp;group_type=" . urlencode ( $group_type );
		echo "<a class=\"btn\" target=\"_blank\" href=\"" . wp_nonce_url ( $print_zout_url, 'classdex_print_email_z_out' ) . "\"><i class=\"icon-print\"></i> Print and Email Z-Out</a>";
	}
	echo "<hr>";
}

echo "<form class=\"form-inline\" action=\"admin.php?page=classdex_zout\" method=\"post\">";
wp_nonce_field ( 'classdex_zout', 'classdex_zout_nonce' );
echo "<div class=\"form-actions\">";
echo "<h3 style=\"display: inline; line-height: 0px; position: relative; top: 5px;\">Register</h3>&nbsp;&nbsp;&nbsp;&nbsp;
			Start Date: <input type=\"date\" class=\"input-medium\" name=\"date\" value=\"" . esc_attr ( get_option ( 'classdex_zout_register_date' ) ) . "\">
			&nbsp; Time: <input type=\"time\" class=\"input-medium\" name=\"time\" value=\"" . esc_attr ( get_option ( 'classdex_zout_register_time' ) ) . "\">
			<input type=\"hidden\" name=\"group_type\" value=\"register\"/>
			&nbsp;&nbsp;&nbsp;
			  <button type=\"submit\" name=\"submit\" class=\"btn btn-primary\">Z Out</button>
			</div>
		</form>";

echo "<form class=\"form-inline\" action=\"admin.php?page=classdex_zout\" method=\"post\">";
wp_nonce_field ( 'classdex_zout', 'classdex_zout_nonce' );
echo "<div class=\"form-actions\">";
echo "<h3 style=\"display: inline; line-height: 0px; position: relative; top: 5px;\">Paypal</h3>&nbsp;&nbsp;&nbsp;&nbsp;  
			Start Date: <input type=\"date\" class=\"input-medium\" name=\"date\" value=\"" . esc_attr ( get_option ( 'classdex_zout_paypal_date' ) ) . "\">
			&nbsp; Time: <input type=\"time\" class=\"input-medium\" name=\"time\" value=\"" . esc_attr ( get_option ( 'classdex_zout_paypal_time' ) ) . "\">
			<input type=\"hidden\" name=\"group_type\" value=\"paypal\"/>
			&nbsp;&nbsp;&nbsp;
			  <button type=\"submit\" name=\"submit\" class=\"btn btn-primary\">Z Out</button>
			</div>
		</form>";
?>
<div class="alert alert-info">
	<strong>Note:</strong> The Z-Out is preformed from the date entered to
	the present moment. The date/time is automatically set to the last time
	that you preformed a Z-Out. 'Paypal' refers to all of the payments that
	came through paypal. 'Register' refers to all credit card, cash, check,
	and gift certificate payments.
</div>
<?php if(get_option('classdex_zout_email') == FALSE) { ?>
<div class="alert alert-danger">
	<strong>Warning:</strong> No email address has been set to send the
	Z-Out to. Visit the ClassDex Settings page to add an email address.
</div>
<?php
}
require (CLASSDEX_PATH . "/includes/footer.php");
?>