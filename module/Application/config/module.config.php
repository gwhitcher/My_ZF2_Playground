<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            //Pages (needs to be loaded first or else below routes will be overwritten!!!)
            'page' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/[:slug]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*', //Not necessary just for reference
                        'id'     => '[0-9]+', //Not necessary just for reference
                        'slug' => '[a-zA-Z][a-zA-Z0-9_-]*', //Necessary!!!
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'page',
                    ),
                ),
            ),
            //Homepage
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            //About
            'about' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/about',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'about',
                    ),
                ),
            ),
            //RSS
            'rss' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/rss',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'rss',
                    ),
                ),
            ),
            //XML
            'xml' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/xml',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'xml',
                    ),
                ),
            ),
            //JSON
            'json' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/json',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'json',
                    ),
                ),
            ),
            //AJAX
            'ajax' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/ajax',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'ajax',
                    ),
                ),
            ),
            //PDF
            'pdf' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/pdf',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'pdf',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
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
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
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
    // Enable JSON
    'strategies' => array(
        'ViewJsonStrategy',
        'ViewFeedStrategy',
    ),
);
