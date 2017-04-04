<?php global $wpdb; ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php include(CLASSDEX_PATH . "/includes/header.php"); ?>
<div class="modal" id="pleaseWaitDialog" data-backdrop="static"
	data-keyboard="false">
	<div class="modal-header">
		<h1>Loading...</h1>
	</div>
	<div class="modal-body">
		<div class="progress progress-striped active">
			<div class="bar" style="width: 100%;"></div>
		</div>
	</div>
</div>
<h2>Browse Students</h2>

<div class="pagination">
	<ul>
		<?php
		echo "<li";
		if (! isset ( $_GET ['initial'] )) {
			echo " class=\"active\"";
		}
		echo "><a href=\"admin.php?page=classdex_browse\">All</a></li>";
		?>
	</ul>
	&nbsp;&nbsp;
	<ul> 
<?php
$letters = array (
		'A',
		'B',
		'C',
		'D',
		'E',
		'F',
		'G',
		'H',
		'I',
		'J',
		'K',
		'L',
		'M',
		'N',
		'O',
		'P',
		'Q',
		'R',
		'S',
		'T',
		'U',
		'V',
		'W',
		'X',
		'Y',
		'Z' 
);

$item = "";

foreach ( $letters as $letter ) {
	echo "<li";
	if (strtolower ( $letter ) == strtolower ( $_GET ['initial'] )) {
		echo " class=\"active\"";
	}
	echo "><a href=\"admin.php?page=classdex_browse&initial={$letter}\">{$letter}</a></li>";
}
?>
	</ul>
</div>

<?php
$customers = get_table_name ( "customers" );

if (isset ( $_GET ['initial'] )) {
	?>
<table class="table table-striped" id="data_table_simple">
	<thead>
		<tr>
			<td><strong>First</strong></td>
			<td><strong>Last</strong></td>
			<td><strong>Phone</strong></td>
			<td><strong>Address</strong></td>
			<td><strong>City, State</strong></td>
			<td><strong>Zip</strong></td>
			<td><strong>Action</strong></td>
		</tr>
	</thead>
	<tbody>
<?php
	$initial = $wpdb->escape ( $_GET ['initial'] );
	$query = "
            SELECT * FROM {$customers} 
            WHERE last_name LIKE '{$initial}%' 
            ORDER BY last_name, first_name";
	
	$result_set = $wpdb->get_results ( $query, ARRAY_A );
	
	if ($result_set != NULL) {
		foreach ( $result_set as $row ) {
			if (strtolower ( $row ['last_name'] [0] ) == strtolower ( $_GET ['initial'] )) {
				echo "<tr><td>{$row['first_name']}</td>";
				echo "<td>{$row['last_name']}<a style=\"display: block; position: relative; top: -70px; visibility: hidden;\" name=\"" . urlencode ( strtolower ( $row ['last_name'] ) ) . "\"></a></td>";
				echo "<td>{$row['phone']}</td>";
				echo "<td>{$row['address']}</td>";
				echo "<td>{$row['city']} {$row['state']}</td>";
				if ($row ['zip'] == 0) {
					echo "<td></td>";
				} else {
					echo "<td>{$row['zip']}</td>";
				}
				echo "<td><a href=\"admin.php?page=classdex_edit_customer&amp;cust_id={$row['cust_id']}\"><i class=\"icon-pencil\"></i> Edit</a>&nbsp;&nbsp;
							<a href=\"admin.php?page=classdex_registration&amp;cust_id={$row['cust_id']}\"><i class=\"icon-ok\"></i> Register</a>&nbsp;&nbsp;  
							<a href=\"admin.php?page=classdex_payment&amp;cust_id={$row['cust_id']}\"><i class=\"icon-shopping-cart\"></i> Pay</a>
							</td></tr>";
			}
		}
	}
} else {
	?>
<table class="table table-striped" id="data_table">
			<thead>
				<tr>
					<td><strong>First</strong></td>
					<td><strong>Last</strong></td>
					<td><strong>Phone</strong></td>
					<td><strong>Address</strong></td>
					<td><strong>City, State</strong></td>
					<td><strong>Zip</strong></td>
					<td><strong>Action</strong></td>
				</tr>
			</thead>
			<tbody>
<?php
	$query = "
            SELECT * FROM {$customers} 
            ORDER BY last_name, first_name";
	
	$result_set = $wpdb->get_results ( $query, ARRAY_A );
	
	if ($result_set != NULL) {
		foreach ( $result_set as $row ) {
			echo "<tr><td>{$row['first_name']}</td>";
			echo "<td>{$row['last_name']}<a style=\"display: block; position: relative; top: -70px; visibility: hidden;\" name=\"" . urlencode ( strtolower ( $row ['last_name'] ) ) . "\"></a></td>";
			echo "<td>{$row['phone']}</td>";
			echo "<td>{$row['address']}</td>";
			echo "<td>{$row['city']} {$row['state']}</td>";
			if ($row ['zip'] == 0) {
				echo "<td></td>";
			} else {
				echo "<td>{$row['zip']}</td>";
			}
			echo "<td><a href=\"admin.php?page=classdex_edit_customer&amp;cust_id={$row['cust_id']}\"><i class=\"icon-pencil\"></i> Edit</a>&nbsp;&nbsp;
						<a href=\"admin.php?page=classdex_registration&amp;cust_id={$row['cust_id']}\"><i class=\"icon-ok\"></i> Register</a>&nbsp;&nbsp;  
						<a href=\"admin.php?page=classdex_payment&amp;cust_id={$row['cust_id']}\"><i class=\"icon-shopping-cart\"></i> Pay</a>
						</td></tr>";
		}
	}
}

?>

</tbody>
		</table>
		<br>
		<div class="pagination">
			<ul>
		<?php
		echo "<li";
		if (! isset ( $_GET ['initial'] )) {
			echo " class=\"active\"";
		}
		echo "><a href=\"admin.php?page=classdex_browse\">All</a></li>";
		?>
	</ul>
			&nbsp;&nbsp;
			<ul>
		<?php
		$letters = array (
				'A',
				'B',
				'C',
				'D',
				'E',
				'F',
				'G',
				'H',
				'I',
				'J',
				'K',
				'L',
				'M',
				'N',
				'O',
				'P',
				'Q',
				'R',
				'S',
				'T',
				'U',
				'V',
				'W',
				'X',
				'Y',
				'Z' 
		);
		
		$item = "";
		
		foreach ( $letters as $letter ) {
			echo "<li";
			if (strtolower ( $letter ) == strtolower ( $_GET ['initial'] )) {
				echo " class=\"active\"";
			}
			echo "><a href=\"admin.php?page=classdex_browse&amp;initial={$letter}\">{$letter}</a></li>";
		}
		?>
	</ul>
		</div>
		<a class="btn" href="admin.php?page=classdex_new_customer">Add New
			Student</a>
		<script>	
	jQuery(window).load(function () {
		jQuery('#pleaseWaitDialog').hide();
	});
</script>	
<?php require("includes/footer.php"); ?>