<?php
return [
    'groups' => [
        'system' => [
            'label'    => trans('settings::common.System'),
            'priority' => PHP_INT_MAX - 1,
            'tab'      => 'system',
        ],
        'other'  => [
            'label'    => trans('settings::common.Other'),
            'priority' => PHP_INT_MAX,
            'tab'      => 'other',
        ],
    ],
];