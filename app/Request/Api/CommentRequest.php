<?php

declare(strict_types=1);

namespace App\Request\Api;

use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\Validation\Request\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $method = $this->getAttribute(Dispatched::class)->handler->callback[1] . 'Rules';
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        return [];
    }

    public function createRules(): array
    {
        return [
            'access_token' => 'required|string|alpha_num',
            'content'      => 'required|string',
            'content_type' => 'integer|min:1|max:4',
            'source_type'  => 'required|integer|min:1|max:3',
            'customer_id'  => 'required|min:1',
            'address'      => 'string|alpha',
            'lng'          => 'regex:/^\d{3}(\.\d{7})?$/gi',
            'lat'          => 'regex:/^\d{1,3}(\.\d{7})?$/gi',
            'pics'         => 'string',
            'videos'       => 'string',
            'audios'       => 'string',
            'id'           => 'integer',
            'type_tag'     => 'alpha',
            'parent_id'    => 'numeric',
            'device_token' => 'alpha_num'
        ];
    }
}
