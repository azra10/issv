<?php
/*
 * To help narrow class selection, we'll pass in variables to determine the scope of the classes
 * 't' for time range will accept 3 possible values
 * 1. current
 * 2. upcoming
 * 3. archive
 *
 */
if (! isset ( $_GET ['t'] ) || intval ( $_GET ['t'] ) == 0) {
	// If no time range or class group is selected, go to default -- Upcoming, All Classes
	$time_range = 2;
	$page_name = $_GET ['page'];
} else {
	$time_range = $_GET ['t'];
	$group = $_GET ['g'];
	$page_name = $_GET ['page'];
}

if (! isset ( $_GET ['cust_id'] ) || intval ( $_GET ['cust_id'] ) == 0) {
	$cust_id = 0;
	$registering = false;
} else {
	$cust_id = $_GET ['cust_id'];
	$registering = true;
}

$today = today ();

// Build the query based on the time range and group -- this could be a function...
$classes = get_table_name ( "classes" );
$query = "SELECT * FROM {$classes} ";

if ($time_range == 1) // If Current
{
	$query .= "WHERE end_date >= '{$today}' AND start_date <= '{$today}' ";
	$query .= "ORDER BY title ASC";
} elseif ($time_range == 3) // If Archive
{
	$query .= "ORDER BY start_date DESC";
} // If anything else {
	$time_range = 2;
	$query .= "WHERE start_date > '{$today}' ";
	$query .= "ORDER BY title ASC";
}
?>

<div class="pagination">
	<ul>
		<li <?php if($time_range == 1){ echo "class=\"active\""; } ?>><a
			href="admin.php?page=<?php echo $page_name; ?>&amp;t=1<?php if($cust_id != 0){ echo "&amp;cust_id={$cust_id}"; } ?>">Current</a></li>
		<li <?php if($time_range == 2){ echo "class=\"active\""; } ?>><a
			href="admin.php?page=<?php echo $page_name; ?>&amp;t=2<?php if($cust_id != 0){ echo "&amp;cust_id={$cust_id}"; } ?>">Upcoming</a></li>
    <?php
				if (! $registering) {
					echo "<li";
					if ($time_range == 3) {
						echo " class=\"active\"";
					}
					echo "><a href=\"admin.php?page={$page_name}&amp;t=3";
					if ($cust_id != 0) {
						echo "&amp;cust_id={$cust_id}";
					}
					echo "\">Archive</a></li>";
				}
				?>
  </ul>
</div>
