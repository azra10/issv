<?php include(CLASSDEX_PATH . "/includes/header.php"); ?>

<legend>ClassDex Settings</legend>

<div class="alert alert-info">
	<strong>Note:</strong> For more detailed information about these
	settings, visit <a
		href="http://howtononprofit.wordpress.com/classdex/settings/">http://howtononprofit.wordpress.com/classdex/settings/</a>
</div>

<form action="options.php" method="post" id="classdex-options-form">
<?php
settings_fields ( 'classdex_options' );
?>
<strong>Account Types (Required):</strong> Used for accounting, so
	should cover all possible channels of income. List account types
	seperated by commas without spaces in between. Example:
	Hatha,Vinyasa,Meditation,Teacher Training,Rent,Donations. The order of
	these matters, so pick your list and stick with it. If you need to make
	a change add onto the end of the list. <br>
	<br> <label for="classdex_account_types">List of account types
		(Required): </label> <input required="required" class="input-xxlarge"
		type="text" id="classdex_account_types" name="classdex_account_types"
		value="<?php echo esc_attr( get_option('classdex_account_types') ); ?>" />
<?php submit_button(); ?>
<hr>
	<strong>Discounts (Required):</strong> All students can be identified
	as a Member, Senior, or Student. If you want to give discounted rates
	for any or all of these categories, enter the percentage off as a whole
	number (i.e. for 10% off, enter the number 10). If you do not want to
	give discounts for one of these groups, enter the number 0. (Note: You
	can turn discounts on an off for each particular class.)<br>
	<br> <label for="classdex_discount_member">Member Discount (Required):
	</label> <input required="required" class="input-medium" type="number"
		id="classdex_discount_member" name="classdex_discount_member"
		value="<?php echo esc_attr( get_option('classdex_discount_member') ); ?>" />

	<label for="classdex_discount_senior">Senior Discount (Required): </label>
	<input required="required" class="input-medium" type="number"
		id="classdex_discount_senior" name="classdex_discount_senior"
		value="<?php echo esc_attr( get_option('classdex_discount_senior') ); ?>" />

	<label for="classdex_discount_student">Student Discount (Required): </label>
	<input required="required" class="input-medium" type="number"
		id="classdex_discount_student" name="classdex_discount_student"
		value="<?php echo esc_attr( get_option('classdex_discount_student') ); ?>" />
<?php submit_button(); ?>
<hr>
	<strong>Mailchimp Integration:</strong> Sends email to list when
	editing student information.<br>
	<br> <label for="classdex_mailchimp_apikey">Mailchimp API Key: </label>
	<input type="text" id="classdex_mailchimp_apikey"
		name="classdex_mailchimp_apikey"
		value="<?php echo esc_attr( get_option('classdex_mailchimp_apikey') ); ?>" />

	<label for="classdex_mailchimp_listid">Mailchimp List ID: </label> <input
		type="text" id="classdex_mailchimp_listid"
		name="classdex_mailchimp_listid"
		value="<?php echo esc_attr( get_option('classdex_mailchimp_listid') ); ?>" />
<?php submit_button(); ?>
<hr>
	<strong>Zip Code Range:</strong> Determines the range of zip codes that
	will be include when exporting a mailing list.<br>
	<br> <label for="classdex_zip_start">Zip Code Range Start: </label> <input
		type="number" id="classdex_zip_start" name="classdex_zip_start"
		value="<?php echo esc_attr( get_option('classdex_zip_start') ); ?>" />

	<label for="classdex_zip_stop">Zip Code Range Stop: </label> <input
		type="number" id="classdex_zip_stop" name="classdex_zip_stop"
		value="<?php echo esc_attr( get_option('classdex_zip_stop') ); ?>" />
<?php submit_button(); ?>
<hr>
	<strong>HTML for Month Headings in Seminar Shortcode Display</strong> <label
		for="classdex_month_01">January: </label> <input class="input-xxlarge"
		type="text" id="classdex_month_01" name="classdex_month_01"
		value="<?php echo esc_attr( get_option('classdex_month_01') ); ?>" />

	<label for="classdex_month_02">February: </label> <input
		class="input-xxlarge" type="text" id="classdex_month_02"
		name="classdex_month_02"
		value="<?php echo esc_attr( get_option('classdex_month_02') ); ?>" />

	<label for="classdex_month_03">March: </label> <input
		class="input-xxlarge" type="text" id="classdex_month_03"
		name="classdex_month_03"
		value="<?php echo esc_attr( get_option('classdex_month_03') ); ?>" />

	<label for="classdex_month_04">April: </label> <input
		class="input-xxlarge" type="text" id="classdex_month_04"
		name="classdex_month_04"
		value="<?php echo esc_attr( get_option('classdex_month_04') ); ?>" />

	<label for="classdex_month_05">May: </label> <input
		class="input-xxlarge" type="text" id="classdex_month_05"
		name="classdex_month_05"
		value="<?php echo esc_attr( get_option('classdex_month_05') ); ?>" />

	<label for="classdex_month_06">June: </label> <input
		class="input-xxlarge" type="text" id="classdex_month_06"
		name="classdex_month_06"
		value="<?php echo esc_attr( get_option('classdex_month_06') ); ?>" />

	<label for="classdex_month_07">July: </label> <input
		class="input-xxlarge" type="text" id="classdex_month_07"
		name="classdex_month_07"
		value="<?php echo esc_attr( get_option('classdex_month_07') ); ?>" />

	<label for="classdex_month_08">August: </label> <input
		class="input-xxlarge" type="text" id="classdex_month_08"
		name="classdex_month_08"
		value="<?php echo esc_attr( get_option('classdex_month_08') ); ?>" />

	<label for="classdex_month_09">September: </label> <input
		class="input-xxlarge" type="text" id="classdex_month_09"
		name="classdex_month_09"
		value="<?php echo esc_attr( get_option('classdex_month_09') ); ?>" />

	<label for="classdex_month_10">October: </label> <input
		class="input-xxlarge" type="text" id="classdex_month_10"
		name="classdex_month_10"
		value="<?php echo esc_attr( get_option('classdex_month_10') ); ?>" />

	<label for="classdex_month_11">November: </label> <input
		class="input-xxlarge" type="text" id="classdex_month_11"
		name="classdex_month_11"
		value="<?php echo esc_attr( get_option('classdex_month_11') ); ?>" />

	<label for="classdex_month_12">December: </label> <input
		class="input-xxlarge" type="text" id="classdex_month_12"
		name="classdex_month_12"
		value="<?php echo esc_attr( get_option('classdex_month_12') ); ?>" />
<?php submit_button(); ?>
<hr>
	<strong>Waiver Text for Class Waivers</strong> <label
		for="classdex_waiver">Full text for waiver: </label> <input
		class="input-xxlarge" type="text" id="classdex_waiver"
		name="classdex_waiver"
		value="<?php echo esc_attr( get_option('classdex_waiver') ); ?>" />
<?php submit_button(); ?>
<hr>
	<strong>Email for Z-Out:</strong> Set the email that accounting Z-Outs
	get sent to.<br>
	<br> <label for="classdex_zout_email">Email address: </label> <input
		class="input-large" type="email" id="classdex_zout_email"
		name="classdex_zout_email"
		value="<?php echo esc_attr( get_option('classdex_zout_email') ); ?>" />
<?php submit_button(); ?>
<hr>
	<strong>Shortcode Display Type Descriptions:</strong> Make it easy to
	assign classes to a shortcode.<br>
	<br> <label for="classdex_list_shortcode">List: </label> <input
		class="input-large" type="text" id="classdex_list_shortcode"
		name="classdex_list_shortcode"
		value="<?php echo esc_attr( get_option('classdex_list_shortcode') ); ?>" />

	<label for="classdex_detail_shortcode">Detail: </label> <input
		class="input-large" type="text" id="classdex_detail_shortcode"
		name="classdex_detail_shortcode"
		value="<?php echo esc_attr( get_option('classdex_detail_shortcode') ); ?>" />

	<label for="classdex_detail1_shortcode">Detail1: </label> <input
		class="input-large" type="text" id="classdex_detail1_shortcode"
		name="classdex_detail1_shortcode"
		value="<?php echo esc_attr( get_option('classdex_detail1_shortcode') ); ?>" />

	<label for="classdex_detail2_shortcode">Detail2: </label> <input
		class="input-large" type="text" id="classdex_detail2_shortcode"
		name="classdex_detail2_shortcode"
		value="<?php echo esc_attr( get_option('classdex_detail2_shortcode') ); ?>" />

	<label for="classdex_detail3_shortcode">Detail3: </label> <input
		class="input-large" type="text" id="classdex_detail3_shortcode"
		name="classdex_detail3_shortcode"
		value="<?php echo esc_attr( get_option('classdex_detail3_shortcode') ); ?>" />
<?php submit_button(); ?>
</form>
<?php if(get_option('timezone_string') == FALSE) { ?>
<div class="alert alert-danger">
	<strong>Warning:</strong> No timezone has been set. Visit the Wordpress
	Dashboard >> Settings >> General and set the timezone to a city near
	you.
</div>
<?php
}
	
require(CLASSDEX_PATH . "/includes/footer.php"); ?>