<?php

namespace RabbitCMS\Settings\Providers;

use Illuminate\Foundation\AliasLoader;
use RabbitCMS\Modules\ModuleProvider;
use RabbitCMS\Settings\Manager;
use RabbitCMS\Settings\Repository;
use RabbitCMS\Settings\Support\Facade\SettingsFacade;

class SettingsModuleProvider extends ModuleProvider
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        parent::register();

        $this->app->singleton(Repository::class);
        $this->app->singleton(Manager::class);

        AliasLoader::getInstance(['Settings' => SettingsFacade::class]);
    }

    /**
     * @inheritdoc
     */
    protected function name()
    {
        return 'settings';
    }
}