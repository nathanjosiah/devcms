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
		foreach($value as $key => $block) {
			if($block->id) {
				$this->contentBlocksTable->setContent($block->id,$block->content);
			}
			else {
				$block->id = $this->contentBlocksTable->createContent($block->content);
			}
			$ids[$key] = $block->id;
		}
		return json_encode($ids);
	}

	public function hydrate($value) {
		if(empty($value)) return [];
		if(is_string($value)) $value = json_decode($value,true);

		$rows = $this->contentBlocksTable->fetchWithIds(array_values($value));
		$blocks = [];
		foreach($rows as $row) {
			$blocks[array_search($row->id,$value)] = $row;
		}
		return $blocks;
	}
}
