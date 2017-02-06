<?php
namespace RabbitCMS\Settings;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Collection;
use RabbitCMS\Modules\Managers\Modules;
use RabbitCMS\Modules\Module;


class Manager
{
    /**
     * @var Modules
     */
    protected $modules;

    /**
     * @var Collection|Group[]
     */
    protected $groups;

    /**
     * @var Collection|Meta[]
     */
    protected $all;

    public function __construct(Modules $modules)
    {
        $this->modules = $modules;
        $this->groups = new Collection();
        //$this->all = new Collection();
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
     * @param string $group
     *
     * @return Collection|Meta[]
     */
    public function getMetaGroup(string $group):Collection
    {
        return $this->all->filter(
            function (Meta $meta) use ($group) {
                return $meta->getGroup() === $group;
            }
        );
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroups()
    {
        $this->getAllMeta();

        return $this->groups;
    }

    /**
     * Get all setting meta info.
     *
     * @return array
     */
    protected function getAllMeta()
    {
        if ($this->all === null) {
            $this->all = new Collection();
            foreach ($this->modules->enabled() as $module) {
                /* @var Module $module */
                $path = $module->getPath('Config/settings.php');
                if (is_file($path)) {
                    $config = require($path);
                    if (array_key_exists('groups', $config)) {
                        foreach ((array)$config['groups'] as $name => $group) {
                            if (!is_array($group)) {
                                $group = ['caption' => $group];
                            }
                            if ($this->groups->has($name)) {
                                if (empty($group['slave'])) {
                                    throw  new \RuntimeException('Settings group `' . $name . '` already defined.');
                                }
                                continue;
                            }
                            $this->groups->put($name, new Group($name, $group));
                        }
                    }

                    if (array_key_exists('settings', $config)) {
                        foreach ((array)$config['settings'] as $name => $option) {
                            $meta = new Meta($name, (array)$option);
                            $this->addMeta($meta);
                        }
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
        //$this->groups->get($group);
        $this->groups->get($group)->put($meta->getName(), $meta);
    }
}