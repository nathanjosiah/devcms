<?php

namespace DevCms\Form;

use Zend\Form\Form;

class ContentBlockForm extends Form {
	/**
	 * @param array $block_config
	 * @return \DevCms\Form\ContentBlockForm
	 */
	public static function fromConfig(array $block_config) {
		$form = new static('content_block');
		$form->add([
			'name' => 'html',
			'type' => 'textarea',
			'options' => [
				'label' => 'Content',
			]
		]);
		return $form;
	}
}

