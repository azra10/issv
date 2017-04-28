
<div class="form-alerts">
<?php
echo (isset($zf_error) ? $zf_error : (isset($error) ? $error : ''));
$form_list = get_option('software_issue_manager_glob_forms_list');
$form_list_init = get_option('software_issue_manager_glob_forms_init_list');
if (!empty($form_list['issue_search'])) {
	$form_variables = $form_list['issue_search'];
}
$form_variables_init = $form_list_init['issue_search'];
$max_row = count($form_variables_init);
foreach ($form_variables_init as $fkey => $fval) {
	if (empty($form_variables[$fkey])) {
		$form_variables[$fkey] = $form_variables_init[$fkey];
	}
}
$ext_inputs = Array();
$ext_inputs = apply_filters('emd_ext_form_inputs', $ext_inputs, 'software_issue_manager', 'issue_search');
$form_variables = apply_filters('emd_ext_form_var_init', $form_variables, 'software_issue_manager', 'issue_search');
$req_hide_vars = emd_get_form_req_hide_vars('software_issue_manager', 'issue_search');
$glob_list = get_option('software_issue_manager_glob_list');
?>
</div>
<!-- issue_search Form Description -->
<div class="issue_search_desc">
<?php _e('<p>Use this <em>form</em> to search project issues.</p>', 'software-issue-manager'); ?>
</div>
<fieldset>
<?php wp_nonce_field('issue_search', 'issue_search_nonce'); ?>
<input type="hidden" name="form_name" id="form_name" value="issue_search">
<div class="issue_search-btn-fields container-fluid">
<!-- issue_search Form Attributes -->
<div class="issue_search_attributes">
<div id="row13" class="row ">
<!-- text input-->
<?php if ($form_variables['emd_iss_id']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['emd_iss_id']['size']; ?> woptdiv">
<div class="form-group">
<label id="label_emd_iss_id" class="control-label" for="emd_iss_id">
<?php _e('ID', 'software-issue-manager'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Sets a unique identifier for an issue.', 'software-issue-manager'); ?>" id="info_emd_iss_id" class="helptip"><span class="field-icons icons-help"></span></a>
<?php if (in_array('emd_iss_id', $req_hide_vars['req'])) { ?>
<a href="#" data-html="true" data-toggle="tooltip" title="<?php _e('ID field is required', 'software-issue-manager'); ?>" id="info_emd_iss_id" class="helptip">
<span class="field-icons icons-required"></span>
</a>
<?php
	} ?>
</span>
</label>
<?php echo $emd_iss_id; ?>
</div>
</div>
<?php
} ?>
</div>
<div id="row14" class="row ">
<!-- date-->
<?php if ($form_variables['emd_iss_due_date']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['emd_iss_due_date']['size']; ?> woptdiv">
<div class="form-group">
<label id="label_emd_iss_due_date" class="control-label" for="emd_iss_due_date">
<?php _e('Due Date', 'software-issue-manager'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;"> <a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Sets the targeted resolution date for an issue.', 'software-issue-manager'); ?>" id="info_emd_iss_due_date" class="helptip"><span class="field-icons icons-help"></span></a>
<?php if (in_array('emd_iss_due_date', $req_hide_vars['req'])) { ?>
<a href="#" data-html="true" data-toggle="tooltip" title="<?php _e('Due Date field is required', 'software-issue-manager'); ?>" id="info_emd_iss_due_date" class="helptip">
<span class="field-icons icons-required"></span>
</a>
<?php
	} ?> </span>
</label>
<?php echo $emd_iss_due_date; ?>
</div>
</div>
<?php
} ?>
</div>
<div id="row15" class="row ">
<!-- Taxonomy input-->
<?php if ($form_variables['issue_cat']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['issue_cat']['size']; ?>">
<div class="form-group">
<label id="label_issue_cat" class="control-label" for="issue_cat">
<?php _e('Category', 'software-issue-manager'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Sets the category that an issue belongs to.', 'software-issue-manager'); ?>" id="info_issue_cat" class="helptip"><span class="field-icons icons-help"></span></a>
<?php if (in_array('issue_cat', $req_hide_vars['req'])) { ?>
<a href="#" data-html="true" data-toggle="tooltip" title="<?php _e('Category field is required', 'software-issue-manager'); ?>" id="info_issue_cat" class="helptip">
<span class="field-icons icons-required"></span>
</a>
<?php
	} ?>
</span>
</label>
<?php echo $issue_cat; ?>
</div>
</div>
<?php
} ?>
</div>
<div id="row16" class="row ">
<!-- Taxonomy input-->
<?php if ($form_variables['issue_priority']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['issue_priority']['size']; ?>">
<div class="form-group">
<label id="label_issue_priority" class="control-label" for="issue_priority">
<?php _e('Priority', 'software-issue-manager'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Sets the priority level assigned to an issue.', 'software-issue-manager'); ?>" id="info_issue_priority" class="helptip"><span class="field-icons icons-help"></span></a>
<?php if (in_array('issue_priority', $req_hide_vars['req'])) { ?>
<a href="#" data-html="true" data-toggle="tooltip" title="<?php _e('Priority field is required', 'software-issue-manager'); ?>" id="info_issue_priority" class="helptip">
<span class="field-icons icons-required"></span>
</a>
<?php
	} ?>
</span>
</label>
<?php echo $issue_priority; ?>
</div>
</div>
<?php
} ?>
</div>
<div id="row17" class="row ">
<!-- Taxonomy input-->
<?php if ($form_variables['issue_status']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['issue_status']['size']; ?>">
<div class="form-group">
<label id="label_issue_status" class="control-label" for="issue_status">
<?php _e('Status', 'software-issue-manager'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Sets the current status of an issue.', 'software-issue-manager'); ?>" id="info_issue_status" class="helptip"><span class="field-icons icons-help"></span></a>
<?php if (in_array('issue_status', $req_hide_vars['req'])) { ?>
<a href="#" data-html="true" data-toggle="tooltip" title="<?php _e('Status field is required', 'software-issue-manager'); ?>" id="info_issue_status" class="helptip">
<span class="field-icons icons-required"></span>
</a>
<?php
	} ?>
</span>
</label>
<?php echo $issue_status; ?>
</div>
</div>
<?php
} ?>
</div>
<div id="row18" class="row ">
<!-- rel-ent input-->
<?php if ($form_variables['rel_project_issues']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['rel_project_issues']['size']; ?>">
<div class="form-group">
<label id="label_rel_project_issues" class="control-label" for="rel_project_issues">
<?php _e('Affected Projects', 'software-issue-manager'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Allows to assign issue(s) to project(s) ,and vice versa.', 'software-issue-manager'); ?>" id="info_project_issues" class="helptip"><span class="field-icons icons-help"></span></a>
<?php if (in_array('rel_project_issues', $req_hide_vars['req'])) { ?>
<a href="#" data-html="true" data-toggle="tooltip" title="<?php _e('Affected Projects field is required', 'software-issue-manager'); ?>" id="info_rel_project_issues" class="helptip">
<span class="field-icons icons-required"></span>
</a>
<?php
	} ?>
</span>
</label>
<?php echo $rel_project_issues; ?>
</div>
</div>
<?php
} ?>
</div>
<div id="row22" class="row ext-row">
<!-- rel-ent input-->
<?php
if (!empty($ext_inputs['rel_emd_issue_edd_product']) && $ext_inputs['rel_emd_issue_edd_product']['type'] != 'hidden' && !empty($form_variables['rel_emd_issue_edd_product']) && $form_variables['rel_emd_issue_edd_product']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['rel_emd_issue_edd_product']['size']; ?>">
<div class="form-group">
<label id="label_rel_emd_issue_edd_product" class="control-label" for="rel_emd_issue_edd_product">
<?php _e('Products', 'software-issue-manager'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<?php if (in_array('rel_emd_issue_edd_product', $req_hide_vars['req'])) { ?>
<a href="#" data-html="true" data-toggle="tooltip" title="<?php _e('Products is required', 'software-issue-manager'); ?>" id="info_rel_emd_issue_edd_product" class="helptip">
<span class="field-icons icons-required"></span>
</a>
<?php
	} ?>
</span>
</label>
<?php echo $rel_emd_issue_edd_product; ?>
</div>
</div>
<?php
} ?>
</div>
<div id="row21" class="row ext-row">
<!-- rel-ent input-->
<?php
if (!empty($ext_inputs['rel_emd_issue_woo_product']) && $ext_inputs['rel_emd_issue_woo_product']['type'] != 'hidden' && !empty($form_variables['rel_emd_issue_woo_product']) && $form_variables['rel_emd_issue_woo_product']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['rel_emd_issue_woo_product']['size']; ?>">
<div class="form-group">
<label id="label_rel_emd_issue_woo_product" class="control-label" for="rel_emd_issue_woo_product">
<?php _e('Products', 'software-issue-manager'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<?php if (in_array('rel_emd_issue_woo_product', $req_hide_vars['req'])) { ?>
<a href="#" data-html="true" data-toggle="tooltip" title="<?php _e('Products is required', 'software-issue-manager'); ?>" id="info_rel_emd_issue_woo_product" class="helptip">
<span class="field-icons icons-required"></span>
</a>
<?php
	} ?>
</span>
</label>
<?php echo $rel_emd_issue_woo_product; ?>
</div>
</div>
<?php
} ?>
</div>
<?php
$cust_fields = Array();
$cust_fields = apply_filters('emd_get_cust_fields', $cust_fields, 'emd_issue');
if (!empty($cust_fields)) {
	foreach ($cust_fields as $cfield => $clabel) {
		$max_row++;
		if ($form_variables[$cfield]['show'] == 1) { ?>
             <div id="row<?php echo $max_row; ?>" class="row">
             <!-- custom field text input-->
             <div class="col-md-<?php echo $form_variables[$cfield]['size']; ?> woptdiv">
             <div class="form-group">
             
             <label id="label_<?php echo $cfield; ?>" class="control-label" for="<?php echo $cfield; ?>" >
             <?php echo $clabel; ?>
             <span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
             <?php if (in_array($cfield, $req_hide_vars['req'])) { ?>
                 <a href="#" data-html="true" data-toggle="tooltip" title="<?php printf(__('%s field is required.', 'software-issue-manager') , $cfield); ?>" id="info_<?php echo $cfield; ?>" class="helptip">
                 <span class="field-icons icons-required"></span>
                 </a>
             <?php
			} ?>
             </span>
             </label>
             
             
             <?php echo $$cfield; ?>
             
             
             </div>
             </div>
             </div>
             <?php
		}
	}
}
?>
</div><!--form-attributes-->
<?php if ($show_captcha == 1) { ?>
<div class="row">
<div class="col-xs-12">
<div id="captcha-group" class="form-group">
<?php echo $captcha_image; ?>
<label style="padding:0px;" id="label_captcha_code" class="control-label" for="captcha_code">
<a id="info_captcha_code_help" class="helptip" data-html="true" data-toggle="tooltip" href="#" title="<?php _e('Please enter the characters with black color in the image above.', 'software-issue-manager'); ?>">
<span class="field-icons icons-help"></span>
</a>
<a id="info_captcha_code_req" class="helptip" title="<?php _e('Security Code field is required', 'software-issue-manager'); ?>" data-toggle="tooltip" href="#">
<span class="field-icons icons-required"></span>
</a>
</label>
<?php echo $captcha_code; ?>
</div>
</div>
</div>
<?php
} ?>
<!-- Button -->
<div class="row">
<div class="col-md-12">
<div class="wpas-form-actions">
<?php echo $singlebutton_issue_search; ?>
</div>
</div>
</div>
</div><!--form-btn-fields-->
</fieldset>