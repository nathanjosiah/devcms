<?php

namespace DevCms\Entity\Hydrator;

use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Stdlib\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

class ContentEntityHydrator extends ObjectProperty {
	public function __construct() {
		parent::__construct();
		$this->setNamingStrategy(new UnderscoreNamingStrategy());
	}
}
