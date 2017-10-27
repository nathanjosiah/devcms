<?php

namespace DevCms\Model\Variable\Serializer;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SerializerFactoryServiceFactory implements FactoryInterface {
	public function createService(ServiceLocatorInterface $serviceLocator) {
		return $this($serviceLocator,'');
	}
	public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null) {
		return new SerializerFactory($serviceLocator);
	}
}