<?php
namespace CollectingTogether;

return [
    'view_manager' => [
        'template_path_stack' => [
            sprintf('%s/../view', __DIR__),
        ],
    ],
    'controllers' => [
        'invokables' => [
            'CollectingTogether\Controller\Site\Form' => Controller\Site\FormController::class,
        ],
    ],
    'block_layouts' => [
        'invokables' => [
            'collectingTogetherForm' => Site\BlockLayout\CollectingTogetherForm::class,
        ],
    ],
    'router' => [
        'routes' => [
            'site' => [
                'child_routes' => [
                    'collecting-together' => [
                        'type' => \Laminas\Router\Http\Segment::class,
                        'options' => [
                            'route' => '/collecting-together/:controller[/:action]',
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                '__NAMESPACE__' => 'CollectingTogether\Controller\Site',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
