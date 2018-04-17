<?php
//var_dump($_POST);

$errors = array();
$newteacher = true;

if (isset ( $_POST ['_wpnonce-iss-edit-teacher-form-page'] )) {
    check_admin_referer ( 'iss-edit-teacher-form-page', '_wpnonce-iss-edit-teacher-form-page' );
    if (ISS_TeacherService::isValid($_POST['FormArray'], $errors)) {      
        $result = ISS_TeacherService::Add($_POST['FormArray']);
        if ($result>0) {
            echo '<div class="text-primary"><p><strong>Teacher Record Added.</strong></p></div>';
            exit;
        }       
    } else {
        // else populate value and show errors
        $teacher = ISS_Teacher::Create($_POST['FormArray']);
    }
} else {
    $teacher = new ISS_Teacher();
}
?>