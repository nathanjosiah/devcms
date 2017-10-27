<?php

namespace DevCms\Renderer;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Filter\FilterChain;

class ContentRendererServiceFactory implements FactoryInterface {

	public function createService(ServiceLocatorInterface $serviceLocator) {
		return $this($serviceLocator,'');
	}

	public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null) {
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
		$renderer = new ContentRenderer($filter_chain);
		return $renderer;
	}
}

