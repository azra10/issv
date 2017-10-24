
<div class="container">
  <form class="form" method="post" action="" enctype="multipart/form-data">

    <?php wp_nonce_field( 'iss-edit-class-form-page', '_wpnonce-iss-edit-class-form-page' ); ?>
    <input type="hidden" id="ClassID" name="FormArray[ClassID]" value="<?php echo $class->ClassID; ?>" />    
    
    <!-- Name-->
    <div class="form-group">
      <label class="control-label" for="Name">Name</label>
      
        <div class="input-group col-md-6">
        <input id="Name" name="FormArray[Name]" class="form-control " required="" 
        placeholder="Class Name (required)" type="text" maxlength="100" value="<?php echo $class->Name; ?>" >
          <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
        </div>  
        <p class="text-danger"><?php iss_show_error($errors,'Name'); ?></p>  
    </div>

    <!-- ISSGrade-->
    <div class="form-group">
      <label class="control-label" for="ISSGrade">ISS Grade</label>  
        <div class="input-group col-md-6">
          <select id="ISSGrade" name="FormArray[ISSGrade]" class="form-control">
            <?php
                foreach ($classlist as $key => $value) {
                    echo "<option value=\"{$key}\">{$value}</option>";
                } 
            ?>
          </select>
        </div>
    </div>

    <!-- Subject-->
    <div class="form-group">
      <label class="control-label" for="Subject">Subject</label>  
      <div> 
        <label class="radio-inline" for="Subject1">
          <input class="form-check-input" type="radio" name="FormArray[Subject]" id="Subject1" value="IS" 
            <?php echo ($class->Subject != 'QS')?  'checked': ''; ?> > Islamic Studies
        </label>
        <label class="radio-inline" for="Subject2">
          <input class="form-check-input" type="radio" name="FormArray[Subject]" id="Subject2" value="QS" 
            <?php echo ($class->Subject == 'QS')?  'checked': ''; ?> > Quranic Studies
        </label>
      </div>  
    </div>

    <!-- Registration Year -->
    <div class="form-group">
      <label class="control-label" for="RegistrationYear">Registration Year</label>
      <div class="input-group col-md-6">
        <select id="RegistrationYear" name="FormArray[RegistrationYear]" class="form-control">
        <?php echo "<option value=\"{$regyear}\">{$regyear}</option>"; ?>
        </select>
      </div>
    </div>

   <!-- Grading Period-->
   <div class="form-group">
      <label class="control-label" for="GradingPeriod1">Grading Period</label>    
      <div> 
        <label class="radio-inline" for="GradingPeriod1">
          <input class="form-check-input" type="radio" name="FormArray[GradingPeriod]" id="GradingPeriod1" value="1" 
            <?php echo ($class->GradingPeriod != 2)?  'checked': ''; ?> > 1
        </label>
        <label class="radio-inline" for="GradingPeriod2">
          <input class="form-check-input" type="radio" name="FormArray[GradingPeriod]" id="GradingPeriod2" value="2" 
            <?php echo ($class->GradingPeriod == 2)?  'checked': ''; ?> > 2
        </label>
      </div>
    </div>

    <!-- Status-->
    <div class="form-group">
      <label class="control-label" for="radios">Status</label>
      <div> 
        <label class="radio-inline" for="Status1">
        <input class="form-check-input" type="radio" name="FormArray[Status]" id="Status1" value="active" 
        <?php echo ($class->Status != 'inactive')?  'checked': ''; ?> > Active       
        </label> 
        <label class="radio-inline" for="Status2">
        <input class="form-check-input" type="radio" name="FormArray[Status]" id="Status2" value="inactive" <?php echo ($class->Status == 'inactive')?  'checked': ''; ?> > Inactive
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