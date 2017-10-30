<?php

namespace DevCms\Controller;

use DevCms\Form\PageFormFactory;
use DevCms\Model\Variable\Serializer\SerializerFactory;
use DevCms\Table\PagesTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DevCms\Entity\ContentEntity;
use DevCms\Entity\PageEntity;
use Zend\Form\FormInterface;

class PageAdminController extends AbstractActionController {
	/**
	 * @var PagesTable
	 */
	private $pagesTable;
	/**
	 * @var PageFormFactory
	 */
	private $pageFormFactory;
	/**
	 * @var array
	 */
	private $config;
	/**
	 * @var SerializerFactory
	 */
	private $serializerFactory;

	public function __construct(PagesTable $pagesTable, PageFormFactory $pageFormFactory, array $config,SerializerFactory $serializerFactory) {
		$this->pagesTable = $pagesTable;
		$this->pageFormFactory = $pageFormFactory;
		$this->config = $config;
		$this->serializerFactory = $serializerFactory;
	}

	public function listAction() {
		$pages = $this->pagesTable->fetchAll();
		$vm = new ViewModel([
			'pages' => $pages,
			'layouts' => $this->config['layouts'],
			'layout_categories' => $this->config['layout_categories'],
		]);
		$vm->setTemplate('devcms/admin/page/list');
		return $vm;
	}

	public function createAction() {
		$page = new PageEntity();

		if($this->request->isPost()) {
			$form = $this->pageFormFactory->createWithLayoutId($this->request->getPost('layout'));
			$this->processForm($form,$page);
			return $this->Redirect()->toRoute('devcms-admin/page/edit',['id'=>$page->id]);
		}
		else {
			$form = $this->pageFormFactory->create();
		}

		$vm = new ViewModel([
			'form' => $form,
			'page' => $page
		]);
		$vm->setTemplate('devcms/admin/page/edit');
		return $vm;
	}

	public function editAction() {
		$page = $this->pagesTable->fetchWithId($this->Params('id'));
		if(!$page) {
			return $this->notFoundAction();
		}

		$layout = $this->request->getQuery('layout',$page->layout);
		$form = $this->pageFormFactory->createWithLayoutId($layout);

		if($this->request->isPost()) {
			$this->processForm($form,$page);
		}
		else {
			$form->get('slug')->setValue($page->slug);
			$form->get('label')->setValue($page->label);
			$fvars = $form->get('vars');
			foreach($page->variables as $var_name => $variable) {
				// may have been removed from the config
				if(empty($this->config['layouts'][$layout]['variables'][$var_name])) {
					continue;
				}
				$var_config = $this->config['layouts'][$layout]['variables'][$var_name];
				$type_config = $this->config['variable_types'][$var_config['type']];

				if($fvars->has($var_name)) {
					/**
					 * @var \Zend\Hydrator\HydrationInterface $hydrator
					 */
					$hydrator = $this->serviceLocator->get($type_config['hydrator']);
					$content_field = $fvars->get($var_name)->get('content');
					$hydrator->hydrate(['content' => $variable->content],$content_field);
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

	public function deleteAction() {
		$page = $this->pagesTable->fetchWithId($this->Params('id'));
		if(!$page) {
			return $this->notFoundAction();
		}
		$this->pagesTable->deleteWithId($page->id);
		return $this->Redirect()->toRoute('devcms-admin/page/list');
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
			$type_config = $this->config['variable_types'][$this->config['layouts'][$page->layout]['variables'][$var_name]['type']];
			/**
			 * @var \DevCms\Model\Variable\Serializer\SerializerInterface $serializer
			 */
			$serializer = $this->serializerFactory->get($type_config['serializer']);
			$var->content = $serializer->serialize($var_data['content']);
			$vars[$var_name] = $var;
		}
		$page->variables = $vars;
		$this->pagesTable->savePage($page);
	}
}
