<?php

namespace DevCms\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DevCms\Entity\ContentEntity;

class PageAdminController extends AbstractActionController {
	public function listAction() {
		$sl = $this->getServiceLocator();
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
		$pages_table = $sl->get('DevCms\Table\PagesTable');

		$page = $pages_table->fetchWithId($this->Params('id'));
		if(!$page) {
			return $this->notFoundAction();
		}

		$factory = $sl->get('DevCms\Form\PageFormFactory');
		$form = $factory->createWithLayoutId($this->request->getQuery('layout',$page->layout));

		if($this->request->isPost()) {
			$form->setData($this->request->getPost());
			$form->isValid();
			$data = $form->getData();
			$page->label = $data['label'];
			$page->layout = $data['layout'];
			$vars = [];
			foreach($data['vars'] as $var_name => $var_content) {
				$var = new ContentEntity();
				$var->id = 'page.' . $page->id . '.' . $var_name;
				$var->content = $var_content;
				$vars[$var_name] = $var;
			}
			$page->variables = $vars;
			$pages_table->savePage($page);
		}
		else {
			$form->get('label')->setValue($page->label);
			$fvars = $form->get('vars');
			foreach($page->variables as $var_name => $variable) {
				if($fvars->has($var_name)) {
					$fvars->get($var_name)->setValue($variable->content);
				}
			}
		}

		$vm = new ViewModel([
			'form' => $form,
			'page' => $page
		]);
		$vm->setTemplate('devcms/admin/page/edit');
		return $vm;
	}
}
