<?php

class Template extends Mustache {

	// default page title
	public $title = 'Pastry Dish';

	// page content
	public $content = '';

	// site mustache template file
	public static $site_template = 'site.mustache';

	// menu mustache template file
	public static $menu_template = 'menu.mustache';

	public function __construct() {

		// get site template
		$site_template = file_get_contents(realpath(TEMPLATEPATH.self::$site_template));

		// get menu template
		$menu_template = file_get_contents(realpath(TEMPLATEPATH.self::$menu_template));

		// setup mustache view with menu partial
		parent::__construct($site_template, $this, array('menu' => $menu_template));

	}

	// convert content structure into key values for mustache
	public function menu() {

		$menu = array();

		// get root content pages
		foreach (Page::find_all(array('section' => NULL)) as $page) {

			if ($page->is_visible) {

				// add child pages if parent is section
				$menu[] = ($page->is_section) ? $this->_recursive_pages($page) : $page;

			}
		}

		return $menu;

	}

	// recursively build menu
	private function _recursive_pages($parent) {

		// add children recursively
		foreach (Page::find_all(array('section' => $parent->name)) as $page) {

			$parent->children[] = $this->_recursive_pages($page);

		}

		return $parent;

	}


}