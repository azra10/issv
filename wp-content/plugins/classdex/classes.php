<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php include(CLASSDEX_PATH . "/includes/header.php"); ?>
<?php include(CLASSDEX_PATH . "/includes/class_pagination.php"); ?>
	
<?php

if ($time_range == 3) {
	display_classes ( $query, false, true );
} else {
	display_classes ( $query );
}

?>
	
<?php require(CLASSDEX_PATH . "/includes/footer.php"); ?>