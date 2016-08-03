<?php

namespace RabbitCMS\Settings;

use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Config\Repository as ConfigRepository;
use RabbitCMS\Settings\Entities\Settings as SettingsEntity;

class Repository extends ConfigRepository
{
    const CACHE_KEY = 'rbc.settings';

    /**
     * @var CacheRepository
     */
    protected $cache;

    /**
     * Settings constructor.
     *
     * @param CacheRepository $cache
     */
    public function __construct(CacheRepository $cache)
    {
        $this->cache = $cache;

        parent::__construct(
            $this->cache->rememberForever(
                self::CACHE_KEY,
                function () {
                    return SettingsEntity::all()->pluck('value', 'name')->all();
                }
            )
        );
    }

    /**
     * Forget settings cache.
     *
     * @return bool
     */
    public function forget()
    {
        return $this->cache->forget(self::CACHE_KEY);
    }
}