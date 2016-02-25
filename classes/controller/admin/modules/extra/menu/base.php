<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Modules_Extra_Menu_Base extends Controller_Admin_Front {

	protected $module_config = 'extra-menu';
	protected $menu_active_item = 'modules';
	protected $title = 'Extra menu';
	protected $sub_title = 'Extra menu';
	
	protected $category_id;
	protected $controller_name = array(
		'element' => 'extra_menu',
	);
	
	protected $type_options;
	protected $type_code;
	
	public function before()
	{
		parent::before();
	
		$query_controller = $this->request->query('controller');
		if ( ! empty($query_controller) AND is_array($query_controller)) {
			$this->controller_name = $this->request->query('controller');
		}
		$this->template
			->bind_global('CONTROLLER_NAME', $this->controller_name);
		
		$this->title = __($this->title);
		$this->sub_title = __($this->sub_title);
		
		$this->type_options = Kohana::$config->load('extra-menu.type');
		$this->template
			->set_global('TYPE_OPTIONS', $this->type_options);
		
		$this->type_code = $this->request->current()
			->query('type');
		if (empty($this->type_code)) {
			$this->type_code = key($this->type_options);
		}
		
		$this->template
			->set_global('TYPE_CODE', $this->type_code);
	}
	
	protected function layout_aside()
	{
		$menu_items = array_merge_recursive(
			Kohana::$config->load('admin/aside/extra-menu')->as_array(),
			$this->menu_left_ext
		);
		
		return parent::layout_aside()
			->set('menu_items', $menu_items)
			->set('replace', array(
				'{TYPE_CODE}' => $this->type_code,
			));
	}

	protected function left_menu_element_add()
	{
		$this->menu_left_add(array(
			'extra-menu' => array(
				'sub' => array(
					'add' => array(
						'title' => __('Add item'),
						'link' => Route::url('modules', array(
							'controller' => $this->controller_name['element'],
							'action' => 'edit',
							'query' => 'type={TYPE_CODE}'
						)),
					),
				),
			),
		));
	}
	
	protected function left_menu_element_fix($orm)
	{
		$can_fix_all = $this->acl->is_allowed($this->user, $orm, 'fix_all');
		$can_fix_master = $this->acl->is_allowed($this->user, $orm, 'fix_master');
		$can_fix_slave = $this->acl->is_allowed($this->user, $orm, 'fix_slave');
		
		if ($can_fix_all OR $can_fix_master OR $can_fix_slave) {
			$this->menu_left_add(array(
				'extra-menu' => array(
					'sub' => array(
						'fix' => array(
							'title' => __('Fix positions'),
							'link'  => Route::url('modules', array(
								'controller' => $this->controller_name['element'],
								'action' => 'position',
								'query' => 'type={TYPE_CODE}&mode=fix'
							)),
						),
					),
				),
			));
		}
	}
	
	protected function _get_breadcrumbs()
	{
		$query_array = array(
			'type' => $this->type_code
		);
		
		return array(
			array(
				'title' => __('Extra menu'),
				'link' => Route::url('modules', array(
					'controller' => $this->controller_name['element'],
					'query' => Helper_Page::make_query_string($query_array),
				)),
			)
		);
	}
}

