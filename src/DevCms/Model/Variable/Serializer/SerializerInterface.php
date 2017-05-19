<?php

namespace DevCms\Model\Variable\Serializer;

interface SerializerInterface {
	public function serialize($content);
	public function unserialize($content);
}

