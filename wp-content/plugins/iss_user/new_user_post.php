<?php

$user = null; 
$type=null;  $email = null;  $userid = null;
$type1=null; $email1 = null; $userid1 = null;  

if (!ISS_Validate::invalid_int_in_url($_GET,'tid')) {
    $user = ISS_TeacherService::LoadByID ( $_GET ['tid'] );   
    if (null == $user){
        echo '<div class="text-primary"><p><strong>Record not found.</strong></p></div>';
        exit;
    }
    $type = "Teacher";
    $email = $user->Email;

} elseif (!ISS_Validate::invalid_int_in_url($_GET,'pid')) {
    $user = ISS_ParentService::LoadByID ( $_GET ['pid'] );   
     if (null == $user){
        echo '<div class="text-primary"><p><strong>Record not found.</strong></p></div>';
        exit;
    }
    $type = "Father"; 
    $email = $user->FatherEmail;
    $type1 = "Mother";
    $email1 = $user->MotherEmail;

} elseif (!ISS_Validate::invalid_int_in_url($_GET,'sid')) {
    $user = ISS_StudentService::LoadByID ( $_GET ['sid'] );   
    if (null == $user){
        echo '<div class="text-primary"><p><strong>Record not found.</strong></p></div>';
        exit;
    }
    $email = $user->StudentEmail;
    $type = "Student";    
} 
if ((null == $type) ||  (null == $user) || ((null == $email) && (null == $email1))) {  
    echo '<div class="text-danger"><p><strong>Record not found.</strong></p></div>';
    exit;
}
if (!empty($email)) {$userid = ISS_UserService::GetWPUserId($email);}
if (!empty($email1)) {$userid1 = ISS_UserService::GetWPUserId($email1);}

var_dump($_POST);

if (isset ( $_POST ['_wpnonce-iss-user-account-form-page'] )) {
    check_admin_referer ( 'iss-user-account-form-page', '_wpnonce-iss-user-account-form-page' );   
    
    if (isset($_POST['WPUserID']) && empty($_POST['WPUserID'])) {
        $result = ISS_UserService::CreateUser($_POST['Email'], $_POST['Type']);
        if(! is_wp_error( $result ) ) {
            echo "<div class=\"text-primary\"><p><strong>Account Created  {$_POST['Email']}</strong></p></div>";
            exit;
        }  else {
            echo "<div class=\"text-danger\"><p><strong>Error Creating Account {$_POST['Email']}</strong></p></div>";
            echo $result->get_error_message();
            exit;
        }
    } else {
        $result = ISS_UserService::DeleteUser($_POST['WPUserID']);
        if( $result > 0 ) {
            echo "<div class=\"text-primary\"><p><strong>Account Deleted  {$_POST['Email']}</strong></p></div>";
            exit;
        }  else {
            echo "<div class=\"text-danger\"><p><strong>Error Deleting Account {$_POST['Email']}</strong></p></div>";
            exit;
        }
    }
}

?>