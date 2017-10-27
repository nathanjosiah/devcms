<?php

namespace DevCms\Controller;

use DevCms\Model\Variable\Serializer\SerializerFactory;
use DevCms\View\Model\ViewModelFactory;
use Zend\Mvc\Controller\AbstractController;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;

class PageController extends AbstractController {
	/**
	 * @var array
	 */
	private $config;
	/**
	 * @var ViewModelFactory
	 */
	private $viewModelFactory;

	public function __construct(ViewModelFactory $viewModelFactory, array $config, ServiceLocatorInterface $serviceLocator) {
		$this->config = $config;
		$this->serviceLocator = $serviceLocator;
		$this->viewModelFactory = $viewModelFactory;
	}

	public function onDispatch(\Zend\Mvc\MvcEvent $e) {
		$route_match = $e->getRouteMatch();

		/* @var $page \DevCms\Entity\PageEntity */
		$page = $route_match->getParam('page');

		if($page->layout) {
			$layout_config = $this->config['layouts'][$page->layout];
			if(!empty($layout_config['layout'])) {
				$this->Layout($layout_config['layout']);
			}
		}

		$vm = $this->viewModelFactory->createWithPage($page);

		$e->setResult($vm);
		return $vm;
	}
}
