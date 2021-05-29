<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 */
class CommentModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comment';

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
        'id'                => 'integer',
        'uni_id'            => 'real',
        'parent_id'         => 'real',
        'source_type'       => 'integer',
        'content_type'      => 'integer',
        'founder_id'        => 'integer',
        'founder_type'      => 'integer',
        'customer_id'       => 'integer',
        'status'            => 'integer',
        'ip'                => 'integer',
        'lng'               => 'decimal:7',
        'lat'               => 'decimal:7',
        'like_num'          => 'integer',
        'v_like_num'        => 'integer',
        'n_like_num'        => 'integer',
        'target_id'         => 'integer',
        'device_type'       => 'integer',
        'main_comment_id'   => 'real',
        'dialogue_id'       => 'real',
        'be_commenter_id'   => 'integer',
        'be_commenter_type' => 'integer',
        'is_top'            => 'boolean',
        'create_time'       => 'integer',
        'update_time'       => 'integer',
    ];

    public $timestamps = false;
}