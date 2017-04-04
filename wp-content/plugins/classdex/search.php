<?php global $wpdb; ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php include(CLASSDEX_PATH . "/includes/header.php"); ?>
<?php
if (isset ( $_POST ['submit'] )) {
	check_admin_referer ( 'classdex_search', 'classdex_search_nonce' );
	
	$keyword = $wpdb->escape ( $_POST ['keyword'] );
	
	if (strlen ( $keyword ) == 3) {
		$customers = get_table_name ( "customers" );
		
		$query = "
				SELECT * FROM {$customers} 
				WHERE (first_name LIKE '{$keyword}' OR last_name LIKE '{$keyword}') 
				ORDER BY last_name, first_name";
		
		$result_set = $wpdb->get_results ( $query, ARRAY_A );
		
		if (count ( $result_set ) == 0) {
			echo "<br><em>No results were found.</em><br> <a href=\"admin.php?page=classdex_browse&amp;initial=a\">Browse students by last name.</a><br>
					<br><a class=\"btn\" href=\"admin.php?page=classdex_new_customer\">Add New Student</a>";
		} else {
			echo "<table class=\"table table-striped\">
					  <tr><td></td><td>Name</td><td>Phone</td><td>Action</td></tr>";
			foreach ( $result_set as $row ) {
				echo "<tr><td><a title=\"Browse students by last name.\" href=\"admin.php?page=classdex_browse&amp;initial=" . urlencode ( strtolower ( $row ['last_name'] [0] ) ) . "#" . urlencode ( strtolower ( $row ['last_name'] ) ) . "\"><i class=\"icon-list\"></i></a></td>";
				echo "<td>{$row['first_name']} {$row['last_name']}</td>";
				echo "<td>{$row['phone']}</td>";
				echo "<td><a href=\"admin.php?page=classdex_edit_customer&amp;cust_id={$row['cust_id']}\"><i class=\"icon-pencil\"></i> Edit</a>&nbsp;&nbsp;
						<a href=\"admin.php?page=classdex_registration&amp;cust_id={$row['cust_id']}\"><i class=\"icon-ok\"></i> Register</a>&nbsp;&nbsp;  
						<a href=\"admin.php?page=classdex_payment&amp;cust_id={$row['cust_id']}\"><i class=\"icon-shopping-cart\"></i> Pay</a>
						</td></tr>";
			}
			echo "</table>";
			echo "<br><a class=\"btn\" href=\"admin.php?page=classdex_new_customer\">Add New Student</a>";
		}
	} elseif (strlen ( $keyword ) > 3) {
		$customers = get_table_name ( "customers" );
		
		$query = "
				SELECT *,
					MATCH(first_name, last_name) AGAINST('{$keyword}*' IN BOOLEAN MODE) AS score
					FROM {$customers}
				WHERE MATCH(first_name, last_name) AGAINST('{$keyword}*' IN BOOLEAN MODE)
				ORDER BY score DESC";
		
		$result_set = $wpdb->get_results ( $query, ARRAY_A );
		
		if (count ( $result_set ) == 0) {
			echo "<br><em>No results were found.</em><br> <a href=\"admin.php?page=classdex_browse&amp;initial=a\">Browse students by last name.</a><br>
					<br><a class=\"btn\" href=\"admin.php?page=classdex_new_customer\">Add New Student</a>";
		} else {
			echo "<table class=\"table table-striped\">
					  <tr><td></td><td>Name</td><td>Phone</td><td>Action</td></tr>";
			foreach ( $result_set as $row ) {
				echo "<tr><td><a title=\"Browse students by last name.\" href=\"admin.php?page=classdex_browse&amp;initial=" . urlencode ( strtolower ( $row ['last_name'] [0] ) ) . "#" . urlencode ( strtolower ( $row ['last_name'] ) ) . "\"><i class=\"icon-list\"></i></a></td>";
				echo "<td>{$row['first_name']} {$row['last_name']}</td>";
				echo "<td>{$row['phone']}</td>";
				echo "<td><a href=\"admin.php?page=classdex_edit_customer&amp;cust_id={$row['cust_id']}\"><i class=\"icon-pencil\"></i> Edit</a>&nbsp;&nbsp;
						<a href=\"admin.php?page=classdex_registration&amp;cust_id={$row['cust_id']}\"><i class=\"icon-ok\"></i> Register</a>&nbsp;&nbsp;  
						<a href=\"admin.php?page=classdex_payment&amp;cust_id={$row['cust_id']}\"><i class=\"icon-shopping-cart\"></i> Pay</a>
						</td></tr>";
			}
			echo "</table>";
			echo "<br><a class=\"btn\" href=\"admin.php?page=classdex_new_customer\">Add New Student</a>";
		}
	} else {
		echo "<br><em>Search must be at least 3 characters long.</em><br><a href=\"admin.php?page=classdex_browse&amp;initial=a\">Browse students by last name.</a><br>
					<br><a class=\"btn\" href=\"admin.php?page=classdex_new_customer\">Add New Student</a>";
	}
}
?>
<?php include(CLASSDEX_PATH . "/includes/footer.php"); ?>