<?php

namespace RabbitCMS\Settings;

use Illuminate\Support\Collection;

class Group extends Collection
{
    protected $name;

    protected $caption;

    protected $priority = 0;

    public function __construct(string $name, array $options = [])
    {
        parent::__construct();

        $this->name = $name;
        $this->caption = $options['caption'] ?? $name;
        $this->priority = $options['priority'] ?? $this->priority;
    }
}