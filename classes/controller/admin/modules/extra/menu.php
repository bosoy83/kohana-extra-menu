<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Modules_Extra_Menu extends Controller_Admin_Modules_Extra_Menu_Base {

	private $target_options;

	public function before()
	{
		parent::before();

		$target_options = Arr::path(ORM::factory('extra_menu')->list_columns(), 'target.options', array());
		foreach ($target_options as $_v) {
			$this->target_options[$_v] = __($_v);
		}
		$this->template
			->set_global('TARGET_OPTIONS', $this->target_options);
	}

	public function action_index()
	{
		$orm = ORM::factory('extra_menu')
			->where('type', '=', $this->type_code);
		
		$paginator_orm = clone $orm;
		$paginator = new Paginator('admin/layout/paginator');
		$paginator
			->per_page(20)
			->count($paginator_orm->count_all());
		unset($paginator_orm);
		
		$list = $orm
			->paginator($paginator)
			->find_all();
		
		$this->template
			->set_filename('modules/extra-menu/element/list')
			->set('list', $list)
			->set('hided_list', $this->get_hided_list($orm->object_name()))
			->set('paginator', $paginator)
			->set('handlers', $this->get_handlers());
			
		$this->left_menu_element_add();
		$this->left_menu_element_fix($orm);
		$this->sub_title = __('Menu items list');;
	}

	public function action_edit()
	{
		$request = $this->request->current();
		$id = (int) $request->current()->param('id');
		$helper_orm = ORM_Helper::factory('extra_menu');
		$orm = $helper_orm->orm();
		if ( (bool) $id) {
			$orm
				->where('id', '=', $id)
				->find();
		
			if ( ! $orm->loaded() OR ! $this->acl->is_allowed($this->user, $orm, 'edit')) {
				throw new HTTP_Exception_404();
			}
			$this->title = __('Edit item');
		} else {
			$this->title = __('Add item');
		}
		
		if (empty($this->back_url)) {
			$query_array = array(
				'type' => $this->type_code
			);
			$query_array = Paginator::query($request, $query_array);
			$this->back_url = Route::url('modules', array(
				'controller' => $this->controller_name['element'],
				'query' => Helper_Page::make_query_string($query_array),
			));
		}
		
		if ($this->is_cancel) {
			$request
				->redirect($this->back_url);
		}

		$errors = array();
		$submit = $request->post('submit');
		if ($submit) {
			try {
				if ( (bool) $id) {
					$orm->updater_id = $this->user->id;
					$orm->updated = date('Y-m-d H:i:s');
					$reload = FALSE;
				} else {
					$orm->site_id = SITE_ID;
					$orm->creator_id = $this->user->id;
					$orm->type = $this->type_code;
					$reload = TRUE;
				}
				
				$values = $request->current()->post();
				$helper_orm->save($values + $_FILES);
				
				if ($reload) {
					if ($submit != 'save_and_exit') {
						$this->back_url = Route::url('modules', array(
							'controller' => $request->controller(),
							'action' => $request->action(),
							'id' => $orm->id,
							'query' => Helper_Page::make_query_string($request->query()),
						));
					}
						
					$request
						->redirect($this->back_url);
				}
			} catch (ORM_Validation_Exception $e) {
				$errors = $this->errors_extract($e);
			}
		}

		// If add action then $submit = NULL
		if ( ! empty($errors) OR $submit != 'save_and_exit') {
			
			$properties = $helper_orm->property_list();
			
			$this->template
				->set_filename('modules/extra-menu/element/edit')
				->set('errors', $errors)
				->set('helper_orm', $helper_orm)
				->set('properties', $properties)
				->set('handlers', $this->get_handlers());
			
			$this->left_menu_element_add();
		}
		else {
			$request
				->redirect($this->back_url);
		}
	}
	
	public function action_delete()
	{
		$request = $this->request->current();
		$id = (int) $request->param('id');
		
		$helper_orm = ORM_Helper::factory('extra_menu');
		$orm = $helper_orm->orm();
		$orm
			->and_where('id', '=', $id)
			->find();
		
		if ( ! $orm->loaded() OR ! $this->acl->is_allowed($this->user, $orm, 'edit')) {
			throw new HTTP_Exception_404();
		}
		
		if ($this->element_delete($helper_orm)) {
			if (empty($this->back_url)) {
				$query_array = array(
					'type' => $this->type_code
				);
				$query_array = Paginator::query($request, $query_array);
				$this->back_url = Route::url('modules', array(
					'controller' => $this->controller_name['element'],
					'query' => Helper_Page::make_query_string($query_array),
				));
			}
		
			$request
				->redirect($this->back_url);
		}
	}

	public function action_position()
	{
		$request = $this->request->current();
		$id = (int) $request->param('id');
		$mode = $request->query('mode');
		$errors = array();
		$helper_orm = ORM_Helper::factory('extra_menu');
	
		try {
			$this->element_position($helper_orm, $id, $mode);
		} catch (ORM_Validation_Exception $e) {
			$errors = $this->errors_extract($e);
		}
	
		if (empty($errors)) {
			if (empty($this->back_url)) {
				$query_array = array(
					'type' => $this->type_code
				);
				if ($mode != 'fix') {
					$query_array = Paginator::query($request, $query_array);
				}
	
				$this->back_url = Route::url('modules', array(
					'controller' => $this->controller_name['element'],
					'query' => Helper_Page::make_query_string($query_array),
				));
			}
	
			$request
				->redirect($this->back_url);
		}
	}
	
	public function action_visibility()
	{
		$request = $this->request->current();
		$id = (int) $request->param('id');
		$mode = $request->query('mode');
		
		$orm = ORM::factory('extra_menu')
			->and_where('id', '=', $id)
			->find();
		
		if ( ! $orm->loaded() OR ! $this->acl->is_allowed($this->user, $orm, 'hide')) {
			throw new HTTP_Exception_404();
		}
		
		if ($mode == 'hide') {
			$this->element_hide($orm->object_name(), $orm->id);
		} elseif ($mode == 'show') {
			$this->element_show($orm->object_name(), $orm->id);
		}
		
		if (empty($this->back_url)) {
			$query_array = array(
				'type' => $this->type_code
			);
			$query_array = Paginator::query($request, $query_array);
			$this->back_url = Route::url('modules', array(
				'controller' => $this->controller_name['element'],
				'query' => Helper_Page::make_query_string($query_array),
			));
		}
		
		$request
			->redirect($this->back_url);
	}
	
	protected function _get_breadcrumbs()
	{
		$breadcrumbs = parent::_get_breadcrumbs();
		
		$action = $this->request->current()
			->action();
		if (in_array($action, array('edit'))) {
			$id = (int) $this->request->current()->param('id');
			$element_orm = ORM::factory('extra_menu')
				->where('id', '=', $id)
				->find();
			if ($element_orm->loaded()) {
				switch ($action) {
					case 'edit':
						$_str = ' ['.__('edition').']';
						break;
					default:
						$_str = '';
				}
				
				$breadcrumbs[] = array(
					'title' => $element_orm->title.$_str,
				);
			} else {
				$breadcrumbs[] = array(
					'title' => ' ['.__('new item').']',
				);
			}
		}
		
		return $breadcrumbs;
	}
	

	private function get_handlers()
	{
		$handlers = Kohana::$config->load('extra-menu.handlers');
		
		return array(
			'' => __('-- handler --')
		) + Arr::pluck($handlers, 'title', TRUE);
	}
} 
