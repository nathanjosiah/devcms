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
		'content_filters' => [],
		'content_blocks' => [
			/*
			'foo' => [
				'label' => 'Header tagline',
				'default_value' => 'optional',
			],
			'foo-bar' => [
				'label' => 'Other thang'
			]*/
		],
		/*
		'layouts' => [
			'my-foo-bar' => [
				'label' => 'Two Column Layout',
				'template' => 'partial/cms/my-template',
				'variables' => [
					[
						'name' => 'left-col',
						'label' => 'Left Column',
						'required' => true
					],
					[
						'name' => 'right-col',
						'label' => 'Left Column',
						'required' => false
					]
				]
			]
		]*/
	],
	'router' => [
		'routes' => [
			'devcms-admin' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/devcms-admin'
				],
				'may_terminate' => true,
				'child_routes' => [
					'content-block' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/content-block'
						],
						'may_terminate' => true,
						'child_routes' => [
							'list' => [
								'type' => 'Literal',
								'options' => [
									'route' => '/list',
									'defaults' => [
										'controller' => 'DevCms\Controller\ContentBlockAdminController',
										'action' => 'list'
									]
								]
							],
							'edit' => [
								'type' => 'Segment',
								'options' => [
									'route' => '/edit/:id',
									'defaults' => [
										'controller' => 'DevCms\Controller\ContentBlockAdminController',
										'action' => 'edit'
									]
								]
							]
						]
					]
				]
			]
		]
	],
	'controllers' => [
		'invokables' => [
			'DevCms\Controller\ContentBlockAdminController' => 'DevCms\Controller\ContentBlockAdminController',
		]
	],
	'view_manager' => [
		'template_map' => [
			'devcms/admin/content-block/list' => __DIR__ . '/../view/pages/content-block-admin/list.phtml',
			'devcms/admin/content-block/edit' => __DIR__ . '/../view/pages/content-block-admin/edit.phtml',
		]
	],
	'service_manager' => [
		'invokables' => [
			'DevCms\Entity\Hydrator\ContentEntityHydrator' => 'DevCms\Entity\Hydrator\ContentEntityHydrator',
			'DevCms\Entity\ContentEntity' => 'DevCms\Entity\ContentEntity'
		],
		'factories' => [
			'DevCms\Renderer\ContentRenderer' => 'DevCms\Renderer\ContentRendererServiceFactory',
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