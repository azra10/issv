<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php include(CLASSDEX_PATH . "/includes/header.php"); ?>
<?php
if (! isset ( $_GET ['cust_id'] ) || intval ( $_GET ['cust_id'] ) == 0) {
	// If no customer is selected, escape.
	redirect_to ( "admin.php?page=classdex_home" );
} else {
	$cust_id = $_GET ['cust_id'];
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

echo "<form class=\"form-inline\" action=\"admin.php?page=classdex_full_payment&amp;noheader=true&amp;cust_id=" . urlencode ( $customer ['cust_id'] ) . "\" method=\"post\">";
wp_nonce_field ( 'classdex_full_payment', 'classdex_full_payment_nonce' );
?>
<?php
$total_payment = display_classes_for_payment ( $cust_id ); // This function displays the classes and then returns a value.

$class_credit = $customer ['class_credit'];

if ($total_payment !== NULL) {
	if ($class_credit > 0) { // If there is class credit to be used
		echo "<div class=\"alert alert-info\">
				<strong>Class Credit:</strong> <em>This student has \${$class_credit} of class credit. This credit will be put towards the payment.</em>
			</div>";
		if ($class_credit > $total_payment) {
			$total_payment = 0;
		} else {
			$total_payment -= $class_credit;
		}
	}
	echo "<div class=\"form-actions\">
				<h3 id=\"full-payment-total\" style=\"display: inline; line-height: 0px; position: relative; top: 5px;\">Total: \$";
	echo $total_payment;
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
				  <input type=\"hidden\" name=\"class_credit\" value=\"{$customer['class_credit']}\"/>
				  <button type=\"submit\" name=\"submit\" class=\"btn btn-primary\">Pay Total</button>
				  <a href=\"admin.php?page=classdex_partial_payment&amp;cust_id={$customer['cust_id']}\" class=\"btn pull-right\">Partial Payment</a>
				</div>
			</form>";
}
?>
<script type="text/javascript">
	function getTotal()
	{
         var sum= 0;
         var classCredit = <?php echo $class_credit; ?>;
         var paymentRows = document.getElementById('payment-rows').getElementsByTagName('tr');
         for (var i=1;i<=paymentRows.length;i++) {
             var payAmount = document.getElementById('full_payment'+i);
             if(payAmount.checked)
             {
             	if (isNaN(+payAmount.value)) { sum = 'N/A'; }
             	else { sum+= +payAmount.value; }
             }
         }
         if(classCredit > sum) { 
         	sum = 0; 
         } else {
         	sum -= classCredit;
         }
         document.getElementById('full-payment-total').innerHTML = 'Total: $' + sum + '&nbsp;&nbsp;&nbsp;';
    }      
</script>
<?php require(CLASSDEX_PATH . "/includes/footer.php"); ?>
