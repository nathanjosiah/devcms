<?php

namespace DevCms\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DevCms\Entity\ContentEntity;
use DevCms\Entity\PageEntity;
use Zend\Form\FormInterface;

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

	public function createAction() {
		$factory = $this->serviceLocator->get('DevCms\Form\PageFormFactory');

		$page = new PageEntity();

		if($this->request->isPost()) {
			$form = $factory->createWithLayoutId($this->request->getPost('layout'));
			$this->processForm($form,$page);
			return $this->Redirect()->toRoute('devcms-admin/page/edit',['id'=>$page->id]);
		}
		else {
			$form = $factory->create();
		}

		$vm = new ViewModel([
			'form' => $form,
			'page' => $page
		]);
		$vm->setTemplate('devcms/admin/page/edit');
		return $vm;
	}

	public function editAction() {
		$pages_table = $this->serviceLocator->get('DevCms\Table\PagesTable');

		$page = $pages_table->fetchWithId($this->Params('id'));
		if(!$page) {
			return $this->notFoundAction();
		}

		$factory = $this->serviceLocator->get('DevCms\Form\PageFormFactory');
		$form = $factory->createWithLayoutId($this->request->getQuery('layout',$page->layout));

		if($this->request->isPost()) {
			$this->processForm($form,$page);
		}
		else {
			$form->get('slug')->setValue($page->slug);
			$form->get('label')->setValue($page->label);
			$fvars = $form->get('vars');
			foreach($page->variables as $var_name => $variable) {
				if($fvars->has($var_name)) {
					$fvars->get($var_name)->get('content')->setValue($variable->content);
					$fvars->get($var_name)->get('id')->setValue($variable->id);
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

	private function processForm(FormInterface $form,PageEntity $page) {
		$form->setData($this->request->getPost());
		$form->isValid();
		$data = $form->getData();
		$page->slug = $data['slug'];
		$page->label = $data['label'];
		$page->layout = $data['layout'];
		$vars = [];
		foreach($data['vars'] as $var_name => $var_data) {
			$var = new ContentEntity();
			if(isset($var_data['id'])) {
				$var->id = $var_data['id'];
			}
			$var->content = $var_data['content'];
			$vars[$var_name] = $var;
		}
		$page->variables = $vars;
		$pages_table = $this->serviceLocator->get('DevCms\Table\PagesTable');
		$pages_table->savePage($page);
	}
}
