<?php
/*
 * Plugin Name: ISS Unit Test Plugin
 * Description: UNIT TEST THE CODE AS MUCH AS POSSIBLE
 * Version: 1.0.0
 * Author: Azra Syed
 * Text Domain: iss_unittest
 */
class ISS_UnitTestPlugin {
	private $submit = "none";
	
	/**
	 * Start up
	 */
	public function __construct() {
		add_action ( 'admin_menu', array (
				$this,
				'add_plugin_page' 
		) );
		add_action ( 'init', array (
				$this,
				'add_plugin_page_action' 
		) );
		add_action ( 'admin_enqueue_scripts', 'load_custom_iss_style' );
	}
	public function add_plugin_page() {
		// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page ( 'iss_unittest', 'Unit Test', 'iss_admin', 'test_home', array (
				$this,
				'tests_page' 
		), 'dashicons-yes', 99 );
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
	public function tests_page() {
		if (! current_user_can ( 'iss_admin' ))
			wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'iss_unittest_text' ) );
		?>

<div class="wrap">
	<h2><?php _e( 'Unit Test Cases', 'iss_unittest_text' ); ?></h2>

    <?php
		if (isset ( $_GET ['error'] )) {
			echo '<div class="updated"><p><strong> Error happened.</strong></p></div>';
		}
		?>
      <form class="form" method="post" action=""
		enctype="multipart/form-data">
        <?php wp_nonce_field( 'iss-test-cases', '_wpnonce-iss-test-cases' ); ?>

          <p class="submit">
			<button type="submit" name="submit" class="button-primary"
				value="all">Run All Test</button>
		</p>
		<table class="table table-striped">
			<!-- Test 1-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test1">Run Test</button>
				</td>
				<th><label>Test1. Registration Years (iss_get_registrationyear_list)
				</label></th>
				<td>
                <?php $this->iss_get_registrationyear_list_test1();?>
              </td>
			</tr>
			<!-- Test2-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test2">Run Test</button>
				</td>
				<th><label>Test2. Next Tab (iss_get_next_tab) </label></th>
				<td>
                <?php $this->iss_get_next_tab_test2();?>
              </td>
			</tr>
			<!-- Test 3-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test3">Run Test</button>
				</td>
				<th><label>Test3. School Name (iss_get_school_name) </label></th>
				<td>
                <?php $this->iss_get_school_name_test3();?>
              </td>
			</tr>
			<!-- Test 4-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test4">Run Test</button>
				</td>
				<th><label>Test4. Admin Preference School Name
						(iss_adminpref_schoolname) </label></th>
				<td>
                <?php $this->iss_adminpref_schoolname_test4();?>
              </td>
			</tr>
			<!-- test23-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test23">Run Test</button>
				</td>
				<th><label>Test23. Admin Preference Open Registration Days
						(iss_adminpref_openregistrationdays) </label></th>
				<td>
                <?php $this->iss_adminpref_openregistrationdays_test23();?>
              </td>
			</tr>

			<!-- test33-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test24">Run Test</button>
				</td>
				<th><label>Test33. Admin Preference Registration Fee Installments
						(iss_adminpref_registrationfee_installments) </label></th>
				<td>
                <?php $this->iss_adminpref_registrationfee_installments_test33();?>
              </td>
			</tr>
			<!-- test24-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test24">Run Test</button>
				</td>
				<th><label>Test24. Admin Preference First Child Registration Fee
						(iss_adminpref_registrationfee1) </label></th>
				<td>
                <?php $this->iss_adminpref_registrationfee1_test24();?>
              </td>
			</tr>
			<!-- test25-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test25">Run Test</button>
				</td>
				<th><label>Test25. Admin Preference First Child Registration
						Installment (iss_adminpref_registrationfee1_installment) </label>
				</th>
				<td>
                <?php $this->iss_adminpref_registrationfee1_installment_test25();?>
              </td>
			</tr>
			<!-- test26-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test26">Run Test</button>
				</td>
				<th><label>Test26. Admin Preference Sibling Registration Fee
						(iss_adminpref_registrationfee1) </label></th>
				<td>
                <?php $this->iss_adminpref_registrationfee2_test26();?>
              </td>
			</tr>
			<!-- test27-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test27">Run Test</button>
				</td>
				<th><label>Test27. Admin Preference Sibling Registration Installment
						(iss_adminpref_registrationfee2_installment) </label></th>
				<td>
                <?php $this->iss_adminpref_registrationfee2_installment_test27();?>
              </td>
			</tr>
			<!-- Test 5-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test5">Run Test</button>
				</td>
				<th><label>Test5. Admin Preference Registration Year
						(iss_adminpref_registrationyear) </label></th>
				<td>
                <?php $this->iss_adminpref_registrationyear_test5();?>
              </td>
			</tr>
			<!-- Test 6-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test6">Run Test</button>
				</td>
				<th><label>Test6. User Preference Registration Year
						(iss_userpref_registrationyear) </label></th>
				<td>
                <?php $this->iss_userpref_registrationyear_test6();?>
              </td>
			</tr>
			<!-- Test 7-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test7">Run Test</button>
				</td>
				<th><label>Test7. Current Registration Year
						(iss_registration_period) </label></th>
				<td>
                <?php $this->iss_registration_period_test7();?>
              </td>
			</tr>
			<!-- Test 8-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test8">Run Test</button>
				</td>
				<th><label>Test8. Next Registration Year
						(iss_next_registration_year) </label></th>
				<td>
                <?php $this->iss_next_registration_year_test8();?>
              </td>
			</tr>
			<!-- Test 9-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test9">Run Test</button>
				</td>
				<th><label>Test9. Previous Registration Year
						(iss_last_registration_year) </label></th>
				<td>
                <?php $this->iss_last_registration_year_test9();?>
              </td>
			</tr>
			<!-- test10-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test10">Run Test</button>
				</td>
				<th><label>Test10. Set/Get User Preferences
						(iss_set_user_option_list) </label></th>
				<td>
                <?php $this->iss_set_user_option_list_test10();?>
              </td>
			</tr>
			<!-- test11-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test11">Run Test</button>
				</td>
				<th><label>Test11. Exoprt List (iss_get_export_list) </label></th>
				<td>
                <?php $this->iss_get_export_list_test11();?>
              </td>
			</tr>
			<!-- test12-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test12">Run Test</button>
				</td>
				<th><label>Test12. Parent List (iss_get_parents_complete_list) </label>
				</th>
				<td>
                <?php $this->iss_get_parents_complete_list_test12();?>
              </td>
			</tr>
			<!-- test13-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test13">Run Test</button>
				</td>
				<th><label>Test13. Student List (iss_get_students_list) </label></th>
				<td>
                <?php $this->iss_get_complete_students_list_test13();?>
              </td>
			</tr>
			<!-- test14-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test14">Run Test</button>
				</td>
				<th><label>Test14. Archived Parent List
						(iss_get_archived_parents_list, iss_archive_family,
						iss_unarchive_family, iss_get_parent_by_id,
						iss_get_students_by_parentid) </label></th>
				<td>
                <?php $this->iss_archive_family_test14();?>
              </td>
			</tr>
			<!-- Test15-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test15">Run Test</button>
				</td>
				<th><label>Test15. Field Type (iss_field_type) </label></th>
				<td>
                <?php $this->iss_field_type_test15();?>
              </td>
			</tr>
			<!-- Test16-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test16">Run Test</button>
				</td>
				<th><label>Test16. Valid Field Value (iss_field_valid) </label></th>
				<td>
                <?php $this->iss_field_valid_test16();?>
              </td>
			</tr>
			<!-- test17-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test17">Run Test</button>
				</td>
				<th><label>Test17. Parent List (iss_get_parents_list) </label></th>
				<td>
                <?php $this->iss_get_parents_list_test17();?>
              </td>
			</tr>
			<!-- test18-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test18">Run Test</button>
				</td>
				<th><label>Test18. Parent List (iss_get_startwith_parents_list) </label>
				</th>
				<td>
                <?php $this->iss_get_startwith_parents_list_test18();?>
              </td>
			</tr>
			<!-- test19-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test19">Run Test</button>
				</td>
				<th><label>Test19. Parent List (iss_get_search_parents_list) </label>
				</th>
				<td>
                <?php $this->iss_get_search_parents_list_test19();?>
              </td>
			</tr>
			<!-- test20-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test20">Run Test</button>
				</td>
				<th><label>Test20. Student List (iss_get_students_list) </label></th>
				<td>
                <?php $this->iss_get_students_list_test20();?>
              </td>
			</tr>
			<!-- test21-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test21">Run Test</button>
				</td>
				<th><label>Test21. Student List (iss_get_class_students_list) </label>
				</th>
				<td>
                <?php $this->iss_get_class_students_list_test21();?>
              </td>
			</tr>
			<!-- test22-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test22">Run Test</button>
				</td>
				<th><label>Test22. Student List (iss_get_search_students_list) </label>
				</th>
				<td>
                <?php $this->iss_get_search_students_list_test22();?>
              </td>
			</tr>

			<!-- test28-->

			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test28">Run Test</button>
				</td>
				<th><label>Test28. Open Registration Expiry Date
						(iss_registration_expirydate) </label></th>
				<td>
                <?php $this->iss_registration_expirydate_test28();?>
              </td>
			</tr>
			<!-- Test29-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test29">Run Test</button>
				</td>
				<th><label>Test29. Table Name (iss_get_table_name) </label></th>
				<td>
                <?php $this->iss_get_table_name_test29();?>
              </td>
			</tr>
			<!-- test30-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test30">Run Test</button>
				</td>
				<th><label>Test30. Registration Code(iss_parent_insert,
						iss_get_parent_registration_code, iss_get_parent_by_parentid) </label>
				</th>
				<td>
                <?php $this->iss_get_parent_registration_code_test30();?>
              </td>
			</tr>
			<!-- test31-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test31">Run Test</button>
				</td>
				<th><label>test31. New/Update Parent (iss_parent_insert,
						iss_payment_insert, iss_parent_update, iss_get_parent_by_parentid,
						iss_changelog_list) </label></th>
				<td>
                <?php $this->iss_parent_insert_test31();?>
              </td>
			</tr>
			<!-- test32-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test32">Run Test</button>
				</td>
				<th><label>test32. New/Update Student (iss_student_insert,
						iss_registration_insert, iss_student_update,
						iss_get_student_by_studentid, iss_changelog_list) </label></th>
				<td>
                <?php $this->iss_student_insert_test32();?>
              </td>
			</tr>
			<!-- test34-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test34">Run Test</button>
				</td>
				<th><label>test34. New/Update Parent Request
						(iss_process_newparentrequest, iss_process_updateparentrequest) </label>
				</th>
				<td>
                <?php $this->iss_process_newparentrequest_test34();?>
              </td>
			</tr>
			<!-- test35-->
			<tr>
				<td>
					<button type="submit" name="submit" class="button-primary"
						value="test35">Run Test</button>
				</td>
				<th><label>test35. New/Update Student Request
						(iss_process_newstudentrequest, iss_process_updatestudentrequest)
				</label></th>
				<td>
                <?php $this->iss_process_newstudentrequest_test35();?>
              </td>
			</tr>
			<!-- test36-->
		</table>
	</form>
</div>
<?php
		if (isset ( $_GET ['error'] )) {
			echo '<div class="updated"><p><strong>' . __ ( 'Error Saving!', 'iss_unittest_text' ) . '</strong></p></div>';
		}
	}
	public function iss_process_newstudentrequest_test35() {
		if (($this->submit === 'test35') || ($this->submit === 'all')) {
			global $_POST;
			$_POST = array ();
			$regyear = iss_registration_period ();
			$parentid = NULL;
			$studentid = NULL;
			try {
				if ($regyear === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1. NULL Registration Year </span>";
					return;
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
					return;
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
					return;
				}
				
				$_POST ['StudentBirthDate'] = '2000-06-06';
				
				// INSERT STUDENT INSERT TEST
				$errors = array ();
				$studentnew = array ();
				$studentid = iss_process_newstudentrequest ( $_POST, $studentnew, $errors );
				if (($studentid == 0) || (intval ( $studentid ) == 0) || (count ( $errors ) !== 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">3. StudentID : {$studentid} error on iss_process_newstudentrequest </span>";
					return;
				}
				
				// get student record
				$student = iss_get_student_by_studentid ( $studentid, $regyear );
				if ($student === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">4. NULL returned iss_get_parent_by_parentid after iss_process_newstudentrequest </span>";
					return;
				}
				if (($student ['ParentID'] != $parentid) || ($student ['StudentID'] != $studentid) || ($student ['RegistrationYear'] != $regyear) || ($student ['StudentLastName'] != 'TestStudentLastName') || ($student ['StudentFirstName'] != 'TestStudentFirstName') || ($student ['StudentStatus'] != 'active') || ($student ['StudentBirthDate'] != '2000-06-06') || ($student ['StudentGender'] != 'M') || ($student ['ISSGrade'] != '2') || ($student ['RegularSchoolGrade'] != '3')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">5. Incorrect values returned iss_get_parent_by_parentid after iss_process_newstudentrequest </span>";
					return;
				}
				
				// CHANGE LOG TEST ON INSERT
				$changeset = iss_changelog_list ( iss_get_table_name ( "student" ), $parentid, NULL );
				// $count = count($changeset); echo "<br>first changelog {$count}<br>"; foreach($changeset as $row){ echo "<br><br>"; var_dump($row); }
				if ((count ( $changeset ) != 1) || ($changeset [0] ['StudentLastName'] != 'TestStudentLastName')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">6. Incorrect student change log  iss_changelog_list after iss_process_newstudentrequest </span>";
					return;
				}
				
				$changeset = iss_changelog_list ( iss_get_table_name ( "registration" ), $parentid, NULL );
				if ((count ( $changeset ) != 1) || ($changeset [0] ["RegistrationYear"] != $regyear)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">7. Incorrect registration change log  iss_changelog_list after iss_process_newstudentrequest </span>";
					return;
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
					return;
				}
				
				// get parent record
				$student = iss_get_student_by_studentid ( $studentid, $regyear );
				if ($student === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">9. NULL returned iss_get_parent_by_parentid after iss_process_updateparentrequest </span>";
					return;
				}
				if (($student ['ParentID'] != $parentid) || ($student ['StudentID'] != $studentid) || ($student ['RegistrationYear'] != $regyear) || ($student ['StudentLastName'] != 'testchangedlast') || ($student ['StudentFirstName'] != 'testchangedfirst') || ($student ['StudentStatus'] != 'inactive') || ($student ['StudentBirthDate'] != '2014-07-07') || ($student ['StudentGender'] != 'F') || ($student ['ISSGrade'] != '4') || ($student ['RegularSchoolGrade'] != '6')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">10. Incorrect values returned after student tab iss_process_updatestudentrequest </span>";
					return;
				}
				
				iss_write_changelog_vertical ( 'student', $parentid, $studentid );
				iss_write_changelog_vertical ( 'registration', $parentid, $studentid );
			} finally {
				if (($parentid != NULL) && ($regyear != NULL)) {
					if (($studentid != NULL) && ($regyear != NULL)) {
						iss_delete_student_by_studentid ( $studentid );
					}
					iss_delete_parent_by_parentid ( $parentid, $regyear );
					iss_delete_changelog_by_parentid ( $parentid );
				}
			}
			echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
	}
	public function iss_process_newparentrequest_test34() {
		if (($this->submit === 'test34') || ($this->submit === 'all')) {
			global $_POST;
			$_POST = array ();
			$regyear = iss_registration_period ();
			$parentid = NULL;
			try {
				if ($regyear === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Year </span>";
					return;
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
					return;
				}
				
				if (($issparent ['FatherLastName'] != 'TestFatherLastName') || ($issparent ['FatherFirstName'] != 'TestFatherFirstName')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">2. issparent is not populated with POST values after iss_parent_insert </span>";
					return;
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
				// var_dump($errorstring); var_dump($errors);
				if (($parentid == 0) || (intval ( $parentid ) == 0) || (count ( $errors ) !== 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">3. ParentID : {$parentid} error on iss_process_newparentrequest </span>";
					return;
				}
				
				// get parent record
				$parent = iss_get_parent_by_parentid ( $parentid, $regyear );
				if ($parent === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">4. NULL returned iss_get_parent_by_parentid after iss_process_newparentrequest </span>";
					return;
				}
				if (($parent ['ParentID'] != $parentid) || ($parent ['RegistrationYear'] != $regyear) || ($parent ['FatherLastName'] != 'TestFatherLastName') || ($parent ['FatherFirstName'] != 'TestFatherFirstName') || ($parent ['RegistrationCode'] != NULL) || ($parent ['RegistrationExpiration'] != NULL) || // default
($parent ['RegistrationComplete'] != 'New') || ($parent ['PaidInFull'] != 'No')) // default
{
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">5. Incorrect values returned iss_get_parent_by_parentid after iss_process_newparentrequest </span>";
					return;
				}
				
				// CHANGE LOG TEST ON INSERT
				$changeset = iss_changelog_list ( iss_get_table_name ( "parent" ), $parentid, NULL );
				// $count = count($changeset); echo "<br>first changelog {$count}<br>"; foreach($changeset as $row){ echo "<br><br>"; var_dump($row); }
				if ((count ( $changeset ) != 1) || ($changeset [0] ['FatherLastName'] != 'TestFatherLastName')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">6. Incorrect parent change log  iss_changelog_list after iss_process_newparentrequest </span>";
					return;
				}
				
				$changeset = iss_changelog_list ( iss_get_table_name ( "payment" ), $parentid, NULL );
				if ((count ( $changeset ) != 1) || ($changeset [0] ["RegistrationYear"] != $regyear)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">7. Incorrect payment change log  iss_changelog_list after iss_process_newparentrequest </span>";
					return;
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
					return;
				}
				
				// get parent record
				$parent = iss_get_parent_by_parentid ( $parentid, $regyear );
				
				if ($parent === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">9. NULL returned iss_get_parent_by_parentid after iss_process_updateparentrequest </span>";
					return;
				}
				if (($parent ['ParentID'] != $parentid) || ($parent ['RegistrationYear'] != $regyear) || ($parent ['FatherLastName'] != 'testchangedlast') || ($parent ['FatherFirstName'] != 'testchangedfirst')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">10. Incorrect values returned after parent tab iss_process_updateparentrequest </span>";
					return;
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
					return;
				}
				
				// get parent record
				$parent = iss_get_parent_by_parentid ( $parentid, $regyear );
				
				if ($parent === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">12. NULL returned iss_get_parent_by_parentid after iss_process_updateparentrequest </span>";
					return;
				}
				if (($parent ['ParentID'] != $parentid) || ($parent ['Comments'] != 'Full Aid') || ($parent ['RegistrationExpiration'] != NULL) || ($parent ['RegistrationComplete'] != 'Complete') || ($parent ['PaidInFull'] != 'Yes')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">13. Incorrect values returned after complete tab iss_process_updateparentrequest </span>";
					return;
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
				$result = iss_process_updateparentrequest ( 'home', $issparent, $_POST, $errors ); // var_dump($errors);
				if (($result == 0) || (count ( $errors ) != 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">14. ParentID : {$parentid} error home tab iss_process_updateparentrequest </span>";
					return;
				}
				
				// get parent record
				$parent = iss_get_parent_by_parentid ( $parentid, $regyear );
				
				if ($parent === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">15. NULL returned iss_get_parent_by_parentid after iss_process_updateparentrequest </span>";
					return;
				}
				if (($parent ['ParentID'] != $parentid) || ($parent ['HomeCity'] != 'Complete') || ($parent ['HomePhone'] != 'Yes')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">16. Incorrect values returned after home tab iss_process_updateparentrequest </span>";
					return;
				}
				
				// CONTACT TAB TEST
				$_POST ['EmergencyContactName1'] = 'TestEmergencyContactName1';
				$_POST ['EmergencyContactPhone1'] = 'Yes';
				$_POST ['EmergencyContactName2'] = 'TestEmergencyContactName2';
				$_POST ['EmergencyContactPhone2'] = 'Yes';
				
				// update contact fields
				$errors = array ();
				$issparent = iss_get_parent_by_parentid ( $parentid, $regyear );
				$result = iss_process_updateparentrequest ( 'contact', $issparent, $_POST, $errors ); // var_dump($errors);
				if (($result == 0) || (count ( $errors ) != 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">17. ParentID : {$parentid} error contact tab iss_process_updateparentrequest </span>";
					return;
				}
				
				// get parent record
				$parent = iss_get_parent_by_parentid ( $parentid, $regyear );
				
				if ($parent === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">18. NULL returned iss_get_parent_by_parentid after iss_process_updateparentrequest </span>";
					return;
				}
				if (($parent ['ParentID'] != $parentid) || ($parent ['EmergencyContactName1'] != 'TestEmergencyContactName1') || ($parent ['EmergencyContactPhone1'] != 'Yes')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">19. Incorrect values returned after contact tab iss_process_updateparentrequest </span>";
					return;
				}
				
				iss_write_changelog_vertical ( 'parent', $parentid, NULL );
				iss_write_changelog_vertical ( 'payment', $parentid, NULL );
			} finally {
				if (($parentid != NULL) && ($regyear != NULL)) {
					iss_delete_parent_by_parentid ( $parentid, $regyear );
					iss_delete_changelog_by_parentid ( $parentid );
				}
			}
			echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
	}
	public function iss_adminpref_registrationfee_installments_test33() {
		if (($this->submit === 'test33') || ($this->submit === 'all')) {
			$errors = array ();
			$name = iss_adminpref_registrationfee_installments ();
			if ($name === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Fee Installments </span>";
				return;
			}
			if (! iss_field_valid ( 'ParentID', $name, $errors, '' )) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid Registration Fee Installments {$name} </span>";
				return;
			}
			
			echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
	}
	public function iss_student_insert_test32() {
		if (($this->submit === 'test32') || ($this->submit === 'all')) {
			$regyear = iss_registration_period ();
			$parentid = NULL;
			$studentid = NULL;
			try {
				if ($regyear === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1. NULL Registration Year </span>";
					return;
				}
				$sdata ['RegistrationYear'] = $regyear;
				$sdata ['ParentID'] = 'new';
				$sdata ['FatherLastName'] = 'TestFatherLastName';
				$sdata ['FatherFirstName'] = 'TestFatherFirstName';
				$sdata ['PaidInFull'] = 'No';
				$parentid = iss_parent_insert ( $sdata );
				if (($parentid == 0) || (intval ( $parentid ) == 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">2 ParentID : {$parentid} error on iss_parent_insert </span>";
					return;
				}
				
				$sdata ['StudentID'] = 'new';
				$sdata ['ISSGrade'] = '2';
				$sdata ['RegularSchoolGrade'] = 'KG';
				$sdata ['StudentLastName'] = 'TestStudentLastName';
				$sdata ['StudentFirstName'] = 'TestStudentFirstName';
				
				$studentid = iss_student_insert ( $sdata );
				if (($studentid != 0) || (intval ( $studentid ) != 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">3 StudentID : {$studentid} error on iss_student_insert </span>";
					return;
				}
				
				$sdata ['ParentID'] = $parentid;
				
				// insert student record
				$studentid = iss_student_insert ( $sdata );
				if (($studentid == 0) || (intval ( $studentid ) == 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">4 StudentID : {$studentid} error on iss_student_insert </span>";
					return;
				}
				
				// get student record
				$student = iss_get_student_by_studentid ( $studentid, $regyear );
				if ($student === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL returned iss_get_student_by_studentid after iss_student_insert </span>";
					return;
				}
				if (($student ['ParentID'] != $parentid) || ($student ['StudentID'] != $studentid) || ($student ['StudentLastName'] != 'TestStudentLastName') || ($student ['StudentFirstName'] != 'TestStudentFirstName') || ($student ['ISSGrade'] != '2')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">5 Incorrect values returned iss_get_student_by_studentid after iss_student_insert </span>";
					return;
				}
				
				// check change log
				$changeset = iss_changelog_list ( iss_get_table_name ( "student" ), $parentid, $studentid );
				// $count = count($changeset); echo "<br>first changelog {$count}<br>"; foreach($changeset as $row){ echo "<br><br>"; var_dump($row); }
				if ((count ( $changeset ) != 1) || ($changeset [0] ['StudentLastName'] != 'TestStudentLastName')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">6 Incorrect student change log  iss_changelog_list after iss_student_insert </span>";
					return;
				}
				$changeset = iss_changelog_list ( iss_get_table_name ( "registration" ), $parentid, $studentid );
				if ((count ( $changeset ) != 1) || ($changeset [0] ["RegistrationYear"] != $regyear)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">7 Incorrect registration change log  iss_changelog_list after iss_student_insert </span>";
					return;
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
					return;
				}
				
				// get student record
				$student = iss_get_student_by_studentid ( $studentid, $regyear );
				if ($student === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL returned iss_get_student_by_studentid after iss_student_insert </span>";
					return;
				}
				if (($student ['ParentID'] != $parentid) || ($student ['StudentID'] != $studentid) || ($student ['StudentLastName'] != 'testchangedlastname') || ($student ['StudentFirstName'] != 'testchangedfirstname') || ($student ['ISSGrade'] != '4') | ($student ['RegularSchoolGrade'] != '5')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">9 Incorrect values returned iss_get_student_by_studentid after iss_student_update </span>";
					return;
				}
				
				// check the changed log
				$changeset = iss_changelog_list ( iss_get_table_name ( "student" ), $parentid, $studentid );
				if ((count ( $changeset ) != 2) || ($changeset [1] ['StudentLastName'] != 'TestStudentLastName') || ($changeset [0] ['StudentLastName'] != 'testchangedlastname')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect student change log  iss_changelog_list after iss_student_update </span>";
					return;
				}
				$changeset = iss_changelog_list ( iss_get_table_name ( "registration" ), $parentid, $studentid );
				if ((count ( $changeset ) != 2) || ($changeset [1] ['ISSGrade'] != '2') || ($changeset [0] ['ISSGrade'] != '4') || ($changeset [1] ['RegularSchoolGrade'] != 'KG') || ($changeset [0] ['RegularSchoolGrade'] != '5')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">10 Incorrect student change log  iss_changelog_list after iss_student_update </span>";
					return;
				}
			} finally {
				if (($parentid != NULL) && ($regyear != NULL)) {
					if (($studentid != NULL) && ($regyear != NULL)) {
						iss_delete_student_by_studentid ( $studentid );
					}
					iss_delete_parent_by_parentid ( $parentid, $regyear );
					iss_delete_changelog_by_parentid ( $parentid );
				}
			}
			echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
	}
	public function iss_parent_insert_test31() {
		if (($this->submit === 'test31') || ($this->submit === 'all')) {
			$regyear = iss_registration_period ();
			$parentid = NULL;
			try {
				if ($regyear === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1. NULL Registration Year </span>";
					return;
				}
				$sdata ['RegistrationYear'] = $regyear;
				$sdata ['ParentID'] = 'new';
				$parentid = iss_parent_insert ( $sdata );
				if (($parentid != 0) || (intval ( $parentid ) != 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">2. ParentID : {$parentid} minimum required field failed iss_parent_insert </span>";
					return;
				}
				
				$sdata ['FatherLastName'] = 'TestFatherLastName';
				$sdata ['FatherFirstName'] = 'TestFatherFirstName';
				$sdata ['PaidInFull'] = 'No';
				
				// insert parent record
				$parentid = iss_parent_insert ( $sdata );
				if (($parentid == 0) || (intval ( $parentid ) == 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">3. ParentID : {$parentid} error on iss_parent_insert </span>";
					return;
				}
				
				// get parent record
				$parent = iss_get_parent_by_parentid ( $parentid, $regyear );
				if ($parent === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">4. NULL returned iss_get_parent_by_parentid after iss_parent_insert </span>";
					return;
				}
				if (($parent ['ParentID'] != $parentid) || ($parent ['RegistrationYear'] != $regyear) || ($parent ['FatherLastName'] != 'TestFatherLastName') || ($parent ['FatherFirstName'] != 'TestFatherFirstName') || ($parent ['RegistrationCode'] != NULL) || ($parent ['RegistrationExpiration'] != NULL) || ($parent ['RegistrationComplete'] != 'New') || ($parent ['PaidInFull'] != 'No')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">5. Incorrect values returned iss_get_parent_by_parentid after iss_parent_insert </span>";
					return;
				}
				
				// check change log
				$changeset = iss_changelog_list ( iss_get_table_name ( "parent" ), $parentid, NULL );
				// $count = count($changeset); echo "<br>first changelog {$count}<br>"; foreach($changeset as $row){ echo "<br><br>"; var_dump($row); }
				if ((count ( $changeset ) != 1) || ($changeset [0] ['FatherLastName'] != 'TestFatherLastName')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">6. Incorrect parent change log  iss_changelog_list after iss_parent_insert </span>";
					return;
				}
				
				$changeset = iss_changelog_list ( iss_get_table_name ( "payment" ), $parentid, NULL );
				if ((count ( $changeset ) != 1) || ($changeset [0] ["RegistrationYear"] != $regyear)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">7. Incorrect payment change log  iss_changelog_list after iss_parent_insert </span>";
					return;
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
					return;
				}
				
				// get parent record
				$parent = iss_get_parent_by_parentid ( $parentid, $regyear );
				if ($parent === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">9. NULL returned iss_get_parent_by_parentid after iss_parent_update </span>";
					return;
				}
				if (($parent ['ParentID'] != $parentid) || ($parent ['RegistrationYear'] != $regyear) || ($parent ['FatherLastName'] != 'testchangedlast') || ($parent ['FatherFirstName'] != 'testchangedfirst') || ($parent ['Comments'] != 'Full Aid') || ($parent ['RegistrationExpiration'] != NULL) || ($parent ['RegistrationComplete'] != 'Complete') || ($parent ['PaidInFull'] != 'Yes')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect values returned iss_get_parent_by_parentid after iss_parent_update </span>";
					return;
				}
				
				// check the changed log
				$changeset = iss_changelog_list ( iss_get_table_name ( "parent" ), $parentid, NULL );
				if ((count ( $changeset ) != 2) || ($changeset [1] ['FatherLastName'] != 'TestFatherLastName') || ($changeset [0] ['FatherLastName'] != 'testchangedlast')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">10. Incorrect parent change log  iss_changelog_list after iss_parent_update </span>";
					return;
				}
				$changeset = iss_changelog_list ( iss_get_table_name ( "payment" ), $parentid, NULL );
				if ((count ( $changeset ) != 2) || ($changeset [1] ['PaidInFull'] != "No") || ($changeset [0] ['PaidInFull'] != "Yes")) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect payment change log  iss_changelog_list after iss_parent_update </span>";
					return;
				}
			} finally {
				if (($parentid != NULL) && ($regyear != NULL)) {
					iss_delete_parent_by_parentid ( $parentid, $regyear );
					iss_delete_changelog_by_parentid ( $parentid );
				}
			}
			echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
	}
	public function iss_get_parent_registration_code_test30() {
		if (($this->submit === 'test30') || ($this->submit === 'all')) {
			$regyear = iss_registration_period ();
			$parentid = NULL;
			try {
				if ($regyear === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Year </span>";
					return;
				}
				$sdata ['RegistrationYear'] = $regyear;
				$sdata ['ParentID'] = 'new';
				$sdata ['FatherLastName'] = 'TestFatherLastName';
				$sdata ['FatherFirstName'] = 'TestFatherFirstName';
				$parentid = iss_parent_insert ( $sdata );
				if (($parentid == 0) || (intval ( $parentid ) == 0)) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> ParentID : {$parentid} error on iss_parent_insert </span>";
					return;
				}
				$parent = iss_get_parent_by_parentid ( $parentid, $regyear );
				if ($parent === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL returned iss_get_parent_by_parentid after iss_parent_insert </span>";
					return;
				}
				
				$code = iss_get_parent_registration_code ( $parent ['ID'] );
				if ($code === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL code returned iss_get_parent_registration_code </span>";
					return;
				}
				
				$parent = iss_get_parent_by_parentid ( $parentid, $regyear );
				if ($parent === NULL) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL returned iss_get_parent_by_parentid after iss_parent_insert </span>";
					return;
				}
				if (($parent ['ParentID'] != $parentid) || ($parent ['RegistrationYear'] != $regyear) || ($parent ['FatherFirstName'] != 'TestFatherFirstName') || ($parent ['RegistrationCode'] != $code) || ($parent ['RegistrationExpiration'] === NULL) || ($parent ['RegistrationComplete'] != 'Open')) {
					echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect values returned iss_get_parent_registration_code  </span>";
					return;
				}
			} finally {
				if (($parentid != NULL) && ($regyear != NULL)) {
					iss_delete_parent_by_parentid ( $parentid, $regyear );
					iss_delete_changelog_by_parentid ( $parentid );
				}
			}
			echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
	}
	public function iss_get_table_name_test29() {
		global $wpdb;
		if (($this->submit === 'test29') || ($this->submit === 'all')) {
			$tab = iss_get_table_name ( '' );
			if ($tab != NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">  Incorrect table  name for empty</span>";
				return;
			}
			$tab = iss_get_table_name ( "parent" );
			if ($tab != ($wpdb->prefix . 'iss_parent')) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> {$tab}  Incorrect table  name for parent</span>";
				return;
			}
			if (iss_get_table_name ( 'parents' ) != $wpdb->prefix . 'iss_parents') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">  Incorrect table  name for parents</span>";
				return;
			}
			if (iss_get_table_name ( 'registration' ) != $wpdb->prefix . 'iss_registration') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">  Incorrect table  name for registration</span>";
				return;
			}
			if (iss_get_table_name ( 'student' ) != $wpdb->prefix . 'iss_student') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">  Incorrect table  name for student </span>";
				return;
			}
			// if(iss_get_table_name('students') != $wpdb->prefix .'iss_students') {
			// echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect table name for students</span>";
			// return;
			// }
			if (iss_get_table_name ( 'payment' ) != $wpdb->prefix . 'iss_payment') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">  Incorrect table  name for payment</span>";
				return;
			}
			if (iss_get_table_name ( 'changelog' ) != $wpdb->prefix . 'iss_changelog') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">  Incorrect table  name for changelog </span>";
				return;
			}
			echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
	}
	public function iss_registration_expirydate_test28() {
		if (($this->submit === 'test28') || ($this->submit === 'all')) {
			$date = current_time ( 'mysql' );
			$edate = iss_registration_expirydate ();
			if ($edate === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Expiration Date </span>";
				return;
			}
			$errors = array ();
			if (! iss_field_valid ( 'created', $edate, $errors, '' )) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Not Valid DateTime {$edate} </span>";
				return;
			}
			
			echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
	}
	public function iss_get_registrationyear_list_test1() {
		if (($this->submit === 'test1') || ($this->submit === 'all')) {
			$list = iss_get_registrationyear_list ();
			if ($list === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL List </span>";
				return;
			}
			if (count ( $list ) == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item list </span>";
				return;
			}
			
			echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
	}
	public function iss_get_next_tab_test2() {
		if (($this->submit === 'test2') || ($this->submit === 'all')) {
			$tab = iss_get_next_tab ( '' );
			if ($tab != 'view') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Empty -> {$tab} Tab </span>";
				return;
			}
			if (iss_get_next_tab ( 'parent' ) != 'home') {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Parent -> Home tab </span>";
				return;
			}
			if (iss_get_next_tab ( 'home' ) != 'contact') {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Home -> Contact tab </span>";
				return;
			}
			if (iss_get_next_tab ( 'contact' ) != 'student') {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> contact -> student tab </span>";
				return;
			}
			if (iss_get_next_tab ( 'student' ) != 'complete') {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> student -> complete tab </span>";
				return;
			}
			if (iss_get_next_tab ( 'complete' ) != 'view') {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> complete -> view tab </span>";
				return;
			}
			if (! iss_valid_tabname ( 'complete' ) || ! iss_valid_tabname ( 'home' ) || ! iss_valid_tabname ( 'contact' ) || ! iss_valid_tabname ( 'student123' ) || ! iss_valid_tabname ( 'studentnew' ) || ! iss_valid_tabname ( 'parent' ) || ! iss_valid_tabname ( 'view' )) {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> iss_valid_tabname failing </span>";
				return;
			}
			$rlist = iss_get_requiredfields_by_tabname ( 'contact' );
			$tlist = iss_get_tabfields_by_tabname ( 'contact' );
			if (($rlist === NULL) || (count ( $rlist ) == 0) || ($tlist === NULL) || (count ( $tlist ) == 0)) {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> contact tab NULL iss_get_tabfields_by_tabname or iss_get_requiredfields_by_tabname </span>";
				return;
			}
			$rlist = iss_get_requiredfields_by_tabname ( 'home' );
			$tlist = iss_get_tabfields_by_tabname ( 'home' );
			if (($rlist === NULL) || (count ( $rlist ) == 0) || ($tlist === NULL) || (count ( $tlist ) == 0)) {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> home tab NULL iss_get_tabfields_by_tabname or iss_get_requiredfields_by_tabname </span>";
				return;
			}
			$rlist = iss_get_requiredfields_by_tabname ( 'parent' );
			$tlist = iss_get_tabfields_by_tabname ( 'parent' );
			if (($rlist === NULL) || (count ( $rlist ) == 0) || ($tlist === NULL) || (count ( $tlist ) == 0)) {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> parent tab NULL iss_get_tabfields_by_tabname or iss_get_requiredfields_by_tabname </span>";
				return;
			}
			$rlist = iss_get_requiredfields_by_tabname ( 'studentnew' );
			$tlist = iss_get_tabfields_by_tabname ( 'studentnew' );
			if (($rlist === NULL) || (count ( $rlist ) == 0) || ($tlist === NULL) || (count ( $tlist ) == 0)) {
				
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> studentnew tab NULL iss_get_tabfields_by_tabname or iss_get_requiredfields_by_tabname </span>";
				return;
			}
			$rlist = iss_get_requiredfields_by_tabname ( 'complete' );
			$tlist = iss_get_tabfields_by_tabname ( 'complete' );
			if (($rlist === NULL) || (count ( $rlist ) > 0) || ($tlist === NULL) || (count ( $tlist ) == 0)) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> complete tab NULL iss_get_tabfields_by_tabname or iss_get_requiredfields_by_tabname </span>";
				return;
			}
			echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
	}
	public function iss_get_school_name_test3() {
		if (($this->submit === 'test3') || ($this->submit === 'all')) {
			$result = true;
			$name = iss_get_school_name ();
			if ($name === NULL) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Name </span>";
			}
			if ($result && ($name != 'Islamic School of Silicon Valley')) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect name {$name} </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
	}
	public function iss_adminpref_schoolname_test4() {
		if (($this->submit === 'test4') || ($this->submit === 'all')) {
			$result = true;
			$name = iss_adminpref_schoolname ();
			if ($name === NULL) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Name </span>";
			}
			if ($result && ($name != 'Islamic School of Silicon Valley')) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect name {$name} </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
	}
	public function iss_adminpref_registrationyear_test5() {
		if (($this->submit === 'test5') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_adminpref_registrationyear ();
			if ($name === NULL) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Year </span>";
			}
			if ($result && ! iss_field_valid ( 'RegistrationYear', $name, $errors, '' )) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid registration year {$name} </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
	}
	public function iss_userpref_registrationyear_test6() {
		if (($this->submit === 'test6') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_userpref_registrationyear ();
			if ($name === NULL) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Year </span>";
			}
			if ($result && ! iss_field_valid ( 'RegistrationYear', $name, $errors, '' )) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid registration year {$name} </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
	}
	public function iss_registration_period_test7() {
		if (($this->submit === 'test7') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_registration_period ();
			if ($name === NULL) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Year </span>";
			}
			if ($result && ! iss_field_valid ( 'RegistrationYear', $name, $errors, '' )) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid registration period {$name} </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
	}
	public function iss_next_registration_year_test8() {
		if (($this->submit === 'test8') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_next_registration_year ();
			if ($name === NULL) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Year </span>";
			}
			if ($result && ! iss_field_valid ( 'RegistrationYear', $name, $errors, '' )) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid registration period {$name} </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
	}
	public function iss_last_registration_year_test9() {
		if (($this->submit === 'test9') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_last_registration_year ();
			
			if ($name === NULL) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Year </span>";
			}
			if ($result && ! iss_field_valid ( 'RegistrationYear', $name, $errors, '' )) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid registration period {$name} </span>";
			}
			$newregyear = iss_next_registration_year ();
			list ( $y1, $y2 ) = explode ( "-", $newregyear );
			$y1int = intval ( $y1 );
			$y2int = intval ( $y2 );
			$regyear = ($y1int - 1) . '-' . ($y1);
			list ( $y3, $y4 ) = explode ( "-", $name );
			
			if ($result && ($y1 != $y4)) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect last registration period {$name} </span>";
			}
			if ($result && ($name != $regyear)) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect last registration period {$name} </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
	}
	public function iss_set_user_option_list_test10() {
		if (($this->submit === 'test10') || ($this->submit === 'all')) {
			$originalList = array (
					"iss_user_registrationyear" => iss_userpref_registrationyear () 
			);
			
			$changelog = array ();
			$changelog ['iss_user_registrationyear'] = '2010-2011';
			iss_set_user_option_list ( $changelog );
			$returnlog = iss_get_user_option_list ();
			
			$result = true;
			if ($returnlog === NULL) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL User Option List </span>";
			}
			if ($result && (count ( $returnlog ) == 0)) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero items in User Option List </span>";
			}
			if ($result && ('2010-2011' != $returnlog ['iss_user_registrationyear'] [0])) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid user preference value {$returnlog['iss_user_registrationyear'][0]} </span>";
			}
			
			iss_set_user_option_list ( $originalList );
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
	}
	public function iss_get_export_list_test11() {
		if (($this->submit === 'test11') || ($this->submit === 'all')) {
			$regyear = iss_registration_period ();
			$list = iss_get_export_list ( $regyear );
			if ($list === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Export List </span>";
				return;
			}
			if (count ( $list ) == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item export list </span>";
				return;
			}
			if ($list [0] ['RegistrationYear'] != $regyear) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect registration year {$list[0]['RegistrationYear']} </span>";
				return;
			}
			echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
	}
	public function iss_get_parents_complete_list_test12() {
		if (($this->submit === 'test12') || ($this->submit === 'all')) {
			$result = true;
			$regyear = iss_registration_period ();
			$list = iss_get_parents_complete_list ( $regyear );
			if ($list === NULL) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Parent List </span>";
			}
			if ($result && (count ( $list ) == 0)) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item parent list </span>";
			}
			if ($result && ($list [0] ['RegistrationYear'] != $regyear)) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect registration year {$list[0]['RegistrationYear']} </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
	}
	public function iss_get_complete_students_list_test13() {
		if (($this->submit === 'test13') || ($this->submit === 'all')) {
			$regyear = iss_registration_period ();
			$list = iss_get_students_list ( $regyear, "*" );
			if ($list === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Student List </span>";
				return;
			}
			if (count ( $list ) == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item student list </span>";
				return;
			}
			if ($list [0] ['RegistrationYear'] != $regyear) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect registration year {$list[0]['RegistrationYear']} </span>";
				return;
			}
			echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
	}
	public function iss_archive_family_test14() {
		if (($this->submit === 'test14') || ($this->submit === 'all')) {
			$regyear = iss_registration_period ();
			$plist = iss_get_parents_complete_list ( $regyear );
			if (count ( $plist ) == 0) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Cannot Test Arvhived Parent List </span>";
				return;
			}
			$aid = $plist [0] ['ID'];
			$aresult = iss_archive_family ( $aid ); // archive a parent record
			
			if ($aresult == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">  iss_archive_family Failed </span>";
				return;
			}
			
			$parent = iss_get_parent_by_id ( $aid );
			if ($parent === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">  iss_get_parent_by_id Failed </span>";
				return;
			}
			if ($parent ['ParentStatus'] == 'active') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">  iss_archive_family Failed </span>";
				return;
			}
			$students = iss_get_students_by_parentid ( $parent ['ParentID'], $regyear );
			if ($students === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">  iss_get_students_by_parentid Failed </span>";
				return;
			}
			if (count ( $students ) == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item students list </span>";
				return;
			}
			if ($students [0] ['StudentStatus'] == 'active') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">  iss_archive_family Failed </span>";
				return;
			}
			
			$list = iss_get_archived_parents_list ( $regyear );
			if ($list === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Arvhived Parent List </span>";
				return;
			}
			if (count ( $list ) == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item parent list </span>";
				return;
			}
			if ($list [0] ['RegistrationYear'] != $regyear) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect registration year {$list[0]['RegistrationYear']} </span>";
				return;
			}
			
			$uresult = iss_unarchive_family ( $aid ); // unarchive the parent record
			if ($uresult == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">  iss_unarchive_family Failed </span>";
				return;
			}
			
			$parent = iss_get_parent_by_id ( $aid );
			if ($parent === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">  iss_get_parent_by_id Failed </span>";
				return;
			}
			if ($parent ['ParentStatus'] == 'inactive') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">  iss_unarchive_family Failed </span>";
				return;
			}
			$students = iss_get_students_by_parentid ( $parent ['ParentID'], $regyear );
			if ($students === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">  iss_get_students_by_parentid Failed </span>";
				return;
			}
			if (count ( $students ) == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item students list </span>";
				return;
			}
			if ($students [0] ['StudentStatus'] == 'inactive') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">  iss_unarchive_family Failed </span>";
				return;
			}
			echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
	}
	public function iss_field_type_test15() {
		if (($this->submit === 'test15') || ($this->submit === 'all')) {
			$type = iss_field_type ( 'RegistrationYear' );
			if ($type != '%s') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect type {$type} for RegistrationYear </span>";
				return;
			}
			$type = iss_field_type ( 'ParentID' );
			if ($type != '%d') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect type {$type} for ParentID </span>";
				return;
			}
			$type = iss_field_type ( 'StudentBirthDate' );
			if ($type != '%s') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect type {$type} for StudentBirthDate </span>";
				return;
			}
			$type = iss_field_type ( 'StudentFirstName' );
			if ($type != '%s') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect type {$type} for StudentFirstName </span>";
				return;
			}
			$type = iss_field_type ( 'TotalAmountDue' );
			if ($type != '%f') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect type {$type} for TotalAmountDue </span>";
				return;
			}
			$type = iss_field_type ( 'SpecialNeedNote' );
			if ($type != '%s') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect type {$type} for SpecialNeedNote </span>";
				return;
			}
			echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
	}
	public function iss_field_valid_test16() {
		if (($this->submit === 'test16') || ($this->submit === 'all')) {
			$errors = array ();
			$field = 'RegistrationYear';
			$result = iss_field_valid ( $field, '', $errors, '' );
			if ($result == true) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> 1. RegistrationYear required validation failed. {$errors[$field]}</span>";
				return;
			}
			if ($errors [$field] != 'Registration Period is required.') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1.  Incorrect validation error {$errors[$field]} </span>";
				return;
			}
			$result = iss_field_valid ( $field, '2015', $errors, '' );
			if ($result == true) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">2. RegistrationYear value validation failed. {$errors[$field]}</span>";
				return;
			}
			if ($errors [$field] != 'Registration Period is not a valid.') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">2.  Incorrect validation error {$errors[$field]} </span>";
				return;
			}
			$result = iss_field_valid ( $field, '20152015201520152015', $errors, '' );
			if ($result == true) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">3. RegistrationYear value validation failed. {$errors[$field]}</span>";
				return;
			}
			if ($errors [$field] != 'Registration Period is too long (10).') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">3.  Incorrect validation error {$errors[$field]} </span>";
				return;
			}
			$result = iss_field_valid ( $field, '2015-2015', $errors, '' );
			if ($result == true) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">4. RegistrationYear value validation failed. {$errors[$field]}</span>";
				return;
			}
			if ($errors [$field] != 'Registration Period is not a valid.') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">4.  Incorrect validation error {$errors[$field]} </span>";
				return;
			}
			$result = iss_field_valid ( $field, '2015-2016', $errors, '' );
			if ($result === false) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">5. RegistrationYear value validation failed. {$errors[$field]} </span>";
				return;
			}
			
			$field = 'ParentID';
			$result = iss_field_valid ( $field, 'abc', $errors, '' );
			if ($result == true) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1. ParentID value validation failed. {$errors[$field]}</span>";
				return;
			}
			if ($errors [$field] != 'Parent ID is not a valid integer.') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1.  Incorrect validation error {$errors[$field]} </span>";
				return;
			}
			$result = iss_field_valid ( $field, '2015', $errors, '' );
			if ($result === false) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">5. ParentID value validation failed. {$errors[$field]} </span>";
				return;
			}
			
			$field = 'StudentBirthDate';
			$result = iss_field_valid ( $field, 'abc', $errors, '' );
			if ($result == true) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1. StudentBirthDate value validation failed. {$errors[$field]}</span>";
				return;
			}
			if ($errors [$field] != 'Student Birth Date is a not valid date (yyyy-mm-dd).') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1.  Incorrect validation error {$errors[$field]} </span>";
				return;
			}
			$result = iss_field_valid ( $field, '2015-11-11', $errors, '' );
			if ($result === false) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">5. StudentBirthDate value validation failed. {$errors[$field]} </span>";
				return;
			}
			
			$field = 'TotalAmountDue';
			$result = iss_field_valid ( $field, 'abc', $errors, '' );
			if ($result == true) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1. TotalAmountDue value validation failed. {$errors[$field]}</span>";
				return;
			}
			if ($errors [$field] != 'Total Amount Due is not a valid amount.') {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">1.  Incorrect validation error {$errors[$field]} </span>";
				return;
			}
			$result = iss_field_valid ( $field, '2015.90', $errors, '' );
			if ($result === false) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">5. TotalAmountDue value validation failed. {$errors[$field]} </span>";
				return;
			}
			
			echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
	}
	public function iss_get_parents_list_test17() {
		if (($this->submit === 'test17') || ($this->submit === 'all')) {
			$result = true;
			$regyear = iss_registration_period ();
			$columns = "ID, ParentID, FatherLastName, FatherFirstName, RegistrationComplete, RegistrationYear";
			$list = iss_get_parents_list ( $regyear, $columns );
			if ($list === NULL) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Parent List </span>";
			}
			if ($result && (count ( $list ) == 0)) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item parent list </span>";
			}
			if ($result && ($list [0] ['RegistrationYear'] != $regyear)) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect registration year {$list[0]['RegistrationYear']} </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
	}
	public function iss_get_startwith_parents_list_test18() {
		if (($this->submit === 'test18') || ($this->submit === 'all')) {
			$result = true;
			$regyear = iss_registration_period ();
			$columns = "ID, ParentID, FatherLastName, FatherFirstName, RegistrationComplete, RegistrationYear";
			$list = iss_get_startwith_parents_list ( $regyear, $columns, 'S' );
			if ($list === NULL) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Parent List </span>";
			}
			if ($result && (count ( $list ) == 0)) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item parent list </span>";
			}
			if ($result && ($list [0] ['RegistrationYear'] != $regyear)) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect registration year {$list[0]['RegistrationYear']} </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
	}
	public function iss_get_search_parents_list_test19() {
		if (($this->submit === 'test19') || ($this->submit === 'all')) {
			$result = true;
			$regyear = iss_registration_period ();
			$columns = "ID, ParentID, FatherLastName, FatherFirstName, RegistrationComplete, RegistrationYear";
			$list = iss_get_search_parents_list ( $regyear, $columns, 'syed' );
			if ($list === NULL) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Parent List </span>";
			}
			if ($result && (count ( $list ) == 0)) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item parent list </span>";
			}
			if ($result && ($list [0] ['RegistrationYear'] != $regyear)) {
				$result = false;
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect registration year {$list[0]['RegistrationYear']} </span>";
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
	}
	public function iss_get_students_list_test20() {
		if (($this->submit === 'test20') || ($this->submit === 'all')) {
			$columns = "ID,StudentID,ParentId,StudentFirstName, StudentLastName,ISSGrade,StudentGender";
			$regyear = iss_registration_period ();
			$list = iss_get_students_list ( $regyear, $columns );
			if ($list === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Student List </span>";
				return;
			}
			if (count ( $list ) == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item student list </span>";
				return;
			}
			if ($list [0] ['RegistrationYear'] == $regyear) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect registration year {$list[0]['RegistrationYear']} <i class=\"glyphicon glyphicon-remove\" ></i></span>";
				return;
			}
			
			echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
	}
	public function iss_get_class_students_list_test21() {
		if (($this->submit === 'test21') || ($this->submit === 'all')) {
			$regyear = iss_registration_period ();
			$columns = "ID,StudentID,ParentID,StudentFirstName, StudentLastName,ISSGrade,StudentGender,RegistrationYear";
			$list = iss_get_class_students_list ( $regyear, $columns, '4' );
			if ($list === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Student List </span>";
				return;
			}
			if (count ( $list ) == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item student list </span>";
				return;
			}
			if ($list [0] ['RegistrationYear'] != $regyear) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\">Incorrect registration year {$list[0]['RegistrationYear']}</span>";
				return;
			}
			
			echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
	}
	public function iss_get_search_students_list_test22() {
		if (($this->submit === 'test22') || ($this->submit === 'all')) {
			$columns = "ID,StudentID,ParentId,StudentFirstName, StudentLastName,ISSGrade,StudentGender,RegistrationYear";
			$regyear = iss_registration_period ();
			$list = iss_get_search_students_list ( $regyear, $columns, 'syed' );
			if ($list === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Student List </span>";
				return;
			}
			if (count ( $list ) == 0) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Zero item student list </span>";
				return;
			}
			if ($list [0] ['RegistrationYear'] != $regyear) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Incorrect registration year {$list[0]['RegistrationYear']} <i class=\"glyphicon glyphicon-remove\" ></i></span>";
				return;
			}
			echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
		}
	}
	public function iss_adminpref_openregistrationdays_test23() {
		if (($this->submit === 'test23') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_adminpref_openregistrationdays ();
			if ($name === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Open Registration Days </span>";
				return;
			}
			if (! iss_field_valid ( 'ParentID', $name, $errors, '' )) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid registration year {$name} </span>";
				return;
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
	}
	public function iss_adminpref_registrationfee1_test24() {
		if (($this->submit === 'test24') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_adminpref_registrationfee1 ();
			if ($name === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Fee1 </span>";
				return;
			}
			if (! iss_field_valid ( 'PaymentInstallment1', $name, $errors, '' )) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid Registration Fee1 {$name} </span>";
				return;
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
	}
	public function iss_adminpref_registrationfee1_installment_test25() {
		if (($this->submit === 'test25') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_adminpref_registrationfee1_installment ();
			if ($name === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Fee1 Installment </span>";
				return;
			}
			if (! iss_field_valid ( 'PaymentInstallment1', $name, $errors, '' )) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid registration year {$name} </span>";
				return;
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
	}
	public function iss_adminpref_registrationfee2_test26() {
		if (($this->submit === 'test26') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_adminpref_registrationfee2 ();
			if ($name === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Fee2</span>";
				return;
			}
			if (! iss_field_valid ( 'PaymentInstallment1', $name, $errors, '' )) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid Registration Fee1 {$name} </span>";
				return;
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
	}
	public function iss_adminpref_registrationfee2_installment_test27() {
		if (($this->submit === 'test27') || ($this->submit === 'all')) {
			$result = true;
			$errors = array ();
			$name = iss_adminpref_registrationfee2_installment ();
			if ($name === NULL) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> NULL Registration Fee2 Installment </span>";
				return;
			}
			if (! iss_field_valid ( 'PaymentInstallment1', $name, $errors, '' )) {
				echo "<i class=\"glyphicon glyphicon-remove\" ></i><span class=\"text-danger\"> Invalid registration year {$name} </span>";
				return;
			}
			
			if ($result) {
				echo "<span class=\"text-success\"> Pass  <i class=\"glyphicon glyphicon-ok\" ></i></span>";
			}
		}
	}
}
$my_test_page = new ISS_UnitTestPlugin ();
?>