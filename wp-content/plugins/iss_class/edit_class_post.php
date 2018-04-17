<?php
//var_dump($_POST);
if (ISS_Validate::invalid_int_in_url($_GET,'cid')) {
    echo '<div class="text-primary"><p><strong>Record not found.</strong></p></div>';
    exit;
}

$errors = array();

if (isset ( $_POST ['_wpnonce-iss-edit-class-form-page'] )) {
    check_admin_referer ( 'iss-edit-class-form-page', '_wpnonce-iss-edit-class-form-page' );
   
    // if valid add/update
    if (ISS_ClassService::isValid($_POST['FormArray'], $errors)) {
        $result = ISS_ClassService::Update($_POST['FormArray']);
        if ($result>0) {
            echo '<div class="text-primary"><p><strong>Class Record Updated.</strong></p></div>';
            exit;
        }
    } else {
        // else populate value and show errors
        $class = ISS_Class::Create($_POST['FormArray']);
    }
} else {
    $class = ISS_ClassService::LoadByID ( $_GET ['cid'] );
}

if (null == $class){
    echo '<div class="text-primary"><p><strong>Record not found.</strong></p></div>';
    exit;
}
$regyear = iss_registration_period();
$classlist = ISS_Class::GetClassList();
?>