<?php

use Illuminate\Routing\Router;
use RabbitCMS\Backend\Support\Backend;

return [
    'boot'     => function (Backend $backend) {
        $backend->addMenuResolver(
            function (Backend $backend) {
                $backend->addMenu(
                    'system',
                    'settings',
                    trans('settings::common.Settings'),
                    route('backend.settings.view'),
                    'fa icon-settings',
                    ['system.settings']
                );
            }
        );

        $backend->addAclResolver(
            function (Backend $backend) {
                $backend->addAcl('system', 'settings', trans('settings::common.ReadSettings'));
                $backend->addAcl('system', 'settings.write', trans('settings::common.ModifySettings'));
            }
        );
    },
    'routes'   => function (Router $router) {
        $router->get('', ['as' => 'view', 'uses' => 'Settings@getIndex']);
    },
    'reuirejs' => [
        'packages' => [
            'settings' => [
                'location' => 'js',
                'main'     => 'main',
            ],
        ],
    ],
    'handlers' => [
        '' => [
            'module'   => 'settings',
            'exec'     => 'table',
            'menuPath' => 'system.settings',
        ],
    ],
];