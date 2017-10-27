<?php

namespace DevCms\Entity\Hydrator;

use Zend\Hydrator\ObjectProperty;
use Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

class ContentEntityHydrator extends ObjectProperty {
	public function __construct() {
		parent::__construct();
		$this->setNamingStrategy(new UnderscoreNamingStrategy());
	}
}
