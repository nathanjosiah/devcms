<?php

namespace DevCms\Table;

use Zend\Db\TableGateway\TableGateway;
class ContentBlockTable extends TableGateway{
	/**
	 * @param string $id
	 * @return \DevCms\Entity\ContentEntity
	 */
	public function fetchWithId($id) {
		$result = $this->select(['id'=>$id]);
		return $result->current();
	}

	/**
	 * @param string $id The id to set
	 * @param string $content The content to set it to
	 */
	public function setContent($id,$content) {
		if($this->fetchWithId($id)) {
			$this->update(['html'=>$content],['id'=>$id]);
		}
		else {
			$this->insert(['html'=>$content]);
		}
		return $this;
	}
}