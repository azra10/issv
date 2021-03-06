<?php
/*
 * Plugin Name: 999. ISS Unit Test
 * Description: UNIT TEST THE CODE AS MUCH AS POSSIBLE
 * Version: 1.0.0
 * Author: Azra Syed
 * Text Domain: iss_unittest
 */
class ISS_UnitTestPlugin extends ISS_UnitTest1 {
	
	/* Start up */
	public function __construct() {
		add_action ( 'admin_menu', array (
				$this,
				'add_plugin_page' 
		) );
		add_action ( 'init', array (
				$this,
				'add_plugin_page_action' 
		) );
		add_action ( 'admin_enqueue_scripts', 'load_custom_issv_style' );
	}
	public function add_plugin_page() {
		// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page ( 'iss_unittest', 'Unit Test', 'iss_test', 'test_home', array (
				$this,
				'tests_page' 
		), 'dashicons-lightbulb', 999 );
	}
	public function add_plugin_page_action() {
		// / IF FORM POST REQUEST
		if (isset ( $_POST ['_wpnonce-iss-test-cases'] )) {
			check_admin_referer ( 'iss-test-cases', '_wpnonce-iss-test-cases' );
			
			if (isset ( $_POST ['submit'] )) {
				$this->submit = $_POST ['submit'];
			}
		} // form post request
	}

	public function iss_unit_test_failed($msg) {	
		echo "</th> <td><i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">" . $msg . "</span> </td></tr>";
		$this->failedtestcount++; 
	}
	public function iss_unit_test_start($title, $num) {				
			echo '<tr> <td> <button type="submit" name="submit" class="button-primary" value="' . $num . '"> 
			Run Test</button></td><th><label>' . $num . '. ' .  $title . ' </label>';
			if ( ($this->submit == $num) || ($this->submit === 'all')){	return true; }
			else {echo '</th> <td></td></tr>';return false;	}
	}
	public function iss_unit_test_pass() {
		echo "</th> <td><span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span></td> </tr>";
	}
	public function iss_delete_test_data() {
		$parentid = 999999;
		$studentid = 999999;
		iss_delete_student_by_studentid ( $studentid );
		iss_delete_parent_by_parentid ( $parentid );
		iss_delete_changelog_by_parentid ( $parentid );
	}
	public function iss_add_test_data() {
		echo '<tr> <td colspan=3>';
		$regyear = iss_registration_period ();
		$parentid = 999999;
		$studentid = 999999;
		if ($regyear === NULL) {
			echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1. NULL Registration Year </span>";
			echo '</td></tr>';
			$this->failedtestcount++; return;
		}
		// create parent record
		$sdata = array ();
		
		$sdata ['RegistrationYear'] = $regyear;
		$sdata ['ParentID'] = $parentid;
		$sdata ['ParentStatus'] = 'active';
		$sdata ['SchoolEmail'] = 'Father';
		$sdata ['FatherFirstName'] = 'TestFatherFirstName';
		$sdata ['FatherLastName'] = 'TestFatherLastName';
		$sdata ['FatherEmail'] = 'father@father.com';
		$sdata ['FatherCellPhone'] = 'fathercell';
		$sdata ['MotherFirstName'] = 'TestMotherFirstName';
		$sdata ['MotherLastName'] = 'TestMotherLastName';
		$sdata ['MotherEmail'] = 'mother@mother.com';
		$sdata ['MotherCellPhone'] = 'mothercell';
		$sdata ['PaidInFull'] = 'No';
		
		$parentid = iss_parent_insert ( $sdata );
		if ($parentid !== 999999) {
			echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> ParentID : {$parentid}, error on iss_parent_insert </span>";
			echo '</td></tr>';
			$this->failedtestcount++; return;
		}
		
		// create student record
		$sdata ['ParentID'] = $parentid;
		$sdata ['StudentID'] = $studentid;
		$sdata ['RegistrationYear'] = $regyear;
		$sdata ['StudentFirstName'] = 'TestStudentFirstName';
		$sdata ['StudentLastName'] = 'TestStudentLastName';
		$sdata ['StudentBirthDate'] = '2000-06-06';
		$sdata ['StudentGender'] = 'M';
		$sdata ['StudentStatus'] = 'active';
		$sdata ['RegularSchoolGrade'] = '3';
		$sdata ['ISSGrade'] = '2';
		
		$studentid = iss_student_insert ( $sdata );
		if ($studentid !== 999999) {
			echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> StudentID : {$studentid}, error on iss_student_insert </span>";
		}
		echo '</td></tr>';
	}
	public function iss_initialize() {
		if ($this->submit === 'addregyear') {
			iss_set_user_option_list ( 'iss_user_registrationyear', '2016-2017' );
		} else if ($this->submit === 'deleteregyear') {
			iss_set_user_option_list ( 'iss_user_registrationyear', '' );
		} else if ($this->submit === 'addtestdata') {
			iss_add_test_data ();
		} else if ($this->submit === 'deletetestdata') {
			iss_delete_test_data ();
		}
	} // iss_initialize
	public function tests_page() {
		if (isset ( $_POST ['submit'] )) { $this->submit = $_POST ['submit']; }
		if (! iss_current_user_can_runtest())
			wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'iss_unittest_text' ) );

		echo '<div class="wrap"> <h2>Unit Test Cases</h2>';
 	
		if (isset ( $_GET ['error'] )) {
			echo '<div class="updated"><p><strong> Error happened.</strong></p></div>';
		}

		global $wpdb; $wpdb->flush(); //in the beginning of the page
		
		$this->iss_initialize ();
	 	echo '</div><table class="table table-striped">';
 		echo '<form class="form" method="post" action="" enctype="multipart/form-data">';
		echo "<?php wp_nonce_field( 'iss-test-cases', '_wpnonce-iss-test-cases' ); ?>";
		echo '<div class="row">';
		echo '<button type="submit" name="submit" class="btn btn-primary" value="all">Run All Tests</button>';
		echo '<a class="btn btn-info" href="admin.php?page=user_home">Change User Preferences</a> <a class="btn btn-info" href="admin.php?page=adminpref">Change Admin Preferences</a> ';	
	 	echo '<button type="submit" name="submit" class="btn btn-info" value="addtestdata">Add Test Data</button>';
		// echo '<button type="submit" name="submit" class="btn btn-info" value="deletetestdata">Delete Test Data</button>';
		// echo '<button type="submit" name="submit" class="btn btn-info" value="addregyear">Add RegYear (UserPref)</button>';
		// echo '<button type="submit" name="submit" class="btn btn-info" value="removeregyear">Delete RegYear (UserPref)</button>';
		echo '</div><hr />';
		echo ' <th><td colspan=3 ><h3>ADMIN PREFERENCES (Must Pass with Admin Preferences set)</h3></td></th>';
		$this->iss_get_school_name_test3 ();
		$this->iss_adminpref_schoolname_test4 ();
		$this->iss_adminpref_registrationyear_test5 ();
		$this->iss_adminpref_openregistrationdays_test23 ();
		$this->iss_adminpref_registrationfee_installments_test33 ();
		$this->iss_adminpref_registrationfee_firstchild_test24 ();
		$this->iss_adminpref_registrationfee_firstchild_installment_test25 ();
		$this->iss_adminpref_registrationfee_sibling_test26 ();
		$this->iss_adminpref_registrationfee_sibling_installment_test27 ();
		
		echo '<th><td colspan=3><h3>REGISTRATION YEAR DEPENDENT (Must with User/Admin Preference Pass Registration Year set )</h3></td> </th>';
		$this->iss_userpref_registrationyear_test6 ();
		$this->iss_registration_period_test7 ();
		$this->iss_get_parent_registration_code_test30 ();
		$this->iss_parent_insert_test31 ();
		$this->iss_student_insert_test32 ();
		$this->iss_process_newparentrequest_test34 ();
		$this->iss_process_newstudentrequest_test35 ();
		
		echo ' <th><td colspan=3 ><h3>DATA DEPENDENT</h3></td></th>';
		$this->iss_add_test_data ();
		$this->iss_get_registrationyear_list_test1 ();
		$this->iss_next_registration_year_test8 ();
		$this->iss_last_registration_year_test9 ();
		$this->iss_get_export_list_test11 ();
		$this->iss_get_parents_complete_list_test12 ();
		$this->iss_get_complete_students_list_test13 ();
		$this->iss_archive_family_test14 ();
		$this->iss_get_parents_list_test17 ();
		$this->iss_get_startwith_parents_list_test18 ();
		$this->iss_get_search_parents_list_test19 ();
		$this->iss_get_students_list_test20 ();
		$this->iss_get_class_students_list_test21 ();
		$this->iss_get_search_students_list_test22 ();
		$this->iss_delete_test_data ();
		
		echo '<th><td colspan=3><h3>CODE UNIT TEST</h3></td></th>';
		$this->iss_get_next_tab_test2 ();
		$this->iss_set_user_option_list_test10 ();
		$this->iss_field_type_test15 ();
		$this->iss_field_valid_test16 ();
		$this->iss_registration_expirydate_test28 ();
		$this->iss_get_table_name_test29 ();

		echo '<th><td colspan=3><h3>Registration Periods</h3></td></th>';
		$this->iss_test36 ();

		echo '<th><td colspan=3><h3>ISS_ParentService</h3></td></th>';
		$this->iss_test37 ();
		
		echo '<th><td colspan=3><h3>ISS_TeacherService</h3></td></th>';
		$this->iss_test38 ();
		echo '<th><td colspan=3><h3>ISS_ClassService</h3></td></th>';
		$this->iss_test39 ();
		$this->iss_test99 ();

		echo '</table><h4 class=\"text-danger\">';
		echo 'Failed Tests Count: ' . $this->failedtestcount;
		echo '</h4></form>';

		var_dump($wpdb->queries); //in the end of the page or where the query happening.
		
	} // tests_page

	public function iss_test99() {
		if ($this->iss_unit_test_start('TITLE ', 'test99')) {
		
			if (null == null) { $this->iss_unit_test_failed("TEXT"); return; }

			$this->iss_unit_test_pass();
		}
	}
	public function iss_test39() {
		
		if ($this->iss_unit_test_start('Class ', 'test39')) {
			try
			{			   
			echo "<br/>GetClasses";
			$list = ISS_ClassService::GetClasses();
			if (empty($list)) { $this->iss_unit_test_failed("1. Not Valid Classes"); return; }
			$first  = $list[0];
		
			if ($first == null) { $this->iss_unit_test_failed("2. first object null"); return; }	

			echo "<br/>LoadByID";
			$id = $first->ClassID;
			$load = ISS_ClassService::LoadByID($id); 
			if ($load == null) { $this->iss_unit_test_failed("3. LoadByID object null"); return; }
			if (($first->ClassID != $load->ClassID) ||
			 	($first->Name != $load->Name) || 
				($first->ISSGrade != $load->ISSGrade) || 
				($first->GradingPeriodID != $load->GradingPeriodID) || 
				($first->RegistrationYear != $load->RegistrationYear) ||  
				($first->GradingPeriod != $load->GradingPeriod) ||
				($first->Subject != $load->Subject) || 
				($first->Status != $load->Status) || 
				($first->created != $load->created) || 
				($first->updated != $load->updated)  ) 
			{ $this->iss_unit_test_failed("4. LoadByID  != GetClasses [0]"); return; }
			
			echo "<br/>LoadByID Invalid ID";
			$load = ISS_ClassService::LoadByID(-1);
			if ($load != null) { $this->iss_unit_test_failed("5. LoadByID object not null"); return; }
			
			echo "<br/> Add Empty Array";
			$row = array();
			$result = ISS_ClassService::Add($row);
			if ($result != 0) { $this->iss_unit_test_failed("6. ISS_ClassService::Add added empty array {$result}"); return; }
			
			echo "<br/> Add not valid array";
			$row = array();
			$row ['Name'] = 'name'; 
			$result = ISS_ClassService::Add($row);
			if ($result != 0) { $this->iss_unit_test_failed("7. ISS_ClassService::Add added not valid array {$result}"); return; }
			
			echo "<br/> Add valid array";
			$row = array();
			$row ['Name'] = 'name'; $row ['ISSGrade'] = 'KG'; $row ['Subject'] ='IS'; $row ['Status'] ='active';
			$row ['RegistrationYear'] = '2000-2001'; $row ['GradingPeriod'] = 1;
			$result = ISS_ClassService::Add($row);
			if ($result != 1) { $this->iss_unit_test_failed("8. ISS_ClassService::Add cannot add valid record {$result}"); return; }
			
			global $wpdb; $table = ISS_Class::GetViewName();
			$query = "SELECT *  FROM {$table} where Name = 'name' and ISSGrade = 'KG' and Subject = 'IS'";
			$row1 = $wpdb->get_row ( $query, ARRAY_A );
			//var_dump($row1);
			if (($row1 ['Name'] != 'name') ||  ($row1['ISSGrade'] != 'KG') || 
				($row1 ['Subject'] != 'IS') ||  ($row1['Status'] != 'active') ||
				($row1 ['RegistrationYear'] != '2000-2001') ||  ($row1['GradingPeriod'] != 1)  ) 
	   		{ $this->iss_unit_test_failed("8.1 ISS_ClassService::Add added incorrect data "); return; }
			 
			echo "<br/> Update";
			$id = $row1['ClassID'];
			$row1 = array(); 
			$row1['ClassID'] = $id;
			$row1 ['Name'] = 'uname'; $row1 ['ISSGrade'] ='3';$row1 ['Subject'] = 'QS'; $row1 ['Status'] = 'inactive'; 
			$row1 ['RegistrationYear'] = '2001-2002'; $row1['GradingPeriod'] = 2;
			$result = ISS_ClassService::Update($row1);
			if ($result != 1) { $this->iss_unit_test_failed("8. ISS_TeacherService::Update cannot update");  return; }
			
			$row2 = ISS_ClassService::LoadByID($row1['ClassID']);
			//var_dump($row2);
			if (($row2->Name != 'uname') ||  ($row2->ISSGrade != '3') || 
			($row2->Subject != 'QS') ||  ($row2->Status != 'inactive') ||
			($row2->RegistrationYear != '2001-2002') ||  ($row2->GradingPeriod != 2)  ) 
		    { $this->iss_unit_test_failed("9. ISS_ClassService::After Update error");  return; }
			  
			
			echo "<br/> DeleteByID";			
			$result = ISS_ClassService::DeleteByID($row2->ClassID);
			if ($result != 1) { $this->iss_unit_test_failed("10. ISS_ClassService::Delete error {$result}"); return; }

			if ($row2->created == '0000-00-00 00:00:00')
			echo "<br/> PROBLEM: Created date empty {$row2->created}";
			
		} finally
		{
			global $wpdb;
			$wpdb->delete (ISS_Class::GetTableName(),
				array( 'Name' => 'name', 'ISSGrade' => 'KG', 'Subject' =>'IS'),
				array( "%s", "%s", "%s" )  );
			$wpdb->delete (ISS_GradingPeriod::GetTableName(),
				array( 'RegistrationYear' => '2000-2001', 'GradingPeriod' => 1),
				array( "%s", "%d" )  );
			ISS_GradingPeriodService::Delete('2000-2001', 1);	
			ISS_GradingPeriodService::Delete('2001-2002', 2);	
		}
			$this->iss_unit_test_pass();			
		}
	}
	
	public function iss_test38() {
		if ($this->iss_unit_test_start('Teacher', 'test38')) {
			try
			{
				echo "<br/> GetTeachers";
			$list = ISS_TeacherService::GetTeachers();
			if (empty($list)) { $this->iss_unit_test_failed("1. Not Valid Grading Periods"); return; }
			$first = $list[0];
			if ($first == null) { $this->iss_unit_test_failed("first object null"); return; }			
			$id = $first->TeacherID;
			echo "<br/> LoadById";
			$load = ISS_TeacherService::LoadByID($id);
			if ($load == null) { $this->iss_unit_test_failed("2. LoadByID object null"); return; }
			if (($first->TeacherID != $load->TeacherID) ||
			 	($first->Name != $load->Name) || 
				($first->Email != $load->Email) || 
				($first->Status != $load->Status) || 
				($first->created != $load->created) || 
				($first->updated != $load->updated)  ) 
			{ $this->iss_unit_test_failed("LoadByID  != GetTeachers [0]"); return; }
			
			echo "<br/>LoadByID Invalid ID";
			$load = ISS_TeacherService::LoadByID(-1);
			if ($load != null) { $this->iss_unit_test_failed("2.1. LoadByID object not null"); return; }
			
			echo "<br/> Add Empty Array";
			$row = array();
			$result = ISS_TeacherService::Add($row);
			if ($result != 0) { $this->iss_unit_test_failed("3. ISS_TeacherService::Add added empty array {$result}"); return; }
			
			echo "<br/> Add invalid Array";
			$row = array();
			$row ['Name'] = 'name'; 
			$result = ISS_TeacherService::Add($row);
			if ($result != 0) { $this->iss_unit_test_failed("4. ISS_TeacherService::Add added not valid array {$result}"); return; }
			
			echo "<br/> Add Valid Array";			
			$row = array();
			$row ['Name'] = 'name';  $row ['Email'] ='email'; $row ['Status'] ='active';
			$result = ISS_TeacherService::Add($row);
			if ($result != 1) { $this->iss_unit_test_failed("6. ISS_TeacherService::Add cannot add valid record {$result}"); return; }
			
			echo "<br/> Load by Invalid Email";			
			$result = ISS_TeacherService::Load('email1');
			if ($result != null) { $this->iss_unit_test_failed("6. ISS_TeacherService::Load invalid email {$result}"); return; }

			echo "<br/> Load by valid Email";			
			$result = ISS_TeacherService::Load('email');
			if (($result == null) || !is_a($result, 'ISS_Teacher') || ($result->Status != 'active') || 
				($result->Name != 'name') || ($result->Email != 'email')) 
			{ $this->iss_unit_test_failed("7. ISS_TeacherService::Load cannot load");  return; }
			
			
			echo "<br/> Update";			
			$row1 = array(); $row1 ['TeacherID'] = $result->TeacherID;
			$row1 ['Status'] = 'inactive'; $row1 ['Name'] = 'uname'; $row1 ['Email'] ='uemail@email.com';
			$result = ISS_TeacherService::Update($row1);
			if ($result != 1) { $this->iss_unit_test_failed("8. ISS_TeacherService::Update cannot update");  return; }
			
			$result = ISS_TeacherService::Load('uemail@email.com');
			if (($result == null) || !is_a($result, 'ISS_Teacher') || ($result->Status != 'inactive') || 
				($result->Name != 'uname') || ($result->Email != 'uemail@email.com')) 
			{ $this->iss_unit_test_failed("9. ISS_TeacherService::After Update error");  return; }
			
			echo "<br/> Delete by ID";			
			$result = ISS_TeacherService::DeleteByID($result->TeacherID);
			if ($result != 1) { $this->iss_unit_test_failed("10. ISS_TeacherService::Delete error {$result}"); return; }
	
		    } finally
			{
				global $wpdb;
				$result = $wpdb->delete ( ISS_Teacher::GetTableName(), array( 'email' => 'email@email.com' ), array( "%s" ) );
				$result = $wpdb->delete ( ISS_Teacher::GetTableName(), array( 'email' => 'uemail@email.com' ), array( "%s" ) );
			}
			$this->iss_unit_test_pass();
		}
	}
	
	

} // class

class ISS_UnitTest1 {
	public $submit = "none";
	public $failedtestcount = 0;

	public function iss_get_registrationyear_list_test1() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test1">Run Test</button>
			</td>
			<th><label>Test1. Registration Years (iss_get_registrationyear_list)
			</label></th>
			<td>';
		if (($this->submit === 'test1') || ($this->submit === 'all')) {
			$list =  ISS_GradingPeriodService::GetRegistrationYears();
			if ($list === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL List </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (count ( $list ) == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item list </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
		echo '</td> </tr>';
	}
	public function iss_get_next_tab_test2() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test2">Run Test</button>
			</td>
			<th><label>Test2. Next Tab (iss_get_next_tab) </label></th>
			<td>';
		
		if (($this->submit === 'test2') || ($this->submit === 'all')) {
			$tab = iss_get_next_tab ( '' );
			if ($tab != 'view') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Empty -> {$tab} Tab </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (iss_get_next_tab ( 'parent' ) != 'home') {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Parent -> Home tab </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (iss_get_next_tab ( 'home' ) != 'contact') {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Home -> Contact tab </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (iss_get_next_tab ( 'contact' ) != 'student') {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> contact -> student tab </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (iss_get_next_tab ( 'student' ) != 'complete') {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> student -> complete tab </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (iss_get_next_tab ( 'complete' ) != 'view') {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> complete -> view tab </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (! iss_valid_tabname ( 'complete' ) || ! iss_valid_tabname ( 'home' ) || ! iss_valid_tabname ( 'contact' ) || ! iss_valid_tabname ( 'student123' ) || ! iss_valid_tabname ( 'studentnew' ) || ! iss_valid_tabname ( 'parent' ) || ! iss_valid_tabname ( 'view' )) {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> iss_valid_tabname failing </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$rlist = iss_get_requiredfields_by_tabname ( 'contact' );
			$tlist = iss_get_tabfields_by_tabname ( 'contact' );
			if (($rlist === NULL) || (count ( $rlist ) == 0) || ($tlist === NULL) || (count ( $tlist ) == 0)) {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> contact tab NULL iss_get_tabfields_by_tabname or iss_get_requiredfields_by_tabname </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$rlist = iss_get_requiredfields_by_tabname ( 'home' );
			$tlist = iss_get_tabfields_by_tabname ( 'home' );
			if (($rlist === NULL) || (count ( $rlist ) == 0) || ($tlist === NULL) || (count ( $tlist ) == 0)) {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> home tab NULL iss_get_tabfields_by_tabname or iss_get_requiredfields_by_tabname </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$rlist = iss_get_requiredfields_by_tabname ( 'parent' );
			$tlist = iss_get_tabfields_by_tabname ( 'parent' );
			if (($rlist === NULL) || (count ( $rlist ) == 0) || ($tlist === NULL) || (count ( $tlist ) == 0)) {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> parent tab NULL iss_get_tabfields_by_tabname or iss_get_requiredfields_by_tabname </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$rlist = iss_get_requiredfields_by_tabname ( 'studentnew' );
			$tlist = iss_get_tabfields_by_tabname ( 'studentnew' );
			if (($rlist === NULL) || (count ( $rlist ) == 0) || ($tlist === NULL) || (count ( $tlist ) == 0)) {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> studentnew tab NULL iss_get_tabfields_by_tabname or iss_get_requiredfields_by_tabname </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$rlist = iss_get_requiredfields_by_tabname ( 'complete' );
			$tlist = iss_get_tabfields_by_tabname ( 'complete' );
			if (($rlist === NULL) || (count ( $rlist ) > 0) || ($tlist === NULL) || (count ( $tlist ) == 0)) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> complete tab NULL iss_get_tabfields_by_tabname or iss_get_requiredfields_by_tabname </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
		echo '</td></tr>';
	}
	public function iss_get_school_name_test3() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test3">Run Test</button>
			</td>
			<th><label>Test3. School Name (iss_get_school_name) </label></th>
			<td>';
		
		if (($this->submit === 'test3') || ($this->submit === 'all')) {
			$result = true;
			$name = iss_get_school_name ();
			if ($name === NULL) {
				 $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Name, expected Islamic School of Silicon Valley </span>";
			}
			if ($result && ($name != 'Islamic School of Silicon Valley')) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect name {$name}, expected Islamic School of Silicon Valley </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
		echo '</td></tr>';
	}
	public function iss_adminpref_schoolname_test4() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test4">Run Test</button>
			</td>
			<th><label>Test4. Admin Preference School Name
					(iss_adminpref_schoolname) </label></th>
			<td>';
		
		if (($this->submit === 'test4') || ($this->submit === 'all')) {
			$result = true;
			$name = iss_adminpref_schoolname ();
			if ($name === NULL) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Name, expected Islamic School of Silicon Valley </span>";
			}
			if ($result && ($name != 'Islamic School of Silicon Valley')) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect name {$name}, expected Islamic School of Silicon Valley </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
		echo '</td></tr>';
	}
	public function iss_adminpref_registrationyear_test5() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test5">Run Test</button>
			</td>
			<th><label>Test5. Admin Preference Registration Year
					(iss_adminpref_registrationyear) </label></th>
			<td>';
		
		if (($this->submit === 'test5') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_adminpref_registrationyear ();
			if ($name === NULL) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Year, expected format 2016-2017 </span>";
			}
			if ($result && ! iss_field_valid ( 'RegistrationYear', $name, $errors, '' )) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid registration year {$name}, expected format 2016-2017</span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
		echo '</td></tr>';
	}
	public function iss_userpref_registrationyear_test6() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test6">Run Test</button>
			</td>
			<th><label>Test6. User Preference Registration Year (if
					iss_userpref_registrationyear = null, if
					iss_adminpref_registrationyear=null, 2016-2017) </label></th>
			<td>';
		
		if (($this->submit === 'test6') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_userpref_registrationyear ();
			if ($name === NULL) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Year, expected format 2016-2017 </span>";
			}
			if ($result && ! iss_field_valid ( 'RegistrationYear', $name, $errors, '' )) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid registration year {$name}, expected format 2016-2017 </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
		echo '</td></tr>';
	}
	public function iss_registration_period_test7() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test7">Run Test</button>
			</td>
			<th><label>Test7. Current Registration Year (iss_registration_period,
					if iss_userpref_registrationyear = null, if
					iss_adminpref_registrationyear=null, 2016-2017) </label></th>
			<td>';
		
		if (($this->submit === 'test7') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_registration_period ();
			if ($name === NULL) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Year </span>";
			}
			if ($result && ! iss_field_valid ( 'RegistrationYear', $name, $errors, '' )) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid registration period {$name} </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
		echo '</td></tr>';
	}
	public function iss_next_registration_year_test8() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test8">Run Test</button>
			</td>
			<th><label>Test8. Next Registration Year (iss_next_registration_year)
			</label></th>
			<td>';
		
		if (($this->submit === 'test8') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_next_registration_year ();
			if ($name === NULL) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Year </span>";
			}
			if ($result && ! iss_field_valid ( 'RegistrationYear', $name, $errors, '' )) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid registration period {$name} </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
		echo '</td></tr>';
	}
	public function iss_last_registration_year_test9() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test9">Run Test</button>
			</td>
			<th><label>Test9. Previous Registration Year
					(iss_last_registration_year) </label></th>
			<td>';
		
		if (($this->submit === 'test9') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_last_registration_year ();
			
			if ($name === NULL) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Year </span>";
			}
			if ($result && ! iss_field_valid ( 'RegistrationYear', $name, $errors, '' )) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid registration period {$name} </span>";
			}
			if ($result)
			{
			$newregyear = iss_next_registration_year ();
			list ( $y1, $y2 ) = explode ( "-", $newregyear );
			$y1int = intval ( $y1 );
			$y2int = intval ( $y2 );
			$regyear = ($y1int - 1) . '-' . ($y1);
			list ( $y3, $y4 ) = explode ( "-", $name );
			
			if ($result && ($y1 != $y4)) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect last registration period {$name} </span>";
			}
			}
			if ($result && ($name != $regyear)) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect last registration period {$name} </span>";
			}
			
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
		
		echo '</td></tr>';
	}
	public function iss_set_user_option_list_test10() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test10">Run Test</button>
			</td>
			<th><label>Test10. Set/Get User Preferences
					(iss_set_user_option_list) </label></th>
			<td>';
		
		if (($this->submit === 'test10') || ($this->submit === 'all')) {
			$originalList = array (
					"iss_user_registrationyear" => iss_userpref_registrationyear () 
			);
			
			iss_set_user_option_list ( 'iss_user_registrationyear', '2010-2011' );
			$returnlog = iss_get_user_option_list ();
			
			$result = true;
			if ($returnlog === NULL) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL User Option List </span>";
			}
			if ($result && (count ( $returnlog ) == 0)) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero items in User Option List </span>";
			}
			if ($result && ('2010-2011' != $returnlog ['iss_user_registrationyear'] [0])) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid user preference value {$returnlog['iss_user_registrationyear'][0]} </span>";
			}
			
			iss_set_user_option_list ( 'iss_user_registrationyear', $originalList ['iss_user_registrationyear'] );
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
		echo '</td></tr>';
	}
	public function iss_get_export_list_test11() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test11">Run Test</button>
			</td>
			<th><label>Test11. Exoprt List (iss_get_export_list) </label></th>
			<td>';
		
		if (($this->submit === 'test11') || ($this->submit === 'all')) {
			$regyear = iss_registration_period ();
			$list = iss_get_export_list ( $regyear);
			if ($list === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Export List </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (count ( $list ) == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item export list </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if ($list [0] ['RegistrationYear'] != $regyear) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect registration year {$list[0]['RegistrationYear']} </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
		
		echo '</td></tr>';
	}
	public function iss_get_parents_complete_list_test12() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test12">Run Test</button>
			</td>
			<th><label>Test12. Parent List (iss_get_parents_complete_list) </label>
			</th>
			<td>';
		
		if (($this->submit === 'test12') || ($this->submit === 'all')) {
			$result = true;
			$regyear = iss_registration_period ();
			$list = iss_get_parents_complete_list ( $regyear );
			if ($list === NULL) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Parent List </span>";
			}
			if ($result && (count ( $list ) == 0)) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item parent list </span>";
			}
			if ($result && ($list [0] ['RegistrationYear'] != $regyear)) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect registration year {$list[0]['RegistrationYear']} </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
		echo '</td></tr>';
	}
	public function iss_get_complete_students_list_test13() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test13">Run Test</button>
			</td>
			<th><label>Test13. Student List (iss_get_students_list) </label></th>
			<td>';
		
		if (($this->submit === 'test13') || ($this->submit === 'all')) {
			$regyear = iss_registration_period ();
			if ($regyear === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL REgistration Period </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$list = iss_get_students_list ( $regyear, "*" );
			if ($list === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Student List </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (count ( $list ) == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item student list </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if ($list [0] ['RegistrationYear'] != $regyear) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect registration year {$list[0]['RegistrationYear']} </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
		echo '</td></tr>';
	}
	public function iss_archive_family_test14() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test14">Run Test</button>
			</td>
			<th><label>Test14. Archived Parent List
					(iss_get_archived_parents_list, iss_archive_family,
					iss_unarchive_family, iss_get_parent_and_payment_by_id,
					iss_get_students_by_parentid) </label></th>
			<td>';
		
		if (($this->submit === 'test14') || ($this->submit === 'all')) {
			$regyear = iss_registration_period ();
			$plist = iss_get_parents_complete_list ( $regyear );
			if (count ( $plist ) == 0) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Cannot Test Arvhived Parent List </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$aid = $plist [0] ['ParentViewID'];
			$aresult = iss_archive_family ( $aid );
			// archive a parent record
			
			if ($aresult == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> iss_archive_family Failed </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			
			$parent = iss_get_parent_and_payment_by_id ( $aid );
			if ($parent === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> iss_get_parent_and_payment_by_id Failed </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if ($parent ['ParentStatus'] == 'active') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> iss_archive_family Failed </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$students = iss_get_students_by_parentid ( $parent ['ParentID'], $regyear );
			if ($students === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> iss_get_students_by_parentid Failed </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (count ( $students ) == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item students list </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if ($students [0] ['StudentStatus'] == 'active') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> iss_archive_family Failed </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			
			$list = iss_get_archived_parents_list ( $regyear, '*' );
			if ($list === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Arvhived Parent List </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (count ( $list ) == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item parent list </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if ($list [0] ['RegistrationYear'] != $regyear) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect registration year {$list[0]['RegistrationYear']} </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			
			$uresult = iss_unarchive_family ( $aid );
			// unarchive the parent record
			if ($uresult == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> iss_unarchive_family Failed </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			
			$parent = iss_get_parent_and_payment_by_id ( $aid );
			if ($parent === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> iss_get_parent_and_payment_by_id Failed </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if ($parent ['ParentStatus'] == 'inactive') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> iss_unarchive_family Failed </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$students = iss_get_students_by_parentid ( $parent ['ParentID'], $regyear );
			if ($students === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> iss_get_students_by_parentid Failed </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (count ( $students ) == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item students list </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if ($students [0] ['StudentStatus'] == 'inactive') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> iss_unarchive_family Failed </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
		echo '</td></tr>';
	}
	public function iss_field_type_test15() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test15">Run Test</button>
			</td>
			<th><label>Test15. Field Type (iss_field_type) </label></th>
			<td>';
		
		if (($this->submit === 'test15') || ($this->submit === 'all')) {
			$type = iss_field_type ( 'RegistrationYear' );
			if ($type != '%s') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect type {$type} for RegistrationYear </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$type = iss_field_type ( 'ParentID' );
			if ($type != '%d') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect type {$type} for ParentID </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$type = iss_field_type ( 'StudentBirthDate' );
			if ($type != '%s') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect type {$type} for StudentBirthDate </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$type = iss_field_type ( 'StudentFirstName' );
			if ($type != '%s') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect type {$type} for StudentFirstName </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$type = iss_field_type ( 'TotalAmountDue' );
			if ($type != '%f') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect type {$type} for TotalAmountDue </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$type = iss_field_type ( 'SpecialNeedNote' );
			if ($type != '%s') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect type {$type} for SpecialNeedNote </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
		echo '</td></tr>';
	}
	public function iss_field_valid_test16() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test16">Run Test</button>
			</td>
			<th><label>Test16. Valid Field Value (iss_field_valid) </label></th>
			<td>';
		
		if (($this->submit === 'test16') || ($this->submit === 'all')) {
			$errors = array ();
			$field = 'RegistrationYear';
			$result = iss_field_valid ( $field, '', $errors, '' );
			if ($result == true) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> 1. RegistrationYear required validation failed. {$errors[$field]}</span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if ($errors [$field] != 'Registration Period is required.') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1. Incorrect validation error {$errors[$field]} </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$result = iss_field_valid ( $field, '2015', $errors, '' );
			if ($result == true) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">2. RegistrationYear value validation failed. {$errors[$field]}</span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if ($errors [$field] != 'Registration Period is not a valid.') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">2. Incorrect validation error {$errors[$field]} </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$result = iss_field_valid ( $field, '20152015201520152015', $errors, '' );
			if ($result == true) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">3. RegistrationYear value validation failed. {$errors[$field]}</span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if ($errors [$field] != 'Registration Period is too long (10).') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">3. Incorrect validation error {$errors[$field]} </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$result = iss_field_valid ( $field, '2015-2015', $errors, '' );
			if ($result == true) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">4. RegistrationYear value validation failed. {$errors[$field]}</span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if ($errors [$field] != 'Registration Period is not a valid.') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">4. Incorrect validation error {$errors[$field]} </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$result = iss_field_valid ( $field, '2015-2016', $errors, '' );
			if ($result === false) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">5. RegistrationYear value validation failed. {$errors[$field]} </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			
			$field = 'ParentID';
			$result = iss_field_valid ( $field, 'abc', $errors, '' );
			if ($result == true) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1. ParentID value validation failed.</span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if ($errors [$field] != 'ParentID is not a valid integer.') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1. Incorrect validation error {$errors[$field]} </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$result = iss_field_valid ( $field, '2015', $errors, '' );
			if ($result === false) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">5. ParentID value validation failed. {$errors[$field]} </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			
			$result = iss_field_valid ( $field, '0', $errors, '' );
			if ($result == true) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">2. ParentID value validation failed.</span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$field = 'StudentBirthDate';
			$result = iss_field_valid ( $field, 'abc', $errors, '' );
			if ($result == true) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1. StudentBirthDate value validation failed. {$errors[$field]}</span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if ($errors [$field] != 'Student Birth Date is a not valid date (yyyy-mm-dd).') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1. Incorrect validation error {$errors[$field]} </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$result = iss_field_valid ( $field, '2015-11-11', $errors, '' );
			if ($result === false) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">5. StudentBirthDate value validation failed. {$errors[$field]} </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			
			$field = 'TotalAmountDue';
			$result = iss_field_valid ( $field, 'abc', $errors, '' );
			if ($result == true) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1. TotalAmountDue value validation failed. {$errors[$field]}</span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if ($errors [$field] != 'Total Amount Due is not a valid amount.') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">2. Incorrect validation error {$errors[$field]} </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$result = iss_field_valid ( $field, '0', $errors, '' );
			if ($result === false) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">3. TotalAmountDue value validation failed. {$errors[$field]} </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$result = iss_field_valid ( $field, '2015.90', $errors, '' );
			if ($result === false) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">5. TotalAmountDue value validation failed. {$errors[$field]} </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			
			echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
		echo '</td></tr>';
	}
	public function iss_get_parents_list_test17() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test17">Run Test</button>
			</td>
			<th><label>Test17. Parent List (iss_get_parents_list) </label></th>
			<td>';
		
		if (($this->submit === 'test17') || ($this->submit === 'all')) {
			$result = true;
			$regyear = iss_registration_period ();
			$columns = "ParentViewID, ParentID, FatherLastName, FatherFirstName, RegistrationComplete, RegistrationYear";
			$list = iss_get_parents_list ( $regyear, $columns );
			if ($list === NULL) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Parent List </span>";
			}
			if ($result && (count ( $list ) == 0)) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item parent list </span>";
			}
			if ($result && ($list [0] ['RegistrationYear'] != $regyear)) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect registration year {$list[0]['RegistrationYear']} </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
		echo '</td></tr>';
	}
	public function iss_get_startwith_parents_list_test18() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test18">Run Test</button>
			</td>
			<th><label>Test18. Parent List (iss_get_startwith_parents_list) </label>
			</th>
			<td>';
		
		if (($this->submit === 'test18') || ($this->submit === 'all')) {
			$result = true;
			$regyear = iss_registration_period ();
			$columns = "ParentViewID, ParentID, FatherLastName, FatherFirstName, RegistrationComplete, RegistrationYear";
			$list = iss_get_startwith_parents_list ( $regyear, $columns, 'T' );
			if ($list === NULL) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Parent List </span>";
			}
			if ($result && (count ( $list ) == 0)) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item parent list </span>";
			}
			if ($result && ($list [0] ['RegistrationYear'] != $regyear)) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect registration year {$list[0]['RegistrationYear']} </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
		echo '</td></tr>';
	}
	public function iss_get_search_parents_list_test19() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test19">Run Test</button>
			</td>
			<th><label>Test19. Parent List (iss_get_search_parents_list) </label>
			</th>
			<td>';
		
		if (($this->submit === 'test19') || ($this->submit === 'all')) {
			$result = true;
			$regyear = iss_registration_period ();
			$columns = "ParentViewID, ParentID, FatherLastName, FatherFirstName, RegistrationComplete, RegistrationYear";
			$list = iss_get_search_parents_list ( $regyear, $columns, 'test' );
			if ($list === NULL) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Parent List </span>";
			}
			if ($result && (count ( $list ) == 0)) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item parent list </span>";
			}
			if ($result && ($list [0] ['RegistrationYear'] != $regyear)) {
				$result = false; $this->failedtestcount++;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect registration year {$list[0]['RegistrationYear']} </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
		echo '</td></tr>';
	}
	public function iss_get_students_list_test20() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test20">Run Test</button>
			</td>
			<th><label>Test20. Student List (iss_get_students_list) </label></th>
			<td>';
		
		if (($this->submit === 'test20') || ($this->submit === 'all')) {
			$columns = "StudentViewID,StudentID,ParentId,StudentFirstName, StudentLastName,ISSGrade,StudentGender,RegistrationYear";
			$regyear = iss_registration_period ();
			$list = iss_get_students_list ( $regyear, $columns );
			if ($list === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Student List </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (count ( $list ) === 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item student list </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if ($list [0] ['RegistrationYear'] !== $regyear) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect registration year {$list[0]['RegistrationYear']} <i class=\"glyphicon glyphicon-remove\" ></i></span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			
			echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
		echo '</td></tr>';
	}
	public function iss_get_class_students_list_test21() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test21">Run Test</button>
			</td>
			<th><label>Test21. Student List (iss_get_class_students_list) </label>
			</th>
			<td>';
		
		if (($this->submit === 'test21') || ($this->submit === 'all')) {
			$regyear = iss_registration_period ();
			$columns = "StudentViewID,StudentID,ParentID,StudentFirstName, StudentLastName,ISSGrade,StudentGender,RegistrationYear";
			$list = iss_get_class_students_list ( $regyear, $columns, '2' );
			if ($list === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Student List </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (count ( $list ) == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item student list </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if ($list [0] ['RegistrationYear'] != $regyear) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">Incorrect registration year {$list[0]['RegistrationYear']}</span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			
			echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
		echo '</td></tr>';
	}
	public function iss_get_search_students_list_test22() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test22">Run Test</button>
			</td>
			<th><label>Test22. Student List (iss_get_search_students_list) </label>
			</th>
			<td>';
		$result = true;
		if (($this->submit === 'test22') || ($this->submit === 'all')) {
			$columns = "StudentViewID,StudentID,ParentId,StudentFirstName, StudentLastName,ISSGrade,StudentGender,RegistrationYear";
			$regyear = iss_registration_period ();
			$list = iss_get_search_students_list ( $regyear, $columns, 'test' );
			if ($list === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Student List </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (count ( $list ) == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item student list </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if ($list [0] ['RegistrationYear'] != $regyear) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect registration year {$list[0]['RegistrationYear']} <i class=\"glyphicon glyphicon-remove\" ></i></span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if ($result == true)
				echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
		echo '</td></tr>';
	}
	public function iss_adminpref_openregistrationdays_test23() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test23">Run Test</button>
			</td>
			<th><label>Test23. Admin Preference Open Registration Days
					(iss_adminpref_openregistrationdays) </label></th>
			<td>';
		
		if (($this->submit === 'test23') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_adminpref_openregistrationdays ();
			if (($name === NULL) || ($name === 0)) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Open Registration Days not set.</span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (! intval ( $name ) >0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid number {$name}, example: 7 (link is valid for 7 day) </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
		echo '</td></tr>';
	}
	public function iss_adminpref_registrationfee_firstchild_test24() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test24">Run Test</button>
			</td>
			<th><label>Test24. Admin Preference Registration Fee Full $ (First
					Child) (iss_adminpref_registrationfee_firstchild) </label></th>
			<td>';
		
		if (($this->submit === 'test24') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_adminpref_registrationfee_firstchild ();
			if (($name === NULL) || ($name === 0)) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Registration Fee (first child) not set. </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (! iss_field_valid ( 'PaymentInstallment1', $name, $errors, '' )) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid Registration Fee (first child) {$name} example:470 </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
		echo '</td></tr>';
	}
	public function iss_adminpref_registrationfee_firstchild_installment_test25() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test25">Run Test</button>
			</td>
			<th><label>Test25. Admin Preference Registration Installment $ (First
					Child) Installment
					(iss_adminpref_registrationfee_firstchild_installment) </label></th>
			<td>';
		
		if (($this->submit === 'test25') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_adminpref_registrationfee_firstchild_installment ();
			if (($name === NULL) || ($name === 0)) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Registration Fee (first child) Installment not set. </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (! iss_field_valid ( 'PaymentInstallment1', $name, $errors, '' )) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid Installment Amount(first child) {$name}, example: 235 </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
		echo '</td></tr>';
	}
	public function iss_adminpref_registrationfee_sibling_test26() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test26">Run Test</button>
			</td>
			<th><label>Test26. Admin Preference Registration Fee Full $ (Sibling)
					(iss_adminpref_registrationfee_firstchild) </label></th>
			<td>';
		if (($this->submit === 'test26') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_adminpref_registrationfee_sibling ();
			if (($name === NULL) || ($name === 0)) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Registration Fee (sibling) not set.</span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (! iss_field_valid ( 'PaymentInstallment1', $name, $errors, '' )) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid Registration Fee (sibling) {$name}, example:420 </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
		echo '</td></tr>';
	}
	public function iss_adminpref_registrationfee_sibling_installment_test27() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test27">Run Test</button>
			</td>
			<th><label>Test27. Admin Preference Registration Fee Installment $
					(Sibling) (iss_adminpref_registrationfee_sibling_installment) </label></th>
			<td>';
		
		if (($this->submit === 'test27') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_adminpref_registrationfee_sibling_installment ();
			if (($name === NULL) || ($name === 0)) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Fee (sibline) Installment </span>";
				$result = false; $this->failedtestcount++;
			}
			if (! iss_field_valid ( 'PaymentInstallment1', $name, $errors, '' )) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid Installment Amount(sibling) {$name} </span>";
				$result = false; $this->failedtestcount++;
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
		echo '</td></tr>';
	}
	public function iss_process_newstudentrequest_test35() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test35">Run Test</button>
			</td>
			<th><label>test35. New/Update Student Request
					(iss_process_newstudentrequest, iss_process_updatestudentrequest) </label></th>
			<td>';
		
		if (($this->submit === 'test35') || ($this->submit === 'all')) {
			global $_POST;
			$_POST = array ();
			$regyear = iss_registration_period ();
			$parentid = NULL;
			$studentid = NULL;
			try {
				if ($regyear === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1. NULL Registration Year </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				$_POST ['RegistrationYear'] = $regyear;
				$_POST ['ParentID'] = 'new';
				$_POST ['FatherLastName'] = 'TestFatherLastName';
				$_POST ['FatherFirstName'] = 'TestFatherFirstName';
				$_POST ["ParentStatus"] = 'active';
				$_POST ["SchoolEmail"] = 'Father';
				$_POST ["FatherEmail"] = 'father@father.com';
				$_POST ["FatherCellPhone"] = 'fathercell';
				$_POST ["MotherFirstName"] = 'TestMotherFirstName';
				$_POST ["MotherLastName"] = 'TestMotherLastName';
				$_POST ["MotherEmail"] = 'mother@mother.com';
				$_POST ["MotherCellPhone"] = 'mothercell';
				
				// INSERT PARENT TEST
				$errors = array ();
				$issparent = array ();
				$parentid = iss_process_newparentrequest ( $_POST, $issparent, $errors );
				
				if (($parentid == 0) || (intval ( $parentid ) == 0) || (count ( $errors ) !== 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">2. ParentID : {$parentid} error on iss_process_newstudentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				$_POST = array ();
				$_POST ['RegistrationYear'] = $regyear;
				$_POST ['ParentID'] = $parentid;
				$_POST ['StudentLastName'] = 'TestStudentLastName';
				$_POST ['StudentFirstName'] = 'TestStudentFirstName';
				$_POST ['StudentStatus'] = 'active';
				$_POST ['StudentID'] = 'new';
				$_POST ['ISSGrade'] = '2';
				$_POST ['RegularSchoolGrade'] = '3';
				$_POST ['StudentBirthDate'] = 'StudentBirthDate';
				$_POST ['StudentGender'] = 'M';
				
				// INSERT STUDENT INSERT TEST
				$errors = array ();
				$studentnew = array ();
				$studentid = iss_process_newstudentrequest ( $_POST, $studentnew, $errors );
				if (($studentid != 0) || (intval ( $studentid ) != 0) || (count ( $errors ) === 0) || ($errors ['newStudentBirthDate'] != 'Student Birth Date is too long (10).')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">3. StudentID : {$studentid} error on iss_process_newstudentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				$_POST ['StudentBirthDate'] = '2000-06-06';
				
				// INSERT STUDENT INSERT TEST
				$errors = array ();
				$studentnew = array ();
				$studentid = iss_process_newstudentrequest ( $_POST, $studentnew, $errors );
				if (($studentid == 0) || (intval ( $studentid ) == 0) || (count ( $errors ) !== 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">3. StudentID : {$studentid} error on iss_process_newstudentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// get student record
				$student = iss_get_student_by_studentid ( $studentid, $regyear );
				if ($student === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">4. NULL returned iss_get_parent_by_parentid after iss_process_newstudentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				if (($student ['ParentID'] != $parentid) || ($student ['StudentID'] != $studentid) || ($student ['RegistrationYear'] != $regyear) || ($student ['StudentLastName'] != 'TestStudentLastName') || ($student ['StudentFirstName'] != 'TestStudentFirstName') || ($student ['StudentStatus'] != 'active') || ($student ['StudentBirthDate'] != '2000-06-06') || ($student ['StudentGender'] != 'M') || ($student ['ISSGrade'] != '2') || ($student ['RegularSchoolGrade'] != '3')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">5. Incorrect values returned iss_get_parent_by_parentid after iss_process_newstudentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// CHANGE LOG TEST ON INSERT
				$changeset = iss_changelog_list ( "student", $parentid, NULL );
				// $count = count($changeset);
				//echo "<br>first changelog {$count}<br>";
				foreach ( $changeset as $row ) {
					echo "<br><br>";
					//var_dump ( $row );
				}
				if ((count ( $changeset ) != 1) || ($changeset [0] ['StudentLastName'] != 'TestStudentLastName')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">6. Incorrect student change log iss_changelog_list after iss_process_newstudentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				$changeset = iss_changelog_list (  "registration" , $parentid, NULL );
				if ((count ( $changeset ) != 1) || ($changeset [0] ["RegistrationYear"] != $regyear)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">7. Incorrect registration change log iss_changelog_list after iss_process_newstudentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// STUDENT TAB / STUDENT UPDATE TEST
				
				$_POST = array ();
				$_POST ['RegistrationYear'] = $regyear;
				$_POST ['ParentID'] = $parentid;
				$_POST ['StudentID'] = $studentid;
				$_POST ['StudentLastName'] = 'testchangedlast';
				$_POST ['StudentFirstName'] = 'testchangedfirst';
				$_POST ['StudentStatus'] = 'inactive';
				$_POST ['ISSGrade'] = '4';
				$_POST ['RegularSchoolGrade'] = '6';
				$_POST ['StudentBirthDate'] = '2014-07-07';
				$_POST ['StudentGender'] = 'F';
				
				// // update parent fields
				$errors = array ();
				$studentrow = iss_get_student_by_studentid ( $studentid, $regyear );
				$result = iss_process_updatestudentrequest ( $studentrow, $_POST, $errors );
				if (($result == 0) || (count ( $errors ) != 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">8. ParentID : {$parentid} error iss_process_updateparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// get parent record
				$student = iss_get_student_by_studentid ( $studentid, $regyear );
				if ($student === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">9. NULL returned iss_get_parent_by_parentid after iss_process_updateparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				if (($student ['ParentID'] != $parentid) || ($student ['StudentID'] != $studentid) || ($student ['RegistrationYear'] != $regyear) || ($student ['StudentLastName'] != 'testchangedlast') || ($student ['StudentFirstName'] != 'testchangedfirst') || ($student ['StudentStatus'] != 'inactive') || ($student ['StudentBirthDate'] != '2014-07-07') || ($student ['StudentGender'] != 'F') || ($student ['ISSGrade'] != '4') || ($student ['RegularSchoolGrade'] != '6')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">10. Incorrect values returned after student tab iss_process_updatestudentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				iss_write_changelog_vertical ( 'student', $parentid, $studentid );
				iss_write_changelog_vertical ( 'registration', $parentid, $studentid );
			} finally {
				if (($parentid != NULL) && ($regyear != NULL)) {
					if (($studentid != NULL) && ($regyear != NULL)) {
						iss_delete_student_by_studentid ( $studentid );
					}
					iss_delete_parent_by_parentid ( $parentid );
					iss_delete_changelog_by_parentid ( $parentid );
				}
			}
			echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
		echo '</td></tr>';
	}
	public function iss_process_newparentrequest_test34() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test34">Run Test</button>
			</td>
			<th><label>test34. New/Update Parent Request
					(iss_process_newparentrequest, iss_process_updateparentrequest) </label>
			</th>
			<td>';
		
		if (($this->submit === 'test34') || ($this->submit === 'all')) {
			global $_POST;
			$_POST = array ();
			$regyear = iss_registration_period ();
			$parentid = NULL;
			try {
				if ($regyear === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Year </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				$_POST ['RegistrationYear'] = $regyear;
				$_POST ['ParentID'] = 'new';
				$_POST ['FatherLastName'] = 'TestFatherLastName';
				$_POST ['FatherFirstName'] = 'TestFatherFirstName';
				
				$errors = array ();
				$issparent = array ();
				$parentid = iss_process_newparentrequest ( $_POST, $issparent, $errors );
				
				if (($parentid != 0) || (intval ( $parentid ) != 0) || (count ( $errors ) === 0) || ($errors ['ParentStatus'] != 'Parent Status is required.')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1. ParentID : {$parentid} minimum required field failed iss_parent_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				if (($issparent ['FatherLastName'] != 'TestFatherLastName') || ($issparent ['FatherFirstName'] != 'TestFatherFirstName')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">2. issparent is not populated with POST values after iss_parent_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				$_POST ["ParentStatus"] = 'active';
				$_POST ["SchoolEmail"] = 'Father';
				$_POST ["FatherEmail"] = 'father@father.com';
				$_POST ["FatherCellPhone"] = 'fathercell';
				$_POST ["MotherFirstName"] = 'TestMotherFirstName';
				$_POST ["MotherLastName"] = 'TestMotherLastName';
				$_POST ["MotherEmail"] = 'mother@mother.com';
				$_POST ["MotherCellPhone"] = 'mothercell';
				
				// INSERT PARENT INSERT TEST
				$errors = array ();
				$issparent = array ();
				$parentid = iss_process_newparentrequest ( $_POST, $issparent, $errors );
				// var_dump($errorstring);
				//var_dump ( $errors );
				if (($parentid == 0) || (intval ( $parentid ) == 0) || (count ( $errors ) !== 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">3. ParentID : {$parentid} error on iss_process_newparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// get parent record
				$parent = iss_get_parent_by_parentid ( $parentid, $regyear );
				if ($parent === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">4. NULL returned iss_get_parent_by_parentid after iss_process_newparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				if (($parent ['ParentID'] != $parentid) || ($parent ['RegistrationYear'] != $regyear) || ($parent ['FatherLastName'] != 'TestFatherLastName') || 
				($parent ['FatherFirstName'] != 'TestFatherFirstName') || ($parent ['RegistrationCode'] != NULL) || ($parent ['RegistrationExpiration'] != NULL) || // default
				($parent ['RegistrationComplete'] != 'New') || ($parent ['PaidInFull'] != 'No')) // default
				{
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">5. Incorrect values returned iss_get_parent_by_parentid after iss_process_newparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// CHANGE LOG TEST ON INSERT
				$changeset = iss_changelog_list (  "parent" , $parentid, NULL );
				// $count = count($changeset);
				//echo "<br>first changelog {$count}<br>";
				foreach ( $changeset as $row ) {
					echo "<br><br>";
					//var_dump ( $row );
				}
				if ((count ( $changeset ) != 1) || ($changeset [0] ['FatherLastName'] != 'TestFatherLastName')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">6. Incorrect parent change log iss_changelog_list after iss_process_newparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				$changeset = iss_changelog_list (  "payment" , $parentid, NULL );
				if ((count ( $changeset ) != 1) || ($changeset [0] ["RegistrationYear"] != $regyear)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">7. Incorrect payment change log iss_changelog_list after iss_process_newparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// PARENT TAB TEST
				$_POST ['ParentID'] = $parentid;
				$_POST ['FatherLastName'] = 'testchangedlast';
				$_POST ['FatherFirstName'] = 'testchangedfirst';
				
				// // update parent fields
				$errors = array ();
				$issparent = iss_get_parent_by_parentid ( $parentid, $regyear );
				$result = iss_process_updateparentrequest ( 'parent', $issparent, $_POST, $errors );
				if (($result == 0) || (count ( $errors ) != 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">8. ParentID : {$parentid} error iss_process_updateparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// get parent record
				$parent = iss_get_parent_by_parentid ( $parentid, $regyear );
				
				if ($parent === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">9. NULL returned iss_get_parent_by_parentid after iss_process_updateparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				if (($parent ['ParentID'] != $parentid) || ($parent ['RegistrationYear'] != $regyear) || ($parent ['FatherLastName'] != 'testchangedlast') || ($parent ['FatherFirstName'] != 'testchangedfirst')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">10. Incorrect values returned after parent tab iss_process_updateparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// COMPLETE TAB TEST
				$_POST ['RegistrationComplete'] = 'Complete';
				$_POST ['PaidInFull'] = 'Yes';
				$_POST ['Comments'] = 'Full Aid';
				
				// update complete fields
				$errors = array ();
				$issparent = iss_get_parent_by_parentid ( $parentid, $regyear );
				$result = iss_process_updateparentrequest ( 'complete', $issparent, $_POST, $errors );
				if (($result == 0) || (count ( $errors ) != 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">11. ParentID : {$parentid} error iss_process_updateparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// get parent record
				$parent = iss_get_parent_by_parentid ( $parentid, $regyear );
				
				if ($parent === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">12. NULL returned iss_get_parent_by_parentid after iss_process_updateparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				if (($parent ['ParentID'] != $parentid) || ($parent ['Comments'] != 'Full Aid') || ($parent ['RegistrationExpiration'] != NULL) || ($parent ['RegistrationComplete'] != 'Complete') || ($parent ['PaidInFull'] != 'Yes')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">13. Incorrect values returned after complete tab iss_process_updateparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// HOME TAB TEST
				$_POST ['HomeStreetAddress'] = 'TestHomeStreetAddress';
				$_POST ['HomeZip'] = '12345';
				$_POST ['HomeCity'] = 'Complete';
				$_POST ['HomePhone'] = 'Yes';
				$_POST ['ShareAddress'] = 'No';
				$_POST ['TakePicture'] = 'Yes';
				
				// update home fields
				$errors = array ();
				$issparent = iss_get_parent_by_parentid ( $parentid, $regyear );
				$result = iss_process_updateparentrequest ( 'home', $issparent, $_POST, $errors );
				// var_dump($errors);
				if (($result == 0) || (count ( $errors ) != 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">14. ParentID : {$parentid} error home tab iss_process_updateparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// get parent record
				$parent = iss_get_parent_by_parentid ( $parentid, $regyear );
				
				if ($parent === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">15. NULL returned iss_get_parent_by_parentid after iss_process_updateparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				if (($parent ['ParentID'] != $parentid) || ($parent ['HomeCity'] != 'Complete') || ($parent ['HomePhone'] != 'Yes')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">16. Incorrect values returned after home tab iss_process_updateparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// CONTACT TAB TEST
				$_POST ['EmergencyContactName1'] = 'TestEmergencyContactName1';
				$_POST ['EmergencyContactPhone1'] = 'Yes';
				$_POST ['EmergencyContactName2'] = 'TestEmergencyContactName2';
				$_POST ['EmergencyContactPhone2'] = 'Yes';
				
				// update contact fields
				$errors = array ();
				$issparent = iss_get_parent_by_parentid ( $parentid, $regyear );
				$result = iss_process_updateparentrequest ( 'contact', $issparent, $_POST, $errors );
				// var_dump($errors);
				if (($result == 0) || (count ( $errors ) != 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">17. ParentID : {$parentid} error contact tab iss_process_updateparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// get parent record
				$parent = iss_get_parent_by_parentid ( $parentid, $regyear );
				
				if ($parent === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">18. NULL returned iss_get_parent_by_parentid after iss_process_updateparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				if (($parent ['ParentID'] != $parentid) || ($parent ['EmergencyContactName1'] != 'TestEmergencyContactName1') || ($parent ['EmergencyContactPhone1'] != 'Yes')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">19. Incorrect values returned after contact tab iss_process_updateparentrequest </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				iss_write_changelog_vertical ( 'parent', $parentid, NULL );
				iss_write_changelog_vertical ( 'payment', $parentid, NULL );
			} finally {
				if (($parentid != NULL) && ($regyear != NULL)) {
					iss_delete_parent_by_parentid ( $parentid );
					iss_delete_changelog_by_parentid ( $parentid );
				}
			}
			echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
		echo '</td></tr>';
	}
	public function iss_adminpref_registrationfee_installments_test33() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test24">Run Test</button>
			</td>
			<th><label>Test33. Admin Preference Registration Fee Installments #
					(iss_adminpref_registrationfee_installments) </label></th>
			<td>';
		if (($this->submit === 'test33') || ($this->submit === 'all')) {
			$errors = array ();
			$name = iss_adminpref_registrationfee_installments ();
			if (($name === NULL) || ($name === 0)) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Registration Fee Installments # not set. </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (! intval ( $name ) >0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid Registration Fee Installments {$name}, example: 2 </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			
			echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
		echo '</td></tr>';
	}
	public function iss_student_insert_test32() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test32">Run Test</button>
			</td>
			<th><label>test32. New/Update Student (iss_student_insert,
					iss_registration_insert, iss_student_update,
					iss_get_student_by_studentid, iss_changelog_list) </label></th>
			<td>';
		
		if (($this->submit === 'test32') || ($this->submit === 'all')) {
			$regyear = iss_registration_period ();
			$parentid = NULL;
			$studentid = NULL;
			try {
				if ($regyear === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1. NULL Registration Year </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				$sdata ['RegistrationYear'] = $regyear;
				$sdata ['ParentID'] = 'new';
				$sdata ['FatherLastName'] = 'TestFatherLastName';
				$sdata ['FatherFirstName'] = 'TestFatherFirstName';
				$sdata ['PaidInFull'] = 'No';
				$parentid = iss_parent_insert ( $sdata );
				if (($parentid == 0) || (intval ( $parentid ) == 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">2 ParentID : {$parentid} error on iss_parent_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				$sdata ['StudentID'] = 'new';
				$sdata ['ISSGrade'] = '2';
				$sdata ['RegularSchoolGrade'] = 'KG';
				$sdata ['StudentLastName'] = 'TestStudentLastName';
				$sdata ['StudentFirstName'] = 'TestStudentFirstName';
				
				$studentid = iss_student_insert ( $sdata );
				if (($studentid != 0) || (intval ( $studentid ) != 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">3 StudentID : {$studentid} error on iss_student_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				$sdata ['ParentID'] = $parentid;
				
				// insert student record
				$studentid = iss_student_insert ( $sdata );
				if (($studentid == 0) || (intval ( $studentid ) == 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">4 StudentID : {$studentid} error on iss_student_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// get student record
				$student = iss_get_student_by_studentid ( $studentid, $regyear );
				if ($student === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL returned iss_get_student_by_studentid after iss_student_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				if (($student ['ParentID'] != $parentid) || ($student ['StudentID'] != $studentid) || ($student ['StudentLastName'] != 'TestStudentLastName') || ($student ['StudentFirstName'] != 'TestStudentFirstName') || ($student ['ISSGrade'] != '2')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">5 Incorrect values returned iss_get_student_by_studentid after iss_student_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// check change log
				$changeset = iss_changelog_list ( "student" , $parentid, $studentid );
				// $count = count($changeset);
				//echo "<br>first changelog {$count}<br>";
				foreach ( $changeset as $row ) {
					echo "<br><br>";
					//var_dump ( $row );
				}
				if ((count ( $changeset ) != 1) || ($changeset [0] ['StudentLastName'] != 'TestStudentLastName')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">6 Incorrect student change log iss_changelog_list after iss_student_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				$changeset = iss_changelog_list ( "registration" , $parentid, $studentid );
				if ((count ( $changeset ) != 1) || ($changeset [0] ["RegistrationYear"] != $regyear)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">7 Incorrect registration change log iss_changelog_list after iss_student_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				$sdata ['StudentID'] = $studentid;
				$sdata ['RegularSchoolGrade'] = '5';
				$sdata ['ISSGrade'] = '4';
				$sdata ['StudentLastName'] = 'testchangedlastname';
				$sdata ['StudentFirstName'] = 'testchangedfirstname';
				$changedfields = array (
						"ISSGrade",
						"StudentLastName",
						"StudentFirstName",
						"RegularSchoolGrade" 
				);
				// update student fields
				$result = iss_student_update ( $changedfields, $sdata );
				if ($result == 0) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">8 StudentID : {$studentid} error iss_student_update </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// get student record
				$student = iss_get_student_by_studentid ( $studentid, $regyear );
				if ($student === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL returned iss_get_student_by_studentid after iss_student_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				if (($student ['ParentID'] != $parentid) || ($student ['StudentID'] != $studentid) || ($student ['StudentLastName'] != 'testchangedlastname') || ($student ['StudentFirstName'] != 'testchangedfirstname') || ($student ['ISSGrade'] != '4') | ($student ['RegularSchoolGrade'] != '5')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">9 Incorrect values returned iss_get_student_by_studentid after iss_student_update </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// check the changed log
				$changeset = iss_changelog_list (   "student" , $parentid, $studentid );
				if ((count ( $changeset ) != 2) || ($changeset [0] ['StudentLastName'] != 'TestStudentLastName') || ($changeset [1] ['StudentLastName'] != 'testchangedlastname')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> 9.1 Incorrect student change log iss_changelog_list after iss_student_update </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				$changeset = iss_changelog_list (   "registration" , $parentid, $studentid );
				if ((count ( $changeset ) != 2) || ($changeset [0] ['ISSGrade'] != '2') || ($changeset [1] ['ISSGrade'] != '4') || ($changeset [0] ['RegularSchoolGrade'] != 'KG') || ($changeset [1] ['RegularSchoolGrade'] != '5')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">10 Incorrect student change log iss_changelog_list after iss_student_update </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
			} finally {
				if (($parentid != NULL) && ($regyear != NULL)) {
					if (($studentid != NULL) && ($regyear != NULL)) {
						iss_delete_student_by_studentid ( $studentid );
					}
					iss_delete_parent_by_parentid ( $parentid );
					iss_delete_changelog_by_parentid ( $parentid );
				}
			}
			echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
		echo '</td></tr>';
	}
	public function iss_parent_insert_test31() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test31">Run Test</button>
			</td>
			<th><label>test31. New/Update Parent (iss_parent_insert,
					iss_payment_insert, iss_parent_update, iss_get_parent_by_parentid,
					iss_changelog_list) </label></th>
			<td>';
		
		if (($this->submit === 'test31') || ($this->submit === 'all')) {
			$regyear = iss_registration_period ();
			$parentid = NULL;
			try {
				if ($regyear === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1. NULL Registration Year </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				$sdata ['RegistrationYear'] = $regyear;
				$sdata ['ParentID'] = 'new';
				$parentid = iss_parent_insert ( $sdata );
				if (($parentid != 0) || (intval ( $parentid ) != 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">2. ParentID : {$parentid} minimum required field failed iss_parent_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				$sdata ['FatherLastName'] = 'TestFatherLastName';
				$sdata ['FatherFirstName'] = 'TestFatherFirstName';
				$sdata ['PaidInFull'] = 'No';
				
				// insert parent record
				$parentid = iss_parent_insert ( $sdata );
				if (($parentid == 0) || (intval ( $parentid ) == 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">3. ParentID : {$parentid} error on iss_parent_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// get parent record
				$parent = iss_get_parent_by_parentid ( $parentid, $regyear );
				if ($parent === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">4. NULL returned iss_get_parent_by_parentid after iss_parent_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				if (($parent ['ParentID'] != $parentid) || ($parent ['RegistrationYear'] != $regyear) || ($parent ['FatherLastName'] != 'TestFatherLastName') || ($parent ['FatherFirstName'] != 'TestFatherFirstName') || ($parent ['RegistrationCode'] != NULL) || ($parent ['RegistrationExpiration'] != NULL) || ($parent ['RegistrationComplete'] != 'New') || ($parent ['PaidInFull'] != 'No')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">5. Incorrect values returned iss_get_parent_by_parentid after iss_parent_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// check change log
				$changeset = iss_changelog_list (  "parent" , $parentid, NULL );
				// $count = count($changeset);
				//echo "<br>first changelog {$count}<br>";
				// foreach ( $changeset as $row ) {
				// 	echo "<br><br>";
				// 	var_dump ( $row );
				// }
				if ((count ( $changeset ) != 1) || ($changeset [0] ['FatherLastName'] != 'TestFatherLastName')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">6. Incorrect parent change log iss_changelog_list after iss_parent_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				$changeset = iss_changelog_list ( "payment" , $parentid, NULL );
				if ((count ( $changeset ) != 1) || ($changeset [0] ["RegistrationYear"] != $regyear)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">7. Incorrect payment change log iss_changelog_list after iss_parent_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				$sdata ['ParentID'] = $parentid;
				$sdata ['FatherLastName'] = 'testchangedlast';
				$sdata ['FatherFirstName'] = 'testchangedfirst';
				$sdata ['RegistrationComplete'] = 'Complete';
				$sdata ['PaidInFull'] = 'Yes';
				$sdata ['Comments'] = 'Full Aid';
				
				$changedfields = array (
						'FatherLastName',
						'FatherFirstName',
						'RegistrationComplete',
						'PaidInFull',
						'Comments' 
				);
				
				// update parent fields
				$result = iss_parent_update ( $changedfields, $sdata );
				if ($result == 0) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">8. ParentID : {$parentid} error iss_parent_update </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// get parent record
				$parent = iss_get_parent_by_parentid ( $parentid, $regyear );
				if ($parent === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">9. NULL returned iss_get_parent_by_parentid after iss_parent_update </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				if (($parent ['ParentID'] != $parentid) || ($parent ['RegistrationYear'] != $regyear) || ($parent ['FatherLastName'] != 'testchangedlast') || ($parent ['FatherFirstName'] != 'testchangedfirst') || ($parent ['Comments'] != 'Full Aid') || ($parent ['RegistrationExpiration'] != NULL) || ($parent ['RegistrationComplete'] != 'Complete') || ($parent ['PaidInFull'] != 'Yes')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> 9.1 Incorrect values returned iss_get_parent_by_parentid after iss_parent_update </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				// check the changed log
				$changeset = iss_changelog_list ( "parent" , $parentid, NULL );
				//var_dump ( $changeset );
				if ((count ( $changeset ) != 2) || ($changeset [0] ['FatherLastName'] != 'TestFatherLastName') || ($changeset [1] ['FatherLastName'] != 'testchangedlast')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">10. Incorrect parent change log iss_changelog_list after iss_parent_update </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				$changeset = iss_changelog_list (   "payment" , $parentid, NULL );
				if ((count ( $changeset ) != 2) || ($changeset [0] ['PaidInFull'] != "No") || ($changeset [1] ['PaidInFull'] != "Yes")) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect payment change log iss_changelog_list after iss_parent_update </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
			} finally {
				if (($parentid != NULL) && ($regyear != NULL)) {
					iss_delete_parent_by_parentid ( $parentid );
					iss_delete_changelog_by_parentid ( $parentid );
				}
			}
			echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
		echo '</td></tr>';
	}
	public function iss_get_parent_registration_code_test30() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test30">Run Test</button>
			</td>
			<th><label>Test30. Registration Code(iss_parent_insert,
					iss_get_parent_registration_code, iss_get_parent_by_parentid) </label>
			</th>
			<td>';
		if (($this->submit === 'test30') || ($this->submit === 'all')) {
			$regyear = iss_registration_period ();
			$parentid = NULL;
			try {
				if ($regyear === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Year </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				$sdata ['RegistrationYear'] = $regyear;
				$sdata ['ParentID'] = 'new';
				$sdata ['FatherLastName'] = 'TestFatherLastName';
				$sdata ['FatherFirstName'] = 'TestFatherFirstName';
				$parentid = iss_parent_insert ( $sdata );
				if (($parentid == 0) || (intval ( $parentid ) == 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> ParentID : {$parentid} error on iss_parent_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				$parent = iss_get_parent_by_parentid ( $parentid, $regyear );
				if ($parent === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL returned iss_get_parent_by_parentid after iss_parent_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				$code = iss_get_parent_registration_code ( $parent ['ParentViewID'] );
				if ($code === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL code returned iss_get_parent_registration_code </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				$parent = iss_get_parent_by_parentid ( $parentid, $regyear );
				if ($parent === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL returned iss_get_parent_by_parentid after iss_parent_insert </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
				
				if (($parent ['ParentID'] != $parentid) || ($parent ['RegistrationYear'] != $regyear) || ($parent ['FatherFirstName'] != 'TestFatherFirstName') || ($parent ['RegistrationCode'] != $code) || ($parent ['RegistrationExpiration'] === NULL) || ($parent ['RegistrationComplete'] != 'Open')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect values returned iss_get_parent_registration_code </span>";
					echo '</td></tr>';
					$this->failedtestcount++; return;
				}
			} finally {
				if (($parentid != NULL) && ($regyear != NULL)) {
					iss_delete_parent_by_parentid ( $parentid );
					iss_delete_changelog_by_parentid ( $parentid );
				}
			}
			echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
		echo '</td></tr>';
	}
	public function iss_get_table_name_test29() {
		echo '<tr>
			<td>
				<button type="submit" name="submit" class="button-primary"
					value="test29">Run Test</button>
			</td>
			<th><label>Test29. Table Name (iss_get_table_name) </label></th>
			<td>';
		
		global $wpdb;
		if (($this->submit === 'test29') || ($this->submit === 'all')) {
			$tab = iss_get_table_name ( '' );
			if ($tab != NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect table name for empty</span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			$tab = iss_get_table_name ( "parent" );
			if ($tab != ($wpdb->prefix . 'iss_parent')) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> {$tab} Incorrect table name for parent</span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (iss_get_table_name ( 'parents' ) != $wpdb->prefix . 'iss_parents') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect table name for parents</span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (iss_get_table_name ( 'registration' ) != $wpdb->prefix . 'iss_registration') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect table name for registration</span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (iss_get_table_name ( 'student' ) != $wpdb->prefix . 'iss_student') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect table name for student </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if(iss_get_table_name('students') != $wpdb->prefix .'iss_students') {
			echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect table name for students</span>";
			echo '</td></tr>';$this->failedtestcount++; return;
			
			}
			if (iss_get_table_name ( 'payment' ) != $wpdb->prefix . 'iss_payment') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect table name for payment</span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			if (iss_get_table_name ( 'changelog' ) != $wpdb->prefix . 'iss_changelog') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect table name for changelog </span>";
				echo '</td></tr>';
				$this->failedtestcount++; return;
			}
			echo "<span class=\"text-success\"> Pass <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
		echo '</td></tr>';
	}
	public function iss_registration_expirydate_test28() {

		if ($this->iss_unit_test_start('Open Registration Expiry Date', 'test28')) {
				
			echo "<br/>  NULL Expiration Date";							
			$date = current_time ( 'mysql' );
			$edate = iss_registration_expirydate ();
			if ($edate === NULL) { $this->iss_unit_test_failed("1. NULL Expiration Date"); return; }
			
			echo "<br/>  Invalid DateTime";							
			$errors = array ();
			if (! iss_field_valid ( 'created', $edate, $errors, '' )) { $this->iss_unit_test_failed("1. Not Valid DateTime {$edate}"); return; }
				
			$this->iss_unit_test_pass();
		}
	}
	public function iss_test36() {
		if ($this->iss_unit_test_start('Grading Period', 'test36')) {
			try
			{
			echo "<br/> GetGradingPeriods";							
			$list = ISS_GradingPeriodService::GetGradingPeriods();
			if (empty($list)) { $this->iss_unit_test_failed("1. Not Valid Grading Periods"); return; }
			$first = $list[0];
			if ($first == null) { $this->iss_unit_test_failed("first object null"); return; }			
			$id = $first->GradingPeriodID;
			$load = ISS_GradingPeriodService::LoadByID($id);
			if ($load == null) { $this->iss_unit_test_failed("2. LoadByID object null"); return; }
			if (($first->GradingPeriodID != $load->GradingPeriodID) ||
			 	($first->RegistrationYear != $load->RegistrationYear) || 
				($first->GradingPeriod != $load->GradingPeriod) || 
				($first->StartDate != $load->StartDate) || 
				($first->EndDate != $load->EndDate) || 
				($first->created != $load->created) || 
				($first->updated != $load->updated)  ) 
			{ $this->iss_unit_test_failed("LoadByID  != GetGradingPeriods [0]"); return; }
			
			echo "<br/> Add empty";										
			$row = array();
			$result = ISS_GradingPeriodService::Add($row);
			if ($result != 0) { $this->iss_unit_test_failed("3. ISS_GradingPeriodService::Add added empty array {$result}"); return; }
			
			// initalize invalid array
			echo "<br/> Add invalid array";										
			$row = array();
			$row ['GradingPeriod'] = 1; 
			$result = ISS_GradingPeriodService::Add($row);
			if ($result != 0) { $this->iss_unit_test_failed("4. ISS_GradingPeriodService::Add added not valid array {$result}"); return; }
			
			echo "<br/> Delete invalid";										
			$result = ISS_GradingPeriodService::Delete('2010-2011', 1);
			if ($result != 0) { $this->iss_unit_test_failed("5. ISS_GradingPeriodService::Delete non existent record {$result}"); return; }
			
			echo "<br/> Add valid";										
			$row = array();
			$row ['GradingPeriod'] = 1; $row ['RegistrationYear'] = '2010-2011'; $row ['StartDate'] ='2000-06-06'; $row ['EndDate'] ='2000-12-06';
			$result = ISS_GradingPeriodService::Add($row);
			if ($result != 1) { $this->iss_unit_test_failed("6. ISS_GradingPeriodService::Add cannot add valid record {$result}"); return; }
			
			echo "<br/> Load invalid regyear and gp";										
			$result = ISS_GradingPeriodService::Load('010-2011', 1);
			if ($result != null) { $this->iss_unit_test_failed("6. ISS_GradingPeriodService::Load invalid registration year {$result}"); return; }

			echo "<br/> Load valid regyear and gp";										
			$result = ISS_GradingPeriodService::Load('2010-2011', 1);
			if (($result == null) || !is_a($result, 'ISS_GradingPeriod') || ($result->GradingPeriod != 1) || 
				($result->RegistrationYear != '2010-2011') || ($result->StartDate != '2000-06-06') || ($result->EndDate != '2000-12-06')) 
			{ $this->iss_unit_test_failed("7. ISS_GradingPeriodService::Load cannot load");  return; }
			
			echo "<br/> Update";										
			$row1 ['GradingPeriodID'] = $result->GradingPeriodID;
			$row1 ['GradingPeriod'] = 2; $row1 ['RegistrationYear'] = '2011-2012'; $row1 ['StartDate'] ='2000-06-02'; $row1 ['EndDate'] ='2000-12-02';
			$result = ISS_GradingPeriodService::Update($row1);
			if ($result != 1) { $this->iss_unit_test_failed("8. ISS_GradingPeriodService::Update cannot update");  return; }
			
			$result = ISS_GradingPeriodService::Load('2011-2012', 2);
			if (($result == null) || !is_a($result, 'ISS_GradingPeriod') || ($result->GradingPeriod != 2) || 
				($result->RegistrationYear != '2011-2012') || ($result->StartDate != '2000-06-02') || ($result->EndDate != '2000-12-02')) 
			{ $this->iss_unit_test_failed("9. ISS_GradingPeriodService::After Update error");  return; }
			
			echo "<br/> DeleteByID ";										
			$result = ISS_GradingPeriodService::DeleteByID($result->GradingPeriodID);
			if ($result != 1) { $this->iss_unit_test_failed("10. ISS_GradingPeriodService::Delete error {$result}"); return; }

			echo "<br/> Delete by regyear and gp ";										
			$row = array();
			$row ['GradingPeriod'] = 1; $row ['RegistrationYear'] = '2010-2011'; $row ['StartDate'] ='2000-06-06'; $row ['EndDate'] ='2000-12-06';
			$result = ISS_GradingPeriodService::Add($row);
			$result = ISS_GradingPeriodService::Delete('2010-2011', 1);
			if ($result != 1) { $this->iss_unit_test_failed("11. ISS_GradingPeriodService::Delete error {$result}"); return; }

			$result = ISS_GradingPeriodService::Delete('2016-2017', 1);
			if ($result != 0) { $this->iss_unit_test_failed("12. ISS_GradingPeriodService::Delete records with parents {$result}"); return; }
			
			echo "<br/> isValid errors ";										
			$row = array();
			$row ['GradingPeriod'] = 'n'; $row ['RegistrationYear'] = '2010-'; $row ['StartDate'] ='2000-06'; $row ['EndDate'] ='2000-06';
			$errors = array();
			$result = ISS_GradingPeriodService::isValid($row, $errors);
			
			if (($result == true) || empty($errors) || !isset($errors['GradingPeriod']) || !isset($errors['RegistrationYear']) || !isset($errors['StartDate']) || !isset($errors['EndDate'])) 
			{ $this->iss_unit_test_failed("13. ISS_GradingPeriodService::isValid {$result}"); return; }
			
			echo "<br/> AddWithDefaultDate ";										
			$resultrow = ISS_GradingPeriodService::AddWithDefaultDate('2010-2011', 1);
			if ($resultrow == null) { $this->iss_unit_test_failed("14. ISS_GradingPeriodService::AddWithDefaultDate cannot add  {$resultrow}"); return; }
		
			if (($resultrow == null) || !is_a($resultrow, 'ISS_GradingPeriod') || ($resultrow->GradingPeriod != 1) || 
				($resultrow->RegistrationYear != '2010-2011') || ($resultrow->StartDate != '2010-06-15') || ($resultrow->EndDate != '2010-12-31')) 
			{ $this->iss_unit_test_failed("15. ISS_GradingPeriodService::AddWithDefaultDate error");  return; }
			
			echo "<br/> AddWithDefaultDate duplicate";										
			$resultrow = ISS_GradingPeriodService::AddWithDefaultDate('2010-2011', 1);
			if ($resultrow == null) { $this->iss_unit_test_failed("15.1. ISS_GradingPeriodService::AddWithDefaultDate add duplicate {$resultrow}"); return; }
			
			echo "<br/> AddWithDefaultDate gp=2";										
			$resultrow = ISS_GradingPeriodService::AddWithDefaultDate('2010-2011', 2);
			if ($resultrow == null) { $this->iss_unit_test_failed("16. ISS_GradingPeriodService::AddWithDefaultDate cannot add  {$resultrow}"); return; }
			
			if (($resultrow == null) || !is_a($resultrow, 'ISS_GradingPeriod') || ($resultrow->GradingPeriod != 2) || 
				($resultrow->RegistrationYear != '2010-2011') || ($resultrow->StartDate != '2011-01-01') || ($resultrow->EndDate != '2011-06-14')) 
			{ $this->iss_unit_test_failed("17. ISS_GradingPeriodService::AddWithDefaultDate gp=2 error");  return; }

			echo "<br/> AddWithDefaultDate gp=3";										
			$resultrow = ISS_GradingPeriodService::AddWithDefaultDate('2010-2011', 3);
			if ($resultrow == null) { $this->iss_unit_test_failed("18. ISS_GradingPeriodService::AddWithDefaultDate cannot add  {$resultrow}"); return; }
			if (($resultrow == null) || !is_a($resultrow, 'ISS_GradingPeriod') || ($resultrow->GradingPeriod != 3) || 
				($resultrow->RegistrationYear != '2010-2011') || ($resultrow->StartDate != '2011-01-01') || ($resultrow->EndDate != '2011-06-14')) 
			{ $this->iss_unit_test_failed("19. ISS_GradingPeriodService::AddWithDefaultDate gp=3 error");  return; }

		    } finally
			{
				ISS_GradingPeriodService::Delete('2010-2011', 1);
				ISS_GradingPeriodService::Delete('2011-2012', 2);
				ISS_GradingPeriodService::Delete('2010-2011', 2);
				ISS_GradingPeriodService::Delete('2010-2011', 3);
			}
			$this->iss_unit_test_pass();
		}
	
	}

	public function iss_test37() {		
		if ($this->iss_unit_test_start('ISS_ParentService::GetParentCount ', 'test37')) {
			
			echo "<br/> GetTableName ";										
			$table = ISS_ParentService::GetTableName();
			if (empty($table)) { $this->iss_unit_test_failed("Null GetTableName"); return; }			
			if (strpos($table, 'iss_parent')===false) { $this->iss_unit_test_failed("Not Valid GetTableName {$table}"); return; }

			echo "<br/> GetViewName ";										
			$table = ISS_ParentService::GetViewName();
			if (empty($table)) { $this->iss_unit_test_failed("Null GetViewName"); return; }			
			if (strpos($table, 'iss_parents')===false) { $this->iss_unit_test_failed("Not Valid GetViewName {$table}"); return; }

			echo "<br/> GetParentCount regyear = null";										
			$count = ISS_ParentService::GetParentCount(null);
			if (empty($count)) { $this->iss_unit_test_failed("Empty GetParentCount"); return; }
			if (-1 != $count) { $this->iss_unit_test_failed("-1 expected GetParentCount {$count}"); return; }

			echo "<br/> GetParentCount regyear=2016-2017";										
			$count = ISS_ParentService::GetParentCount('2016-2017');
			if (empty($count)) { $this->iss_unit_test_failed("Empty GetParentCount"); return; }
			if (-1 == $count) { $this->iss_unit_test_failed("> 0 expected GetParentCount {$count}"); return; }

			$this->iss_unit_test_pass();
		}
	}
}
$my_test_page = new ISS_UnitTestPlugin ();
?>