<?php
return [
    'groups' => [
        'system' => [
            'caption'  => trans('settings::common.system'),
            'priority' => PHP_INT_MAX - 1,
            'tab'      => 'system',
        ],
        'other'  => [
            'caption'  => trans('settings::common.other'),
            'priority' => PHP_INT_MAX,
            'tab'      => 'other',
        ],
    ],
];