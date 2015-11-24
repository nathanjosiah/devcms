<?php

namespace DevCms\Router\Plugin;

use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\Db\Sql\Sql;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\ResultSet\HydratingResultSet;

class PageServiceDelegator implements DelegatorFactoryInterface {
	public function createDelegatorWithName(ServiceLocatorInterface $routePluginManager,$name,$requestedName,$callback) {
		$serviceLocator = $routePluginManager->getServiceLocator();

		/* @var $plugin \DevCms\Router\Plugin\Page */
		$plugin = call_user_func($callback);

		$sql = new Sql($serviceLocator->get('Zend\Db\Adapter\Adapter'));
		$table_name = $serviceLocator->get('Config')['devcms']['pages_table_name'];
		//$result_set = new HydratingResultSet($hydrator,$page_entity);

		//$plugin->setSql($sql);
		//$plugin->setTableName($table_name);
		//$plugin->setResultSetPrototype($result_set);

		return $plugin;
	}
}