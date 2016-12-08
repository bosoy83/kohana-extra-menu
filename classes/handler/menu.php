<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Menu handlers interface
 * @author g.gudin
 */
interface Handler_Menu {
	
	/**
	 * Run handler for menu item
	 * @param array $item	menu item
	 * @param array $config	config
	 * @return array	changed menu item
	 */
	public function apply(array $item, array $config);
	
}
