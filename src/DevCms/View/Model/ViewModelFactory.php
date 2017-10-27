<?php
namespace DevCms\View\Model;

use DevCms\Entity\PageEntity;
use DevCms\Model\Variable\Serializer\SerializerFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;

class ViewModelFactory {
	/**
	 * @var array
	 */
	private $config;
	/**
	 * @var ServiceLocatorInterface
	 */
	private $serviceLocator;
	/**
	 * @var SerializerFactory
	 */
	private $serializerFactory;

	public function __construct(array $config, SerializerFactory $serializerFactory, ServiceLocatorInterface $serviceLocator) {
		$this->config = $config;
		$this->serviceLocator = $serviceLocator;
		$this->serializerFactory = $serializerFactory;
	}

	public function createWithPage(PageEntity $page) : ViewModel {
		if($page->layout) {
			$layout_config = $this->config['layouts'][$page->layout];

			if(empty($layout_config['view_model_key'])) {
				$vm = new ViewModel();
			}
			else {
				$vm = $this->serviceLocator->get($layout_config['view_model_key']);
			}

			if(!empty($layout_config['template'])) {
				$vm->setTemplate($layout_config['template']);
			}
		}

		if(!empty($page->variables)) {
			foreach($page->variables as $key => $content_block) {
				$type_config = $this->config['variable_types'][$this->config['layouts'][$page->layout]['variables'][$key]['type']];
				/**
				 * @var \DevCms\Model\Variable\Serializer\SerializerInterface $serializer
				 */
				$serializer = $this->serializerFactory->get($type_config['serializer']);
				$content = $serializer->unserialize($content_block->content);
				$vm->setVariable($key,$content);
			}
		}

		return $vm;
	}
}