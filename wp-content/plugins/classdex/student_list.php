<?php global $wpdb; ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php include(CLASSDEX_PATH . "/includes/header.php"); ?>
<?php
if (! isset ( $_GET ['class_id'] ) || intval ( $_GET ['class_id'] ) == 0) {
	// If no class is selected, escape.
	redirect_to ( "admin.php?page=classdex_home" );
} else {
	$class_id = $_GET ['class_id'];
}

$class = get_class_by_id ( $class_id );
?>

<legend>Class List: <?php echo "{$class['title']} - {$class['teacher']} (" . date("M j, Y", strtotime($class['start_date'])) . ")"; ?></legend>

<?php
display_student_list ( $class_id );
?>

<?php require(CLASSDEX_PATH . "/includes/footer.php"); ?>