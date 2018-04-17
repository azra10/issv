<?php
iss_show_heading("Account");
include (plugin_dir_path( __FILE__ ) . "/new_user_post.php");
?>

<div class="container">
<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
    <?php wp_nonce_field('iss-user-account-form-page', '_wpnonce-iss-user-account-form-page') ?>
    <div class="row">
        <input type="hidden" id="Email" name="Email" value="<?php echo $email; ?>" />          
        <input type="hidden" id="Type" name="Type" value="<?php echo $type; ?>" />          
       
       <?php 
       if (!empty($email)) {
        if (null == $userid) { 
            echo "<input type=\"hidden\" id=\"WPUserID\" name=\"WPUserID\" value=\"\" />"  ;
            echo "<input type=\"hidden\" id=\"Type\" name=\"Type\" value=\"{$type}\" />" ;                               
            echo "<br/><h4>{$type} Account</h4>";
            echo "<strong>Email:</strong> {$email} <br/>";      
            echo "<button type=\"submit\" name=\"submit\" value=\"user\" class=\"btn btn-primary \">Create Account</button>";			
         
        } else { 
            echo "<input type=\"hidden\" id=\"WPUserID\" name=\"WPUserID\" value=\"{$userid}\" /> ";   
            echo "<br/><h4>{$type} Account</h4>";
            echo "<strong>Email:</strong> {$email} ({$userid})<br/> ";      
            echo "<button type=\"submit\" name=\"submit\" value=\"user\" class=\"btn btn-primary \">Delete Account</button>";			
         } 
        }  
        
        ?>
        <?php 
       if (!empty($email1)) {
        if (null == $userid1) { 
            echo "<input type=\"hidden\" id=\"WPUserID\" name=\"WPUserID\" value=\"\" />"  ;
            echo "<input type=\"hidden\" id=\"Type\" name=\"Type\" value=\"{$type1}\" />" ;                               
            echo "<br/><h4>{$type1} Account</h4>";
            echo "<strong>Email:</strong> {$email1} <br/>";      
            echo "<button type=\"submit\" name=\"submit\" value=\"user\" class=\"btn btn-primary \">Create Account</button>";			                        
        } else { 
            echo "<input type=\"hidden\" id=\"WPUserID1\" name=\"WPUserID1\" value=\"{$userid1}\" /> ";   
            echo "<br/><h4>{$type1} Account</h4>";
            echo "<strong>Email:</strong> {$email1} ({$userid1}) <br/>";      
            echo "<button type=\"submit\" name=\"submit\" value=\"user\" class=\"btn btn-primary \">Delete Account</button>";			               
          } 
        }
        ?>
    </div> 
</form>
</div>

