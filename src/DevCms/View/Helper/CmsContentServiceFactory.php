<?php

namespace DevCms\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CmsContentServiceFactory implements FactoryInterface {

	public function createService(ServiceLocatorInterface $helperPluginManager) {
		$serviceLocator = $helperPluginManager->getServiceLocator();
		$renderer = $serviceLocator->get('DevCms\Renderer\ContentRenderer');
		$storage = $serviceLocator->get('DevCms\Cache\ContentCache');
		$resolver = $serviceLocator->get('DevCms\Resolver\DbResolver');
		$helper = new CmsContent($resolver,$renderer,$storage);
		return $helper;
	}
}
