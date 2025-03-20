<?php

namespace App\Casts;

use App\Enums\ParticipationTypeEnum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class ParticipationTypeEnumCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return $value;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if (!in_array($value, ParticipationTypeEnum::cases())) {
            throw new \InvalidArgumentException("مقدار نامعتبر برای ParticipationTypeEnum");
        }

        return $value;
    }
}