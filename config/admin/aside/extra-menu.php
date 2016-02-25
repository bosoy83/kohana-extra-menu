<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'extra-menu' => array(
		'title' => __('Menu items list'),
		'link' => Route::url('modules', array(
			'controller' => 'extra_menu',
			'query' => 'type={TYPE_CODE}'
		)),
		'sub' => array(),
	),
);