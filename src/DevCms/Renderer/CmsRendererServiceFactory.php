<?php

namespace DevCms\Renderer;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Filter\FilterChain;

class CmsRendererServiceFactory implements FactoryInterface {

	public function createService(ServiceLocatorInterface $serviceLocator) {
		$filter_chain = new FilterChain();
		$filter_chain->getPluginManager()->addPeeringServiceManager($serviceLocator);

		$devcms_config = $serviceLocator->get('Config')['devcms'];
		if(isset($devcms_config['content_filters'])) {
			foreach($devcms_config['content_filters'] as $filter_name) {
				if(is_string($filter_name)) {
					$filter_chain->attachByName($filter_name);
				}
				else {
					$filter_chain->attach($filter_name);
				}
			}
		}
		$renderer = new CmsRenderer($filter_chain);
		return $renderer;
	}
}

