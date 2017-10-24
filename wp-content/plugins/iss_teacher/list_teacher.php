<?php 
    iss_show_heading("Teachers", iss_current_user_can_admin()? "admin.php?page=new_teacher": null);        
    $result_set = ISS_TeacherService::GetTeachers ( );
?>
<div>
    <div class="row">
        <table class="table table-striped table-responsive table-condensed" id="iss_teacher_table">
            <thead>
                <tr>               
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result_set as $row) { ?>
                <tr>
                <td><?php echo $row->Name; ?></td>
                <td> <?php echo $row->Email ;?> </td>
                <td> <?php echo $row->Status =='inactive'? 'No' : 'Yes';?> </td>
                <td>
                <a href="admin.php?page=edit_teacher&tid=<?php echo $row->TeacherID ; ?>">
                    <span style="padding-left: 10px; white-space: nowrap;"> <i class="glyphicon glyphicon-edit"></i> Edit </span>
                </a>
                <a href="admin.php?page=delete_teacher&tid=<?php echo $row->TeacherID ; ?>">
                    <span style="padding-left: 10px; white-space: nowrap;"> <i class="glyphicon glyphicon-remove"></i> Delete </span>
                </a>
                </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
