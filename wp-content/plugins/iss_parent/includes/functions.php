<?php
if (! function_exists ( 'iss_write_log' )) {
	function iss_write_log($log) {
		if (true === WP_DEBUG) {
			
			if (is_array ( $log ) || is_object ( $log )) {
				error_log ( get_current_user () . ' ' . print_r ( $log, true ) );
			} else {
				error_log ( get_current_user () . ' ' . $log );
			}
		}
	}
}
function iss_valid_tabname($tabname) {
	switch ($tabname) {
		case 'parent' :
		case 'contact' :
		case 'complete' :
		case 'home' :
		case 'view' :
			return true;
		default :
			if (strpos ( $tabname, "student", 0 ) === 0)
				return true;
	}
	return false;
}
function iss_get_requiredfields_by_tabname($tabname) {
	switch ($tabname) {
		case 'parent' :
			return iss_parent_required_tabfields ();
		case 'home' :
			return iss_home_required_tabfields ();
		case 'contact' :
			return iss_contact_required_tabfields ();
		case 'complete' :
			return array ();
		default :
			if (strpos ( $tabname, "student", 0 ) === 0) {
				return iss_student_required_fields ();
			}
	}
	return array ();
}
function iss_get_tabfields_by_tabname($tabname) {
	switch ($tabname) {
		case 'parent' :
			return iss_parent_tabfields ();
			break;
		case 'home' :
			return iss_home_tabfields ();
			break;
		case 'contact' :
			return iss_contact_tabfields ();
			break;
		case 'complete' :
			return iss_payment_tabfields ();
			break;
		default :
			if (strpos ( $tabname, "student", 0 ) === 0) {
				return iss_student_fields ();
			}
	}
	return array ();
}
/**
 * Function iss_get_next_tab
 * Find next or default tab name to show to user
 * 
 * @param
 *        	current showing tab could be null
 * @return string tab name
 *        
 */
function iss_get_next_tab($current_tab) {
	if ($current_tab == 'parent') {
		return 'home';
	} else if ($current_tab == 'home') {
		return 'contact';
	} else if ($current_tab == 'contact') {
		return 'student';
	} else if ($current_tab == 'student') {
		return 'complete';
	} else if ($current_tab == 'complete') {
		return 'view';
	} else
		return "view";
}
/**
 * Function iss_get_school_name
 * Find admin preference or default school name
 * 
 * @param
 *        	none
 * @return string school name
 *        
 */
function iss_get_school_name() {
	$name = iss_adminpref_schoolname ();
	if (NULL == $name)
		$name = "Islamic School";
	return $name;
}
/**
 * Function iss_registration_period
 * Find user / admin preference or default registration year
 * 
 * @param
 *        	none
 * @return string registration year
 *        
 */
function iss_registration_period() {
	$regyear = iss_userpref_registrationyear ();
	if (NULL == $regyear) {
		$regyear = iss_adminpref_registrationyear ();
	}
	if (NULL == $regyear) {
		return "2016-2017";
	} else {
		return $regyear;
	}
}
/**
 * Function iss_userpref_registrationyear
 * Find user preference registration year in user meta data
 * 
 * @param
 *        	none
 * @return string registration year
 *        
 */
function iss_userpref_registrationyear() {
	$list = iss_get_user_option_list ();
	if (isset ( $list ['iss_user_registrationyear'] ) && isset ( $list ['iss_user_registrationyear'] [0] ) && ! empty ( $list ['iss_user_registrationyear'] [0] )) {
		return $list ['iss_user_registrationyear'] [0];
	}
	return NULL;
}
/**
 * Function iss_last_registration_year
 * Finds the last registration year in payment table
 * 
 * @param
 *        	none
 * @return string last registration year
 *        
 */
function iss_last_registration_year() {
	global $wpdb;
	$parents = iss_get_table_name ( "parents" );
	$query = "SELECT MAX(RegistrationYear) as RegistrationYear FROM {$parents} LIMIT 1";
	$result_set = $wpdb->get_row ( $query, ARRAY_A );
	
	$regyear = NULL;
	if ($result_set != NULL) {
		$regyear = $result_set ['RegistrationYear'];
	}
	return $regyear;
}
/**
 * Function iss_next_registration_year
 * Finds the last registration year in payment table and appends an year
 * 
 * @param
 *        	none
 * @return string next registration year
 *        
 */
function iss_next_registration_year() {
	global $wpdb;
	$parents = iss_get_table_name ( "parents" );
	$query = "SELECT MAX(RegistrationYear) as RegistrationYear FROM {$parents} LIMIT 1";
	$result_set = $wpdb->get_row ( $query, ARRAY_A );
	
	$regyear = NULL;
	if ($result_set != NULL) {
		$regyear = $result_set ['RegistrationYear'];
	}
	if ($regyear == NULL)
		return NULL;
	
	list ( $y1, $y2 ) = explode ( "-", $regyear );
	$y1int = intval ( $y1 );
	$y2int = intval ( $y2 );
	$nextregyear = $y2 . '-' . ($y2int + 1);
	return $nextregyear;
}
function iss_next_issgrade($issgrade, $gender, $regularschoolgrade) {
	switch ($issgrade) {
		case '1' :
			return '2';
			break;
		case '2' :
			return '3';
			break;
		case '3' :
			return '4';
			break;
		case '4' :
			return '5';
			break;
		case '5' :
			return '6';
			break;
		case '6' :
			return '7';
			break;
		case '7' :
			return '8';
			break;
		case '8' :
			return ($gender == 'F') ? 'YG' : 'YB';
			break;
		case 'KG' :
			return '1';
			break;
		case 'YG' :
			return ($regularschoolgrade == '10') ? 'XX' : 'YG';
			break;
		case 'YB' :
			return ($regularschoolgrade == '10') ? 'XX' : 'YB';
			break;
		default :
			return $issgrade;
	}
}
function iss_next_regularschoolgrade($regularschoolgrade) {
	switch ($regularschoolgrade) {
		case '1' :
			return '2';
			break;
		case '2' :
			return '3';
			break;
		case '3' :
			return '4';
			break;
		case '4' :
			return '5';
			break;
		case '5' :
			return '6';
			break;
		case '6' :
			return '7';
			break;
		case '7' :
			return '8';
			break;
		case '8' :
			return '9';
			break;
		case '9' :
			return '10';
			break;
		case '10' :
			return '11';
			break;
		case 'KG' :
			return '1';
			break;
		default :
			return $regularschoolgrade;
	}
}
function iss_sanitize_input($data) {
	$data = trim ( $data );
	$data = stripslashes ( $data );
	$data = htmlspecialchars ( $data );
	return $data;
}
/**
 * Function iss_get_user_option_list
 * Get user preferences array
 * 
 * @param
 *        	none
 * @return array of key/valueArray pairs
 *         Ex: $returnlog['iss_user_registrationyear'][0]
 *        
 */
function iss_get_user_option_list() {
	$user_id = get_current_user_id ();
	return get_user_meta ( $user_id );
}
/**
 * Function iss_set_user_option_list
 * Set User preferences
 * 
 * @param
 *        	changelog array of key/value pairs
 *        	Ex: $changelog = array ('iss_user_registrationyear' => '2010-2011');
 * @return none
 *
 */
function iss_set_user_option_list($changelog) {
	iss_write_log ( "iss_set_user_option_list" );
	iss_write_log ( $changelog );
	
	$user_id = get_current_user_id ();
	foreach ( $changelog as $field => $value ) {
		update_user_meta ( $user_id, $field, $value );
	}
}
/**
 * Function iss_get_registrationyear_list
 * Queries registration years in payment table
 * 
 * @param
 *        	none
 * @return array of strings
 *        
 */
function iss_get_registrationyear_list() {
	global $wpdb;
	$parents = iss_get_table_name ( "payment" );
	$query = "SELECT distinct(RegistrationYear)  FROM {$parents} order by  RegistrationYear";
	$result_set = $wpdb->get_results ( $query, ARRAY_A );
	return $result_set;
}
/**
 * Function iss_get_export_list
 * Queries parents & studnts in a registration period
 * 
 * @param
 *        	none
 * @return array of strings
 *        
 */
function iss_get_export_list($regyear) {
	global $wpdb;
	
	$parents = iss_get_table_name ( "parents" );
	$students = iss_get_table_name ( "students" );
	$query = "SELECT *  FROM {$parents} AS p INNER JOIN  {$students} AS s ON p.ParentID  = s.ParentID
    WHERE s.StudentStatus = 'active' and p.ParentStatus = 'active'
    and p.RegistrationYear = '{$regyear}' and s.RegistrationYear = '{$regyear}'";
	$result_set = $wpdb->get_results ( $query, ARRAY_A );
	
	return $result_set;
}
/**
 * Function iss_get_parents_complete_list
 * Queries parents in a registration period
 * 
 * @param
 *        	none
 * @return array of parent records
 *        
 */
function iss_get_parents_complete_list($regyear) {
	return iss_get_parents_list ( $regyear, '*' );
}
/**
 * Function iss_get_parents_list
 * Queries parents in a registration period
 * 
 * @param
 *        	none
 * @return array of parent records
 *        
 */
function iss_get_parents_list($regyear, $columns) {
	global $wpdb;
	
	$parents = iss_get_table_name ( "parents" );
	$query = "SELECT {$columns}  FROM {$parents} WHERE
    ParentStatus = 'active' and RegistrationYear = '{$regyear}'
    ORDER BY FatherLastName, FatherFirstName";
	$result_set = $wpdb->get_results ( $query, ARRAY_A );
	return $result_set;
}
/**
 * Function iss_get_startwith_parents_list
 * Queries active parents in a registration period starting with given keyword
 * 
 * @param
 *        	none
 * @return array of parent records
 *        
 */
function iss_get_startwith_parents_list($regyear, $columns, $keyword) {
	global $wpdb;
	
	$customers = iss_get_table_name ( "parents" );
	$query = "SELECT {$columns} FROM {$customers}
    WHERE FatherLastName LIKE '{$keyword}%' && RegistrationYear LIKE '{$regyear}%' && ParentStatus = 'active'
    ORDER BY FatherLastName, FatherFirstName";
	
	$result_set = $wpdb->get_results ( $query, ARRAY_A );
	return $result_set;
}
/**
 * Function iss_get_search_parents_list
 * Queries active parents in a registration period searcg with given keyword
 * 
 * @param
 *        	none
 * @return array of parent records
 *        
 */
function iss_get_search_parents_list($regyear, $columns, $keyword) {
	global $wpdb;
	
	$customers = iss_get_table_name ( "parents" );
	// if( strlen($keyword) > 3 )
	// {
	// $query = "
	// SELECT *,
	// MATCH(FatherFirstName, FatherLastName) AGAINST('{$keyword}*' IN BOOLEAN MODE) AS score
	// FROM {$customers}
	// WHERE MATCH(FatherFirstName, FatherLastName) AGAINST('{$keyword}*' IN BOOLEAN MODE)
	// ORDER BY score DESC";
	// }
	// else
	// {
	$query = "SELECT {$columns} FROM {$customers}
    WHERE (FatherFirstName LIKE '%{$keyword}%' OR FatherLastName LIKE '%{$keyword}%')
    && RegistrationYear LIKE '{$regyear}' && ParentStatus = 'active'
    ORDER BY FatherLastName, FatherFirstName";
	
	$result_set = $wpdb->get_results ( $query, ARRAY_A );
	return $result_set;
}
/**
 * Function iss_get_archived_parents_list
 * Queries archived parents in a registration period
 * 
 * @param
 *        	none
 * @return array of parent records
 *        
 */
function iss_get_archived_parents_list($regyear) {
	global $wpdb;
	
	$customers = iss_get_table_name ( "parents" );
	
	$query = "SELECT * FROM {$customers}
    WHERE RegistrationYear LIKE '{$regyear}%' && ParentStatus = 'inactive'
    ORDER BY FatherLastName, FatherFirstName";
	
	$result_set = $wpdb->get_results ( $query, ARRAY_A );
	return $result_set;
}

/**
 * Function iss_get_students_list
 * Queries active students in a registration period
 * 
 * @param
 *        	none
 * @return array of student records
 *        
 */
function iss_get_students_list($regyear, $columns) {
	global $wpdb;
	$table = iss_get_table_name ( "students" );
	$query = "SELECT {$columns}  FROM {$table} WHERE  StudentStatus = 'active'
    and RegistrationYear = '{$regyear}' ORDER BY StudentLastName, StudentFirstName";
	$result_set = $wpdb->get_results ( $query, ARRAY_A );
	return $result_set;
}
/**
 * Function iss_get_class_students_list
 * Queries students in a class
 * 
 * @param
 *        	none
 * @return array of student records
 *        
 */
function iss_get_class_students_list($regyear, $columns, $class) {
	global $wpdb;
	$table = iss_get_table_name ( "students" );
	$query = "SELECT {$columns}  FROM {$table}
    WHERE  ISSGrade LIKE '{$class}%' and StudentStatus = 'active' and RegistrationYear = '{$regyear}'
    ORDER BY StudentLastName, StudentFirstName";
	$result_set = $wpdb->get_results ( $query, ARRAY_A );
	return $result_set;
}
/**
 * Function iss_get_search_students_list
 * Queries students in a registration period in a class
 * 
 * @param
 *        	none
 * @return array of student records
 *        
 */
function iss_get_search_students_list($regyear, $columns, $keyword) {
	global $wpdb;
	$table = iss_get_table_name ( "students" );
	
	// if( strlen($keyword) > 3 )
	// {
	// $query = "
	// SELECT *,
	// MATCH(StudentFirstName, StudentLastName) AGAINST('{$keyword}*' IN BOOLEAN MODE) AS score
	// FROM {$customers}
	// WHERE MATCH(StudentFirstName, StudentLastName) AGAINST('{$keyword}*' IN BOOLEAN MODE)
	// and RegistrationYear LIKE '{$regyear}' and StudentStatus = 'active'
	// ORDER BY score DESC";
	// } else {
	
	$query = "SELECT {$columns}  FROM {$table}
    WHERE ((StudentFirstName LIKE '%{$keyword}%' OR StudentLastName LIKE '%{$keyword}%'))
    and RegistrationYear LIKE '{$regyear}' and StudentStatus = 'active'
    ORDER BY StudentLastName, StudentFirstName";
	$result_set = $wpdb->get_results ( $query, ARRAY_A );
	return $result_set;
}
/**
 * Function iss_field_type
 * Find field type substitue for wpdb input
 * 
 * @param
 *        	field name
 * @return string type substitute
 *        
 */
function iss_field_type($field) {
	$list = iss_fields_types ();
	if ($list [$field] == 'string')
		return '%s';
	if ($list [$field] == 'int')
		return '%d';
	if ($list [$field] == 'date')
		return '%s';
	if ($list [$field] == 'text')
		return '%s';
	if ($list [$field] == 'float')
		return '%f';
	if ($list [$field] == 'registrationyear')
		return '%s';
}
/**
 * Function iss_field_type
 * VAlidate field value as per its type
 * 
 * @param
 *        	field name, fieldvalue, reference of error array, field prefix for error
 * @return none
 *
 */
function iss_field_valid($field, $inputval, &$errors, $prefix) {
	$errorfield = $prefix . $field;
	if (($inputval == 'new') && (($field == 'ParentID') || ($field == 'StudentID')))
		return true;
	
	$fields_with_lengths = iss_fields_lengths ();
	$fields_with_types = iss_fields_types ();
	$displaynames = iss_field_displaynames ();
	// / REQUIRED FIELD ERRORS
	if (in_array ( $field, iss_required_fields () ) && empty ( $inputval )) {
		$errors [$errorfield] = "{$displaynames[$field]} is required.";
		return false;
	}
	
	// / VALIDATION ERRORS
	if (! empty ( $inputval )) {
		if (strlen ( $inputval ) > $fields_with_lengths [$field]) {
			$errors [$errorfield] = "{$displaynames[$field]} is too long ($fields_with_lengths[$field]).";
			return false;
		}
		
		if ($fields_with_types [$field] == 'int') {
			if (intval ( $inputval ) === 0) {
				$errors [$errorfield] = "{$displaynames[$field]} is not a valid integer.";
				return false;
			}
		}
		if ($fields_with_types [$field] == 'date') {
			$y = 0;
			$m = 0;
			$d = 0;
			$list = explode ( "-", $inputval );
			$count = count ( $list );
			if ($count > 0)
				$y = intval ( $list [0] );
			if ($count > 1)
				$m = intval ( $list [1] );
			if ($count > 2)
				$d = intval ( $list [2] );
			if (! checkdate ( $m, $d, $y )) {
				$errors [$errorfield] = "{$displaynames[$field]} is a not valid date (yyyy-mm-dd).";
				return false;
			}
		}
		
		if ($fields_with_types [$field] == 'float') {
			if (floatval ( $inputval ) == 0) {
				$errors [$errorfield] = "{$displaynames[$field]} is not a valid amount.";
				return false;
			}
		}
		if ($fields_with_types [$field] == 'registrationyear') {
			$list = explode ( "-", $inputval );
			$count = count ( $list );
			$y1int = 0;
			$y2int = 0;
			if ($count > 0)
				$y1int = intval ( $list [0] );
			if ($count > 1)
				$y2int = intval ( $list [1] );
			$y3int = $y1int + 1;
			if (($y1int === 0) || ($y2int === 0) || ($y3int != $y2int)) {
				$errors [$errorfield] = "{$displaynames[$field]} is not a valid.";
				return false;
			}
		}
		if ($fields_with_types [$field] == 'datetime') {
			$format = 'Y-m-d H:i:s';
			$input = trim ( $inputval );
			$time = strtotime ( $input );
			$newdate = date ( $format, $time );
			if ($newdate != $inputval)
				return false;
		}
	}
	return true;
}
function iss_changelogsetid() {
	return date ( 'Y-m-d H:i:s' ) . substr ( microtime (), 1, 9 );
}
/**
 * Function iss_changelog_insert
 * Insert change records
 * 
 * @param
 *        	changelog & tablename
 * @return none
 *
 */
function iss_changelog_insert($tablename, $changelog) {
	try {
		iss_write_log ( "iss_changelog_insert table:{$tablename}" );
		iss_write_log ( $changelog );
		$table = iss_get_table_name ( "changelog" );
		global $wpdb;
		$dsarray = array ();
		$typearray = array ();
		
		$dn = wp_get_current_user ()->display_name;
		$cid = iss_changelogsetid ();
		foreach ( $changelog as $sdata ) {
			if (($sdata ['FieldName'] == 'ParentID') || ($sdata ['FieldName'] == 'StudentID'))
				continue;
			
			$result = $wpdb->insert ( $table, array (
					"TableName" => $tablename,
					"ParentID" => $sdata ['ParentID'],
					"StudentID" => $sdata ['StudentID'],
					"FieldName" => $sdata ['FieldName'],
					"FieldValue" => $sdata ['FieldValue'],
					"ChangeSetID" => $cid,
					"ModifiedBy" => $dn 
			), array (
					"%s",
					"%d",
					"%d",
					"%s",
					"%s",
					"%s" 
			) );
		}
	} catch ( Exception $ex ) {
		iss_write_log ( "Error" . $ex . getMessage () );
	}
}
/**
 * Function iss_changelog_list
 * Array of change records
 * 
 * @param
 *        	parent id
 * @return Array of Changelod
 *        
 */
function iss_changelog_list($tablename, $parentid, $studentid) {
	$result = array ();
	global $wpdb;
	// $table = iss_get_table_name("parent");
	// $query = $wpdb->prepare("SELECT * FROM {$table} WHERE ParentID = %d LIMIT 1", $parentid);
	// $row = $wpdb->get_row($query, ARRAY_A);
	// if ($row != NULL) { $result[] = $row; }
	
	$table = iss_get_table_name ( "changelog" );
	$query = ($studentid == NULL) ? "SELECT * FROM {$table} WHERE ParentID = {$parentid} and  TableName = '{$tablename}' order by ChangelogID DESC" : "SELECT * FROM {$table} WHERE ParentID = {$parentid} and  TableName = '{$tablename}' and StudentID = {$studentid} order by ChangelogID DESC";
	$result_set = $wpdb->get_results ( $query, ARRAY_A );
	
	$changesetid = NULL;
	$changeset = NULL;
	$modifiedby = NULL;
	foreach ( $result_set as $change ) {
		// echo "<br>"; var_dump($change);
		$modifiedbyt = $change ['ModifiedBy'];
		$changesetidt = substr ( $change ['ChangeSetID'], 0, 19 );
		if (isset ( $changeset [$change ['FieldName']] ) || ($changesetidt != $changesetid) || ($modifiedbyt != $modifiedby)) {
			if ($changeset != NULL) {
				$result [] = $changeset;
			}
			$changeset = array ();
			$changeset ['ModifiedBy'] = $modifiedby = $modifiedbyt;
			$changeset ['ChangeSetID'] = $changesetid = $changesetidt;
		}
		$changeset [$change ['FieldName']] = $change ['FieldValue'];
	}
	if ($changeset != NULL)
		$result [] = $changeset;
	return $result;
}
function iss_create_changelog($parentid, $studentid, $fieldname, $inputval) {
	return array (
			"ParentID" => $parentid,
			'StudentID' => $studentid,
			"FieldName" => $fieldname,
			"FieldValue" => $inputval 
	);
}
function iss_get_new_parentid() {
	try {
		iss_write_log ( "iss_get_new_parentid" );
		$parentid = NULL;
		$table = iss_get_table_name ( "parent" );
		global $wpdb;
		$query = "SELECT MAX(ParentID)+1 AS ParentID FROM {$table} LIMIT 1";
		$result_set = $wpdb->get_row ( $query, ARRAY_A );
		
		if ($result_set != NULL) {
			$parentid = $result_set ['ParentID'];
		}
		if ($parentid == NULL)
			$parentid = 1;
		return $parentid;
	} catch ( Exception $ex ) {
		iss_write_log ( "Error" . $ex . getMessage () );
	}
	return 1;
}
/**
 * Function iss_parent_insert
 * Insert parent record
 * 
 * @param
 *        	with minimum required fields (RegistrationYear, FatherLastName, FatherFirstName)
 * @return parent id  / 0 indicating error
 *        
 */
function iss_parent_insert($sdata) {
	try {
		iss_write_log ( "iss_parent_insert" );
		
		if (! isset ( $sdata ['RegistrationYear'] ) || empty ( $sdata ['RegistrationYear'] ) || ! isset ( $sdata ['FatherLastName'] ) || empty ( $sdata ['FatherLastName'] ) || ! isset ( $sdata ['FatherFirstName'] ) || empty ( $sdata ['FatherFirstName'] )) {
			iss_write_log ( "Cannot insert parent due to minimum required fields" );
			return 0;
		}
		
		$table = iss_get_table_name ( "parent" );
		global $wpdb;
		if (! isset ( $sdata ['ParentID'] ) || empty ( $sdata ['ParentID'] ) || ($sdata ['ParentID'] == 'new')) {
			$sdata ['ParentID'] = iss_get_new_parentid ();
		}
		$sdata ['ParentStatus'] = 'active';
		
		$dsarray = array ();
		$typearray = array ();
		$changelog = array ();
		foreach ( iss_parent_table_fields () as $field ) {
			if ($field == 'RegistrationYear')
				continue;
			if (isset ( $sdata [$field] )) {
				$dsarray [$field] = $sdata [$field];
				$typearray [] = iss_field_type ( $field );
				$changelog [] = iss_create_changelog ( $sdata ['ParentID'], NULL, $field, $sdata [$field] );
			}
		}
		$dsarray ['created'] = current_time ( 'mysql' ); // date('d-m-Y H:i:s');
		$typearray [] = iss_field_type ( 'created' );
		
		// check again
		$query = "SELECT * FROM {$table} WHERE ParentID = {$sdata['ParentID']} LIMIT 1";
		$row = $wpdb->get_row ( $query, ARRAY_A );
		
		if ($row != NULL) {
			iss_write_log ( 'iss_parent_insert skipped' );
			if (iss_payment_insert ( $sdata ) == 1)
				return $sdata ['ParentID'];
		}
		
		iss_write_log ( $dsarray );
		$result = $wpdb->insert ( $table, $dsarray, $typearray );
		if ($result == 1) {
			iss_changelog_insert ( $table, $changelog );
			if (iss_payment_insert ( $sdata ) == 1)
				return $sdata ['ParentID'];
		}
	} catch ( Exception $ex ) {
		iss_write_log ( "Error" . $ex . getMessage () );
	}
	return 0;
}
/**
 * Function iss_payment_insert
 * Insert payment record
 * 
 * @param
 *        	with minimum required fields (RegistrationYear, ParentID)
 * @return 1 for success and 0 for no insert
 *        
 */function iss_payment_insert($sdata) {
	try {
		iss_write_log ( "iss_payment_insert" );
		$table = iss_get_table_name ( "payment" );
		global $wpdb;
		
		$dsarray = array ();
		$typearray = array ();
		$changelog = array ();
		foreach ( iss_payment_table_fields () as $field ) {
			if (isset ( $sdata [$field] )) {
				$dsarray [$field] = $sdata [$field];
				$typearray [] = iss_field_type ( $field );
				$changelog [] = iss_create_changelog ( $sdata ['ParentID'], NULL, $field, $sdata [$field] );
			}
		}
		$dsarray ['created'] = current_time ( 'mysql' ); // date('d-m-Y H:i:s');
		$typearray [] = iss_field_type ( 'created' );
		
		iss_write_log ( $dsarray );
		
		// check again
		$query = "SELECT * FROM {$table} WHERE ParentID = {$sdata['ParentID']}
        and RegistrationYear = '{$sdata['RegistrationYear']}' LIMIT 1";
		$row = $wpdb->get_row ( $query, ARRAY_A );
		
		if (NULL != $row) {
			iss_write_log ( 'iss_payment_insert skipped' );
			return 1;
		}
		
		$result = $wpdb->insert ( $table, $dsarray, $typearray );
		if (1 === $result)
			iss_changelog_insert ( $table, $changelog );
		return $result;
	} catch ( Exception $ex ) {
		iss_write_log ( "iss_payment_insert:Error" . $ex . getMessage () );
	}
	return 0;
}
/**
 * Function iss_parent_update
 * Update parent record
 * 
 * @param $sdata with
 *        	key required fields (RegistrationYear, ParentID)
 *        	$changed fields to update and record the change in log
 * @return 1 for success and 0 for no update
 *        
 */
function iss_parent_update($changedfields, $sdata) {
	try {
		if (! isset ( $sdata ['RegistrationYear'] ) || empty ( $sdata ['RegistrationYear'] ) || ! isset ( $sdata ['ParentID'] ) || empty ( $sdata ['ParentID'] )) {
			iss_write_log ( "Cannot update parent due to minimum required fields" );
			return 0;
		}
		
		iss_write_log ( "iss_parent_update" );
		
		$update = false;
		$changelog = array ();
		$dsarray = array ();
		$typearray = array ();
		$result = 0;
		foreach ( iss_parent_table_fields () as $field ) {
			if (in_array ( $field, $changedfields )) {
				$update = true;
				$dsarray [$field] = $sdata [$field];
				$typearray [] = iss_field_type ( $field );
				$changelog [] = iss_create_changelog ( $sdata ['ParentID'], NULL, $field, $sdata [$field] );
			}
		}
		if ($update) {
			iss_write_log ( "paernt table update" );
			iss_write_log ( $dsarray );
			$table = iss_get_table_name ( "parent" );
			global $wpdb;
			$result = $wpdb->update ( $table, $dsarray, array (
					'ParentID' => $sdata ['ParentID'] 
			), $typearray, array (
					'%d' 
			) );
			if (1 === $result) {
				iss_changelog_insert ( $table, $changelog );
			}
		}
		$result |= iss_payment_update ( $changedfields, $sdata );
		return $result;
	} catch ( Exception $ex ) {
		iss_write_log ( "Error" . $ex . getMessage () );
	}
	return 0;
}
/**
 * Function iss_payment_update
 * Update parent record
 * 
 * @param $sdata with
 *        	key required fields (RegistrationYear, ParentID)
 *        	$changed fields to update and record the change in log
 * @return 1 for success and 0 for no update
 *        
 */
function iss_payment_update($changedfields, $sdata) {
	if (! isset ( $sdata ['RegistrationYear'] ) || empty ( $sdata ['RegistrationYear'] ) || ! isset ( $sdata ['ParentID'] ) || empty ( $sdata ['ParentID'] )) {
		iss_write_log ( "Cannot update payment due to minimum required fields" );
		return 0;
	}
	
	iss_write_log ( "iss_payment_update" );
	$update = false;
	$changelog = array ();
	$dsarray = array ();
	$typearray = array ();
	foreach ( iss_payment_table_fields () as $field ) {
		if (in_array ( $field, $changedfields )) {
			$result = - 1;
			$update = true;
			$dsarray [$field] = $sdata [$field];
			$typearray [] = iss_field_type ( $field );
			$changelog [] = iss_create_changelog ( $sdata ['ParentID'], NULL, $field, $sdata [$field] );
		}
	}
	if ($update) {
		iss_write_log ( "payment table update" );
		$table = iss_get_table_name ( "payment" );
		global $wpdb;
		iss_write_log ( $dsarray );
		$result = $wpdb->update ( $table, $dsarray, array (
				'ParentID' => $sdata ['ParentID'],
				'RegistrationYear' => $sdata ['RegistrationYear'] 
		), $typearray, array (
				'%d',
				'%s' 
		) );
		if (1 === $result)
			iss_changelog_insert ( $table, $changelog );
		return $result;
	}
	return 0;
}
function iss_get_new_studentid() {
	iss_write_log ( "iss_get_new_studentid" );
	$studentid = NULL;
	$table = iss_get_table_name ( "student" );
	global $wpdb;
	$query = "SELECT MAX(StudentID)+1 AS StudentID FROM {$table} LIMIT 1";
	$result_set = $wpdb->get_row ( $query, ARRAY_A );
	
	if ($result_set != NULL) {
		$studentid = $result_set ['StudentID'];
	}
	if ($studentid == NULL)
		$studentid = 1;
	return $studentid;
}
/**
 * Function iss_student_insert
 * Insert parent record
 * 
 * @param
 *        	with minimum required fields (ParentID, RegistrationYear, StudentLastName, StudentFirstName)
 * @return student id
 *        
 */
function iss_student_insert($sdata) {
	try {
		if (! isset ( $sdata ['ParentID'] ) || empty ( $sdata ['ParentID'] ) || ($sdata ['ParentID'] == 'new') || ! isset ( $sdata ['RegistrationYear'] ) || empty ( $sdata ['RegistrationYear'] ) || ! isset ( $sdata ['StudentLastName'] ) || empty ( $sdata ['StudentLastName'] ) || ! isset ( $sdata ['StudentFirstName'] ) || empty ( $sdata ['StudentFirstName'] )) {
			iss_write_log ( "Cannot insert student due to minimum required fields" );
			return 0;
		}
		
		iss_write_log ( "iss_student_insert" );
		$table = iss_get_table_name ( "student" );
		global $wpdb;
		
		if (! isset ( $sdata ['StudentID'] ) || empty ( $sdata ['StudentID'] ) || ($sdata ['StudentID'] == 'new')) {
			$sdata ['StudentID'] = iss_get_new_studentid ();
		}
		$sdata ['StudentStatus'] = 'active';
		
		$dsarray = array ();
		$typearray = array ();
		$changelog = array ();
		foreach ( iss_student_table_fields () as $field ) {
			if (isset ( $sdata [$field] )) {
				$dsarray [$field] = $sdata [$field];
				$typearray [] = iss_field_type ( $field );
				$changelog [] = iss_create_changelog ( $sdata ['ParentID'], $sdata ['StudentID'], $field, $sdata [$field] );
			}
		}
		$dsarray ['created'] = current_time ( 'mysql' ); // date('d-m-Y H:i:s');
		$typearray [] = iss_field_type ( 'created' );
		
		iss_write_log ( $dsarray );
		
		// check again
		$query = "SELECT * FROM {$table} WHERE  StudentID = {$sdata['StudentID']} LIMIT 1";
		$row = $wpdb->get_row ( $query, ARRAY_A );
		if ($row != NULL) {
			iss_write_log ( 'iss_student_insert skipped' );
			if (iss_registration_insert ( $sdata ) === 1)
				return $sdata ['StudentID'];
		}
		
		$result = $wpdb->insert ( $table, $dsarray, $typearray );
		if ($result == 1) {
			iss_changelog_insert ( $table, $changelog );
			if (iss_registration_insert ( $sdata ) === 1)
				return $sdata ['StudentID'];
		}
	} catch ( Exception $ex ) {
		iss_write_log ( "Error" . $ex . getMessage () );
	}
	return 0;
}
function iss_registration_insert($sdata) {
	try {
		if (! isset ( $sdata ['RegistrationYear'] ) || empty ( $sdata ['RegistrationYear'] ) || ! isset ( $sdata ['StudentID'] ) || empty ( $sdata ['StudentID'] )) {
			iss_write_log ( "Cannot insert student registration due to minimum required fields" );
			return 0;
		}
		
		iss_write_log ( "iss_registration_insert" );
		$table = iss_get_table_name ( "registration" );
		global $wpdb;
		
		$dsarray = array ();
		$typearray = array ();
		$changelog = array ();
		foreach ( iss_registration_table_fields () as $field ) {
			if (isset ( $sdata [$field] )) {
				$dsarray [$field] = $sdata [$field];
				$typearray [] = iss_field_type ( $field );
				$changelog [] = iss_create_changelog ( $sdata ['ParentID'], $sdata ['StudentID'], $field, $sdata [$field] );
			}
		}
		$dsarray ['created'] = current_time ( 'mysql' ); // date('d-m-Y H:i:s');
		$typearray [] = iss_field_type ( 'created' );
		
		iss_write_log ( $dsarray );
		
		// check again
		$query = "SELECT * FROM {$table} WHERE StudentID = {$sdata['StudentID']}
        and RegistrationYear = '{$sdata['RegistrationYear']}' LIMIT 1";
		$row = $wpdb->get_row ( $query, ARRAY_A );
		
		if (NULL != $row) {
			iss_write_log ( 'iss_registration_insert skipped' );
			return 1;
		}
		
		$result = $wpdb->insert ( $table, $dsarray, $typearray );
		if (1 === $result) {
			iss_changelog_insert ( $table, $changelog );
			return $result;
		}
	} catch ( Exception $ex ) {
		iss_write_log ( "Error" . $ex . getMessage () );
	}
	return 0;
}
/**
 * Function iss_student_update
 * Update student record
 * 
 * @param $sdata with
 *        	key required fields (RegistrationYear, ParentID, StudentID)
 *        	$changed fields to update and record the change in log
 * @return 1 for success and 0 for no update
 *        
 */
function iss_student_update($changedfields, $sdata) {
	try {
		if (! isset ( $sdata ['ParentID'] ) || empty ( $sdata ['ParentID'] ) || ($sdata ['ParentID'] == 'new') || ! isset ( $sdata ['StudentID'] ) || empty ( $sdata ['StudentID'] ) || ($sdata ['StudentID'] == 'new')) {
			iss_write_log ( "Cannot update student due to minimum required fields" );
			return 0;
		}
		
		iss_write_log ( "iss_student_update" );
		
		$update = false;
		$changelog = array ();
		$dsarray = array ();
		$typearray = array ();
		$result = 0;
		foreach ( iss_student_table_fields () as $field ) {
			if (in_array ( $field, $changedfields )) {
				$update = true;
				$dsarray [$field] = $sdata [$field];
				$typearray [] = iss_field_type ( $field );
				$changelog [] = iss_create_changelog ( $sdata ['ParentID'], $sdata ['StudentID'], $field, $sdata [$field] );
			}
		}
		if ($update) {
			iss_write_log ( "student table update" );
			iss_write_log ( $dsarray );
			$table = iss_get_table_name ( "student" );
			global $wpdb;
			$result = $wpdb->update ( $table, $dsarray, array (
					'StudentID' => $sdata ['StudentID'] 
			), $typearray, array (
					'%d' 
			) );
			if (1 === $result) {
				iss_changelog_insert ( $table, $changelog );
			}
		}
		$result |= iss_registration_update ( $changedfields, $sdata );
		return $result;
	} catch ( Exception $ex ) {
		iss_write_log ( "Error" . $ex . getMessage () );
	}
	return 0;
}
/**
 * Function iss_registration_update
 * Update registration student record
 * 
 * @param $sdata with
 *        	key required fields (RegistrationYear, StudentID)
 *        	$changed fields to update and record the change in log
 * @return 1 for success and 0 for no update
 *        
 */
function iss_registration_update($changedfields, $sdata) {
	if (! isset ( $sdata ['RegistrationYear'] ) || empty ( $sdata ['RegistrationYear'] ) || ! isset ( $sdata ['StudentID'] ) || empty ( $sdata ['StudentID'] ) || ($sdata ['StudentID'] == 'new')) {
		iss_write_log ( "Cannot update student registration due to minimum required fields" );
		return 0;
	}
	
	iss_write_log ( "iss_student_update" );
	$update = false;
	$changelog = array ();
	$dsarray = array ();
	$typearray = array ();
	foreach ( array (
			"ISSGrade",
			"RegularSchoolGrade" 
	) as $field ) {
		if (in_array ( $field, $changedfields )) {
			$update = true;
			$dsarray [$field] = $sdata [$field];
			$typearray [] = iss_field_type ( $field );
			$changelog [] = iss_create_changelog ( $sdata ['ParentID'], $sdata ['StudentID'], $field, $sdata [$field] );
		}
	}
	if ($update) {
		iss_write_log ( "registration table update" );
		$table = iss_get_table_name ( "registration" );
		iss_write_log ( $dsarray );
		global $wpdb;
		$result = $wpdb->update ( $table, $dsarray, array (
				'StudentID' => $sdata ['StudentID'],
				'RegistrationYear' => $sdata ['RegistrationYear'] 
		), $typearray, array (
				'%d',
				'%s' 
		) );
		if (1 === $result) {
			iss_changelog_insert ( $table, $changelog );
			return $result;
		}
	}
	return 0;
}
/**
 * Function iss_get_student_by_studentid
 * Get Student record by StudentID
 * 
 * @param
 *        	StudentID
 * @return Student record
 *        
 */
function iss_get_student_by_studentid($studentid, $regyear) {
	try {
		// echo "br/> get student {$studentid} , {$regyear}"; // ISS TEST
		global $wpdb;
		$table = iss_get_table_name ( "students" );
		$query = $wpdb->prepare ( "SELECT * FROM {$table} WHERE RegistrationYear = '{$regyear}' " . // StudentStatus = 'active'
" and StudentID = %d LIMIT 1", $studentid );
		$row = $wpdb->get_row ( $query, ARRAY_A );
		if ($row != NULL) {
			return $row;
		}
	} catch ( Exception $ex ) {
		iss_write_log ( "Error" . $ex . getMessage () );
	}
	return NULL;
}
/**
 * Function iss_delete_parent_by_id (Testing Only)
 * @param	ParentID 
 * @return 1 success & 0 for failure
 */
function iss_delete_parent_by_parentid($parentid) {
	global $wpdb;
	$table = iss_get_table_name ( "parent" );
	$result = $wpdb->delete ( $table, array ( 'ParentID' => $parentid  ), array ( '%d' ) );
	return $result;
}
/**
 * Function iss_delete_student_by_studentid (Testing Only)
 * @param	StudentID 
 * @return 1 success & 0 for failure
 */
function iss_delete_student_by_studentid($studentid) {
	global $wpdb;
	$table = iss_get_table_name ( "student" );
	$result = $wpdb->delete ( $table, array ( 'StudentID' => $studentid ), array ( '%d'  ) );
	return $result;
}
/**
 * Function iss_delete_students_by_parentid (Testing Only)
 * Delete student records by ParentID
 * 
 * @param PareentID 
 * @return 1 success & 0 for failure
 */
function iss_delete_students_by_parentid($parentid) {
	global $wpdb;
	$table = iss_get_table_name ( "student" );
	$result = $wpdb->delete ( $table, array ('ParentID' => $parentid ), array ('%d' ) );
	return $result;
}
/**
 * Function iss_delete_changelog_by_parentid (Testing Only)
 * Get change record by ParentID
 * 
 * @paramParentID
 *  @return 1 success & 0 for failure
 */
function iss_delete_changelog_by_parentid($parentid) {
	global $wpdb;
	$table = iss_get_table_name ( "changelog" );
	$result = $wpdb->delete ( $table, array ('ParentID' => $parentid ), array ('%d' ) );
	return $result;
}
/**
 * Function
 * Get student records
 * 
 * @param
 *        	ParentID & RegistrationYear
 * @return student records array or NULL
 *        
 */
function iss_get_students_by_parentid($parentid, $regyear) {
	global $wpdb;
	$table = iss_get_table_name ( "students" );
	$query = $wpdb->prepare ( "SELECT * FROM {$table} WHERE " . " RegistrationYear = '{$regyear}' and ParentID = %d ORDER BY StudentID", $parentid );
	$result_set = $wpdb->get_results ( $query, ARRAY_A );
	
	if ($result_set != NULL) {
		return $result_set;
	}
	return NULL;
}
/**
 * Function iss_get_parent_by_parentid
 * Get parent record by ParentID
 * 
 * @param
 *        	ParentID and registration year
 * @return parent record or NULL
 *        
 */
function iss_get_parent_by_parentid($parentid, $regyear) {
	try {
		global $wpdb;
		$parents = iss_get_table_name ( "parents" );
		$query = $wpdb->prepare ( "SELECT * FROM {$parents} WHERE" . " RegistrationYear = '{$regyear}' and ParentStatus = 'active' and ParentID = %d LIMIT 1", $parentid );
		$row = $wpdb->get_row ( $query, ARRAY_A );
		if ($row != NULL) {
			return $row;
		}
	} catch ( Exception $ex ) {
		iss_write_log ( "Error" . $ex . getMessage () );
	}
	return NULL;
}
/**
 * Function iss_get_parent_and_payment_by_id
 * Get parent record by ID
 * 
 * @param
 *        	ID (auto increment column of the table)
 * @return parent record or NULL
 *        
 */
function iss_get_parent_and_payment_by_id($id) {
	global $wpdb;
	$parents = iss_get_table_name ( "parents" );
	$query = $wpdb->prepare ( "SELECT * FROM {$parents} WHERE ID = %d LIMIT 1", $id );
	$row = $wpdb->get_row ( $query, ARRAY_A );
	if ($row != NULL) {
		return $row;
	}
	return NULL;
}
/**
 * Function iss_get_parent_by_code
 * Get parent record by registration code
 * 
 * @param
 *        	code
 * @return parent record or NULL
 *        
 */
function iss_get_parent_by_code($code) {
	global $wpdb;
	$date = current_time ( 'mysql' );
	
	$parents = iss_get_table_name ( "parents" );
	$query = $wpdb->prepare ( "SELECT * FROM {$parents} WHERE RegistrationCode = '%s' and
    '{$date}' <= RegistrationExpiration and RegistrationComplete = 'Open' LIMIT 1", $code );
	$row = $wpdb->get_row ( $query, ARRAY_A );
	if ($row != NULL) {
		return $row;
	}
	return NULL;
}
function iss_get_parent_registration_code($id) {
	global $wpdb;
	
	$table = iss_get_table_name ( "parents" );
	$code = iss_registration_code ();
	$edate = iss_registration_expirydate ();
	$result = $wpdb->update ( $table, array (
			'RegistrationCode' => $code,
			'RegistrationExpiration' => $edate,
			'RegistrationComplete' => 'Open' 
	), array (
			'ID' => $id 
	), array (
			'%s',
			'%s',
			'%s' 
	), array (
			'%d' 
	) );
	
	return $code;
}
/**
 * Function iss_get_table_name
 * Returns the table name
 * 
 * @param
 *        	table alias name
 * @return table name string
 *        
 */
function iss_get_table_name($name) {
	global $wpdb;
	
	if ($name == "registration") {
		return $wpdb->prefix . "iss_registration";
	} elseif ($name == "changelog") {
		return $wpdb->prefix . "iss_changelog";
	} elseif ($name == "parents") {
		return $wpdb->prefix . "iss_parents";
	} elseif ($name == "students") {
		return $wpdb->prefix . "iss_students";
	} elseif ($name == "payment") {
		return $wpdb->prefix . "iss_payment";
	} elseif ($name == "parent") {
		return $wpdb->prefix . "iss_parent";
	} elseif ($name == "student") {
		return $wpdb->prefix . "iss_student";
	} else {
		iss_write_log ( "Unknown table name {$name}" );
		return NULL;
	}
}
/**
 * Helper function
 */
function iss_get_table_fields($name) {
	global $wpdb;
	
	if ($name == "registration") {
		return iss_registration_table_fields ();
	} elseif ($name == "payment") {
		return iss_payment_table_fields ();
	} elseif ($name == "parent") {
		return iss_parent_table_fields ();
	} elseif ($name == "student") {
		return iss_student_table_fields ();
	} else {
		iss_write_log ( "Unknown table name {$name}" );
		return NULL;
	}
}
function iss_quote_all_array($values) {
	foreach ( $values as $key => $value )
		if (is_array ( $value ))
			$values [$key] = iss_quote_all_array ( $value );
		else
			$values [$key] = iss_quote_all ( $value );
	return $values;
}
function iss_quote_all($value) {
	global $wpdb;
	
	if (is_null ( $value ))
		return "NULL";
	
	$value = "\"" . $wpdb->escape ( $value ) . "\"";
	return $value;
}
/**
 * Function iss_archive_family
 * Update parent & students records inactive
 * 
 * @param
 *        	ID (auto increment column of the table)
 * @return 1 for success
 *        
 */
function iss_archive_family($parentViewID) {
	global $wpdb;
	$parents = iss_get_table_name ( "parents" );
	$students = iss_get_table_name ( "students" );
	$query = $wpdb->prepare ( "SELECT * FROM {$parents} WHERE ID = %d LIMIT 1", $parentViewID );
	$row = $wpdb->get_row ( $query, ARRAY_A );
	
	if ($row != NULL) {
		$result = $wpdb->update ( $parents, array (
				'ParentStatus' => 'inactive' 
		), array (
				'ParentViewID' => $row ['ParentViewID'] 
		), array (
				'%s' 
		), array (
				'%d' 
		) );
		
		$result = $wpdb->update ( $students, array (
				'StudentStatus' => 'inactive' 
		), array (
				'ParentID' => $row ['ParentID'],
				'RegistrationYear' => $row ['RegistrationYear'] 
		), array (
				'%s' 
		), array (
				'%d',
				'%s' 
		) );
		return $result;
	}
	return 0;
}
/**
 * Function iss_unarchive_family
 * Update parent & students records active
 * 
 * @param
 *        	ID (auto increment column of the table)
 * @return 1 for success
 *        
 */
function iss_unarchive_family($parentViewID) {
	global $wpdb;
	$parents = iss_get_table_name ( "parents" );
	$students = iss_get_table_name ( "students" );
	$query = $wpdb->prepare ( "SELECT * FROM {$parents} WHERE ID = %d LIMIT 1", $parentViewID );
	$row = $wpdb->get_row ( $query, ARRAY_A );
	
	if ($row != NULL) {
		$result = $wpdb->update ( $parents, array (
				'ParentStatus' => 'active' 
		), array (
				'ParentViewID' => $row ['ParentViewID'] 
		), array (
				'%s' 
		), array (
				'%d' 
		) );
		
		$result = $wpdb->update ( $students, array (
				'StudentStatus' => 'active' 
		), array (
				'ParentID' => $row ['ParentID'],
				'RegistrationYear' => $row ['RegistrationYear'] 
		), array (
				'%s' 
		), array (
				'%d',
				'%s' 
		) );
		return $result;
	}
	return 0;
}
/**
 * Function iss_registration_expirydate
 * Returns an expiry date using the admin preference for open registration days
 * 
 * @param
 *        	none
 * @return expirty date
 *        
 */
function iss_registration_expirydate() {
	$date = current_time ( 'mysql' );
	$delay = ' + ' . iss_adminpref_openregistrationdays () . 'days';
	$cur_dat = date ( 'Y-m-d H:i:s', strtotime ( $date . $delay ) );
	return $cur_dat;
}
function iss_registration_code() {
	return getToken ( 20 );
}
function crypto_rand_secure($min, $max) {
	$range = $max - $min;
	if ($range < 1)
		return $min; // not so random...
	$log = ceil ( log ( $range, 2 ) );
	$bytes = ( int ) ($log / 8) + 1; // length in bytes
	$bits = ( int ) $log + 1; // length in bits
	$filter = ( int ) (1 << $bits) - 1; // set all lower bits to 1
	do {
		$rnd = hexdec ( bin2hex ( openssl_random_pseudo_bytes ( $bytes ) ) );
		$rnd = $rnd & $filter; // discard irrelevant bits
	} while ( $rnd > $range );
	return $min + $rnd;
}
function getToken($length) {
	$token = "";
	$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
	$codeAlphabet .= "0123456789";
	$max = strlen ( $codeAlphabet ); // edited
	
	for($i = 0; $i < $length; $i ++) {
		$token .= $codeAlphabet [crypto_rand_secure ( 0, $max - 1 )];
	}
	
	return $token;
}
/**
 * FUNCTION iss_process_newstudentrequest
 *
 * @param
 *        	registration year, post:input from client, studentnew: fill in input, errors: processing errors
 * @return 1 for update success, 0 for update failure
 *        
 */
function iss_process_newstudentrequest(&$post, &$studentnew, &$errors) {
	$tabname = 'student';
	$required_fields = iss_get_requiredfields_by_tabname ( $tabname );
	$tab_fields = iss_get_tabfields_by_tabname ( $tabname );
	$displaynames = iss_field_displaynames ();
	foreach ( $required_fields as $rf ) {
		if (! isset ( $post [$rf] ))
			$errors [$rf] = $displaynames [$rf] . " is required.";
	}
	
	foreach ( $tab_fields as $fieldname ) {
		if (isset ( $post [$fieldname] )) {
			$inputval = iss_sanitize_input ( $post [$fieldname] );
			$studentnew [$fieldname] = $inputval;
			iss_field_valid ( $fieldname, $inputval, $errors, 'new' );
		}
	} // for tab fields
	
	if (empty ( $errors )) {
		;
		$studentid = iss_student_insert ( $studentnew );
		if (0 < $studentid) {
			$studentnew = array ();
			return $studentid;
		}
	}
	return 0;
}
function iss_process_updatestudentrequest(&$studentrow, &$post, &$errors) {
	$tabname = 'student';
	$required_fields = iss_get_requiredfields_by_tabname ( $tabname );
	$tab_fields = iss_get_tabfields_by_tabname ( $tabname );
	$displaynames = iss_field_displaynames ();
	foreach ( $required_fields as $rf ) {
		if (! isset ( $post [$rf] ))
			$errors [$rf] = $displaynames [$rf] . " is required.";
	}
	
	$changedfields = array ();
	foreach ( $tab_fields as $fieldname ) {
		if (isset ( $post [$fieldname] )) {
			$inputval = iss_sanitize_input ( $post [$fieldname] );
			if ((strcmp ( $inputval, $studentrow [$fieldname] ) != 0) && iss_field_valid ( $fieldname, $inputval, $errors, $post ['StudentID'] )) {
				iss_write_log ( "studentchanged: SID:{$post['StudentID']} FLD:{$fieldname} OLD:{$studentrow[$fieldname]}  NEW:{$inputval}" );
				$changedfields [] = $fieldname; // record changed fields
			}
			$studentrow [$fieldname] = $inputval; // modify the student row
		}
	} // for tab fields
	
	if (empty ( $errors )) {
		if (! empty ( $changedfields )) {
			return iss_student_update ( $changedfields, $studentrow ); /* EXISTING STUDENT UPDATE */
		}
		// else { $errorstring = '* No changes to save.'; }
	}
	
	return 0;
}
/**
 * FUNCTION iss_process_newparentrequest
 *
 * @param
 *        	registration year, post:input from client, issparent: fill in input, errors: processing errors
 * @return 1 for update success, 0 for update failure
 *        
 */
function iss_process_newparentrequest(&$post, &$issparent, &$errors) {
	iss_write_log('iss_process_newparentrequest');
	$required_fields = iss_parent_required_tabfields ();
	$tab_fields = iss_parent_tabfields ();
	$displaynames = iss_field_displaynames ();
	foreach ( $required_fields as $rf ) {
		if (! isset ( $post [$rf] ))
			$errors [$rf] = $displaynames [$rf] . " is required.";
	}
	
	foreach ( $tab_fields as $fieldname ) {
		if (isset ( $post [$fieldname] )) {
			$inputval = iss_sanitize_input ( $post [$fieldname] );
			$issparent [$fieldname] = $inputval;
			iss_field_valid ( $fieldname, $inputval, $errors, '' );
		}
	} // for tab fields
	
	if (empty ( $errors )) {
		$parentid = iss_parent_insert ( $issparent );
		if (0 < $parentid) {
			return $parentid;
		}
	}
	return "new";
}
/**
 * FUNCTION iss_process_updateparentrequest
 *
 * @param
 *        	tabname, parentid, issparent:existing parent record where change applied, post:input from client, errors: processing errors
 * @return 1 for update success, 0 for update failure
 *        
 */
function iss_process_updateparentrequest($tabname, &$issparent, &$post, &$errors) {
	$required_fields = iss_get_requiredfields_by_tabname ( $tabname );
	$tab_fields = iss_get_tabfields_by_tabname ( $tabname );
	$displaynames = iss_field_displaynames ();
	foreach ( $required_fields as $rf ) {
		if (! isset ( $post [$rf] ))
			$errors [$rf] = $displaynames [$rf] . " is required.";
	}
	
	$changedfields = array ();
	foreach ( $tab_fields as $fieldname ) {
		if (isset ( $post [$fieldname] )) {
			$inputval = iss_sanitize_input ( $post [$fieldname] );
			if ((strcmp ( $inputval, $issparent [$fieldname] ) != 0) && iss_field_valid ( $fieldname, $inputval, $errors, '' )) {
				iss_write_log ( "parentchanged: PID:{$post['ParentID']} FLD:{$fieldname} OLD:{$issparent[$fieldname]}  NEW:{$inputval}" );
				$changedfields [] = $fieldname; // record changed fields
			}
			$issparent [$fieldname] = $inputval; // modify parent record
		}
	} // for tab fields
	
	if (empty ( $errors )) {
		if (! empty ( $changedfields )) {
			return iss_parent_update ( $changedfields, $issparent ); // PARENT UPDATE
		} else {
			return 1; /* No changes to save */
		}
	}
	return 0;
}
function iss_write_changelog_vertical($tablename, $parentid, $studentid) {
	$changeset = iss_changelog_list ( iss_get_table_name ( $tablename ), $parentid, $studentid );
	echo "<br>Table:{$tablename}<table border=1><tr><th>Change Details</th>";
	foreach ( $changeset as $changelog ) {
		echo "<td>{$changelog['ChangeSetID']} <br/>By {$changelog['ModifiedBy']}</td>";
	}
	echo "</tr>";
	foreach ( iss_get_table_fields ( $tablename ) as $field ) {
		echo "<tr><th>{$field}</th>";
		foreach ( $changeset as $changelog ) {
			echo "<td>";
			if ($field == 'ParentID')
				echo $parentid;
			if ($field == 'StudentID')
				echo $studentid;
			foreach ( $changelog as $key => $value ) {
				if ($field == $key) {
					echo $value;
				}
			}
			echo "</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
}
function iss_write_changelog_horizontal($tablename, $parentid, $studentid) {
	$changeset = iss_changelog_list ( iss_get_table_name ( $tablename ), $parentid, $studentid );
	echo "<br>Table:{$tablename}<table border=1>";
	echo "<tr>";
	foreach ( iss_get_table_fields ( $tablename ) as $field ) {
		echo "<th>{$field}</th>";
	}
	echo "</tr>";
	foreach ( $changeset as $changelog ) {
		echo "<tr>";
		foreach ( iss_parent_table_fields () as $field ) {
			echo "<td>";
			if ($field == 'ParentID')
				echo $parentid;
			if ($field == 'StudentID')
				echo $studentid;
			foreach ( $changelog as $key => $value ) {
				if ($field == $key) {
					echo $value;
				}
			}
			echo "</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
}