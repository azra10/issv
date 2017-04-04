<div class="row-fluid">
	<div class="span3">
		<label class="control-label" for="public_id">ID#</label> <input
			class="span12" placeholder="ID#" type="text" name="public_id"
			value="<?php if($edit){echo $class['public_id'];} ?>" id="public_id"
			required="required">
	</div>
	<div class="span3">
		<label class="control-label" for="first_name">Title</label> <input
			class="span12" placeholder="Title" type="text" name="title"
			value="<?php if($edit){echo $class['title'];} ?>" id="title"
			required="required">
	</div>
	<div class="span3">
		<label class="control-label" for="teacher">Teacher</label> <input
			class="span12" placeholder="Teacher" type="text" name="teacher"
			value="<?php if($edit){echo $class['teacher'];} ?>" id="teacher"
			required="required">
	</div>
</div>
<div class="row-fluid">
	<div class="span3">
		<label class="control-label" for="start_date">Start Date</label> <input
			class="span12" placeholder="Start Date" type="date" name="start_date"
			value="<?php if($edit){echo $class['start_date'];} ?>"
			id="start_date" required="required">
	</div>
	<div class="span3">
		<label class="control-label" for="end_date">End Date</label> <input
			class="span12" placeholder="End Date" type="date" name="end_date"
			value="<?php if($edit){echo $class['end_date'];} ?>" id="end_date"
			required="required">
	</div>
	<div class="span3">
		<label class="control-label" for="time">Time</label> <input
			class="span12" placeholder="Time" type="text" name="time"
			value="<?php if($edit){echo $class['time'];} ?>" id="time"
			required="required">
	</div>
</div>

<label class="control-label" for="description">Description</label>
<textarea class="span9" rows="4" placeholder="Description" type="text"
	name="description" id="description"><?php if($edit){echo $class['description'];} ?></textarea>

<div class="row-fluid">
	<div class="span2">
		<label class="control-label" for="fee">Fee</label>
		<div class="input-prepend">
			<span class="add-on">$</span> <input class="span12" placeholder="Fee"
				type="number" name="fee"
				value="<?php if($edit){echo $class['fee'];} ?>" id="fee">
		</div>
	</div>
	<div class="span2 offset1">
		<label class="control-label" for="max_size">Max # of Students</label>
		<input class="span12" placeholder="0 for no max" type="number"
			name="max_size" value="<?php if($edit){echo $class['max_size'];} ?>"
			id="max_size">
	</div>
	<div class="span4">
		<label class="control-label" for="image">Image (location)</label> <input
			class="span12" placeholder="Image (location)" type="url" name="image"
			value="<?php if($edit){echo $class['image'];} ?>" id="image">
	</div>
</div>

<div class="row-fluid">
	<div class="span5">
		<label class="checkbox"> <input type="checkbox" name="discounts"
			value="1" id="discounts"
			<?php if($edit){if($class['discounts'] == 1) { echo "checked"; }} ?> />
			Discounts (Will the class have discounted rates?)
		</label>
	</div>
	<div class="span4">
		<label class="checkbox"> <input type="checkbox" name="image_justified"
			value="1" id="image_justified"
			<?php if($edit){if($class['image_justified'] == 1) { echo "checked"; }} ?> />
			Image Justified Left (Auto Justifies Right)
		</label>
	</div>
</div>

<label class="control-label" for="custom_html">Custom HTML</label>
<textarea class="span9" rows="4" placeholder="Custom HTML" type="text"
	name="custom_html" id="custom_html"><?php if($edit){echo $class['custom_html'];} ?></textarea>

<br>
Choose the shortcode display type (none, one, or many):
<br>
<label class="checkbox inline"> <input type="checkbox" name="yoga_class"
	value="1" id="yoga_class"
	<?php if($edit){if($class['yoga_class'] == 1) { echo "checked"; }} ?> /> List <?php
	if (get_option ( 'classdex_list_shortcode' ) != FALSE) {
		echo "(" . esc_attr ( get_option ( 'classdex_list_shortcode' ) ) . ")";
	}
	?>
		</label>

<label class="checkbox inline"> <input type="checkbox" name="seminar"
	value="1" id="seminar"
	<?php if($edit){if($class['seminar'] == 1) { echo "checked"; }} ?> /> Detail <?php
	if (get_option ( 'classdex_detail_shortcode' ) != FALSE) {
		echo "(" . esc_attr ( get_option ( 'classdex_detail_shortcode' ) ) . ")";
	}
	?>
		</label>

<label class="checkbox inline"> <input type="checkbox" name="meditation"
	value="1" id="meditation"
	<?php if($edit){if($class['meditation'] == 1) { echo "checked"; }} ?> /> Detail1 <?php
	if (get_option ( 'classdex_detail1_shortcode' ) != FALSE) {
		echo "(" . esc_attr ( get_option ( 'classdex_detail1_shortcode' ) ) . ")";
	}
	?>
		</label>

<label class="checkbox inline"> <input type="checkbox" name="wellness"
	value="1" id="wellness"
	<?php if($edit){if($class['wellness'] == 1) { echo "checked"; }} ?> /> Detail2 <?php
	if (get_option ( 'classdex_detail2_shortcode' ) != FALSE) {
		echo "(" . esc_attr ( get_option ( 'classdex_detail2_shortcode' ) ) . ")";
	}
	?>
		</label>

<label class="checkbox inline"> <input type="checkbox" name="philosophy"
	value="1" id="philosophy"
	<?php if($edit){if($class['philosophy'] == 1) { echo "checked"; }} ?> /> Detail3 <?php
	if (get_option ( 'classdex_detail3_shortcode' ) != FALSE) {
		echo "(" . esc_attr ( get_option ( 'classdex_detail3_shortcode' ) ) . ")";
	}
	?>
		</label>

<br>
<br>

<div class="control-group">
	<label class="control-label" for="acct-type">Account Type</label>
	<div class="controls">
		<select id="acct-type" class="input-large" name="acct_type" required>
				  <?php
						if ($edit) {
							echo get_acct_type_option_list ( $class ['acct_type'] );
						} else {
							echo get_acct_type_option_list ();
						}
						?>
				</select>
	</div>
</div>

<label class="checkbox"> <input type="checkbox" name="canceled"
	value="1" id="canceled"
	<?php if($edit){if($class['canceled'] == 1) { echo "checked"; }} ?> />
	Class is Canceled
</label>