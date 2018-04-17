<div class="container">
  <form class="form" method="post" action="" enctype="multipart/form-data">

    <?php wp_nonce_field( 'iss-edit-teacher-form-page', '_wpnonce-iss-edit-teacher-form-page' ); ?>
    <input type="hidden" id="TeacherID" name="FormArray[TeacherID]" value="<?php echo $teacher->TeacherID; ?>" />    
    
    <!-- Name-->
    <div class="form-group">
      <label class="control-label" for="Name">Name</label>
      
        <div class="input-group col-md-6">
        <input id="Name" name="FormArray[Name]" class="form-control " required="" 
        placeholder="Teacher Name (required)" type="text" maxlength="100" value="<?php echo $teacher->Name; ?>" >
          <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
        </div>  
        <p class="text-danger"><?php iss_show_error( $errors, 'Name'); ?></p>   
    </div>
    <?php if (!$newteacher) { ?>
      <input type="hidden" id="Email" name="FormArray[Email]" value="<?php echo $teacher->Email; ?>" />    
      <div class="form-group">
        <label class="control-label">Email</label>
        <div><?php echo $teacher->Email; ?></div>
      </div>      
    <?php } else { ?> 
    <!-- Email-->
    <div class="form-group">
      <label class="control-label" for="Email">Email</label>  
      
      <div class="input-group col-md-6">
        <input id="Email" name="FormArray[Email]" class="form-control " required="" 
        placeholder="Email (required)" type="text" maxlength="100" value="<?php echo $teacher->Email; ?>" >
        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
      </div>
      <p class="text-danger"><?php iss_show_error( $errors, 'Email'); ?></p>     
    </div>
    <?php } ?> 
    <!-- Status-->
    <div class="form-group">
      <label class="control-label" for="radios">Status</label>
      <div> 
        <label class="radio-inline" for="Status1">
        <input class="form-check-input" type="radio" name="FormArray[Status]" id="Status1" value="active" <?php echo ($teacher->Status != 'inactive')?  'checked': ''; ?> > Active
        </label> 
        <label class="radio-inline" for="Status2">
        <input class="form-check-input" type="radio" name="FormArray[Status]" id="Status2" value="inactive" <?php echo ($teacher->Status == 'inactive')?  'checked': ''; ?> > Inactive
        </label> 
      </div>
    </div>
 

    <!-- Button -->
    <div class="form-group">
      <label class="control-label" for="submit"></label>
      <div class="col-md-6">
        <button id="submit" name="submit" class="btn btn-primary">Save</button>
      </div>
    </div>

  </form>
</div>