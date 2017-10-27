<?php

namespace DevCms\Model\Variable\Serializer;


use Zend\ServiceManager\ServiceLocatorInterface;

class SerializerFactory {
	/**
	 * @var ServiceLocatorInterface
	 */
	private $serviceLocator;

	public function __construct(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}

	public function get($name) : SerializerInterface {
		return $this->serviceLocator->get($name);
	}

}