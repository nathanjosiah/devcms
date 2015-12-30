<?php

namespace DevCms\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
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

		$var_fs = new Fieldset('vars');
		$var_if = new InputFilter();
		$if = $form->getInputFilter();
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
			if(!$var['required']) {
				$input = new Input($var['name']);
				$input->setRequired(false);
				$var_if->add($input);
			}
		}
		$form->add($var_fs);
		$if->add($var_if,'vars');

		return $form;
	}
}
