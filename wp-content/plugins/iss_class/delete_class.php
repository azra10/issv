
 
<?php include (plugin_dir_path( __FILE__ ) . "/delete_class_post.php"); ?>

<div class="container">
<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
    <?php wp_nonce_field('iss-delete-class-form-page', '_wpnonce-iss-delete-class-form-page') ?>
    <input type="hidden" id="ClassID" name="ClassID" value="<?php echo $tid; ?>" />    
	<div class="row">
 		<h3>Delete Class Record</h3>
		<h5>Name: <?php echo $class->Name; ?></h5>
		<h5>ISSGrade: <?php echo $class->ISSGrade; ?></h5>
        <h5>Subject: <?php echo $class->Subject; ?></h5>
        <h5>Status: <?php echo $class->Status == 'inactive'? 'No' : 'Yes'; ?></h5>
        <input type="checkbox" id="agreeyes" name="agreeyes"> 
			<strong>Are you sure to delete this class record?</strong>
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
