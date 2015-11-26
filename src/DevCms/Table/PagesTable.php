<?php

namespace DevCms\Table;

use Zend\Db\TableGateway\TableGateway;
use DevCms\Entity\PageEntity;

class PagesTable extends TableGateway {
	public function fetchWithId($id) {
		$result = $this->select(['id' => $id]);
		return $result->current();
	}

	public function savePage(PageEntity $page) {
		$data = $this->resultSetPrototype->getHydrator()->extract($page);
		if($this->fetchWithId($page->id)) {
			$this->update($data,['id'=>$page->id]);
		}
		else {
			$this->insert($data);
		}
		return $this;
	}
}
