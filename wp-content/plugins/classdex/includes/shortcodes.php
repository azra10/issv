<?php

// [classdex_detail start="yyyy-mm-dd" stop="yyyy-mm-dd" type="detail"] start can be set to "today"
function classdex_detail_function($atts) {
	$start = $atts ['start'];
	$stop = $atts ['stop'];
	$type = $atts ['type'];
	global $wpdb;
	$classes = get_table_name ( "classes" );
	$today = today ();
	if ($start == "today") {
		$start = $today;
	}
	
	if ($type == "detail1") {
		$type_protocol = "meditation = 1";
	} elseif ($type == "detail2") {
		$type_protocol = "wellness = 1";
	} elseif ($type == "detail3") {
		$type_protocol = "philosophy = 1";
	} elseif ($type == "detail") {
		$type_protocol = "(seminar = 1 OR meditation = 1 OR wellness = 1 OR philosophy = 1)";
	} else {
		return "Error: Class type must be named correctly. <a href=\"http://howtononprofit.wordpress.com/classdex/\">See ClassDex documentation.</a>";
	}
	
	$query = "SELECT * ";
	$query .= "FROM {$classes} ";
	$query .= "WHERE end_date >= %s AND start_date <= %s AND {$type_protocol} ";
	$query .= "ORDER BY start_date";
	
	$query_prep = $wpdb->prepare ( $query, $start, $stop );
	
	$result_set = $wpdb->get_results ( $query_prep, ARRAY_A );
	
	$current_month = 0;
	$content = " ";
	
	if (count ( $result_set ) == 0) {
		$content .= "<br><em>No classes were found under these conditions.</em><br>";
	} else {
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
			if ($row ['end_date'] == $row ['start_date']) {
				$recurring = false;
			} else {
				$recurring = true;
			}
			
			if ($current_month != date ( "n", strtotime ( $row ['start_date'] ) )) {
				$current_month = date ( "n", strtotime ( $row ['start_date'] ) );
				
				switch ($current_month) {
					case 1 :
						$content .= get_option ( 'classdex_month_01' );
						break;
					case 2 :
						$content .= get_option ( 'classdex_month_02' );
						break;
					case 3 :
						$content .= get_option ( 'classdex_month_03' );
						break;
					case 4 :
						$content .= get_option ( 'classdex_month_04' );
						break;
					case 5 :
						$content .= get_option ( 'classdex_month_05' );
						break;
					case 6 :
						$content .= get_option ( 'classdex_month_06' );
						break;
					case 7 :
						$content .= get_option ( 'classdex_month_07' );
						break;
					case 8 :
						$content .= get_option ( 'classdex_month_08' );
						break;
					case 9 :
						$content .= get_option ( 'classdex_month_09' );
						break;
					case 10 :
						$content .= get_option ( 'classdex_month_10' );
						break;
					case 11 :
						$content .= get_option ( 'classdex_month_11' );
						break;
					case 12 :
						$content .= get_option ( 'classdex_month_12' );
						break;
				}
			}
			
			if (! empty ( $row ['custom_html'] )) {
				$content .= $row ['custom_html'];
			} else {
				$content .= "<a name=\"" . urlencode ( $row ['public_id'] ) . "\"></a>";
				if (! empty ( $row ['image'] )) {
					$content .= "<img src=\"{$row['image']}\" class=\"";
					if ($row ['image_justified'] == 1) {
						$content .= "alignleft framed\" />";
					} else {
						$content .= "alignright framed\" />";
					}
				}
				$content .= "<h4>" . $row ['title'] . " with " . $row ['teacher'] . "</h4><em>";
				$content .= date ( "l", strtotime ( $row ['start_date'] ) );
				if ($recurring) {
					$content .= "s";
				}
				$content .= ", ";
				if (date ( "m", strtotime ( $row ['start_date'] ) ) == date ( "m", strtotime ( $row ['end_date'] ) )) {
					if ($recurring) {
						$content .= date ( "F j", strtotime ( $row ['start_date'] ) ) . "-" . date ( "j", strtotime ( $row ['end_date'] ) ) . ", ";
					} else {
						$content .= date ( "F j", strtotime ( $row ['start_date'] ) ) . ", ";
					}
				} else {
					$content .= date ( "F j", strtotime ( $row ['start_date'] ) ) . "-" . date ( "F j", strtotime ( $row ['end_date'] ) ) . ", ";
				}
				$content .= $row ['time'] . "</em><br>";
				$content .= $row ['description'] . " Fee: \$" . $row ['fee'] . " (ID#: " . $row ['public_id'] . ") &mdash; ";
				if ($class_status == "Open") {
					$content .= "[add_to_cart item=\"" . $row ['class_id'] . "\" style=\"padding:0px; display:inline;\" showprice=\"no\" ]";
				} else {
					$content .= $class_status;
				}
				$content .= "<br><div class=\"classdex_divider\"></div>";
			}
		}
	}
	
	return do_shortcode ( $content );
}
add_shortcode ( 'classdex_detail', 'classdex_detail_function' );

// [classdex_list start="yyyy-mm-dd" stop="yyyy-mm-dd"] start can be set to "today"
function classdex_list_function($atts) {
	$start = $atts ['start'];
	$stop = $atts ['stop'];
	global $wpdb;
	$classes = get_table_name ( "classes" );
	$today = today ();
	if ($start == "today") {
		$start = $today;
	}
	
	$query = "SELECT * ";
	$query .= "FROM {$classes} ";
	$query .= "WHERE end_date >= %s AND start_date <= %s AND yoga_class = 1 ";
	$query .= "ORDER BY title, start_date";
	$query_prep = $wpdb->prepare ( $query, $start, $stop );
	$result_set = $wpdb->get_results ( $query_prep, ARRAY_A );
	
	$content = " ";
	
	if (count ( $result_set ) == 0) {
		$content .= "<br><em>No classes were found under these conditions.</em><br>";
	} else {
		
		$content .= "<table class=\"classdex_fancy_table\"><thead><tr>";
		$content .= "<th><strong>Class</strong></th><th><strong>Day</strong></th><th style=\"width: 100px;\"><strong>Time</strong></th><th style=\"width: 110px;\"><strong>Dates</strong></th><th><strong>Fee</strong></th><th style=\"width: 240px;\"><strong>Registration</strong></th></tr></thead><tbody>";
		
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
			
			$content .= "<tr><td>{$row['title']} - {$row['teacher']}</td>";
			$content .= "<td>";
			$content .= date ( "D", strtotime ( $row ['start_date'] ) );
			$content .= "</td>";
			$content .= "<td>{$row['time']}</td>";
			$content .= "<td>";
			$content .= date ( "M j", strtotime ( $row ['start_date'] ) ) . "-" . date ( "M j", strtotime ( $row ['end_date'] ) );
			$content .= "</td>";
			$content .= "<td>\${$row['fee']}</td>";
			if ($class_status == "Open") {
				$content .= "<td>[add_to_cart item=\"" . $row ['class_id'] . "\" showprice=\"no\" ]</td>";
			} elseif ($class_status == "FULL") {
				$content .= "<td>FULL - <a href=\"#full\">Already registered?</a></td>";
			} else {
				$content .= "<td>" . $class_status . "</td>";
			}
			$content .= "</tr>";
		}
		$content .= "</tbody></table>";
	}
	
	return do_shortcode ( $content );
}
add_shortcode ( 'classdex_list', 'classdex_list_function' );

?>