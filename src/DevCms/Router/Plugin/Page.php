<?php

namespace DevCms\Router\Plugin;

use Zend\Mvc\Router\Http\Segment;
use Zend\Stdlib\RequestInterface;
use DevCms\Table\PagesTable;

class Page extends Segment {
	protected $pagesTable;

	public function match(RequestInterface $request) {
		$match = parent::match($request);
		if(!$match) {
			return null;
		}

		$page_slug = $match->getParam('page_slug');

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

