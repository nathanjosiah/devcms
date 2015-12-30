<?php
use DevCms\Table\PagesTableServiceFactory;
use DevCms\Entity\Hydrator\ContentEntityHydrator;
use DevCms\Entity\ContentEntity;
use DevCms\Renderer\ContentRendererServiceFactory;
use DevCms\Table\ContentBlocksTableServiceFactory;
use DevCms\Cache\ContentCacheServiceFactory;
use DevCms\DefaultListenerServiceFactory;
use DevCms\View\Helper\CmsContentServiceFactory;
use DevCms\Controller\ContentBlockAdminController;
use DevCms\Entity\PageEntity;
use DevCms\Entity\Hydrator\Strategy\ContentBlocksStrategy;
use DevCms\Entity\Hydrator\PageEntityHydratorServiceFactory;
use DevCms\Controller\PageController;
use DevCms\Controller\AdminHomeController;
use DevCms\Controller\PageAdminController;
use DevCms\Form\PageFormFactory;
return [
	'devcms' => [
		'content_table_name' => 'cms_content',
		'pages_table_name' => 'cms_pages',
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
			'foo' => [
				'label' => 'Header tagline',
				'default_value' => 'optional',
			],
			'foo-bar' => [
				'label' => 'Other thang'
			]
		],
		'layouts' => [
			'my-foo-bar' => [
				'label' => 'Two Column Layout',
				// option a: 'template' => 'partial/cms/my-template',
				// option b: 'view_model' => 'service-manager-key',
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
		]
	],
	'router' => [
		'routes' => [
			'devcms-admin' => [
				'type' => 'Literal',
				'options' => [
					'route' => '/devcms-admin',
					'defaults' => [
						'controller' => 'DevCms\Controller\AdminHomeController',
						'action' => 'index'
					]
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
					],
					'page' => [
						'type' => 'Literal',
						'options' => [
							'route' => '/page'
						],
						'may_terminate' => true,
						'child_routes' => [
							'list' => [
								'type' => 'Literal',
								'options' => [
									'route' => '/list',
									'defaults' => [
										'controller' => 'DevCms\Controller\PageAdminController',
										'action' => 'list'
									]
								]
							],
							'edit' => [
								'type' => 'Segment',
								'options' => [
									'route' => '/edit/:id',
									'defaults' => [
										'controller' => 'DevCms\Controller\PageAdminController',
										'action' => 'edit'
									]
								]
							]
						]
					]
				]
			]
		],
	],
	'controllers' => [
		'invokables' => [
			'DevCms\Controller\ContentBlockAdminController' => ContentBlockAdminController::class,
			'DevCms\Controller\PageAdminController' => PageAdminController::class,
			'DevCms\Controller\AdminHomeController' => AdminHomeController::class,
			'DevCms\Controller\PageController' => PageController::class,
		]
	],
	'view_manager' => [
		'template_map' => [
			'devcms/admin/home' => __DIR__ . '/../view/pages/admin-home.phtml',
			'devcms/admin/content-block/list' => __DIR__ . '/../view/pages/content-block-admin/list.phtml',
			'devcms/admin/content-block/edit' => __DIR__ . '/../view/pages/content-block-admin/edit.phtml',
			'devcms/admin/page/list' => __DIR__ . '/../view/pages/page-admin/list.phtml',
			'devcms/admin/page/edit' => __DIR__ . '/../view/pages/page-admin/edit.phtml',
			'layout/devcms/admin' => __DIR__ . '/../view/templates/admin-layout.phtml',
		]
	],
	'service_manager' => [
		'invokables' => [
			'DevCms\Entity\Hydrator\ContentEntityHydrator' => ContentEntityHydrator::class,
			'DevCms\Entity\ContentEntity' => ContentEntity::class,
			'DevCms\Entity\PageEntity' => PageEntity::class,
			'DevCms\Entity\Hydrator\Strategy\ContentBlocksStrategy' => ContentBlocksStrategy::class,
		],
		'factories' => [
			'DevCms\Renderer\ContentRenderer' => ContentRendererServiceFactory::class,
			'DevCms\Table\ContentBlocksTable' => ContentBlocksTableServiceFactory::class,
			'DevCms\Table\PagesTable' => PagesTableServiceFactory::class,
			'DevCms\Entity\Hydrator\PageEntityHydrator' => PageEntityHydratorServiceFactory::class,
			'DevCms\Cache\ContentCache' => ContentCacheServiceFactory::class,
			'DevCms\DefaultListener' => DefaultListenerServiceFactory::class,
			'DevCms\Form\PageFormFactory' => PageFormFactory::class,
		]
	],
	'view_helpers' => [
		'factories' => [
			'CmsContent' => CmsContentServiceFactory::class,
		]
	],
	'listeners' => [
		'DevCms\DefaultListener'
	]
];