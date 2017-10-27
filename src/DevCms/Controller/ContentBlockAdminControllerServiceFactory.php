<?php
namespace DevCms\Controller;

use DevCms\Entity\ContentEntity;
use DevCms\Table\ContentBlocksTable;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Interop\Container\ContainerInterface;

class ContentBlockAdminControllerServiceFactory implements FactoryInterface  {
	public function createService(ServiceLocatorInterface $serviceLocator) {
		return $this($serviceLocator,'');
	}

	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
		$container = (is_subclass_of($container,\Zend\ServiceManager\ServiceManager::class) ? $container->getServiceLocator() : $container);
		$blocksTable = $container->get(ContentBlocksTable::class);
		$devcmsConfig = $container->get('Config')['devcms'];
		$entityPrototype = $container->get(ContentEntity::class);
		$controller = new ContentBlockAdminController($devcmsConfig,$blocksTable,$entityPrototype);
		return $controller;
	}
}