<?php

namespace DevCms\Entity;

class PageEntity {
	public $id,$template,$viewModelKey,$label;

	/**
	 * @var \DevCms\Entity\ContentEntity[]
	 */
	public $variables;
}
