<?php
namespace DevCms\Controller;

use DevCms\Model\Variable\Serializer\SerializerFactory;
use DevCms\View\Model\ViewModelFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Interop\Container\ContainerInterface;

class PageControllerServiceFactory implements FactoryInterface  {
	public function createService(ServiceLocatorInterface $serviceLocator) {
		return $this($serviceLocator,'');
	}

	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
		$container = (is_subclass_of($container,\Zend\ServiceManager\ServiceManager::class) ? $container->getServiceLocator() : $container);
		$devcmsConfig = $container->get('Config')['devcms'];
		$viewModelFactory = $container->get(ViewModelFactory::class);
		$controller = new PageController($viewModelFactory,$devcmsConfig,$container);
		return $controller;
	}
}