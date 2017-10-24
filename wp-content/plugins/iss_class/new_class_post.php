<?php
//var_dump($_POST);

$errors = array();

if (isset ( $_POST ['_wpnonce-iss-edit-class-form-page'] )) {
    check_admin_referer ( 'iss-edit-class-form-page', '_wpnonce-iss-edit-class-form-page' );
    if (ISS_ClassService::isValid($_POST['FormArray'], $errors)) {
        $result = ISS_ClassService::Add($_POST['FormArray']);
        if ($result>0) {
            echo '<div class="text-primary"><p><strong>Class Record Added.</strong></p></div>';
            exit;
        }
    } else {
        // else populate value and show errors
        $class = ISS_Class::Create($_POST['FormArray']);
    }
} else {
    $class = new ISS_Class();
}
$regyear = iss_registration_period();
$classlist = ISS_Class::GetClassList();
?>