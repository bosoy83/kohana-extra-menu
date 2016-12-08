<?php defined('SYSPATH') or die('No direct script access.');

class Model_Extra_Menu extends ORM_Base {

	protected $_table_name = 'extra_menu';
	protected $_sorting = array('position' => 'ASC');
	protected $_deleted_column = 'delete_bit';
	protected $_active_column = 'active';

	public function labels()
	{
		return array(
			'title' => 'Title',
			'link' => 'Link',
			'target' => 'Open link',
			'type' => 'Type',
			'handler' => 'Handler',
			'active' => 'Active',
			'position' => 'Position',
			'for_all' => 'For all sites',
		);
	}

	public function rules()
	{
		return array(
			'id' => array(
				array('digit'),
			),
			'site_id' => array(
				array('not_empty'),
				array('digit'),
			),
			'title' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
			),
			'link' => array(
// 				array('not_empty'),
				array('max_length', array(':value', 255)),
			),
			'target' => array(
				array('in_array', array(':value', array('_self', '_blank', '_modal'))),
			),
			'type' => array(
				array('not_empty'),
			),
			'handler' => array(
				array('max_length', array(':value', 255)),
			),
			'position' => array(
				array('digit'),
			),
		);
	}

	public function filters()
	{
		return array(
			TRUE => array(
				array('trim'),
			),
			'title' => array(
				array('strip_tags'),
			),
			'active' => array(
				array(array($this, 'checkbox'))
			),
			'for_all' => array(
				array(array($this, 'checkbox'))
			),
		);
	}
}
