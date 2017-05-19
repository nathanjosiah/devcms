<?php

namespace DevCms\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
class PageFormFactory implements FactoryInterface {

	protected $serviceLocator;
	public function createService(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
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

		$devcms_config = $this->serviceLocator->get('Config')['devcms'];

		$layouts = [];
		foreach($devcms_config['layout_categories'] as $category) {
			$tmp = [];
			foreach($category['layouts'] as $layout_id) {
				$tmp[$layout_id] = $devcms_config['layouts'][$layout_id]['label'];
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
			$var_fieldset = new Fieldset($var_name);
			$var_if = new InputFilter();

			$type_config = $devcms_config['variable_types'][$var['type']];

			$var_fieldset->add([
				'name' => 'content',
				'type' => $type_config['element'],
				'attributes' => [
					'id' => 'f-' . $var_name,
				],
				'options' => [
					'label' => $var['label'],
					'__partial__' => $type_config['partial'],
				]
			]);

			$if_spec = $type_config['input_filter']['options'];
			$if_spec['name'] = 'content';
			$if_spec['required'] = $var['required'];
			$var_if->add($if_spec);

			$var_fieldset->add([
				'name' => 'id',
				'type' => 'hidden',
			]);
			$var_if->add([
				'name' => 'id',
				'required' => false,
			]);

			$vars_fs->add($var_fieldset);
			$vars_if->add($var_if,$var_name);
		}

		return $form;
	}
}
