<?php

namespace RabbitCMS\Settings\Providers;

use Illuminate\Foundation\AliasLoader;
use RabbitCMS\Settings\Manager;
use RabbitCMS\Settings\Repository;
use RabbitCMS\Settings\Support\Facade\SettingsFacade;

class ModuleProvider extends \RabbitCMS\Carrot\Providers\ModuleProvider
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        parent::register();

        $this->app->singleton(Repository::class);
        $this->app->singleton(Manager::class);

        AliasLoader::getInstance()->alias('Settings', SettingsFacade::class);
    }

    /**
     * @inheritdoc
     */
    protected function name()
    {
        return 'settings';
    }
}