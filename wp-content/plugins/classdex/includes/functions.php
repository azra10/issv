<?php
/*
 * Functions
 * ------------------------------------
 *
 * GENERAL
 * redirect_to( $location = NULL )
 * today()
 * timestamp_now()
 * get_table_name( $name )
 * get_acct_type_option_list( $acct_type = NULL )
 *
 *
 * ADMIN
 * get_household($address,$zip,$last_name)
 * query_to_csv($query, $filename, $attachment = false, $headers = true)
 * csv_file_to_mysql_table($source_file, $target_table, $max_line_length=10000)
 * quote_all_array($values)
 * quote_all($value)
 * z_out_classes($type, $start_date_time)
 *
 *
 * CUSTOMERS
 * update_active_date($cust_id)
 * get_customer_by_id($cust_id)
 * get_class_history($cust_id)
 * get_current_classes($cust_id)
 *
 *
 * CLASSES
 * get_class_by_id($class_id)
 * number_of_students($class_id)
 * display_classes($query, $registering = false, $archive = false)
 * display_student_list($class_id)
 *
 *
 * REGISTRATION
 * get_existing_registrations_by_cust($cust_id)
 * is_paid($reg_id)
 * mark_as_paid($reg_id)
 * mark_as_not_paid($reg_id)
 *
 *
 * PAYMENT
 * get_pending_payments($cust_id)
 * get_reg_deposit($reg_id)
 * get_reg_balance($fee, $discounts, $deposit)
 * display_classes_for_payment($cust_id, $partial = false)
 * update_class_credit($cust_id, $new_amount)
 * create_payment($cust_id, $reg_id, $amount, $pay_type, $date_time, $acct_type)
 * display_payment_history($cust_id)
 *
 */

/*
 * *************************************
 * GENERAL
 * *************************************
 */
function redirect_to($location = NULL) {
	if ($location != NULL) {
		wp_redirect ( $location );
		exit ();
	}
}
function today() {
	date_default_timezone_set ( get_option ( 'timezone_string' ) );
	$today = date ( "Y-m-d", strtotime ( "now" ) );
	
	return $today;
}
function time_now() {
	date_default_timezone_set ( get_option ( 'timezone_string' ) );
	$time_now = date ( "H:i", strtotime ( "now" ) );
	
	return $time_now;
}
function timestamp_now() {
	date_default_timezone_set ( get_option ( 'timezone_string' ) );
	$now = date ( "Y-m-d H:i:s", strtotime ( "now" ) ); // For MySQL format should be: YYYY-MM-DD HH:MM:SS
	
	return $now;
}
function get_table_name($name) {
	global $wpdb;
	
	if ($name == "customers") {
		return $wpdb->prefix . "classdex_customers";
	} elseif ($name == "classes") {
		return $wpdb->prefix . "classdex_classes";
	} elseif ($name == "registrations") {
		return $wpdb->prefix . "classdex_registrations";
	} elseif ($name == "payments") {
		return $wpdb->prefix . "classdex_payments";
	} else {
		die ();
	}
}
function get_acct_type_option_list($acct_type = NULL) {
	if (get_option ( 'classdex_account_types' ) == FALSE) {
		return "<option value=\"\">**Visit settings. Account types must be set.**</option>";
	} else {
		$acct_type_string = "(Select Account Type)," . esc_attr ( get_option ( 'classdex_account_types' ) );
		$types = explode ( ",", $acct_type_string );
		$output = "";
		foreach ( $types as $key => $value ) {
			if ($key == 0) {
				$output .= "<option value =\"\">(Select Account Type)</option>";
			} else {
				$output .= "<option value =\"{$key}\"";
				if ($acct_type == $key) {
					$output .= " selected";
				}
				$output .= ">{$value}</option>";
			}
		}
		return $output;
	}
}
function get_acct_type($acct_type) {
	if (get_option ( 'classdex_account_types' ) == FALSE) {
		return "<p>**Visit settings. Account types must be set.**</p>";
	} else {
		$acct_type_string = "(Select Account Type)," . esc_attr ( get_option ( 'classdex_account_types' ) );
		$types = explode ( ",", $acct_type_string );
		return $types [$acct_type];
	}
}

/*
 * *************************************
 * ADMIN
 * *************************************
 */

// this function returns a mysql result set of all of the customers with a certain address, zip, and last name (must be identical)
function get_household($address, $zip, $last_name) {
	global $wpdb;
	$customers = get_table_name ( "customers" );
	
	$query = $wpdb->prepare ( "SELECT * FROM {$customers} WHERE (address = %s AND zip = %d AND last_name = %s)", $address, $zip, $last_name );
	$household = $wpdb->get_results ( $query, ARRAY_A );
	
	return $household;
}
function query_to_csv($query, $filename, $attachment = false, $headers = true) {
	global $wpdb;
	if ($attachment) {
		// send response headers to the browser
		header ( 'Content-Type: text/csv' );
		header ( 'Content-Disposition: attachment;filename=' . $filename );
		$fp = fopen ( 'php://output', 'w' );
	} else {
		$fp = fopen ( $filename, 'w' );
	}
	
	$result_set = $wpdb->get_results ( $query, ARRAY_A );
	
	if ($headers) {
		// output header row (if at least one row exists)
		$row = $result_set [0];
		if ($row) {
			fputcsv ( $fp, array_keys ( $row ) );
			// reset pointer back to beginning
			// mysql_data_seek($result, 0);
		}
	}
	
	foreach ( $result_set as $row ) {
		fputcsv ( $fp, $row );
	}
	
	fclose ( $fp );
}

// Here is a function that accepts the path to a CSV file,
// and inserts all records to the given MySQL table, paying attention to the column names.
// This assumes that the columns in the table have exactly the same name as the columns in the CSV file,
// except that the dots (".") are removed. This is because MySQL column names cannot contain dots.
function csv_file_to_mysql_table($source_file, $target_table, $max_line_length = 10000) {
	global $wpdb;
	
	ini_set ( 'auto_detect_line_endings', TRUE );
	
	if (($handle = fopen ( "$source_file", "r" )) !== FALSE) {
		$columns = fgetcsv ( $handle, $max_line_length, "," );
		foreach ( $columns as &$column ) {
			$column = str_replace ( ".", "", $column );
		}
		$insert_query_prefix = "INSERT INTO $target_table (" . join ( ",", $columns ) . ")\nVALUES";
		while ( ($data = fgetcsv ( $handle, $max_line_length, "," )) !== FALSE ) {
			while ( count ( $data ) < count ( $columns ) )
				array_push ( $data, NULL );
			$query = "$insert_query_prefix (" . join ( ",", quote_all_array ( $data ) ) . ");";
			$wpdb->query ( $query );
		}
		fclose ( $handle );
	}
}
function quote_all_array($values) {
	foreach ( $values as $key => $value )
		if (is_array ( $value ))
			$values [$key] = quote_all_array ( $value );
		else
			$values [$key] = quote_all ( $value );
	return $values;
}
function quote_all($value) {
	global $wpdb;
	
	if (is_null ( $value ))
		return "NULL";
	
	$value = "'" . $wpdb->escape ( $value ) . "'";
	return $value;
}
function z_out_classes($type, $start_date_time) {
	global $wpdb;
	$payments = get_table_name ( "payments" );
	
	$query = "SELECT acct_type, SUM(amount) ";
	$query .= "FROM {$payments} ";
	$query .= "WHERE date_time > %s ";
	if ($type == 'paypal') {
		$query .= "AND pay_type = 1 ";
	} elseif ($type == 'register') {
		$query .= "AND pay_type >= 2 AND pay_type <= 6 ";
	} else {
		die ();
	}
	$query .= "GROUP BY acct_type ";
	$query .= "ORDER BY acct_type";
	
	$query_prep = $wpdb->prepare ( $query, $start_date_time );
	
	$z_out = $wpdb->get_results ( $query_prep, ARRAY_N );
	
	return $z_out;
}

/*
 * *************************************
 * CUSTOMERS
 * *************************************
 */
function update_active_date($cust_id) {
	global $wpdb;
	$today = today ();
	$customers = get_table_name ( "customers" );
	
	$result = $wpdb->update ( $customers, array (
			'active_date' => $today 
	), array (
			'cust_id' => $cust_id 
	), array (
			'%s' 
	) // today
, array (
			'%d' 
	) ) // cust_id
;
	
	return $result;
}
function get_customer_by_id($cust_id) {
	global $wpdb;
	
	$customers = get_table_name ( "customers" );
	$query = $wpdb->prepare ( "SELECT * FROM {$customers} WHERE cust_id = %d LIMIT 1", $cust_id );
	
	$row = $wpdb->get_row ( $query, ARRAY_A );
	
	if ($row != NULL) {
		return $row;
	} else {
		return NULL;
	}
}
function get_class_history($cust_id) {
	global $wpdb;
	$classes = get_table_name ( "classes" );
	$registrations = get_table_name ( "registrations" );
	
	$query = "SELECT * ";
	$query .= "FROM {$registrations}, {$classes} ";
	$query .= "WHERE ({$registrations}.cust_id = %d AND {$registrations}.class_id = {$classes}.class_id) ";
	$query .= "ORDER BY {$classes}.start_date DESC";
	
	$query_prep = $wpdb->prepare ( $query, $cust_id );
	
	$result_set = $wpdb->get_results ( $query_prep, ARRAY_A );
	if ($result_set != NULL) {
		return $result_set;
	} else {
		return NULL;
	}
}
function get_current_classes($cust_id) {
	global $wpdb;
	$classes = get_table_name ( "classes" );
	$registrations = get_table_name ( "registrations" );
	
	$today = today ();
	$query = "SELECT * ";
	$query .= "FROM {$registrations}, {$classes} ";
	$query .= "WHERE ({$registrations}.cust_id = %d AND {$registrations}.class_id = {$classes}.class_id AND {$classes}.end_date >= '{$today}') ";
	$query .= "ORDER BY {$classes}.start_date DESC";
	
	$query_prep = $wpdb->prepare ( $query, $cust_id );
	
	$result_set = $wpdb->get_results ( $query_prep, ARRAY_A );
	if ($result_set != NULL) {
		return $result_set;
	} else {
		return NULL;
	}
}
function add_to_mailchimp($email, $first_name, $last_name) {
	require_once (CLASSDEX_PATH . "/includes/MCAPI.class.php");
	
	$apikey = get_option ( 'classdex_mailchimp_apikey' );
	$listId = get_option ( 'classdex_mailchimp_listid' );
	
	$api = new MCAPI ( $apikey );
	// By default this sends a confirmation email - you will not see new members
	// until the link contained in it is clicked!
	
	$merge_vars = array (
			'FNAME' => $first_name,
			'LNAME' => $last_name 
	);
	
	$retval = $api->listSubscribe ( $listId, $email, $merge_vars );
}

/*
 * *************************************
 * CLASSES
 * *************************************
 */
function get_class_by_id($class_id) {
	global $wpdb;
	$classes = get_table_name ( "classes" );
	$query = $wpdb->prepare ( "SELECT * FROM {$classes} WHERE class_id = %d LIMIT 1", $class_id );
	$row = $wpdb->get_row ( $query, ARRAY_A );
	if ($row != NULL) {
		return $row;
	} else {
		return NULL;
	}
}
function number_of_students($class_id) {
	global $wpdb;
	$number_of_students = 0;
	$customers = get_table_name ( "customers" );
	$registrations = get_table_name ( "registrations" );
	$query = "SELECT {$customers}.cust_id ";
	$query .= "FROM {$customers}, {$registrations} ";
	$query .= "WHERE ({$registrations}.class_id = %d AND {$registrations}.cust_id = {$customers}.cust_id) ";
	
	$query_prep = $wpdb->prepare ( $query, $class_id );
	
	$result_set = $wpdb->get_results ( $query_prep, ARRAY_A );
	
	$number_of_students = count ( $result_set );
	
	return $number_of_students;
}
function display_classes($query, $registering = false, $archive = false) {
	global $wpdb;
	$today = today ();
	$result_set = $wpdb->get_results ( $query, ARRAY_A );
	
	if (count ( $result_set ) == 0) {
		echo "<br><em>No classes were found under these conditions.</em><br>";
	} else {
		echo "<table class=\"table table-striped\"";
		if ($archive) {
			echo " id=\"data_table\"";
		} elseif ($registering) {
			echo " id=\"data_table_simple\"";
		} else {
			echo " id=\"data_table_simple\"";
		}
		echo "><thead><tr>";
		if ($registering) {
			echo "<td></td>";
		}
		echo "<td><strong>ID</strong></td><td><strong>Title - Teacher</strong></td><td><strong>Date</strong></td>
				<td><strong>Time</strong></td><td><strong>Fee</strong></td><td><strong>Status</strong></td>
				<td><strong>Actions</strong></td></tr></thead><tbody>";
		foreach ( $result_set as $row ) {
			if ($row ['canceled'] == 1) {
				$class_status = "CANCELED";
			} elseif ($row ['end_date'] < $today) {
				$class_status = "FINISHED";
			} elseif ($row ['max_size'] != 0 && $row ['max_size'] <= number_of_students ( $row ['class_id'] )) {
				$class_status = "FULL";
			} else {
				$class_status = "Open";
			}
			echo "<tr>";
			if ($registering) {
				echo "<td><label class=\"checkbox\"><input";
				if ($class_status != "Open") {
					echo " disabled=\"disabled\"";
				}
				echo " type=\"checkbox\" name=\"class_ids[]\" value=\"{$row['class_id']}\" ></label></td>";
			}
			echo "<td>{$row['public_id']}</td>";
			echo "<td>{$row['title']} - {$row['teacher']} ";
			if (current_user_can ( 'manage_options' )) {
				echo "<a href=\"admin.php?page=classdex_edit_class&amp;class_id=" . urlencode ( $row ['class_id'] ) . "\"><i class=\"icon-pencil\"></i></a>";
			}
			echo "</td><td>";
			if ($row ['start_date'] == $row ['end_date']) {
				echo date ( "D, M j", strtotime ( $row ['start_date'] ) );
			} else {
				echo date ( "D, M j", strtotime ( $row ['start_date'] ) ) . "-" . date ( "M j", strtotime ( $row ['end_date'] ) );
			}
			echo "</td>";
			echo "<td>{$row['time']}</td>";
			echo "<td>\${$row['fee']}</td>";
			$class_status .= " (" . number_of_students ( $row ['class_id'] ) . "/" . $row ['max_size'] . ")";
			echo "<td>{$class_status}</td>";
			echo "<td><a href=\"admin.php?page=classdex_student_list&amp;class_id=" . urlencode ( $row ['class_id'] ) . "\"><i class=\"icon-eye-open\"></i> Class List</a>";
			if (current_user_can ( 'manage_options' )) {
				echo "&nbsp;&nbsp;&nbsp;<a href=\"admin.php?page=classdex_clone_class&amp;class_id=" . urlencode ( $row ['class_id'] ) . "\"><i class=\"icon-retweet\"></i> Clone</a>";
			}
			echo "</td></tr>";
		}
		echo "</tbody></table>";
		if ($registering) {
			echo "<br><input type=\"submit\" name=\"submit\" id=\"submit\" value=\"Register\" class=\"btn btn-primary\" />";
			echo "</form>";
		}
	}
}
function display_classes_for_widget($query) {
	global $wpdb;
	$today = today ();
	$result_set = $wpdb->get_results ( $query, ARRAY_A );
	
	if (count ( $result_set ) == 0) {
		echo "<br><em>No classes were found under these conditions.</em><br>";
	} else {
		echo "<table style=\"padding: 8px;
					  line-height: 20px;
					  text-align: left;
					  vertical-align: middle;
					  border-top: 1px solid #dddddd; width: 100%;
					  margin-bottom: 20px;\">
				  <thead style=\"font-weight: bold;\"><tr>";
		echo "<td>Title - Teacher</td><td>Time</td><td>Fee</td><td>Status</td><td>Actions</td></tr></thead><tbody>";
		foreach ( $result_set as $row ) {
			if ($row ['canceled'] == 1) {
				$class_status = "CANCELED";
			} elseif ($row ['end_date'] < $today) {
				$class_status = "FINISHED";
			} elseif ($row ['max_size'] != 0 && $row ['max_size'] <= number_of_students ( $row ['class_id'] )) {
				$class_status = "FULL";
			} else {
				$class_status = "Open";
			}
			echo "<tr><td>";
			if (current_user_can ( 'manage_options' ))
				echo "<a href=\"admin.php?page=classdex_edit_class&amp;class_id=" . urlencode ( $row ['class_id'] ) . "\">";
			echo "{$row['title']} - {$row['teacher']} ";
			if (current_user_can ( 'manage_options' ))
				echo "</a>";
			echo "</td><td>{$row['time']}</td>";
			echo "<td>\${$row['fee']}</td>";
			$class_status .= " (" . number_of_students ( $row ['class_id'] ) . "/" . $row ['max_size'] . ")";
			echo "<td>{$class_status}</td>";
			echo "<td><a href=\"admin.php?page=classdex_student_list&amp;class_id=" . urlencode ( $row ['class_id'] ) . "\"><i class=\"icon-eye-open\"></i> Class List</a></td>";
			echo "</tr>";
		}
		echo "</tbody></table>";
	}
}
function display_student_list($class_id) {
	global $wpdb;
	$customers = get_table_name ( "customers" );
	$registrations = get_table_name ( "registrations" );
	$email_list = "";
	
	$query = "SELECT * ";
	$query .= "FROM {$customers}, {$registrations} ";
	$query .= "WHERE ({$registrations}.class_id = %d AND {$registrations}.cust_id = {$customers}.cust_id) ";
	$query .= "ORDER BY {$customers}.last_name ASC";
	
	$query_prep = $wpdb->prepare ( $query, $class_id );
	
	$result_set = $wpdb->get_results ( $query_prep, ARRAY_A );
	
	if ($result_set == NULL) {
		echo "<em>No students are registered for this class.</em><br>";
	} else {
		echo "<table class=\"table table-striped\">
				  <tr><td>Name</td><td>Phone</td><td>Status</td><td>Action</td></tr>";
		foreach ( $result_set as $row ) {
			echo "<tr>";
			echo "<td>{$row['first_name']} {$row['last_name']}</td>";
			echo "<td>{$row['phone']}</td>";
			echo "<td>";
			if (is_paid ( $row ['reg_id'] )) {
				echo "PAID";
			}
			echo "</td>";
			$payment = get_reg_deposit ( $row ['reg_id'] );
			$delete_registration_url = "admin.php?page=classdex_delete_table_item&amp;noheader=true&amp;t=registrations&amp;i=" . urlencode ( $row ['reg_id'] );
			echo "<td><a href=\"admin.php?page=classdex_edit_customer&amp;cust_id={$row['cust_id']}\"><i class=\"icon-pencil\"></i> Edit</a>&nbsp;&nbsp; 
					<a href=\"admin.php?page=classdex_payment&amp;cust_id={$row['cust_id']}\"><i class=\"icon-shopping-cart\"></i> Pay</a>&nbsp;&nbsp;
					<a href=\"" . wp_nonce_url ( $delete_registration_url, 'classdex_delete_table_item' ) . "\" onclick=\"return confirm('Are you sure? Unregistering from this class cannot be undone. Give \${$payment} as class credit or refund.')\" ><i class=\"icon-remove\"></i> Remove</a>
					</td></tr>";
			if ($row ['email'] != NULL)
				$email_list .= $row ['email'] . ",";
		}
		echo "</table>";
		
		$print_student_list_url = "admin.php?page=classdex_print_student_list&amp;noheader=true&amp;class_id={$class_id}";
		$print_sign_up_list_url = "admin.php?page=classdex_print_sign_up_list&amp;noheader=true&amp;class_id={$class_id}";
		$print_waiver_url = "admin.php?page=classdex_print_waiver&amp;noheader=true&amp;class_id={$class_id}";
		
		echo "<div class=\"btn-group\">";
		echo "<a class=\"btn\" target=\"_blank\" href=\"" . wp_nonce_url ( $print_student_list_url, 'classdex_print_student_list' ) . "\"><i class=\"icon-print\"></i> Print</a>";
		echo "<button class=\"btn dropdown-toggle\" data-toggle=\"dropdown\">
			    <span class=\"caret\"></span>
			  </button>";
		echo "<ul class=\"dropdown-menu\">
			    <li><a target=\"_blank\" href=\"" . wp_nonce_url ( $print_student_list_url, 'classdex_print_student_list' ) . "\">Print Class List</a></li>
			    <li><a target=\"_blank\" href=\"" . wp_nonce_url ( $print_sign_up_list_url, 'classdex_print_sign_up_list' ) . "\">Print Sign Up List</a></li>
			    <li><a target=\"_blank\" href=\"" . wp_nonce_url ( $print_waiver_url, 'classdex_print_waiver' ) . "\">Print Waiver</a></li>
			  	<li><a href=\"mailto:" . $email_list . "\">Email Class List</a></li>
			  </ul>
			</div>";
	}
}

/*
 * *************************************
 * REGISTRATIONS
 * *************************************
 */

// this function returns a mysql result set of all of the customers with a certain address and zip (must be identical)
function get_existing_registrations_by_cust($cust_id) {
	global $wpdb;
	$registrations = get_table_name ( "registrations" );
	
	$query = "SELECT * ";
	$query .= "FROM {$registrations} ";
	$query .= "WHERE cust_id = %d";
	
	$query_prep = $wpdb->prepare ( $query, $cust_id );
	
	$result_set = $wpdb->get_results ( $query_prep, ARRAY_A );
	if ($result_set != NULL) {
		return $result_set;
	} else {
		return NULL;
	}
}
function get_acct_type_by_reg_id($reg_id) {
	global $wpdb;
	$registrations = get_table_name ( "registrations" );
	
	$query = "SELECT * ";
	$query .= "FROM {$registrations} ";
	$query .= "WHERE reg_id = %d ";
	$query .= "LIMIT 1";
	
	$query_prep = $wpdb->prepare ( $query, $reg_id );
	
	$registration = $wpdb->get_row ( $query_prep, ARRAY_A );
	
	$class_id = $registration ['class_id'];
	
	$class = get_class_by_id ( $class_id );
	$acct_type = $class ['acct_type'];
	
	return $acct_type;
}

// This function returns true is the registration is paid, and false if not
function is_paid($reg_id) {
	global $wpdb;
	$registrations = get_table_name ( "registrations" );
	
	$query = "SELECT * ";
	$query .= "FROM {$registrations} ";
	$query .= "WHERE reg_id = {$reg_id} ";
	$query .= "LIMIT 1";
	$registration = $wpdb->get_row ( $query, ARRAY_A );
	
	if ($registration ['paid'] == 1) {
		return true;
	} else {
		return false;
	}
}
function mark_as_paid($reg_id) {
	global $wpdb;
	$registrations = get_table_name ( "registrations" );
	
	$result = $wpdb->update ( $registrations, array (
			'paid' => 1 
	), array (
			'reg_id' => $reg_id 
	), array (
			'%d' 
	) // paid
, array (
			'%d' 
	) ) // reg_id
;
	
	if ($result == 1) {
		// Success
		return true;
	} else {
		// Failed
		return false;
	}
}
function mark_as_not_paid($reg_id) {
	global $wpdb;
	$registrations = get_table_name ( "registrations" );
	
	$result = $wpdb->update ( $registrations, array (
			'paid' => 0 
	), array (
			'reg_id' => $reg_id 
	), array (
			'%d' 
	) // paid
, array (
			'%d' 
	) ) // reg_id
;
	
	if ($result == 1) {
		// Success
		return true;
	} else {
		// Failed
		return false;
	}
}

/*
 * *************************************
 * PAYMENTS
 * *************************************
 */
function get_payment_reg_id($pay_id) {
	global $wpdb;
	$payments = get_table_name ( "payments" );
	
	$query = "SELECT * ";
	$query .= "FROM {$payments} ";
	$query .= "WHERE pay_id = %d";
	
	$query_prep = $wpdb->prepare ( $query, $pay_id );
	
	$row = $wpdb->get_row ( $query_prep, ARRAY_A );
	
	$reg_id = $row ['reg_id'];
	
	return $reg_id;
}
function get_pending_payments($cust_id) {
	global $wpdb;
	$classes = get_table_name ( "classes" );
	$registrations = get_table_name ( "registrations" );
	
	$query = "SELECT * ";
	$query .= "FROM {$registrations}, {$classes} ";
	$query .= "WHERE ({$registrations}.cust_id = %d AND {$registrations}.class_id = {$classes}.class_id AND {$registrations}.paid = 0) ";
	$query .= "ORDER BY {$classes}.start_date DESC";
	
	$query_prep = $wpdb->prepare ( $query, $cust_id );
	
	$pending_payments = $wpdb->get_results ( $query_prep, ARRAY_A );
	
	return $pending_payments;
}
function get_reg_deposit($reg_id) {
	global $wpdb;
	$deposit = 0;
	$payments = get_table_name ( "payments" );
	
	$query = $wpdb->prepare ( "SELECT amount FROM {$payments} WHERE reg_id = %d", $reg_id );
	
	$result_set = $wpdb->get_results ( $query, ARRAY_A );
	
	if (count ( $result_set ) != 0) {
		foreach ( $result_set as $row ) {
			$deposit += $row ['amount'];
		}
	}
	
	return $deposit;
}
function get_reg_balance($reg_id) {
	global $wpdb;
	$customers = get_table_name ( "customers" );
	$classes = get_table_name ( "classes" );
	$registrations = get_table_name ( "registrations" );
	
	$query = "SELECT * ";
	$query .= "FROM {$registrations} ";
	$query .= "INNER JOIN {$classes} ON {$registrations}.class_id = {$classes}.class_id ";
	$query .= "INNER JOIN {$customers} ON {$registrations}.cust_id = {$customers}.cust_id ";
	$query .= "WHERE reg_id = %d ";
	$query .= "LIMIT 1";
	
	$query_prep = $wpdb->prepare ( $query, $reg_id );
	
	$row = $wpdb->get_row ( $query_prep, ARRAY_A );
	
	$discount = get_discount ( $row ['member'], $row ['senior'], $row ['student'] );
	
	$discounts = $row ['discounts'];
	$fee = $row ['fee'];
	$deposit = get_reg_deposit ( $reg_id );
	
	if ($discounts == 0) {
		$balance = $fee - $deposit;
	} else {
		$balance = round ( $fee * $discount ) - $deposit;
	}
	
	return $balance;
}
function display_classes_for_payment($cust_id, $partial = false) {
	$customer = get_customer_by_id ( $cust_id );
	$subtotal = 0;
	$today = today ();
	$pending_payments = get_pending_payments ( $cust_id );
	
	$discount = get_discount ( $customer ['member'], $customer ['senior'], $customer ['student'] );
	
	if ($pending_payments == NULL) {
		echo "<em>No pending payments.</em><br>";
		$total_payment = NULL;
	} else {
		echo "<table class=\"table table-striped\">
				  <thead><tr>";
		if (! $partial) {
			echo "<td></td>";
		}
		echo "<td><strong>ID</strong></td><td><strong>Title - Teacher</strong></td>
					<td><strong>Date</strong></td><td><strong>Fee</strong></td><td><strong>Discount</strong></td>
					<td><strong>Deposit</strong></td><td><strong>Balance</strong></td>";
		if ($partial) {
			echo "<td><strong>Payment</strong></td><td><strong>Paid</strong></td>";
		}
		echo "</tr></thead><tbody id=\"payment-rows\">";
		$paycounter = 0;
		foreach ( $pending_payments as $row ) {
			$paycounter ++;
			$deposit = get_reg_deposit ( $row ['reg_id'] );
			$balance = get_reg_balance ( $row ['reg_id'] );
			echo "<tr>";
			if (! $partial) {
				echo "<td><label class=\"checkbox\"><input type=\"checkbox\" name=\"full_payment_reg_ids[{$row['reg_id']}]\" value=\"{$balance}\" checked id=\"full_payment{$paycounter}\" onclick=\"getTotal()\" ></label></td>";
			}
			echo "<td>{$row['public_id']}</td>";
			echo "<td>{$row['title']} - {$row['teacher']}</td>";
			echo "<td>";
			if ($row ['start_date'] == $row ['end_date']) {
				echo date ( "D, M j", strtotime ( $row ['start_date'] ) );
			} else {
				echo date ( "D, M j", strtotime ( $row ['start_date'] ) ) . "-" . date ( "M j", strtotime ( $row ['end_date'] ) );
			}
			echo "</td>";
			echo "<td>\${$row['fee']}</td>";
			if ($row ['discounts'] == 0) {
				echo "<td>\${$row['fee']}</td>";
				$subtotal += $row ['fee'];
			} else {
				echo "<td>\$" . round ( $row ['fee'] * $discount ) . "</td>";
				$subtotal += round ( $row ['fee'] * $discount );
			}
			
			$subtotal -= $deposit;
			echo "<td>\${$deposit}</td><td>\${$balance}</td>";
			if ($partial) {
				echo "<td><div class=\"input-prepend\">
								<span class=\"add-on\">\$</span>
								<input class=\"input-mini\" placeholder=\"0\" type=\"number\" style=\"height:30px\" step=\"any\" min=\"0\" name=\"partial_pays[{$row['reg_id']}]\" value=\"\" id=\"partial_pay{$paycounter}\" onkeyup=\"getSum()\" />
							</div></td>";
				echo "<td><label class=\"checkbox\"><input type=\"checkbox\" name=\"paid_reg_ids[]\" value=\"{$row['reg_id']}\" ></label></td>";
			}
			echo "</tr>";
		} // End: while loop
		
		echo "</tbody></table>";
		
		$total_payment = $subtotal;
	} // End: else statement
	
	return $total_payment;
}
function update_class_credit($cust_id, $new_amount) {
	global $wpdb;
	$customers = get_table_name ( "customers" );
	
	$result = $wpdb->update ( $customers, array (
			'class_credit' => $new_amount 
	), array (
			'cust_id' => $cust_id 
	), array (
			'%d' 
	) // new_amount
, array (
			'%d' 
	) ) // cust_id
;
	
	if ($result == 1) {
		// Success
		return true;
	} else {
		// Failed
		return false;
	}
}
function create_payment($cust_id, $reg_id, $amount, $pay_type, $date_time, $acct_type) {
	global $wpdb;
	
	$payments = get_table_name ( "payments" );
	
	$result = $wpdb->insert ( $payments, array (
			'cust_id' => $cust_id,
			'reg_id' => $reg_id,
			'amount' => $amount,
			'pay_type' => $pay_type,
			'date_time' => $date_time,
			'acct_type' => $acct_type 
	), array (
			'%d',
			'%d',
			'%d',
			'%s',
			'%s',
			'%s' 
	) );
	
	if ($result != false) {
		// Success
		return true;
	} else {
		// Failed
		return false;
	}
}
function display_payment_history($cust_id) {
	global $wpdb;
	$payments = get_table_name ( "payments" );
	
	$query = "SELECT * ";
	$query .= "FROM {$payments} ";
	$query .= "WHERE cust_id = %d ";
	$query .= "ORDER BY date_time DESC";
	
	$query_prep = $wpdb->prepare ( $query, $cust_id );
	
	$results = $wpdb->get_results ( $query_prep, ARRAY_A );
	
	if (count ( $results ) == 0) {
		echo "<em>No payment history.</em><br>";
	} else {
		echo "<ul>";
		foreach ( $results as $row ) {
			echo "<li>";
			echo "\${$row['amount']} with ";
			if ($row ['pay_type'] == 1) {
				echo "Paypal";
			} elseif ($row ['pay_type'] == 2) {
				echo "Check";
			} elseif ($row ['pay_type'] == 3) {
				echo "Cash";
			} elseif ($row ['pay_type'] == 4) {
				echo "Credit Card";
			} elseif ($row ['pay_type'] == 5) {
				echo "Gift Certificate";
			} else {
				echo "Class Credit";
			}
			echo " (";
			echo "{$row['date_time']}";
			echo ") - ";
			echo get_acct_type ( $row ['acct_type'] );
			$delete_payment_url = "admin.php?page=classdex_delete_table_item&amp;noheader=true&amp;t=payments&amp;i=" . urlencode ( $row ['pay_id'] );
			echo " 	<a href=\"" . wp_nonce_url ( $delete_payment_url, 'classdex_delete_table_item' ) . "\" onclick=\"return confirm('Are you sure? Canceling payment cannot be undone. Give \${$row['amount']} as class credit or refund.')\" ><i class=\"icon-remove\"></i></a>";
			echo "</li>";
		}
		echo "</ul>";
	}
}
function get_discount($member, $senior, $student) {
	if ($senior == 1) {
		$discount = (100 - get_option ( 'classdex_discount_senior' )) / 100;
	} elseif ($student == 1) {
		$discount = (100 - get_option ( 'classdex_discount_student' )) / 100;
	} elseif ($member == 1) {
		$discount = (100 - get_option ( 'classdex_discount_member' )) / 100;
	} else {
		$discount = 1;
	} // No discount
	
	return $discount;
}
function get_discount_options($fee) {
	$member_fee = round ( $fee * ((100 - get_option ( 'classdex_discount_member' )) / 100) );
	$senior_fee = round ( $fee * ((100 - get_option ( 'classdex_discount_senior' )) / 100) );
	$student_fee = round ( $fee * ((100 - get_option ( 'classdex_discount_student' )) / 100) );
	$options = "Regular +\${$fee}";
	if (get_option ( 'classdex_discount_member' ) != 0) {
		$options .= ", Member +\${$member_fee}";
	}
	if (get_option ( 'classdex_discount_student' ) != 0) {
		$options .= ", Student +\${$student_fee}";
	}
	if (get_option ( 'classdex_discount_senior' ) != 0) {
		$options .= ", Senior (65+) +\${$senior_fee}";
	}
	
	return $options;
}
function display_admin_navigation($active) {
	echo "<ul class=\"nav nav-pills\" style=\"padding-right: 0px; padding-left: 0px;\">";
	echo "<li";
	if ($active == "add")
		echo " class=\"active\"";
	echo "><a href=\"admin.php?page=classdex_new_class\">Add Classes</a></li>";
	echo "<li";
	if ($active == "zout")
		echo " class=\"active\"";
	echo "><a href=\"admin.php?page=classdex_zout\">Z-Out Classes</a></li>";
	echo "<li";
	if ($active == "import")
		echo " class=\"active\"";
	echo "><a href=\"admin.php?page=classdex_import_export\">Import/Export</a></li>";
	echo "<li";
	if ($active == "clear")
		echo " class=\"active\"";
	echo "><a href=\"admin.php?page=classdex_clear_database\">Clear Database</a></li>";
	echo "<li";
	if ($active == "mailing")
		echo " class=\"active\"";
	echo "><a href=\"admin.php?page=classdex_mailing_list\">Export Mailing List</a></li>";
	echo "<li";
	if ($active == "unpaid")
		echo " class=\"active\"";
	echo "><a href=\"admin.php?page=classdex_unpaid\">Unpaid</a></li>";
	echo "<li";
	if ($active == "expired")
		echo " class=\"active\"";
	echo "><a href=\"admin.php?page=classdex_expired\">Expired Members</a></li>";
	echo "</ul>";
}

?>