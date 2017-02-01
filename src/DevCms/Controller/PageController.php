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
			$vm = $this->getServiceLocator()->get($page->viewModelKey);
		}
		else {
			$vm = new ViewModel();
		}

		if($page->template) {
			$vm->setTemplate($page->template);
		}

		if($page->layout) {
			$this->Layout($page->layout);
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
