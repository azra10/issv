<div class="container">
  <form class="form" method="post" action="" enctype="multipart/form-data">

    <?php wp_nonce_field( 'iss-edit-gradingperiod-form-page', '_wpnonce-iss-edit-gradingperiod-form-page' ); ?>
    <input type="hidden" id="GradingPeriodID" name="FormArray[GradingPeriodID]" value="<?php echo $gradingperiod->GradingPeriodID; ?>" />    
    <?php if (!$newgp) { ?>
      <input type="hidden" id="RegistrationYear" name="FormArray[RegistrationYear]" value="<?php echo $gradingperiod->RegistrationYear; ?>" />    
      <input type="hidden" id="GradingPeriod" name="FormArray[GradingPeriod]" value="<?php echo $gradingperiod->GradingPeriod; ?>" />    
      <div class="form-group">
        <label class="control-label">Registration Year</label>
        <div><?php echo $gradingperiod->RegistrationYear; ?></div>
      <div class="form-group">
        <label class="control-label">Grading Period</label>    
        <div><?php echo $gradingperiod->GradingPeriod; ?></div>    
      </div>
    <?php } else { ?> 
    <!-- Registration Year-->
    <div class="form-group">
      <label class="control-label" for="RegistrationYear">Registration Year</label>
      
        <div class="input-group col-md-6">
        <input  id="RegistrationYear" name="FormArray[RegistrationYear]" class="form-control " required="" 
        placeholder="YYYY-YYYY (required)" type="text" maxlength="9" value="<?php echo $gradingperiod->RegistrationYear; ?>" >
          <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
        </div>  
        <p class="text-danger"><?php iss_show_error($errors,'RegistrationYear'); ?></p>  
    </div>

    <!-- Grading Period-->
    <div class="form-group">
        <label class="control-label" for="GradingPeriod1">Grading Period</label>    
        <div> 
          <label class="radio-inline" for="GradingPeriod1">
            <input class="form-check-input" type="radio" name="FormArray[GradingPeriod]" id="GradingPeriod1" value="1" <?php echo ($gradingperiod->GradingPeriod != 2)?  'checked': ''; ?> > 1
          </label>
          <label class="radio-inline" for="GradingPeriod2">
            <input class="form-check-input" type="radio" name="FormArray[GradingPeriod]" id="GradingPeriod2" value="2" <?php echo ($gradingperiod->GradingPeriod == 2)?  'checked': ''; ?> > 2
          </label>
        </div>
    </div>
    <?php } ?> 
    <!-- Start Date-->
    <div class="form-group">
      <label class="control-label" for="StartDate">Start Date</label>  
      
      <div class="input-group col-md-6">
        <input id="StartDate" name="FormArray[StartDate]" class="form-control " required="" 
        placeholder="YYYY-MM-DD (required)" type="date" maxlength="10" value="<?php echo $gradingperiod->StartDate; ?>" >
        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
      </div>
      <p class="text-danger"><?php iss_show_error($errors,'StartDate'); ?></p>      
    </div>

    <!-- Text input-->
    <div class="form-group">
      <label class="control-label" for="EndDate">End Date</label>  
     
      <div class="input-group col-md-6">
        <input id="EndDate" name="FormArray[EndDate]"  class="form-control " required="" 
        placeholder="YYYY-MM-DD (required)" type="date" maxlength="10" value="<?php echo $gradingperiod->EndDate; ?>" >
        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
      </div>
      <p class="text-danger"><?php iss_show_error($errors,'EndDate'); ?></p>       
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