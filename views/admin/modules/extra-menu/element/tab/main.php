<?php defined('SYSPATH') or die('No direct access allowed.');

	$orm = $helper_orm->orm();
	$labels = $orm->labels();
	$required = $orm->required_fields();

/**** for_all ****/

	if (IS_MASTER_SITE) {
		echo View_Admin::factory('form/checkbox', array(
			'field' => 'for_all',
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'orm_helper' => $helper_orm,
		));
	}	
	
/**** active ****/
	
	echo View_Admin::factory('form/checkbox', array(
		'field' => 'active',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'orm_helper' => $helper_orm,
	));
	
/**** title ****/
	
	echo View_Admin::factory('form/control', array(
		'field' => 'title',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'controls' => Form::input('title', $orm->title, array(
			'id' => 'title_field',
			'class' => 'input-xxlarge',
		)),
	));
	
/**** link ****/
	
	echo View_Admin::factory('form/control', array(
		'field' => 'link',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'controls' => Form::input('link', $orm->link, array(
			'id' => 'link_field',
			'class' => 'input-xxlarge',
		)),
	));
	
/**** target ****/
	
	echo View_Admin::factory('form/control', array(
		'field' => 'target',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'controls' => Form::select('target', $TARGET_OPTIONS, $orm->target, array(
			'id' => 'target_field',
			'class' => 'input-xxlarge',
		)),
	));
	
/**** handler ****/
	
	if ( ! empty($handlers)) {
		echo View_Admin::factory('form/control', array(
			'field' => 'handler',
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'controls' => Form::select('handler', $handlers, $orm->handler, array(
				'id' => 'target_field',
				'class' => 'input-xxlarge',
			)),
		));
	}
