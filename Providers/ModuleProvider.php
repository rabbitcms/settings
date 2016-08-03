<?php

namespace RabbitCMS\Settings\Providers;

class ModuleProvider extends \RabbitCMS\Carrot\Providers\ModuleProvider
{
    /**
     * @inheritdoc
     */
    protected function name() {
        return 'settings';
    }
}