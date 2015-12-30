<?php

namespace DevCms\Entity;

class PageEntity {
	public $id,$template,$viewModelKey,$label,$layout;

	/**
	 * @var \DevCms\Entity\ContentEntity[]
	 */
	public $variables;
}
