<?php
if (isset ( $_POST ['_wpnonce-iss-delete-gradingperiod-form-page'] )) {
    
    check_admin_referer ( 'iss-delete-gradingperiod-form-page', '_wpnonce-iss-delete-gradingperiod-form-page' );

    if (! isset ( $_POST ['GradingPeriodID'] ) || empty ( $_POST ['GradingPeriodID'] ) || (intval ( $_POST ['GradingPeriodID'] ) == 0)) {
        echo '<div class="text-danger"><p><strong>Unknown record.</strong></p></div>';
        exit;
    }
        $gradingperiodid = iss_sanitize_input ( $_POST ['GradingPeriodID'] );

        $result = ISS_GradingPeriodService::DeleteByID($gradingperiodid);
        
    if ($result>0) {
        echo '<div class="text-primary"><p><strong>Grading Period Deleted.</strong></p></div>';
        exit;
    } else {
         echo '<div class="text-primary"><p><strong>Unable to delete Grading Period.</strong></p></div>';
        exit;      
    }
} elseif (! isset ( $_GET ['gid'] ) || empty ( $_GET ['gid'] ) || (intval ( $_GET ['gid'] ) == 0)) {
    echo '<div class="text-danger"><p><strong>Unknown record.</strong></p></div>';
    exit;
}

$gid = iss_sanitize_input ( $_GET ['gid'] );
$gradingperiod = ISS_GradingPeriodService::LoadByID ( $gid );

if ($gradingperiod == null) 
{
    echo '<div class="text-danger"><p><strong>Unknown record.</strong></p></div>';
    exit;
} 
?>