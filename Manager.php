<?php
namespace RabbitCMS\Settings;

use Carbon\Carbon;
use Countable;
use DateTimeInterface;
use Iterator;
use Pingpong\Modules\Collection;
use Pingpong\Modules\Module;
use Pingpong\Modules\Repository as ModulesRepository;

class Group implements Iterator, Countable
{
    protected $items = [];

    protected $caption;

    public function __construct($items = [], string $caption = '')
    {
        $this->caption = $caption;
        $this->items = $items;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        next($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->current() !== false;
    }

    /**
     * {@inheritdoc}
     * @return Meta
     */
    public function current()
    {
        return current($this->items) ?: null;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        reset($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->items);
    }
}

class Manager
{
    /**
     * @var ModulesRepository
     */
    protected $modules;

    /**
     * @var Collection|Group[]|Meta[][]
     */
    protected $groups;

    /**
     * @var Collection|Meta[]
     */
    protected $all;

    public function __construct(ModulesRepository $modules)
    {
        $this->modules = $modules;
        $this->groups = new Collection();
        $this->all = new Collection();
    }

    public function getValue(string $name, $value)
    {
        $meta = $this->getMetaByName($name);
        if ($value === null) {
            return $value;
        }

        switch ($meta['type']) {
            case 'int':
            case 'integer':
                return (int)$value;
            case 'real':
            case 'float':
            case 'double':
                return (float)$value;
            case 'string':
                return (string)$value;
            case 'bool':
            case 'boolean':
                return (bool)$value;
            case 'object':
                return json_decode($value, false);
            case 'array':
            case 'json':
                return json_decode($value, true);
            case 'collection':
                return new Collection(json_decode($value, false));
            case 'date':
            case 'datetime':
                return $this->asDateTime($value);
            case 'timestamp':
                return $this->asDateTime($value)->getTimestamp();
            default:
                return $value;
        }
    }

    /**
     * Return a timestamp as DateTime object.
     *
     * @param  mixed $value
     *
     * @return \Carbon\Carbon
     */
    protected function asDateTime($value)
    {
        // If this value is already a Carbon instance, we shall just return it as is.
        // This prevents us having to re-instantiate a Carbon instance when we know
        // it already is one, which wouldn't be fulfilled by the DateTime check.
        if ($value instanceof Carbon) {
            return $value;
        }

        // If the value is already a DateTime instance, we will just skip the rest of
        // these checks since they will be a waste of time, and hinder performance
        // when checking the field. We will just return the DateTime right away.
        if ($value instanceof DateTimeInterface) {
            return new Carbon(
                $value->format('Y-m-d H:i:s.u'), $value->getTimezone()
            );
        }

        // If this value is an integer, we will assume it is a UNIX timestamp's value
        // and format a Carbon object from this timestamp. This allows flexibility
        // when defining your date fields as they might be UNIX timestamps here.
        if (is_numeric($value)) {
            return Carbon::createFromTimestamp($value);
        }

        // If the value is in simply year, month, day format, we will instantiate the
        // Carbon instances from that format. Again, this provides for simple date
        // fields on the database, while still supporting Carbonized conversion.
        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value)) {
            return Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
        }

        // Finally, we will just assume this date is in the format used by default on
        // the database connection and use that format to create the Carbon object
        // that is returned back out to the developers after we convert it here.
        return Carbon::createFromFormat('Y-m-d H:i:s', $value);
    }

    /**
     * Get settings meta info by name.
     *
     * @param string $name
     *
     * @return Meta|null
     */
    public function getMetaByName(string $name)
    {
        return $this->all->get($name);
    }

    public function getMetaGroup(string $group) {

    }

    /**
     * Get all setting meta info.
     *
     * @return array
     */
    protected function getAllMeta(): array
    {
        if ($this->all === null) {
            $this->all = [];
            foreach ($this->modules->enabled() as $module) {
                /* @var Module $module */
                $path = $module->getExtraPath('Config/settings.php');
                if (is_file($path)) {
                    $config = require($path);
                    foreach ($config['settings'] as $name => $option) {
                        $meta = new Meta($name, (array)$option);
                        $this->addMeta($meta);
                    }
                }
            }
        }

        return $this->all;
    }

    /**
     * Add new settings meta info.
     *
     * @param Meta $meta
     */
    public function addMeta(Meta $meta)
    {
        $group = $meta->getGroup();
        if (!$this->groups->has($group)) {
            $this->groups->put($group, new Collection());
        }

        if ($this->all->has($meta->getName())) {
            throw new \RuntimeException('Settings with name `' . $meta->getName() . '` already exists .');
        }

        $this->all->put($meta->getName(), $meta);
        $this->groups->get($group)->put($meta->getName(), $meta);
    }
}