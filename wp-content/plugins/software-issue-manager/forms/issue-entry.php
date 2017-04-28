
<div class="form-alerts">
<?php
echo (isset($zf_error) ? $zf_error : (isset($error) ? $error : ''));
$form_list = get_option('software_issue_manager_glob_forms_list');
$form_list_init = get_option('software_issue_manager_glob_forms_init_list');
if (!empty($form_list['issue_entry'])) {
	$form_variables = $form_list['issue_entry'];
}
$form_variables_init = $form_list_init['issue_entry'];
$max_row = count($form_variables_init);
foreach ($form_variables_init as $fkey => $fval) {
	if (empty($form_variables[$fkey])) {
		$form_variables[$fkey] = $form_variables_init[$fkey];
	}
}
$ext_inputs = Array();
$ext_inputs = apply_filters('emd_ext_form_inputs', $ext_inputs, 'software_issue_manager', 'issue_entry');
$form_variables = apply_filters('emd_ext_form_var_init', $form_variables, 'software_issue_manager', 'issue_entry');
$req_hide_vars = emd_get_form_req_hide_vars('software_issue_manager', 'issue_entry');
$glob_list = get_option('software_issue_manager_glob_list');
?>
</div>
<!-- issue_entry Form Description -->
<div class="issue_entry_desc">
<?php _e('<p>Use this <em>form</em> to file <em>issues</em> about projects.</p>', 'software-issue-manager'); ?>
</div>
<fieldset>
<?php wp_nonce_field('issue_entry', 'issue_entry_nonce'); ?>
<input type="hidden" name="form_name" id="form_name" value="issue_entry">
<div class="issue_entry-btn-fields container-fluid">
<!-- issue_entry Form Attributes -->
<div class="issue_entry_attributes">
<div id="row1" class="row ">
<!-- text input-->
<?php if ($form_variables['blt_title']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['blt_title']['size']; ?> woptdiv">
<div class="form-group">
<label id="label_blt_title" class="control-label" for="blt_title">
<?php _e('Title', 'software-issue-manager'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<?php if (in_array('blt_title', $req_hide_vars['req'])) { ?>
<a href="#" data-html="true" data-toggle="tooltip" title="<?php _e('Title field is required', 'software-issue-manager'); ?>" id="info_blt_title" class="helptip">
<span class="field-icons icons-required"></span>
</a>
<?php
	} ?>
</span>
</label>
<?php echo $blt_title; ?>
</div>
</div>
<?php
} ?>
</div>
<div id="row2" class="row ">
<!-- wysiwyg input-->
<?php if ($form_variables['blt_content']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['blt_content']['size']; ?>">
<div class="form-group">
<label id="label_blt_content" class="control-label" for="blt_content">
<?php _e('Content', 'software-issue-manager'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<?php if (in_array('blt_content', $req_hide_vars['req'])) { ?>
<a href="#" data-html="true" data-toggle="tooltip" title="<?php _e('Content field is required', 'software-issue-manager'); ?>" id="info_blt_content" class="helptip">
<span class="field-icons icons-required"></span>
</a>
<?php
	} ?>
</span>
</label>
<?php echo $blt_content; ?>
</div>
</div>
<?php
} ?>
</div>
<div id="row3" class="row ">
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
<div id="row4" class="row ">
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
<div id="row5" class="row ">
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
<div id="row6" class="row ">
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
<div id="row7" class="row ">
<!-- Taxonomy input-->
<?php if ($form_variables['issue_tag']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['issue_tag']['size']; ?>">
<div class="form-group">
<label id="label_issue_tag" class="control-label" for="issue_tag">
<?php _e('Tag', 'software-issue-manager'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Allows to tag issues to further classify or group related issues.', 'software-issue-manager'); ?>" id="info_issue_tag" class="helptip"><span class="field-icons icons-help"></span></a>
<?php if (in_array('issue_tag', $req_hide_vars['req'])) { ?>
<a href="#" data-html="true" data-toggle="tooltip" title="<?php _e('Tag field is required', 'software-issue-manager'); ?>" id="info_issue_tag" class="helptip">
<span class="field-icons icons-required"></span>
</a>
<?php
	} ?>
</span>
</label>
<?php echo $issue_tag; ?>
</div>
</div>
<?php
} ?>
</div>
<div id="row8" class="row ">
<!-- Taxonomy input-->
<?php if ($form_variables['browser']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['browser']['size']; ?>">
<div class="form-group">
<label id="label_browser" class="control-label" for="browser">
<?php _e('Browser', 'software-issue-manager'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Sets the browser version that an issue may be reproduced in.', 'software-issue-manager'); ?>" id="info_browser" class="helptip"><span class="field-icons icons-help"></span></a>
<?php if (in_array('browser', $req_hide_vars['req'])) { ?>
<a href="#" data-html="true" data-toggle="tooltip" title="<?php _e('Browser field is required', 'software-issue-manager'); ?>" id="info_browser" class="helptip">
<span class="field-icons icons-required"></span>
</a>
<?php
	} ?>
</span>
</label>
<?php echo $browser; ?>
</div>
</div>
<?php
} ?>
</div>
<div id="row9" class="row ">
<!-- Taxonomy input-->
<?php if ($form_variables['operating_system']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['operating_system']['size']; ?>">
<div class="form-group">
<label id="label_operating_system" class="control-label" for="operating_system">
<?php _e('Operating System', 'software-issue-manager'); ?>
<span style="display: inline-flex;right: 0px; position: relative; top:-6px;">
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Sets the operating system(s) that an issue may be reproduced in.', 'software-issue-manager'); ?>" id="info_operating_system" class="helptip"><span class="field-icons icons-help"></span></a>
<?php if (in_array('operating_system', $req_hide_vars['req'])) { ?>
<a href="#" data-html="true" data-toggle="tooltip" title="<?php _e('Operating System field is required', 'software-issue-manager'); ?>" id="info_operating_system" class="helptip">
<span class="field-icons icons-required"></span>
</a>
<?php
	} ?>
</span>
</label>
<?php echo $operating_system; ?>
</div>
</div>
<?php
} ?>
</div>
<div id="row10" class="row ">
<!-- file input-->
<?php if ($form_variables['emd_iss_document']['show'] == 1) { ?>
<div class="col-md-<?php echo $form_variables['emd_iss_document']['size']; ?>">
<?php _e('Documents', 'software-issue-manager'); ?>
<a data-html="true" href="#" data-toggle="tooltip" title="<?php _e('Allows to upload files related to an issue.', 'software-issue-manager'); ?>" id="info_emd_iss_document" class="helptip"><span class="field-icons icons-help"></span></a>
<div class="form-group">
<?php echo $emd_iss_document; ?>
</div>
</div>
<?php
} ?>
</div>
<div id="row11" class="row ">
<!-- HR-->
<hr>
</div>
<div id="row12" class="row ">
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
 
<div id="row20" class="row ext-row">
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
<div id="row19" class="row ext-row">
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
<?php echo $singlebutton_issue_entry; ?>
</div>
</div>
</div>
</div><!--form-btn-fields-->
</fieldset>