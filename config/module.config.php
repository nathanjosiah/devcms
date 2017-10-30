<?php
use Zend\Mvc\Router\RouteInvokableFactory;
use \Zend\ServiceManager\Factory\InvokableFactory;

return [
	'controllers' => [
		'factories' => [
			// invokables
			DevCms\Controller\AdminHomeController::class => InvokableFactory::class,

			// real factories
			DevCms\Controller\ContentBlockAdminController::class => DevCms\Controller\ContentBlockAdminControllerServiceFactory::class,
			DevCms\Controller\PageAdminController::class => DevCms\Controller\PageAdminControllerServiceFactory::class,
			DevCms\Controller\PageController::class => DevCms\Controller\PageControllerServiceFactory::class,
		]
	],
	'route_manager' => [
		'factories' => [
			DevCms\Router\Plugin\Page::class => RouteInvokableFactory::class,
		],
		'delegators' => [
			DevCms\Router\Plugin\Page::class => [
				DevCms\Router\Plugin\PageServiceDelegator::class,
			]
		]
	],
	'service_manager' => [
		'aliases' => [
			'DevCms\Model\Variable\Hydrator\Strategy\Default' => Zend\Hydrator\Strategy\DefaultStrategy::class,
			'DevCms\Model\Variable\Hydrator\Checkbox' => DevCms\Model\Variable\Hydrator\Value::class,
			'DevCms\Model\Variable\Hydrator\Textarea' => DevCms\Model\Variable\Hydrator\Value::class,
			'DevCms\Model\Variable\Hydrator\Textbox' => DevCms\Model\Variable\Hydrator\Value::class,
		],
		'factories' => [
			// Invokables
			Zend\Hydrator\Strategy\DefaultStrategy::class => InvokableFactory::class,
			DevCms\Entity\Hydrator\ContentEntityHydrator::class => InvokableFactory::class,
			DevCms\Entity\ContentEntity::class => InvokableFactory::class,
			DevCms\Entity\PageEntity::class => InvokableFactory::class,
			DevCms\Entity\Hydrator\Strategy\ContentBlocksStrategy::class => InvokableFactory::class,
			DevCms\Model\Variable\Serializer\Passthrough::class => InvokableFactory::class,
			DevCms\Model\Variable\Hydrator\Value::class => InvokableFactory::class,

			// Real factories
			DevCms\Model\Variable\Serializer\SerializerFactory::class => DevCms\Model\Variable\Serializer\SerializerFactoryServiceFactory::class,
			DevCms\Renderer\ContentRenderer::class => DevCms\Renderer\ContentRendererServiceFactory::class,
			DevCms\Table\ContentBlocksTable::class => DevCms\Table\ContentBlocksTableServiceFactory::class,
			DevCms\Table\PagesTable::class => DevCms\Table\PagesTableServiceFactory::class,
			DevCms\Entity\Hydrator\PageEntityHydrator::class => DevCms\Entity\Hydrator\PageEntityHydratorServiceFactory::class,
			DevCms\Cache\ContentCache::class => DevCms\Cache\ContentCacheServiceFactory::class,
			DevCms\Form\PageFormFactory::class => DevCms\Form\PageFormFactoryFactory::class,
			DevCms\Model\Variable\Form\Factory::class => DevCms\Model\Variable\Form\FactoryFactory::class,
			DevCms\View\Model\ViewModelFactory::class => DevCms\View\Model\ViewModelFactoryServiceFactory::class,
		]
	],
	'view_helpers' => [
		'factories' => [
			DevCms\View\Helper\CmsContent::class => DevCms\View\Helper\CmsContentServiceFactory::class,
		],
		'aliases' => [
			'CmsContent' => DevCms\View\Helper\CmsContent::class,
		]
	],
	'devcms' => [
		'content_table_name' => 'cms_content',
		'pages_table_name' => 'cms_pages',
		'cache_storage' => [
			// Set to blackhole to disable cache
			'adapter' => 'blackhole',
			'options' => [
			]
		],
		// May be normal filter names or normal SM keys.
		'content_filters' => [],
		'content_blocks' => [
		],
		'layout_categories' => [
		],
		'layouts' => [
		],
		'variable_types' => [
			'checkbox' => [
				'element' => 'Checkbox',
				'partial' => 'partial/devcms/element/checkbox',
				'hydrator' => 'DevCms\Model\Variable\Hydrator\Checkbox',
				'serializer' => 'DevCms\Model\Variable\Serializer\Passthrough',
				'input_filter' => [
					// This can either be element_spec, options, or service_locator_key
					'options' => [
						'filters' => [
							['name' => 'StringTrim'],
							['name' => 'ToInt'],
						]
					],
				],
			],
			'textarea' => [
				'element' => 'Textarea',
				'partial' => 'partial/devcms/element/textarea',
				'hydrator' => 'DevCms\Model\Variable\Hydrator\Textarea',
				'serializer' => 'DevCms\Model\Variable\Serializer\Passthrough',
				'input_filter' => [
					'options' => [
						'filters' => [
							['name' => 'StringTrim'],
						]
					],
				],
			],
			'wysiwyg' => [
				'element' => 'Textarea',
				'partial' => 'partial/devcms/element/wysiwyg',
				'hydrator' => 'DevCms\Model\Variable\Hydrator\Textarea',
				'serializer' => 'DevCms\Model\Variable\Serializer\Passthrough',
				'input_filter' => [
					'options' => [
						'filters' => [
							['name' => 'StringTrim'],
						]
					],
				],
			],
			'textbox' => [
				'element' => 'Text',
				'partial' => 'partial/devcms/element/textbox',
				'hydrator' => 'DevCms\Model\Variable\Hydrator\Textbox',
				'serializer' => 'DevCms\Model\Variable\Serializer\Passthrough',
				'input_filter' => [
					'options' => [
						'filters' => [
							['name' => 'StringTrim'],
						]
					],
				],
			],
		],
	],
	'router' => [
		'routes' => [
			'devcms-page' => [
				'priority' => 1000,
				'type' => DevCms\Router\Plugin\Page::class,
				'options' => [
					'route' => '/:page_slug',
					'constraints' => [
						'page_slug' => '.*'
					],
					'defaults' => [
						'controller' => 'DevCms\Controller\PageController'
					]
				]
			],
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
							],
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
							],
							'delete' => [
								'type' => 'Segment',
								'options' => [
									'route' => '/delete/:id',
									'defaults' => [
										'controller' => 'DevCms\Controller\PageAdminController',
										'action' => 'delete'
									]
								]
							],
							'create' => [
								'type' => 'Segment',
								'options' => [
									'route' => '/create',
									'defaults' => [
										'controller' => 'DevCms\Controller\PageAdminController',
										'action' => 'create'
									]
								]
							],
						]
					]
				]
			]
		],
	],
	'view_manager' => [
		'template_map' => [
			'devcms/admin/home' => __DIR__ . '/../view/pages/admin-home.phtml',
			'devcms/admin/content-block/list' => __DIR__ . '/../view/pages/content-block-admin/list.phtml',
			'devcms/admin/content-block/edit' => __DIR__ . '/../view/pages/content-block-admin/edit.phtml',
			'devcms/admin/page/list' => __DIR__ . '/../view/pages/page-admin/list.phtml',
			'devcms/admin/page/edit' => __DIR__ . '/../view/pages/page-admin/edit.phtml',
			'layout/devcms/admin' => __DIR__ . '/../view/templates/admin-layout.phtml',
			'partial/devcms/element/checkbox' => __DIR__ . '/../view/partials/elements/checkbox.phtml',
			'partial/devcms/element/textarea' => __DIR__ . '/../view/partials/elements/textarea.phtml',
			'partial/devcms/element/textbox' => __DIR__ . '/../view/partials/elements/textbox.phtml',
			'partial/devcms/element/dropdown' => __DIR__ . '/../view/partials/elements/dropdown.phtml',
			'partial/devcms/element/wysiwyg' => __DIR__ . '/../view/partials/elements/wysiwyg.phtml',
			'partial/my-cms-template-configurable' => __DIR__ . '/../view/mytemplate.phtml',
		]
	],
];