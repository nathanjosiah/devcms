<?php

namespace DevCms\Table;

use Zend\Db\TableGateway\TableGateway;

class ContentBlocksTable {
	private $tableGateway;

	public function __construct(TableGateway $table_gateway) {
		$this->tableGateway = $table_gateway;
	}

	/**
	 * @param string $id
	 * @return \DevCms\Entity\ContentEntity
	 */
	public function fetchWithId($id) {
		$result = $this->tableGateway->select(['id'=>$id]);
		return $result->current();
	}

	/**
	 * @param array $ids
	 * @return \DevCms\Entity\ContentEntity[]
	 */
	public function fetchWithIds(array $ids) {
		$select = $this->tableGateway->getSql()->select();
		$select->where->in('id',$ids);
		$result = $this->tableGateway->selectWith($select);
		return $result;
	}

	public function createContent($content) {
		$this->tableGateway->insert(['content'=>$content]);
		return $this->tableGateway->getLastInsertValue();
	}

	/**
	 * @param string $id The id to set
	 * @param string $content The content to set it to
	 */
	public function setContent($id,$content) {
		if($this->fetchWithId($id)) {
			$this->tableGateway->update(['content'=>$content],['id'=>$id]);
		}
		else {
			$this->tableGateway->insert(['content'=>$content]);
		}
		return $this;
	}
}