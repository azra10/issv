<?php
/*
 * Plugin Name: ISS Roles Plugin
 * Description: Create admin, board, teacher, parent and student roles on activation.
 * Version: 1.0.0
 * Author: Azra Syed
 * Text Domain: iss_adminpref
 */
class ISS_AdminRolePlugin {
	static function uninsatall() {
		if (get_role ( 'issadmin' )) {
			remove_role ( 'issadmin' );
		}
		if (get_role ( 'issboard' )) {
			remove_role ( 'issboard' );
		}
		if (get_role ( 'issparent' )) {
			remove_role ( 'issparent' );
		}
		if (get_role ( 'issteacher' )) {
			remove_role ( 'issteacher' );
		}
		if (get_role ( 'issstudent' )) {
			remove_role ( 'issstudent' );
		}
		$admrole = get_role ( 'administrator' );
		if (NULL != $admrole) {
			$admrole->remove_cap ( 'iss_admin' );
			$admrole->remove_cap ( 'iss_board' );
		}
		iss_write_log ( 'administrator role capability is_admin, issteacher, issparent, issstudent & iss_board removed' );
		global $wp_roles;
		if (! isset ( $wp_roles )) {
			$wp_roles = new WP_Roles ();
			iss_write_log ( $wp_roles );
		}
	}
	static function addroll($role, $roleName, $capability) {
		$issrole = get_role ( $role );
		if (NULL == $issrole) {
			$result = add_role ( $role, $roleName, array (
					'read' => true,
					'level_0' => true,
					$capability => true 
			) );
			iss_write_log ( $result );
			if (null != $result) {
				iss_write_log ( "{$role} role with capability {$capability} created!" );
			}
		} else {
			iss_write_log ( "{$role} role exists" );
		}
	}
	static function addcapability($role, $capability) {
		$issrole = get_role ( $role );
		$cap = NULL;
		if (NULL != $issrole) {
			if (isset ( $issrole->capabilities [$capability] )) {
				$cap = $issrole->capabilities [$capability];
			}
			if (NULL == $cap) {
				$issrole->add_cap ( $capability );
				iss_write_log ( "{$role} role, capability {$capability} is added" );
			} else {
				iss_write_log ( "{$role} role, capability {$capability} already exists" );
			}
		} else {
			iss_write_log ( "{$role} role does not exists" );
		}
	}
	static function install() {
		global $wp_roles;
		if (! isset ( $wp_roles ))
			$wp_roles = new WP_Roles ();
		
		forward_static_call_array ( array (
				'ISS_AdminRolePlugin',
				'addroll' 
		), array (
				'issadmin',
				'ISS Admin',
				'iss_admin' 
		) );
		forward_static_call_array ( array (
				'ISS_AdminRolePlugin',
				'addroll' 
		), array (
				'issboard',
				'ISS Board',
				'iss_board' 
		) );
		forward_static_call_array ( array (
				'ISS_AdminRolePlugin',
				'addroll' 
		), array (
				'issteacher',
				'ISS Teacher',
				'iss_teacher' 
		) );
		forward_static_call_array ( array (
				'ISS_AdminRolePlugin',
				'addroll' 
		), array (
				'issparent',
				'ISS Parent',
				'iss_parent' 
		) );
		forward_static_call_array ( array (
				'ISS_AdminRolePlugin',
				'addroll' 
		), array (
				'issstudent',
				'ISS Student',
				'iss_student' 
		) );
		
		forward_static_call_array ( array (
				'ISS_AdminRolePlugin',
				'addcapability' 
		), array (
				'issteacher',
				'iss_parent' 
		) );
		forward_static_call_array ( array (
				'ISS_AdminRolePlugin',
				'addcapability' 
		), array (
				'administrator',
				'iss_admin' 
		) );
		forward_static_call_array ( array (
				'ISS_AdminRolePlugin',
				'addcapability' 
		), array (
				'administrator',
				'iss_board' 
		) );
	}
}
register_activation_hook ( __FILE__, array (
		'ISS_AdminRolePlugin',
		'install' 
) );
register_deactivation_hook ( __FILE__, array (
		'ISS_AdminRolePlugin',
		'uninsatall' 
) );