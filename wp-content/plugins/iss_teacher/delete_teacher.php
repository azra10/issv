<?php iss_show_heading("Delete Teacher"); ?> 
<?php include (plugin_dir_path( __FILE__ ) . "/delete_teacher_post.php"); ?>

<div class="container">
<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
    <?php wp_nonce_field('iss-delete-teacher-form-page', '_wpnonce-iss-delete-teacher-form-page') ?>
    <input type="hidden" id="TeacherID" name="TeacherID" value="<?php echo $tid; ?>" />    
	<div class="row">
 		<h5>Name: <?php echo $teacher->Name; ?></h5>
		<h5>Email: <?php echo $teacher->Email; ?></h5>
        <h5>Status: <?php echo $teacher->Status == 'inactive'? 'No' : 'Yes'; ?></h5>
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
