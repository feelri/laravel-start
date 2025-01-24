<?php

namespace App\Traits\Model;

use Illuminate\Support\Str;

trait ModelTrait
{
    /**
     * 重写 table 方法，去除末尾的 s
     * @return string
     */
    public function getTable(): string
    {
        return $this->table ?? Str::snake(class_basename($this));
    }
}
