<?php

namespace DevCms\Table;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\ResultSet\HydratingResultSet;

class ContentBlocksTableServiceFactory implements FactoryInterface {

	public function createService(ServiceLocatorInterface $serviceLocator) {
		$hydrator = $serviceLocator->get('DevCms\Entity\Hydrator\ContentEntityHydrator');
		$entity = $serviceLocator->get('DevCms\Entity\ContentEntity');
		$adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
		$table_name = $serviceLocator->get('config')['devcms']['content_table_name'];
		$result_set = new HydratingResultSet($hydrator,$entity);
		$table = new ContentBlocksTable($table_name,$adapter,null,$result_set);
		return $table;
	}
}
