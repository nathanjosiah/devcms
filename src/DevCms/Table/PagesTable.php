<?php

namespace DevCms\Table;

use Zend\Db\TableGateway\TableGateway;
use DevCms\Entity\PageEntity;

final class PagesTable {
	private $tableGateway;

	public function __construct(TableGateway $table_gateway) {
		$this->tableGateway = $table_gateway;
	}
	/**
	 * @return \DevCms\Entity\PageEntity
	 */
	public function fetchWithId($id) {
		$result = $this->tableGateway->select(['id' => $id]);
		return $result->current();
	}

	/**
	 * @return \DevCms\Entity\PageEntity
	 */
	public function fetchWithSlug($slug) {
		$result = $this->tableGateway->select(['slug' => $slug]);
		return $result->current();
	}

	public function fetchAll() {
		return $this->tableGateway->select();
	}

	public function savePage(PageEntity $page) {
		$data = $this->tableGateway->getResultSetPrototype()->getHydrator()->extract($page);
		if($this->fetchWithId($page->id)) {
			$this->tableGateway->update($data,['id'=>$page->id]);
		}
		else {
			$this->tableGateway->insert($data);
			$page->id = $this->tableGateway->getLastInsertValue();
		}
		return $this;
	}
}
