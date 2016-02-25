<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'a2' => array(
		'resources' => array(
			'extra_menu_controller' => 'module_controller',
			'extra_menu' => 'module',
		),
		'rules' => array(
			'allow' => array(
				'controller_access_1' => array(
					'role' => 'full',
					'resource' => 'extra_menu_controller',
					'privilege' => 'access',
//					'assertion' => array('Acl_Assert_Module_Access', array(
//						'module' => 'extra-menu',
//					)),
				),
			
				'extra_menu_edit_1' => array(
					'role' => 'full',
					'resource' => 'extra_menu',
					'privilege' => 'edit',
					'assertion' => array('Acl_Assert_Edit', array(
						'site_id' => SITE_ID,
					)),
				),
				'extra_menu_hide' => array(
					'role' => 'full',
					'resource' => 'extra_menu',
					'privilege' => 'hide',
					'assertion' => array('Acl_Assert_Hide', array(
						'site_id' => SITE_ID,
						'site_id_master' => SITE_ID_MASTER
					)),
				),
				'extra_menu_fix_all' => array(
					'role' => 'super',
					'resource' => 'extra_menu',
					'privilege' => 'fix_all',
				),
				'extra_menu_fix_master' => array(
					'role' => 'main',
					'resource' => 'extra_menu',
					'privilege' => 'fix_master',
				),
				'extra_menu_fix_slave' => array(
					'role' => 'full',
					'resource' => 'extra_menu',
					'privilege' => 'fix_slave',
				),
			),
			'deny' => array(
			)
		)
	),
);