<?php
if (class_exists ( 'Cart66Product' )) {
	$product = new Cart66Product ();
	$product->set_data ( array (
			'name' => 'Product Name',
			'item_number' => 'Item Number',
			'price' => 10.00 
	) );
	$product->save ();
	$product->clear ();
}

?>

