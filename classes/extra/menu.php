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
		foreach ($list as $_orm) {
			$_item = array(
				'title' => $_orm->title,
				'target' => $_orm->target,
				'handler' => $_orm->handler,
				'sub' => array(),
				'link' => $_orm->link,
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