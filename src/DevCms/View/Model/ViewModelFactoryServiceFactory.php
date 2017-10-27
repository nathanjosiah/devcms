<?php
namespace DevCms\View\Model;

use DevCms\Model\Variable\Serializer\SerializerFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Interop\Container\ContainerInterface;

class ViewModelFactoryServiceFactory implements FactoryInterface  {
	public function createService(ServiceLocatorInterface $serviceLocator) {
		return $this($serviceLocator,'');
	}

	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
		$devcmsConfig = $container->get('Config')['devcms'];
		$serializerFactory = $container->get(SerializerFactory::class);
		$factory = new ViewModelFactory($devcmsConfig,$serializerFactory,$container);
		return $factory;
	}
}