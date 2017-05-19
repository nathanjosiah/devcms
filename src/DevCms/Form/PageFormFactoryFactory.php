<?php

namespace DevCms\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PageFormFactoryFactory implements FactoryInterface {
	public function createService(ServiceLocatorInterface $serviceLocator) {
		$page_factory = new PageFormFactory($serviceLocator->get('DevCms\Model\Variable\Form\Factory'),$serviceLocator->get('Config')['devcms']);
		return $page_factory;
	}
}

