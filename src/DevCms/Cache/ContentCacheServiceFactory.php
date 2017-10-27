<?php

namespace DevCms\Cache;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Cache\StorageFactory;

class ContentCacheServiceFactory implements FactoryInterface {

	public function createService(ServiceLocatorInterface $serviceLocator) {
		return $this($serviceLocator,'');
	}

	public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null) {
		$cache_config = $serviceLocator->get('config')['devcms']['cache_storage'];
		$storage = StorageFactory::factory($cache_config);
		return $storage;
	}
}
