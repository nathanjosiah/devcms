<?php

namespace DevCms\Table;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

class PagesTableServiceFactory implements FactoryInterface {
	public function createService(ServiceLocatorInterface $serviceLocator) {
		return $this($serviceLocator,'');
	}
	public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null) {
		$hydrator = $serviceLocator->get('DevCms\Entity\Hydrator\PageEntityHydrator');
		$entity = $serviceLocator->get('DevCms\Entity\PageEntity');
		$adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
		$table_name = $serviceLocator->get('config')['devcms']['pages_table_name'];
		$result_set = new HydratingResultSet($hydrator,$entity);
		$table_gateway = new TableGateway($table_name,$adapter,null,$result_set);
		$table = new PagesTable($table_gateway);
		return $table;
	}
}

