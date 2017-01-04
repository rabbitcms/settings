<?php

use Illuminate\Routing\Router;
use RabbitCMS\Backend\Support\Backend;

return [
    'boot'     => function (Backend $backend) {
        $backend->addMenuResolver(
            function (Backend $backend) {
                $backend->addMenu('system', 'settings', trans('settings::common.settings'), route('backend.settings.view'), 'fa fa-angle-double-right', ['system.settings.read']);
            }
        );

        $backend->addAclResolver(
            function (Backend $backend) {
                $backend->addAcl('system', 'settings.read', trans('settings::acl.settings.read'));
                $backend->addAcl('system', 'settings.write', trans('settings::acl.settings.write'));
            }
        );
    },
    'requirejs' => [
        'packages' => [
            'rabbitcms.settings' => [
                'location' => 'js',
                'main'     => 'settings',
            ],
        ],
    ],
    'handlers' => [
        '' => [
            'module'   => 'rabbitcms.settings',
            'menuPath' => 'system.settings',
        ],
    ],
];