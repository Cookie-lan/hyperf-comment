<?php

declare(strict_types=1);

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

    public function createRules()
    {
        return [
            'config' => 'required',
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
