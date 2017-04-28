<?php
/**
 * Plugin Page Feedback Functions
 *
 * @package SOFTWARE_ISSUE_MANAGER
 * @since WPAS 5.3
 */
if (!defined('ABSPATH')) exit;
add_filter('plugin_row_meta', 'software_issue_manager_plugin_row_meta', 10, 2);
add_filter('plugin_action_links', 'software_issue_manager_plugin_action_links', 10, 2);
add_action('wp_ajax_software_issue_manager_send_deactivate_reason', 'software_issue_manager_send_deactivate_reason');
global $pagenow;
if ('plugins.php' === $pagenow) {
	add_action('admin_footer', 'software_issue_manager_deactivation_feedback_box');
}
add_action('wp_ajax_software_issue_manager_show_rateme', 'software_issue_manager_show_rateme_action');
//check min entity count if its not -1 then show notice
$min_trigger = get_option('software_issue_manager_show_rateme_plugin_min', 5);
if ($min_trigger != - 1) {
	add_action('admin_notices', 'software_issue_manager_show_rateme_notice');
}
function software_issue_manager_show_rateme_action() {
	if (!wp_verify_nonce($_POST['rateme_nonce'], 'software_issue_manager_rateme_nonce')) {
		exit;
	}
	$min_trigger = get_option('software_issue_manager_show_rateme_plugin_min', 5);
	if ($min_trigger == - 1) {
		die;
	}
	if (5 === $min_trigger) {
		$min_trigger = 10;
	} else {
		$min_trigger = - 1;
	}
	update_option('software_issue_manager_show_rateme_plugin_min', $min_trigger);
	echo 1;
	die;
}
function software_issue_manager_show_rateme_notice() {
	if (!current_user_can('manage_options')) {
		return;
	}
	$min_count = 0;
	$ent_list = get_option('software_issue_manager_ent_list');
	$min_trigger = get_option('software_issue_manager_show_rateme_plugin_min', 5);
	$triggerdate = get_option('software_issue_manager_activation_date', false);
	$installed_date = (!empty($triggerdate) ? $triggerdate : '999999999999999');
	$today = mktime(0, 0, 0, date('m') , date('d') , date('Y'));
	$label = $ent_list['emd_issue']['label'];
	$count_posts = wp_count_posts('emd_issue');
	if ($count_posts->publish > $min_trigger) {
		$min_count = $count_posts->publish;
	}
	if ($min_count > 5 || ($min_trigger == 5 && $installed_date <= $today)) {
		$message_start = '<div class="emd-show-rateme update-nag success">
                        <span class=""><b>Software Issue Manager</b></span>
                        <div>';
		if ($min_count > 5) {
			$message_start.= sprintf(__("Hi, I noticed you just crossed the %d %s milestone on Software Issue Manager - that's awesome!", "software-issue-manager") , $min_trigger, $label);
		} elseif ($installed_date <= $today) {
			$message_start.= __("Hi, I just noticed you have been using Software Issue Manager for about a week now - that's awesome!", "software-issue-manager");
		}
		$message_level1 = __('Could you please do me a <span style="color:red" class="dashicons dashicons-heart"></span> BIG favor <span style="color:red" class="dashicons dashicons-heart"></span> and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.', "software-issue-manager");
		$message_level2 = sprintf(__("Would you like to upgrade now to get more out of your %s?", "software-issue-manager") , $label);
		$message_end = '<br/><br/>
                        <strong>Safiye Duman</strong><br>eMarket Design Cofounder<br><a data-rate-action="twitter" style="text-decoration:none" href="https://twitter.com/safiye_emd" target="_blank"><span class="dashicons dashicons-twitter"></span>@safiye_emd</a>
                        </div>
                        <div style="background-color: #f0f8ff;padding: 0 0 10px 10px;width: 300px;border: 1px solid;border-radius: 10px;margin: 14px 0;"><br><strong>Thank you</strong> <span class="dashicons dashicons-smiley"></span>
                        <ul data-nonce="' . wp_create_nonce('software_issue_manager_rateme_nonce') . '">';
		$message_end1 = '<li><a data-rate-action="do-rate" data-plugin="software_issue_manager" href="https://wordpress.org/support/plugin/software-issue-manager/reviews/#postform">' . __('Ok, you deserve it', 'software-issue-manager') . '</a>
       </li>
        <li><a data-rate-action="done-rating" data-plugin="software_issue_manager" href="#">' . __('I already did', 'software-issue-manager') . '</a></li>
        <li><a data-rate-action="not-enough" data-plugin="software_issue_manager" href="#">' . __('Maybe later', 'software-issue-manager') . '</a></li>';
		$message_end2 = '<li><a data-rate-action="upgrade-now" data-plugin="software_issue_manager" href="https://emdplugins.com/plugin_tag/sim-com">' . __('I want to upgrade', 'software-issue-manager') . '</a>
       </li>
        <li><a data-rate-action="not-enough" data-plugin="software_issue_manager" href="#">' . __('Maybe later', 'software-issue-manager') . '</a></li>';
	}
	if ($min_count > 10 && $min_trigger == 10) {
		echo $message_start . '<br>' . $message_level2 . ' ' . $message_end . ' ' . $message_end2 . '</ul></div></div>';
	} elseif ($min_count > 5 || ($min_trigger == 5 && $installed_date <= $today)) {
		echo $message_start . '<br>' . $message_level1 . ' ' . $message_end . ' ' . $message_end1 . '</ul></div></div>';
	}
}
/**
 * Adds links under plugin description
 *
 * @since WPAS 5.3
 * @param array $input
 * @param string $file
 * @return array $input
 */
function software_issue_manager_plugin_row_meta($input, $file) {
	if ($file != 'software-issue-manager/software-issue-manager.php') return $input;
	$links = array(
		'<a href="https://docs.emdplugins.com/docs/software-issue-manager-community-documentation/">' . __('Docs', 'software-issue-manager') . '</a>',
		'<a href="https://emdplugins.com/plugin_tag/sim-com">' . __('Pro Version', 'software-issue-manager') . '</a>'
	);
	$input = array_merge($input, $links);
	return $input;
}
/**
 * Adds links under plugin description
 *
 * @since WPAS 5.3
 * @param array $input
 * @param string $file
 * @return array $input
 */
function software_issue_manager_plugin_action_links($links, $file) {
	if ($file != 'software-issue-manager/software-issue-manager.php') return $links;
	foreach ($links as $key => $link) {
		if ('deactivate' === $key) {
			$links[$key] = $link . '<i class="software_issue_manager-deactivate-slug" data-slug="software_issue_manager-deactivate-slug"></i>';
		}
	}
	$new_links['settings'] = '<a href="' . admin_url('admin.php?page=software_issue_manager_settings') . '">' . __('Settings', 'software-issue-manager') . '</a>';
	$links = array_merge($new_links, $links);
	return $links;
}
function software_issue_manager_deactivation_feedback_box() {
	wp_enqueue_style("emd-plugin-modal", SOFTWARE_ISSUE_MANAGER_PLUGIN_URL . 'assets/css/emd-plugin-modal.css');
	$feedback_vars['submit'] = __('Submit & Deactivate', 'software-issue-manager');
	$feedback_vars['skip'] = __('Skip & Deactivate', 'software-issue-manager');
	$feedback_vars['cancel'] = __('Cancel', 'software-issue-manager');
	$feedback_vars['ask_reason'] = __('Kindly tell us the reason so we can improve', 'software-issue-manager');
	$feedback_vars['nonce'] = wp_create_nonce('software_issue_manager_deactivate_nonce');
	$reasons[1] = __('I no longer need the plugin', 'software-issue-manager');
	$reasons[2] = __('I found a better plugin', 'software-issue-manager');
	$reasons[8] = __('I haven\'t found a feature that I need', 'software-issue-manager');
	$reasons[3] = __('I only needed the plugin for a short period', 'software-issue-manager');
	$reasons[4] = __('The plugin broke my site', 'software-issue-manager');
	$reasons[5] = __('The plugin suddenly stopped working', 'software-issue-manager');
	$reasons[6] = __('It\'s a temporary deactivation. I\'m just debugging an issue', 'software-issue-manager');
	$reasons[7] = __('Other', 'software-issue-manager');
	$feedback_vars['msg'] = __('If you have a moment, please let us know why you are deactivating', 'software-issue-manager');
	$feedback_vars['disclaimer'] = __('No private information is sent during your submission. Thank you very much for your help improving our plugin.', 'software-issue-manager');
	$feedback_vars['reasons'] = '';
	foreach ($reasons as $key => $reason) {
		$feedback_vars['reasons'].= '<li class="reason';
		if ($key == 2 || $key == 7 || $key == 8) {
			$feedback_vars['reasons'].= ' has-input';
		}
		$feedback_vars['reasons'].= '"';
		if ($key == 2 || $key == 7 || $key == 8) {
			$feedback_vars['reasons'].= 'data-input-type="textfield"';
			if ($key == 2) {
				$feedback_vars['reasons'].= 'data-input-placeholder="' . __('What\'s the plugin\'s name?', 'software-issue-manager') . '"';
			} elseif ($key == 8) {
				$feedback_vars['reasons'].= 'data-input-placeholder="' . __('What feature do you need?', 'software-issue-manager') . '"';
			}
		}
		$feedback_vars['reasons'].= '><label><span>
                                        <input type="radio" name="selected-reason" value="' . $key . '"/>
                                        </span><span>' . $reason . '</span></label></li>';
	}
	wp_enqueue_script('emd-plugin-feedback', SOFTWARE_ISSUE_MANAGER_PLUGIN_URL . 'assets/js/emd-plugin-feedback.js');
	wp_localize_script("emd-plugin-feedback", 'plugin_feedback_vars', $feedback_vars);
	wp_enqueue_script('software-issue-manager-feedback', SOFTWARE_ISSUE_MANAGER_PLUGIN_URL . 'assets/js/software-issue-manager-feedback.js');
	$software_issue_manager_vars['plugin'] = 'software_issue_manager';
	wp_localize_script("software-issue-manager-feedback", 'software_issue_manager_vars', $software_issue_manager_vars);
}
function software_issue_manager_send_deactivate_reason() {
	if (empty($_POST['deactivate_nonce']) || !isset($_POST['reason_id'])) {
		exit;
	}
	if (!wp_verify_nonce($_POST['deactivate_nonce'], 'software_issue_manager_deactivate_nonce')) {
		exit;
	}
	$reason_info = isset($_POST['reason_info']) ? sanitize_text_field($_POST['reason_info']) : '';
	$postfields['reason_id'] = intval($_POST['reason_id']);
	$postfields['plugin_name'] = sanitize_text_field($_POST['plugin_name']);
	if (!empty($reason_info)) {
		$postfields['reason_info'] = $reason_info;
	}
	$args = array(
		'body' => $postfields,
		'sslverify' => false,
		'timeout' => 15,
	);
	$resp = wp_remote_post('https://api.emarketdesign.com/deactivate_info.php', $args);
	echo 1;
	exit;
}
