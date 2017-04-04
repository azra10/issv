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
		if (get_role ( 'issadminrole' )) {
			remove_role ( 'issadminrole' );
		}
		if (get_role ( 'issboardrole' )) {
			remove_role ( 'issboardrole' );
		}
		if (get_role ( 'issparentrole' )) {
			remove_role ( 'issparentrole' );
		}
		if (get_role ( 'issteacherrole' )) {
			remove_role ( 'issteacherrole' );
		}
		if (get_role ( 'issstudentrole' )) {
			remove_role ( 'issstudentrole' );
		}
		$admrole = get_role ( 'administrator' );
		if (NULL != $admrole) {
			$admrole->remove_cap ( 'iss_admin' );
			$admrole->remove_cap ( 'iss_board' );
		}
		iss_write_log ( 'administrator role capability is_admin & iss_board removed' );
		global $wp_roles;
		if (! isset ( $wp_roles )) {
			$wp_roles = new WP_Roles ();
			iss_write_log ( $wp_roles );
		}
	}
	static function addrole($roleInternalName, $roleDisplayName, $capability) {
		$issrole = get_role ( $roleInternalName );
		if (NULL == $issrole) {
			$result = add_role ( $roleInternalName, $roleDisplayName, array (
					'read' => true,
					'level_0' => true,
					$capability => true 
			) );
			iss_write_log ( $result );
			if (null != $result) {
				iss_write_log ( "{$roleInternalName} role with capability {$capability} created!" );
			}
		} else {
			iss_write_log ( "{$roleInternalName} role exists" );
		}
	}
	static function addcapability($roleInternalName, $capability) {
		$issrole = get_role ( $roleInternalName );
		$cap = NULL;
		if (NULL != $issrole) {
			if (isset ( $issrole->capabilities [$capability] )) {
				$cap = $issrole->capabilities [$capability];
			}
			if (NULL == $cap) {
				$issrole->add_cap ( $capability );
				iss_write_log ( "{$roleInternalName} role, capability {$capability} is added" );
			} else {
				iss_write_log ( "{$roleInternalName} role, capability {$capability} already exists" );
			}
		} else {
			iss_write_log ( "{$roleInternalName} role does not exists" );
		}
	}
	static function install() {
		global $wp_roles;
		if (! isset ( $wp_roles ))
			$wp_roles = new WP_Roles ();
		
		forward_static_call_array ( array (
				'ISS_AdminRolePlugin',
				'addrole' 
		), array (
				'issadminrole',
				'ISS Admin Role',
				'iss_admin' 
		) );
		forward_static_call_array ( array (
				'ISS_AdminRolePlugin',
				'addrole' 
		), array (
				'issboardrole',
				'ISS Board Role',
				'iss_board' 
		) );
		forward_static_call_array ( array (
				'ISS_AdminRolePlugin',
				'addrole' 
		), array (
				'issteacherrole',
				'ISS Teacher Role',
				'iss_teacher' 
		) );
		forward_static_call_array ( array (
				'ISS_AdminRolePlugin',
				'addrole' 
		), array (
				'issparentrole',
				'ISS Parent Role',
				'iss_parent' 
		) );
		forward_static_call_array ( array (
				'ISS_AdminRolePlugin',
				'addrole' 
		), array (
				'issstudentrole',
				'ISS Student Role',
				'iss_student' 
		) );
		
		forward_static_call_array ( array (
				'ISS_AdminRolePlugin',
				'addcapability' 
		), array (
				'issteacherrole',
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