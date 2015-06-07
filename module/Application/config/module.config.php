<?php

return array(
    'items_per_page' => 1,
    'router' => array(
        'routes' => array(
            'contact' => array(
                'type' => 'segment',
                'options' => array(
                    'route' 
                        => '/[contacts][/:action][/:id][/page/:page]'
                            . '[/order_by/:order_by][/:order]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)(?!\border_by\b)'
                            . '[a-zA-Z][a-zA-Z-]*',
                        'id' => '[0-9]+',
                        'page' => '[0-9]+',
                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order' => 'ASC|DESC'
                    ),
                    'defaults' => array(
                        'controller' => 'contact_controller',
                        'action' => 'list'
                    ),
                ),
            ),
            'category' => array(
                'type' => 'segment',
                'options' => array(
                    'route' 
                        => '/category[/:action][/:id][/page/:page]'
                            . '[/order_by/:order_by][/:order]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)(?!\border_by\b)'
                            . '[a-zA-Z][a-zA-Z-]*',
                        'id' => '[0-9]+',
                        'page' => '[0-9]+',
                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order' => 'ASC|DESC'
                    ),
                    'defaults' => array(
                        'controller' => 'category_controller',
                        'action' => 'list'
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'contact_table'
                => 'Application\Factory\ContactTableFactory',
            'contact_input_filter'
                => 'Application\Factory\ContactInputFilterFactory',
            'category_table'
                => 'Application\Factory\CategoryTableFactory',
            'category_input_filter'
                => 'Application\Factory\CategoryInputFilterFactory',
        ),        
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'contact_controller' 
                => 'Application\Factory\ContactControllerFactory',
            'category_controller' 
                => 'Application\Factory\CategoryControllerFactory',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'partial/paginator'       => __DIR__ . '/../view/partial/paginator.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
