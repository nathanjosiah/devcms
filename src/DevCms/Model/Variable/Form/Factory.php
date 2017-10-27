<?php

namespace DevCms\Model\Variable\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;
use Zend\Form\FormElementManager;
use Zend\InputFilter\InputFilterPluginManager;

class Factory {
	private $formElementManager,$devCmsConfig,$inputFilterPluginManager;

	public function __construct($element_manager,InputFilterPluginManager $input_filter_plugin_manager,array $devcms_config) {
		$this->formElementManager = $element_manager;
		$this->devCmsConfig = $devcms_config;
		$this->inputFilterPluginManager = $input_filter_plugin_manager;
	}

	public function createElement($name,array $config) {
		$var_fieldset = new Fieldset($name);

		$this->formElementManager->injectFactory($var_fieldset,$this->formElementManager);

		$type_config = $this->devCmsConfig['variable_types'][$config['type']];

		$var_fieldset->add([
			'name' => 'content',
			'type' => $type_config['element'],
			'attributes' => [
				'id' => 'f-' . $name,
			],
			'options' => [
				'label' => $config['label'],
				'__partial__' => $type_config['partial'],
			]
		]);

		if(!empty($config['type_options'])) {
			$var_fieldset->setOptions($config['type_options']);
		}

		$var_fieldset->add([
			'name' => 'id',
			'type' => 'hidden',
		]);

		return $var_fieldset;
	}

	public function createInputFilter($config) {
		$var_if = new InputFilter();
		$var_if->getFactory()->setInputFilterManager($this->inputFilterPluginManager);

		$type_config = $this->devCmsConfig['variable_types'][$config['type']];

		$if_spec = $type_config['input_filter']['options'];
		$if_spec['name'] = 'content';
		$if_spec['required'] = $config['required'];
		$var_if->add($if_spec);

		$var_if->add([
			'name' => 'id',
			'required' => false,
		]);

		return $var_if;
	}
}

