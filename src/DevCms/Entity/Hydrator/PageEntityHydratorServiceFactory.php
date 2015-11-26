<?php

namespace DevCms\Entity\Hydrator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PageEntityHydratorServiceFactory implements FactoryInterface {
	public function createService(ServiceLocatorInterface $serviceLocator) {
		$hydrator = new PageEntityHydrator($serviceLocator->get('DevCms\Table\ContentBlocksTable'));
		return $hydrator;
	}
}
