<?php

namespace DevCms\Model\Variable\Form;

use Zend\ServiceManager\FactoryInterface;

class FactoryFactory implements FactoryInterface {
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
		$factory = new Factory($serviceLocator->get('FormElementManager'),$serviceLocator->get('InputFilterManager'),$serviceLocator->get('Config')['devcms']);
		return $factory;
	}
}

