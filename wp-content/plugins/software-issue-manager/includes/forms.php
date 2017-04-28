<?php
/**
 * Setup and Process submit and search forms
 * @package SOFTWARE_ISSUE_MANAGER
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
if (is_admin()) {
	add_action('wp_ajax_nopriv_emd_check_unique', 'emd_check_unique');
}
add_action('init', 'software_issue_manager_form_shortcodes', -2);
/**
 * Start session and setup upload idr and current user id
 * @since WPAS 4.0
 *
 */
function software_issue_manager_form_shortcodes() {
	global $file_upload_dir;
	$upload_dir = wp_upload_dir();
	$file_upload_dir = $upload_dir['basedir'];
	if (!empty($_POST['emd_action'])) {
		if ($_POST['emd_action'] == 'software_issue_manager_user_login' && wp_verify_nonce($_POST['emd_login_nonce'], 'emd-login-nonce')) {
			emd_process_login($_POST, 'software_issue_manager');
		} elseif ($_POST['emd_action'] == 'software_issue_manager_user_register' && wp_verify_nonce($_POST['emd_register_nonce'], 'emd-register-nonce')) {
			emd_process_register($_POST, 'software_issue_manager');
		}
	}
}
add_shortcode('issue_entry', 'software_issue_manager_process_issue_entry');
add_shortcode('issue_search', 'software_issue_manager_process_issue_search');
/**
 * Set each form field(attr,tax and rels) and render form
 *
 * @since WPAS 4.0
 *
 * @return object $form
 */
function software_issue_manager_set_issue_entry($atts) {
	global $file_upload_dir;
	$show_captcha = 0;
	$form_variables = get_option('software_issue_manager_glob_forms_list');
	$form_init_variables = get_option('software_issue_manager_glob_forms_init_list');
	if (!empty($atts['set'])) {
		$set_arrs = emd_parse_set_filter($atts['set']);
	}
	if (!empty($form_variables['issue_entry']['captcha'])) {
		switch ($form_variables['issue_entry']['captcha']) {
			case 'never-show':
				$show_captcha = 0;
			break;
			case 'show-always':
				$show_captcha = 1;
			break;
			case 'show-to-visitors':
				if (is_user_logged_in()) {
					$show_captcha = 0;
				} else {
					$show_captcha = 1;
				}
			break;
		}
	}
	$req_hide_vars = emd_get_form_req_hide_vars('software_issue_manager', 'issue_entry');
	$form = new Zebra_Form('issue_entry', 0, 'POST', '', array(
		'class' => 'form-container wpas-form wpas-form-stacked',
		'session_obj' => SOFTWARE_ISSUE_MANAGER()->session
	));
	$csrf_storage_method = (isset($form_variables['issue_entry']['csrf']) ? $form_variables['issue_entry']['csrf'] : $form_init_variables['issue_entry']['csrf']);
	if ($csrf_storage_method == 0) {
		$form->form_properties['csrf_storage_method'] = false;
	}
	if (!in_array('blt_title', $req_hide_vars['hide'])) {
		//text
		$form->add('label', 'label_blt_title', 'blt_title', __('Title', 'software-issue-manager') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'class' => 'input-md form-control',
			'placeholder' => __('Title', 'software-issue-manager')
		);
		if (!empty($_GET['blt_title'])) {
			$attrs['value'] = sanitize_text_field($_GET['blt_title']);
		} elseif (!empty($set_arrs['attr']['blt_title'])) {
			$attrs['value'] = $set_arrs['attr']['blt_title'];
		}
		$obj = $form->add('text', 'blt_title', '', $attrs);
		$zrule = Array();
		if (in_array('blt_title', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Title is required', 'software-issue-manager')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('blt_content', $req_hide_vars['hide'])) {
		//wysiwyg
		$form->add('label', 'label_blt_content', 'blt_content', __('Content', 'software-issue-manager') , array(
			'class' => 'control-label'
		));
		$obj = $form->add('wysiwyg', 'blt_content', '', array(
			'placeholder' => __('Enter text ...', 'software-issue-manager') ,
			'style' => 'width: 100%; height: 200px',
			'class' => 'wyrj'
		));
		$zrule = Array();
		if (in_array('blt_content', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Content is required', 'software-issue-manager')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('emd_iss_due_date', $req_hide_vars['hide'])) {
		//date
		$form->add('label', 'label_emd_iss_due_date', 'emd_iss_due_date', __('Due Date', 'software-issue-manager') , array(
			'class' => 'control-label'
		));
		$obj = $form->add('date', 'emd_iss_due_date', '', array(
			'class' => 'input-md form-control',
			'placeholder' => __('Due Date', 'software-issue-manager')
		));
		$obj->format('m-d-Y');
		$zrule = Array(
			'dependencies' => array() ,
			'date' => array(
				'error',
				__('Due Date: Please enter a valid date format', 'software-issue-manager')
			) ,
		);
		if (in_array('emd_iss_due_date', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Due Date is required', 'software-issue-manager')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('issue_priority', $req_hide_vars['hide'])) {
		$form->add('label', 'label_issue_priority', 'issue_priority', __('Priority', 'software-issue-manager') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'class' => 'input-md'
		);
		if (!empty($_GET['issue_priority'])) {
			$attrs['value'] = sanitize_text_field($_GET['issue_priority']);
		} elseif (!empty($set_arrs['tax']['issue_priority'])) {
			$attrs['value'] = $set_arrs['tax']['issue_priority'];
		}
		$obj = $form->add('selectadv', 'issue_priority', 'normal', $attrs, '', '{"allowClear":true,"placeholder":"' . __("Please Select", "software-issue-manager") . '","placeholderOption":"first"}');
		//get taxonomy values
		$txn_arr = Array();
		$txn_arr[''] = __('Please Select', 'software-issue-manager');
		$txn_obj = get_terms('issue_priority', array(
			'hide_empty' => 0
		));
		foreach ($txn_obj as $txn) {
			$txn_arr[$txn->slug] = $txn->name;
		}
		$obj->add_options($txn_arr);
		$zrule = Array(
			'dependencies' => array() ,
		);
		if (in_array('issue_priority', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Priority is required!', 'software-issue-manager')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('issue_cat', $req_hide_vars['hide'])) {
		$form->add('label', 'label_issue_cat', 'issue_cat', __('Category', 'software-issue-manager') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'class' => 'input-md'
		);
		if (!empty($_GET['issue_cat'])) {
			$attrs['value'] = sanitize_text_field($_GET['issue_cat']);
		} elseif (!empty($set_arrs['tax']['issue_cat'])) {
			$attrs['value'] = $set_arrs['tax']['issue_cat'];
		}
		$obj = $form->add('selectadv', 'issue_cat', 'bug', $attrs, '', '{"allowClear":true,"placeholder":"' . __("Please Select", "software-issue-manager") . '","placeholderOption":"first"}');
		//get taxonomy values
		$txn_arr = Array();
		$txn_arr[''] = __('Please Select', 'software-issue-manager');
		$txn_obj = get_terms('issue_cat', array(
			'hide_empty' => 0
		));
		foreach ($txn_obj as $txn) {
			$txn_arr[$txn->slug] = $txn->name;
		}
		$obj->add_options($txn_arr);
		$zrule = Array(
			'dependencies' => array() ,
		);
		if (in_array('issue_cat', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Category is required!', 'software-issue-manager')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('issue_status', $req_hide_vars['hide'])) {
		$form->add('label', 'label_issue_status', 'issue_status', __('Status', 'software-issue-manager') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'class' => 'input-md'
		);
		if (!empty($_GET['issue_status'])) {
			$attrs['value'] = sanitize_text_field($_GET['issue_status']);
		} elseif (!empty($set_arrs['tax']['issue_status'])) {
			$attrs['value'] = $set_arrs['tax']['issue_status'];
		}
		$obj = $form->add('selectadv', 'issue_status', 'open', $attrs, '', '{"allowClear":true,"placeholder":"' . __("Please Select", "software-issue-manager") . '","placeholderOption":"first"}');
		//get taxonomy values
		$txn_arr = Array();
		$txn_arr[''] = __('Please Select', 'software-issue-manager');
		$txn_obj = get_terms('issue_status', array(
			'hide_empty' => 0
		));
		foreach ($txn_obj as $txn) {
			$txn_arr[$txn->slug] = $txn->name;
		}
		$obj->add_options($txn_arr);
		$zrule = Array(
			'dependencies' => array() ,
		);
		if (in_array('issue_status', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Status is required!', 'software-issue-manager')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('issue_tag', $req_hide_vars['hide'])) {
		$form->add('label', 'label_issue_tag', 'issue_tag', __('Tag', 'software-issue-manager') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'multiple' => 'multiple',
			'class' => 'input-md'
		);
		if (!empty($_GET['issue_tag'])) {
			$attrs['value'] = sanitize_text_field($_GET['issue_tag']);
		} elseif (!empty($set_arrs['tax']['issue_tag'])) {
			$attrs['value'] = $set_arrs['tax']['issue_tag'];
		}
		$obj = $form->add('selectadv', 'issue_tag[]', __('Please Select', 'software-issue-manager') , $attrs, '', '{"allowClear":true,"placeholder":"' . __("Please Select", "software-issue-manager") . '","placeholderOption":"first"}');
		//get taxonomy values
		$txn_arr = Array();
		$txn_obj = get_terms('issue_tag', array(
			'hide_empty' => 0
		));
		foreach ($txn_obj as $txn) {
			$txn_arr[$txn->slug] = $txn->name;
		}
		$obj->add_options($txn_arr);
		$zrule = Array(
			'dependencies' => array() ,
		);
		if (in_array('issue_tag', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Tag is required!', 'software-issue-manager')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('browser', $req_hide_vars['hide'])) {
		$form->add('label', 'label_browser', 'browser', __('Browser', 'software-issue-manager') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'multiple' => 'multiple',
			'class' => 'input-md'
		);
		if (!empty($_GET['browser'])) {
			$attrs['value'] = sanitize_text_field($_GET['browser']);
		} elseif (!empty($set_arrs['tax']['browser'])) {
			$attrs['value'] = $set_arrs['tax']['browser'];
		}
		$obj = $form->add('selectadv', 'browser[]', __('Please Select', 'software-issue-manager') , $attrs, '', '{"allowClear":true,"placeholder":"' . __("Please Select", "software-issue-manager") . '","placeholderOption":"first"}');
		//get taxonomy values
		$txn_arr = Array();
		$txn_obj = get_terms('browser', array(
			'hide_empty' => 0
		));
		foreach ($txn_obj as $txn) {
			$txn_arr[$txn->slug] = $txn->name;
		}
		$obj->add_options($txn_arr);
		$zrule = Array(
			'dependencies' => array() ,
		);
		if (in_array('browser', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Browser is required!', 'software-issue-manager')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('operating_system', $req_hide_vars['hide'])) {
		$form->add('label', 'label_operating_system', 'operating_system', __('Operating System', 'software-issue-manager') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'multiple' => 'multiple',
			'class' => 'input-md'
		);
		if (!empty($_GET['operating_system'])) {
			$attrs['value'] = sanitize_text_field($_GET['operating_system']);
		} elseif (!empty($set_arrs['tax']['operating_system'])) {
			$attrs['value'] = $set_arrs['tax']['operating_system'];
		}
		$obj = $form->add('selectadv', 'operating_system[]', __('Please Select', 'software-issue-manager') , $attrs, '', '{"allowClear":true,"placeholder":"' . __("Please Select", "software-issue-manager") . '","placeholderOption":"first"}');
		//get taxonomy values
		$txn_arr = Array();
		$txn_obj = get_terms('operating_system', array(
			'hide_empty' => 0
		));
		foreach ($txn_obj as $txn) {
			$txn_arr[$txn->slug] = $txn->name;
		}
		$obj->add_options($txn_arr);
		$zrule = Array(
			'dependencies' => array() ,
		);
		if (in_array('operating_system', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Operating System is required!', 'software-issue-manager')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('emd_iss_document', $req_hide_vars['hide'])) {
		//file
		$obj = $form->add('file', 'emd_iss_document', '');
		$zrule = Array(
			'dependencies' => array() ,
			'upload' => array(
				$file_upload_dir,
				true,
				'error',
				'File could not be uploaded.'
			) ,
		);
		$obj->set_rule($zrule);
	}
	if (!in_array('rel_project_issues', $req_hide_vars['hide'])) {
		$form->add('label', 'label_rel_project_issues', 'rel_project_issues', __('Affected Projects', 'software-issue-manager') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'multiple' => 'multiple',
			'class' => 'input-md'
		);
		if (!empty($_GET['rel_project_issues'])) {
			$attrs['value'] = sanitize_text_field($_GET['rel_project_issues']);
		} elseif (!empty($set_arrs['rel']['project_issues'])) {
			$attrs['value'] = $set_arrs['rel']['project_issues'];
		}
		$obj = $form->add('selectadv', 'rel_project_issues[]', __('Please select', 'software-issue-manager') , $attrs, '', '{"allowClear":true,"placeholder":"' . __("Please Select", "software-issue-manager") . '"}');
		//get entity values
		$rel_ent_arr = Array();
		$rel_ent_args = Array(
			'post_type' => 'emd_project',
			'numberposts' => - 1,
			'orderby' => 'title',
			'order' => 'ASC'
		);
		$front_ents = emd_find_limitby('frontend', 'software_issue_manager');
		if (!empty($front_ents) && in_array('emd_project', $front_ents)) {
			$pids = emd_get_form_pids('software_issue_manager', 'emd_project');
			$rel_ent_args['post__in'] = $pids;
		}
		$rel_ent_pids = get_posts($rel_ent_args);
		if (!empty($rel_ent_pids)) {
			foreach ($rel_ent_pids as $my_ent_pid) {
				$rel_ent_arr[$my_ent_pid->ID] = get_the_title($my_ent_pid->ID);
			}
		}
		$obj->add_options($rel_ent_arr);
		$zrule = Array(
			'dependencies' => array() ,
		);
		if (in_array('rel_project_issues', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Affected Projects is required!', 'software-issue-manager')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	//hidden_func
	$emd_iss_id = emd_get_hidden_func('unique_id');
	$form->add('hidden', 'emd_iss_id', $emd_iss_id);
	//hidden
	$obj = $form->add('hidden', 'wpas_form_name', 'issue_entry');
	//hidden_func
	$wpas_form_submitted_by = emd_get_hidden_func('user_login');
	$form->add('hidden', 'wpas_form_submitted_by', $wpas_form_submitted_by);
	//hidden_func
	$wpas_form_submitted_ip = emd_get_hidden_func('user_ip');
	$form->add('hidden', 'wpas_form_submitted_ip', $wpas_form_submitted_ip);
	$ext_inputs = Array();
	$ext_inputs = apply_filters('emd_ext_form_inputs', $ext_inputs, 'software_issue_manager', 'issue_entry');
	foreach ($ext_inputs as $input_param) {
		$inp_name = $input_param['name'];
		if (!in_array($input_param['name'], $req_hide_vars['hide'])) {
			if ($input_param['type'] == 'hidden') {
				$obj = $form->add('hidden', $input_param['name'], $input_param['vals']);
			} elseif ($input_param['type'] == 'select') {
				$form->add('label', 'label_' . $input_param['name'], $input_param['name'], $input_param['label'], array(
					'class' => 'control-label'
				));
				$ext_class['class'] = 'input-md';
				if (!empty($input_param['multiple'])) {
					$ext_class['multiple'] = 'multiple';
					$input_param['name'] = $input_param['name'] . '[]';
				}
				$obj = $form->add('select', $input_param['name'], '', $ext_class, '', '{"allowClear":true,"placeholder":"' . __("Please Select", "software-issue-manager") . '","placeholderOption":"first"}');
				$obj->add_options($input_param['vals']);
				$obj->disable_spam_filter();
			} elseif ($input_param['type'] == 'text') {
				$form->add('label', 'label_' . $input_param['name'], $input_param['name'], $input_param['label'], array(
					'class' => 'control-label'
				));
				$obj = $form->add('text', $input_param['name'], '', array(
					'class' => 'input-md form-control',
					'placeholder' => $input_param['label']
				));
			}
			if ($input_param['type'] != 'hidden' && in_array($inp_name, $req_hide_vars['req'])) {
				$zrule = Array(
					'dependencies' => $input_param['dependencies'],
					'required' => array(
						'error',
						$input_param['label'] . __(' is required', 'software-issue-manager')
					)
				);
				$obj->set_rule($zrule);
			}
		}
	}
	$cust_fields = Array();
	$cust_fields = apply_filters('emd_get_cust_fields', $cust_fields, 'emd_issue');
	foreach ($cust_fields as $ckey => $clabel) {
		if (!in_array($ckey, $req_hide_vars['hide'])) {
			$form->add('label', 'label_' . $ckey, $ckey, $clabel, array(
				'class' => 'control-label'
			));
			$obj = $form->add('text', $ckey, '', array(
				'class' => 'input-md form-control',
				'placeholder' => $clabel
			));
			if (in_array($ckey, $req_hide_vars['req'])) {
				$zrule = Array(
					'required' => array(
						'error',
						$clabel . __(' is required', 'software-issue-manager')
					)
				);
				$obj->set_rule($zrule);
			}
		}
	}
	$form->assign('show_captcha', $show_captcha);
	if ($show_captcha == 1) {
		//Captcha
		$form->add('captcha', 'captcha_image', 'captcha_code', '', '<span style="font-weight:bold;" class="refresh-txt">Refresh</span>', 'refcapt');
		$form->add('label', 'label_captcha_code', 'captcha_code', __('Please enter the characters with black color.', 'software-issue-manager'));
		$obj = $form->add('text', 'captcha_code', '', array(
			'placeholder' => __('Code', 'software-issue-manager')
		));
		$obj->set_rule(array(
			'required' => array(
				'error',
				__('Captcha is required', 'software-issue-manager')
			) ,
			'captcha' => array(
				'error',
				__('Characters from captcha image entered incorrectly!', 'software-issue-manager')
			)
		));
	}
	$form->add('submit', 'singlebutton_issue_entry', '' . __('Create Issue', 'software-issue-manager') . ' ', array(
		'class' => 'wpas-button wpas-juibutton-primary wpas-button-large  col-md-12 col-lg-12 col-xs-12 col-sm-12'
	));
	return $form;
}
/**
 * Process each form and show error or success
 *
 * @since WPAS 4.0
 *
 * @return html
 */
function software_issue_manager_process_issue_entry($atts) {
	$show_form = 1;
	$access_views = get_option('software_issue_manager_access_views', Array());
	if (!current_user_can('view_issue_entry') && !empty($access_views['forms']) && in_array('issue_entry', $access_views['forms'])) {
		$show_form = 0;
	}
	$form_init_variables = get_option('software_issue_manager_glob_forms_init_list');
	$form_variables = get_option('software_issue_manager_glob_forms_list');
	if ($show_form == 1) {
		if (!empty($form_init_variables['issue_entry']['login_reg'])) {
			$show_login_register = (isset($form_variables['issue_entry']['login_reg']) ? $form_variables['issue_entry']['login_reg'] : $form_init_variables['issue_entry']['login_reg']);
			if (!is_user_logged_in() && $show_login_register != 'none') {
				do_action('emd_show_login_register_forms', 'software_issue_manager', 'issue_entry', $show_login_register);
				return;
			}
		}
		wp_enqueue_script('wpas-jvalidate-js');
		wp_enqueue_style('wpasui');
		wp_enqueue_style('jq-css');
		wp_enqueue_script('wpas-filepicker-js');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style('issue-entry-forms');
		wp_enqueue_script('issue-entry-forms-js');
		software_issue_manager_enq_custom_css();
		do_action('emd_ext_form_enq', 'software_issue_manager', 'issue_entry');
		$success_msg = (isset($form_variables['issue_entry']['success_msg']) ? $form_variables['issue_entry']['success_msg'] : $form_init_variables['issue_entry']['success_msg']);
		$error_msg = (isset($form_variables['issue_entry']['error_msg']) ? $form_variables['issue_entry']['error_msg'] : $form_init_variables['issue_entry']['error_msg']);
		return emd_submit_php_form('issue_entry', 'software_issue_manager', 'emd_issue', 'publish', 'draft', $success_msg, $error_msg, 0, 1, $atts);
	} else {
		$noaccess_msg = (isset($form_variables['issue_entry']['noaccess_msg']) ? $form_variables['issue_entry']['noaccess_msg'] : $form_init_variables['issue_entry']['noaccess_msg']);
		return "<div class='alert alert-info not-authorized'>" . $noaccess_msg . "</div>";
	}
}
/**
 * Set each form field(attr,tax and rels) and render form
 *
 * @since WPAS 4.0
 *
 * @return object $form
 */
function software_issue_manager_set_issue_search($atts) {
	global $file_upload_dir;
	$show_captcha = 0;
	$form_variables = get_option('software_issue_manager_glob_forms_list');
	$form_init_variables = get_option('software_issue_manager_glob_forms_init_list');
	if (!empty($atts['set'])) {
		$set_arrs = emd_parse_set_filter($atts['set']);
	}
	if (!empty($form_variables['issue_search']['captcha'])) {
		switch ($form_variables['issue_search']['captcha']) {
			case 'never-show':
				$show_captcha = 0;
			break;
			case 'show-always':
				$show_captcha = 1;
			break;
			case 'show-to-visitors':
				if (is_user_logged_in()) {
					$show_captcha = 0;
				} else {
					$show_captcha = 1;
				}
			break;
		}
	}
	$req_hide_vars = emd_get_form_req_hide_vars('software_issue_manager', 'issue_search');
	$form = new Zebra_Form('issue_search', 0, 'POST', '', array(
		'class' => 'form-container wpas-form wpas-form-stacked',
		'session_obj' => SOFTWARE_ISSUE_MANAGER()->session
	));
	$csrf_storage_method = (isset($form_variables['issue_search']['csrf']) ? $form_variables['issue_search']['csrf'] : $form_init_variables['issue_search']['csrf']);
	if ($csrf_storage_method == 0) {
		$form->form_properties['csrf_storage_method'] = false;
	}
	if (!in_array('emd_iss_id', $req_hide_vars['hide'])) {
		//text
		$form->add('label', 'label_emd_iss_id', 'emd_iss_id', __('ID', 'software-issue-manager') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'class' => 'input-md form-control',
			'placeholder' => __('ID', 'software-issue-manager')
		);
		if (!empty($_GET['emd_iss_id'])) {
			$attrs['value'] = sanitize_text_field($_GET['emd_iss_id']);
		} elseif (!empty($set_arrs['attr']['emd_iss_id'])) {
			$attrs['value'] = $set_arrs['attr']['emd_iss_id'];
		}
		$obj = $form->add('text', 'emd_iss_id', '', $attrs);
		$zrule = Array(
			'dependencies' => array() ,
		);
		if (in_array('emd_iss_id', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('ID is required', 'software-issue-manager')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('emd_iss_due_date', $req_hide_vars['hide'])) {
		//date
		$form->add('label', 'label_emd_iss_due_date', 'emd_iss_due_date', __('Due Date', 'software-issue-manager') , array(
			'class' => 'control-label'
		));
		$obj = $form->add('date', 'emd_iss_due_date', '', array(
			'class' => 'input-md form-control',
			'placeholder' => __('Due Date', 'software-issue-manager')
		));
		$obj->format('m-d-Y');
		$zrule = Array(
			'dependencies' => array() ,
			'date' => array(
				'error',
				__('Due Date: Please enter a valid date format', 'software-issue-manager')
			) ,
		);
		if (in_array('emd_iss_due_date', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Due Date is required', 'software-issue-manager')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('issue_cat', $req_hide_vars['hide'])) {
		$form->add('label', 'label_issue_cat', 'issue_cat', __('Category', 'software-issue-manager') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'multiple' => 'multiple',
			'class' => 'input-md'
		);
		if (!empty($_GET['issue_cat'])) {
			$attrs['value'] = sanitize_text_field($_GET['issue_cat']);
		} elseif (!empty($set_arrs['tax']['issue_cat'])) {
			$attrs['value'] = $set_arrs['tax']['issue_cat'];
		}
		$obj = $form->add('selectadv', 'issue_cat[]', '', $attrs, '', '{"allowClear":true,"placeholder":"' . __("Please Select", "software-issue-manager") . '","placeholderOption":"first"}');
		//get taxonomy values
		$txn_arr = Array();
		$txn_obj = get_terms('issue_cat', array(
			'hide_empty' => 0
		));
		foreach ($txn_obj as $txn) {
			$txn_arr[$txn->slug] = $txn->name;
		}
		$obj->add_options($txn_arr);
		$zrule = Array(
			'dependencies' => array() ,
		);
		if (in_array('issue_cat', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Category is required!', 'software-issue-manager')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('issue_priority', $req_hide_vars['hide'])) {
		$form->add('label', 'label_issue_priority', 'issue_priority', __('Priority', 'software-issue-manager') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'multiple' => 'multiple',
			'class' => 'input-md'
		);
		if (!empty($_GET['issue_priority'])) {
			$attrs['value'] = sanitize_text_field($_GET['issue_priority']);
		} elseif (!empty($set_arrs['tax']['issue_priority'])) {
			$attrs['value'] = $set_arrs['tax']['issue_priority'];
		}
		$obj = $form->add('selectadv', 'issue_priority[]', '', $attrs, '', '{"allowClear":true,"placeholder":"' . __("Please Select", "software-issue-manager") . '","placeholderOption":"first"}');
		//get taxonomy values
		$txn_arr = Array();
		$txn_obj = get_terms('issue_priority', array(
			'hide_empty' => 0
		));
		foreach ($txn_obj as $txn) {
			$txn_arr[$txn->slug] = $txn->name;
		}
		$obj->add_options($txn_arr);
		$zrule = Array(
			'dependencies' => array() ,
		);
		if (in_array('issue_priority', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Priority is required!', 'software-issue-manager')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('issue_status', $req_hide_vars['hide'])) {
		$form->add('label', 'label_issue_status', 'issue_status', __('Status', 'software-issue-manager') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'multiple' => 'multiple',
			'class' => 'input-md'
		);
		if (!empty($_GET['issue_status'])) {
			$attrs['value'] = sanitize_text_field($_GET['issue_status']);
		} elseif (!empty($set_arrs['tax']['issue_status'])) {
			$attrs['value'] = $set_arrs['tax']['issue_status'];
		}
		$obj = $form->add('selectadv', 'issue_status[]', '', $attrs, '', '{"allowClear":true,"placeholder":"' . __("Please Select", "software-issue-manager") . '","placeholderOption":"first"}');
		//get taxonomy values
		$txn_arr = Array();
		$txn_obj = get_terms('issue_status', array(
			'hide_empty' => 0
		));
		foreach ($txn_obj as $txn) {
			$txn_arr[$txn->slug] = $txn->name;
		}
		$obj->add_options($txn_arr);
		$zrule = Array(
			'dependencies' => array() ,
		);
		if (in_array('issue_status', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Status is required!', 'software-issue-manager')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	if (!in_array('rel_project_issues', $req_hide_vars['hide'])) {
		$form->add('label', 'label_rel_project_issues', 'rel_project_issues', __('Affected Projects', 'software-issue-manager') , array(
			'class' => 'control-label'
		));
		$attrs = array(
			'multiple' => 'multiple',
			'class' => 'input-md'
		);
		if (!empty($_GET['rel_project_issues'])) {
			$attrs['value'] = sanitize_text_field($_GET['rel_project_issues']);
		} elseif (!empty($set_arrs['rel']['project_issues'])) {
			$attrs['value'] = $set_arrs['rel']['project_issues'];
		}
		$obj = $form->add('selectadv', 'rel_project_issues[]', __('Please select', 'software-issue-manager') , $attrs, '', '{"allowClear":true,"placeholder":"' . __("Please Select", "software-issue-manager") . '"}');
		//get entity values
		$rel_ent_arr = Array();
		$rel_ent_args = Array(
			'post_type' => 'emd_project',
			'numberposts' => - 1,
			'orderby' => 'title',
			'order' => 'ASC'
		);
		$front_ents = emd_find_limitby('frontend', 'software_issue_manager');
		if (!empty($front_ents) && in_array('emd_project', $front_ents)) {
			$pids = emd_get_form_pids('software_issue_manager', 'emd_project');
			$rel_ent_args['post__in'] = $pids;
		}
		$rel_ent_pids = get_posts($rel_ent_args);
		if (!empty($rel_ent_pids)) {
			foreach ($rel_ent_pids as $my_ent_pid) {
				$rel_ent_arr[$my_ent_pid->ID] = get_the_title($my_ent_pid->ID);
			}
		}
		$obj->add_options($rel_ent_arr);
		$zrule = Array(
			'dependencies' => array() ,
		);
		if (in_array('rel_project_issues', $req_hide_vars['req'])) {
			$zrule = array_merge($zrule, Array(
				'required' => array(
					'error',
					__('Affected Projects is required!', 'software-issue-manager')
				)
			));
		}
		$obj->set_rule($zrule);
	}
	$ext_inputs = Array();
	$ext_inputs = apply_filters('emd_ext_form_inputs', $ext_inputs, 'software_issue_manager', 'issue_search');
	foreach ($ext_inputs as $input_param) {
		$inp_name = $input_param['name'];
		if (!in_array($input_param['name'], $req_hide_vars['hide'])) {
			if ($input_param['type'] == 'hidden') {
				$obj = $form->add('hidden', $input_param['name'], $input_param['vals']);
			} elseif ($input_param['type'] == 'select') {
				$form->add('label', 'label_' . $input_param['name'], $input_param['name'], $input_param['label'], array(
					'class' => 'control-label'
				));
				$ext_class['class'] = 'input-md';
				if (!empty($input_param['multiple'])) {
					$ext_class['multiple'] = 'multiple';
					$input_param['name'] = $input_param['name'] . '[]';
				}
				$obj = $form->add('select', $input_param['name'], '', $ext_class, '', '{"allowClear":true,"placeholder":"' . __("Please Select", "software-issue-manager") . '","placeholderOption":"first"}');
				$obj->add_options($input_param['vals']);
				$obj->disable_spam_filter();
			} elseif ($input_param['type'] == 'text') {
				$form->add('label', 'label_' . $input_param['name'], $input_param['name'], $input_param['label'], array(
					'class' => 'control-label'
				));
				$obj = $form->add('text', $input_param['name'], '', array(
					'class' => 'input-md form-control',
					'placeholder' => $input_param['label']
				));
			}
			if ($input_param['type'] != 'hidden' && in_array($inp_name, $req_hide_vars['req'])) {
				$zrule = Array(
					'dependencies' => $input_param['dependencies'],
					'required' => array(
						'error',
						$input_param['label'] . __(' is required', 'software-issue-manager')
					)
				);
				$obj->set_rule($zrule);
			}
		}
	}
	$cust_fields = Array();
	$cust_fields = apply_filters('emd_get_cust_fields', $cust_fields, 'emd_issue');
	foreach ($cust_fields as $ckey => $clabel) {
		if (!in_array($ckey, $req_hide_vars['hide'])) {
			$form->add('label', 'label_' . $ckey, $ckey, $clabel, array(
				'class' => 'control-label'
			));
			$obj = $form->add('text', $ckey, '', array(
				'class' => 'input-md form-control',
				'placeholder' => $clabel
			));
			if (in_array($ckey, $req_hide_vars['req'])) {
				$zrule = Array(
					'required' => array(
						'error',
						$clabel . __(' is required', 'software-issue-manager')
					)
				);
				$obj->set_rule($zrule);
			}
		}
	}
	$form->assign('show_captcha', $show_captcha);
	if ($show_captcha == 1) {
		//Captcha
		$form->add('captcha', 'captcha_image', 'captcha_code', '', '<span style="font-weight:bold;" class="refresh-txt">Refresh</span>', 'refcapt');
		$form->add('label', 'label_captcha_code', 'captcha_code', __('Please enter the characters with black color.', 'software-issue-manager'));
		$obj = $form->add('text', 'captcha_code', '', array(
			'placeholder' => __('Code', 'software-issue-manager')
		));
		$obj->set_rule(array(
			'required' => array(
				'error',
				__('Captcha is required', 'software-issue-manager')
			) ,
			'captcha' => array(
				'error',
				__('Characters from captcha image entered incorrectly!', 'software-issue-manager')
			)
		));
	}
	$form->add('submit', 'singlebutton_issue_search', '' . __('Search Issues', 'software-issue-manager') . ' ', array(
		'class' => 'wpas-button wpas-juibutton-secondary wpas-button-large  col-md-12 col-lg-12 col-xs-12 col-sm-12'
	));
	return $form;
}
/**
 * Process each form and show error or success
 *
 * @since WPAS 4.0
 *
 * @return html
 */
function software_issue_manager_process_issue_search($atts) {
	$show_form = 1;
	$access_views = get_option('software_issue_manager_access_views', Array());
	if (!current_user_can('view_issue_search') && !empty($access_views['forms']) && in_array('issue_search', $access_views['forms'])) {
		$show_form = 0;
	}
	$form_init_variables = get_option('software_issue_manager_glob_forms_init_list');
	$form_variables = get_option('software_issue_manager_glob_forms_list');
	if ($show_form == 1) {
		if (!empty($form_init_variables['issue_search']['login_reg'])) {
			$show_login_register = (isset($form_variables['issue_search']['login_reg']) ? $form_variables['issue_search']['login_reg'] : $form_init_variables['issue_search']['login_reg']);
			if (!is_user_logged_in() && $show_login_register != 'none') {
				do_action('emd_show_login_register_forms', 'software_issue_manager', 'issue_search', $show_login_register);
				return;
			}
		}
		wp_enqueue_script('wpas-jvalidate-js');
		wp_enqueue_style('wpasui');
		wp_enqueue_style('jq-css');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style('issue-search-forms');
		wp_enqueue_script('issue-search-forms-js');
		wp_enqueue_style('view-sc-issues-cdn');
		wp_enqueue_style('software-issue-manager-allview-css');
		software_issue_manager_enq_custom_css();
		do_action('emd_ext_form_enq', 'software_issue_manager', 'issue_search');
		$noresult_msg = (isset($form_variables['issue_search']['noresult_msg']) ? $form_variables['issue_search']['noresult_msg'] : $form_init_variables['issue_search']['noresult_msg']);
		return emd_search_php_form('issue_search', 'software_issue_manager', 'emd_issue', $noresult_msg, 'sc_issues', $atts);
	} else {
		$noaccess_msg = (isset($form_variables['issue_search']['noaccess_msg']) ? $form_variables['issue_search']['noaccess_msg'] : $form_init_variables['issue_search']['noaccess_msg']);
		return "<div class='alert alert-info not-authorized'>" . $noaccess_msg . "</div>";
	}
}
