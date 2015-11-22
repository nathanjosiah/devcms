<?php
return [
	'devcms' => [
		'content_table_name' => 'cms_content',
		'cache_storage' => [
			// Set to blackhole to disable cache
			'adapter' => 'filesystem',
			'options' => [
				'ttl' => 1,
				'cache_dir' => __DIR__ . '/../data/'
			]
		],
		// May be normal filter names or normal SM keys.
		'content_filters' => []
	],
	'service_manager' => [
		'invokables' => [
			'DevCms\Entity\Hydrator\ContentEntityHydrator' => 'DevCms\Entity\Hydrator\ContentEntityHydrator',
			'DevCms\Entity\ContentEntity' => 'DevCms\Entity\ContentEntity'
		],
		'factories' => [
			'DevCms\Renderer\CmsRenderer' => 'DevCms\Renderer\CmsRendererServiceFactory',
			'DevCms\Resolver\DbResolver' => 'DevCms\Resolver\DbResolverServiceFactory',
			'DevCms\Cache\ContentCache' => 'DevCms\Cache\ContentCacheServiceFactory',
		]
	],
	'view_helpers' => [
		'factories' => [
			'CmsContent' => 'DevCms\View\Helper\CmsContentServiceFactory',
		]
	]
];