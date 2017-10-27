<?php

namespace DevCms\Model\Variable\Form;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;

class FactoryFactory implements FactoryInterface {
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
		return $this($serviceLocator,'');
	}
	public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null) {
		$factory = new Factory($serviceLocator->get('FormElementManager'),$serviceLocator->get('InputFilterManager'),$serviceLocator->get('Config')['devcms']);
		return $factory;
	}
}

