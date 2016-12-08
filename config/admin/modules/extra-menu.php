<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'a2' => array(
		'resources' => array(
			'extra_menu_controller' => 'module_controller',
			'extra_menu' => NULL,
		),
		'rules' => array(
			'allow' => array(
				// Module
				array(
					'role' => 'full',
					'resource' => 'extra_menu_controller',
					'privilege' => 'access',
				),
					
				// Elements
				array(
					'role' => 'full',
					'resource' => 'extra_menu',
					'privilege' => 'edit',
					'assertion' => array('Acl_Assert_Edit', array(
						'site_id' => SITE_ID,
					)),
				),
				array(
					'role' => 'full',
					'resource' => 'extra_menu',
					'privilege' => 'hide',
					'assertion' => array('Acl_Assert_Hide', array(
						'site_id' => SITE_ID,
						'site_id_master' => SITE_ID_MASTER
					)),
				),
				array(
					'role' => 'super',
					'resource' => 'extra_menu',
					'privilege' => 'fix_all',
				),
				array(
					'role' => 'main',
					'resource' => 'extra_menu',
					'privilege' => 'fix_master',
				),
				array(
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