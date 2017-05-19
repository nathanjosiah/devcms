<?php

namespace DevCms\Model\Variable\Serializer;

class Passthrough implements SerializerInterface {
	public function serialize($content) {
		return $content;
	}

	public function unserialize($content) {
		return $content;
	}
}

