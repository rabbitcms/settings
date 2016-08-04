<?php
/**
 * This file is part of NAS-Broker projects
 *
 * @author lnkvisitor
 * @since  03.08.16
 */

namespace RabbitCMS\Settings;


class Meta
{
    const GROUP_OTHER  = 'other';
    const GROUP_SYSTEM = 'system';

    const TYPE_STRING  = 'string';
    const TYPE_JSON    = 'json';
    const TYPE_INTEGER = 'integer';
    const TYPE_ENUM    = 'enum';

    /**
     * @var string
     */
    protected $group = Meta::GROUP_OTHER;

    /**
     * @var string
     */
    protected $caption;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var array
     */
    protected $values = [];

    /**
     * @var array
     */
    protected $options;

    public function __construct(string $name, array $options)
    {
        $this->name = array_key_exists('name', $options) ? $options['name'] : $name;

        if (array_key_exists('group', $options)) {
            $this->group = $options['group'];
            unset($options['group']);
        } else if (count($n = explode('.', $name, 2)) === 2) {
            $this->group = $n[0];
        }

        $this->caption = array_key_exists('caption', $options) ? $options['caption'] : $name;
        $this->type = array_key_exists('type', $options) ? $options['type'] : self::TYPE_STRING;
        unset($options['type'], $options['caption']);

        if (array_key_exists('value', $options)) {
            $this->value = $options['value'];
            unset($options['value']);
        }

        if ($this->type === self::TYPE_ENUM && array_key_exists('values', $options)) {
            $this->values = $options['values'];
            unset($options['values']);
        }

        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getType():string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getGroup():string
    {
        return $this->group;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get available values.
     *
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * Get default value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get caption value.
     *
     * @return string
     */
    public function getCaption(): string
    {
        return $this->caption;
    }

    /**
     * Get settings options.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}