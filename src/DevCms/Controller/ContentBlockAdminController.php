<?php

namespace DevCms\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DevCms\Entity\ContentEntity;
use DevCms\Form\ContentBlockForm;

class ContentBlockAdminController extends AbstractActionController {
	public function listAction() {
		$sl = $this->getServiceLocator();
		$devcms_config = $sl->get('Config')['devcms'];

		$available_blocks = [];
		if(isset($devcms_config['content_blocks'])) {
			foreach($devcms_config['content_blocks'] as $key => $options) {
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
		$sl = $this->getServiceLocator();
		$resolver = $sl->get('DevCms\Resolver\DbResolver');
		$devcms_config = $sl->get('Config')['devcms'];

		$id = $this->params('id');
		if(!isset($devcms_config['content_blocks'][$id])) {
			return $this->notFoundAction();
		}
		$block_config = $devcms_config['content_blocks'][$id];
		$block = $resolver->resolveModelWithId($id);
		if(!$block) {
			$block = new ContentEntity();
			$block->id = $id;
			$block->html = (isset($block_config['default_value']) ? $block_config['default_value'] : '');
		}
		$block->label = $block_config['label'];

		$form = ContentBlockForm::fromConfig($block_config);

		$request = $this->getRequest();
		$form->setAttribute('action',$this->url()->fromRoute('devcms-admin/content-block/edit',['id'=>$id]));
		if($request->isPost()) {
			$form->setData($request->getPost());
			if($form->isValid()) {
				$resolver->setContent($id,$form->getInputFilter()->getValue('html'));
				return $this->redirect()->toRoute('devcms-admin/content-block/list');
			}
		}
		else {
			$form->get('html')->setValue($block->html);
		}

		$vm = new ViewModel([
			'block' => $block,
			'form' => $form,
		]);
		$vm->setTemplate('devcms/admin/content-block/edit');
		return $vm;
	}
}
