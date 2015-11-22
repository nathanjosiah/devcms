<?php

namespace DevCms\View\Helper;

use DevCms\Renderer\ContentRenderer;
use Zend\Cache\Storage\StorageInterface;
use Zend\View\Helper\AbstractHelper;
use DevCms\Resolver\DbResolver;
use DevCms\Entity\ContentEntity;
class CmsContent extends AbstractHelper {
	protected $storage,$renderer,$resolver;
	public function __construct(DbResolver $resolver,ContentRenderer $renderer,StorageInterface $storage) {
		$this->storage = $storage;
		$this->renderer = $renderer;
		$this->resolver = $resolver;
	}
	public function __invoke($id) {
		if($this->storage->hasItem($id)) {
			$content = $this->storage->getItem($id);
			return $content;
		}
		$model = $this->resolver->resolveModelWithId($id);
		// Default behavior is to render an empty string when nothing was returned.
		// The id may not have existed which is assumed to be fine.
		// Dev environments may have the id but the production environment may not have
		// the id when the code is deployed.
		if($model instanceof ContentEntity){
			$content = $this->renderer->render($model);
		}
		else {
			$content = '';
		}
		$this->storage->setItem($id,$content);
		return $content;
	}
}