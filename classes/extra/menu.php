<?php defined('SYSPATH') or die('No direct script access.');

class Extra_Menu {
	
	public function get($type)
	{
		$handlers = Kohana::$config->load('extra-menu.handlers');
		
		$list = ORM::factory('Extra_Menu')
			->where('type', '=', $type)
			->order_by('position')
			->find_all();
			
		$result = array();
		$helper_orm = ORM_Helper::factory('Extra_Menu');
		foreach ($list as $_orm) {
			$helper_orm->orm($_orm);
			$_item = array(
				'id' => $_orm->id,
				'title' => $_orm->title,
				'target' => $_orm->target,
				'handler' => $_orm->handler,
				'link' => $_orm->link,
				'properties' => $helper_orm->property_list(),
				'sub' => array(),
			);
			
			if (($_getter = Arr::path($handlers, $_orm->handler.'.instance')) AND is_callable($_getter)) {
				$_instance = $_getter();
				if ($_instance instanceof Handler_Menu) {
					$_item = $_instance->apply($_item, Arr::get($handlers, $_orm->handler));
				}
			}
			
			$result[] = $_item;
		}
		
		return $result;
	}
	
}