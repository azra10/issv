<?php check_admin_referer( 'classdex_create_class','classdex_create_class_nonce' );  ?>
<?php global $wpdb; ?>
<?php require_once(CLASSDEX_PATH . "/includes/functions.php"); ?>
<?php
$errors = array ();

$required_fields = array (
		'public_id',
		'title',
		'teacher' 
);
foreach ( $required_fields as $fieldname ) {
	if (! isset ( $_POST [$fieldname] ) || empty ( $_POST [$fieldname] )) {
		$errors [] = $fieldname;
	}
}

if (! empty ( $errors )) {
	redirect_to ( "admin.php?page=classdex_new_class" );
}

$public_id = $_POST ['public_id'];
$title = stripslashes_deep ( $_POST ['title'] );
$teacher = $_POST ['teacher'];
$start_date = $_POST ['start_date'];
$end_date = $_POST ['end_date'];
$time = $_POST ['time'];
$description = stripslashes_deep ( $_POST ['description'] );
$fee = $_POST ['fee'];
$max_size = $_POST ['max_size'];
$custom_html = stripslashes_deep ( $_POST ['custom_html'] );
$image = $_POST ['image'];
$acct_type = $_POST ['acct_type'];
if (isset ( $_POST ['discounts'] )) {
	$discounts = 1;
} else {
	$discounts = 0;
}
if (isset ( $_POST ['yoga_class'] )) {
	$yoga_class = 1;
} else {
	$yoga_class = 0;
}
if (isset ( $_POST ['seminar'] )) {
	$seminar = 1;
} else {
	$seminar = 0;
}
if (isset ( $_POST ['meditation'] )) {
	$meditation = 1;
} else {
	$meditation = 0;
}
if (isset ( $_POST ['wellness'] )) {
	$wellness = 1;
} else {
	$wellness = 0;
}
if (isset ( $_POST ['philosophy'] )) {
	$philosophy = 1;
} else {
	$philosophy = 0;
}
if (isset ( $_POST ['canceled'] )) {
	$canceled = 1;
} else {
	$canceled = 0;
}
if (isset ( $_POST ['image_justified'] )) {
	$image_justified = 1;
} else {
	$image_justified = 0;
}

$classes = get_table_name ( "classes" );

$result = $wpdb->insert ( $classes, array (
		'public_id' => $public_id,
		'title' => $title,
		'teacher' => $teacher,
		'start_date' => $start_date,
		'end_date' => $end_date,
		'time' => $time,
		'description' => $description,
		'fee' => $fee,
		'max_size' => $max_size,
		'image' => $image,
		'custom_html' => $custom_html,
		'discounts' => $discounts,
		'yoga_class' => $yoga_class,
		'seminar' => $seminar,
		'meditation' => $meditation,
		'wellness' => $wellness,
		'philosophy' => $philosophy,
		'canceled' => $canceled,
		'image_justified' => $image_justified,
		'acct_type' => $acct_type 
), array (
		'%s', // public_id
		'%s', // title
		'%s', // teacher
		'%s', // start_date
		'%s', // end_date
		'%s', // time
		'%s', // description
		'%d', // fee
		'%d', // max_size
		'%s', // image
		'%s', // custom_html
		'%d', // discounts
		'%d', // yoga_class
		'%d', // seminar
		'%d', // meditation
		'%d', // wellness
		'%d', // philosophy
		'%d', // canceled
		'%d', // image_justified
		'%s' 
) // acct_type
 );

// Create Cart66 Product
$new_id = $wpdb->insert_id;

if ($discounts) {
	$options = get_discount_options ( $fee );
}

if (class_exists ( 'Cart66Product' )) {
	$product = new Cart66Product ();
	$_POST ['cart66_product_nonce'] = wp_create_nonce ( 'cart66_product_nonce' );
	if ($discounts) {
		$product->setData ( array (
				'name' => $title . " - " . $teacher . " (" . $public_id . ")",
				'item_number' => $new_id,
				'price' => 0,
				'shipped' => 0,
				'options_1' => $options,
				'custom' => 'single',
				'custom_desc' => 'Registering multiple students? List names' 
		) );
	} else {
		$product->setData ( array (
				'name' => $title . " - " . $teacher . " (" . $public_id . ")",
				'item_number' => $new_id,
				'price' => $fee,
				'shipped' => 0,
				'custom' => 'single',
				'custom_desc' => 'Registering multiple students? List names' 
		) );
	}
	$product->save ();
	$product->clear ();
}

if ($result != false) {
	// Success!
	redirect_to ( "admin.php?page=classdex_new_class" );
} else {
	// Display error message.
	echo "<p>Class creation failed.</p>";
	echo "<p>" . $wpdb->print_error () . "</p>";
}
?>