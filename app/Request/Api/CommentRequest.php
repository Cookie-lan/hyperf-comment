<?php

declare(strict_types = 1);

namespace App\Request\Api;

use App\Kernel\Request\AbstractRequest;

class CommentRequest extends AbstractRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function createRules(): array
    {
        return [
            'access_token'    => 'required|string|alpha_num',
            'content'         => 'required|string',
            'content_type'    => 'integer|min:1|max:4',
            'source_type'     => 'required|integer|min:1|max:3',
            'customer_id'     => 'required|integer|min:1',
            'address'         => 'string|alpha',
            'lng'             => 'regex:/^\d{1,3}(\.\d{7})?$/',
            'lat'             => 'regex:/^\d{1,3}(\.\d{7})?$/',
            'pics'            => 'string|json',
            'videos'          => 'string|json',
            'audios'          => 'string|json',
            'target_id'       => 'required|integer|min:0',
            'target_type_tag' => 'required|alpha',
            'parent_id'       => 'numeric|min:1',
            'device_token'    => 'alpha_num'
        ];
    }
}
