<?php
if (isset ( $_POST ['_wpnonce-iss-delete-teacher-form-page'] )) {
    
    check_admin_referer ( 'iss-delete-teacher-form-page', '_wpnonce-iss-delete-teacher-form-page' );

    if (! isset ( $_POST ['TeacherID'] ) || empty ( $_POST ['TeacherID'] ) || (intval ( $_POST ['TeacherID'] ) == 0)) {
        echo '<div class="text-danger"><p><strong>Unknown record.</strong></p></div>';
        exit;
    }
        $teacherid = iss_sanitize_input ( $_POST ['TeacherID'] );

        $result = ISS_TeacherService::DeleteByID($teacherid);
        
    if ($result>0) {
        echo '<div class="text-primary"><p><strong>Teacher record deleted.</strong></p></div>';
        exit;
    } else {
         echo '<div class="text-primary"><p><strong>Unable to delete Grading Period.</strong></p></div>';
        exit;      
    }
} elseif (! isset ( $_GET ['tid'] ) || empty ( $_GET ['tid'] ) || (intval ( $_GET ['tid'] ) == 0)) {
    echo '<div class="text-danger"><p><strong>Unknown record.</strong></p></div>';
    exit;
}

$tid = iss_sanitize_input ( $_GET ['tid'] );
$teacher = ISS_TeacherService::LoadByID ( $tid );

if ($teacher == null) 
{
    echo '<div class="text-danger"><p><strong>Unknown record.</strong></p></div>';
    exit;
} 
?>