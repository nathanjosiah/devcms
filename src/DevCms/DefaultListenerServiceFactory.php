<?php

namespace DevCms;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\FactoryInterface;

class DefaultListenerServiceFactory implements ListenerAggregateInterface,FactoryInterface {
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
		// These default listener classes in the form of factories are the only userland code that executes before the router
		// config is merged into the router. This means that dynamically added routes, or routes with custom plugins
		// must be added through this fairly hacky way by way of a default listener that is obtained through a ServiceManager factory.
		// Even then, it is only made possible because the factory interface requires a ServiceLocator to be injected.
		$router = $serviceLocator->get('Router');
		$route_plugin_manager = $router->getRoutePluginManager();
		$route_plugin_manager->setInvokableClass('CmsPage','DevCms\Router\Plugin\Page');
		$route_plugin_manager->addDelegator('CmsPage','DevCms\Router\Plugin\PageServiceDelegator');
		$router->addRoute('devcms_page',[
			'type' => 'CmsPage',
			'options' => [
				'route' => '/content/:page_id',
				'constraints' => [
					'page_id' => '[a-zA-Z0-9][a-zA-Z0-9-]+'
				],
				'defaults' => [
					'controller' => 'DevCms\Controller\PageController'
				]
			]
		]);
		return $this;
	}

	// We don't actually care about the events. We only use this listener/factory to be able to add the route plugin dynamically
	public function detach(EventManagerInterface $events) {}
	public function attach(EventManagerInterface $events) {}
}
