<?php

namespace DevCms\Model\Variable\Hydrator;

use Zend\Hydrator\HydrationInterface;

class Value implements HydrationInterface {
	public function hydrate(array $data,$object) {
		$object->setValue($data['content']);
	}
}

