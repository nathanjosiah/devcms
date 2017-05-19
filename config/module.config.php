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
use DevCms\Model\Variable\Form\FactoryFactory as VariableFormFactoryFactory;
use DevCms\Form\PageFormFactoryFactory;

return [
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
			'foo' => [
				'label' => 'Header tagline',
				'type' => 'textarea',
			],
			'foo-bar' => [
				'label' => 'Other thang'
			]
		],
		'layout_categories' => [
			[
				'label' => 'Landing Pages',
				'layouts' => ['my-foo-bar','foo-bar']
			],
			[
				'label' => 'Regular Pages',
				'layouts' => ['foo-bar']
			],
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
		],
		'layouts' => [
			'my-foo-bar' => [
				'label' => 'Two Column Layout',
				'layout' => 'layout/landing-page',
				'template' => 'partial/my-cms-template',
				'variables' => [
					'isThing' => [
						'type' => 'checkbox',
						'label' => 'Is a thing?',
						'required' => true
					],
					'leftCol' => [
						'type' => 'textarea',
						'label' => 'Left Column',
						'required' => true
					],
					'rightCol' => [
						'type' => 'textarea',
						'label' => 'Right Column',
						'required' => false
					]
				]
			],
			'foo-bar' => [
				'label' => 'Three Column Layout - editable header',
				'layout' => 'layout/landing-page',
				'template' => 'partial/my-cms-template-configurable',
				'variables' => [
					'header' => [
						'label' => 'Header thing',
						'required' => true
					],
					'leftCol' => [
						'label' => 'Left Column',
						'required' => true
					],
					'rightCol' => [
						'label' => 'Right Column',
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
			'partial/devcms/element/checkbox' => __DIR__ . '/../view/partials/elements/form-row.phtml',
			'partial/devcms/element/textarea' => __DIR__ . '/../view/partials/elements/form-row.phtml',
			'partial/devcms/element/textbox' => __DIR__ . '/../view/partials/elements/form-row.phtml',
			'partial/devcms/element/dropdown' => __DIR__ . '/../view/partials/elements/form-row.phtml',
		]
	],
	'service_manager' => [
		'invokables' => [
			'DevCms\Entity\Hydrator\ContentEntityHydrator' => ContentEntityHydrator::class,
			'DevCms\Entity\ContentEntity' => ContentEntity::class,
			'DevCms\Entity\PageEntity' => PageEntity::class,
			'DevCms\Entity\Hydrator\Strategy\ContentBlocksStrategy' => ContentBlocksStrategy::class,
			'DevCms\Model\Variable\Hydrator\Strategy\Default' => \Zend\Stdlib\Hydrator\Strategy\DefaultStrategy::class,
			'DevCms\Model\Variable\Hydrator\Checkbox' => \DevCms\Model\Variable\Hydrator\Value::class,
			'DevCms\Model\Variable\Hydrator\Textarea' => \DevCms\Model\Variable\Hydrator\Value::class,
			'DevCms\Model\Variable\Serializer\Passthrough' => DevCms\Model\Variable\Serializer\Passthrough::class,
		],
		'factories' => [
			'DevCms\Renderer\ContentRenderer' => ContentRendererServiceFactory::class,
			'DevCms\Table\ContentBlocksTable' => ContentBlocksTableServiceFactory::class,
			'DevCms\Table\PagesTable' => PagesTableServiceFactory::class,
			'DevCms\Entity\Hydrator\PageEntityHydrator' => PageEntityHydratorServiceFactory::class,
			'DevCms\Cache\ContentCache' => ContentCacheServiceFactory::class,
			'DevCms\DefaultListener' => DefaultListenerServiceFactory::class,
			'DevCms\Form\PageFormFactory' => PageFormFactoryFactory::class,
			'DevCms\Model\Variable\Form\Factory' => VariableFormFactoryFactory::class,
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