<?php

namespace RabbitCMS\Settings\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Settings.
 *
 * @property-read int    $id
 * @property-read string $name
 * @property             $value
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class Settings extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'value'];
}