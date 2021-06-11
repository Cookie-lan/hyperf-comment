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

    /**
     * 评论创建表单验证规则
     *
     * @return array
     */
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

    /**
     * 获取个人评论表单验证规则
     *
     * @return array
     */
    public function getMyCommentsRules(): array
    {
        return [
            'access_token' => 'required|string|alpha_num',
            'source_type'  => 'required|integer|min:1|max:3',
            'customer_id'  => 'required|integer|min:1',
            'page'         => 'integer|min:1',
            'count'        => 'integer|min:1|max:200',
        ];
    }

    /**
     * 获取他人评论表单验证规则
     *
     * @return array
     */
    public function getOtherCommentsRules(): array
    {
        return [
            'access_token' => 'required|string|alpha_num',
            'source_type'  => 'required|integer|min:1|max:3',
            'customer_id'  => 'required|integer|min:1',
            'member_id'    => 'required|integer|min:1',
            'page'         => 'integer|min:1',
            'count'        => 'integer|min:1|max:200',
        ];
    }

    /**
     * 获取内容的评论列表表单验证规则
     *
     * @return array
     */
    public function listRules(): array
    {
        return [
            'source_type' => 'required|integer|min:1|max:3',
            'customer_id' => 'required|integer|min:1',
            'page'        => 'integer|min:1',
            'count'       => 'integer|min:1|max:200',
            'id'          => 'required|integer|min:1',
            'type_tag'    => 'required|string|alpha',
        ];
    }
}
