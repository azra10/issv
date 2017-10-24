<?php include (plugin_dir_path( __FILE__ ) . "/delete_gp_post.php"); ?>

<div class="container">
<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
    <?php wp_nonce_field('iss-delete-gradingperiod-form-page', '_wpnonce-iss-delete-gradingperiod-form-page') ?>
    <input type="hidden" id="GradingPeriodID" name="GradingPeriodID" value="<?php echo $gid; ?>" />    
	<div class="row">
 		<h3>Delete Grading Period</h3>
		<h5>Registration Year: <?php echo $gradingperiod->RegistrationYear; ?></h5>
		<h5>Grading Period: <?php echo $gradingperiod->GradingPeriod; ?></h5>
        <h5>Start Date: <?php echo $gradingperiod->StartDate; ?></h5>
        <h5>End Date: <?php echo $gradingperiod->EndDate; ?></h5>
        <input type="checkbox" id="agreeyes" name="agreeyes"> 
			<strong>Are you sure to delete this grading period?</strong>
         <br/> <br/>
    </div>
    <div class="agree">
           <button type="submit" name="submit" value="delete" class="btn btn-primary btn-lg deletebutton">Delete</button>			
    </div>
</form>
</div>

<script>
  $(document).ready(function() {
		$('button.deletebutton').prop('disabled', true);
            $('#agreeyes').click(function() {
            if (!this.checked)
                $('button.deletebutton').prop('disabled', true);
            else
                $('button.deletebutton').prop('disabled', false);

        });
  });
</script>
