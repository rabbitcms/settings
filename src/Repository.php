<?php

namespace RabbitCMS\Settings;

use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Config\Repository as ConfigRepository;

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
     * @param Manager         $manager
     */
    public function __construct(CacheRepository $cache, Manager $manager)
    {
        $this->cache = $cache;

        parent::__construct(
            $this->cache->rememberForever(
                self::CACHE_KEY,
                function () use ($manager) {
                    return Entities\Settings::all()->pluck('value', 'name')->all();
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