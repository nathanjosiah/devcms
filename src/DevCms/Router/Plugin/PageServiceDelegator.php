<?php

namespace DevCms\Router\Plugin;

use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PageServiceDelegator implements DelegatorFactoryInterface {
	public function createDelegatorWithName(ServiceLocatorInterface $routePluginManager,$name,$requestedName,$callback) {
		$serviceLocator = $routePluginManager->getServiceLocator();

		/* @var $plugin \DevCms\Router\Plugin\Page */
		$plugin = call_user_func($callback);
		$plugin->setPagesTable($serviceLocator->get('DevCms\Table\PagesTable'));
		return $plugin;
	}
}