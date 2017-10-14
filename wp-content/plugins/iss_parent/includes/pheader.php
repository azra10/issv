<div class="navbar-form bg-faded">
	<span style="font-size: 18px;margin-right:100px;">Parents (<?php echo $regyear; ?>)</span>		
  <?php if (iss_current_user_can_editparent() && (strlen($regyear)>0)) { ?>
      <a  href="admin.php?page=new_parent"><i class="icon-plus-sign"></i> <span class="button-primary">Add Parent</span></a>
  <?php } ?>
 </div>


