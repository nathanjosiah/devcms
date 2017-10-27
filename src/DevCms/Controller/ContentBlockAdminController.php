<?php

namespace DevCms\Controller;

use DevCms\Entity\ContentEntity;
use DevCms\Table\ContentBlocksTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DevCms\Form\ContentBlockForm;

class ContentBlockAdminController extends AbstractActionController {
	/**
	 * @var array
	 */
	private $config;
	/**
	 * @var ContentBlocksTable
	 */
	private $blocksTable;
	/**
	 * @var ContentEntity
	 */
	private $contentEntityPrototype;

	public function __construct(array $config, ContentBlocksTable $blocksTable, ContentEntity $contentEntityPrototype) {
		$this->config = $config;
		$this->blocksTable = $blocksTable;
		$this->contentEntityPrototype = $contentEntityPrototype;
	}

	public function listAction() {
		$available_blocks = [];
		if(isset($this->config['content_blocks'])) {
			foreach($this->config['content_blocks'] as $key => $options) {
				$available_blocks[$key] = $options['label'];
			}
		}

		$vm = new ViewModel([
			'available_blocks' => $available_blocks
		]);
		$vm->setTemplate('devcms/admin/content-block/list');
		return $vm;
	}

	public function editAction() {
		$id = $this->params('id');
		if(!isset($this->config['content_blocks'][$id])) {
			return $this->notFoundAction();
		}
		$block_config = $this->config['content_blocks'][$id];
		$block = $this->blocksTable->fetchWithId($id);
		if(!$block) {
			$block = clone $this->contentEntityPrototype;
			$block->id = $id;
			$block->content = (isset($block_config['default_value']) ? $block_config['default_value'] : '');
		}
		$block->label = $block_config['label'];

		$form = ContentBlockForm::fromConfig($block_config,$this->config['variable_types'][$block_config['type']]);

		$request = $this->getRequest();
		$form->setAttribute('action',$this->Url()->fromRoute('devcms-admin/content-block/edit',['id'=>$id]));
		if($request->isPost()) {
			$form->setData($request->getPost());
			if($form->isValid()) {
				$this->blocksTable->setContent($id,$form->getInputFilter()->getValue('content'));
				return $this->Redirect()->toRoute('devcms-admin/content-block/list');
			}
		}
		else {
			$form->get('content')->setValue($block->content);
		}

		$vm = new ViewModel([
			'block' => $block,
			'form' => $form,
		]);
		$vm->setTemplate('devcms/admin/content-block/edit');
		return $vm;
	}
}
