<?php
namespace DevCms\Controller;

use DevCms\Form\PageFormFactory;
use DevCms\Model\Variable\Serializer\SerializerFactory;
use DevCms\Table\PagesTable;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PageAdminControllerServiceFactory implements FactoryInterface  {
	public function createService(ServiceLocatorInterface $serviceLocator) {
		return $this($serviceLocator,'');
	}

	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
		$container = (is_subclass_of($container,\Zend\ServiceManager\ServiceManager::class) ? $container->getServiceLocator() : $container);
		$pagesTable = $container->get(PagesTable::class);
		$pageFormFactory = $container->get(PageFormFactory::class);
		$devcmsConfig = $container->get('Config')['devcms'];
		$serializerFactory = $container->get(SerializerFactory::class);
		$controller = new PageAdminController($pagesTable,$pageFormFactory,$devcmsConfig,$serializerFactory);
		return $controller;
	}
}