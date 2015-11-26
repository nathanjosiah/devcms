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

		$page_key = $match->getParam('page_id');

		$page = $this->pagesTable->fetchWithId($page_key);

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

