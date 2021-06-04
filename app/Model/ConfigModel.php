<?php

declare (strict_types = 1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 */
class ConfigModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'config';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'          => 'integer',
        'customer_id' => 'integer',
        'source_type' => 'integer',
        'config'      => 'json',
        'create_time' => 'integer',
        'update_time' => 'integer',
    ];

    public $timestamps = false;
}