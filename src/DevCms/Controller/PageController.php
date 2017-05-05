<?php

namespace DevCms\Controller;

use Zend\Mvc\Controller\AbstractController;
use Zend\View\Model\ViewModel;

class PageController extends AbstractController {
	public function onDispatch(\Zend\Mvc\MvcEvent $e) {
		$route_match = $e->getRouteMatch();

		/* @var $page \DevCms\Entity\PageEntity */
		$page = $route_match->getParam('page');

		if($page->viewModelKey) {
			$vm = $this->serviceLocator->get($page->viewModelKey);
		}
		else {
			$vm = new ViewModel();
		}

		if($page->layout) {
			$devcms_config = $this->serviceLocator->get('Config')['devcms'];
			$layout_config = $devcms_config['layouts'][$page->layout];
			if(!empty($layout_config['layout'])) {
				$this->Layout($layout_config['layout']);
			}

			if(!empty($layout_config['template'])) {
				$vm->setTemplate($layout_config['template']);
			}
		}


		if(!empty($page->variables)) {
			foreach($page->variables as $key => $content_block) {
				$vm->setVariable($key,$content_block->content);
			}
		}
		$e->setResult($vm);
		return $vm;
	}
}
