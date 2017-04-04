<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php include(CLASSDEX_PATH . "/includes/header.php"); ?>
<?php
if (! isset ( $_GET ['cust_id'] ) || intval ( $_GET ['cust_id'] ) == 0) {
	// If no customer is selected, escape.
	redirect_to ( "admin.php?page=classdex_home" );
} else {
	$cust_id = $_GET ['cust_id'];
}

if (isset ( $_POST ['submit'] ) && (! empty ( $_POST ['partial_pays'] ) || ! empty ( $_POST ['paid_reg_ids'] ))) {
	check_admin_referer ( 'classdex_partial_payment', 'classdex_partial_payment_nonce' );
	
	$pay_type = $_POST ['pay_type'];
	/*
	 * Payment Types Key
	 * -----------------
	 * 1. Paypal
	 * 2. Check
	 * 3. Cash
	 * 4. Credit Card
	 * 5. Gift Certificate
	 * 6. Class Credit
	 *
	 */
	
	update_active_date ( $cust_id );
	
	$timestamp_now = timestamp_now ();
	
	// Get all of the partial payments, reg_id and amount, and create
	foreach ( $_POST ['partial_pays'] as $reg_id => $amount ) {
		if ($amount != 0) {
			$acct_type = get_acct_type_by_reg_id ( $reg_id );
			create_payment ( $cust_id, $reg_id, $amount, $pay_type, $timestamp_now, $acct_type );
			if (get_reg_balance ( $reg_id ) <= 0) {
				mark_as_paid ( $reg_id );
			}
		}
	}
	
	// Mark the classes paid that were selected paid
	if (! empty ( $_POST ['paid_reg_ids'] )) {
		foreach ( $_POST ['paid_reg_ids'] as $reg_id ) {
			mark_as_paid ( $reg_id );
		}
	}
}
?>
<?php
$customer = get_customer_by_id ( $cust_id );

echo "<h2>Payment: " . $customer ['first_name'] . " " . $customer ['last_name'] . " (";
if ($customer ['senior'] == 1) {
	echo "Senior";
} elseif ($customer ['student'] == 1) {
	echo "Student";
} elseif ($customer ['member'] == 1) {
	echo "Member";
} else {
	echo "Regular";
}
echo ")";
?>
&nbsp;
<a class="btn"
	href="admin.php?page=classdex_edit_customer&amp;cust_id=<?php echo $customer['cust_id']; ?>"><i
	class="icon-pencil"></i> Edit</a>
</h2>
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
echo "<br><br>";

if (isset ( $_GET ['m'] ) && intval ( $_GET ['m'] ) != 0) {
	if ($_GET ['m'] == 1) {
		echo "<div class=\"alert alert-success\">Payment was successful.</div>";
	} elseif ($_GET ['m'] == 2) {
		echo "<div class=\"alert alert-success\">Partial payment was successful.</div>";
	}
}

echo "<form class=\"form-inline\" action=\"admin.php?page=classdex_partial_payment&amp;m=2&amp;cust_id=" . urlencode ( $customer ['cust_id'] ) . "\" method=\"post\">";
wp_nonce_field ( 'classdex_partial_payment', 'classdex_partial_payment_nonce' );
?>
<?php
$total_payment = display_classes_for_payment ( $cust_id, true ); // This function displays the classes and then returns a value.
?>
<script type="text/javascript">
	function getSum()
	{
         var sum= 0;
         var paymentRows = document.getElementById('payment-rows').getElementsByTagName('tr');
         for (var i=1;i<=paymentRows.length;i++) {
             var payAmount = document.getElementById('partial_pay'+i);
             if (isNaN(+payAmount.value))
                 sum = 'N/A';
             else
                 sum+= +payAmount.value;
         }
         document.getElementById('partial-total').innerHTML = 'Partial Payment Total: $' + sum + '&nbsp;&nbsp;&nbsp;';
    }      
</script>
<?php
if ($total_payment != 0) {
	echo "<div class=\"form-actions\">
				<h3 id=\"partial-total\" style=\"display: inline; line-height: 0px; position: relative; top: 5px;\">Partial Payment Total: \$0";
	echo "&nbsp;&nbsp;&nbsp;</h3>
				<select class=\"input-large\" name=\"pay_type\" required>
				  <option value =\"\">(Select Payment Type)</option>	
				  <option value =\"1\">Paypal</option>
				  <option value =\"2\">Check</option>
				  <option value =\"3\">Cash</option>
				  <option value =\"4\">Credit Card</option>
				  <option value =\"5\">Gift Certificate</option>
				  <option value =\"6\">Class Credit</option>
				</select>	
				&nbsp;&nbsp;&nbsp;
				  <input type=\"hidden\" name=\"cust_id\" value=\"{$cust_id}\"/>
				  <button type=\"submit\" name=\"submit\" class=\"btn btn-primary\">Make Partial Payment</button>
				</div>
			</form>";
}
?>
<?php require(CLASSDEX_PATH . "/includes/footer.php"); ?>
