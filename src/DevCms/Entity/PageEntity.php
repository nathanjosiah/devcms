<?php

namespace DevCms\Entity;

class PageEntity {
	public $id,$viewModelKey,$label,$layout;

	/**
	 * @var \DevCms\Entity\ContentEntity[]
	 */
	public $variables;
}
