<?php

namespace DevCms\Form;

use Zend\Form\Form;

class ContentBlockForm extends Form {
	/**
	 * @param array $block_config
	 * @param array $type_config
	 * @return ContentBlockForm
	 */
	public static function fromConfig(array $block_config,array $type_config) {
		$form = new static('content_block');
		$form->add([
			'name' => 'content',
			'type' => 'textarea',
			'attributes' => [
				'id' => 'f-content',
			],
			'options' => [
				'label' => 'Content',
				'__partial__' => $type_config['partial'],
			]
		]);
		return $form;
	}
}

