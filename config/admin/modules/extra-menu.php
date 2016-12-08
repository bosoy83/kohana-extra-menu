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
					'role' => 'main',
					'resource' => 'extra_menu_controller',
					'privilege' => 'access',
					'assertion' => array('Acl_Assert_Module_Access', array(
						'module' => 'extra-menu',
					)),
				),
			
				// Elements
				array(
					'role' => 'main',
					'resource' => 'extra_menu',
					'privilege' => 'edit',
				),
				array(
					'role' => 'main',
					'resource' => 'extra_menu',
					'privilege' => 'fix_all',
				),
			),
			'deny' => array(
			)
		)
	),
);