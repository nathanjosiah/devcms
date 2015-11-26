<?php

namespace DevCms\Entity\Hydrator;

use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Stdlib\Hydrator\NamingStrategy\UnderscoreNamingStrategy;
use DevCms\Entity\Hydrator\Strategy\ContentBlocksStrategy;
use DevCms\Table\ContentBlocksTable;

class PageEntityHydrator extends ObjectProperty {
	public function __construct(ContentBlocksTable $contentBlocksTable) {
		parent::__construct();
		$this->setNamingStrategy(new UnderscoreNamingStrategy());
		$this->addStrategy('variables',new ContentBlocksStrategy($contentBlocksTable));
	}
}
