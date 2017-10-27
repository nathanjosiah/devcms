<?php

namespace DevCms\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CmsContentServiceFactory implements FactoryInterface {

	public function createService(ServiceLocatorInterface $helperPluginManager) {
		return $this($helperPluginManager,'');
	}
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
		$serviceLocator = (is_subclass_of($container,\Zend\ServiceManager\ServiceManager::class) ? $container->getServiceLocator() : $container);
		$renderer = $serviceLocator->get('DevCms\Renderer\ContentRenderer');
		$storage = $serviceLocator->get('DevCms\Cache\ContentCache');
		$content_block_table = $serviceLocator->get('DevCms\Table\ContentBlocksTable');
		$helper = new CmsContent($content_block_table,$renderer,$storage);
		return $helper;
	}
}
