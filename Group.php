<?php

namespace RabbitCMS\Settings;

class Group
{
    protected $name;

    protected $caption;

    protected $priority = 0;

    public function __construct(string $name, array $options = [])
    {
        $this->name = $name;
        $this->caption = $options['caption'] ?? $name;
        $this->priority = $options['priority'] ?? $this->priority;
    }
}