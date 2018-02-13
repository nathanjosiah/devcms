<?php

namespace DevCms\Router\Plugin;

use Zend\Stdlib\RequestInterface;
use DevCms\Table\PagesTable;

// ZF3 prep / compatibility
if(class_exists('Zend\Router\Module',true)) {
	class _PageZf3ForwardCompatibility extends \Zend\Router\Http\Segment {

	}
}
else {
	class _PageZf3ForwardCompatibility extends \Zend\Mvc\Router\Http\Segment {

	}
}

class Page extends _PageZf3ForwardCompatibility {
	protected $pagesTable;

	public function match(RequestInterface $request, $pathOffset = null, array $options = []) {
		$match = parent::match($request);
		if(!$match) {
			return null;
		}

		$page_slug = $match->getParam('page_slug');

		if(!$page_slug) {
			return null;
		}

		$page = $this->pagesTable->fetchWithSlug($page_slug);

		if(!$page) {
			return null;
		}

		$match->setParam('page',$page);
		return $match;
	}

	public function setPagesTable(PagesTable $pagesTable) {
		$this->pagesTable = $pagesTable;
		return $this;
	}
}

