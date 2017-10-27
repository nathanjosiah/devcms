<?php

namespace DevCms\Table;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

class ContentBlocksTableServiceFactory implements FactoryInterface {

	public function createService(ServiceLocatorInterface $serviceLocator) {
		return $this($serviceLocator,'');
	}
	public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null) {
		$hydrator = $serviceLocator->get('DevCms\Entity\Hydrator\ContentEntityHydrator');
		$entity = $serviceLocator->get('DevCms\Entity\ContentEntity');
		$adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
		$table_name = $serviceLocator->get('config')['devcms']['content_table_name'];
		$result_set = new HydratingResultSet($hydrator,$entity);
		$table_gateway = new TableGateway($table_name,$adapter,null,$result_set);
		$table = new ContentBlocksTable($table_gateway);
		return $table;
	}
}
