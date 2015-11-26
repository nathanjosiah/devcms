<?php

namespace DevCms\Entity\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use DevCms\Table\ContentBlocksTable;

class ContentBlocksStrategy implements StrategyInterface {
	protected $contentBlocksTable;

	public function __construct(ContentBlocksTable $contentBlocksTable) {
		$this->contentBlocksTable = $contentBlocksTable;
	}

	public function extract($value) {
		if(empty($value)) return [];

		$ids = [];
		foreach($value as $block) {
			$ids[] = $block->id;
			$this->contentBlocksTable->setContent($block->id,$block->content);
		}
		return json_encode($ids);
	}

	public function hydrate($value) {
		if(empty($value)) return [];
		if(is_string($value)) $value = json_decode($value,true);

		$blocks = $this->contentBlocksTable->fetchWithIds($value);
		return $blocks;
	}
}
