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

	public function createWithLayoutId($id) {
		if(empty($id)) {
			throw new \InvalidArgumentException('You must supply a layout id');
		}

		$devcms_config = $this->serviceLocator->get('Config')['devcms'];
		if(!isset($devcms_config['layouts'][$id])) {
			throw new \InvalidArgumentException('Unknown layout id "' . $id . '"');
		}

		$form = new PageForm();

		$if = $form->getInputFilter();

		$form->add([
			'name' => 'label',
			'options' => [
				'label' => 'Page label',
			]
		]);
		$if->add(['name' => 'label']);


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
			'attributes' => [
				'value' => $id,
			],
			'options' => [
				'label' => 'Layout',
				'value_options' => $layouts,
				'empty_option' => 'Please select',
			]
		]);
		$if->add(['name' => 'layout']);

		$var_fs = new Fieldset('vars');
		$var_if = new InputFilter();
		foreach($devcms_config['layouts'][$id]['variables'] as $var) {
			$var_fs->add([
				'name' => $var['name'],
				'type' => 'textarea',
				'attributes' => [
					'id' => 'f-' . $var['name'],
				],
				'options' => [
					'label' => $var['label']
				]
			]);
			$var_if->add([
				'name' => $var['name'],
				'required' => $var['required']
			]);
		}
		$form->add($var_fs);
		$if->add($var_if,'vars');

		return $form;
	}
}
