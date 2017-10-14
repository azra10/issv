<?php   $regyear = iss_registration_period(); ?>
<?php include(ISS_PATH . "/includes/pheader.php"); ?>
<?php
$keyword = ''; $selection=''; $registration='0'; $initial = 'All'; $bulkaction = '0';
$result_set = NULL;
$columns = "ParentViewID, ParentID, FatherLastName, FatherFirstName, RegistrationComplete, ParentStatus";
if (isset ( $_POST ['submit'] ) || isset($_POST['bulkaction']) || isset($_POST['filter'])) {
	check_admin_referer ( 'iss_parent_search', 'iss_parent_search_nonce' );
	$selection=iss_sanitize_input ( $_POST ['selection'] );
	$bulkaction = iss_sanitize_input ( $_POST ['bulkaction'] );
	$registration = iss_sanitize_input ( $_POST ['registration'] );
	$keyword = iss_sanitize_input ( $_POST ['keyword'] );
	echo 'sele' . $selection;
	if ((strlen ( $selection ) > 0)  && ($bulkaction != '0')) {
			// archive selected parents TODO 
			$initial = 'Archived';
			$result_set = iss_get_archived_parents_list ( $regyear, $columns  );
	} else  if (strlen ( $registration ) > 0) {
		$result_set = iss_get_registration_parents_list ( $regyear, $columns, $registration );
			$keyword = '';
	} else if (strlen ( $keyword ) > 0) {
			$result_set = iss_get_search_parents_list ( $regyear, $columns, $keyword );
	} else {
		$result_set = iss_get_parents_list ( $regyear, $columns );
}
} else  if (isset ( $_GET ['initial'] )) {
	$initial = iss_sanitize_input ( $_GET ['initial'] );
	if ($initial == 'Archived')
	$result_set = iss_get_archived_parents_list ( $regyear, $columns  );
	else if ($initial == 'New')
	$result_set = iss_get_new_parents_list ( $regyear, $columns );
	else
	$result_set = iss_get_startwith_parents_list ( $regyear, $columns, $initial );
} else {
		$result_set = iss_get_parents_list ( $regyear, $columns );
}

?>
<div class="row">
<nav aria-label="Page navigation">
	<ul class="pagination">
		<?php
				echo "<li class=\"page-item ";
				if ($initial == 'All') { echo "  active\""; }
				echo "\"><a class=\"page-link\" href=\"admin.php?page=parents_home\">All</a></li>";
		?> </li>&nbsp;&nbsp;
		<?php													
		$letters = array ( 'A', 'B', 'C','D','E','F','G','H','I','J','K','L','M',
						'N','O','P','Q','R','S','T','U','V','W','X','Y','Z','New','Archived' );
		foreach ( $letters as $letter ) {
			echo "<li class=\"page-item ";
			if (strtolower ($letter) == strtolower ( $initial))
			{	echo " active\"";}
			echo "\"><a class=\"page-link\" href=\"admin.php?page=parents_home&initial={$letter}\">{$letter}</a></li>";
		}
		?>
	</ul>
</nav>
<?php
if (count ( $result_set ) == 0) {
	echo "<br><em>No results were found.</em><br> <a href=\"admin.php?page=parents_home\">Browse parents by last name.</a>";
} else {
?>
<div>
	
	<form action="" method="post" class="navbar-form">
      <?php wp_nonce_field( 'iss_parent_search','iss_parent_search_nonce' ); ?>
			<input type="hidden" id="initial" name="initial" value="<?php echo $$initial;?>"/>
			<input type="hidden" id="selection" name="selection" value"<?php echo $selection;?>" />
				<select name="bulkaction">
				<option selected value="0">Bulk Action</option> <option value="Archive">Archive</option> <!--option>Email</option-->
				</select>
			<button id="actionbutton" name="actionbutton" class="button-primary" disabled=""> </i> Submit </button>
			<select name="registration" id="filter-by-date">
				<option <?php echo ($registration == '0')?  'selected':'';?> value="0">Registration Status</option>
				<option <?php echo ($registration == 'Open')?  'selected':'';?> value="Open">Open</option>
				<option <?php echo ($registration == 'Complete')?  'selected':'';?> value="Complete">Complete</option>
			</select>
			<!--select name="cat" id="cat" class="postform"> <option value="0" selected="selected">All Categories</option></select-->
			<input type="submit" name="filter" id="filter" class="button-primary" value="Filter">	

			<input type="text"  name="keyword" style="margin-left:200px;"
					value="<?php echo $keyword;?>" id="keyword" class="search-query pullright"
					placeholder="First/Last Name"> <input type="submit" name="submit"
					id="submit" value="Search Parent" class="button-primary">
	</form>

	<table class="table table-striped table-responsive table-condensed" 
	id="iss_parent_table">
		<thead>
			<tr>
				<th></th>
				<th>ParentID</th>
				<th>Lastname</th>
				<th>Firstname</th>
				<th>Registration</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
		<?php  foreach($result_set as $row) { ?>
			<tr>
				<td></td>
				<td nowrap>
					<?php echo $row['ParentID'];?>
				</td>
				<td nowrap><i class="dashicons dashicons-id"></i> 
					<?php echo $row['FatherLastName']; ?>
				</td>
				<td nowrap>
					<?php echo $row['FatherFirstName'];?>
				</td>
				<td nowrap>
					<?php echo $row['RegistrationComplete'];?>
				</td>
				<td nowrap>
					<?php if (iss_current_user_can_editparent()) 
					{ 
						if ($row['ParentStatus'] == 'active') 
						{ 
						?>
							<a
								href="admin.php?page=edit_parent&pid=<?php echo $row['ParentID']; ?>&regyear=<?php if (isset($regyear)) echo $regyear;?>">
									<span style="padding-left: 10px; white-space: nowrap;"> <i
										class="glyphicon glyphicon-edit"></i> Edit
								</span>
							</a> <a
								href="admin.php?page=payment_parent&id=<?php echo $row[ 'ParentViewID'];?>">
									<span style="padding-left: 10px; white-space: nowrap;"> <span
										class="text-primary">$</span> Payment
								</span>
							</a> 
							<!--<a
								href="admin.php?page=email_home&id=<?php echo $row['ParentViewID'];?>"> <span
									style="padding-left: 10px; white-space: nowrap;"> <i
										class="glyphicon glyphicon-envelope"></i> Email
								</span></a> -->
								
							<a
								href="admin.php?page=archived_home&aid=<?php echo $row['ParentViewID'];?>">
									<span style="padding-left: 10px; white-space: nowrap;"> <i
										class="glyphicon glyphicon-eye-close"></i> Archive 	</span>
							</a>
						<?php 
						} else 
						{ 	
						?>
							<a href="admin.php?page=archived_home&uid=<?php echo $row['ParentViewID'];?>">
								<span style="padding-left: 10px; white-space: nowrap;"><i 
								class="glyphicon glyphicon-eye-open"></i> UnArchive  </span>
							</a>                   
							<a href="admin.php?page=delete_parent&id=<?php echo $row['ParentViewID'];?>"> 
								<span style="padding-left: 10px; white-space: nowrap;"><i 
								class="glyphicon glyphicon-remove"></i> Delete</span>
							</a>
						<?php 
						} 
					} 
					?>
					<a
					href="admin.php?page=history_parent&id=<?php echo $row['ParentViewID'];?>">
						<span style="padding-left: 10px; white-space: nowrap;"> <i
							class="glyphicon glyphicon-header"></i> History
					</span> </a>

					<a
					href="admin.php?page=view_parent&id=<?php echo $row['ParentViewID'];?>"> <span
						style="padding-left: 10px; white-space: nowrap;"> <i
							class="glyphicon glyphicon-eye-open"></i> View
					</span></a> 
					
					<a
					href="admin.php?page=print_parent&id=<?php echo $row['ParentViewID'];?>"> <span
						style="padding-left: 10px; white-space: nowrap;"> <i
							class="glyphicon glyphicon-print"></i> Print
					</span></a>

				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	</div>
</div>
<?php }?>

<script>
	jQuery(window).load(function() {
		jQuery('#pleaseWaitDialog').hide();
		$('#iss_parent_table').bootstrapTable({
			pagination: true,
			pageSize: 15,
			columns: [{
				field: 'state',
				checkbox: true,
				align: 'center',
				valign: 'middle'
			}, {
				field: 'id',
				align: 'center',
				valign: 'middle'
			}, {
				field: 'lastname',
				align: 'left',
				valign: 'middle',
				sortable: true
			}, {
				field: 'firstname',
				align: 'left',
				valign: 'middle',
				sortable: true
			}, {
				field: 'action',
				align: 'left'
			}]
		});
		//$('#iss_parent_table').bootstrapTable('hideColumn', 'id');

	$('#iss_parent_table').on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table',
			function() {
				$('#actionbutton').prop('disabled', !$('#iss_parent_table').bootstrapTable('getSelections').length);

				// save your data, here just save the current page
				selections = getIdSelections();
				//console.log(selections);
				$('#selection').val(getIdSelections());
				// push or splice the selections if you want to save all data selections
			});
		$('#actionbutton').click(function() {
			var ids = getIdSelections();

			$table.bootstrapTable('actionbutton', {
			    field: 'id',
			    values: ids
			});
			$actionbutton.prop('disabled', true);
		});

	});

	function getIdSelections() {
		return $.map($('#iss_parent_table').bootstrapTable('getSelections'), function(row) {
			return row.id
		});
	}
</script>
