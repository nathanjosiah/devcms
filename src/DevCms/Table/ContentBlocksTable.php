<?php

namespace DevCms\Table;

use Zend\Db\TableGateway\TableGateway;
class ContentBlocksTable extends TableGateway{
	/**
	 * @param string $id
	 * @return \DevCms\Entity\ContentEntity
	 */
	public function fetchWithId($id) {
		$result = $this->select(['id'=>$id]);
		return $result->current();
	}

	/**
	 * @param array $ids
	 * @return \DevCms\Entity\ContentEntity[]
	 */
	public function fetchWithIds(array $ids) {
		$select = $this->getSql()->select();
		$select->where->in('id',$ids);
		$result = $this->selectWith($select);
		return $result;
	}

	/**
	 * @param string $id The id to set
	 * @param string $content The content to set it to
	 */
	public function setContent($id,$content) {
		if($this->fetchWithId($id)) {
			$this->update(['content'=>$content],['id'=>$id]);
		}
		else {
			$this->insert(['content'=>$content,'id'=>$id]);
		}
		return $this;
	}
}