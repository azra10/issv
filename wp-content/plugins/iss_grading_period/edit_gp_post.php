<?php
//var_dump($_POST);

if  (! isset ( $_GET ['gid'] ) || empty ( $_GET ['gid'] ) || (intval ( $_GET ['gid'] ) == 0)) {   
    echo '<div class="text-primary"><p><strong>Record not found.</strong></p></div>';
    exit;
}
$errors = array();
$newgp = false;

if (isset ( $_POST ['_wpnonce-iss-edit-gradingperiod-form-page'] )) {
    check_admin_referer ( 'iss-edit-gradingperiod-form-page', '_wpnonce-iss-edit-gradingperiod-form-page' );
   
    // if valid add/update
    if (ISS_GradingPeriodService::isValid($_POST['FormArray'], $errors)) {
        $result = ISS_GradingPeriodService::Update($_POST['FormArray']);
        if ($result>0) {
            echo '<div class="text-primary"><p><strong>Grading Period Updated.</strong></p></div>';
            exit;
        }
    } else {
        // else populate value and show errors
        $gradingperiod = ISS_GradingPeriod::Create($_POST['FormArray']);
    }
} else {
    $gradingperiod = ISS_GradingPeriodService::LoadByID ( $_GET ['gid'] );   
}
?>
