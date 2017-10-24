<?php
var_dump($_POST);

$errors = array();
$newrecrod = true;

if (isset ( $_POST ['_wpnonce-iss-edit-gradingperiod-form-page'] )) {
    check_admin_referer ( 'iss-edit-gradingperiod-form-page', '_wpnonce-iss-edit-gradingperiod-form-page' );  
    if (ISS_GradingPeriodService::isValid($_POST['FormArray'], $errors)) {
        $result = ISS_GradingPeriodService::Add($_POST['FormArray']);
        if ($result>0) {
            echo '<div class="text-primary"><p><strong>Grading Period Added.</strong></p></div>';
            exit;
        }        
    } else {
        // else populate value and show errors
        $gradingperiod = ISS_GradingPeriod::Create($_POST['FormArray']);
    }
} else  {
    $gradingperiod = new ISS_GradingPeriod();
}
?>
