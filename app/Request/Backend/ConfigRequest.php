<?php

declare(strict_types = 1);

namespace App\Request\Backend;

use App\Kernel\Request\AbstractRequest;

class ConfigRequest extends AbstractRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function createOrUpdateRules()
    {
        return [
            'access_token'              => 'required|string|alpha_num',
            'source_type'               => 'required|integer|min:1|max:3',
            'customer_id'               => 'required|integer|min:1',
            'min_length'                => 'integer|min:1',
            'max_length'                => 'integer|min:1',
            'is_default_audit'          => 'boolean',
            'is_open_member_delete'     => 'boolean',
            'create_interval'           => 'integer|min:0',
            'is_open_building'          => 'boolean',
            'max_pic_num'               => 'integer|min:1',
            'is_open_virtual_like_rule' => 'boolean',
            'virtual_like_rule'         => 'string|json',
        ];
    }
}
