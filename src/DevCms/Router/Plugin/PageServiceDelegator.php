<?php

namespace DevCms\Router\Plugin;

use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PageServiceDelegator implements DelegatorFactoryInterface {

	public function __invoke(\Interop\Container\ContainerInterface $routePluginManager, $requestedName, callable $callback, array $options = null) {
		// ZF3 prep
		$serviceLocator = (is_subclass_of($routePluginManager,\Zend\ServiceManager\ServiceManager::class) ? $routePluginManager->getServiceLocator() : $routePluginManager);

		/* @var $plugin \DevCms\Router\Plugin\Page */
		$plugin = $callback();
		$plugin->setPagesTable($serviceLocator->get('DevCms\Table\PagesTable'));
		return $plugin;
	}

	public function createDelegatorWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName, $callback) {
		return $this($serviceLocator, $requestedName, $callback);
	}
}