<?php
/**
 * Entity Class
 *
 * @package SOFTWARE_ISSUE_MANAGER
 * @since WPAS 4.0
 */
if (!defined('ABSPATH')) exit;
/**
 * Emd_Issue Class
 * @since WPAS 4.0
 */
class Emd_Issue extends Emd_Entity {
	protected $post_type = 'emd_issue';
	protected $textdomain = 'software-issue-manager';
	protected $sing_label;
	protected $plural_label;
	protected $menu_entity;
	/**
	 * Initialize entity class
	 *
	 * @since WPAS 4.0
	 *
	 */
	public function __construct() {
		add_action('init', array(
			$this,
			'set_filters'
		) , 1);
		add_action('admin_init', array(
			$this,
			'set_metabox'
		));
		add_filter('wp_dropdown_users', array(
			$this,
			'author_override'
		));
		add_action('save_post', array(
			$this,
			'update_form_submitted_by'
		) , 11, 3);
		add_filter('post_updated_messages', array(
			$this,
			'updated_messages'
		));
		add_action('admin_menu', array(
			$this,
			'add_top_menu_link'
		));
		add_filter('parent_file', array(
			$this,
			'tax_submenus'
		));
		add_action('manage_emd_issue_posts_custom_column', array(
			$this,
			'custom_columns'
		) , 10, 2);
		add_filter('manage_emd_issue_posts_columns', array(
			$this,
			'column_headers'
		));
		add_filter('is_protected_meta', array(
			$this,
			'hide_attrs'
		) , 10, 2);
		add_filter('postmeta_form_keys', array(
			$this,
			'cust_keys'
		) , 10, 2);
		add_filter('emd_ext_form_var_init', array(
			$this,
			'add_cust_fields'
		) , 10, 3);
		add_filter('emd_get_cust_fields', array(
			$this,
			'get_cust_fields'
		) , 10, 2);
	}
	/**
	 * Get custom attribute list
	 * @since WPAS 4.9
	 *
	 * @param array $cust_fields
	 * @param string $post_type
	 *
	 * @return array $new_keys
	 */
	public function get_cust_fields($cust_fields, $post_type) {
		global $wpdb;
		if ($post_type == $this->post_type) {
			$sql = "SELECT DISTINCT meta_key
               FROM $wpdb->postmeta a
               WHERE a.post_id IN (SELECT id FROM $wpdb->posts b WHERE b.post_type='" . $this->post_type . "')";
			$keys = $wpdb->get_col($sql);
			if (!empty($keys)) {
				foreach ($keys as $i => $mkey) {
					if (!preg_match('/^(_|wpas_|emd_)/', $mkey)) {
						$ckey = str_replace('-', '_', sanitize_title($mkey));
						$cust_fields[$ckey] = $mkey;
					}
				}
			}
		}
		return $cust_fields;
	}
	/**
	 * Set form variables for custom attributes
	 * @since WPAS 4.9
	 *
	 * @param array $form_variables
	 * @param string $app_name
	 * @param string $form_name
	 *
	 * @return array $form_variables
	 */
	public function add_cust_fields($form_variables, $app_name, $form_name) {
		//get cust keys for this entity
		$keys = $this->get_cust_fields(Array() , $this->post_type);
		if (!empty($keys)) {
			$shc_list = get_option($app_name . '_shc_list');
			if ($form_name == '') {
				foreach ($form_variables as $fkey => $fval) {
					$ent_form = $shc_list['forms'][$fkey]['ent'];
					if ($ent_form == $this->post_type) {
						$max_row = count($fval);
						foreach ($keys as $mycust_key) {
							$ckey = str_replace("-", "_", sanitize_title($mycust_key));
							if (empty($form_variables[$fkey][$ckey])) {
								$form_variables[$fkey][$ckey] = Array(
									'show' => 0,
									'row' => $max_row + 1,
									'req' => 0,
									'size' => 12,
									'label' => $mycust_key,
								);
							}
						}
					}
				}
			} else {
				$ent_form = $shc_list['forms'][$form_name]['ent'];
				if ($ent_form == $this->post_type) {
					$max_row = count($form_variables);
					foreach ($keys as $mycust_key) {
						$ckey = str_replace("-", "_", sanitize_title($mycust_key));
						if (empty($form_variables[$ckey])) {
							$form_variables[$ckey] = Array(
								'show' => 0,
								'row' => $max_row + 1,
								'req' => 0,
								'size' => 12,
								'label' => $mycust_key,
							);
						}
					}
				}
			}
		}
		return $form_variables;
	}
	/**
	 * Set new custom attributes dropdown in admin edit entity
	 * @since WPAS 4.9
	 *
	 * @param array $keys
	 * @param object $post
	 *
	 * @return array $keys
	 */
	public function cust_keys($keys, $post) {
		global $post_type, $wpdb;
		if ($post_type == $this->post_type) {
			$sql = "SELECT DISTINCT meta_key
                FROM $wpdb->postmeta a
                WHERE a.post_id IN (SELECT id FROM $wpdb->posts b WHERE b.post_type='" . $this->post_type . "')";
			$keys = $wpdb->get_col($sql);
		}
		return $keys;
	}
	/**
	 * Hide all emd attributes
	 * @since WPAS 4.9
	 *
	 * @param bool $protected
	 * @param string $meta_key
	 *
	 * @return bool $protected
	 */
	public function hide_attrs($protected, $meta_key) {
		if (preg_match('/^(emd_|wpas_)/', $meta_key)) return true;
		foreach ($this->boxes as $mybox) {
			foreach ($mybox['fields'] as $fkey => $mybox_field) {
				if ($meta_key == $fkey) return true;
			}
		}
		return $protected;
	}
	public function update_form_submitted_by($post_id, $post, $update) {
		if ($update && $post->post_type == 'emd_issue') {
			$ulogin = "";
			if (isset($_REQUEST['post_author_override']) && $_REQUEST['post_author_override'] == 0) {
				$ulogin = 'Visitor';
				//unhook this function so it doesn't loop infinitely
				remove_action('save_post', array(
					$this,
					'update_form_submitted_by'
				) , 11, 3);
				//update the post
				wp_update_post(array(
					'ID' => $post_id,
					'post_author' => 0
				));
				//re-hook this function
				add_action('save_post', array(
					$this,
					'update_form_submitted_by'
				) , 11, 3);
			} elseif (!empty($post->post_author)) {
				$user = get_user_by('id', $post->post_author);
				$ulogin = $user->user_login;
			}
			if (!empty($_REQUEST['wpas_form_submitted_by']) && $ulogin != $_REQUEST['wpas_form_submitted_by']) {
				update_post_meta($post_id, 'wpas_form_submitted_by', $ulogin);
			}
		}
	}
	public function author_override($output) {
		global $pagenow, $post, $user_ID;
		if ('post-new.php' === $pagenow || 'post.php' === $pagenow) {
			if ((isset($_GET['post_type']) && $this->post_type === $_GET['post_type']) || (isset($_GET['post']) && get_post_type($_GET['post']) === $this->post_type)) {
				// return if this isn't the theme author override dropdown
				if (!preg_match('/post_author_override/', $output)) return $output;
				// return if we've already replaced the list (end recursion)
				if (preg_match('/post_author_override_replaced/', $output)) return $output;
				//get dropdown values all users who have edit cap for this entity
				// Get valid roles
				global $wp_roles;
				$roles = $wp_roles->role_objects;
				$valid_roles = array();
				$user_ids = array();
				if (!current_user_can('set_author_' . $this->post_type . 's')) {
					//current user
					$user_ids[] = get_current_user_id();
				} else {
					foreach ($roles as $role) {
						if (isset($role->capabilities['edit_' . $this->post_type . 's'])) {
							$valid_roles[] = $role->name;
						}
					}
					if (empty($valid_roles)) return $output;
					// Get user IDs
					foreach ($valid_roles as $role) {
						$users = get_users(array(
							'role' => $role
						));
						if (!empty($users)) {
							foreach ($users as $user) {
								$user_ids[] = $user->ID;
							}
						}
					}
				}
				if (empty($user_ids)) return $output;
				// replacement call to wp_dropdown_users
				$output = wp_dropdown_users(array(
					'echo' => 0,
					'show_option_none' => 'Visitor',
					'option_none_value' => '0',
					'name' => 'post_author_override_replaced',
					'selected' => empty($post->ID) ? $user_ID : $post->post_author,
					'include' => implode(',', $user_ids) ,
					'include_selected' => true
				));
				// put the original name back
				$output = preg_replace('/post_author_override_replaced/', 'post_author_override', $output);
			}
		}
		return $output;
	}
	/**
	 * Get column header list in admin list pages
	 * @since WPAS 4.0
	 *
	 * @param array $columns
	 *
	 * @return array $columns
	 */
	public function column_headers($columns) {
		foreach ($this->boxes as $mybox) {
			foreach ($mybox['fields'] as $fkey => $mybox_field) {
				if (!in_array($fkey, Array(
					'wpas_form_name',
					'wpas_form_submitted_by',
					'wpas_form_submitted_ip'
				)) && !in_array($mybox_field['type'], Array(
					'textarea',
					'wysiwyg'
				)) && $mybox_field['list_visible'] == 1) {
					$columns[$fkey] = $mybox_field['name'];
				}
			}
		}
		$taxonomies = get_object_taxonomies($this->post_type, 'objects');
		if (!empty($taxonomies)) {
			$tax_list = get_option(str_replace("-", "_", $this->textdomain) . '_tax_list');
			foreach ($taxonomies as $taxonomy) {
				if (!empty($tax_list[$this->post_type][$taxonomy->name]) && $tax_list[$this->post_type][$taxonomy->name]['list_visible'] == 1) {
					$columns[$taxonomy->name] = $taxonomy->label;
				}
			}
		}
		$rel_list = get_option(str_replace("-", "_", $this->textdomain) . '_rel_list');
		if (!empty($rel_list)) {
			foreach ($rel_list as $krel => $rel) {
				if ($rel['from'] == $this->post_type && in_array($rel['show'], Array(
					'any',
					'from'
				))) {
					$columns[$krel] = $rel['from_title'];
				} elseif ($rel['to'] == $this->post_type && in_array($rel['show'], Array(
					'any',
					'to'
				))) {
					$columns[$krel] = $rel['to_title'];
				}
			}
		}
		return $columns;
	}
	/**
	 * Get custom column values in admin list pages
	 * @since WPAS 4.0
	 *
	 * @param int $column_id
	 * @param int $post_id
	 *
	 * @return string $value
	 */
	public function custom_columns($column_id, $post_id) {
		if (taxonomy_exists($column_id) == true) {
			$terms = get_the_terms($post_id, $column_id);
			$ret = array();
			if (!empty($terms)) {
				foreach ($terms as $term) {
					$url = add_query_arg(array(
						'post_type' => $this->post_type,
						'term' => $term->slug,
						'taxonomy' => $column_id
					) , admin_url('edit.php'));
					$a_class = preg_replace('/^emd_/', '', $this->post_type);
					$ret[] = sprintf('<a href="%s"  class="' . $a_class . '-tax ' . $term->slug . '">%s</a>', $url, $term->name);
				}
			}
			echo implode(', ', $ret);
			return;
		}
		$rel_list = get_option(str_replace("-", "_", $this->textdomain) . '_rel_list');
		if (!empty($rel_list) && !empty($rel_list[$column_id])) {
			$rel_arr = $rel_list[$column_id];
			if ($rel_arr['from'] == $this->post_type) {
				$other_ptype = $rel_arr['to'];
			} elseif ($rel_arr['to'] == $this->post_type) {
				$other_ptype = $rel_arr['from'];
			}
			$column_id = str_replace('rel_', '', $column_id);
			if (function_exists('p2p_type') && p2p_type($column_id)) {
				$rel_args = apply_filters('emd_ext_p2p_add_query_vars', array(
					'posts_per_page' => - 1
				) , Array(
					$other_ptype
				));
				$connected = p2p_type($column_id)->get_connected($post_id, $rel_args);
				$ptype_obj = get_post_type_object($this->post_type);
				$edit_cap = $ptype_obj->cap->edit_posts;
				$ret = array();
				if (empty($connected->posts)) return '&ndash;';
				foreach ($connected->posts as $myrelpost) {
					$rel_title = get_the_title($myrelpost->ID);
					$rel_title = apply_filters('emd_ext_p2p_connect_title', $rel_title, $myrelpost, '');
					$url = get_permalink($myrelpost->ID);
					$url = apply_filters('emd_ext_connected_ptype_url', $url, $myrelpost, $edit_cap);
					$ret[] = sprintf('<a href="%s" title="%s" target="_blank">%s</a>', $url, $rel_title, $rel_title);
				}
				echo implode(', ', $ret);
				return;
			}
		}
		$value = get_post_meta($post_id, $column_id, true);
		$type = "";
		foreach ($this->boxes as $mybox) {
			foreach ($mybox['fields'] as $fkey => $mybox_field) {
				if ($fkey == $column_id) {
					$type = $mybox_field['type'];
					break;
				}
			}
		}
		switch ($type) {
			case 'plupload_image':
			case 'image':
			case 'thickbox_image':
				$image_list = emd_mb_meta($column_id, 'type=image');
				$value = "";
				if (!empty($image_list)) {
					$myimage = current($image_list);
					$value = "<img style='max-width:100%;height:auto;' src='" . $myimage['url'] . "' >";
				}
			break;
			case 'user':
			case 'user-adv':
				$user_id = emd_mb_meta($column_id);
				if (!empty($user_id)) {
					$user_info = get_userdata($user_id);
					$value = $user_info->display_name;
				}
			break;
			case 'file':
				$file_list = emd_mb_meta($column_id, 'type=file');
				if (!empty($file_list)) {
					$value = "";
					foreach ($file_list as $myfile) {
						$fsrc = wp_mime_type_icon($myfile['ID']);
						$value.= "<a href='" . $myfile['url'] . "' target='_blank'><img src='" . $fsrc . "' title='" . $myfile['name'] . "' width='20' /></a>";
					}
				}
			break;
			case 'radio':
			case 'checkbox_list':
			case 'select':
			case 'select_advanced':
				$value = emd_get_attr_val(str_replace("-", "_", $this->textdomain) , $post_id, $this->post_type, $column_id);
			break;
			case 'checkbox':
				if ($value == 1) {
					$value = '<span class="dashicons dashicons-yes"></span>';
				} elseif ($value == 0) {
					$value = '<span class="dashicons dashicons-no-alt"></span>';
				}
			break;
			case 'rating':
				$value = apply_filters('emd_get_rating_value', $value, Array(
					'meta' => $column_id
				) , $post_id);
			break;
		}
		if (is_array($value)) {
			$value = "<div class='clonelink'>" . implode("</div><div class='clonelink'>", $value) . "</div>";
		}
		echo $value;
	}
	/**
	 * Register post type and taxonomies and set initial values for taxs
	 *
	 * @since WPAS 4.0
	 *
	 */
	public static function register() {
		$labels = array(
			'name' => __('Issues', 'software-issue-manager') ,
			'singular_name' => __('Issue', 'software-issue-manager') ,
			'add_new' => __('Add New', 'software-issue-manager') ,
			'add_new_item' => __('Add New Issue', 'software-issue-manager') ,
			'edit_item' => __('Edit Issue', 'software-issue-manager') ,
			'new_item' => __('New Issue', 'software-issue-manager') ,
			'all_items' => __('All Issues', 'software-issue-manager') ,
			'view_item' => __('View Issue', 'software-issue-manager') ,
			'search_items' => __('Search Issues', 'software-issue-manager') ,
			'not_found' => __('No Issues Found', 'software-issue-manager') ,
			'not_found_in_trash' => __('No Issues Found In Trash', 'software-issue-manager') ,
			'menu_name' => __('Issues', 'software-issue-manager') ,
		);
		$ent_map_list = get_option('software_issue_manager_ent_map_list', Array());
		if (!empty($ent_map_list['emd_issue']['rewrite'])) {
			$rewrite = $ent_map_list['emd_issue']['rewrite'];
		} else {
			$rewrite = 'issues';
		}
		$supports = Array(
			'author',
			'custom-fields',
			'revisions',
			'comments'
		);
		if (empty($ent_map_list['emd_issue']['attrs']['blt_title']) || $ent_map_list['emd_issue']['attrs']['blt_title'] != 'hide') {
			$supports[] = 'title';
		}
		if (empty($ent_map_list['emd_issue']['attrs']['blt_content']) || $ent_map_list['emd_issue']['attrs']['blt_content'] != 'hide') {
			$supports[] = 'editor';
		}
		register_post_type('emd_issue', array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'description' => __('An issue is anything that might affect the project meeting its goals such as bugs, tasks, and feature requests that occur during a project\'s life cycle.', 'software-issue-manager') ,
			'show_in_menu' => '',
			'menu_position' => null,
			'has_archive' => true,
			'exclude_from_search' => false,
			'rewrite' => array(
				'slug' => $rewrite
			) ,
			'can_export' => true,
			'show_in_rest' => false,
			'hierarchical' => false,
			'map_meta_cap' => 'true',
			'taxonomies' => array() ,
			'capability_type' => 'emd_issue',
			'supports' => $supports,
		));
		$browser_nohr_labels = array(
			'name' => __('Browsers', 'software-issue-manager') ,
			'singular_name' => __('Browser', 'software-issue-manager') ,
			'search_items' => __('Search Browsers', 'software-issue-manager') ,
			'popular_items' => __('Popular Browsers', 'software-issue-manager') ,
			'all_items' => __('All', 'software-issue-manager') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Browser', 'software-issue-manager') ,
			'update_item' => __('Update Browser', 'software-issue-manager') ,
			'add_new_item' => __('Add New Browser', 'software-issue-manager') ,
			'new_item_name' => __('Add New Browser Name', 'software-issue-manager') ,
			'separate_items_with_commas' => __('Seperate Browsers with commas', 'software-issue-manager') ,
			'add_or_remove_items' => __('Add or Remove Browsers', 'software-issue-manager') ,
			'choose_from_most_used' => __('Choose from the most used Browsers', 'software-issue-manager') ,
			'menu_name' => __('Browsers', 'software-issue-manager') ,
		);
		$tax_settings = get_option('software_issue_manager_tax_settings', Array());
		if (empty($tax_settings['browser']['hide']) || (!empty($tax_settings['browser']['hide']) && $tax_settings['browser']['hide'] != 'hide')) {
			if (!empty($tax_settings['browser']['rewrite'])) {
				$rewrite = $tax_settings['browser']['rewrite'];
			} else {
				$rewrite = 'browser';
			}
			register_taxonomy('browser', array(
				'emd_issue'
			) , array(
				'hierarchical' => false,
				'labels' => $browser_nohr_labels,
				'public' => true,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'show_in_menu' => true,
				'show_tagcloud' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array(
					'slug' => $rewrite,
				) ,
				'capabilities' => array(
					'manage_terms' => 'manage_browser',
					'edit_terms' => 'edit_browser',
					'delete_terms' => 'delete_browser',
					'assign_terms' => 'assign_browser'
				) ,
			));
		}
		$issue_status_nohr_labels = array(
			'name' => __('Statuses', 'software-issue-manager') ,
			'singular_name' => __('Status', 'software-issue-manager') ,
			'search_items' => __('Search Statuses', 'software-issue-manager') ,
			'popular_items' => __('Popular Statuses', 'software-issue-manager') ,
			'all_items' => __('All', 'software-issue-manager') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Status', 'software-issue-manager') ,
			'update_item' => __('Update Status', 'software-issue-manager') ,
			'add_new_item' => __('Add New Status', 'software-issue-manager') ,
			'new_item_name' => __('Add New Status Name', 'software-issue-manager') ,
			'separate_items_with_commas' => __('Seperate Statuses with commas', 'software-issue-manager') ,
			'add_or_remove_items' => __('Add or Remove Statuses', 'software-issue-manager') ,
			'choose_from_most_used' => __('Choose from the most used Statuses', 'software-issue-manager') ,
			'menu_name' => __('Statuses', 'software-issue-manager') ,
		);
		$tax_settings = get_option('software_issue_manager_tax_settings', Array());
		if (empty($tax_settings['issue_status']['hide']) || (!empty($tax_settings['issue_status']['hide']) && $tax_settings['issue_status']['hide'] != 'hide')) {
			if (!empty($tax_settings['issue_status']['rewrite'])) {
				$rewrite = $tax_settings['issue_status']['rewrite'];
			} else {
				$rewrite = 'issue_status';
			}
			register_taxonomy('issue_status', array(
				'emd_issue'
			) , array(
				'hierarchical' => false,
				'labels' => $issue_status_nohr_labels,
				'public' => true,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'show_in_menu' => true,
				'show_tagcloud' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array(
					'slug' => $rewrite,
				) ,
				'capabilities' => array(
					'manage_terms' => 'manage_issue_status',
					'edit_terms' => 'edit_issue_status',
					'delete_terms' => 'delete_issue_status',
					'assign_terms' => 'assign_issue_status'
				) ,
			));
		}
		$issue_tag_nohr_labels = array(
			'name' => __('Tags', 'software-issue-manager') ,
			'singular_name' => __('Tag', 'software-issue-manager') ,
			'search_items' => __('Search Tags', 'software-issue-manager') ,
			'popular_items' => __('Popular Tags', 'software-issue-manager') ,
			'all_items' => __('All', 'software-issue-manager') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Tag', 'software-issue-manager') ,
			'update_item' => __('Update Tag', 'software-issue-manager') ,
			'add_new_item' => __('Add New Tag', 'software-issue-manager') ,
			'new_item_name' => __('Add New Tag Name', 'software-issue-manager') ,
			'separate_items_with_commas' => __('Seperate Tags with commas', 'software-issue-manager') ,
			'add_or_remove_items' => __('Add or Remove Tags', 'software-issue-manager') ,
			'choose_from_most_used' => __('Choose from the most used Tags', 'software-issue-manager') ,
			'menu_name' => __('Tags', 'software-issue-manager') ,
		);
		$tax_settings = get_option('software_issue_manager_tax_settings', Array());
		if (empty($tax_settings['issue_tag']['hide']) || (!empty($tax_settings['issue_tag']['hide']) && $tax_settings['issue_tag']['hide'] != 'hide')) {
			if (!empty($tax_settings['issue_tag']['rewrite'])) {
				$rewrite = $tax_settings['issue_tag']['rewrite'];
			} else {
				$rewrite = 'issue_tag';
			}
			register_taxonomy('issue_tag', array(
				'emd_issue'
			) , array(
				'hierarchical' => false,
				'labels' => $issue_tag_nohr_labels,
				'public' => true,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'show_in_menu' => true,
				'show_tagcloud' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array(
					'slug' => $rewrite,
				) ,
				'capabilities' => array(
					'manage_terms' => 'manage_issue_tag',
					'edit_terms' => 'edit_issue_tag',
					'delete_terms' => 'delete_issue_tag',
					'assign_terms' => 'assign_issue_tag'
				) ,
			));
		}
		$issue_priority_nohr_labels = array(
			'name' => __('Priorities', 'software-issue-manager') ,
			'singular_name' => __('Priority', 'software-issue-manager') ,
			'search_items' => __('Search Priorities', 'software-issue-manager') ,
			'popular_items' => __('Popular Priorities', 'software-issue-manager') ,
			'all_items' => __('All', 'software-issue-manager') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Priority', 'software-issue-manager') ,
			'update_item' => __('Update Priority', 'software-issue-manager') ,
			'add_new_item' => __('Add New Priority', 'software-issue-manager') ,
			'new_item_name' => __('Add New Priority Name', 'software-issue-manager') ,
			'separate_items_with_commas' => __('Seperate Priorities with commas', 'software-issue-manager') ,
			'add_or_remove_items' => __('Add or Remove Priorities', 'software-issue-manager') ,
			'choose_from_most_used' => __('Choose from the most used Priorities', 'software-issue-manager') ,
			'menu_name' => __('Priorities', 'software-issue-manager') ,
		);
		$tax_settings = get_option('software_issue_manager_tax_settings', Array());
		if (empty($tax_settings['issue_priority']['hide']) || (!empty($tax_settings['issue_priority']['hide']) && $tax_settings['issue_priority']['hide'] != 'hide')) {
			if (!empty($tax_settings['issue_priority']['rewrite'])) {
				$rewrite = $tax_settings['issue_priority']['rewrite'];
			} else {
				$rewrite = 'issue_priority';
			}
			register_taxonomy('issue_priority', array(
				'emd_issue'
			) , array(
				'hierarchical' => false,
				'labels' => $issue_priority_nohr_labels,
				'public' => true,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'show_in_menu' => true,
				'show_tagcloud' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array(
					'slug' => $rewrite,
				) ,
				'capabilities' => array(
					'manage_terms' => 'manage_issue_priority',
					'edit_terms' => 'edit_issue_priority',
					'delete_terms' => 'delete_issue_priority',
					'assign_terms' => 'assign_issue_priority'
				) ,
			));
		}
		$operating_system_nohr_labels = array(
			'name' => __('Operating Systems', 'software-issue-manager') ,
			'singular_name' => __('Operating System', 'software-issue-manager') ,
			'search_items' => __('Search Operating Systems', 'software-issue-manager') ,
			'popular_items' => __('Popular Operating Systems', 'software-issue-manager') ,
			'all_items' => __('All', 'software-issue-manager') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Operating System', 'software-issue-manager') ,
			'update_item' => __('Update Operating System', 'software-issue-manager') ,
			'add_new_item' => __('Add New Operating System', 'software-issue-manager') ,
			'new_item_name' => __('Add New Operating System Name', 'software-issue-manager') ,
			'separate_items_with_commas' => __('Seperate Operating Systems with commas', 'software-issue-manager') ,
			'add_or_remove_items' => __('Add or Remove Operating Systems', 'software-issue-manager') ,
			'choose_from_most_used' => __('Choose from the most used Operating Systems', 'software-issue-manager') ,
			'menu_name' => __('Operating Systems', 'software-issue-manager') ,
		);
		$tax_settings = get_option('software_issue_manager_tax_settings', Array());
		if (empty($tax_settings['operating_system']['hide']) || (!empty($tax_settings['operating_system']['hide']) && $tax_settings['operating_system']['hide'] != 'hide')) {
			if (!empty($tax_settings['operating_system']['rewrite'])) {
				$rewrite = $tax_settings['operating_system']['rewrite'];
			} else {
				$rewrite = 'operating_system';
			}
			register_taxonomy('operating_system', array(
				'emd_issue'
			) , array(
				'hierarchical' => false,
				'labels' => $operating_system_nohr_labels,
				'public' => true,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'show_in_menu' => true,
				'show_tagcloud' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array(
					'slug' => $rewrite,
				) ,
				'capabilities' => array(
					'manage_terms' => 'manage_operating_system',
					'edit_terms' => 'edit_operating_system',
					'delete_terms' => 'delete_operating_system',
					'assign_terms' => 'assign_operating_system'
				) ,
			));
		}
		$issue_cat_nohr_labels = array(
			'name' => __('Categories', 'software-issue-manager') ,
			'singular_name' => __('Category', 'software-issue-manager') ,
			'search_items' => __('Search Categories', 'software-issue-manager') ,
			'popular_items' => __('Popular Categories', 'software-issue-manager') ,
			'all_items' => __('All', 'software-issue-manager') ,
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __('Edit Category', 'software-issue-manager') ,
			'update_item' => __('Update Category', 'software-issue-manager') ,
			'add_new_item' => __('Add New Category', 'software-issue-manager') ,
			'new_item_name' => __('Add New Category Name', 'software-issue-manager') ,
			'separate_items_with_commas' => __('Seperate Categories with commas', 'software-issue-manager') ,
			'add_or_remove_items' => __('Add or Remove Categories', 'software-issue-manager') ,
			'choose_from_most_used' => __('Choose from the most used Categories', 'software-issue-manager') ,
			'menu_name' => __('Categories', 'software-issue-manager') ,
		);
		$tax_settings = get_option('software_issue_manager_tax_settings', Array());
		if (empty($tax_settings['issue_cat']['hide']) || (!empty($tax_settings['issue_cat']['hide']) && $tax_settings['issue_cat']['hide'] != 'hide')) {
			if (!empty($tax_settings['issue_cat']['rewrite'])) {
				$rewrite = $tax_settings['issue_cat']['rewrite'];
			} else {
				$rewrite = 'issue_cat';
			}
			register_taxonomy('issue_cat', array(
				'emd_issue'
			) , array(
				'hierarchical' => false,
				'labels' => $issue_cat_nohr_labels,
				'public' => true,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'show_in_menu' => true,
				'show_tagcloud' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array(
					'slug' => $rewrite,
				) ,
				'capabilities' => array(
					'manage_terms' => 'manage_issue_cat',
					'edit_terms' => 'edit_issue_cat',
					'delete_terms' => 'delete_issue_cat',
					'assign_terms' => 'assign_issue_cat'
				) ,
			));
		}
		if (!get_option('software_issue_manager_emd_issue_terms_init')) {
			$set_tax_terms = Array(
				Array(
					'name' => __('Chrome 33', 'software-issue-manager') ,
					'slug' => sanitize_title('Chrome 33')
				) ,
				Array(
					'name' => __('Internet Explorer 11', 'software-issue-manager') ,
					'slug' => sanitize_title('Internet Explorer 11')
				) ,
				Array(
					'name' => __('Safari 7.0', 'software-issue-manager') ,
					'slug' => sanitize_title('Safari 7.0')
				) ,
				Array(
					'name' => __('Opera 20', 'software-issue-manager') ,
					'slug' => sanitize_title('Opera 20')
				) ,
				Array(
					'name' => __('Firefox 29', 'software-issue-manager') ,
					'slug' => sanitize_title('Firefox 29')
				)
			);
			self::set_taxonomy_init($set_tax_terms, 'browser');
			$set_tax_terms = Array(
				Array(
					'name' => __('Open', 'software-issue-manager') ,
					'slug' => sanitize_title('Open') ,
					'desc' => __('This issue is in the initial state, ready for the assignee to start work on it.', 'software-issue-manager')
				) ,
				Array(
					'name' => __('In Progress', 'software-issue-manager') ,
					'slug' => sanitize_title('In Progress') ,
					'desc' => __('This issue is being actively worked on at the moment.', 'software-issue-manager')
				) ,
				Array(
					'name' => __('Reopened', 'software-issue-manager') ,
					'slug' => sanitize_title('Reopened') ,
					'desc' => __('This issue was once \'Resolved\' or \'Closed\', but is now being re-visited, e.g. an issue with a Resolution of \'Cannot Reproduce\' is Reopened when more information becomes available and the issue becomes reproducible. The next issue states are either marked In Progress, Resolved or Closed.', 'software-issue-manager')
				) ,
				Array(
					'name' => __('Closed', 'software-issue-manager') ,
					'slug' => sanitize_title('Closed') ,
					'desc' => __('This issue is complete.', 'software-issue-manager')
				) ,
				Array(
					'name' => __('Resolved - Fixed', 'software-issue-manager') ,
					'slug' => sanitize_title('Resolved - Fixed') ,
					'desc' => __('A fix for this issue has been implemented.', 'software-issue-manager')
				) ,
				Array(
					'name' => __('Resolved - Won\'t Fix', 'software-issue-manager') ,
					'slug' => sanitize_title('Resolved - Won\'t Fix') ,
					'desc' => __('This issue will not be fixed, e.g. it may no longer be relevant.', 'software-issue-manager')
				) ,
				Array(
					'name' => __('Resolved - Duplicate', 'software-issue-manager') ,
					'slug' => sanitize_title('Resolved - Duplicate') ,
					'desc' => __('This issue is a duplicate of an existing issue. It is recommended you create a link to the duplicated issue by creating a related issue connection.', 'software-issue-manager')
				) ,
				Array(
					'name' => __('Resolved - Incomplete', 'software-issue-manager') ,
					'slug' => sanitize_title('Resolved - Incomplete') ,
					'desc' => __('There is not enough information to work on this issue.', 'software-issue-manager')
				) ,
				Array(
					'name' => __('Resolved - CNR', 'software-issue-manager') ,
					'slug' => sanitize_title('Resolved - CNR') ,
					'desc' => __('This issue could not be reproduced at this time, or not enough information was available to reproduce the issue. If more information becomes available, reopen the issue.', 'software-issue-manager')
				)
			);
			self::set_taxonomy_init($set_tax_terms, 'issue_status');
			$set_tax_terms = Array(
				Array(
					'name' => __('Critical', 'software-issue-manager') ,
					'slug' => sanitize_title('Critical') ,
					'desc' => __('Critical bugs either render a system unusable (not being able to create content or upgrade between versions, blocks not displaying, and the like), cause loss of data, or expose security vulnerabilities. These bugs are to be fixed immediately.', 'software-issue-manager')
				) ,
				Array(
					'name' => __('Major', 'software-issue-manager') ,
					'slug' => sanitize_title('Major') ,
					'desc' => __('Issues which have significant repercussions but do not render the whole system unusable are marked major. An example would be a PHP error which is only triggered under rare circumstances or which affects only a small percentage of all users. These issues are prioritized in the current development release and backported to stable releases where applicable. Major issues do not block point releases.', 'software-issue-manager')
				) ,
				Array(
					'name' => __('Normal', 'software-issue-manager') ,
					'slug' => sanitize_title('Normal') ,
					'desc' => __('Bugs that affect one piece of functionality are normal priority. An example would be the category filter not working on the database log screen. This is a self-contained bug and does not impact the overall functionality of the software.', 'software-issue-manager')
				) ,
				Array(
					'name' => __('Minor', 'software-issue-manager') ,
					'slug' => sanitize_title('Minor') ,
					'desc' => __('Minor priority is most often used for cosmetic issues that don\'t inhibit the functionality or main purpose of the project, such as correction of typos in code comments or whitespace issues.', 'software-issue-manager')
				)
			);
			self::set_taxonomy_init($set_tax_terms, 'issue_priority');
			$set_tax_terms = Array(
				Array(
					'name' => __('Windows 8 (32-bit and 64-bit)', 'software-issue-manager') ,
					'slug' => sanitize_title('Windows 8 (32-bit and 64-bit)')
				) ,
				Array(
					'name' => __('Windows 7 (32-bit and 64-bit)', 'software-issue-manager') ,
					'slug' => sanitize_title('Windows 7 (32-bit and 64-bit)')
				) ,
				Array(
					'name' => __('Windows Vista (32-bit and 64-bit)', 'software-issue-manager') ,
					'slug' => sanitize_title('Windows Vista (32-bit and 64-bit)')
				) ,
				Array(
					'name' => __('Windows XP (32-bit and 64-bit)', 'software-issue-manager') ,
					'slug' => sanitize_title('Windows XP (32-bit and 64-bit)')
				) ,
				Array(
					'name' => __('Windows Server 2008 R2 (64-bit)', 'software-issue-manager') ,
					'slug' => sanitize_title('Windows Server 2008 R2 (64-bit)')
				) ,
				Array(
					'name' => __('Windows Server 2008 (32-bit and 64-bit)', 'software-issue-manager') ,
					'slug' => sanitize_title('Windows Server 2008 (32-bit and 64-bit)')
				) ,
				Array(
					'name' => __('Windows Server 2003 (32-bit and 64-bit)', 'software-issue-manager') ,
					'slug' => sanitize_title('Windows Server 2003 (32-bit and 64-bit)')
				) ,
				Array(
					'name' => __('Windows 2000 SP4', 'software-issue-manager') ,
					'slug' => sanitize_title('Windows 2000 SP4')
				) ,
				Array(
					'name' => __('Mac OS X 10.8 Mountain Lion (32-bit and 64-bit)', 'software-issue-manager') ,
					'slug' => sanitize_title('Mac OS X 10.8 Mountain Lion (32-bit and 64-bit)')
				) ,
				Array(
					'name' => __('Mac OS X 10.7 Lion (32-bit and 64-bit)', 'software-issue-manager') ,
					'slug' => sanitize_title('Mac OS X 10.7 Lion (32-bit and 64-bit)')
				) ,
				Array(
					'name' => __('Mac OS X 10.6 Snow Leopard (32-bit)', 'software-issue-manager') ,
					'slug' => sanitize_title('Mac OS X 10.6 Snow Leopard (32-bit)')
				) ,
				Array(
					'name' => __('Mac OS X 10.5 Leopard', 'software-issue-manager') ,
					'slug' => sanitize_title('Mac OS X 10.5 Leopard')
				) ,
				Array(
					'name' => __('Mac OS X 10.4 Tiger', 'software-issue-manager') ,
					'slug' => sanitize_title('Mac OS X 10.4 Tiger')
				) ,
				Array(
					'name' => __('Linux (32-bit and 64-bit versions, kernel 2.6 or compatible)', 'software-issue-manager') ,
					'slug' => sanitize_title('Linux (32-bit and 64-bit versions, kernel 2.6 or compatible)')
				)
			);
			self::set_taxonomy_init($set_tax_terms, 'operating_system');
			$set_tax_terms = Array(
				Array(
					'name' => __('Bug', 'software-issue-manager') ,
					'slug' => sanitize_title('Bug') ,
					'desc' => __('Bugs are software problems or defects in the system that need to be resolved.', 'software-issue-manager')
				) ,
				Array(
					'name' => __('Feature Request', 'software-issue-manager') ,
					'slug' => sanitize_title('Feature Request') ,
					'desc' => __('Feature requests are functional enhancements submitted by clients.', 'software-issue-manager')
				) ,
				Array(
					'name' => __('Task', 'software-issue-manager') ,
					'slug' => sanitize_title('Task') ,
					'desc' => __('Tasks are activities that need to be accomplished within a defined period of time or by a deadline to resolve issues.', 'software-issue-manager')
				)
			);
			self::set_taxonomy_init($set_tax_terms, 'issue_cat');
			update_option('software_issue_manager_emd_issue_terms_init', true);
		}
	}
	/**
	 * Set metabox fields,labels,filters, comments, relationships if exists
	 *
	 * @since WPAS 4.0
	 *
	 */
	public function set_filters() {
		do_action('emd_ext_class_init', $this);
		$search_args = Array();
		$filter_args = Array();
		$this->sing_label = __('Issue', 'software-issue-manager');
		$this->plural_label = __('Issues', 'software-issue-manager');
		$this->menu_entity = 'emd_project';
		$this->boxes['issue_info_emd_issue_0'] = array(
			'id' => 'issue_info_emd_issue_0',
			'title' => __('Issue Info', 'software-issue-manager') ,
			'app_name' => 'software_issue_manager',
			'pages' => array(
				'emd_issue'
			) ,
			'context' => 'normal',
		);
		list($search_args, $filter_args) = $this->set_args_boxes();
		if (!post_type_exists($this->post_type) || in_array($this->post_type, Array(
			'post',
			'page'
		))) {
			self::register();
		}
		$ent_map_list = get_option(str_replace('-', '_', $this->textdomain) . '_ent_map_list');
	}
	/**
	 * Initialize metaboxes
	 * @since WPAS 4.5
	 *
	 */
	public function set_metabox() {
		if (class_exists('EMD_Meta_Box') && is_array($this->boxes)) {
			foreach ($this->boxes as $meta_box) {
				new EMD_Meta_Box($meta_box);
			}
		}
	}
	/**
	 * Add operations and add new submenu hook
	 * @since WPAS 4.4
	 */
	public function add_menu_link() {
		add_submenu_page(null, __('Operations', 'software-issue-manager') , __('Operations', 'software-issue-manager') , 'manage_operations_emd_issues', 'operations_emd_issue', array(
			$this,
			'get_operations'
		));
	}
	/**
	 * Display operations page
	 * @since WPAS 4.0
	 */
	public function get_operations() {
		if (current_user_can('manage_operations_emd_issues')) {
			$myapp = str_replace("-", "_", $this->textdomain);
			do_action('emd_operations_entity', $this->post_type, $this->plural_label, $this->sing_label, $myapp, $this->menu_entity);
		}
	}
	/**
	 * Add new submenu hook
	 * @since WPAS 4.4
	 */
	public function add_top_menu_link() {
		add_submenu_page('edit.php?post_type=emd_project', __('Issues', 'software-issue-manager') , __('All Issues', 'software-issue-manager') , 'edit_emd_issues', 'edit.php?post_type=emd_issue', false);
		add_submenu_page('edit.php?post_type=emd_project', __('Issues', 'software-issue-manager') , __('Add New Issue', 'software-issue-manager') , 'edit_emd_issues', 'post-new.php?post_type=emd_issue', false);
		add_submenu_page('edit.php?post_type=emd_project', __('Browsers', 'software-issue-manager') , __('Browsers', 'software-issue-manager') , 'manage_browser', 'edit-tags.php?taxonomy=browser&amp;post_type=emd_issue', false);
		add_submenu_page('edit.php?post_type=emd_project', __('Categories', 'software-issue-manager') , __('Categories', 'software-issue-manager') , 'manage_issue_cat', 'edit-tags.php?taxonomy=issue_cat&amp;post_type=emd_issue', false);
		add_submenu_page('edit.php?post_type=emd_project', __('Priorities', 'software-issue-manager') , __('Priorities', 'software-issue-manager') , 'manage_issue_priority', 'edit-tags.php?taxonomy=issue_priority&amp;post_type=emd_issue', false);
		add_submenu_page('edit.php?post_type=emd_project', __('Statuses', 'software-issue-manager') , __('Statuses', 'software-issue-manager') , 'manage_issue_status', 'edit-tags.php?taxonomy=issue_status&amp;post_type=emd_issue', false);
		add_submenu_page('edit.php?post_type=emd_project', __('Tags', 'software-issue-manager') , __('Tags', 'software-issue-manager') , 'manage_issue_tag', 'edit-tags.php?taxonomy=issue_tag&amp;post_type=emd_issue', false);
		add_submenu_page('edit.php?post_type=emd_project', __('Operating Systems', 'software-issue-manager') , __('Operating Systems', 'software-issue-manager') , 'manage_operating_system', 'edit-tags.php?taxonomy=operating_system&amp;post_type=emd_issue', false);
	}
	/**
	 * Parent file for tax submenus with top level
	 * @since WPAS 5.3
	 */
	function tax_submenus($parent_file) {
		global $current_screen;
		$taxonomy = $current_screen->taxonomy;
		if (in_array($taxonomy, Array(
			'browser',
			'issue_cat',
			'issue_priority',
			'issue_status',
			'issue_tag',
			'operating_system'
		))) {
			$parent_file = 'edit.php?post_type=emd_project';
		}
		return $parent_file;
	}
}
new Emd_Issue;
