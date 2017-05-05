<?php

namespace DevCms\Entity;

class PageEntity {
	public $id,$slug,$label,$layout;

	/**
	 * @var \DevCms\Entity\ContentEntity[]
	 */
	public $variables;
}
