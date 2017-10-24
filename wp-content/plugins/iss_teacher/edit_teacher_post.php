<?php
//var_dump($_POST);

if (! isset ( $_GET ['tid'] ) || empty ( $_GET ['tid'] ) || (intval ( $_GET ['tid'] ) == 0)) {
    echo '<div class="text-primary"><p><strong>Record not found.</strong></p></div>';
    exit;
}

$errors = array();
$newteacher = false;

if (isset ( $_POST ['_wpnonce-iss-edit-teacher-form-page'] )) {
    check_admin_referer ( 'iss-edit-teacher-form-page', '_wpnonce-iss-edit-teacher-form-page' );   
    if (ISS_TeacherService::isValid($_POST['FormArray'], $errors)) {
        $result = ISS_TeacherService::Update($_POST['FormArray']);
        if ($result>0) {
            echo '<div class="text-primary"><p><strong>Teacher Record Updated.</strong></p></div>';
            exit;
        }        
    } else {
        // else populate value and show errors
        $teacher = ISS_Teacher::Create($_POST['FormArray']);
    }
} else {
    $teacher = ISS_TeacherService::LoadByID ( $_GET ['tid'] );    
}
?>