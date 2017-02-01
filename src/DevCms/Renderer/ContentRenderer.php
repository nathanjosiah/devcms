<?php

namespace DevCms\Renderer;

use DevCms\Entity\ContentEntity;
use Zend\Filter\FilterChain;
class ContentRenderer {
	protected $filterChain;
	public function __construct(FilterChain $filter_chain) {
		$this->filterChain = $filter_chain;
	}
	public function render(ContentEntity $content) {
		$filtered = $this->filterChain->filter($content->content);
		return $filtered;
	}
}