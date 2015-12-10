<?php

namespace DevCms\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AdminHomeController extends AbstractActionController {
	public function indexAction() {
		$vm = new ViewModel();
		$vm->setTemplate('devcms/admin/home');
		return $vm;
	}
}
