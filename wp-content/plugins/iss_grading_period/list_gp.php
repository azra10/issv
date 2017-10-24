<?php   
    iss_show_heading("Grading Periods", iss_current_user_can_admin()? "admin.php?page=new_gp": null);        
    $result_set = ISS_GradingPeriodService::GetGradingPeriods ( );
?>
<div>
    <div class="row">
        <table class="table table-striped table-responsive table-condensed" id="iss_gp_table">
            <thead>
                <tr>
                    
                    <th>Registration Year</th>
                    <th>Grading Period</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result_set as $row) { ?>
                <tr>
                <td><?php echo $row->RegistrationYear; ?></td>
                <td> <?php echo $row->GradingPeriod ;?> </td>
                <td> <?php echo $row->StartDate ;?> </td>
                <td> <?php echo $row->EndDate ;?> </td>
                <td>
                <a href="admin.php?page=edit_gp&gid=<?php echo $row->GradingPeriodID ; ?>">
                    <span style="padding-left: 10px; white-space: nowrap;"> <i class="glyphicon glyphicon-edit"></i> Edit </span>
                </a>
                <a href="admin.php?page=delete_gp&gid=<?php echo $row->GradingPeriodID ; ?>">
                    <span style="padding-left: 10px; white-space: nowrap;"> <i class="glyphicon glyphicon-remove"></i> Delete </span>
                </a>
                </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
