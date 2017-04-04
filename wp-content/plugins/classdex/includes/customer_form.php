
<div class="row-fluid">
	<div class="span5">
		<legend>Contact Information</legend>
		<div class="controls controls-row">
			<input class="span5" placeholder="First Name" type="text"
				name="first_name"
				value="<?php if($edit){echo $customer['first_name'];} ?>"
				id="first_name" required="required" /> <input class="span7"
				placeholder="Last Name" type="text" name="last_name"
				value="<?php if($edit){echo $customer['last_name'];} ?>"
				id="last_name" required="required" />
		</div>
		<div class="controls controls-row">
			<input class="span7" placeholder="Email" type="email" name="email"
				value="<?php if($edit){echo $customer['email'];} ?>" id="email" /> <input
				class="span5" placeholder="Phone" type="tel" name="phone"
				value="<?php if($edit){echo $customer['phone'];} ?>" id="phone" />
		</div>
		<label class="checkbox"> <input type="checkbox" name="green" value="1"
			id="green"
			<?php if($edit){if($customer['green'] == 1) { echo "checked"; }} ?>>
			Green (Only Receive E-Newsletter)
		</label>
		<div class="controls">
			<input class="span12" placeholder="Address" type="text"
				name="address"
				value="<?php if($edit){echo $customer['address'];} ?>" id="address" />
		</div>
		<div class="controls controls-row">
			<input class="span6" placeholder="City" type="text" name="city"
				value="<?php if($edit){echo $customer['city'];} ?>" id="city" /> <input
				class="span2" placeholder="State" type="text" name="state"
				value="<?php if($edit){echo $customer['state'];} ?>" id="state"
				maxlength="2" /> <input class="span4" placeholder="Zip" type="text"
				name="zip" value="<?php if($edit){echo $customer['zip'];} ?>"
				id="zip" maxlength="5" />
		</div>
		<label class="checkbox inline"> <input type="checkbox" name="member"
			value="1" id="member"
			<?php if($edit){if($customer['member'] == 1) { echo "checked"; }} ?>> Member (<?php echo esc_attr( get_option('classdex_discount_member') ); ?>% off)
			</label> <label class="checkbox inline"> <input type="checkbox"
			name="senior" value="1" id="senior"
			<?php if($edit){if($customer['senior'] == 1) { echo "checked"; }} ?>> Senior (<?php echo esc_attr( get_option('classdex_discount_senior') ); ?>% off)
			</label> <label class="checkbox inline"> <input type="checkbox"
			name="student" value="1" id="student"
			<?php if($edit){if($customer['student'] == 1) { echo "checked"; }} ?>> Student (<?php echo esc_attr( get_option('classdex_discount_student') ); ?>% off)
			</label> <br>
		<br>
		<div class="form-group">
			<label for="notes">Notes:</label>
			<textarea class="form-control span12" rows="5" id="notes"
				name="notes"><?php if($edit){echo $customer['notes'];} ?></textarea>
		</div>
	</div>
	<div class="span6 offset1">
		<legend>Class Information</legend>
		<label>Most Recent Activity: </label> <input class="span5" type="date"
			name="active_date"
			value="<?php
			if ($edit) {
				echo $customer ['active_date'];
			} else {
				echo date ( "Y-m-d", strtotime ( "now" ) );
			}
			?>"
			id="active_date" /> <label>Signed Waiver: </label> <input
			class="span5" type="date" name="signed_waiver"
			value="<?php if($edit){echo $customer['signed_waiver'];} ?>"
			id="signed_waiver" /> <label>Membership Expires: </label> <input
			class="span5" type="date" name="member_expiration"
			value="<?php if($edit){echo $customer['member_expiration'];} ?>"
			id="member_expiration" /> <label>Class Credit: </label>
		<div class="input-prepend">
			<span class="add-on">$</span> <input class="span4" type="number"
				name="class_credit"
				value="<?php if($edit){echo $customer['class_credit'];} ?>"
				id="class_credit" />
		</div>
	</div>
</div>

