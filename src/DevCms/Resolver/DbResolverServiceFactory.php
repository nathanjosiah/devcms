<?php

namespace DevCms\Resolver;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;

class DbResolverServiceFactory implements FactoryInterface {

	public function createService(ServiceLocatorInterface $serviceLocator) {
		$hydrator = $serviceLocator->get('DevCms\Entity\Hydrator\ContentEntityHydrator');
		$entity = $serviceLocator->get('DevCms\Entity\ContentEntity');
		$adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
		$table_name = $serviceLocator->get('config')['devcms']['content_table_name'];
		$result_set = new HydratingResultSet($hydrator,$entity);
		$sql = new Sql($adapter);
		$resolver = new DbResolver($table_name,$sql,$result_set);
		return $resolver;
	}
}
