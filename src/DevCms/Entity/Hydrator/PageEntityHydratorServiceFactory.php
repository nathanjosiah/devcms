<?php

namespace DevCms\Entity\Hydrator;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PageEntityHydratorServiceFactory implements FactoryInterface {
	public function createService(ServiceLocatorInterface $serviceLocator) {
		return $this($serviceLocator,'');
	}

	public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null) {
		$hydrator = new PageEntityHydrator($serviceLocator->get('DevCms\Table\ContentBlocksTable'));
		return $hydrator;
	}
}
