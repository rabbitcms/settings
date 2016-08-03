<?php

namespace RabbitCMS\Settings\Providers;

use RabbitCMS\Settings\Repository;

class ModuleProvider extends \RabbitCMS\Carrot\Providers\ModuleProvider
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        parent::register();

        $this->app->singleton(Repository::class);
    }

    /**
     * @inheritdoc
     */
    protected function name()
    {
        return 'settings';
    }
}