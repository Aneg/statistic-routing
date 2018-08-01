<?php

/**
 * Конфиг модуля v1
 *
 * @author cawa
 */

namespace v1;

use v1\Factory\ParserDataFactory;

return [
    'router' => [
        'routes' => [
            'api' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/v1/[:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*/?',
                    ],
                    'defaults' => [
                        '__NAMESPACE__' => 'v1\Controller',
                        'controller' => 'v1\Controller\Index',
                        'action' => 'index'
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => array(
        'factories' => array(
            'ParserData' => ParserDataFactory::class,
        )
    ),
    'controllers' => [
        'invokables' => [
            'v1\Controller\Index' => 'v1\Controller\IndexController',
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5'
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ],
    ],
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
];