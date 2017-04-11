<?php
/*
 * Plugin Name: ISS Admin Preferences
 * Description: Save admin preferences.
 * Version: 1.0.0
 * Author: Azra Syed
 * Text Domain: iss_adminpref
 */

/**
 * custom option and settings
 */
function iss_adminpref_schoolname() {
	$options = get_option ( 'iss_options' );
	if (isset ( $options ['iss_schoolname'] ))
		return $options ['iss_schoolname'];
	return NULL;
}
function iss_adminpref_registrationyear() {
	$options = get_option ( 'iss_options' );
	if (isset ( $options ['iss_registrationyear'] ))
		return $options ['iss_registrationyear'];
	return NULL;
}
function iss_adminpref_registrationfee_installments() {
	$options = get_option ( 'iss_options' );
	if (isset ( $options ['iss_registrationfee_installments'] ))
		return $options ['iss_registrationfee_installments'];
	return NULL;
}
function iss_adminpref_registrationfee1() {
	$options = get_option ( 'iss_options' );
	if (isset ( $options ['iss_registrationfee1'] ))
		return $options ['iss_registrationfee1'];
	return NULL;
}
function iss_adminpref_registrationfee1_installment() {
	$options = get_option ( 'iss_options' );
	if (isset ( $options ['iss_registrationfee1'] ))
		return $options ['iss_registrationfee1'] / 2;
	return NULL;
}
function iss_adminpref_registrationfee2() {
	$options = get_option ( 'iss_options' );
	if (isset ( $options ['iss_registrationfee2'] ))
		return $options ['iss_registrationfee2'];
	return NULL;
}
function iss_adminpref_registrationfee2_installment() {
	$options = get_option ( 'iss_options' );
	if (isset ( $options ['iss_registrationfee2'] ))
		return $options ['iss_registrationfee2'] / 2;
	return NULL;
}
function iss_adminpref_openregistrationdays() {
	$options = get_option ( 'iss_options' );
	if (isset ( $options ['iss_openregistrationperiod_days'] ))
		return $options ['iss_openregistrationperiod_days'];
	return NULL;
}
function iss_settings_init() {
	// register a new setting for "wporg" page
	register_setting ( 'wporg', 'iss_options' );
	
	// register a new section in the "wporg" page
	add_settings_section ( 'iss_registrationyear_section', __ ( 'Change Registration Year', 'wporg' ), 'iss_registrationyear_section_cb', 'wporg' );
	
	// register a new field in the "iss_registrationyear_section" section, inside the "wporg" page
	add_settings_field ( 'iss_field0', __ ( 'School Name', 'wporg' ), 'iss_textinput_field_cb', 'wporg', 'iss_registrationyear_section', [ 
			'label_for' => 'iss_schoolname',
			'class' => 'iss_row',
			'iss_custom_data' => 'custom' 
	] );
	add_settings_field ( 'iss_field', // as of WP 4.6 this value is used only internally
	             // use $args' label_for to populate the id inside the callback
	__ ( 'Registration Year', 'wporg' ), 'iss_registrationyear_field_cb', 'wporg', 'iss_registrationyear_section', [ 
			'label_for' => 'iss_registrationyear',
			'class' => 'iss_row',
			'iss_custom_data' => 'custom' 
	] );
	add_settings_field ( 'iss_field6', __ ( 'Registration Fee Installments', 'wporg' ), 'iss_textinput_field_cb', 'wporg', 'iss_registrationyear_section', [ 
			'label_for' => 'iss_registrationfee_installments',
			'class' => 'iss_row',
			'iss_custom_data' => 'custom' 
	] );
	add_settings_field ( 'iss_field1', __ ( 'Registration Fee (first child)', 'wporg' ), 'iss_textinput_field_cb', 'wporg', 'iss_registrationyear_section', [ 
			'label_for' => 'iss_registrationfee1',
			'class' => 'iss_row',
			'iss_custom_data' => 'custom' 
	] );
	// add_settings_field(
	// 'iss_field3',
	// __('Registration Installment (first child)', 'wporg'),
	// 'iss_textinput_field_cb',
	// 'wporg',
	// 'iss_registrationyear_section',
	// [
	// 'label_for' => 'iss_registrationfee1_installment',
	// 'class' => 'iss_row',
	// 'iss_custom_data' => 'custom',
	// ]
	// );
	add_settings_field ( 'iss_field2', __ ( 'Registration Fee (siblings)', 'wporg' ), 'iss_textinput_field_cb', 'wporg', 'iss_registrationyear_section', [ 
			'label_for' => 'iss_registrationfee2',
			'class' => 'iss_row',
			'iss_custom_data' => 'custom' 
	] );
	// add_settings_field(
	// 'iss_field4',
	// __('Registration Installment (siblings)', 'wporg'),
	// 'iss_textinput_field_cb',
	// 'wporg',
	// 'iss_registrationyear_section',
	// [
	// 'label_for' => 'iss_registrationfee2_installment',
	// 'class' => 'iss_row',
	// 'iss_custom_data' => 'custom',
	// ]
	// );
	add_settings_field ( 'iss_field5', __ ( 'Open Registration Days', 'wporg' ), 'iss_textinput_field_cb', 'wporg', 'iss_registrationyear_section', [ 
			'label_for' => 'iss_openregistrationperiod_days',
			'class' => 'iss_row',
			'iss_custom_data' => 'custom' 
	] );
}

/**
 * register our iss_settings_init to the admin_init action hook
 */
add_action ( 'admin_init', 'iss_settings_init' );

/**
 * custom option and settings:
 * callback functions
 */

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function iss_registrationyear_section_cb($args) {
	/*
	 * ?>
	 * <p id="<?= esc_attr($args['id']); ?>"><?= esc_html__('Follow the white rabbit.', 'wporg'); ?></p>
	 * <?php
	 */
}
function iss_textinput_field_cb($args) {
	$options = get_option ( 'iss_options' );
	
	// output the field
	?>
<input id="<?= esc_attr($args['label_for']); ?>" type="text"
	max-length="256" size="50"
	data-custom="<?= esc_attr($args['iss_custom_data']); ?>"
	name="iss_options[<?= esc_attr($args['label_for']); ?>]"
	value="<?php if (isset($options[$args['label_for']])) echo $options[$args['label_for']]; ?>">
<?php
}

// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function iss_registrationyear_field_cb($args) {
	$regyearlist = iss_get_registrationyear_list ();
	// get the value of the setting we've registered with register_setting()
	$options = get_option ( 'iss_options' );
	// output the field
	?>
<select id="<?= esc_attr($args['label_for']); ?>"
	data-custom="<?= esc_attr($args['iss_custom_data']); ?>"
	name="iss_options[<?= esc_attr($args['label_for']); ?>]">
	<option value="">Select Registration Year</option>
	<?php foreach ($regyearlist as $regyear) { ?>
        <option value="<?php echo $regyear['RegistrationYear']; ?>" 
        <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], $regyear[ 'RegistrationYear'], false)) : (''); ?>>
            <?= esc_html($regyear[ 'RegistrationYear'], 'wporg'); ?>
        </option>
		<?php } ?>
		<option value="2016-2017">2016-2017</option>        
    </select>
<?php
}

/**
 * top level menu
 */
function iss_options_page() {
	// add top level menu page
	add_menu_page ( 'Admin Preferences', 'Admin Preferences', 'iss_admin', 'wporg', 'iss_options_page_html' );
}

/**
 * register our iss_options_page to the admin_menu action hook
 */
add_action ( 'admin_menu', 'iss_options_page' );

/**
 * top level menu:
 * callback functions
 */
function iss_options_page_html() {
	// check user capabilities
	if (! current_user_can ( 'iss_admin' )) {
		return;
	}
	
	// add error/update messages
	
	// check if the user have submitted the settings
	// wordpress will add the "settings-updated" $_GET parameter to the url
	if (isset ( $_GET ['settings-updated'] )) {
		// add settings saved message with the class of "updated"
		add_settings_error ( 'iss_messages', 'iss_message', __ ( 'Settings Saved', 'wporg' ), 'updated' );
	}
	
	// show error/update messages
	settings_errors ( 'iss_messages' );
	?>
<div class="wrap">
	<h1><?= esc_html(get_admin_page_title()); ?></h1>
	<form action="options.php" method="post">
            <?php
	// output security fields for the registered setting "wporg"
	settings_fields ( 'wporg' );
	// output setting sections and their fields
	// (sections are registered for "wporg", each field is registered to a specific section)
	do_settings_sections ( 'wporg' );
	// output save settings button
	if (current_user_can ( 'manage_options' ))
		submit_button ( 'Save Settings' );
	?>
        </form>
</div>
<?php
}
?>