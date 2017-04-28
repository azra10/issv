<?php
/**
 * Enqueue Scripts Functions
 *
 * @package SOFTWARE_ISSUE_MANAGER
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
add_action('admin_enqueue_scripts', 'software_issue_manager_load_admin_enq');
/**
 * Enqueue style and js for each admin entity pages and settings
 *
 * @since WPAS 4.0
 * @param string $hook
 *
 */
function software_issue_manager_load_admin_enq($hook) {
	global $typenow;
	$dir_url = SOFTWARE_ISSUE_MANAGER_PLUGIN_URL;
	do_action('emd_ext_admin_enq', 'software_issue_manager', $hook);
	$min_trigger = get_option('software_issue_manager_show_rateme_plugin_min', 0);
	if (-1 !== $min_trigger) {
		wp_enqueue_style('emd-plugin-rateme-css', $dir_url . 'assets/css/emd-plugin-rateme.css');
		wp_enqueue_script('emd-plugin-rateme-js', $dir_url . 'assets/js/emd-plugin-rateme.js');
	}
	if ($hook == 'edit-tags.php') {
		return;
	}
	if (isset($_GET['page']) && in_array($_GET['page'], Array(
		'software_issue_manager',
		'software_issue_manager_notify',
		'software_issue_manager_settings'
	))) {
		wp_enqueue_script('accordion');
		wp_enqueue_style('codemirror-css', $dir_url . 'assets/ext/codemirror/codemirror.css');
		wp_enqueue_script('codemirror-js', $dir_url . 'assets/ext/codemirror/codemirror.js', array() , '', true);
		wp_enqueue_script('codemirror-css-js', $dir_url . 'assets/ext/codemirror/css.js', array() , '', true);
		return;
	} else if (isset($_GET['page']) && in_array($_GET['page'], Array(
		'software_issue_manager_store',
		'software_issue_manager_designs',
		'software_issue_manager_support'
	))) {
		wp_enqueue_style('admin-tabs', $dir_url . 'assets/css/admin-store.css');
		return;
	}
	if (in_array($typenow, Array(
		'emd_project',
		'emd_issue'
	))) {
		$theme_changer_enq = 1;
		$sing_enq = 0;
		$tab_enq = 0;
		if ($hook == 'post.php' || $hook == 'post-new.php') {
			$unique_vars['msg'] = __('Please enter a unique value.', 'software-issue-manager');
			$unique_vars['reqtxt'] = __('required', 'software-issue-manager');
			$unique_vars['app_name'] = 'software_issue_manager';
			$ent_list = get_option('software_issue_manager_ent_list');
			if (!empty($ent_list[$typenow])) {
				$unique_vars['keys'] = $ent_list[$typenow]['unique_keys'];
				if (!empty($ent_list[$typenow]['req_blt'])) {
					$unique_vars['req_blt_tax'] = $ent_list[$typenow]['req_blt'];
				}
			}
			$tax_list = get_option('software_issue_manager_tax_list');
			if (!empty($tax_list[$typenow])) {
				foreach ($tax_list[$typenow] as $txn_name => $txn_val) {
					if ($txn_val['required'] == 1) {
						$unique_vars['req_blt_tax'][$txn_name] = Array(
							'hier' => $txn_val['hier'],
							'type' => $txn_val['type'],
							'label' => $txn_val['label'] . ' ' . __('Taxonomy', 'software-issue-manager')
						);
					}
				}
			}
			wp_enqueue_script('unique_validate-js', $dir_url . 'assets/js/unique_validate.js', array(
				'jquery',
				'jquery-validate'
			) , SOFTWARE_ISSUE_MANAGER_VERSION, true);
			wp_localize_script("unique_validate-js", 'unique_vars', $unique_vars);
		} elseif ($hook == 'edit.php') {
			wp_enqueue_style('software-issue-manager-allview-css', SOFTWARE_ISSUE_MANAGER_PLUGIN_URL . '/assets/css/allview.css');
		}
		switch ($typenow) {
			case 'emd_project':
				$tab_enq = 1;
				$sing_enq = 1;
			break;
			case 'emd_issue':
				$tab_enq = 1;
				$sing_enq = 1;
			break;
		}
		if ($sing_enq == 1) {
			wp_enqueue_script('radiotax', SOFTWARE_ISSUE_MANAGER_PLUGIN_URL . 'includes/admin/singletax/singletax.js', array(
				'jquery'
			) , SOFTWARE_ISSUE_MANAGER_VERSION, true);
		}
		if ($tab_enq == 1) {
			wp_enqueue_style('jq-css', SOFTWARE_ISSUE_MANAGER_PLUGIN_URL . 'assets/css/smoothness-jquery-ui.css');
		}
	}
}
add_action('wp_enqueue_scripts', 'software_issue_manager_frontend_scripts');
/**
 * Enqueue style and js for each frontend entity pages and components
 *
 * @since WPAS 4.0
 *
 */
function software_issue_manager_frontend_scripts() {
	$dir_url = SOFTWARE_ISSUE_MANAGER_PLUGIN_URL;
	wp_register_style('software-issue-manager-allview-css', $dir_url . '/assets/css/allview.css');
	$grid_vars = Array();
	$local_vars['ajax_url'] = admin_url('admin-ajax.php');
	$local_vars['validate_msg']['required'] = __('This field is required.', 'emd-plugins');
	$local_vars['validate_msg']['remote'] = __('Please fix this field.', 'emd-plugins');
	$local_vars['validate_msg']['email'] = __('Please enter a valid email address.', 'emd-plugins');
	$local_vars['validate_msg']['url'] = __('Please enter a valid URL.', 'emd-plugins');
	$local_vars['validate_msg']['date'] = __('Please enter a valid date.', 'emd-plugins');
	$local_vars['validate_msg']['dateISO'] = __('Please enter a valid date ( ISO )', 'emd-plugins');
	$local_vars['validate_msg']['number'] = __('Please enter a valid number.', 'emd-plugins');
	$local_vars['validate_msg']['digits'] = __('Please enter only digits.', 'emd-plugins');
	$local_vars['validate_msg']['creditcard'] = __('Please enter a valid credit card number.', 'emd-plugins');
	$local_vars['validate_msg']['equalTo'] = __('Please enter the same value again.', 'emd-plugins');
	$local_vars['validate_msg']['maxlength'] = __('Please enter no more than {0} characters.', 'emd-plugins');
	$local_vars['validate_msg']['minlength'] = __('Please enter at least {0} characters.', 'emd-plugins');
	$local_vars['validate_msg']['rangelength'] = __('Please enter a value between {0} and {1} characters long.', 'emd-plugins');
	$local_vars['validate_msg']['range'] = __('Please enter a value between {0} and {1}.', 'emd-plugins');
	$local_vars['validate_msg']['max'] = __('Please enter a value less than or equal to {0}.', 'emd-plugins');
	$local_vars['validate_msg']['min'] = __('Please enter a value greater than or equal to {0}.', 'emd-plugins');
	$local_vars['unique_msg'] = __('Please enter a unique value.', 'emd-plugins');
	$wpas_shc_list = get_option('software_issue_manager_shc_list');
	$local_vars['issue_entry'] = emd_get_form_req_hide_vars('software_issue_manager', 'issue_entry');
	wp_register_style('issue-entry-forms', $dir_url . 'assets/css/issue-entry-forms.css');
	wp_register_script('issue-entry-forms-js', $dir_url . 'assets/js/issue-entry-forms.js');
	wp_localize_script('issue-entry-forms-js', 'issue_entry_vars', $local_vars);
	$local_vars['issue_search'] = emd_get_form_req_hide_vars('software_issue_manager', 'issue_search');
	wp_register_style('issue-search-forms', $dir_url . 'assets/css/issue-search-forms.css');
	wp_register_script('issue-search-forms-js', $dir_url . 'assets/js/issue-search-forms.js');
	wp_localize_script('issue-search-forms-js', 'issue_search_vars', $local_vars);
	wp_register_style('view-sc-issues-cdn', $dir_url . 'assets/css/view-sc-issues.css');
	wp_register_style('wpas-boot', $dir_url . 'assets/ext/wpas/wpas-bootstrap.min.css');
	wp_register_script('wpas-boot-js', $dir_url . 'assets/ext/wpas/bootstrap.min.js', array(
		'jquery'
	));
	wp_register_style('wpasui', SOFTWARE_ISSUE_MANAGER_PLUGIN_URL . 'assets/ext/wpas-jui/wpas-jui.min.css');
	wp_register_style('frontgen', $dir_url . 'assets/css/frontgen.css');
	wp_register_script('wpas-jvalidate-js', $dir_url . 'assets/ext/jvalidate1150/wpas.validate.min.js', array(
		'jquery'
	));
	wp_register_style('jq-css', SOFTWARE_ISSUE_MANAGER_PLUGIN_URL . 'assets/css/smoothness-jquery-ui.css');
	if (is_single() && get_post_type() == 'emd_issue') {
		software_issue_manager_enq_bootstrap();
		wp_enqueue_style('frontgen');
		wp_enqueue_style('software-issue-manager-allview-css');
		software_issue_manager_enq_custom_css();
		return;
	}
	if (is_single() && get_post_type() == 'emd_project') {
		software_issue_manager_enq_bootstrap();
		wp_enqueue_style('frontgen');
		wp_enqueue_style('software-issue-manager-allview-css');
		software_issue_manager_enq_custom_css();
		return;
	}
}
function software_issue_manager_enq_bootstrap($type = '') {
	$misc_settings = get_option('software_issue_manager_misc_settings');
	if ($type == 'css' || $type == '') {
		if (empty($misc_settings) || (isset($misc_settings['disable_bs_css']) && $misc_settings['disable_bs_css'] == 0)) {
			wp_enqueue_style('wpas-boot');
		}
	}
	if ($type == 'js' || $type == '') {
		if (empty($misc_settings) || (isset($misc_settings['disable_bs_js']) && $misc_settings['disable_bs_js'] == 0)) {
			wp_enqueue_script('wpas-boot-js');
		}
	}
}
/**
 * Enqueue custom css if set in settings tool tab
 *
 * @since WPAS 5.3
 *
 */
function software_issue_manager_enq_custom_css() {
	$tools = get_option('software_issue_manager_tools');
	if (!empty($tools['custom_css'])) {
		$url = home_url();
		if (is_ssl()) {
			$url = home_url('/', 'https');
		}
		wp_enqueue_style('software-issue-manager-custom', add_query_arg(array(
			'software-issue-manager-css' => 1
		) , $url));
	}
}
/**
 * If app custom css query var is set, print custom css
 */
function software_issue_manager_print_css() {
	// Only print CSS if this is a stylesheet request
	if (!isset($_GET['software-issue-manager-css']) || intval($_GET['software-issue-manager-css']) !== 1) {
		return;
	}
	ob_start();
	header('Content-type: text/css');
	$tools = get_option('software_issue_manager_tools');
	$raw_content = isset($tools['custom_css']) ? $tools['custom_css'] : '';
	$content = wp_kses($raw_content, array(
		'\'',
		'\"'
	));
	$content = str_replace('&gt;', '>', $content);
	echo $content; //xss okay
	die();
}
add_action('plugins_loaded', 'software_issue_manager_print_css');
/**
 * Enqueue if allview css is not enqueued
 *
 * @since WPAS 4.5
 *
 */
function software_issue_manager_enq_allview() {
	if (!wp_style_is('software-issue-manager-allview-css', 'enqueued')) {
		wp_enqueue_style('software-issue-manager-allview-css');
	}
}
