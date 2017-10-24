<?php
//var_dump($_POST);
if (isset ( $_POST ['_wpnonce-iss-delete-class-form-page'] )) {
    
    check_admin_referer ( 'iss-delete-class-form-page', '_wpnonce-iss-delete-class-form-page' );

    if (! isset ( $_POST ['ClassID'] ) || empty ( $_POST ['ClassID'] ) || (intval ( $_POST ['ClassID'] ) == 0)) {
        echo '<div class="text-danger"><p><strong>Unknown record.</strong></p></div>';
        exit;
    }
        $classid = iss_sanitize_input ( $_POST ['ClassID'] );

        $result = ISS_ClassService::DeleteByID($classid);
        
    if ($result>0) {
        echo '<div class="text-primary"><p><strong>Grading Period Deleted.</strong></p></div>';
        exit;
    } else {
         echo '<div class="text-primary"><p><strong>Unable to delete Grading Period.</strong></p></div>';
        exit;      
    }
} elseif (! isset ( $_GET ['cid'] ) || empty ( $_GET ['cid'] ) || (intval ( $_GET ['cid'] ) == 0)) {
    echo '<div class="text-danger"><p><strong>Unknown record.</strong></p></div>';
    exit;
}

$cid = iss_sanitize_input ( $_GET ['cid'] );
$class = ISS_ClassService::LoadByID ( $cid );

if ($class == null) 
{
    echo '<div class="text-danger"><p><strong>Unknown record.</strong></p></div>';
    exit;
} 
?>