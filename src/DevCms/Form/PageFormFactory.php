<?php

namespace DevCms\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;
use DevCms\Model\Variable\Form\Factory;

class PageFormFactory {
	protected $devcmsConfig,$variableFormFactory;


	public function __construct(Factory $variableFormFactory,array $devcms_config) {
		$this->variableFormFactory = $variableFormFactory;
		$this->devcmsConfig = $devcms_config;
		return $this;
	}

	public function create() {
		$form = new PageForm();
		$if = $form->getInputFilter();
		$form->add([
			'name' => 'slug',
			'options' => [
				'label' => 'Page URL slug',
			]
		]);
		$if->add(['name' => 'slug']);

		$form->add([
			'name' => 'label',
			'options' => [
				'label' => 'Page label',
			]
		]);
		$if->add(['name' => 'label']);


		$layouts = [];
		foreach($this->devcmsConfig['layout_categories'] as $category) {
			$tmp = [];
			foreach($category['layouts'] as $layout_id) {
				$tmp[$layout_id] = $this->devcmsConfig['layouts'][$layout_id]['label'];
			}
			$layouts[] = [
				'label' => $category['label'],
				'options' => $tmp,
			];
		}

		$form->add([
			'name' => 'layout',
			'type' => 'Select',
			'options' => [
				'label' => 'Layout',
				'value_options' => $layouts,
				'empty_option' => 'Please select',
			]
		]);
		$if->add(['name' => 'layout']);

		$var_fs = new Fieldset('vars');
		$var_if = new InputFilter();
		$form->add($var_fs);
		$if->add($var_if,'vars');

		return $form;
	}

	public function createWithLayoutId($id) {
		if(empty($id)) {
			throw new \InvalidArgumentException('You must supply a layout id');
		}

		$devcms_config = $this->serviceLocator->get('Config')['devcms'];
		if(!isset($devcms_config['layouts'][$id])) {
			throw new \InvalidArgumentException('Unknown layout id "' . $id . '"');
		}

		$form = $this->create();

		$form->get('layout')->setAttribute('value',$id);

		$vars_fs = $form->get('vars');
		$vars_if = $form->getInputFilter()->get('vars');

		foreach($devcms_config['layouts'][$id]['variables'] as $var_name => $var) {
			$var_fieldset = $this->variableFormFactory->createElement($var_name,$var);
			$vars_fs->add($var_fieldset);

			$var_if = $this->variableFormFactory->createInputFilter($var);
			$vars_if->add($var_if,$var_name);
		}

		return $form;
	}
}
