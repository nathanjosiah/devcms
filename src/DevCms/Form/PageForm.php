<?php

namespace DevCms\Form;

use Zend\Form\Form;

class PageForm extends Form {

	public function __construct() {
		parent::__construct('page_form');
		/*$options = [];
		foreach($layouts as $key => $layout) {
			$options[$key] = $layout['label'];
		}
		$this->add([
			'name' => 'layout',
			'type' => 'select',
			'options' => [
				'value_options' => $options
			]
		]);*/
	}
}
