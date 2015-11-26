<?php

namespace DevCms\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CmsContentServiceFactory implements FactoryInterface {

	public function createService(ServiceLocatorInterface $helperPluginManager) {
		$serviceLocator = $helperPluginManager->getServiceLocator();
		$renderer = $serviceLocator->get('DevCms\Renderer\ContentRenderer');
		$storage = $serviceLocator->get('DevCms\Cache\ContentCache');
		$content_block_table = $serviceLocator->get('DevCms\Table\ContentBlocksTable');
		$helper = new CmsContent($content_block_table,$renderer,$storage);
		return $helper;
	}
}
