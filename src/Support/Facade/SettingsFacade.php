<?php

namespace RabbitCMS\Settings\Support\Facade;

use Illuminate\Support\Facades\Facade;
use RabbitCMS\Settings\Repository;

/**
 * Class SettingsFacade.
 */
class SettingsFacade extends Facade
{
    /**
     * @inheritdoc
     */
    protected static function getFacadeAccessor()
    {
        return Repository::class;
    }
}