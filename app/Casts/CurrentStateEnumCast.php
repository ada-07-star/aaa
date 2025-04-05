<?php

namespace App\Casts;

use App\Enums\CurrentStateEnum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class CurrentStateEnumCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return $value;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if (!in_array($value, CurrentStateEnum::cases())) {
            throw new \InvalidArgumentException("مقدار نامعتبر برای CurrentStateEnum");
        }

        return $value;
    }
}