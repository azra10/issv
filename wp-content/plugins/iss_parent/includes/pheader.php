<?php 
 if (iss_current_user_can_editparent() && (strlen($regyear)>0)) { 
    iss_show_heading("Parents ({$regyear})", "admin.php?page=new_parent");
 } else {
    iss_show_heading("Parents ({$regyear})");
 }
?>




