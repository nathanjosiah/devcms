<?php

namespace DevCms\Table;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\ResultSet\HydratingResultSet;

class PagesTableServiceFactory implements FactoryInterface {
	public function createService(ServiceLocatorInterface $serviceLocator) {
		$hydrator = $serviceLocator->get('DevCms\Entity\Hydrator\PageEntityHydrator');
		$entity = $serviceLocator->get('DevCms\Entity\PageEntity');
		$adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
		$table_name = $serviceLocator->get('config')['devcms']['pages_table_name'];
		$result_set = new HydratingResultSet($hydrator,$entity);
		$table = new PagesTable($table_name,$adapter,null,$result_set);
		return $table;
	}
}

