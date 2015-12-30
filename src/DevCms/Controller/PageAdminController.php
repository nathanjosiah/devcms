<?php

namespace DevCms\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PageAdminController extends AbstractActionController {
	public function listAction() {
		$sl = $this->getServiceLocator();
		/* @var $pages_table \DevCms\Table\PagesTable */
		$pages_table = $sl->get('DevCms\Table\PagesTable');
		$pages = $pages_table->fetchAll();

		$vm = new ViewModel([
			'pages' => $pages
		]);
		$vm->setTemplate('devcms/admin/page/list');
		return $vm;
	}

	public function editAction() {
		$sl = $this->getServiceLocator();
		/* @var $pages_table \DevCms\Table\PagesTable */
		$pages_table = $sl->get('DevCms\Table\PagesTable');

		$page = $pages_table->fetchWithId($this->params('id'));
		if(!$page) {
			return $this->notFoundAction();
		}

		/* @var $form \DevCms\Form\PageFormFactory */
		$factory = $sl->get('DevCms\Form\PageFormFactory');
		$form = $factory->createWithLayoutId($page->layout);

		$vm = new ViewModel([
			'form' => $form,
			'page' => $page
		]);
		$vm->setTemplate('devcms/admin/page/edit');
		return $vm;
	}
}
