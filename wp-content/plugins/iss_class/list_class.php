<?php 
    $result_set = ISS_ClassService::GetClasses ();  
    $regyear = iss_registration_period(); 
    if (iss_current_user_can_admin()) { 
        iss_show_heading("Classses ({$regyear})", "admin.php?page=new_class");        
    }
    else{
        iss_show_heading("Classses ({$regyear})");
    }
?>
<div>
    <div class="row">
        <table class="table table-striped table-responsive table-condensed" id="iss_class_table">
            <thead>
                <tr>      
                    <th>Grading Period</th>         
                    <th>Name</th>
                    <th>ISSGrade</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result_set as $row) { ?>
                <tr>
                <td><?php echo $row->GradingPeriod; ?></td>
                <td><?php echo $row->Name; ?></td>
                <td> <?php echo $row->ISSGrade ;?> </td>
                <td> <?php echo $row->Subject ;?> </td>
                <td> <?php echo $row->Status =='inactive'? 'No' : 'Yes';?> </td>
                <td>
                <?php if (iss_current_user_can_admin() || iss_current_user_can_teach()) {?>
                    <a href="admin.php?page=edit_class&cid=<?php echo $row->ClassID ; ?>">
                        <span style="padding-left: 10px; white-space: nowrap;"> <i class="glyphicon glyphicon-edit"></i> Edit </span>
                    </a>
                    <a href="admin.php?page=delete_class&cid=<?php echo $row->ClassID ; ?>">
                        <span style="padding-left: 10px; white-space: nowrap;"> <i class="glyphicon glyphicon-remove"></i> Delete </span>
                    </a>
                    
                <?php } ?>
                </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
