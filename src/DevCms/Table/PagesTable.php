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
	 * @param int $id
	 * @return PageEntity
	 */
	public function fetchWithId(int $id) {
		$result = $this->tableGateway->select(['id' => $id]);
		return $result->current();
	}

	public function deleteWithId(int $id) {
		$result = $this->tableGateway->delete(['id' => $id]);
	}

	/**
	 * @param string $slug
	 * @return PageEntity
	 */
	public function fetchWithSlug(string $slug) {
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
