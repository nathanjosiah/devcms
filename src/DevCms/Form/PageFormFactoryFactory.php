<?php

namespace DevCms\Form;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PageFormFactoryFactory implements FactoryInterface {
	public function createService(ServiceLocatorInterface $serviceLocator) {
		return $this($serviceLocator,'');
	}

	public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null) {
		$page_factory = new PageFormFactory($serviceLocator->get('DevCms\Model\Variable\Form\Factory'),$serviceLocator->get('Config')['devcms']);
		return $page_factory;
	}
}

