<?php

declare(strict_types = 1);

namespace App\Kernel\Request;


use http\Exception\InvalidArgumentException;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\Validation\Request\FormRequest;

class AbstractRequest extends FormRequest
{
    /**
     * 获取调用的方法名
     *
     * @return mixed|string
     */
    protected function getCalledMethod()
    {
        $callback = $this->getAttribute(Dispatched::class)->handler->callback;

        if (is_array($callback) && isset($callback[1])) {
            return $callback[1];
        } elseif (is_string($callback) && strpos($callback, '@') !== false) {
            return explode('@', $callback)[1];
        } elseif (is_string($callback) && strpos($callback, '::') !== false) {
            return explode('::', $callback)[1];
        } else {
            throw new InvalidArgumentException('undefined called method', 0);
        }
    }

    /**
     * 获取规则方法名
     *
     * @return string
     */
    public function getRulesMethod(): string
    {
        return $this->getCalledMethod() . 'Rules';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $method = $this->getRulesMethod();
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        return [];
    }
}