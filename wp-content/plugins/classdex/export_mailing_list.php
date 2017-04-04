<?php check_admin_referer( 'classdex_export_mailing_list','classdex_export_mailing_list_nonce' );  ?>
<?php global $wpdb; ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php
date_default_timezone_set ( get_option ( 'timezone_string' ) );

if (isset ( $_POST ["date"] )) {
	$date = date ( "Y-m-d", strtotime ( $_POST ["date"] ) );
} else {
	$date = date ( "Y-m-d", strtotime ( "-3 years" ) );
}

$filename = "mailing_list.csv";
$attachment = true;
$headers = true;

if ($attachment) {
	// send response headers to the browser
	header ( 'Content-Type: text/csv' );
	header ( 'Content-Disposition: attachment;filename=' . $filename );
	$fp = fopen ( 'php://output', 'w' );
} else {
	$fp = fopen ( $filename, 'w' );
}

$customers = get_table_name ( "customers" );
$zip_start = get_option ( 'classdex_zip_start' );
$zip_stop = get_option ( 'classdex_zip_stop' );

$query = "SELECT cust_id, first_name, last_name, address, city, state, zip ";
$query .= "FROM {$customers} ";
$query .= "WHERE (";
if ($zip_start != $zip_stop) {
	$query .= "zip >= %d AND zip <= %d AND ";
}
$query .= "green != 1 AND active_date > %s) ";
$query .= "ORDER BY zip ASC";

if ($zip_start != $zip_stop) {
	$query_prep = $wpdb->prepare ( $query, $zip_start, $zip_stop, $date );
} else {
	$query_prep = $wpdb->prepare ( $query, $date );
}

$result_set = $wpdb->get_results ( $query_prep, ARRAY_A );

if ($headers) {
	// output header row (if at least one row exists)
	$row = $result_set [0];
	if ($row) {
		fputcsv ( $fp, array_keys ( $row ) );
	}
}

$cust_ids_in_household = array ();

foreach ( $result_set as $row ) {
	if (! in_array ( $row ['cust_id'], $cust_ids_in_household )) // if the cust_id has not already been added to a household
{
		// check if there are customers with matching dates
		// edit row before sending to the csv
		$household = get_household ( $row ['address'], $row ['zip'], $row ['last_name'] );
		
		if (count ( $household ) > 1) // there will always be at least one member of the household
{
			$first_member = $household [0];
			$cust_ids_in_household [] = $first_member ['cust_id'];
			$first_member_last_name = $first_member ['last_name'];
			
			foreach ( $household as $other_member ) {
				$cust_ids_in_household [] = $other_member ['cust_id'];
				if (strtoupper ( $first_member_last_name ) == strtoupper ( $other_member ['last_name'] )) {
					if (strtoupper ( $first_member ['first_name'] ) != strtoupper ( $other_member ['first_name'] )) {
						$first_member ['first_name'] .= " & " . $other_member ['first_name'];
					}
				} else {
					$first_member ['last_name'] .= " & " . $other_member ['first_name'] . " " . $other_member ['last_name'];
				}
				$row ['first_name'] = $first_member ['first_name'];
				$row ['last_name'] = $first_member ['last_name'];
			}
		}
		
		// convert to uppercase for mailing
		$row ['first_name'] = strtoupper ( $row ['first_name'] );
		$row ['last_name'] = strtoupper ( $row ['last_name'] );
		$row ['address'] = strtoupper ( $row ['address'] );
		$row ['city'] = strtoupper ( $row ['city'] );
		$row ['state'] = strtoupper ( $row ['state'] );
		
		fputcsv ( $fp, $row );
	}
}

fclose ( $fp );

exit ();
?>